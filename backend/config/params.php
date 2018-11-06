<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$params = [
    'integration_backend' => [
        'url' => 'http://integration.background.com',
        'authentication' => '/public/privilege/check',
        'gain_token' => '/public/token/gain',
    ]
];

return file_exists('./params-local.php') ? ArrayHelper::merge($params, require_once('./params-local.php')) : $params;