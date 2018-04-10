<?php

# debug
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', true);
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', true);

defined('BASE') or define('BASE', dirname(__DIR__));
defined('ROOT') or define('ROOT', BASE . DIRECTORY_SEPARATOR . 'backend');
defined('BUSINESS') or define('BUSINESS', BASE . DIRECTORY_SEPARATOR . 'business');

require_once BASE . '/vendor/autoload.php';
require_once BASE . '/vendor/yiisoft/yii2/Yii.php';

$config = require_once (BUSINESS . '/config/config.php');

$app = new yii\web\Application($config);
$app->run();