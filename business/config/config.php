<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

$config = [
    'homeUrl' => defined('YII_ENV') && YII_ENV == 'dev' ? 'http://www.admin_client.com' : 'http://www.admin_client.com',
    'runtimePath' => defined('YII_ENV') && YII_ENV == 'dev' ? '/tmp/admin_client/' : '/tmp/backend/',
    'modules' => [
        'index' => [
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
        //'cache' => require(__DIR__ . '/cache.php'),
        //'redis' => require(__DIR__ . '/redis.php'),
    ],
    'params' =>require(__DIR__ . '/params.php')
];

return \yii\helpers\ArrayHelper::merge(require_once(ROOT . DIRECTORY_SEPARATOR . 'config/config.php'), $config);