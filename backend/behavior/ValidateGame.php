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
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\admin\services\SystemService;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;

/**
 * 验证游戏
 * Class PrivilegeFilter
 * @package Backend\behavior
 */
class ValidateGame extends Behavior
{
    public function validateGame()
    {
        $admin = \Yii::$app->user->identity;
        $isMaintain = intval(Helpers::getRequestParam('isMaintain'));
        $privilegeType = $isMaintain ? SystemPriv::PRIVILEGE_TYPE_SETTING : SystemPriv::PRIVILEGE_TYPE_BUSINESS;
        $gameIds = $admin->getMyGameIds($privilegeType);

        //验证游戏权限
        $gameId = Helpers::getRequestParam('game_id');
        $admin->validateGame($gameId, $gameIds);
    }
}