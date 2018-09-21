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
        $this->query = SystemGroup::find();
        $where = [];
        $this->query->andWhere($where)->orderBy('sg_id asc');

        return parent::prepareDataProvider();
    }

    /**
     * 角色的权限列表
     * @return array
     */
    public function actionGroupPrivilegeList()
    {
        $result = [
            'games' => [],
            'privileges' => [],
        ];
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $groupPrivilegesIds = ArrayHelper::getColumn(SystemGroupPriv::getPrivilegesByGroupId($groupId), 'sp_id');
        $allMenus = SystemMenu::getAllMenusByGroup();

        //获取分组权限
        $allPrivileges = SystemPriv::getPrivilegesByGroups($allMenus, $groupPrivilegesIds);
        $allGames = SystemGroupGame::getAllGameMarkByGroup($groupId);
        $result['privileges'] = $allPrivileges;
        $result['games'] = $allGames;

        $result['special_privileges'] = SystemGroupGamePriv::getPrivilegesByGroupIdAndGameId($groupId, ArrayHelper::getColumn($allGames, 'game_id'));

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
            foreach ($params as $key => $val) {
                substr($key, 0, 11) == 'privileges_' && array_push($newPrivileges, $val);
                substr($key, 0, 5) == 'game_' && array_push($newGames, $val);
                if (substr($key, 0, 12) == 'gameSpecial_') {
                    $gameId = str_replace('gameSpecial_', '', $key);
                    !empty($params['game_' . $gameId]) && $newGamePrivileges[$gameId] = explode(',', $val);
                }
            }

            //通用权限修改
            $oldPrivileges = SystemGroupPriv::getPrivilegesByGroupId($groupId);
            $diffPrivileges = SystemGroupPriv::diffPrivList($oldPrivileges, $newPrivileges);
            SystemGroupPriv::deleteDeductPrivileges($groupId, $diffPrivileges['delPrivilegesIds']);
            SystemGroupPriv::createAddPrivileges($groupId, $diffPrivileges['addPrivilegesIds']);

            //管理游戏修改
            $oldGames = SystemGroupGame::getGamesByGroupId($groupId);
            $diffGames = SystemGroupGame::diffGames($oldGames, $newGames);
            SystemGroupGame::deleteDeductGames($groupId, $diffGames['delGamesIds']);
            SystemGroupGame::createAddGames($groupId, $diffGames['addGamesIds']);

            //特殊权限修改
            SystemGroupGamePriv::updateGroupGamePriv($groupId, $newGamePrivileges);

        } catch (\Exception $exception){
            $db->rollBack();
            throw new CustomException($exception->getMessage());
        }

        $db->commit();

        return [];
    }
}