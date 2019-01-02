<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;


use Backend\modules\common\models\BaseModel;
use yii\helpers\ArrayHelper;
use Backend\helpers\Helpers;

class SystemGroupGamePriv extends BaseModel
{
    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_group_game_priv';
    }

    //返回角色、游戏id对应的所有特殊权限
    public static function getPrivilegesByGroupIdAndGameId($groupId, $gameId)
    {
        $sourceDdData = self::find()->select('game_id, sp_id')->where(['sg_id' => $groupId, 'game_id' => $gameId])->indexBy('sp_id')->all();
        $allPrivileges = SystemPriv::getAll();
        $groupGamePrivileges = ArrayHelper::getColumn($sourceDdData, 'privilege');
        $selfPrivileges = \Yii::$app->user->identity->getPrivileges($gameId,'*');
        foreach ($allPrivileges as $sp_id => $allPrivilege) {
            if (!empty($groupGamePrivileges[$sp_id])) {
                $allPrivileges[$sp_id]['is_checked'] = 1;
            }
            if (empty($selfPrivileges[$sp_id])) {
                $allPrivileges[$sp_id]['chkDisabled'] = 1;
            }
        }

        return $allPrivileges;
    }

    //更新角色的一组权限
    public static function updateGroupGamePriv($groupId, $gamesPrivileges)
    {
        //所有带待操作的权限
        $allOperatingPrivileges = self::getPrivilegesByGroupIdAndGameIds($groupId);

        //数据库存在，传上来的没有，直接删除权限
        if (!empty($deletingGames = array_diff(array_keys($allOperatingPrivileges), array_keys($gamesPrivileges)))) {
            self::deleteDeductGames(['sg_id' => $groupId, 'game_id' => array_values($deletingGames)]);
        }

        $addGroupGamePrivileges = [];
        $delGroupGamePrivilegesWhere = [];
        foreach ($gamesPrivileges as $gameId => $gamePrivileges) {
            if (empty($allOperatingPrivileges[$gameId])) $allOperatingPrivileges[$gameId] = [];
            $diffGroupGamePrivileges = self::diffPrivileges($allOperatingPrivileges[$gameId], $gamePrivileges);

            //删除原有权限
            if (!empty($diffGroupGamePrivileges['delPrivilegesIds'])) {
                $delGroupGamePrivilegesWhere[] = [
                    'sg_id' => $groupId,
                    'game_id' => $gameId,
                    'sp_id' => array_values($diffGroupGamePrivileges['delPrivilegesIds'])
                ];
            }

            //组装好所有的添加权限数据
            foreach ($diffGroupGamePrivileges['addPrivilegesIds'] as $addPrivilegesId) {
                $addGroupGamePrivileges[] = [ 'sg_id' => $groupId, 'game_id' => $gameId, 'sp_id' => $addPrivilegesId ];
            }
        }
        if (count($delGroupGamePrivilegesWhere) >= 2) array_unshift($delGroupGamePrivilegesWhere, 'or');

//        var_dump($delGroupGamePrivilegesWhere);exit;
        //一次性删除去除的权限
        self::deleteDeductGames($delGroupGamePrivilegesWhere);
        //一次性添加新增权限
        self::createAddGames($addGroupGamePrivileges);

        return true;
    }

    //一次性获取所有需要操作的角色游戏对应的权限
    public static function getPrivilegesByGroupIdAndGameIds($groupId)
    {
        $allOperateingPrivileges = self::find()->select('game_id, sp_id')->where(['sg_id' => $groupId])->asArray()->all();
        $res = [];
        foreach ($allOperateingPrivileges as $allOperateingPrivilege) {
            @$res[$allOperateingPrivilege['game_id']][] = $allOperateingPrivilege['sp_id'];
        }
        return $res;
    }

    //删除去掉的游戏权限
    protected static function deleteDeductGames($where = [])
    {
        if (empty($where)) return false;
        self::deleteAll($where);

        return true;
    }

    //添加新增的权限
    protected static function createAddGames(&$addPrivilegesIds)
    {
        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['sg_id', 'game_id', 'sp_id'],//字段
            $addPrivilegesIds
        )->execute();

        return true;
    }

    /**
     * 新旧权限对比
     * @param array $oldPrivileges
     * @param array $newPrivileges
     * @return [addPrivList,delPrivList]
     */
    public static function diffPrivileges(&$oldPrivilegeListArr, &$newPrivilegesListArr) {

        $addPrivilegesIds = array_diff($newPrivilegesListArr, $oldPrivilegeListArr);
        $delPrivilegesIds = array_diff($oldPrivilegeListArr, $newPrivilegesListArr);

        return ['addPrivilegesIds' => $addPrivilegesIds, 'delPrivilegesIds' => $delPrivilegesIds];

    }

    public function getPrivilege()
    {
        return $this->hasOne(SystemPriv::class, ['sp_id' => 'sp_id']);
    }
}