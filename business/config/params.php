<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$params = [
    //上传图片保存目的地
    'upload_dest' => [
        'for_cdn' => [//传到cdn平台
            'type' => 'ftp',
            'account' => 'wHCGCNSp7egY',
            'password' => 'Y3ugfwtM164YJdY',
            'domain' => 'upload.dnion.com',
            'projectPath' => '/gamm3/{yyyy}{mm}/',
            'accessDomain' => 'http://download.gamm.ztgame.com',
        ],
        'for_admin' => [//传到内网平台，给admin看
            'type' => 'ftp',
            'account'       => 'gamm_admin',
            'password'      => '1wdvzse4',
            'domain'        => '192.168.101.35',
            'projectPath'   => '/gamm3/{yyyy}{mm}/',
            'accessDomain'  => 'http://192.168.101.35:8082'
        ],
        'for_local' => [//默认
            'type' => 'local',
            'absolute_dir' => BASE . '/public',//图片保存绝对地址
            'accessDomain' => 'http://gamm3_admin.ztgame.com'
        ]
    ],
    //只需要验证登录，不需要验证权限的操作
    'noPrivilegesActions' => [
        '/admin/file/upload'
    ],
    'uploadConfig' => require_once(__DIR__ . '/upload.php'),
];

return file_exists('./params-local.php') ? ArrayHelper::merge($params, require_once('./params-local.php')) : $params;