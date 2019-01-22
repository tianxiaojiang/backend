<?php
/**
 * User: tianweimin
 * Date: 2017/8/22
 * Time: 9:08
 */

namespace Business\modules\index\models;

use Business\components\base\BackendActiveRecord;
use yii\behaviors\TimestampBehavior;

class Img extends BackendActiveRecord
{
    public static function tableName()
    {
        return 'img';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BackendActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BackendActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function() {
                    return time();
                }
            ],
        ];
    }

    public static function InsertFeedBackImg($dateImg = '', $size = [0, 0], $content = '')
    {
        $imgModel = new self();
        $imgModel->setAttributes([
            'url_path'      => $dateImg,
            //'type'          => self::IMG_TYPE_FEEDBACK,
            'width'         => $size[0],
            'height'        => $size[1],
            'content'       => $content,
            'created_at'    => time(),
            'updated_at'    => time()
        ], false);

        if ($imgModel->validate())
        {
            if ($imgModel->save()) {
                return $imgModel->img_id;
            }
        }

        return false;
    }
}