<?php

namespace Backend\modules\admin\controllers;

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
        $sid = intval(Helpers::getRequestParam('sid'));
        $menuType = ($sid === 1) ? SystemMenu::SM_TYPE_SETTING : SystemMenu::SM_TYPE_BUSINESS;
        $menus = SystemMenu::find()->where(['sm_set_or_business' => $menuType])->all();

        $results = [];
        foreach ($menus as $item) {
            $results[] = [
                'sm_id' => $item->sm_id,
                'sm_label' => $item->sm_label,
                'sm_parent_id' => $item->sm_parent_id,
                'sm_view' => $item->sm_view,
            ];
        }

        return $results;
    }
}