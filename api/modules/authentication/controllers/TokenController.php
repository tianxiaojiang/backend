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
        //先验证要登录的系统
        $businessSystem = System::findOne(['systems_id' => 1]);
        SystemService::validateSystemExist($businessSystem);

        //验证登录用户
        $model = AccessToken::findOne(['account' => Helpers::getRequestParam('account')]);
        AdminService::validateModelEmpty($model);
        AdminService::validateLoginAdminStatus($model);
        $model->loginByAccount();//账密登录验证
        $access_token = $model->generateAccessToken($businessSystem);//生成token


        //返回跳转的子系统地址
        return ['access_token' => $access_token];
    }

    //执行退出操作
    public function actionLogout()
    {
        $sid = Helpers::getRequestParam('sid');
        $ad_id = \Yii::$app->user->identity->ad_uid;

        Helpers::validateEmpty($sid, '系统ID');
        Helpers::validateEmpty($ad_id, '用户ID');

        AdminService::punishAdmin($ad_id, $sid);

        //业务系统使用jsonp的方式返回成功
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));
        if (!$isMaintain) {
            $callback = Helpers::getRequestParam('callback');
            exit($callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => []]) . ')');
        }

        return [];
    }
}