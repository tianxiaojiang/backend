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
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\services\SystemAdminService;
use yii\base\Behavior;

/**
 * 验证游戏
 * Class PrivilegeFilter
 * @package Backend\behavior
 */
class ValidateIsLogin extends Behavior
{
    public function validateIsLoggedIn()
    {
        $admin = \Yii::$app->user->identity;
        $ad_uid = $admin->ad_uid;
        $tokenId = $admin->jwt->Payload['jti']->getValue();
        $sid = Helpers::getRequestParam('sid');

        $systemAdminWhere = [
            'ad_uid' => $ad_uid,
        ];
        $isMaintain = Helpers::getRequestParam('isMaintain');
        if (empty($isMaintain)) {
            $systemAdminWhere['token_id'] = $tokenId;
        } else {
            $systemAdminWhere['setting_token_id'] = $tokenId;
        }

        if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
            $systemAdminRelation = SystemAdmin::findOne($systemAdminWhere);
        } else {
            $systemAdminWhere['systems_id'] = $sid;
            $systemAdminRelation = SystemUser::findOne($systemAdminWhere);
        }

        if (empty($systemAdminRelation))
            throw new CustomException(Lang::ERR_TOKEN_INVALID);
    }
}