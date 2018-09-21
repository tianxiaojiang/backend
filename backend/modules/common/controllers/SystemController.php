<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 9:39
 */

namespace Backend\modules\common\controllers;


use Backend\behavior\ModulePrivileges;
use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use yii\httpclient\Client;

class SystemController extends BusinessController
{
    public $modelClass = 'Backend\modules\common\models\SystemModel';

    public static $httpClient = null;
    const TIME_OUT = 20;
    protected $module = null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['systemPrivilege'] = ModulePrivileges::class;
        return $behaviors;
    }

    public function actions()
    {
        return [];
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $this->verifyModulePrivilege();

        return true;
    }

    public function actionIndex() {
        $module = Helpers::getRequestParam('module');
        if (empty($module)) {
            throw new CustomException(Lang::FAIL, '参数错误');
        }

        if (empty(\Yii::$app->params['modules'][$module]['gateWay'])) {
            throw new CustomException(Lang::FAIL, '网关错误');
        }

        $this->module = $module;
        $gateWay = \Yii::$app->params['modules'][$this->module]['gateWay'];

        $url = $gateWay . Helpers::getRequestParam('api');
        $params = Helpers::getRequestParams();
        unset($params['api']);
        unset($params['module']);

        $params['sign'] = $this->signData($params);

        static::$httpClient = new Client();
        $headers = [];
        $resquest = static::$httpClient->createRequest()
            ->addHeaders($headers)
            ->setMethod('POST')
            ->setOptions(['timeout' => self::TIME_OUT])
            ->setUrl($url)
            ->setData($params);

        $response = $resquest->send();
        \Yii::info('transmission request failed, code:'.$response->getStatusCode());
        if ($response->getStatusCode() != 200) {
            throw new CustomException(Lang::FAIL, '网关异常');
        }

        $content = $response->getContent();

        $decodeContent = json_decode($content, true);
        if ($decodeContent['code'] != 0) {
            throw new CustomException(Lang::FAIL, $decodeContent['msg']);
        }

        return $decodeContent['data'];
    }

    protected function signData($params)
    {
        $signKey = \Yii::$app->params['modules'][$this->module]['gateWay'];

        ksort($params);
        $str = '';
        foreach ($params as $key => $param) {
            $str = (empty($str)) ? $key . '=' .$params : '&' . $key . '=' . $params;
        }

        $str .= $signKey;

        return md5($str);
    }
}
