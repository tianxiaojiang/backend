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
     * 拉取用户所属的所有角色管理的所有游戏信息
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionModify()
    {
        $currentSystem = SystemService::getCurrentSystem();

        if (!$currentSystem->allowApiLoginAuth)
            throw new CustomException('该系统不允许直接通过api登录授权');

        $oldPassword = Helpers::getRequestParam('oldPassword');
        $password = Helpers::getRequestParam('password');
        $repeatPassword= Helpers::getRequestParam('repeatPassword');

        Helpers::validateEmpty($oldPassword, '旧密码');
        Helpers::validateEmpty($password, '新密码');
        Helpers::validateEmpty($repeatPassword, '重复新密码');

        if ($password != $repeatPassword)
            throw new CustomException('新密码填写不一致');

        $model = \Yii::$app->user->identity;
        //先验证老密码
        $model->password = $oldPassword;
        $model->validatePassword();

        //再修改新密码
        Helpers::$request_params['password'] = $oldPassword;
        Helpers::$request_params['new_passwd'] = $password;
        $model->updatePasswd();

        return [];
    }
}