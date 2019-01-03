<?php

namespace Api\modules\authentication\controllers;

use Api\modules\open\services\Auth2Service;
use Backend\helpers\Helpers;
use Api\modules\authentication\models\AccessToken;
use Backend\modules\admin\models\System;
use Backend\modules\admin\services\AdminService;
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
        //只能登录到中心系统
        $businessSystem = System::findOne(['systems_id' => 1]);
        SystemService::validateSystemExist($businessSystem);

        //验证登录用户
        $model = AccessToken::getAdmin(Helpers::getRequestParam('account'), Helpers::getRequestParam('auth_type'));
        AdminService::validateModelEmpty($model);
        AdminService::validateLoginAdminStatus($model);
        $model->loginByAccount();//账密登录验证
        $access_token = $model->generateAccessToken($businessSystem);//生成token

        //返回跳转的子系统地址
        return ['access_token' => $access_token];
    }
}