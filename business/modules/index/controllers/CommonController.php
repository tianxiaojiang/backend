<?php

namespace Business\modules\index\controllers;

use Business\modules\index\models\News;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 14:21
 */
class CommonController extends \Backend\modules\common\controllers\BaseController
{
    public $modelClass = 'Business\modules\index\models\News';

    public function actionNewsStatus()
    {
        return News::$_status;
    }
}