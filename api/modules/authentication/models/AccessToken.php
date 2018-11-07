<?php

namespace Api\modules\authentication\models;

use Api\modules\authentication\services\JwtService;
use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\admin\models\SystemUserGroup;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:55
 */
class AccessToken extends Admin
{
    const TOKEN_EXPIRE_DURATION = 60 * 60 * 24 * 7;//7天

    public $password;
    public $captcha;

    public $jwt;

    static public function tableName()
    {
        return 'admin_user';
    }

    public function fields()
    {
        return ['username'];
    }

    public function scenarios()
    {
        return [
            'login' => ['captcha', 'account', 'password', 'access_token', 'access_token_expire'],
        ];
    }

    public function rules()
    {
        return [
            ['captcha', \yii\captcha\CaptchaValidator::class, 'message' => '验证码错误', 'captchaAction'=>'admin/token/captcha', 'on' => ['login']],
            ['account', 'required', 'message' => '请输入账号', 'on' => ['login']],
            ['password', 'required', 'message' => '请输入密码', 'on' => ['login']],
            ['password', 'validatePassword', 'message' => '账号或密码错误', 'on' => ['login']],
        ];
    }

    public function loginByAccount()
    {
        $this->setScenario('login');
        $this->setAttributes(\Yii::$app->request->post());

        if ($this->validate()) {
            return $this;
        } else {
            $error = Helpers::getFirstError($this);
            throw new CustomException($error);
        }
    }

    public function generateAccessToken(System $system)
    {
        //生成JWT
        $jwt = new Jwt($this);
        $jwt->supplementPayloadByAdmin($system);
        $jwtService = new JwtService($jwt);
        return $jwtService->generateTokenString();
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

    public function getSgIds()
    {
        return $this->hasMany(SystemUserGroup::class, ['ad_uid' => 'ad_uid']);
    }

    /**
     * 验证访问权限
     * @param $module
     * @param $controller
     * @param $action
     * @param $privilege
     * @throws CustomException
     */
    public static function validateAccess($module, $controller, $action, $priv_type = SystemPriv::PRIVILEGE_TYPE_BUSINESS)
    {
        $privilege = SystemPriv::find()->where(['sp_module' => $module, 'sp_controller' => $controller, 'sp_action' => $action])->one();

        //获取用户所有的权限列表
        $systemPrivileges = \Yii::$app->user->identity->getPrivilege($priv_type);

        $systemPrivilegeIds = ArrayHelper::getColumn($systemPrivileges, 'sp_id');

        if (empty($systemPrivileges) || empty($privilege) || !in_array($privilege->sp_id, $systemPrivilegeIds)) {
            throw new CustomException(Lang::ERR_NO_ACCESS);
        }
    }
}