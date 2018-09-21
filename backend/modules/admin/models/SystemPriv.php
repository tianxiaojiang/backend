<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;


class SystemPriv extends \yii\db\ActiveRecord
{
    static public function tableName() {
        return 'system_priv';
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
                if( $privilege["sm_id"] == $menu["sm_id"]){
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
}