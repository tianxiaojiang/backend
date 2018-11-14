<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\admin\models\SystemGroupGamePriv;
use Backend\modules\admin\models\SystemGroupPriv;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\common\controllers\BusinessController;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemGroupController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\SystemGroup';

    /**
     * 为 actionIndex 准备查询
     * @param array $where
     * @return array
     */
    public function prepareDataProvider($where = [])
    {
        //增加游戏过滤
        $gameId = Helpers::getRequestParam('game_id');

        $this->query = SystemGroup::find();

        if (intval($gameId) > 0) {//如果有游戏id，则只获取跟游戏id关联的角色
            $sgIds = ArrayHelper::getColumn(SystemGroupGame::findAll(['game_id' => $gameId]), 'group_id');
            $this->query->andWhere(['in', 'sg_id', $sgIds]);
        }
        $where = [];
        $this->query->andWhere($where)->orderBy('sg_id asc');

        return parent::prepareDataProvider();
    }

    /**
     * 角色的拥有的通用权限列表
     * @return array
     */
    public function actionGroupPrivilegeList()
    {
        $result = [
            'privileges' => [],//通用权限列表
            'subdivide_game' => 0,
            'games' => [],//游戏列表权限
            'common_privileges_modify' => 1,
        ];
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $gameId = intval(Helpers::getRequestParam('game_id'));
        if ($gameId > 0)
            $result['common_privileges_modify'] = 0;

        //角色通用权限
        $groupPrivileges = ArrayHelper::getColumn(SystemGroupPriv::getPrivilegesIdsByGroupId($groupId), 'privilege');
        //所有权限
        $allPrivileges = SystemPriv::getAll();
        //非管理员，只能操作自己拥有的权限
        $selfPrivileges = $allPrivileges;
        if (!in_array(1, \Yii::$app->user->identity->jwt->getSystemGroupIdsByToken())) {
            $selfPrivileges = \Yii::$app->user->identity->getPrivilege('*');
        }

        foreach ($allPrivileges as $sp_id => $allPrivilege) {
            if (!empty($groupPrivileges[$sp_id])) {
                $allPrivileges[$sp_id]['is_checked'] = 1;
            }
            if (empty($selfPrivileges[$sp_id])) {//要禁掉的是自己没有的权限
                $allPrivileges[$sp_id]['chkDisabled'] = 1;
            }
        }

        $result['privileges'] = array_values($allPrivileges);

        //管理游戏列表id
        $groupGames = SystemGroupGame::getGamesByGroupId($groupId);
        $groupGamesIds = ArrayHelper::getColumn($groupGames, 'game_id');

        if (!in_array('*', $groupGamesIds) && !empty($groupGamesIds)) {//不区分游戏
            $result['subdivide_game'] = 1;
        }

        //获取游戏权限
        $result['games'] = SystemGroupGame::getAllGameMarkByGroup($gameId, $groupGames);

        return $result;
    }

    /**
     * 更新角色的权限
     * @return array
     */
    public function actionGroupPrivilegeUpdate()
    {
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $params = Helpers::getRequestParams();

        $db = \Yii::$app->getDb()->beginTransaction();
        try{
            $newPrivileges = [];
            $newGames = [];
            $newGamePrivileges = [];
            if ($params['subdivide_game'] == 0) {
                //如果不区分游戏，则加一条通用数据和一条特殊空权限，一遍清空所有特权
                $newGames['*'] = 0;
                $newGamePrivileges['*'] = [];
            }
            foreach ($params as $key => $val) {

                //组装通用权限数据
                substr($key, 0, 11) == 'privileges_' && array_push($newPrivileges, $val);

                if ($params['subdivide_game'] == 0) continue;

                //组装游戏数据，0为通用权限，1为特权
                if (substr($key, 0, 13) == 'checked_game_') {
                    $gameId = str_replace('checked_game_', '', $key);
                    $newGames[$gameId] = intval($val);
                }
                //组装游戏特权数据
                if (substr($key, 0, 12) == 'gameSpecial_') {
                    $gameId = str_replace('gameSpecial_', '', $key);
                    if (intval($params['checked_game_' . $gameId]) === 0) {
                        $newGamePrivileges[$gameId] = [];//表示此游戏为通用权限，特殊权限为空
                    } elseif (intval($params['checked_game_' . $gameId]) === 1) {
                        $newGamePrivileges[$gameId] = explode(',', $val);//1表示此游戏为特殊权限
                    }
                }
            }

            //通用权限修改
            $oldPrivileges = SystemGroupPriv::getPrivilegesIdsByGroupId($groupId);
            $diffPrivileges = SystemGroupPriv::diffPrivList($oldPrivileges, $newPrivileges);
            SystemGroupPriv::deleteDeductPrivileges($groupId, $diffPrivileges['delPrivilegesIds']);
            SystemGroupPriv::createAddPrivileges($groupId, $diffPrivileges['addPrivilegesIds']);

            //管理游戏修改
            $newGameIds = array_keys($newGames);
            $oldGames = SystemGroupGame::getGamesByGroupId($groupId);
            $diffGames = SystemGroupGame::diffGames($oldGames, $newGameIds);
            SystemGroupGame::deleteDeductGames($groupId, $diffGames['delGamesIds']);
            SystemGroupGame::createAddGames($groupId, $diffGames['addGamesIds'], $newGames);
            SystemGroupGame::iterationUpdateProprietary($oldGames, $newGames);//对比需要修改的权限类型

            //特殊权限修改
            SystemGroupGamePriv::updateGroupGamePriv($groupId, $newGamePrivileges);

        } catch (\Exception $exception) {
            $db->rollBack();
            $errMsg = $exception->getMessage();
            throw new CustomException($errMsg);
        }

        $db->commit();

        return [];
    }
}