<?php

namespace Backend\modules\admin\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\System;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\System';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function prepareDataProvider()
    {
        $this->query = System::find();
        $this->query->orderBy('systems_id asc');

        return parent::prepareDataProvider();
    }

    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'systems_id' => $model->systems_id,
                'name' => $model->name,
                'status' => $model->status,
                'statusName' => System::$_status[$model->status],
                'img_id' => $model->img_id,
                'show_url' => (empty($model->img) ? '' : \Yii::$app->params['uploadConfig']['imageUrlPrefix'] . $model->img->url_path),
                'active_url' => (empty($model->activeImg) ? '' : \Yii::$app->params['uploadConfig']['imageUrlPrefix'] . $model->activeImg->url_path),
                'url' => $model->url,
                'auth_url' => $model->auth_url,
                'description' => $model->description,
                'dev_account' => $model->admin->account,
                'dev_account_type_show' => Admin::$_auth_types[$model->admin->auth_type],
                'dev_account_type' => $model->admin->auth_type,
                'staff_number' => $model->admin->staff_number,
                'game_type' => $model->game_type,
                'game_type_show' => Game::$_types[$model->game_type],
                'updated_at' => $model->updated_at,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }


    public function actionDelete()
    {
        $system_id = Helpers::getRequestParam('system_id');
        SystemService::forbiddenSystem($system_id);

        return [];
    }
}