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
class VerifySign extends Behavior
{
    public function verifySignature()
    {
        $request = Helpers::getRequestParams();

        if (empty($request['sign'])) {
            throw new CustomException(Lang::ERR_SIGN);
        }
        $sign = $request['sign'];
        //为了兼容客户端的签名，去掉分页参数的校验
        unset($request['sign']);
        unset($request['page']);
        unset($request['limit']);

        ksort($request);
        $queryStr = '';
        foreach ($request as $key => $item) {
            $queryStr .= (empty($queryStr) ? $key . '=' . $item : '&' . $key . '=' . $item);
        }

        if (strtolower(md5($queryStr . '&access_token=' . Helpers::getHeader('Authorization'))) != strtolower($sign)) {
            throw new CustomException(Lang::ERR_SIGN);
        }
    }
}