<?php

namespace Api\modules\open\controllers;

use Api\modules\open\services\Auth2Service;
use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\common\controllers\BaseController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class TokenController extends BaseController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    public function actionGain()
    {
        $systemId = Helpers::getRequestParam('sid');
        $code = Helpers::getRequestParam('code');

        Helpers::validateEmpty($systemId, '系统ID');
        Helpers::validateEmpty($code, '随机码');

        $access_token = Auth2Service::getTokenByCodeAndSystemId($systemId, $code);

        if (empty($access_token)) {
            throw new CustomException('未授权访问或授权访问失效，请重新登录');
        }

        //生成token并返回
        return ['access_token' => $access_token];
    }
}