<?php

$vendorDir = dirname(__DIR__);

return array (
  'juliardi/yii2-captcha' => 
  array (
    'name' => 'juliardi/yii2-captcha',
    'version' => '1.0.1.0',
    'alias' => 
    array (
      '@juliardi/captcha' => $vendorDir . '/juliardi/yii2-captcha/src',
    ),
  ),
  'yiisoft/yii2-httpclient' => 
  array (
    'name' => 'yiisoft/yii2-httpclient',
    'version' => '2.0.7.0',
    'alias' => 
    array (
      '@yii/httpclient' => $vendorDir . '/yiisoft/yii2-httpclient/src',
    ),
  ),
  'yiisoft/yii2-redis' => 
  array (
    'name' => 'yiisoft/yii2-redis',
    'version' => '2.0.9.0',
    'alias' => 
    array (
      '@yii/redis' => $vendorDir . '/yiisoft/yii2-redis/src',
    ),
  ),
);
