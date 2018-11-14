<?php

namespace Api\modules\authentication\controllers;

use Backend\helpers\Helpers;
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
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));
        $gameIds = \Yii::$app->user->identity->jwt->getGameIdsByToken();

        //角色不区分游戏，返回不区分的特殊id
        if(($isMaintain < 1 && implode('', $gameIds) == '*') || ($isMaintain == 1 && in_array('*', $gameIds))) {
            $games = [['game_id' => '*', 'name' => '不区分游戏']];
        } else {
            $games = Game::getAllGames(['in', 'game_id', $gameIds], 'game_id, name');
        }

        if($isMaintain < 1) {//业务后台
            $callback = Helpers::getRequestParam('callback');
            echo $callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => ['games' => $games]]) . ')';exit;
        }

        return ['games' => $games];
    }
}