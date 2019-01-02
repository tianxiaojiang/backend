<?php

namespace Api\modules\open\controllers;

use Backend\helpers\Helpers;
use Api\modules\authentication\models\AccessToken;
use Backend\modules\common\controllers\SystemController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class PrivilegeController extends SystemController
{
    public $modelClass = 'Backend\modules\admin\models\SystemPriv';

    /**
     * 拉取用户所属的所有角色管理的所有游戏信息
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionCheck()
    {
        $closeValidatePrivilege = intval(Helpers::getRequestParam('close_check_privilege'));
        if ($closeValidatePrivilege) {
            return [];
        }
        $module = Helpers::getRequestParam('m');
        $controller = Helpers::getRequestParam('c');
        $action = Helpers::getRequestParam('a');
        AccessToken::validateAccess($module, $controller, $action);

        return [];
    }
}