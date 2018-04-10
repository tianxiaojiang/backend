<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;


class SystemGroupPriv extends \yii\db\ActiveRecord
{
    static public function tableName() {
        return 'system_group_priv';
    }

    public static function getPrivilegesByGroupId($groupId)
    {
        return SystemGroupPriv::find()->where(['sg_id' => $groupId])->asArray()->all();
    }

    //删除去掉的权限
    public static function deleteDeductPrivileges($sgId, $delPrivilegesIds)
    {
        self::deleteAll(['sg_id' => $sgId, 'sp_id' => $delPrivilegesIds]);

        return true;
    }

    //添加新增的权限
    public static function createAddPrivileges($sgId, &$addPrivilegesIds)
    {
        //制作成二维数组
        $addPrivilegesIds = array_map(function ($col) use ($sgId) {
            return ['sg_id' => $sgId, 'sp_id' => $col];
        }, $addPrivilegesIds);

        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['sg_id','sp_id'],//字段
            $addPrivilegesIds
        )->execute();

        return true;
    }

    /**
     * 新旧权限对比
     * @param array $oldPrivList
     * @param array $newPrivList
     * @return [addPrivList,delPrivList]
     */
    public static function diffPrivList(&$oldPrivileges, &$newPrivileges) {

        $oldPrivilegesListArr = array_column($oldPrivileges, 'sp_id');
        $newPrivilegesListArr = $newPrivileges;
        $addPrivilegesIds = array_diff($newPrivilegesListArr, $oldPrivilegesListArr);
        $delPrivilegesIds = array_diff($oldPrivilegesListArr, $newPrivilegesListArr);

        return ['addPrivilegesIds' => $addPrivilegesIds, 'delPrivilegesIds' => $delPrivilegesIds];

    }
}