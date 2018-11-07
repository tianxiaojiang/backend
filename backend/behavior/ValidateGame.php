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
use Backend\modules\admin\models\SystemPriv;
use yii\base\Behavior;

/**
 * 验证游戏
 * Class PrivilegeFilter
 * @package Backend\behavior
 */
class ValidateGame extends Behavior
{
    public function validateGame()
    {
        //验证游戏权限
        $gameId = Helpers::getRequestParam('game_id');
        \Yii::$app->user->identity->validateGame($gameId, \Yii::$app->user->identity->jwt->getGameIdsByToken());
    }
}