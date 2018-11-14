<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;
use Backend\modules\common\controllers\BusinessController;
use Backend\modules\common\controllers\JwtController;
use yii\helpers\ArrayHelper;

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
}