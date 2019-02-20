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
        $tokenString = Helpers::getHeader('Authorization');
        if (empty($tokenString)) {
            $tokenString = Helpers::getRequestParam('Authorization');
        }
        $userInfo = json_decode(base64_decode(explode(".", $tokenString)[1]), true);
        $request['user'] = [
            'uid' => $userInfo['uid'],
            'token_sid' => $userInfo['sid'],
        ];
        \Yii::info('request-info:' . var_export($request, true));
    }
}