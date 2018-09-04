<?php

namespace Backend\Exception;

use Backend\helpers\Lang;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/22
 * Time: 18:38
 */
class CustomException extends \Exception
{
    /**
     * 反转code和message的顺序
     */
    public function __construct($message = null, $code = -1, \Exception $previous = null)
    {
        if (key_exists($message, Lang::$errMsg)) {
            $msg = Lang::getMsg($message);
            $retCode = $message;
        } else {
            $msg = $message;
            $retCode = $code;
        }
        parent::__construct($msg, $retCode);
    }
}