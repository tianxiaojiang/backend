<?php

namespace Api\modules\authentication\controllers;

use Api\modules\authentication\models\AccessToken;
use Api\modules\open\services\Auth2Service;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\System;
use Backend\modules\admin\services\AdminService;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\JwtController;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class AuthController extends JwtController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    /**
     * 通过登录后刷新传递token
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionGetTokenByToken()
    {
        //先验证要登录的系统
        $goSid = Helpers::getRequestParam('go_sid');
        $system = System::findOne(['systems_id' => $goSid]);
        SystemService::validateSystemExist($system);

        //验证登录用户
        $model = \Yii::$app->user->identity;
        AdminService::validateModelEmpty($model);
        AdminService::validateLoginAdminStatus($model);
        AdminService::validateHasSystemPermission($system->systems_id, ArrayHelper::getColumn($model->systems, 'systems_id'));

        $access_token = $model->generateAccessToken($system);//生成token

        //生成并存储token
        $code = Helpers::getStrBylength();
        Auth2Service::saveTokenByCodeAndSystemId(
            $goSid,
            $code,
            $access_token
        );

        //返回跳转的子系统地址
        return ['redirect_url' => $system->url . '/views/start/index.html?code=' . $code . '&sid=' . $goSid . '#/user/auth'];
    }
}