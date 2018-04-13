<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;


use Backend\helpers\Helpers;
use Backend\modules\common\models\BaseModel;

class SystemMenu extends BaseModel
{
    public $_privilege;

    static public function tableName() {
        return 'system_menu';
    }

    public function fields()
    {
        return [
            'sm_id',
            'sm_label',
            'sm_request_url',
            'sm_icon',
            'children' => function($model) {
                return $model->getChildren();
            }
        ];
    }

    public function getShowMenus()
    {
        $menus_ext = intval(Helpers::getHeader('ext'));
        $list = self::find()->where(['sm_status'=> 0, 'is_show_sidebar' => 1, 'ext' => [0, $menus_ext]])->orderBy('sort_by asc,sm_id asc')->indexBy('sm_id')->with('systemPriv')->asArray()->all();
        $list = $this->filterPrivilege($list);
        //var_dump($list);
        foreach ($list as $listKey => $listVal) {
            $list[$listKey]['title'] = $list[$listKey]['sm_label'];
            $list[$listKey]['name'] = $list[$listKey]['sm_view'];
            $list[$listKey]['icon'] = $list[$listKey]['sm_icon'];

            unset($list[$listKey]['systemPriv']);
            unset($list[$listKey]['sort_by']);
            unset($list[$listKey]['is_show_sidebar']);
            unset($list[$listKey]['sm_status']);
            unset($list[$listKey]['sm_parent_id']);
            unset($list[$listKey]['created_at']);
            unset($list[$listKey]['updated_at']);
            unset($list[$listKey]['sm_request_url']);
            unset($list[$listKey]['sm_label']);
            unset($list[$listKey]['sm_view']);
            unset($list[$listKey]['sm_icon']);
            unset($list[$listKey]['sm_id']);
            unset($list[$listKey]['ext']);

            $list[$listVal['sm_parent_id']]['list'][] = &$list[$listVal['sm_id']];
        }
        return isset($list[0]['list']) ? $list[0]['list'] : [];
    }

    //将非菜单权限过滤掉
    public function filterPrivilege($list) {
        if ($this->_privilege == false) {
            $privilege = \Yii::$app->user->identity->getPrivilege();
            $this->_privilege = [];
            foreach ($privilege as $value) {
                $this->_privilege[$value['sm_id']][] = $value['sp_module'] . $value['sp_controller'] . $value['sp_action'];
            }
        }
        foreach ($list as $listKey => $listKeyval) {
            $privilegeKey = str_replace('/', '', $listKeyval['sm_request_url']);
            if (!isset($this->_privilege[$listKeyval['sm_id']]) || !in_array($privilegeKey, $this->_privilege[$listKeyval['sm_id']])) {
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
            isset($result[$parentRootId]) ? : $result[$parentRootId] = [];
            $result[$parentRootId]['sm_id']         = $menus[$parentRootId]['sm_id'];
            $result[$parentRootId]['sm_label']      = $menus[$parentRootId]['sm_label'];
            $result[$parentRootId]['children'][]    = $menu['sm_id'];
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