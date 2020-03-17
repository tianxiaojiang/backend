<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemGame;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\admin\models\SystemGroupGamePriv;
use Backend\modules\admin\models\SystemGroupPriv;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\admin\services\SystemService;
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

    public function actions()
    {
        $actions =  parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function prepareDataProvider()
    {
        //增加游戏过滤
        $gameId = Helpers::getRequestParam('game_id');
        $currentSystem = SystemService::getCurrentSystem();

        $this->query = SystemGroup::find();

        $where = [];
        if (intval($gameId) > 0) {//如果有游戏id，则只获取跟游戏id关联的单游戏角色
            $roleIds = ArrayHelper::getColumn(SystemGroupGame::find()->select(['sg_id'])->where(['game_id' => $gameId])->all(), 'sg_id');
            $where['sg_id'] = $roleIds;
        }

        //可修改的角色，只拉取通用角色
        $where['kind'] = SystemGroup::SYSTEM_ROLE_KIND_COMMON;

        //增加搜索条件
        $roleName = Helpers::getRequestParam("role_name");
        if (!empty($roleName)) {
            $this->query->andWhere(['like', 'sg_name', $roleName]);
        }

        $this->query->andWhere($where)->orderBy('sg_id asc');

        return parent::prepareDataProvider();
    }

    public function formatModel($models)
    {
        //增加游戏过滤
        $gameId = Helpers::getRequestParam('game_id');

        $result = [];
        foreach ($models as $model) {
            if (intval($gameId) === -1 || in_array($gameId, ArrayHelper::getColumn($model->gameIds, 'game_id'))) {//如果有游戏id，则只获取跟游戏id关联的角色
                $disabled = '0';
            } else {
                $disabled = '1';
            }
            $games = join(',', ArrayHelper::getColumn($model->systemGame, "name"));

            $result[] = [
                'sg_id' => $model->sg_id,
                'sg_name' => $model->sg_name,
                'sg_desc' => $model->sg_desc,
                'game_name' => $games,
                'disabled' => $disabled,
            ];
        }

        return $result;
    }

    /**
     * 角色的拥有的通用权限列表
     * @return array
     */
    public function actionGroupPrivilegeList()
    {
        $result = [
            'privileges' => [],//权限列表
            'games' => [],//游戏列表
        ];
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $gameId = intval(Helpers::getRequestParam('game_id'));

        $roleObj = SystemGroup::findOne($groupId);
        if (empty($roleObj))
            throw new CustomException('角色不存在');
        $roleGames = ArrayHelper::getColumn($roleObj->gameIds, 'game_id');

        //角色权限
        $groupPrivileges = ArrayHelper::getColumn(SystemGroupPriv::getPrivilegesIdsByGroupId($roleObj->sg_id), 'privilege');
        //所有权限
        $allPrivileges = SystemPriv::getAll();
        $selfPrivileges = \Yii::$app->user->identity->getPrivileges($gameId, '*');
        $selfPrivilegeIds = ArrayHelper::getColumn($selfPrivileges, 'sp_id');
        foreach ($allPrivileges as $sp_id => $allPrivilege) {
            if (!empty($groupPrivileges[$sp_id])) {//已有权限，默认选中
                $allPrivileges[$sp_id]['is_checked'] = 1;
            }
            if (!in_array($sp_id, $selfPrivilegeIds)) {//要禁掉的是自己没有的权限
                $allPrivileges[$sp_id]['chkDisabled'] = 1;
            }
        }

        $result['privileges'] = array_values($allPrivileges);

        //拉取游戏列表
        $currentSystem = SystemService::getCurrentSystem();

        //如果系统本身不区分游戏，只返回不区分游戏的标志
        if ($currentSystem === Game::GAME_TYPE_NONE) {
            $games = [['game_id' => '-1', 'name' => '不区分游戏', 'selected' => intval(in_array('-1', $roleGames))]];
        } else {
            $games = [];
            $gameWhere = ['status' => Game::GAME_STAT_NORMAL];
            //区分游戏的，只能设置为当前游戏
            if ($gameId != -1) {
                $gameWhere['game_id'] = $gameId;
            } else {
                $games = [['game_id' => '-1', 'name' => '不区分游戏', 'selected' => intval(in_array('-1', $roleGames))]];
            }
            if (empty($gameWhere['game_id'])) {
                $ids = SystemGame::getSystemGameIds();
                $gameWhere['game_id'] = $ids;
            }
            $realGames = Game::find()->where($gameWhere)->asArray()->all();
            foreach ($realGames as $key => $realGame) {
                if (in_array($realGame['game_id'], $roleGames)) {
                    $realGames[$key]['selected'] = 1;
                }
            }
            //系统管理员，增加不区分游戏按钮
            $games = array_merge($games, $realGames);
        }
        $result['games'] = $games;
        return $result;
    }

    /**
     * 更新角色的权限
     * @return array
     */
    public function actionGroupPrivilegeUpdate()
    {
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $on_game = explode(',', Helpers::getRequestParam('on_game'));
        $gameId = intval(Helpers::getRequestParam('game_id'));
        if ($gameId != -1 && !empty($on_game) && $gameId != implode($on_game)) {//区分游戏的只能设置当前登录的游戏
            throw new CustomException('你只能设置角色关联游戏为当前游戏');
        }

        //检查所选游戏是否为系统支持游戏
//        SystemGame::checkGameInSystem($on_game);

        $params = Helpers::getRequestParams();
        $newPrivileges = [];
        foreach ($params as $key => $val) {
            //组装通用权限数据
            substr($key, 0, 11) == 'privileges_' && array_push($newPrivileges, $val);
        }

        $group = SystemGroup::findOne(['sg_id' => $groupId]);

        /**
         * 单独游戏环境下，不能操作符合游戏的角色
         */
        if ($gameId > 0 && !$group->isSingeGameRole()) {
            throw new CustomException('您无法设置多游戏角色，如果您管理多个游戏，请切换到不区分游戏再尝试');
        }

        //我所选游戏拥有的所有权限
        $currentSystem = SystemService::getCurrentSystem();
        if (\Yii::$app->user->identity->ad_uid === $currentSystem->dev_account) {//管理员取所有权限
            $myPrivilege = SystemPriv::getAll();
        } else {
            $myPrivilege = \Yii::$app->user->identity->getPrivilegesOnGame($gameId, '*');
        }
        $myPrivilegeIds = ArrayHelper::getColumn($myPrivilege, 'sp_id');

        $oldPrivileges = SystemGroupPriv::getPrivilegesIdsByGroupId($group->sg_id);
        $diffPrivileges = SystemGroupPriv::diffPrivList($oldPrivileges, $newPrivileges, $myPrivilegeIds);

        $db = \Yii::$app->getDb()->beginTransaction();
        try {
            //设置权限修改
            SystemGroupPriv::deleteDeductPrivileges($groupId, $diffPrivileges['delPrivilegesIds']);
            SystemGroupPriv::createAddPrivileges($groupId, $diffPrivileges['addPrivilegesIds']);

            //修改完毕，校验角色的权限级别
            $sp_ids = SystemGroupPriv::find()->select('sp_id')->where(['sg_id' => $groupId])->all();
            $businessPriv = SystemPriv::findOne(['sp_set_or_business' => SystemPriv::PRIVILEGE_TYPE_BUSINESS, 'sp_id' => $sp_ids]);
            $settingPriv = SystemPriv::findOne(['sp_set_or_business' => SystemPriv::PRIVILEGE_TYPE_SETTING, 'sp_id' => $sp_ids]);
            $newPrivilegeLevel = 0;
            //重置角色的业务权限
            if (!empty($businessPriv)) {
                $newPrivilegeLevel |= SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT;
            }
            //重置角色的管理权限
            if (!empty($settingPriv)) {
                $newPrivilegeLevel |= SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN;
            }
            SystemGroup::setRolePrivilegeLevel($group, $newPrivilegeLevel);

            //设置管理游戏
            if ($gameId === -1) {
                $myGames = ArrayHelper::getColumn(Game::getAllGames(), 'game_id');
                array_push($myGames, -1);
            } else {
                $myGames = [$gameId];
            }
            SystemGroup::updateRoleGames($group, $on_game, $myGames);

        } catch (\Exception $exception) {
            $db->rollBack();
            $errMsg = $exception->getMessage();
            throw new CustomException($errMsg);
        }

        $db->commit();

        return [];
    }

    public function actionDelete()
    {
        $id = Helpers::getRequestParam("id");
        $gameId = Helpers::getRequestParam("game_id");
        $role = SystemGroup::findOne(['sg_id' => intval($id)]);
        if (empty($role)) {
            throw new CustomException('角色不存在');
        }

        if ($gameId > 0 && !$role->isSingeGameRole()) {
            throw new CustomException('您无法设置多游戏角色，如果您管理多个游戏，请切换到不区分游戏再尝试');
        }

        $role->delete();

        return [];
    }
}