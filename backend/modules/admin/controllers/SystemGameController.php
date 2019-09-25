<?php

namespace Backend\modules\admin\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\SystemGame;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemGameController extends BusinessController
{
    public $modelClass = SystemGame::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);

        return $actions;
    }

    public function prepareDataProvider()
    {
        $systemGameId = intval(Helpers::getRequestParam("system_game_id"));

        $this->query = SystemGame::find();
        if ($systemGameId > 0) {
            $this->query->andWhere(['game_id' => $systemGameId]);
        }

        return parent::prepareDataProvider();
    }

    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'game_id' => $model->gameIdentity->game_id,
                'name' => $model->gameIdentity->name,
                'status' => Game::$_status[$model->gameIdentity->status],
                'alias' => $model->gameIdentity->alias,
                'type' => $model->gameIdentity->type,
                'show_game_type' => Game::$_types[$model->gameIdentity->type],
                'updated_at' => $model->updated_at,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }


    public function actionCreate()
    {
        $system_game = Helpers::getRequestParam('system_game_id');
        $newIds = array_keys($system_game);

        SystemGame::updateGames($newIds);

        return [];
    }
}






