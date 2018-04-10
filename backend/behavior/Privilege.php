<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/23
 * Time: 19:22
 */
namespace Backend\behavior;

use Backend\Exception\CustomException;
use Backend\helpers\Lang;
use yii\base\Behavior;

/**
 * 权限判断
 * Class PrivilegeFilter
 * @package Backend\behavior
 */
class Privilege extends Behavior
{

    public function canAccess()
    {
        $controller = \Yii::$app->controller;
        $path = '/' . $controller->module->id . '/' . $controller->id . '/' . $controller->action->id;

        $user = \Yii::$app->user->identity;

        //获取用户所有的权限菜单
        $systemPrivileges = $user->getPrivilege();

        $systemPrivilegesPaths = array_map(function ($col) {
            $col['path'] = '/' . $col['sp_module'] . '/' . $col['sp_controller'] . '/' . $col['sp_action'];
            return $col['path'];
        }, $systemPrivileges);

        if (empty($systemPrivileges) || !in_array($path, $systemPrivilegesPaths)) {
            throw new CustomException(Lang::ERR_NO_ACCESS);
        }
    }

}