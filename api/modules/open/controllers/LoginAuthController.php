<?php

namespace Api\modules\open\controllers;

use Api\modules\authentication\models\AccessToken;
use Api\modules\open\services\Auth2Service;
use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\services\AdminService;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\BaseController;

/**
 * 登录并授权接口，返回账户所需的接口
 * User: tianweimin
 */
class LoginAuthController extends BaseController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    /**
     * 通过http接口，使用账密登录，返回token
     * @return array
     * @throws CustomException
     */
    public function actionGain()
    {
        $currentSystem = SystemService::getCurrentSystem();
        if (!$currentSystem->allow_api_call)
            throw new CustomException('该系统不允许直接通过api登录');

        $systemId = Helpers::getRequestParam('sid');
        $account = Helpers::getRequestParam('account');
        $password = Helpers::getRequestParam('password');
        $auth_type = intval(Helpers::getRequestParam('auth_type'));

        Helpers::validateEmpty($systemId, '系统ID');
        Helpers::validateEmpty($account, '账号');
        Helpers::validateEmpty($password, '密码');

        //验证登录用户
        $model = AccessToken::getAdmin($account, $auth_type);
        AdminService::validateModelEmpty($model);
        AdminService::validateLoginAdminStatus($model);
        $model->loginByAccount();//账密登录验证

        //验证是否有系统权限
        $systemAdmin = SystemAdmin::findOne(['ad_uid' => $model->ad_uid]);
        if (empty($systemAdmin))
            throw new CustomException('此账号没有该系统的访问权限');

        //生成所需token
        $currentSystem = SystemService::getCurrentSystem();
        $access_token = $model->generateAccessToken($currentSystem);

        //生成token并返回
        return ['access_token' => $access_token];
    }

    /**
     * 给GA返回登录token
     */
    public function actionGetGaToken()
    {
        //验证签名
        $key = '9f547de2b07a8f8d1d811c4549ab3583';
        $params = [
            'account' => Helpers::getRequestParam('account'),
            'time' => Helpers::getRequestParam('time'),
        ];
        $sign = Helpers::getRequestParam('sign');
        if (abs(intval(Helpers::getRequestParam('time')) - time()) > 30)
            throw new CustomException("调用超时");
        if ($sign != md5(md5(urldecode(http_build_query($params))) . $key))
            throw new CustomException('签名错误');

        $systemId = APP_ENV == 'dev' ? 14 : 12;//线下是14
        $account = Helpers::getRequestParam('account');
        $auth_type = Admin::AUTH_TYPE_DOMAIN;//
        Helpers::$request_params['sid'] = $systemId;

        Helpers::validateEmpty($systemId, '系统ID');
        Helpers::validateEmpty($account, '账号');

        //验证登录用户
        $model = AccessToken::getAdmin($account, $auth_type);
        AdminService::validateModelEmpty($model);
        AdminService::validateLoginAdminStatus($model);

        //验证是否有系统权限
        $systemAdmin = SystemAdmin::findOne(['ad_uid' => $model->ad_uid]);
        if (empty($systemAdmin))
            throw new CustomException('此账号没有该系统的访问权限');

        //生成所需token
        $currentSystem = SystemService::getCurrentSystem();
        $access_token = $model->generateAccessToken($currentSystem);

        //生成token并返回
        return ['access_token' => $access_token];
    }
}