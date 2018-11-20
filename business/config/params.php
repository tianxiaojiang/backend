<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$params = [

];

return file_exists(__DIR__ . '/params-local.php') ? ArrayHelper::merge($params, require_once(__DIR__ . '/params-local.php')) : $params;