<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\modules\common\models\BaseModel;

class SystemUserGroup extends BaseModel
{
    static public function tableName() {
        return 'system_user_group';
    }


}