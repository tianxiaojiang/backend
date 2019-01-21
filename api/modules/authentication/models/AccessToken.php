<?php

namespace Api\modules\authentication\models;

use Api\modules\authentication\services\JwtService;
use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\LoginWhiteList;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;
use Backend\modules\admin\services\SystemService;
use Backend\modules\admin\services\admin\NewAdminInfoFill;
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

    public $captcha;
    public $jwt;
    public $thirdData;

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
            'login' => ['captcha', 'auth_type', 'account', 'password', 'password_algorighm_system', 'access_token', 'access_token_expire'],
        ];
    }

    public function rules()
    {
        $rules = [
            ['account', 'required', 'message' => '请输入账号', 'on' => ['login']],
            ['auth_type', 'required', 'message' => '请选择账号类型', 'on' => ['login']],
            ['password', 'required', 'message' => '请输入密码', 'on' => ['login']],
            ['password', 'validatePassword', 'message' => '账号或密码错误', 'on' => ['login']],
        ];

        $whiteListModel = LoginWhiteList::findOne(['type' => $this->auth_type, 'account' => $this->account]);
        if (empty($whiteListModel)) {
            array_unshift($rules, ['captcha', \yii\captcha\CaptchaValidator::class, 'message' => '验证码错误', 'captchaAction'=>'admin/token/captcha', 'on' => ['login']]);
        }
        return $rules;
    }

    public static function getAdmin($account, $auth_type)
    {
        $admin = self::findOne(['account' => $account, 'auth_type' => $auth_type]);
        if (empty($admin) && $auth_type != Admin::AUTH_TYPE_PASSWORD)
            $admin = new static();

        return $admin;
    }

    public function loginByAccount()
    {
        $this->setScenario('login');
        $this->setAttributes(\Yii::$app->request->post());

        if ($this->validate()) {
            //非普通账号，同步一下内容
            if (in_array($this->auth_type, [Admin::AUTH_TYPE_CHANGZHOU, Admin::AUTH_TYPE_DOMAIN])) {
                (new NewAdminInfoFill($this))->fillFieldAtCreate();
                if ($this->isNewRecord){//新的员工，校验下员工工号
                    $oldModel = Admin::findOne(['staff_number' => $this->staff_number]);
                    if (!empty($oldModel)) {
                        $oldModel->username = $this->username;
                        $oldModel->account = $this->account;
                        $oldModel->mobile_phone = $this->mobile_phone;
                        $oldModel->auth_type = $this->auth_type;

                        if (!empty($oldModel)) {
                            $this->refreshInternal($oldModel);
                        }
                    }
                }
                $this->save(false);
            } elseif($this->password_algorithm_system != 1) {
                //普通账号，其他系统的，同步密码为最新系统
                (new NewAdminInfoFill($this))->fillFieldAtCreate();
                $this->save();
            }
            return $this;
        } else {
            $error = Helpers::getFirstError($this);
            throw new CustomException($error);
        }
    }

    /**
     * 生成token
     * @param System $system
     * @return string
     * @throws CustomException
     */
    public function generateAccessToken(System $system)
    {
        //生成JWT
        $jwt = new Jwt($this);
        $jwt->supplementPayloadByAdmin($system);
        $jwtService = new JwtService($jwt);
        $tokenString = $jwtService->generateTokenString();
        $this->updateTokenId($jwtService->tokenObj->getHeader('jti'));//更新登录的系统的token_id
        return $tokenString;
    }

    //更新登录的有效token_id
    public function updateTokenId($jwtId)
    {
        $sid = Helpers::getRequestParam('sid');
        if (empty($this->ad_uid) || empty($sid))
            throw new CustomException('登录的账户或系统异常');
        $systemAdminRelation = SystemUser::findOne(['ad_uid' => $this->ad_uid, 'systems_id' => Helpers::getRequestParam('sid')]);
        //如果系统用户存在，则更新登录的token_id
        if (!empty($systemAdminRelation)) {
            $isMaintain = Helpers::getRequestParam('is_maintain');
            if (!empty($isMaintain)) {
                $systemAdminRelation->setting_token_id = $jwtId;
            } else {
                $systemAdminRelation->token_id = $jwtId;
            }
            $systemAdminRelation->save();
        }
        return true;
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
        $currentSystem = SystemService::getCurrentSystem();
        //如果是系统开发管理员，则权限直接通过
        if (intval(\Yii::$app->user->identity->ad_uid) == intval($currentSystem->dev_account)) {
            return true;
        }

        $privilege = SystemPriv::find()->where(['sp_module' => $module, 'sp_controller' => $controller, 'sp_action' => $action])->one();

        //获取用户所有的权限列表
        $gameId = intval(Helpers::getRequestParam('game_id'));
        $systemPrivileges = \Yii::$app->user->identity->getPrivileges($gameId, $priv_type);

        $systemPrivilegeIds = ArrayHelper::getColumn($systemPrivileges, 'sp_id');

        if (empty($systemPrivileges) || empty($privilege) || !in_array($privilege->sp_id, $systemPrivilegeIds)) {
            throw new CustomException(Lang::ERR_NO_ACCESS);
        }
    }
}