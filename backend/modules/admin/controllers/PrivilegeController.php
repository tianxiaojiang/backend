<?php

namespace Backend\modules\admin\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class PrivilegeController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\SystemPriv';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);

        return $actions;
    }

    /**
     * 后台维护权限
     * @return array
     */
    public function actionIndex()
    {
//        $sid = intval(Helpers::getRequestParam('sid'));
//        $menuType = $sid === 1 ? SystemPriv::PRIVILEGE_TYPE_SETTING : SystemPriv::PRIVILEGE_TYPE_BUSINESS;
//        $privileges = SystemPriv::find()->where(['sp_set_or_business' => $menuType])->all();//取出所有的权限
        $privileges = SystemPriv::find()->all();//取出所有的权限

        $results = [];
        foreach ($privileges as $item) {
            $results[] = [
                'sp_id' => $item->sp_id,
                'sp_label' => $item->sp_label,
                'sp_parent_id' => $item->sp_parent_id,
                'sp_set_or_business' => $item->sp_set_or_business,
                'sp_module' => $item->sp_module,
                'sp_controller' => $item->sp_controller,
                'sp_action' => $item->sp_action,
                'sm_id' => $item->sm_id,
            ];
        }

        return $results;
    }
}