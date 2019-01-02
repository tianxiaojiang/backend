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
        return self::find()->where(['group_id' => $groupId])->indexBy('game_id')->all();
    }

    //删除去掉的游戏
    public static function deleteDeductGames($sgId, &$delGamesIds)
    {
        if (empty($delGamesIds)) return true;
        self::deleteAll(['group_id' => $sgId, 'game_id' => $delGamesIds]);

        return true;
    }

    //添加新增的游戏
    public static function createAddGames($sgId, &$addGamesIds, &$isProprietaryPrivArr)
    {
        if (empty($addGamesIds)) return true;

        //制作成二维数组
        $addGamesIds = array_map(function ($col) use ($sgId, $isProprietaryPrivArr) {
            return [
                'group_id' => $sgId,
                'game_id' => $col,
                'is_proprietary_priv' => $isProprietaryPrivArr[$col],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }, $addGamesIds);
        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['group_id','game_id', 'is_proprietary_priv', 'created_at', 'updated_at'],//字段
            $addGamesIds
        )->execute();

        \Yii::info('addGamesIds11:' . var_export($addGamesIds, true));
        return true;
    }

    /**
     * 新旧权限对比
     * @param array $oldGames
     * @param array $newGames
     * @return [addPrivList,delPrivList]
     */
    public static function diffGames(&$oldGames, &$newGames) {
        $oldGameListArr = ArrayHelper::getColumn($oldGames, 'game_id');
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

    /**
     * 遍历更新权限类型变动的角色游戏
     * @param array $oldGroupGames
     * @param array $newGameProprietaries
     * @return bool
     */
    public static function iterationUpdateProprietary(array $oldGroupGames, array $newGameProprietaries)
    {

        if (empty($newGameProprietaries)) return true;
        foreach ($newGameProprietaries as $gameId => $newProprietary) {
            if ($gameId == '*' || empty($oldGroupGames[$gameId])) continue;
            if ($oldGroupGames[$gameId]->is_proprietary_priv != $newProprietary) {
                $oldGroupGames[$gameId]->is_proprietary_priv = $newProprietary;
                $oldGroupGames[$gameId]->save();
            }
        }

        return true;
    }
}