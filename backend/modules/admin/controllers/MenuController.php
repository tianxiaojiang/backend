<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class MenuController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\SystemMenu';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);

        return $actions;
    }

    /**
     * 菜单维护里拉取的菜单列表
     * @return array
     */
    public function actionIndex()
    {
//        $sid = intval(Helpers::getRequestParam('sid'));
//        $menuType = ($sid === 1) ? SystemMenu::SM_TYPE_SETTING : SystemMenu::SM_TYPE_BUSINESS;
//        $menus = SystemMenu::find()->where(['sm_set_or_business' => $menuType])->indexBy('sm_id')->asArray()->all();
        $menus = SystemMenu::find()->indexBy('sm_id')->asArray()->all();

        $results = [];
        $sortType = Helpers::getRequestParam('useSort');
        if (empty($sortType)) {
            foreach ($menus as $item) {
                if ($item['sort_by'] == 0) {
                    $this->setSortNum1($menus, $results, $item);
                }
            }
            //如果有菜单排序非0，但前置菜单无权限，则直接跟最后
            if (!empty($menus)) {
                foreach ($menus as $menu) {
                    array_push($results, $menu);
                }
            }
        } else {
            $results = array_values($menus);
        }

        return $results;
    }

    /**
     * 批量更新排序
     */
    public function actionUpdateSort()
    {
        $params = Helpers::getRequestParam('nodes');

        $db = \Yii::$app->db;
        $db->beginTransaction();
        try{
            foreach ($params as $param) {
                $menu = SystemMenu::findOne(['sm_id' => $param['sm_id']]);
                $menu->setScenario('update');
                if (isset($param['sort_by'])) {
                    if ($param['sort_by'] == 0) {
                        $menu->sort_by = $param['sort_by'];
                    } else {
                        $prevMenu = SystemMenu::findOne(['sm_id' => intval($param['sort_by'])]);
                        if ($menu->sm_set_or_business === $prevMenu->sm_set_or_business) $menu->sort_by = $param['sort_by'];
                    }
                }
                if (isset($param['sm_parent_id'])) {
                    if ($param['sm_parent_id'] == 0) {
                        $menu->sm_parent_id = $param['sm_parent_id'];
                    } else {
                        $parentMenu = SystemMenu::findOne(['sm_id' => intval($param['sm_parent_id'])]);
                        if ($menu->sm_set_or_business === $parentMenu->sm_set_or_business) $menu->sm_parent_id = $param['sm_parent_id'];
                    }
                }
                $menu->save();
            }
        } catch (\Exception $exception) {
            $db->transaction->rollBack();
            \Yii::error($exception->getTraceAsString());
            throw new CustomException($exception->getMessage());
        }

        $db->transaction->commit();

        return [];
    }

    protected function setSortNum1(&$menus, &$results, $menu)
    {
        if ($menu['sort_by'] === 0) {
            array_unshift($results, $menu);
        } else {
            array_push($results, $menu);
        }
        unset($menus[$menu['sm_id']]);

        foreach ($menus as $m) {
            if ($m['sort_by'] === $menu['sm_id']) {
                return $this->setSortNum1($menus, $results, $m);
                break;
            }
        }
        return true;
    }
}