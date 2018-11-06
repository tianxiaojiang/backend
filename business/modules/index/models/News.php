<?php

namespace Business\modules\index\models;

use yii\db\ActiveRecord;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class News extends ActiveRecord
{
    const NEWS_STATUS_NORMAL = 0;
    const NEWS_STATUS_PUBLISHING = 1;
    const NEWS_STATUS_FORBIDDEN = 2;

    public static $_status = [
        self::NEWS_STATUS_NORMAL => '已发布',
        self::NEWS_STATUS_PUBLISHING => '待发布',
        self::NEWS_STATUS_FORBIDDEN => '已禁止',
    ];

    public static function tableName()
    {
        return 'news';
    }
}