<?php

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
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
    public $sg_id;

    public $_user;

    public static $tableIndex = 0;

    static public function tableName()
    {
        return 's' . Helpers::getRequestParam('sid') . '_admin_user';
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
        $newSalt = Helpers::getStrBylength(4);
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
            $newSalt = Helpers::getStrBylength(4);
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
        $sg_ids = explode(',', $this->sg_id);
        $oldSg_id = ArrayHelper::getColumn($this->systemGroup, 'sg_id');
        if (!array_diff($sg_ids, $oldSg_id) || !array_diff($oldSg_id, $sg_ids)) {
            $transaction = \Yii::$app->db->beginTransaction();
            SystemUserGroup::deleteAll(['ad_uid' => $this->ad_uid]);
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
    public function getPrivilege($priv_type = SystemPriv::PRIVILEGE_TYPE_BUSINESS)
    {
        $gameId = intval(Helpers::getRequestParam('game_id'));

        \Yii::info('user Payload is:' . var_export(\Yii::$app->user->identity->jwt->Payload, true));
        if (empty($this->privilege)) {
            // 权限拉取
            $roleInfo = json_decode(\Yii::$app->user->identity->jwt->Payload['role_info']->getValue(), true);
            // 1. 首先过滤出游戏对应的所有角色，如果没有对应角色游戏或角色游戏对应的是普通权限就是获取通用权限，否则取专有权限
            $privilegeIds = [];
            \Yii::info('user roleInfo is:' . var_export($roleInfo, true));
            foreach ($roleInfo as $roleId => $gameIds) {
                //如果对应角色权限里没有管理gameId且角色限制了游戏，continue
                if (!in_array('*', $gameIds) and !in_array($gameId, $gameIds)) {
                    continue;
                }

                $systemGroupGame = SystemGroupGame::getOneByRoleAndGame($roleId, $gameId);

                if(in_array('*', $gameIds) or $systemGroupGame->is_proprietary_priv == SystemGroupGame::GAME_TYPE_PRVI_COMMON) {
                    //角色通用权限
                    $groupGamePrivileges = SystemGroupPriv::find()->select('sp_id')->where(['sg_id' => $roleId])->asArray()->all();
                } elseif ($systemGroupGame->is_proprietary_priv == SystemGroupGame::GAME_TYPE_PRVI_PROPRIETARY) {
                    //游戏专有权限
                    $groupGamePrivileges = SystemGroupGamePriv::find()->select('sp_id')->where([
                        'game_id' => $gameId,
                        'sg_id' => $roleId])->asArray()->all();
                }
                $groupGamePrivileges = ArrayHelper::getColumn($groupGamePrivileges, 'sp_id');

                //合并所有的权限id
                $privilegeIds = array_merge($privilegeIds, $groupGamePrivileges);
            }

            // 2. 获取所有权限
            $privileges = SystemPriv::find()->where(['sp_id' => $privilegeIds, 'sp_set_or_business' => $priv_type])->asArray()->all();

            $this->privilege = $privileges;
        }
        return $this->privilege;
    }

    public function validateGame($gameId, $gameIds)
    {
        \Yii::info('gameId:' . $gameId);
        \Yii::info(var_export($gameIds, true));
        if (!in_array('*', $gameIds) and !in_array($gameId, $gameIds))
            throw new CustomException('你没有此游戏的管理权限,请联系管理员');
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
        $s = Helpers::getRequestParam('sid');
        return $this->hasMany(SystemGroup::class, ['sg_id' => 'sg_id'])->viaTable('s'. $s .'_system_user_group', ['ad_uid' => 'ad_uid']);
    }
}