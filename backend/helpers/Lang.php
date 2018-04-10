<?php
namespace Backend\helpers;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/23
 * Time: 10:27
 */
class Lang
{
    const SUCCESS                   = 0x0000;
    const FAIL                      = 0x1001;
    const ERR_TOKEN_INVALID         = 0x1002;
    const ERR_LOGIN_FAIL            = 0x1003;
    const ERR_TIME                  = 0x1004;
    const ERR_SIGN                  = 0x1005;
    const ERR_NO_ACCESS             = 0x1006;

    static $errMsg = [
        self::SUCCESS               => '操作成功',
        self::FAIL                  => '操作失败',
        self::ERR_TOKEN_INVALID     => '登录失效',
        self::ERR_LOGIN_FAIL        => '账号或密码错误',
        self::ERR_TIME              => '操作过期',
        self::ERR_SIGN              => '签名错误',
        self::ERR_NO_ACCESS         => '无权限访问',
    ];


    /*
    * @param int $code
    * @param Array $param
    */
    public static function getMsg($code, $param=array())
    {
        if (!isset(self::$errMsg[$code])) {
            return '未定义的错误码'.$code;
        }
        if(empty($param))
        {
            return self::$errMsg[$code];
        }
        else
        {
            array_unshift($param, self::$errMsg[$code]);
            return @call_user_func_array('sprintf', $param);
        }
    }
}