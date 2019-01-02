<?php

namespace Backend\modules\common\controllers;

use Backend\actions\CreateAction;
use Backend\actions\DeleteAction;
use Backend\actions\UpdateAction;
use Backend\behavior\ValidateIsLogin;
use Backend\behavior\ValidateTime;
use Backend\behavior\Privilege;
use Backend\helpers\Helpers;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class BusinessController extends SystemController
{
    public $query = null;

    public function actions()
    {
        $actions = parent::actions();

        $actions['create']['class'] = CreateAction::class;
        $actions['update']['class'] = UpdateAction::class;
        $actions['delete']['class'] = DeleteAction::class;
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider() {

        if (empty($this->query)) {
            $controller = \Yii::$app->controller;
            //$modelClass = new $controller->modelClass();
            $this->query = call_user_func_array([$controller->modelClass, 'find'], []);
        }

        $provider = new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => [
                'pageSize' => Helpers::getRequestParam('limit') ? Helpers::getRequestParam('limit') : 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $model = $provider->getModels();
        if (method_exists($this, 'formatModel')) {
            $model = $this->formatModel($model);
        }

        return ['lists' => $model, 'count' => $provider->getTotalCount()];
    }

    public function behaviors()
    {
        //用户认证, 传递头信息为bearer token
        //{Authorization : Bearer aaaaaa}
        $behaviors = parent::behaviors();

        //验证时间的行为
        $behaviors['validateTime'] = ValidateTime::class;
        $behaviors['Privilege'] = Privilege::class;

        return $behaviors;
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->validateTime();//验证请求时间戳
        $this->canAccess();//验证访问权限

        return true;
    }
}