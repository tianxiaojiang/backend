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

class SystemGroupGame extends BaseModel
{
    static public function tableName() {
        return 'system_group_game';
    }

    //获取所有游戏中，标记好角色拥有的游戏
    public static function getAllGameMarkByGroup($groupId)
    {
        $allGames = Game::getAllGames();
        $groupGamesIds = ArrayHelper::getColumn(self::find()->where(['group_id' => $groupId])->asArray()->all(), 'game_id');

        return array_map(function ($col) use ($groupGamesIds){
            return ['game_id' => $col['game_id'], 'name' => $col['name'], 'is_checked' => intval(in_array($col['game_id'], $groupGamesIds))];
        }, $allGames);
    }
    
    public static function getGamesByGroupId($groupId)
    {
        return self::find()->where(['group_id' => $groupId])->asArray()->all();
    }

    //删除去掉的游戏
    public static function deleteDeductGames($sgId, &$delGamesIds)
    {
        self::deleteAll(['group_id' => $sgId, 'game_id' => $delGamesIds]);

        return true;
    }

    //添加新增的游戏
    public static function createAddGames($sgId, &$addGamesIds)
    {
        //制作成二维数组
        $addGamesIds = array_map(function ($col) use ($sgId) {
            return ['group_id' => $sgId, 'game_id' => $col];
        }, $addGamesIds);

        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['group_id','game_id'],//字段
            $addGamesIds
        )->execute();

        return true;
    }

    /**
     * 新旧权限对比
     * @param array $oldGames
     * @param array $newGames
     * @return [addPrivList,delPrivList]
     */
    public static function diffGames(&$oldGames, &$newGames) {

        $oldGameListArr = array_column($oldGames, 'game_id');
        $newGamesListArr = $newGames;
        $addGamesIds = array_diff($newGamesListArr, $oldGameListArr);
        $delGamesIds = array_diff($oldGameListArr, $newGamesListArr);

        return ['addGamesIds' => $addGamesIds, 'delGamesIds' => $delGamesIds];

    }
}