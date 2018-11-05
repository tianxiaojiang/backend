<?php

namespace Backend\modules\common\models;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/8
 * Time: 18:11
 */

class SettingModel extends BaseModel
{
    public static $tableIndex = 0;

    public static function setTableIndex($index)
    {
        static::$tableIndex = $index;
    }
}