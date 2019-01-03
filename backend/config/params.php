<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$params = [
    'jwt' => [
        'iss' => 'integration_background',//签发人
        'exp' => time() + 60 * 60 * 24 * 7,//过期时间，先给7天
        'sub' => null,//主题
        'aud' => null,//受众
        'nbf' => time(),//生效时间
        'iat' => time(),//签发时间
        'jti' => null,//编号
    ],
    //新系统的sql执行目录
    'sql_file_dir' => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'businessSql' . DIRECTORY_SEPARATOR,
    //获取token以及校验权限的地址
    'integration_backend' => [
        'url' => APP_ENV === 'dev' ? 'http://integration.background.com' : 'https://unify-admin.sdk.mobileztgame.com',
        'authentication' => '/open/privilege/check',
        'gain_token' => '/open/token/gain',
    ],
    //上传图片配置
    'uploadConfig' => require_once(__DIR__ . '/upload.php'),
    'passport' => [
        'url' => 'http://192.168.101.242/passport/soap/soapserver_passport.php?wsdl',
        'source_system_code'	=>	'gamm3admin',
        'cookie_expire'			=>	time() + 86400,
        'cookie_path'			=>	'/',
        'cookie_domian'			=>	'.ztgame.com',
        'cookie_secure'			=>	0,
    ],
];

return file_exists(__DIR__ . '/params-local.php') ? ArrayHelper::merge($params, require_once(__DIR__ . '/params-local.php')) : $params;