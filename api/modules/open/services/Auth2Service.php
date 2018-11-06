<?php

namespace Api\modules\open\services;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/10/25
 * Time: 14:13
 *
 * json web token类用于token的生成、解析与校验
 */

class Auth2Service
{
    const AUTH_TOKEN_EXPIRE = 15;

    public static function getSaveKey($systemId, $code)
    {
        return 'auth' . $systemId . $code;
    }

    public static function saveTokenByCodeAndSystemId($systemId, $code, $token)
    {
        $key = self::getSaveKey($systemId, $code);
        return \Yii::$app->redis->setex($key, self::AUTH_TOKEN_EXPIRE, $token);
    }

    public static function getTokenByCodeAndSystemId($systemId, $code)
    {
        $key = self::getSaveKey($systemId, $code);
        $access_token = \Yii::$app->redis->get($key);
        self::deleteTokenByCodeAndSystemId($systemId, $code);
        return $access_token;
    }

    public static function deleteTokenByCodeAndSystemId($systemId, $code)
    {
        $key = self::getSaveKey($systemId, $code);
        return \Yii::$app->redis->del($key);
    }
}