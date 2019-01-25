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

class SystemAdmin extends BaseModel
{
    static public function tableName() {
        return 's' . Helpers::getRequestParam('sid') . '_admin';
    }

    public function fields()
    {
        return ['system_ad_uid', 'ad_uid', 'created_at', 'updated_at'];
    }

    public function getAdminIdentity()
    {
        return $this->hasOne(Admin::class, ['ad_uid' => 'ad_uid']);
    }

    /**
     * 获取
     * @return \yii\db\ActiveQuery
     */
    public function getSystemGroup() {
        $s = Helpers::getRequestParam('sid');
        return $this->hasMany(SystemGroup::class, ['sg_id' => 'sg_id'])->viaTable('s'. $s .'_system_user_group', ['ad_uid' => 'system_ad_uid']);
    }
}