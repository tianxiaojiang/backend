<?php
/**
 * User: tianweimin
 * Date: 2017/8/22
 * Time: 9:08
 */

namespace Backend\modules\admin\models;

use Backend\modules\common\models\BaseModel;

class Img extends BaseModel
{
    public static function tableName()
    {
        return 'img';
    }

    public static function InsertImg($dateImg, $size = [0, 0])
    {
        $imgModel = new self();
        $imgModel->setAttributes([
            'url_path'      => $dateImg,
            'width'         => $size[0],
            'height'        => $size[1],
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

    //²åÈëbase64Í¼Æ¬
    public static function InsertImgBase64($cont)
    {
        $imgModel = new self();
        $imgModel->setAttributes([
            'content' => $cont,
            'created_at' => time(),
            'updated_at' => time()
        ], false);

        if ($imgModel->validate()) {
            if ($imgModel->save()) {
                return $imgModel->img_id;
            }
        }

        return false;
    }
}