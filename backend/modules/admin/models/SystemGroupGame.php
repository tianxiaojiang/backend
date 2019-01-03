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
use yii\helpers\ArrayHelper;

class SystemGroupGame extends BaseModel
{
    const GAME_TYPE_PRVI_COMMON = 0;//通用权限
    const GAME_TYPE_PRVI_PROPRIETARY = 1;//专有权限

    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_group_game';
    }

    //获取所有游戏中，标记好角色拥有的游戏
    public static function getAllGameMarkByGroup($gameId, $groupGames)
    {
        $allGames = Game::getAllGames($gameId > 0 ? ['game_id' => $gameId] : []);

        foreach ($groupGames as $groupGame) {
            //复制专有游戏到所有数据结构里
            if (isset($allGames[$groupGame['game_id']])) {
                $allGames[$groupGame['game_id']]['is_checked'] = 1;
            }
        }

        return $allGames;
    }
    
    public static function getGamesByGroupId($groupId)
    {
        return self::find()->where(['group_id' => $groupId])->indexBy('game_id')->all();
    }

    //删除去掉的游戏
    public static function deleteDeductGames($sgId, &$delGamesIds)
    {
        if (empty($delGamesIds)) return true;
        self::deleteAll(['sg_id' => $sgId, 'game_id' => $delGamesIds]);

        return true;
    }

    //添加新增的游戏
    public static function createAddGames($sgId, &$addGamesIds)
    {
        if (empty($addGamesIds)) return true;

        //制作成二维数组
        $addGamesIds = array_map(function ($col) use ($sgId) {
            return [
                'sg_id' => $sgId,
                'game_id' => $col,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }, $addGamesIds);
        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['sg_id','game_id', 'created_at', 'updated_at'],//字段
            $addGamesIds
        )->execute();

        \Yii::info('addGamesIds11:' . var_export($addGamesIds, true));
        return true;
    }

    /**
     * 新旧游戏对比
     * @param array $oldGames
     * @param array $newGames
     * @param array $myGames
     * @return [addPrivList,delPrivList]
     */
    public static function diffGames(&$oldGameListArr, &$newGamesListArr, &$myGameIds) {
        $addGamesIds = array_diff($newGamesListArr, $oldGameListArr);
        $delGamesIds = array_diff($oldGameListArr, $newGamesListArr);

        //过滤自己所拥有的游戏id
        $addGamesIds = array_intersect($addGamesIds, $myGameIds);
        $delGamesIds = array_intersect($delGamesIds, $myGameIds);

        return ['addGamesIds' => $addGamesIds, 'delGamesIds' => $delGamesIds];
    }

    /**
     * 获取角色游戏对象
     * @param $sgId
     * @param $gameId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getOneByRoleAndGame($sgId, $gameId)
    {
        return self::find()->where(['game_id' => $gameId, 'group_id' => $sgId])->one();
    }
}