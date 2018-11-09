<?php

namespace Business\modules\index\models;

use Backend\modules\common\models\BaseModel;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class News extends BaseModel
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

    public function scenarios()
    {
        return [
            'default' => ['news_id', 'title', 'description', 'content', 'status', 'updated_at', 'created_at'],
            'update' => ['news_id', 'title', 'description', 'content', 'status', 'updated_at', 'created_at'],
            'create' => ['news_id', 'title', 'description', 'content', 'status', 'updated_at', 'created_at'],
        ];
    }

    public function rules()
    {
        return [
            ['title', 'required', 'message' => '标题不能为空', 'on' => ['create', 'update']],
            ['description', 'required', 'message' => '简介不能为空', 'on' => ['create', 'update']],
            ['content', 'required', 'message' => '内容不能为空', 'on' => ['create', 'update']],
        ];
    }

    public function fields()
    {
        return ['news_id', 'title', 'description', 'content', 'status', 'updated_at', 'created_at'];
    }
}