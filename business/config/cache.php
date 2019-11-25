<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$cache = [
    
];

return file_exists(__DIR__ . '/params-local.php') ? ArrayHelper::merge($cache, require_once(__DIR__ . '/params-local.php')) : $cache;