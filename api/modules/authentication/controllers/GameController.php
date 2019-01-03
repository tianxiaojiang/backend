<?php

namespace Api\modules\authentication\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\LoginController;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class GameController extends LoginController
{
    public $modelClass = 'Backend\modules\admin\models\SystemGroupGame';

    /**
     * 拉取用户所属的所有角色管理的所有游戏信息
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionList()
    {
        $callback = Helpers::getRequestParam('callback');
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));

        $roleIds = \Yii::$app->user->identity->jwt->getSystemGroupIdsByToken();
        $gameIds = [];
        $roles = SystemGroup::findAll(['sg_id' => $roleIds]);
        $privilegeLevel = $isMaintain ? SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN : SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT;
        foreach ($roles as $role) {
            if (($role->privilege_level & $privilegeLevel) === $privilegeLevel) {
                $gameIds = array_merge($gameIds, ArrayHelper::getColumn($role->gameIds, 'game_id'));
            }
        }

        $games = Game::getAllGames(['in', 'game_id', $gameIds], 'game_id, name');
        //如果有所有权，则添加不区分游戏的所有游戏id
        $currentSystem = SystemService::getCurrentSystem();
        if (in_array(-1, $gameIds) && (!empty($isMaintain) || $currentSystem->game_type == Game::GAME_TYPE_NONE)) {
            array_unshift($games, ['game_id' => '-1', 'name' => '不区分游戏']);
        }


        if(!empty($callback)) {//jsonp的方式调用
            echo $callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => ['games' => $games]]) . ')';exit;
        }

        return ['games' => $games];
    }
}