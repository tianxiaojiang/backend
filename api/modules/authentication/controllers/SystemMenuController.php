<?php

namespace Api\modules\authentication\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\common\controllers\JwtController;
use Backend\modules\common\controllers\SystemController;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemMenuController extends SystemController
{
    public $modelClass = 'Backend\modules\admin\models\SystemMenu';

    public function actionShowMenus()
    {
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));
        $callback = Helpers::getRequestParam('callback');
        //维护后台的菜单取维护菜单
        $menuType = $isMaintain ? SystemMenu::SM_TYPE_SETTING : SystemMenu::SM_TYPE_BUSINESS;

        $systemMenu = new SystemMenu();
        $menus = $systemMenu->getShowMenus($menuType);

        if(!empty($callback)) {//业务后台
            $callback = Helpers::getRequestParam('callback');
            echo $callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => $menus]) . ')';exit;
        }

        return $menus;
    }


    //获取指定操作的权限列表
    public function actionPrivileges()
    {
        $gameId = intval(Helpers::getRequestParam('game_id'));
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));
        $callback = Helpers::getRequestParam('callback');
        $requestActions = array_flip(explode(',', trim(Helpers::getRequestParam('actions'))));

        //维护后台的权限列表
        $privilegeType = $isMaintain ? SystemPriv::PRIVILEGE_TYPE_SETTING : SystemPriv::PRIVILEGE_TYPE_BUSINESS;
        $privileges = \Yii::$app->user->identity->getPrivileges($gameId, $privilegeType);
        $privilegesValue = array_map(function ($col) {
            return $item = '/' . $col['sp_module'] . '/' . $col['sp_controller'] . '/' . $col['sp_action'];
        }, $privileges);

        foreach ($requestActions as $requestAction => $val) {
            $requestActions[$requestAction] = in_array($requestAction, $privilegesValue);
        }

        $privilegesRes = ["privileges" => $requestActions];
        if(!empty($callback)) {//jsonp调用
            echo $callback . '(' . json_encode(['code' => 0, 'msg' => '', 'data' => $privilegesRes]) . ')';exit;
        }

        return $privilegesRes;
    }
}