<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 9:50
 */

namespace Backend\behavior;


use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use yii\base\Behavior;

class ModulePrivileges extends Behavior
{
    public function verifyModulePrivilege() {

        $user = \Yii::$app->user->identity;
        $path = Helpers::getRequestParam('api');
        $params = explode('/', $path);

        list($actionName, $controllerName, $moduleName) = array_reverse($params);
        if (empty($controllerName)) $controllerName = 'system';
        if (empty($moduleName)) $moduleName = 'system';
        $path = '/' . $moduleName . '/' . $controllerName . '/' .$actionName;

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