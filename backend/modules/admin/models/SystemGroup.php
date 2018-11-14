<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\helpers\Helpers;
use \Backend\modules\common\models\BaseModel;

class SystemGroup extends BaseModel
{

    public function scenarios()
    {
        return [
            'default' => ['sg_id', 'sg_name', 'sg_desc', 'sg_limit_game'],
            'update' => ['sg_id', 'sg_name', 'sg_desc', 'sg_limit_game'],
            'create' => ['sg_id', 'sg_name', 'sg_desc', 'sg_limit_game'],
        ];
    }

    public function rules()
    {
        return [
            ['sg_name', 'required', 'message' => '角色名不能为空', 'on' => 'default'],
        ];
    }

    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_group';
    }

    public function fields()
    {
        return ['sg_id', 'sg_desc', 'sg_name', 'sg_limit_game'];
    }

    public function getPrivilege()
    {
        return $this->hasMany(SystemPriv::class, ['sp_id' => 'sp_id'])->viaTable(SystemGroupPriv::tableName(), ['sg_id' => 'sg_id']);
    }

    //添加完角色，再加一条默认游戏管理，不细分游戏
    public function insert($runValidation = true, $attributes = null) {
        $group_id = parent::insert($runValidation, $attributes);
        $systemGroupGame = new SystemGroupGame();
        $systemGroupGame->group_id = $group_id;
        $systemGroupGame->game_id = '*';
        $systemGroupGame->save();
        return true;
    }

    public function getSystemGame()
    {
        return $this->hasMany(SystemGroupGame::class, ['sg_id' => 'sg_id']);
    }
}