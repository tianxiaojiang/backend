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
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\models\BaseModel;

class SystemPriv extends BaseModel
{
    const PRIVILEGE_TYPE_BUSINESS  = 0;
    const PRIVILEGE_TYPE_SETTING = 1;

    public static $allPrivileges = null;

    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_priv';
    }

    public function scenarios()
    {
        return [
            'default' => ['sp_id', 'sp_label', 'sp_parent_id', 'sp_module', 'sp_controller', 'sp_action', 'sm_id', 'created_at', 'updated_at'],
            'update' => ['sp_id', 'sp_label', 'sp_parent_id', 'sp_module', 'sp_controller', 'sp_action', 'sm_id', 'created_at', 'updated_at'],
            'create' => ['sp_id', 'sp_label', 'sp_parent_id', 'sp_module', 'sp_controller', 'sp_action', 'sm_id', 'created_at', 'updated_at'],
        ];
    }

    public function rules()
    {
        return [
            ['sp_label', 'required', 'message' => '权限名不能为空', 'on' => ['create', 'update']],
            ['sp_parent_id', 'required', 'message' => '父权限不能为空', 'on' => ['create', 'update']],
            ['sp_module', 'required', 'message' => '操作module不能为空', 'on' => ['create', 'update']],
            ['sp_controller', 'required', 'message' => '操作controller不能为空', 'on' => ['create', 'update']],
            ['sp_action', 'required', 'message' => '操作action不能为空', 'on' => ['create', 'update']],
            ['sm_id', 'required', 'message' => '关联菜单不能为空', 'on' => ['create', 'update']],
        ];
    }

    public function getShowMenu()
    {
        return $this->hasOne(SystemMenu::className(), ['sm_id' => 'sm_id']);
    }

    public static function getPrivilegesByGroups($menus, $groupPrivilegesIds)
    {
        foreach ($menus as $key => $menu) {
            $privileges = self::find()->select('sp_id, sm_id, sp_label')->where(['in', 'sm_id', $menu['children']])->orderBy('sp_id asc')->indexBy('sp_id')->asArray()->all();
            foreach ($privileges as $privilegeKey => $privilege) {
                if( $privilege["sm_id"] == $menu["sm_id"]) {
                    $menus[$key]["sm_label"] = $privilege["sp_label"];
                }

                if (in_array($privilege['sp_id'], $groupPrivilegesIds)) {
                    $privileges[$privilegeKey]['is_checked'] = 1;
                } else {
                    $privileges[$privilegeKey]['is_checked'] = 0;
                }
            }
            $menus[$key]['privileges'] = $privileges;
            unset($menus[$key]['children']);
        }

        return $menus;
    }

    public static function getAll($where = [])
    {
        if (empty(self::$allPrivileges)) {
            self::$allPrivileges = self::find()->where($where)->indexBy('sp_id')->orderBy('sp_id asc')->asArray()->all();
        }
        return self::$allPrivileges;
    }

    public function insert($runValidation = true, $attributes = null)
    {
        //权限类型与菜单类型一致
        $this->sp_set_or_business = $this->menu->sm_set_or_business;

        //如果action不为main或空
        if (empty($this->sp_module) && empty($this->sp_controller) && empty($this->sp_action)) {
            $this->sp_module = $this->sp_controller = $this->sp_action = 'main';
        } elseif (!empty($this->sp_module) && !empty($this->sp_controller) && !empty($this->sp_action)) {
            if ($this->sp_module != 'main' || $this->sp_controller != 'main' || $this->sp_action != 'main') {
                //验证是否已存在
                $tmpPriv = static::findOne(['sp_module' => $this->sp_module, 'sp_controller' => $this->sp_controller, 'sp_action' => $this->sp_action]);
                if (!empty($tmpPriv))
                    throw new CustomException("路由参数已存在，请修改");
            }
        } else {
            throw new CustomException("路由参数不全");
        }

        parent::insert($runValidation = true, $attributes = null);

        return true;
    }

    public function update($runValidation = true, $attributes = null)
    {
        //权限类型与菜单类型一致
        $this->sp_set_or_business = $this->menu->sm_set_or_business;

        //如果action不为main或空
        if (empty($this->sp_module) && empty($this->sp_controller) && empty($this->sp_action)) {
            $this->sp_module = $this->sp_controller = $this->sp_action = 'main';
        } elseif (!empty($this->sp_module) && !empty($this->sp_controller) && !empty($this->sp_action)) {
            if ($this->sp_module != 'main' || $this->sp_controller != 'main' || $this->sp_action != 'main') {
                //验证是否已存在
                $tmpPriv = static::find()->where(['sp_module' => $this->sp_module, 'sp_controller' => $this->sp_controller, 'sp_action' => $this->sp_action])->andWhere(['<>', 'sp_id', $this->sp_id])->One();
                if (!empty($tmpPriv))
                    throw new CustomException("路由参数已存在，请修改");
            }
        } else {
            throw new CustomException("路由参数不全");
        }

        parent::update($runValidation = true, $attributes = null);

        return true;
    }

    public function getMenu()
    {
        return $this->hasOne(SystemMenu::class, ['sm_id' => 'sm_id']);
    }
}