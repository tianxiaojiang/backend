<?php

namespace Backend\behavior;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use yii\base\Behavior;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/22
 * Time: 17:21
 */
class ValidateTime extends Behavior
{
    const TIME_ALLOWANCE_ERROR = 10000;//时间允许误差，10秒

    public function validateTime()
    {
        $requestTime = Helpers::getRequestParam('time');

        if (abs(time() - intval($requestTime)) > self::TIME_ALLOWANCE_ERROR ) {
            throw new CustomException(Lang::ERR_TIME);
        }
    }
}