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
        'url' => 'https://unify-admin.sdk.mobileztgame.com',
        'authentication' => '/open/privilege/check',
        'gain_token' => '/open/token/gain',
    ],
    'check_login_no_privilege' => [
        '/common/'
    ],
];

return file_exists(__DIR__ . '/params-local.php') ? ArrayHelper::merge($params, require_once(__DIR__ . '/params-local.php')) : $params;