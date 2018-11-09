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
        $menuType = intval(Helpers::getRequestParam('menu_type'));

        $systemMenu = new SystemMenu();
        $menus = $systemMenu->getShowMenus($menuType);
        if($menuType < 1) {
            $callback = Helpers::getRequestParam('callback');
            echo $callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => $menus]) . ')';exit;
        }

        return $menus;
    }
}