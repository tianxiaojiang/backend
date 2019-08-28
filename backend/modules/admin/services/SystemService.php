<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/11/5
 * Time: 11:16
 */

namespace Backend\modules\admin\services;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;

/**
 * 系统业务处理
 * Class SystemService
 * @package Backend\modules\admin\services
 */
class SystemService
{
    public static $currentSystem = null;

    /**
     * 获取当前的系统对象
     * @return Backend\modules\admin\models\System
     */
    public static function getCurrentSystem()
    {
        if (self::$currentSystem === null) {
            //获取当前请求的所在系统
            self::$currentSystem = System::findOne(['systems_id' => intval(Helpers::getRequestParam('sid'))]);
        }

        return self::$currentSystem;
    }

    /**
     * 禁止系统
     * @param $system_id
     * @return bool
     * @throws CustomException
     */
    public static function forbiddenSystem($system_id)
    {
        $systemObj = System::findOne(['system_id' => $system_id]);
        //验证非空性
        self::validateSystemExist($systemObj);
        self::validateDuplicationForbidden($systemObj);
        //修改状态
        $systemObj->status = System::SYSTEM_STAT_FORBIDDEN;
        $systemObj->save();
        return true;
    }

    /**
     * 验证系统存在与否
     * @param $systemObj
     * @throws CustomException
     */
    public static function validateSystemExist($systemObj)
    {
        if (!$systemObj instanceof System)
            throw new CustomException('系统不存在');
    }

    /**
     * 验证是否重复禁用
     * @param $systemObj
     * @throws CustomException
     */
    public static function validateDuplicationForbidden($systemObj)
    {
        if ($systemObj->status === System::SYSTEM_STAT_FORBIDDEN)
            throw new CustomException('系统已经被禁止，不可再次禁用');
    }

    /**
     * 验证系统状态正常
     * @param $systemObj
     * @throws CustomException
     */
    public static function validateSystemNormal($systemObj)
    {
        if ($systemObj->status !== System::SYSTEM_STAT_NORMAL)
            throw new CustomException('系统状态异常，请联系平台管理员');
    }

    /**
     * 导入系统用户
     * @param $file 上传的用户json文件
     * @return array 旧的ad_uid对应的新ad_uid
     */
    public static function importDevelopAdmin($users, $systems_id)
    {
        if (empty($users))
            throw new CustomException('导入用户不能为空');

        //保存一下，已经存在的账号
        $exitedAccounts = '';
        $uidMaps = [];
        foreach ($users['RECORDS'] as $RECORD) {
            //检查数据正常
            if (!self::checkAdminData($RECORD)) continue;

            //是否已存在
            $onlineAdmin = Admin::findOne(['account' => $RECORD['account'], 'auth_type' => $RECORD['auth_type']]);
            if (!empty($onlineAdmin)) {
                $foo = $RECORD['ad_uid'] .'_' . $RECORD['auth_type'] . '_' . $RECORD['account'];
                $exitedAccounts .= empty($exitedAccounts) ? $foo : ',' . $foo;
            } else {
                $onlineAdmin = new Admin();
            }

            $onlineAdmin->staff_number = $RECORD['staff_number'];
            $onlineAdmin->auth_type = $RECORD['auth_type'];
            $onlineAdmin->password_algorithm_system = $RECORD['password_algorithm_system'];
            $onlineAdmin->account = $RECORD['account'];
            $onlineAdmin->passwd = $RECORD['passwd'];
            $onlineAdmin->salt = $RECORD['salt'];
            $onlineAdmin->mobile_phone = $RECORD['mobile_phone'];
            $onlineAdmin->username = $RECORD['username'];
            $onlineAdmin->access_token = '';
            $onlineAdmin->access_token_expire = 0;
            $onlineAdmin->status = $RECORD['status'];
            $onlineAdmin->created_at = $RECORD['created_at'];
            $onlineAdmin->updated_at = $RECORD['updated_at'];
            $onlineAdmin->reset_password = $RECORD['reset_password'];
            $onlineAdmin->system_id = $systems_id;

            $onlineAdmin->save();
            $ad_uid = $onlineAdmin->ad_uid;

            $uidMaps[$RECORD['ad_uid']] = $ad_uid;
        }

        return [
            'uidMaps' => $uidMaps,
            'exitedAccounts' => $exitedAccounts
        ];
    }

    /**
     * 修改系统和用户的关系
     * @param $userRoleJsonFile
     * @param $uidMaps
     */
    public static function importSystemAdmin($uidMaps, $newSid)
    {
        if (empty($newSid))
            throw new CustomException('新系统不能为空!');

        //这里需要设置sid为新id
        Helpers::$request_params['sid'] = $newSid;
        $oldSystemAdmins = SystemAdmin::find()->all();
        foreach ($oldSystemAdmins as $oldSystemAdmin) {
            $newUid = intval(@$uidMaps[$oldSystemAdmin->ad_uid]);
            if (!empty($newUid) && $newUid != $oldSystemAdmin->ad_uid) {
                $oldSystemAdmin->ad_uid = $newUid;
                $oldSystemAdmin->save();
            }
        }

        //重置为1
        Helpers::$request_params['sid'] = 1;

        return true;
    }

    protected static function checkAdminData($adminArr)
    {
        //账号必须有salt
        if (empty($adminArr['salt'])) {
            \Yii::error('账号导入失败: 没有salt字段。数据: ' . json_encode($adminArr));
            return false;
        }

        //账号必须有原id
        if (empty($adminArr['ad_uid'])) {
            \Yii::error('账号导入失败: 没有ad_uid字段。数据: ' . json_encode($adminArr));
            return false;
        }

        //账号必须有账号
        if (empty($adminArr['account'])) {
            \Yii::error('账号导入失败: 没有account字段。数据: ' . json_encode($adminArr));
            return false;
        }

        //域账户的auth_type为0或2，staff_number非空
        if (($adminArr['auth_type'] === 0 || $adminArr['auth_type'] === 2) && $adminArr['staff_number'] > 0) {
            return true;
        }

        //普通账密
        if ($adminArr['auth_type'] === 1
            && !empty($adminArr['passwd'])
            && !empty($adminArr['password_algorithm_system'])
        ) {
            return true;
        }

        return false;
    }
}