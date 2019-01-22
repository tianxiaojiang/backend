<?php

$uploads = [
    "imageMaxSize" => 4096000, /* 上传大小限制，单位B */
    "imageAllowFiles" => ["png", "jpg", "jpeg", "gif"], /* 上传图片格式显示 */
    "imageUrlPrefix" => 'https://unify-admin.sdk.mobileztgame.com', /* 图片访问路径前缀 */
    //针对场景细化限制
    "scenarios" => [
        "third_app_logo" => [
            "quality" => [   //上传大小限制，key表示比较符
                '<' => 5
            ],
            "size" => [ //尺寸限制，没有取上面的通用设置
                "prop" => "1x1"
            ],
            "type" => ["png", "jpg", "jpeg", "gif"],
            "dest_type" => "for_cdn"
        ],
        "system_msg_thumb" => [
            "quality" => [   //上传大小限制，key表示比较符
                '<' => 20
            ],
            "size" => [ //尺寸限制，没有取上面的通用设置
                "w" => [ '=' => 186 ],
                "h" => [ '=' => 186 ],
            ],
            "type" => ["png", "jpg", "jpeg", "gif"],
            "dest_type" => "for_cdn"
        ],
    ],
];
return \yii\helpers\ArrayHelper::merge(
    $uploads,
    file_exists(__DIR__ . '/upload-local.php') ? require_once(__DIR__ . '/upload-local.php') : []
);

