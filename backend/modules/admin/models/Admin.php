<?php

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
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

    //是否重置过密码
    const RESET_PASSWORD_NO = 0;//未重置过
    const RESET_PASSWORD_YES = 1;//已重置过

    static public $_status = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_FORBIDDEN => '禁止',
    ];

    static public $users = [];

    public $password;
    public $privilege;
    public $sg_id;
    public $role_info;
    public $new_passwd;
    public $new_passwd_repeat;

    public $_user;

    static public function tableName()
    {
        return 'admin_user';
    }

    public function scenarios()
    {
        return [
            'changePasswd' => ['account', 'reset_password', 'password', 'new_passwd', 'new_passwd_repeat'],
            'default' => ['account', 'reset_password', 'password', 'createtime', 'mobile_phone'],
            'update' => ['account', 'reset_password', 'mobile_phone', 'username', 'access_token', 'status', 'password', 'sg_id'],
            'create' => ['account', 'reset_password', 'mobile_phone', 'username', 'access_token', 'status', 'password', 'sg_id'],
        ];
    }

    public function rules()
    {
        return [
            ['account', 'required', 'message' => '请输入账号', 'on' => 'update'],
            ['mobile_phone', 'required', 'message' => '手机号不能为空', 'on' => 'update'],
            ['mobile_phone', 'checkUnifyPhone', 'message' => '手机号不能为空', 'on' => 'create'],
            ['username', 'required', 'message' => '请输入密码', 'on' => 'update'],
            ['password', 'validatePassword', 'message' => '账号或密码错误', 'on' => ['login', 'changePasswd']],
            ['new_passwd', 'validateNewPasswordSame', 'message' => '新密码不能与旧密码一样', 'on' => ['changePasswd']],
            ['new_passwd', 'validateNewPassword', 'message' => '新密码输入不一致', 'on' => ['changePasswd']],
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
    public function validatePassword() {
        if ($this->hasErrors()) {
            return false;
        }

        if ($this->passwd != md5(md5($this->password) . $this->salt)) {
            $this->addError('password', Lang::getMsg(Lang::ERR_LOGIN_FAIL));
        }
    }

    //校验两次输入的新密码
    public function validateNewPassword()
    {
        if ($this->hasErrors()) {
            return false;
        }

        if ($this->new_passwd != $this->new_passwd_repeat) {
            $this->addError('password', '新密码两次输入不一致');
        }
    }

    //校验新密码是否跟老密码一样
    public function validateNewPasswordSame()
    {
        if ($this->hasErrors())
            return false;

        if ($this->new_passwd == $this->password)
            $this->addError('new_passwd', '新密码不能与旧密码一样');
    }

    public function insert($runValidation = true, $attributes = null) {
        //检查账号是否已存在
        $adminOld = Admin::findOne(['account' => $this->account]);
        if (empty($adminOld)) {
            $newSalt = Helpers::getStrBylength(4);
            $this->salt = $newSalt;
            $this->passwd = md5(md5($this->passwd) . $newSalt);
            $ad_uid = parent::insert($runValidation, $attributes);
            if (!$ad_uid) {
                return false;
            }
            $ad_uid = $this->ad_uid;
        } else {
            $ad_uid = $adminOld->ad_uid;
        }
        //检查系统账号关系存在
        $systemId = Helpers::getRequestParam('sid');
        $systemAdmin = SystemUser::findOne(['systems_id' => $systemId, 'ad_uid' => $ad_uid]);
        if (empty($systemAdmin)) {
            $systemAdmin = new SystemUser();
            $systemAdmin->ad_uid = $ad_uid;
            $systemAdmin->systems_id = $systemId;
            $systemAdmin->save();
        }

        //添加角色
        if (isset($this->sg_id)) {
            $transaction = \Yii::$app->db->beginTransaction();
            $sg_ids = explode(',', $this->sg_id);
            $systemUserGroup = new SystemUserGroup();
            foreach ($sg_ids as $sg_id) {
                $systemUserGroupClone = clone $systemUserGroup;
                $systemUserGroupClone->ad_uid = $ad_uid;
                $systemUserGroupClone->sg_id = intval($sg_id);
                $systemUserGroupClone->save();
            }
            $transaction->commit();
        }

        return true;
    }

    public function update($runValidation = true, $attributes = null) {
        if (!empty($this->passwd) && $this->passwd != $this->getOldAttribute('passwd')) {
            $newSalt = Helpers::getStrBylength(4);
            $this->salt = $newSalt;
            $this->passwd = md5(md5($this->passwd) . $newSalt);
            //修改了密码更新token
            //$this->access_token = Helpers::generateAccessToken($this->account);
            //$this->access_token_expire = time() + self::TOKEN_EXPIRE_DURATION;
        } else {
            $this->salt = $this->getOldAttribute('salt');
            $this->passwd = $this->getOldAttribute('passwd');
        }
        if (!parent::update($runValidation, $attributes)) {
            return false;
        }
        $sg_ids = empty($this->sg_id) ? [] : explode(',', strval($this->sg_id));
        $oldSg_id = ArrayHelper::getColumn($this->systemGroup, 'sg_id');
        if (!empty($sg_ids) && (array_diff($sg_ids, $oldSg_id) || array_diff($oldSg_id, $sg_ids))) {
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
        $this->setAttributes(Helpers::getRequestParams());

        //修改了密码也要更改token
        //$this->access_token = Helpers::generateAccessToken($this->account);
        //$this->access_token_expire = time() + self::TOKEN_EXPIRE_DURATION;
        if ($this->validate()) {
            $this->reset_password = self::RESET_PASSWORD_YES;
            $this->passwd = $this->new_passwd;
            $this->save(false);
            return true;
        } else {
            $errors = $this->getErrors();
            $error  = array_shift($errors);
            \Yii::error(var_export($error, true));
            throw new CustomException($error[0]);
        }
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
        $this->getAllPrivilege();
        if ($priv_type === '*') {
            return ArrayHelper::merge($this->privilege[SystemPriv::PRIVILEGE_TYPE_BUSINESS], $this->privilege[SystemPriv::PRIVILEGE_TYPE_SETTING]);
        } else {
            return $this->privilege[$priv_type];
        }
    }

    public function getAllPrivilege()
    {
        $gameId = intval(Helpers::getRequestParam('game_id'));

        \Yii::debug('user Payload is:' . var_export(\Yii::$app->user->identity->jwt->Payload, true));
        if (empty($this->privilege)) {
            // 权限拉取
            // 1. 首先过滤出游戏对应的所有角色，如果没有对应角色游戏或角色游戏对应的是普通权限就是获取通用权限，否则取专有权限
            $privilegeIds = [];
            foreach ($this->role_info as $roleId => $gameIds) {
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

            $privileges[SystemPriv::PRIVILEGE_TYPE_BUSINESS] = SystemPriv::find()->where(['sp_id' => $privilegeIds, 'sp_set_or_business' => SystemPriv::PRIVILEGE_TYPE_BUSINESS])->indexBy('sp_id')->asArray()->all();
            $privileges[SystemPriv::PRIVILEGE_TYPE_SETTING] = SystemPriv::find()->where(['sp_id' => $privilegeIds, 'sp_set_or_business' => SystemPriv::PRIVILEGE_TYPE_SETTING])->indexBy('sp_id')->asArray()->all();

            $this->privilege = $privileges;
        }
        return $this->privilege;
    }

    /**
     * 授权后台时，校验是否有维护权限
     */
    public function validateIsMaintain()
    {
        $this->role_info = $this->getRoleInfo();
        $setttingPrivileges = $this->getPrivilege(SystemPriv::PRIVILEGE_TYPE_SETTING);
        if (empty($setttingPrivileges))
            throw new CustomException('对不起，您没有维护权限');
    }

    public function validateGame($gameId, $gameIds)
    {
        \Yii::debug('gameId:' . $gameId);
        if (!in_array($gameId, $gameIds))
            throw new CustomException('你没有此游戏的管理权限,请联系管理员');
    }

    /**
     * 获取账号对应的角色和管理的游戏
     * @return array|null
     */
    public function getRoleInfo()
    {
        $roles = array_flip(ArrayHelper::getColumn(ArrayHelper::toArray($this->sgIds), 'sg_id'));
        foreach ($roles as $key => $role) {
            $roles[$key] = ArrayHelper::getColumn(SystemGroupGame::getGamesByGroupId($key), 'game_id', false);
        }
        return $roles;
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

    public function getGroupRelations()
    {
        return $this->hasMany(SystemUserGroup::class, ['ad_uid' => 'ad_uid']);
    }

    public function getSystemRelations()
    {
        return $this->hasMany(SystemUser::class, ['ad_uid' => 'ad_uid']);
    }

    public function getSystems()
    {
        return $this->hasMany(System::class, ['systems_id' => 'systems_id'])->viaTable(SystemUser::tableName(), ['ad_uid' => 'ad_uid']);
    }
}