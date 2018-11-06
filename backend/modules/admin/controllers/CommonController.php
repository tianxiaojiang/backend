<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/11/6
 * Time: 11:12
 */

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\common\controllers\BusinessController;
use yii\httpclient\Client;

class CommonController extends \Backend\modules\common\controllers\CommonController
{
    public $modelClass = 'Backend\modules\common\models\SystemModel';

    public function actions()
    {
        return [];
    }

    public function actionGetToken()
    {
        $code = Helpers::getRequestParam('code');

        if (!preg_match("/^\d{8}$/", $code)) {
            throw new CustomException('code码不合法');
        }

        $client = new Client();
        $url = \Yii::$app->params['integration_backend']['url'] . \Yii::$app->params['integration_backend']['authentication'];
        $response = $client->get($url, ['code' => $code])->send();

        if (!$response->getIsOk())
            throw new CustomException('授权失败，请联系中心后台管理人员');

        $data = $response->getData();
        \Yii::info('get token data:' . var_export($data, true));
        if ($data['code'] != 0)
            throw new CustomException($data['msg']);

        return $data['data'];
    }
}