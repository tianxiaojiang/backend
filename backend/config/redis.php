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
    'hostname' => '172.28.161.206',
    'port' => 6666,
    'database' => 0,
];

return file_exists(__DIR__ . '/redis-local.php') ? ArrayHelper::merge($redis, require_once(__DIR__ . '/redis-local.php')) : $redis;