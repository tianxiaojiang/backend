<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/23
 * Time: 19:22
 */
namespace Backend\behavior;

use Api\modules\authentication\models\AccessToken;
use Backend\Exception\CustomException;
use Backend\helpers\Lang;
use Backend\modules\admin\models\SystemPriv;
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
        AccessToken::validateAccess($controller->module->id, $controller->id, $controller->action->id, SystemPriv::PRIVILEGE_TYPE_SETTING);
    }

}