<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

return [
    'id' => 'integration-backend',
    'basePath' => dirname(dirname(__DIR__)),
    //'runtimePath' => defined('YII_ENV') && YII_ENV == 'dev' ? dirname(__DIR__) : '/tmp/backend/',
    'vendorPath' => dirname(dirname(__DIR__) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR .  'vendor',
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
        'authentication' => [
            'class' => 'Api\modules\authentication\Module'
        ],
        'open' => [
            'class' => 'Api\modules\open\Module'
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'Api\modules\authentication\models\AccessToken',
            'enableSession' => false,
            'enableAutoLogin' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'class' => 'Backend\Exception\ErrorHandler',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            //'enableStrictParsing' =>true,
            'rules' => [
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets' => [
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'categories'=> ['application'],
                    'levels'	=> ['info'],
                    'logFile'	=> '@runtime/logs/backend.log',
                    'exportInterval' => 1,
                    'maxFileSize'	=>	1024*100,//100M
                    'logVars'	=> [],
                ],
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'categories'=> ['application'],
                    'levels'	=> ['warning'],
                    'logFile'	=> '@runtime/logs/warning.log',
                    'exportInterval' => 1,
                    'maxFileSize'	=>	1024*100,//100M
                    'logVars'	=> [],
                ],
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'levels'	=> ['error', 'warning'],
                    'logFile'	=> '@runtime/logs/err.log',
                    'maxFileSize'	=>	1024*100,//100M
                    'exportInterval' => 1,
                    'maxLogFiles'	=>	5,
                    'logVars'	=> [],
                ],
                [
                    'class'		=> 'Backend\log\FileTarget',
                    'levels'	=> ['trace'],
                    'logFile'	=> '@runtime/logs/trace.log',
                    'maxFileSize'	=>	1024*100,//100M
                    'exportInterval' => 1,
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
        'db' => require(__DIR__ . '/db.php'),
        //'cache' => require(__DIR__ . '/cache.php'),
        'redis' => require(__DIR__ . '/redis.php'),
    ],
    'params' =>require(__DIR__ . '/params.php')
];