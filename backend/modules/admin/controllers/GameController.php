<?php

namespace Backend\modules\admin\controllers;

use Backend\actions\CreateAction;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class GameController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\Game';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);

        return $actions;
    }

    public function formatModel($models)
    {
//        $currentSystem = SystemService::getCurrentSystem();
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'game_id' => $model->game_id,
                'name' => $model->name,
                'status' => Game::$_status[$model->status],
                'alias' => $model->alias,
                'type' => $model->type,
                'show_game_type' => Game::$_types[$model->type],
                'updated_at' => $model->updated_at,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }

    public function actionUpdate()
    {
        $model = Game::findOne(['game_id' => Helpers::getRequestParam('business_game_id')]);

        $model->scenario = 'update';
        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if (isset($model->updated_at)) {
            $model->updated_at = date('Y-m-d H:i:s');
        }

        if ($model->validate()) {
            if ($model->save()) {
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        } else {
            $errors = $model->getErrors();
            $error  = array_shift($errors);
            \Yii::error(var_export($error, true));
            throw new CustomException($error[0]);
        }

        return $model;
    }
}