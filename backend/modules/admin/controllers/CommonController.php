<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/30
 * Time: 14:09
 */

namespace Backend\modules\admin\controllers;


use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\services\SystemService;

class CommonController extends \Backend\modules\common\controllers\BaseController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    /**
     * 拉取管理员的可选状态列表
     * @return array
     */
    public function actionAdminStats()
    {
        return ['stats' => Admin::$_status];
    }

    /**
     * 拉取游戏状态列表
     * @return array
     */
    public function actionGameStats()
    {
        return ['stats' => Game::$_status];
    }

    /**
     * 拉取系统状态列表
     * @return array
     */
    public function actionSystemStats()
    {
        return ['stats' => System::$_status];
    }

    /**
     * 菜单下拉选项，供权限选择
     * @return array
     */
    public function actionMenuSelect()
    {
        $sid = intval(Helpers::getRequestParam('sid'));
        $menuType = ($sid === 1) ? SystemMenu::SM_TYPE_SETTING : SystemMenu::SM_TYPE_BUSINESS;
        $systemMenu = new SystemMenu();
        $menus = $systemMenu::find()->where([
                'sm_set_or_business' => $menuType,
                'sm_status'=> 0,
                'is_show_sidebar' => 1
            ])
            ->orderBy('sort_by asc,sm_id asc')
            ->indexBy('sm_id')->asArray()->all();

        $treeMenus = $systemMenu->formatList2Tree($menus);

        $result = [];
        foreach ($treeMenus as $treeMenu) {
            $loopDepth = 0;
            $result[] = $this->formatMenu($treeMenu, $loopDepth);
            if (!isset($treeMenu['list'])) continue;
            foreach ($treeMenu['list'] as $child) {
                $loopDepth = 2;
                $result[] = $this->formatMenu($child, $loopDepth);
                if (!isset($child['list'])) continue;
                foreach ($child['list'] as $grandson) {
                    $loopDepth = 4;
                    $result[] = $this->formatMenu($grandson, $loopDepth);
                }
            }
        }

        return $result;
    }

    public function actionSystems()
    {
        $systems = System::findAll(['status' => System::SYSTEM_STAT_NORMAL]);

        return ['systems' => $systems];
    }

    protected function formatMenu($treeMenu, $loopDepth)
    {
        return [
            'sm_id' => $treeMenu['sm_id'],
            'sm_label' => str_pad('', $loopDepth, "--") . ' ' . $treeMenu['sm_label'],
            'sm_parent_id' => $treeMenu['sm_parent_id'],
            'sm_view' => $treeMenu['sm_view'],
        ];
    }
}