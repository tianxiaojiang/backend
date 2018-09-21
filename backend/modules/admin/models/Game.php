<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use \Backend\modules\common\models\BaseModel;

class Game extends BaseModel
{

    public function scenarios()
    {
        return [
            'default' => ['game_id', 'name', 'alias', 'status', 'order_by', 'updated_at', 'created_at'],
            'update' => ['game_id', 'name', 'alias', 'status', 'order_by', 'updated_at', 'created_at'],
            'create' => ['game_id', 'name', 'alias', 'status', 'order_by', 'updated_at', 'created_at'],
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => '游戏名不能为空', 'on' => ['create', 'update']],
            ['game_id', 'required', 'message' => '游戏id不能为空', 'on' => ['create', 'update']],
        ];
    }

    static public function tableName() {
        return 'game';
    }

    public function fields()
    {
        return ['game_id', 'name', 'alias', 'status', 'order_by', 'updated_at', 'created_at'];
    }

    public static function getAllGames()
    {
        return self::find()->asArray()->all();
    }
}