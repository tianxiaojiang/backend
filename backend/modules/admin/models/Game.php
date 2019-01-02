<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\modules\admin\services\SystemService;
use \Backend\modules\common\models\BaseModel;

class Game extends BaseModel
{
    const GAME_STAT_NORMAL = 0;//启用中
    const GAME_STAT_FORBIDDEN = 1;//已禁用

    public static $_status = [
        self::GAME_STAT_NORMAL => '正常',
        self::GAME_STAT_FORBIDDEN => '禁用中',
    ];

    //游戏类型
    const GAME_TYPE_PC = 0;
    const GAME_TYPE_PHONE = 1;
    const GAME_TYPE_NONE = 2;

    public static $_types = [
        self::GAME_TYPE_PC => '端游',
        self::GAME_TYPE_PHONE => '手游',
        self::GAME_TYPE_NONE => '不区分',
    ];

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

    public static function getAllGames($where = [], $fields = '*')
    {
        $currentSystem = SystemService::getCurrentSystem();
        $where['type'] = $currentSystem->game_type;
        return self::find()->select($fields)->where($where)->indexBy('game_id')->asArray()->all();
    }
}