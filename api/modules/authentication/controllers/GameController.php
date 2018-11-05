<?php

namespace Api\modules\authentication\controllers;

use Backend\modules\admin\models\Game;
use Backend\modules\common\controllers\JwtController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class GameController extends JwtController
{
    public $modelClass = 'Backend\modules\admin\models\SystemGroupGame';

    /**
     * 拉取用户所属的所有角色管理的所有游戏信息
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionList()
    {
        $gameIds = \Yii::$app->user->identity->jwt->getGameIdsByToken();

        $games = Game::getAllGames(['game_id' => ['in' => $gameIds]], 'game_id, name');

        return ['games' => $games];
    }
}