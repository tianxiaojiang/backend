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
            'md5Key' => 'LijunDeFengKongXiTong',
        ],
    ],
];

return file_exists('./params-local.php') ? ArrayHelper::merge($params, require_once('./params-local.php')) : $params;