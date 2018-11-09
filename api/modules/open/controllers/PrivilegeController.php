<?php

namespace Api\modules\open\controllers;

use Api\modules\authentication\models\AccessToken;
use Backend\helpers\Helpers;
use Backend\modules\common\controllers\BaseController;
use Backend\modules\common\controllers\JwtController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class PrivilegeController extends JwtController
{
    public $modelClass = 'Backend\modules\admin\models\SystemPriv';

    /**
     * 拉取用户所属的所有角色管理的所有游戏信息
     * @return array
     * @throws \Backend\Exception\CustomException
     */
    public function actionCheck()
    {
        $module = Helpers::getRequestParam('m');
        $controller = Helpers::getRequestParam('c');
        $action = Helpers::getRequestParam('a');
        AccessToken::validateAccess($module, $controller, $action);

        return [];
    }
}