<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

$config = [
    'homeUrl' => defined('YII_ENV') && YII_ENV == 'dev' ? 'https://dev.backend.com' : 'https://backend.com',
    'runtimePath' => defined('YII_ENV') && YII_ENV == 'dev' ? 'E:\\project\\backend\\backend\\runtime' : '/tmp/backend/',
    'modules' => [
        'test' => [
            'class' => 'Business\modules\index\Module',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' =>true,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'admin/system-group'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'admin/admin-user'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'test/user'],
            ],
        ],
        'db' => require(__DIR__ . DIRECTORY_SEPARATOR . 'db.php'),
        'gamm3_db' => require_once(__DIR__ . DIRECTORY_SEPARATOR . 'gamm3-db.php'),
        //'cache' => require(__DIR__ . '/cache.php'),
        //'redis' => require(__DIR__ . '/redis.php'),
    ],
    'params' =>require(__DIR__ . '/params.php')
];

return \yii\helpers\ArrayHelper::merge(require_once(ROOT . DIRECTORY_SEPARATOR . 'config/config.php'), $config);