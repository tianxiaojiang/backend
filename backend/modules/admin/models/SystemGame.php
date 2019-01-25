<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use \Backend\modules\common\models\BaseModel;
use yii\helpers\ArrayHelper;

class SystemGame extends BaseModel
{
    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_game';
    }

    public function fields()
    {
        return ['game_id', 'order_by', 'created_at', 'updated_at'];
    }

    public function getGameIdentity()
    {
        return $this->hasOne(Game::class, ['game_id' => 'game_id']);
    }

    public static function checkGameInSystem($gameIds)
    {
        $counts = count($gameIds);
        $countsInDb = self::find()->where(['game_id' => $gameIds])->count('game_id');

        \Yii::debug('select counts from system_Game'. var_export($countsInDb, true));

        if ($counts != $countsInDb)
            throw new CustomException('所选游戏中有些本系统不支持');

        return true;
    }

    public static function getSystemGameIds()
    {
        return ArrayHelper::getColumn(self::find()->all(), 'game_id');
    }

    /**
     * 更新本系统负责的游戏
     * @param $newGameIds
     * @return bool
     */
    public static function updateGames($newGameIds)
    {
        $oldGameIds = self::getSystemGameIds();
        $diffGameIds = self::diffGames($oldGameIds, $newGameIds);

        self::createAddGames($diffGameIds['addGamesIds']);
        self::deleteDeductGames($diffGameIds['delGamesIds']);

        return true;
    }
    
    //删除去掉的游戏
    public static function deleteDeductGames(&$delGamesIds)
    {
        if (empty($delGamesIds)) return true;
        self::deleteAll(['game_id' => $delGamesIds]);

        return true;
    }

    //添加新增的游戏
    public static function createAddGames(&$addGamesIds)
    {
        if (empty($addGamesIds)) return true;

        //制作成二维数组
        $addGamesIds = array_map(function ($col) {
            return [
                'game_id' => $col,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }, $addGamesIds);
        $connection = \Yii::$app->db;
        //数据批量入库
        $connection->createCommand()->batchInsert(
            self::tableName(),
            ['game_id', 'created_at', 'updated_at'],//字段
            $addGamesIds
        )->execute();
        
        return true;
    }

    /**
     * 新旧游戏对比
     * @param array $oldGames
     * @param array $newGames
     * @param array $myGames
     * @return [addPrivList,delPrivList]
     */
    public static function diffGames(&$oldGameListArr, &$newGamesListArr) {
        $addGamesIds = array_diff($newGamesListArr, $oldGameListArr);
        $delGamesIds = array_diff($oldGameListArr, $newGamesListArr);

        return ['addGamesIds' => $addGamesIds, 'delGamesIds' => $delGamesIds];
    }
}