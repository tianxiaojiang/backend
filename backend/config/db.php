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
    'dsn' => 'mysql:host=10.20.49.195;port=3306;dbname=integration_background',
    'username' => 'giant_manage',
    'password' => 'qGwQAZBR5j4DJYyI',
    'charset' => 'utf8mb4',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];

return file_exists(__DIR__ . '/db-local.php') ? ArrayHelper::merge($db, require_once(__DIR__ . '/db-local.php')) : $db;