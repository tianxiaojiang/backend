<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

$config = [
    'homeUrl' => APP_ENV === 'dev' ? 'http://integration.background.com' : 'https://unify-admin.sdk.mobileztgame.com',
    'runtimePath' => defined('APP_ENV') && APP_ENV == 'dev' ? '/tmp/backend/runtime/' : '/data/pt_weblog/unify_admin',
    'modules' => [
        'test' => [
            'class' => 'Business\modules\test\Module',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' =>true,
            'rules' => [
            ],
        ],
        'db' => require(__DIR__ . DIRECTORY_SEPARATOR . 'db.php'),
        //'cache' => require(__DIR__ . '/cache.php'),
        //'redis' => require(__DIR__ . '/redis.php'),
    ],
    'params' =>require(__DIR__ . '/params.php')
];

return \yii\helpers\ArrayHelper::merge(require_once(ROOT . DIRECTORY_SEPARATOR . 'config/config.php'), $config);