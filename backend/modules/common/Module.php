<?php

namespace Backend\modules\common;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        // 从config.php加载配置来初始化模块
        \Yii::configure($this, require(__DIR__ . '/config/config.php'));
    }
}
