<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\helpers\Helpers;

class SystemGroupPriv extends \yii\db\ActiveRecord
{
    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_group_priv';
    }

    public static function getPrivilegesIdsByGroupId($groupId)
    {
        return self::find()->where(['sg_id' => $groupId])->indexBy('sp_id')->all();
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
            return ['sg_id' => $sgId, 'sp_id' => $col, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
        }, $addPrivilegesIds);

        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['sg_id','sp_id', 'created_at', 'updated_at'],//字段
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
    public static function diffPrivList(&$oldPrivileges, &$newPrivileges, &$myPrivilegeIds) {

        $oldPrivilegesListArr = array_column($oldPrivileges, 'sp_id');
        $newPrivilegesListArr = $newPrivileges;
        $addPrivilegesIds = array_diff($newPrivilegesListArr, $oldPrivilegesListArr);
        $delPrivilegesIds = array_diff($oldPrivilegesListArr, $newPrivilegesListArr);

        $addPrivilegesIds = array_intersect($addPrivilegesIds, $myPrivilegeIds);//要增加的权限跟已有的取交集
        $delPrivilegesIds = array_intersect($delPrivilegesIds, $myPrivilegeIds);//要删除的权限跟已有的取交集

        return ['addPrivilegesIds' => $addPrivilegesIds, 'delPrivilegesIds' => $delPrivilegesIds];

    }

    public function getPrivilege()
    {
        return $this->hasOne(SystemPriv::class, ['sp_id' => 'sp_id']);
    }
}