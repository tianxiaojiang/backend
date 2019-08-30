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

class SystemGroup extends BaseModel
{

    const SYSTEM_PRIVILEGE_LEVEL_FRONT = 1;
    const SYSTEM_PRIVILEGE_LEVEL_ADMIN = 2;

    const SYSTEM_ROLE_KIND_COMMON = 0;
    const SYSTEM_ROLE_KIND_PROPER = 1;

    public function scenarios()
    {
        return [
            'default' => ['sg_id', 'kind', 'sg_name', 'sg_desc', 'privilege_level'],
            'update' => ['sg_id', 'kind', 'sg_name', 'sg_desc', 'privilege_level'],
            'create' => ['sg_id', 'kind', 'sg_name', 'sg_desc', 'privilege_level'],
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
        return ['sg_id', 'sg_desc', 'sg_name', 'privilege_level'];
    }

    public function insert($runValidation = true, $attributes = null) {
        $gameId = Helpers::getRequestParam("game_id");
        parent::insert($runValidation, $attributes);

        $groupId = $this->sg_id;
        $groupGame = new SystemGroupGame();
        $groupGame->sg_id = $groupId;
        $groupGame->game_id = $gameId;
        $groupGame->save(false);

        return true;
    }

    public function update($runValidation = true, $attributes = null) {
        $gameId = Helpers::getRequestParam("game_id");
        /**
         * 单独游戏环境下，不能操作符合游戏的角色
         */
        if ($gameId > 0 && !$this->isSingeGameRole()) {
            throw new CustomException('您无法设置多游戏角色，如果您管理多个游戏，请切换到不区分游戏再尝试');
        } else {
            parent::update($runValidation, $attributes);
            return true;
        }
    }

    //给角色设置管理游戏
    public static function setRoleOnGame(SystemGroup $roleObj, $gameId)
    {
        if (empty($roleObj))
            throw new CustomException('角色不存在');
        if ($roleObj->on_game == $gameId)
            return true;

        $roleObj->on_game = $gameId;
        $roleObj->save();

        return true;
    }

    //给角色设置权限级别
    public static function setRolePrivilegeLevel(SystemGroup $roleObj, $privilegeLevel)
    {
        if (empty($roleObj))
            throw new CustomException('角色不存在');

        if ($roleObj->privilege_level == $privilegeLevel)
            return true;
        $roleObj->privilege_level = $privilegeLevel;
        $roleObj->save();

        return true;
    }

    /**
     * 根据登录游戏，获取所有角色
     * @param $gameId
     * @return array
     */
    public static function getRoleIdByGame($gameId)
    {
        if ($gameId !== -1) {
            $roles = static::findAll(['on_game' => $gameId]);
        } else {
            $roles = static::find()->all();
        }

        return ArrayHelper::getColumn($roles, 'sg_id');
    }

    public function getPrivilege()
    {
        return $this->hasMany(SystemPriv::class, ['sp_id' => 'sp_id'])->viaTable(SystemGroupPriv::tableName(), ['sg_id' => 'sg_id']);
    }

    /**
     * 角色关联的所有游戏
     * @return \yii\db\ActiveQuery
     */
    public function getSystemGame()
    {
        return $this->hasMany(Game::class, ['game_id' => 'game_id'])->viaTable(SystemGroupGame::tableName(), ['sg_id' => 'sg_id']);
    }

    /**
     * 角色关联的所有游戏id
     * @return \yii\db\ActiveQuery
     */
    public function getGameIds()
    {
        return $this->hasMany(SystemGroupGame::class, ['sg_id' => 'sg_id']);
    }

    /**
     * 针对角色执行更新游戏
     * @param SystemGroup $systemGroup
     * @param array $newGameIds
     * @param array $myGameIds
     * @return bool
     */
    public static function updateRoleGames(SystemGroup $systemGroup, array &$newGameIds, array &$myGameIds)
    {
        $oldGameIds = ArrayHelper::getColumn($systemGroup->gameIds, 'game_id');
        $diffGameIds = SystemGroupGame::diffGames($oldGameIds, $newGameIds, $myGameIds);

        SystemGroupGame::createAddGames($systemGroup->sg_id, $diffGameIds['addGamesIds']);
        SystemGroupGame::deleteDeductGames($systemGroup->sg_id, $diffGameIds['delGamesIds']);

        return true;
    }

    /**
     * 查询一个角色是否为单游戏角色
     * 因为在区分游戏环境下，只可以操作单游戏角色
     * 如果一个角色管理多个游戏，这个角色只有在不区分游戏时，可以操作
     * @return bool
     */
    public function isSingeGameRole()
    {
        $ret = SystemGroupGame::find()->select(['sggid'])->where(['sg_id' => $this->sg_id])->asArray()->all();
        return count($ret) === 1;
    }
}