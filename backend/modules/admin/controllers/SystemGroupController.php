<?php

namespace Backend\modules\admin\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemGroup;
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
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $groupPrivilegesIds = ArrayHelper::getColumn(SystemGroupPriv::getPrivilegesByGroupId($groupId), 'sp_id');

        $allMenus = SystemMenu::getAllMenusByGroup();

        //获取分组权限
        $allPrivileges = SystemPriv::getPrivilegesByGroups($allMenus, $groupPrivilegesIds);

        return array_values($allPrivileges);
    }

    /**
     * 更新角色的权限
     * @return array
     */
    public function actionGroupPrivilegeUpdate()
    {
        $groupId = intval(Helpers::getRequestParam('group_id'));
        $params = Helpers::getRequestParams();
        $newPrivileges = [];
        foreach ($params as $key => $val) {
            if (substr($key, 0, 11) == 'privileges_') {
                array_push($newPrivileges, $val);
            }
        }
        $oldPrivileges = SystemGroupPriv::getPrivilegesByGroupId($groupId);

        $diffPrivileges = SystemGroupPriv::diffPrivList($oldPrivileges, $newPrivileges);

        SystemGroupPriv::deleteDeductPrivileges($groupId, $diffPrivileges['delPrivilegesIds']);
        SystemGroupPriv::createAddPrivileges($groupId, $diffPrivileges['addPrivilegesIds']);

        return [];
    }
}