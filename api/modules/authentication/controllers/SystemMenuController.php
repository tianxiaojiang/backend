<?php

namespace Api\modules\authentication\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\common\controllers\JwtController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemMenuController extends JwtController
{
    public $modelClass = 'Backend\modules\admin\models\SystemMenu';

    public function actionShowMenus()
    {
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));
        //维护后台的菜单取维护菜单
        $menuType = $isMaintain ? SystemMenu::SM_TYPE_SETTING : SystemMenu::SM_TYPE_BUSINESS;

        $systemMenu = new SystemMenu();
        $menus = $systemMenu->getShowMenus($menuType);
        if($isMaintain < 1) {//业务后台
            $callback = Helpers::getRequestParam('callback');
            echo $callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => $menus]) . ')';exit;
        }

        return $menus;
    }
}