<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/23
 * Time: 19:22
 */
namespace Backend\behavior;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use yii\base\Behavior;
use yii\httpclient\Client;

/**
 * 权限判断
 * Class PrivilegeFilter
 * @package Backend\behavior
 */
class Privilege extends Behavior
{

    public function canAccess()
    {
        $controller = \Yii::$app->controller;
        $c = $controller->id;
        $m = $controller->module->id;
        $a = $controller->action->id;
        $sid = Helpers::getRequestParam('sid');
        $game_id = Helpers::getRequestParam('game_id');
        $authorization = Helpers::getHeader('Authorization');

        Helpers::validateEmpty($authorization, 'token');

        $params = [
            'm' => $m,
            'c' => $c,
            'a' => $a,
            'sid' => $sid,
            'game_id' => $game_id,
            ];
        \Yii::info('鉴权头：'. var_export($authorization, true));
        \Yii::info('鉴权参数：'. var_export($params, true));
        $httpClient = new Client();
        $authenticationUrl = \Yii::$app->params['integration_backend']['url'] . \Yii::$app->params['integration_backend']['authentication'];
        $response = $httpClient->get(
                $authenticationUrl,
                $params,
                ['Authorization' => $authorization]
        )->send();

        if (!$response->getIsOk())
            throw new CustomException('鉴权系统发生错误');

        $data = $response->getData();
        \Yii::info('鉴权结果：'. var_export($data, true));
        if ($data['code'] != 0) {
            throw new CustomException($data['msg'], $data['code']);
        }
    }
}