<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:03
 */

use yii\helpers\ArrayHelper;

$params = [
    'modules' => [//外部接入系统
        'gamm3' => [
            'gateWay' => 'http://gamm3.dev.ztgame.com'
        ],
        'risk' => [
            'gateWay' => 'http://gamm3.dev.ztgame.com/fdsf/fdsf',
        ],
    ],
    'menus_ext' => [//菜单维度扩展
        1 => '征途',
        2 => '征途2',
    ],
];

return file_exists('./params-local.php') ? ArrayHelper::merge($params, require_once('./params-local.php')) : $params;