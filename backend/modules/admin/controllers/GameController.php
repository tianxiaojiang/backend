<?php

namespace Backend\modules\admin\controllers;

use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\common\controllers\BusinessController;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class GameController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\Game';

    //过滤当前用户的所有角色下的游戏id
    public function prepareDataProvider()
    {
        $groups = \Yii::$app->user->identity->systemGroup;
        $gameIds = SystemGroupGame::find()->where(['group_id' => ArrayHelper::getColumn($groups, 'sg_id')])->asArray()->all();
        $this->query = Game::find()->where(['game_id' => ArrayHelper::getColumn($gameIds, 'game_id')]);

        return parent::prepareDataProvider();
    }
}