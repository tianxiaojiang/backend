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
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemPriv;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;

/**
 * 验证系统
 * Class PrivilegeFilter
 * @package Backend\behavior
 */
class ValidateSystem extends Behavior
{
    public function validateSystem()
    {
        //验证账号是否属于所请求的系统
        $systemId = Helpers::getRequestParam('sid');
        $system = System::findOne(['systems_id' => $systemId]);

        $admin = \Yii::$app->user->identity;
        if (!in_array($system->systems_id, ArrayHelper::getColumn($admin->systems, 'systems_id'))) {
            throw new CustomException('该认证账号没有访问'.$system->name.'系统的权限！');
        }
    }
}