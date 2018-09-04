<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

return [
    'id' => 'base-backend',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . DIRECTORY_SEPARATOR .'vendor',
    'timeZone' => 'Asia/Shanghai',
    'language' => 'zh-CN',
    'bootstrap' => ['log'],
    'modules' => [
        'common' => [
            'class' => 'Backend\modules\common\Module',
        ],
        'admin' => [
            'class' => 'Backend\modules\admin\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'Backend\modules\admin\models\Admin',
            'enableSession' => false,
            'enableAutoLogin' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'class' => 'Backend\Exception\ErrorHandler',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' =>true,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'admin/system-group'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'admin/admin-user'],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'categories'=> ['application'],
                    'levels'	=> ['info'],
                    'logFile'	=> '@runtime/logs/backend.log',
                    'maxFileSize'	=>	1024*100,//100M
                    'logVars'	=> [],
                ],
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'levels'	=> ['error', 'warning'],
                    'logFile'	=> '@runtime/logs/err.log',
                    'maxFileSize'	=>	1024*100,//100M
                    'maxLogFiles'	=>	5,
                    'logVars'	=> [],
                ],
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'levels'	=> ['trace'],
                    'logFile'	=> '@runtime/logs/trace.log',
                    'maxFileSize'	=>	1024*100,//100M
                    'maxLogFiles'	=>	5,
                    'logVars'	=> [],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'nijjjjj334j4kji434322ghlcvmdmd9',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'Backend\components\JsonOutput',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                ],
            ],
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->statusCode = 200;
            },
        ],
        //'db' => require(__DIR__ . '/db.php'),
        //'cache' => require(__DIR__ . '/cache.php'),
        //'redis' => require(__DIR__ . '/redis.php'),
    ],
    'params' =>require(__DIR__ . '/params.php')
];