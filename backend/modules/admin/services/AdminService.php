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

class AdminService
{
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
}