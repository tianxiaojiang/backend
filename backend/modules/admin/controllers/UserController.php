<?php

namespace Backend\modules\admin\controllers;

use Backend\modules\admin\models\Admin;
use Backend\modules\common\controllers\JwtController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class UserController extends JwtController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    public function actionChangePassword()
    {
        $model = Admin::findOne(['ad_uid' => \Yii::$app->user->identity->ad_uid]);
        $model->updatePasswd();

        return [];
    }

    public function actionProfile()
    {
        $model = Admin::findOne(['ad_uid' => \Yii::$app->user->identity->ad_uid]);

        return [
            "mobile_phone" => $model->mobile_phone,
            "username" => $model->username
            ];
    }

    public function actionChangeProfile()
    {
        $model = Admin::findOne(['ad_uid' => \Yii::$app->user->identity->ad_uid]);
        $model->updateProfile();

        return [];
    }
}