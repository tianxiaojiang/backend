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

class SystemGroupGame extends BaseModel
{
    const GAME_TYPE_PRVI_COMMON = 0;//通用权限
    const GAME_TYPE_PRVI_PROPRIETARY = 1;//专有权限

    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_group_game';
    }

    //获取所有游戏中，标记好角色拥有的游戏
    public static function getAllGameMarkByGroup($groupGames)
    {
        $allGames = Game::getAllGames();

        foreach ($groupGames as $groupGame) {
            //复制专有游戏到所有数据结构里
            if (isset($allGames[$groupGame['game_id']])) {
                $allGames[$groupGame['game_id']]['is_checked'] = 1;
                $allGames[$groupGame['game_id']]['is_proprietary_priv'] = $groupGame['is_proprietary_priv'];
                if ($groupGame['is_proprietary_priv'] == SystemGroupGame::GAME_TYPE_PRVI_PROPRIETARY) {
                    $allGames[$groupGame['game_id']]['proprietary'] = array_values(SystemGroupGamePriv::getPrivilegesByGroupIdAndGameId($groupGame['group_id'], $groupGame['game_id']));
                }
            }
        }

        return $allGames;
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
    public static function createAddGames($sgId, &$addGamesIds, &$isProprietaryPrivArr)
    {
        //制作成二维数组
        $addGamesIds = array_map(function ($col) use ($sgId, $isProprietaryPrivArr) {
            return [
                'group_id' => $sgId,
                'game_id' => $col,
                'is_proprietary_priv' => $isProprietaryPrivArr[$col]
            ];
        }, $addGamesIds);

        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['group_id','game_id', 'is_proprietary_priv'],//字段
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