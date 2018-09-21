<?php

namespace Backend\behavior;

use Backend\Exception\CustomException;
use Backend\helpers\Lang;
use yii\base\Behavior;

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
            throw new CustomException(Lang::ERR_TOKEN_INVALID);
        }
    }
}