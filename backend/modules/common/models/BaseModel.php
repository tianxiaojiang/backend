<?php

namespace Backend\modules\common\models;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/8
 * Time: 18:11
 */

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}