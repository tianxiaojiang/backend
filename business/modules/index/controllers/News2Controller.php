<?php

namespace Business\modules\index\controllers;

use Backend\helpers\Helpers;
use Business\modules\index\models\News;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class News2Controller extends \Backend\modules\common\controllers\BusinessController
{
    public $modelClass = 'Business\modules\index\models\News';

    //组装自定义的query，不写默认取所有
    public function prepareDataProvider()
    {
        $start_time = Helpers::getRequestParam('start_time');
        $end_time = Helpers::getRequestParam('end_time');

        $this->query = News::find();

        if (!empty($start_time))
            $this->query->andFilterWhere(['>=', 'created_at', $start_time]);

        if (!empty($end_time))
            $this->query->andFilterWhere(['<=', 'created_at', $end_time]);

        $this->query->orderBy('created_at desc');

        return parent::prepareDataProvider();
    }

    //格式化输出字段，不写此方法，返回model的所有默认字段
    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'news_id' => $model->news_id,
                'title' => $model->title,
                'description' => $model->description,
                'content' => $model->content,
                'status_show' => News::$_status[$model->status],
                'status' => $model->status,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }
}