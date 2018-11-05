<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=192.168.39.75;port=3306;dbname=gamm3',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];

return file_exists('./gamm3-db-local.php') ? ArrayHelper::merge($db, require_once('./params-local.php')) : $db;