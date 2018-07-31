<?php

namespace Backend\modules\admin\models;

use Backend\helpers\Helpers;
use yii\helpers\ArrayHelper;
use Backend\modules\common\models\BaseModel;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:55
 */
class Admin extends BaseModel implements \yii\web\IdentityInterface
{
    const TOKEN_EXPIRE_DURATION = 60 * 60 * 24 * 7;//token有效期7天
    const STATUS_NORMAL     = 0;
    const STATUS_FORBIDDEN  = 1;

    static public $_status = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_FORBIDDEN => '禁止',
    ];

    static public $users = [];

    public $privilege;

    public $_user;

    static public function tableName()
    {
        return 'admin_user';
    }

    public function scenarios()
    {
        return [
            'changePasswd' => ['account', 'passwd', 'new_passwd', 'new_passwd_repeat'],
            'default' => ['account', 'passwd', 'createtime', 'mobile_phone'],
            'update' => ['account', 'mobile_phone', 'username', 'access_token', 'status', 'passwd', 'sg_id'],
            'create' => ['account', 'mobile_phone', 'username', 'access_token', 'status', 'passwd', 'sg_id'],
        ];
    }

    public function rules()
    {
        return [
            ['account', 'required', 'message' => '请输入账号', 'on' => 'update'],
            ['mobile_phone', 'required', 'message' => '手机号不能为空', 'on' => 'update'],
            ['mobile_phone', 'checkUnifyPhone', 'message' => '手机号不能为空', 'on' => 'create'],
            ['username', 'required', 'message' => '请输入密码', 'on' => 'update'],
            //['passwd', 'validatePassword', 'message' => '账号或密码错误', 'on' => ['login', 'changePasswd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($uid)
    {
        return self::findOne(['ad_uid' => $uid]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->ad_uid;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function getUser()
    {
        if (empty($this->_user)) {
            $this->_user = self::findOne(['account' => $this->account]);
        }
        return $this->_user;
    }

    //校验密码
    private function validatePassword() {
        if ($this->hasErrors()) {
            return false;
        }

        $user = Admin::getUser($this->account);
        if (empty($user) || $user->passwd != md5(md5($this->passwd) . $user->salt)) {
            return false;
        }

        return true;
    }

    public function insert($runValidation = true, $attributes = null) {
        $newSalt = Helpers::getStrBylength();
        $this->salt = $newSalt;
        $this->passwd = md5(md5($this->passwd) . $newSalt);
        $ad_uid = parent::insert($runValidation, $attributes);
        if (!$ad_uid) {
            return false;
        }
        if (isset($this->sg_id)) {
            $transaction = \Yii::$app->db->beginTransaction();
            $sg_ids = explode(',', $this->sg_id);
            $systemUserGroup = new SystemUserGroup();
            foreach ($sg_ids as $sg_id) {
                $systemUserGroupClone = clone $systemUserGroup;
                $systemUserGroupClone->ad_uid = $this->ad_uid;
                $systemUserGroupClone->sg_id = intval($sg_id);
                $systemUserGroupClone->save();
            }
            $transaction->commit();
        }

        return true;
    }

    public function update($runValidation = true, $attributes = null) {
        if (!empty($this->passwd)) {
            $newSalt = Helpers::getStrBylength();
            $this->salt = $newSalt;
            $this->passwd = md5(md5($this->passwd) . $newSalt);
            //修改了密码更新token
            $this->access_token = Helpers::generateAccessToken($this->account);
            $this->access_token_expire = time() + self::TOKEN_EXPIRE_DURATION;
        } else {
            $this->salt = $this->getOldAttribute('salt');
            $this->passwd = $this->getOldAttribute('passwd');
        }
        if (!parent::update($runValidation, $attributes)) {
            return false;
        }
        if (isset($this->sg_id) && ($this->sg_id != $this->getOldAttribute('sg_id'))) {
            $transaction = \Yii::$app->db->beginTransaction();
            SystemUserGroup::deleteAll(['ad_uid' => $this->ad_uid]);
            $sg_ids = explode(',', $this->sg_id);
            $systemUserGroup = new SystemUserGroup();
            foreach ($sg_ids as $sg_id) {
                $systemUserGroupClone = clone $systemUserGroup;
                $systemUserGroupClone->ad_uid = $this->ad_uid;
                $systemUserGroupClone->sg_id = $sg_id;
                $systemUserGroupClone->save();
            }
            $transaction->commit();
        }
        return true;
    }

    /**
     * validate status
     */
    public function validateStatus() {
        if ($this->hasErrors()) {
            return false;
        }
        $user = $this->getUser();
        if (empty($user) || $user->status == self::STATUS_FORBIDDEN) {
            $this->addError('account', '账号已被禁，请联系管理员！');
        }
    }

    /**
     * update user passwd
     */
    public function updatePasswd() {
        $this->setScenario('changePasswd');
        $this->setAttributes(\Yii::$app->request->post());

        //修改了密码也要更改token
        $this->access_token = Helpers::generateAccessToken($this->account);
        $this->access_token_expire = time() + self::TOKEN_EXPIRE_DURATION;
        if ($this->validate()) {
            $user = $this->getUser();
            $newSalt = Helpers::getStrBylength(4);
            $user->passwd = md5(md5($this->new_passwd) . $newSalt);
            $user->salt = $newSalt;
            $user->save();
            return true;
        }
        return false;
    }

    public function checkUnifyPhone()
    {
        if ($this->hasErrors()) {
            return false;
        }
        $account = self::findOne(['mobile_phone' => $this->mobile_phone]);
        if (!empty($account)) {
            $this->addError('mobile_phone', '手机号已存在，不能重复添加');
        }
    }

    /**
     * get privilege
     */
    public function getPrivilege()
    {
        if (empty($this->privilege)) {
            $systemGroupIds = ArrayHelper::getColumn(SystemUserGroup::find()->where(['ad_uid' => $this->getId()])->asArray()->all(), 'sg_id');

            $systemGroupPrivs = SystemGroup::find()->where(['sg_id' => $systemGroupIds])->with('privilege')->asArray()->all();
            $privilege = [];
            foreach ($systemGroupPrivs as $val)
            {
                $privilege = array_merge($privilege, $val['privilege']);
            }
            $this->privilege = $privilege;
        }
        return $this->privilege;
    }

    /**
     * get user role names
     */
    public function getRoleName() {
        $gids = ArrayHelper::getColumn(SystemUserGroup::find()->where(['ad_uid' => $this->getId()])->asArray()->all(), 'sg_id');
        $gName = ArrayHelper::getColumn(SystemGroup::find()->where(['sg_id' => $gids])->asArray()->all(), 'sg_name');
        return implode('、', $gName);
    }

    /**
     * get groups
     */
    public function getSystemGroup() {
        return $this->hasMany(SystemGroup::className(), ['sg_id' => 'sg_id'])->viaTable('system_user_group', ['ad_uid' => 'ad_uid']);
    }
}