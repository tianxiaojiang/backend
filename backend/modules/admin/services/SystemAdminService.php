<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2019/1/24
 * Time: 14:46
 */

namespace Backend\modules\admin\services;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemUser;

class SystemAdminService
{
    public static function checkUseNewSystemAdminSchedule()
    {
        $systemAdmin = SystemAdmin::find()->one();
        return !empty($systemAdmin);
    }

    /**
     * 添加子系统的管理员权限
     * @param $ad_uid
     * @return SystemAdmin|null
     */
    public static function addSystemAdmin($ad_uid, $sid)
    {
        if (self::checkUseNewSystemAdminSchedule())
            $systemAdmin = self::addNewSystemAdmin($ad_uid);
        else
            $systemAdmin = self::addOldSystemAdmin($ad_uid, $sid);

        return $systemAdmin;
    }

    /**
     * 检查某个子系统中的某个管理员是否有权限
     * @param $ad_uid
     * @param $sid
     * @return bool
     */
    public static function checkAdminInSystem($ad_uid, $sid)
    {
        if (self::checkUseNewSystemAdminSchedule())
            $result = self::checkNewSystemAdminInSystem($ad_uid, $sid);
        else
            $result = self::checkOldSystemAdminInSystem($ad_uid, $sid);

        if ($result)
            return true;
        else
            throw new CustomException('该用户无此系统的访问权限');
    }


    /**
     * 删除子系统的管理员
     * @param $system_admin_id
     * @return bool|false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteSystemAdmin($ad_uid, $sid)
    {
        if (self::checkUseNewSystemAdminSchedule())
            $result = self::deleteNewSystemAdmin($ad_uid);
        else
            $result = self::deleteOldSystemAdmin($ad_uid, $sid);

        if (!$result) {
            \Yii::error(sprintf("老的系统用户删除失败! ad_uid: %s, sid: %s", $ad_uid, $sid));
            throw new CustomException('用户删除失败');
        }
        return true;
    }


    /**
     * 旧的方式添加系统用户
     * @param $ad_uid
     * @param $sid
     * @return SystemUser|null
     */
    protected static function addOldSystemAdmin($ad_uid, $sid)
    {
        $systemAdmin = SystemUser::findOne(['ad_uid' => $ad_uid, 'systems_id' => $sid]);
        if (!empty($systemAdmin)) return $systemAdmin;

        $systemAdmin = new SystemUser();
        $systemAdmin->ad_uid = $ad_uid;
        $systemAdmin->systems_id = $sid;
        $systemAdmin->save();

        return $systemAdmin;
    }

    /**
     * 新的方式添加系统用户
     * @param $ad_uid
     * @param $sid
     * @return SystemAdmin|null
     */
    protected static function addNewSystemAdmin($ad_uid) {
        $systemAdmin = SystemAdmin::findOne(['ad_uid' => $ad_uid]);
        if (!empty($systemAdmin)) return $systemAdmin;

        $systemAdmin = new SystemAdmin();
        $systemAdmin->ad_uid = $ad_uid;
        $systemAdmin->save();

        return $systemAdmin;
    }

    /**
     * 老的方式检查用户是否有权限
     * @param $ad_uid
     * @param $sid
     * @return bool
     */
    protected static function checkOldSystemAdminInSystem($ad_uid, $sid)
    {
        $systemAdmin = SystemUser::findOne(['ad_uid' => $ad_uid, 'systems_id' => $sid]);
        return !empty($systemAdmin);
    }

    /**
     * 新的方式检查用户是否有权限
     * @param $ad_uid
     * @param $sid
     * @return bool
     */
    protected static function checkNewSystemAdminInSystem($ad_uid, $sid)
    {
        $tmpSid = Helpers::getRequestParam('sid');
        Helpers::$request_params['sid'] = $sid;
        $systemAdmin = SystemAdmin::findOne(['ad_uid' => $ad_uid]);
        Helpers::$request_params['sid'] = $tmpSid;
        return !empty($systemAdmin);
    }

    /**
     * 老方式删除系统用户
     * @param $ad_uid
     * @param $sid
     * @return bool|false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected static function deleteOldSystemAdmin($ad_uid, $sid)
    {
        $systemAdmin = SystemUser::findOne(['ad_uid' => $ad_uid, 'systems_id' => $sid]);
        if (empty($systemAdmin)) return true;

        return $systemAdmin->delete();
    }

    /**
     * 新的方式删除系统用户
     * @param $ad_uid
     * @return bool|false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected static function deleteNewSystemAdmin($ad_uid)
    {
        //删除
        $systemAdmin = SystemAdmin::findOne(['ad_uid' => $ad_uid]);
        if (empty($systemAdmin)) return true;

        return $systemAdmin->delete();
    }
}