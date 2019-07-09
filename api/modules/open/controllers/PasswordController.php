<?php

namespace Api\modules\open\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\SystemController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class PasswordController extends SystemController
{
    public $modelClass = Admin::class;

    /**
     * 修改密码
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionModify()
    {
        $currentSystem = SystemService::getCurrentSystem();
        if (!$currentSystem->allow_api_call)
            throw new CustomException('该系统不允许直接通过api修改密码');

        Helpers::$request_params['new_passwd'] = Helpers::getRequestParam('password');
        Helpers::$request_params['password'] = Helpers::getRequestParam('oldPassword');
        Helpers::$request_params['new_passwd_repeat'] = Helpers::getRequestParam('repeatPassword');

        $model = Admin::findOne(['ad_uid' => \Yii::$app->user->identity->ad_uid]);
        $model->updatePasswd();

        return [];
    }
}