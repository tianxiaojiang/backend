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
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        $retCode = $message;
        $msg = empty($code) ? Lang::getMsg($retCode) : $code;
        parent::__construct($msg, $retCode);
    }
}