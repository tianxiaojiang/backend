<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$params = [
    'modules' => [
    ],
    'menus_ext' => [
    ],
];

return file_exists('./params-local.php') ? ArrayHelper::merge($params, require_once('./params-local.php')) : $params;