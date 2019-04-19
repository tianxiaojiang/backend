<?php

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use Backend\modules\admin\services\admin\AuthTypeService;
use Backend\modules\admin\services\admin\NewAdminInfoFill;
use Backend\modules\admin\services\SystemAdminService;
use Backend\modules\admin\services\SystemService;
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

    const AUTH_TYPE_DOMAIN = 0;//域账号认证
    const AUTH_TYPE_PASSWORD = 1;//密码认证
    const AUTH_TYPE_CHANGZHOU = 2;//常州第三方认证

    public static $_auth_types = [
        self::AUTH_TYPE_DOMAIN => '域账号',
        self::AUTH_TYPE_PASSWORD => '普通账号',
        self::AUTH_TYPE_CHANGZHOU => '常州账号',
    ];

    static public $users = [];

    public $password;
    public $privilege = [];
    public $sg_id;
    public $role_info;
    public $new_passwd;
    public $new_passwd_repeat;
    public $system_id = null;

    public $_user;

    static public function tableName()
    {
        return 'admin_user';
    }

    public function scenarios()
    {
        return [
            'changePasswd' => ['account', 'auth_type', 'staff_number', 'reset_password', 'password', 'new_passwd', 'new_passwd_repeat'],
            'updateProfile' => ['username', 'mobile_phone'],
            'default' => ['account', 'auth_type', 'staff_number', 'password_algorithm_system', 'reset_password', 'password', 'createtime', 'mobile_phone'],
            'update' => ['account', 'auth_type', 'staff_number', 'password_algorithm_system', 'reset_password', 'mobile_phone', 'username', 'access_token', 'status', 'password', 'sg_id'],
            'create' => ['account', 'auth_type', 'staff_number', 'password_algorithm_system', 'reset_password', 'mobile_phone', 'username', 'access_token', 'status', 'password', 'sg_id'],
        ];
    }

    public function rules()
    {
        return [
//            ['account', 'required', 'message' => '请输入账号', 'on' => 'update'],
//            ['mobile_phone', 'required', 'message' => '手机号不能为空', 'on' => 'update'],
//            ['mobile_phone', 'checkUnifyPhone', 'message' => '手机号不能为空', 'on' => 'create'],
//            ['username', 'required', 'message' => '请输入密码', 'on' => 'update'],
            ['password', 'validatePassword', 'message' => '账号或密码错误', 'on' => ['login', 'changePasswd']],
            ['new_passwd', 'validateNewPasswordSame', 'message' => '新密码不能与旧密码一样', 'on' => ['changePasswd']],
            ['new_passwd', 'validateNewPassword', 'message' => '新密码输入不一致', 'on' => ['changePasswd']],
            ['password', 'validateAccountType', 'message' => '只有普通账号可以修改密码', 'on' => ['changePasswd']],
            ['username', 'required', 'message' => '姓名不能为空', 'on' => ['updateProfile']],
            ['mobile_phone', 'match', 'pattern' => '/^1\d{10}$/', 'message' => '手机号格式错误', 'on' => ['updateProfile']],
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

        $authType = new AuthTypeService($this);
        if (!$authType->validatePassword()) {
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

    //校验是否普通账号
    public function validateAccountType()
    {
        if ($this->hasErrors()) {
            return false;
        }

        if ($this->auth_type != self::AUTH_TYPE_PASSWORD) {
            $this->addError('password', '只有普通账号可以修改密码');
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
        //salt必须要有，后面生成jwt的签名时候依赖于salt
        $newSalt = Helpers::getStrBylength(4);
        $this->salt = $newSalt;
        //如果是密码普通账号，填充密码
        if ($this->auth_type == Admin::AUTH_TYPE_PASSWORD && !empty($this->password)) {
            $this->password_algorithm_system = 1;
            $this->passwd = md5(md5($this->password) . $this->salt);
        }
        parent::insert($runValidation, $attributes);

        return true;
    }

    public function update($runValidation = true, $attributes = null) {
        if (!empty($this->password)) {
            $newSalt = Helpers::getStrBylength(4);
            $this->salt = $newSalt;
            $this->passwd = md5(md5($this->password) . $newSalt);
        } else {
            $this->salt = $this->getOldAttribute('salt');
            $this->passwd = $this->getOldAttribute('passwd');
        }
        if (!parent::update($runValidation, $attributes)) {
            return false;
        }

        if (empty(\Yii::$app->user->identity)) {
            return true;
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

        if ($this->validate()) {
            $this->reset_password = self::RESET_PASSWORD_YES;
            $this->password = $this->new_passwd;
            $this->save(false);
            return true;
        } else {
            $errors = $this->getErrors();
            $error  = array_shift($errors);
            \Yii::error(var_export($error, true));
            throw new CustomException($error[0]);
        }
    }

    /**
     * update user passwd
     */
    public function updateProfile() {
        $this->setScenario('updateProfile');
        $this->setAttributes(Helpers::getRequestParams());

        if ($this->validate()) {
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
     * 返回自己所有拥有的gameIds
     * @param int $privilegeType
     * @return array
     */
    public function getMyGameIds($privilegeType = SystemPriv::PRIVILEGE_TYPE_BUSINESS)
    {
        $systemPrivileTypeMapRolePrivilegeLevel = [
            '*' => 0,
            SystemPriv::PRIVILEGE_TYPE_BUSINESS => SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT,
            SystemPriv::PRIVILEGE_TYPE_SETTING => SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN,
        ];

        $roles = $this->getRoles();

        $gameIds = [];
        foreach ($roles as $role) {
            //如果角色对应的权限级别跟请求一致，则gameId有效
            if (($systemPrivileTypeMapRolePrivilegeLevel[$privilegeType] & $role->privilege_level) == $systemPrivileTypeMapRolePrivilegeLevel[$privilegeType]) {
                $gameIds = array_merge($gameIds, ArrayHelper::getColumn($role->gameIds, 'game_id'));
            }
        }

        return $gameIds;
    }

    /**
     * 当前用户在某个游戏下的所有角色id
     * @param $gameId
     * @return array
     */
    public function getMyRoleIdsOnGame($gameId)
    {
        $gameId = intval($gameId);
        $roleIds = [];
        $roles = SystemGroup::find()->all();
        foreach ($roles as $role) {
            if ($gameId === -1 || in_array($gameId, ArrayHelper::getColumn($role->gameIds, 'game_id'))) {
                array_push($roleIds, $role->sg_id);
            }
        }
        return $roleIds;
    }

    /**
     * 获取游戏下对应的所有的角色的权限
     * @param $gameId
     * @param int $privilegeType
     */
    public function getPrivilegeIdsOnGame($gameId, $privilegeType = SystemPriv::PRIVILEGE_TYPE_BUSINESS)
    {
        $systemPrivileTypeMapRolePrivilegeLevel = [
            '*' => 0,
            SystemPriv::PRIVILEGE_TYPE_BUSINESS => SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT,
            SystemPriv::PRIVILEGE_TYPE_SETTING => SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN,
        ];

        $roles = $this->getRoles();

        $roleIds = [];
        foreach ($roles as $role) {
            //如果角色对应的权限级别跟请求一致，则gameId有效
            if (($systemPrivileTypeMapRolePrivilegeLevel[$privilegeType] & $role->privilege_level) == $systemPrivileTypeMapRolePrivilegeLevel[$privilegeType] && in_array($gameId, ArrayHelper::getColumn($role->gameIds, 'game_id'))) {
                array_push($roleIds, $role->sg_id);
            }
        }

        $privilegeIds = SystemGroupPriv::find()->select(['sp_id'])->where(['sg_id' => $roleIds])->all();


        return ArrayHelper::getColumn($privilegeIds, 'sp_id');
    }


    /**
     * 获取游戏下对应的所有的角色的权限
     * @param $gameId
     * @param int $privilegeType
     */
    public function getPrivilegesOnGame($gameId, $privilegeType = SystemPriv::PRIVILEGE_TYPE_BUSINESS)
    {
        $privilegeIds = $this->getPrivilegeIdsOnGame($gameId, $privilegeType);
        $where = $privilegeType === '*' ? ['sp_id' => $privilegeIds] : ['sp_id' => $privilegeIds, 'sp_set_or_business' => $privilegeType];
        $privileges = SystemPriv::find()->select(['sp_id', 'sm_id', 'sp_label', 'sp_module', 'sp_controller', 'sp_action', 'sp_parent_id', 'sp_set_or_business'])->where($where)->asArray()->all();

        return $privileges;
    }

    /**
     * 获取管理员当前请求时的权限
     * @param $gameId
     * @param string $privilegeType
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPrivileges($gameId, $privilegeType = '*')
    {
        if (empty($this->privilege[$privilegeType])) {
            //我所选游戏拥有的所有权限
            $currentSystem = SystemService::getCurrentSystem();
            $isMaintain = Helpers::getRequestParam('isMaintain');
            if (\Yii::$app->user->identity->ad_uid === $currentSystem->dev_account && !empty($isMaintain)) {//管理员取后台的所有权限
                $this->privilege[$privilegeType] = SystemPriv::getAll();
            } else {
                $this->privilege[$privilegeType] = \Yii::$app->user->identity->getPrivilegesOnGame($gameId, $privilegeType);
            }
        }

        return $this->privilege[$privilegeType];
    }

    public function validateGame($gameId, $gameIds)
    {
        \Yii::error('gameId:' . $gameId);
        \Yii::error('gameId:' . var_export($gameIds, true));
        if (!in_array($gameId, $gameIds))
            throw new CustomException('你没有此游戏的管理权限,请联系管理员');
    }

    /**
     * 获取账号对应的角色和管理的游戏
     * @return array|null
     */
    public function getRoleInfo()
    {
        $roles = [];
        $dataRoles = $this->getRoles();
        foreach ($dataRoles as $systemGroup) {
            $roles[$systemGroup->sg_id] = ArrayHelper::getColumn($systemGroup->gameIds, 'game_id');
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

    public function getRoles()
    {
        if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
            return empty($this->systemAdmin) ? [] : $this->systemAdmin->systemGroup;
        } else {
            return $this->systemGroup;
        }
    }

    /**
     * get groups
     */
    public function getSystemGroup() {
        $s = Helpers::getRequestParam('sid');
        return $this->hasMany(SystemGroup::class, ['sg_id' => 'sg_id'])->viaTable('s'. $s .'_system_user_group', ['ad_uid' => 'ad_uid']);
    }

    /**
     * get user
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        $s = Helpers::getRequestParam('sid');
        return $this->hasMany(SystemGroupGame::class, ['sg_id' => 'sg_id'])->viaTable('s'. $s .'_system_user_group', ['ad_uid' => 'ad_uid']);
    }

    public function getSystemAdmin() {
        return $this->hasOne(SystemAdmin::class, ['ad_uid' => 'ad_uid']);
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