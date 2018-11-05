<?php

namespace Api\modules\authentication\controllers;

use Backend\helpers\Helpers;
use Api\modules\authentication\models\AccessToken;
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

    public function actionGet()
    {
        $model = AccessToken::findOne(['account' => Helpers::getRequestParam('account')]);

        //验证是否找到管理员
        AccessToken::validateModelEmpty($model);

        //验证管理员的状态
        AccessToken::validateLoginAdminStatus($model);

        //生成token并返回
        return ['tokenString' => $model->generateToken()];
    }
}