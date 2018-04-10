<?php

namespace Backend\behavior;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use yii\base\Behavior;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/22
 * Time: 17:21
 */
class Log extends Behavior
{
    public function logRequest()
    {
        $request = Helpers::getRequestParams();
        $apiPath = \Yii::$app->request->getPathInfo();
        $request['api_path'] = $apiPath;
        \Yii::info('request:' . var_export($request, true));
    }
}