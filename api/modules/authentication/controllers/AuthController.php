<?php

namespace Api\modules\authentication\controllers;

use Api\modules\authentication\models\AccessToken;
use Api\modules\open\services\Auth2Service;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemUserGroup;
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
        $isMaintain = intval(Helpers::getRequestParam('is_maintain'));
        $system = System::findOne(['systems_id' => $goSid]);
        SystemService::validateSystemExist($system);
        Helpers::$request_params['sid'] = $goSid;

        //验证登录用户
        $model = \Yii::$app->user->identity;
        $model->validateCanAuth($isMaintain);

        AdminService::validateModelEmpty($model);
        AdminService::validateLoginAdminStatus($model);
        AdminService::validateResetPassword($model);
        AdminService::validateHasSystemPermission($system->systems_id, ArrayHelper::getColumn($model->systems, 'systems_id'));
        AdminService::validateHasSystemBusinessOrSetting(
            $isMaintain ? SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN : SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT,
            ArrayHelper::getColumn($model->systemGroup, 'privilege_level')
        );

        //验证有没有系统对应的业务权限或管理权限


        $access_token = $model->generateAccessToken($system);//生成token

        //生成并存储token
        $code = Helpers::getStrBylength();
        Auth2Service::saveTokenByCodeAndSystemId(
            $goSid,
            $code,
            $access_token
        );

        /**
         * 如果是维护系统，则地址跳转到中心后台
         * sid是业务系统id
         */
        if ($isMaintain) {
            $system = System::findOne(['systems_id' => 1]);
        }

        //返回跳转的子系统地址
        return ['redirect_url' => $system->url . $system->auth_url . '?code=' . $code . '&sid=' . $goSid . '#/user/auth'];
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
        $callback = Helpers::getRequestParam('callback');
        if (!empty($callback)) {
            $callback = Helpers::getRequestParam('callback');
            exit($callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => []]) . ')');
        }

        return [];
    }
}