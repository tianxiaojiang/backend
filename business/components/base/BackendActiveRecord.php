<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2017/11/13
 * Time: 18:20
 */

namespace Business\components\base;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class BackendActiveRecord extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function(){
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }
}