<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$redis = [
    'class' => 'yii\redis\Connection',
    'hostname' => '10.20.9.46',
    'port' => 6666,
    'database' => 0,
];

return file_exists(__DIR__ . '/redis-local.php') ? ArrayHelper::merge($redis, require_once(__DIR__ . '/redis-local.php')) : $redis;