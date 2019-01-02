<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/11/5
 * Time: 11:16
 */

namespace Backend\modules\admin\services;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\System;

/**
 * 系统业务处理
 * Class SystemService
 * @package Backend\modules\admin\services
 */
class SystemService
{
    public static $currentSystem = null;

    /**
     * 获取当前的系统对象
     * @return Backend\modules\admin\models\System
     */
    public static function getCurrentSystem()
    {
        if (self::$currentSystem === null) {
            //获取当前请求的所在系统
            self::$currentSystem = System::findOne(['systems_id' => intval(Helpers::getRequestParam('sid'))]);
        }

        return self::$currentSystem;
    }

    /**
     * 禁止系统
     * @param $system_id
     * @return bool
     * @throws CustomException
     */
    public static function forbiddenSystem($system_id)
    {
        $systemObj = System::findOne(['system_id' => $system_id]);
        //验证非空性
        self::validateSystemExist($systemObj);
        self::validateDuplicationForbidden($systemObj);
        //修改状态
        $systemObj->status = System::SYSTEM_STAT_FORBIDDEN;
        $systemObj->save();
        return true;
    }

    /**
     * 验证系统存在与否
     * @param $systemObj
     * @throws CustomException
     */
    public static function validateSystemExist($systemObj)
    {
        if (!$systemObj instanceof System)
            throw new CustomException('系统不存在');
    }

    /**
     * 验证是否重复禁用
     * @param $systemObj
     * @throws CustomException
     */
    public static function validateDuplicationForbidden($systemObj)
    {
        if ($systemObj->status === System::SYSTEM_STAT_FORBIDDEN)
            throw new CustomException('系统已经被禁止，不可再次禁用');
    }

    /**
     * 验证系统状态正常
     * @param $systemObj
     * @throws CustomException
     */
    public static function validateSystemNormal($systemObj)
    {
        if ($systemObj->status !== System::SYSTEM_STAT_NORMAL)
            throw new CustomException('系统状态异常，请联系平台管理员1');
    }
}