<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use \Backend\modules\common\models\BaseModel;

class SystemGroup extends BaseModel
{

    public function scenarios()
    {
        return [
            'default' => ['sg_id', 'sg_name', 'sg_desc'],
            'update' => ['sg_id', 'sg_name', 'sg_desc'],
        ];
    }

    public function rules()
    {
        return [
            ['sg_name', 'required', 'message' => '角色名不能为空', 'on' => 'default'],
        ];
    }

    static public function tableName() {
        return 'system_group';
    }

    public function fields()
    {
        return ['sg_id', 'sg_desc', 'sg_name'];
    }

    public function getPrivilege()
    {
        return $this->hasMany(SystemPriv::className(), ['sp_id' => 'sp_id'])->viaTable(SystemGroupPriv::tableName(), ['sg_id' => 'sg_id']);
    }
}