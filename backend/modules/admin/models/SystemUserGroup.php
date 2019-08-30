<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\modules\common\models\BaseModel;
use Backend\helpers\Helpers;

class SystemUserGroup extends BaseModel
{
    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_user_group';
    }

    public static function updateAdminUserGroup($systemAdminId, $newRoleIds, $oldRoleIds, $myRoleIds)
    {
        //if (empty($newRoleIds) || empty($systemAdminId) || empty($myRoleIds)) return true;

        $diffRoleIds = self::diffRoles($oldRoleIds, $newRoleIds, $myRoleIds);
        self::addRoleAdmin($diffRoleIds['addRoleIds'], $systemAdminId);
        self::delRoleAdmin($diffRoleIds['delRoleIds'], $systemAdminId);

        return true;
    }

    public static function delRoleAdmin($roleIds, $ad_uid)
    {
        if (empty($roleIds)) return true;

        self::deleteAll(['ad_uid' => $ad_uid, 'sg_id' => $roleIds]);
    }

    /**
     * 添加角色用户
     * @param $roleIds
     * @param $ad_uid
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function addRoleAdmin($roleIds, $ad_uid)
    {
        if (empty($roleIds)) return true;

        //制作成二维数组
        $addRoleAdmin = array_map(function ($col) use ($ad_uid) {
            return ['ad_uid' => $ad_uid, 'sg_id' => $col, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
        }, $roleIds);

        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['ad_uid','sg_id', 'created_at', 'updated_at'],//字段
            $addRoleAdmin
        )->execute();

        return true;
    }

    /**
     * 获取要删除和添加的角色id
     * @param array $oldRoleIds
     * @param array $newRoleIds
     * @param array $myRoleIds
     * @return array
     */
    public static function diffRoles(array $oldRoleIds, array $newRoleIds, array $myRoleIds)
    {
        $addRoleIds = array_diff($newRoleIds, $oldRoleIds);
        $delRoleIds = array_diff($oldRoleIds, $newRoleIds);

        $addRoleIds = array_intersect($addRoleIds, $myRoleIds);
        $delRoleIds = array_intersect($delRoleIds, $myRoleIds);

        return ['addRoleIds' => $addRoleIds, 'delRoleIds' => $delRoleIds];
    }
}