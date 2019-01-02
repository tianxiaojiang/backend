<?php

$uploads = [
    "imageMaxSize" => 4096000, /* 上传大小限制，单位B */
    "imageAllowFiles" => array("png", "jpg", "jpeg", "gif"), /* 上传图片格式显示 */
    "imageCompressEnable" => true, /* 是否压缩图片,默认是true */
    "imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
    "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
    "imageUrlPrefix" => "http://integration.background.com", /* 图片访问路径前缀 */
    "imagePathFormat" => "/uploads/{yyyy}{mm}{dd}/{time}{rand:6}",
    //针对场景细化限制
    "scenarios" => [
        "systems_icon" => [
            "quality" => [   //上传大小限制，key表示比较符，见文件最后的注释，没有取上面的通用设置
                '<' => 20
            ],
            "size" => [ //尺寸限制，没有取上面的通用设置
                "w" => [ '=' => 91 ],
                "h" => [ '=' => 88 ],
            ],
            "type" => ["png", "jpg", "jpeg", "gif"]
        ],
    ],
];

return \yii\helpers\ArrayHelper::merge(
    $uploads,
    file_exists(__DIR__ . '/upload-local.php') ? require_once(__DIR__ . '/upload-local.php') : []
);


/**
 * 等于	1
 * 大于	2
 * 小于	3
 */
