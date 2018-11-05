<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$db = [
];

return file_exists(__DIR__ . '/db-local.php') ? ArrayHelper::merge($db, require_once(__DIR__ . '/db-local.php')) : $db;