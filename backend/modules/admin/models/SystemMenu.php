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

class SystemMenu extends BaseModel
{
    const SM_TYPE_BUSINESS  = 0;
    const SM_TYPE_SETTING   = 1;

    public $_privilege;

    public $menuType = 0;

    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_system_menu';
    }

    public function scenarios()
    {
        return [
            'default' => ['sm_id', 'sm_label', 'sm_parent_id', 'sm_view', 'created_at', 'updated_at'],
            'update' => ['sm_id', 'sm_label', 'sm_parent_id', 'sm_view', 'created_at', 'updated_at'],
            'create' => ['sm_id', 'sm_label', 'sm_parent_id', 'sm_view', 'created_at', 'updated_at'],
        ];
    }

    public function rules()
    {
        return [
            ['sm_label', 'required', 'message' => '菜单名不能为空', 'on' => ['create', 'update']],
        ];
    }

    public function fields()
    {
        return [
            'sm_id',
            'sm_label',
            'sm_view',
            'sm_icon',
            'sm_parent_id',
            'children' => function($model) {
                return $model->getChildren();
            }
        ];
    }

    public function getShowMenus($menuType = self::SM_TYPE_BUSINESS)
    {
        $this->menuType = $menuType;
        $list = self::find()
            ->where([
                'sm_set_or_business' => $this->menuType,
                'sm_status'=> 0,
                'is_show_sidebar' => 1
            ])
            ->orderBy('sort_by asc,sm_id asc')
            ->indexBy('sm_id')
            ->asArray()->all();
        $list = $this->filterPrivilege($list);
        //var_dump($list);
        return $this->formatList2Tree($list);
    }

    public function formatList2Tree(&$list)
    {
        foreach ($list as $sm_id => $listVal) {
            $list[$sm_id]['title'] = $list[$sm_id]['sm_label'];
            $list[$sm_id]['name'] = $list[$sm_id]['sm_view'];
            $list[$sm_id]['icon'] = $list[$sm_id]['sm_icon'];

            unset($list[$sm_id]['sort_by']);
            unset($list[$sm_id]['is_show_sidebar']);
            unset($list[$sm_id]['sm_status']);
            unset($list[$sm_id]['created_at']);
            unset($list[$sm_id]['updated_at']);
            unset($list[$sm_id]['sm_icon']);

            $list[$listVal['sm_parent_id']]['list'][] = &$list[$listVal['sm_id']];
        }
        return isset($list[0]['list']) ? $list[0]['list'] : [];
    }

    //将非菜单权限过滤掉
    public function filterPrivilege($list) {
        if ($this->_privilege == false) {
            $gameId = Helpers::getRequestParam('game_id');
            $privilege = \Yii::$app->user->identity->getPrivileges($gameId, $this->menuType);
            $this->_privilege = [];
            foreach ($privilege as $value) {
                $this->_privilege[$value['sm_id']][] = $value['sp_module'] . $value['sp_controller'] . $value['sp_action'];
            }
        }
//        \Yii::info('privilege is:'. var_export($this->_privilege, true));

        foreach ($list as $listKey => $listKeyval) {
            if (!isset($this->_privilege[$listKeyval['sm_id']]) || !in_array('mainmainmain', $this->_privilege[$listKeyval['sm_id']])) {
                unset($list[$listKey]);
            }
        }
        return $list;
    }

    public function getChildren() {
        return self::findAll(['sm_parent_id' => $this->sm_id]);
    }

    public static function getAllMenusByGroup()
    {
        $menus = self::find()->indexBy('sm_id')->asArray()->all();

        $result = [];
        foreach ($menus as $menu) {
            $parentRootId = self::getRootParentId($menus, $menu);
            $menuKey = 'sg_' . $parentRootId;
            isset($result[$menuKey]) ? : $result[$menuKey] = [];
            $result[$menuKey]['sm_id']         = $menus[$parentRootId]['sm_id'];
            $result[$menuKey]['sm_label']      = $menus[$parentRootId]['sm_label'];
            $result[$menuKey]['children'][]    = $menu['sm_id'];
        }

        return $result;
    }

    private static function getRootParentId($array, $item)
    {
        if ($item['sm_parent_id'] == 0) {
            return $item['sm_id'];
        }
        if ($array[$item['sm_parent_id']]['sm_parent_id'] == 0) {
            return $array[$item['sm_parent_id']]['sm_id'];
        } else {
            return self::getRootParentId($array, $array[$item['sm_parent_id']]);
        }
    }
    
    public function getSystemPriv() {
        return $this->hasMany(SystemPriv::className(), ['sm_id' => 'sm_id']);
    }
}