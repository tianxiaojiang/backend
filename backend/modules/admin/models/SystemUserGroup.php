<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;


class SystemUserGroup extends \yii\db\ActiveRecord
{
    static public function tableName() {
        return 'system_user_group';
    }


}