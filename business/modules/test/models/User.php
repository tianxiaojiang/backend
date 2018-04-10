<?php

namespace Business\modules\test\models;

use yii\db\ActiveRecord;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class User extends ActiveRecord
{

    public static function getDb()
    {
        return \Yii::$app->gamm3_db;
    }

    public static function tableName()
    {
        return 'user';
    }
}