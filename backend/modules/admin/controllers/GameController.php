<?php

namespace Backend\modules\admin\controllers;

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

    public function formatModel($models)
    {
        $currentSystem = SystemService::getCurrentSystem();
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'game_id' => $model->game_id,
                'name' => $model->name,
                'status' => Game::$_status[$model->status],
                'alias' => $model->alias,
                'game_type' => $currentSystem->game_type,
                'updated_at' => $model->updated_at,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }
}