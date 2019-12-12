<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/11/7
 * Time: 11:51
 */

namespace Backend\modules\admin\services;


use Api\modules\authentication\models\AccessToken;
use Backend\Exception\CustomException;
use Backend\helpers\Lang;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\services\admin\DomainAuthSoapClient;

class AdminService
{

    /**
     * 针对某个系统的某个用户执行踢线操作
     * @param $ad_uid
     * @param $sid
     * @return bool
     */
    public static function punishAdmin($ad_uid, $sid)
    {
        $adminSystemRelation = SystemUser::findOne(['systems_id' => $sid, 'ad_uid' => $ad_uid]);
        if (empty($adminSystemRelation->token_id)) return true;

        $adminSystemRelation->token_id = '';
        $adminSystemRelation->save();

        return true;
    }

    /**
     * 验证要登录的账号是否存在
     * @param $model
     * @return bool
     * @throws CustomException
     */
    public static function validateModelEmpty($model)
    {
        if (empty($model) or !$model instanceof AccessToken)
            throw new CustomException(Lang::ERR_LOGIN_FAIL);
        else
            return true;
    }

    /**
     * 验证管理员的状态
     * @param $model
     * @return bool
     * @throws CustomException
     */
    public static function validateLoginAdminStatus($model)
    {
        if ($model->status != AccessToken::STATUS_NORMAL)
            throw new CustomException(Lang::ERR_STATUS_FORBIDDEN);
        else
            return true;
    }

    /**
     * 校验管理员是否重置过密码
     * @param $model
     * @throws CustomException
     */
    public static function validateResetPassword($model)
    {
        if ($model->auth_type == Admin::AUTH_TYPE_PASSWORD && $model->reset_password == Admin::RESET_PASSWORD_NO)
            throw new CustomException('初始化密码过于简单，请先重置密码！');
    }

    /**
     * 验证用户是否有访问某个系统的权限
     * @param array $systemIds
     * @param Admin $admin
     */
    public static function validateHasSystemPermission($systemId, array $systemIds)
    {
        if (!in_array($systemId, $systemIds))
            throw new CustomException('您没有访问此系统的权限');
        else
            return true;
    }

    /**
     * 验证所有角色的权限级别，跟要验证的级别做检查
     * @param $privilege_level
     * @param $privilege_levels
     * @return bool
     */
    public static function validateHasSystemBusinessOrSetting($privilege_checked_level, $privilege_levels)
    {
        $total_privilege_level = 0;
        foreach ($privilege_levels as $privilege_level) {
            $total_privilege_level |= $privilege_level;
        }

        $tips = [
            SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT => '业务',
            SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN => '管理',
        ];
        if (($total_privilege_level & $privilege_checked_level) != $privilege_checked_level)
            throw new CustomException(sprintf('你没有此系统的%s权限', $tips[$privilege_checked_level]));
    }

}