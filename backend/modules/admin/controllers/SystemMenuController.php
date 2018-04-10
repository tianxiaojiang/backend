<?php

namespace Backend\modules\admin\controllers;

use Backend\modules\admin\models\SystemMenu;
use Backend\modules\common\controllers\CommonController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemMenuController extends CommonController
{
    public $modelClass = 'Backend\modules\admin\models\SystemMenu';

    public function actionShowMenus()
    {
        $systemMenu = new $this->modelClass();
        $menus = $systemMenu->getShowMenus();

        return $menus;
    }
}