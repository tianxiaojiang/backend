<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/11/6
 * Time: 11:12
 */

namespace Backend\modules\admin\controllers;

use yii\httpclient\Client;
use Backend\helpers\Helpers;
use Backend\Exception\CustomException;

class CommonController extends \Backend\modules\common\controllers\CommonController
{
    public $modelClass = 'Backend\modules\common\models\SystemModel';

    public function actionGetToken()
    {
        $code = Helpers::getRequestParam('code');
        $sid = Helpers::getRequestParam('sid');

        if (!preg_match("/^\d{8}$/", $code)) {
            throw new CustomException('code码不合法');
        }

        $client = new Client();
        $url = \Yii::$app->params['integration_backend']['url'] . \Yii::$app->params['integration_backend']['gain_token'];
        $response = $client->get($url, ['code' => $code, 'sid' => $sid])->send();

        if (!$response->getIsOk())
            throw new CustomException('授权失败，请联系中心后台管理人员');

        $data = $response->getData();
        \Yii::info('get token data:' . var_export($data, true));
        if ($data['code'] != 0)
            throw new CustomException($data['msg']);

        return $data['data'];
    }
}