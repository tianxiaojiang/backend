<?php

namespace Api\modules\authentication\controllers;

use Api\modules\open\services\Auth2Service;
use Backend\helpers\Helpers;
use Api\modules\authentication\models\AccessToken;
use Backend\modules\admin\models\System;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\BaseController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class TokenController extends BaseController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    /**
     * 通过账密获取token
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionGet()
    {
        //先验证要登录的系统
        $sid = Helpers::getRequestParam('sid');
        $businessSystem = System::findOne(['systems_id' => $sid]);
        SystemService::validateSystemExist($businessSystem);
        $isMaintain = Helpers::getRequestParam('is_maintain');//是跳转到业务后台还是中心后台

        //验证登录用户
        $model = AccessToken::findOne(['account' => Helpers::getRequestParam('account')]);
        AccessToken::validateModelEmpty($model);
        AccessToken::validateLoginAdminStatus($model);
        $model->loginByAccount();//账密登录验证
        $access_token = $model->generateAccessToken($businessSystem);//生成token

        //生成并存储token
        $code = Helpers::getStrBylength();
        Auth2Service::saveTokenByCodeAndSystemId($sid, $code, $access_token);

        $adminCenterSystem = System::findOne(['systems_id' => 1]);
        $redirectUrl = empty($isMaintain) ? $businessSystem->url : $adminCenterSystem->url;

        //返回跳转的子系统地址
        return ['redirect_url' => $redirectUrl . '/views/start/index.html?code=' . $code . '&sid=' . $sid . '#/user/auth'];
    }
}