<?php

namespace Backend\behavior;

use yii\base\Behavior;
use yii\web\ForbiddenHttpException;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/22
 * Time: 17:21
 */
class TokenExpire extends Behavior
{
    public function validateTokenExpire()
    {
        $identity = \Yii::$app->user->identity;

        if (empty($identity->access_token_expire) || $identity->access_token_expire < time()) {
            throw new ForbiddenHttpException('身份已过期，请重新登录');
        }
    }
}