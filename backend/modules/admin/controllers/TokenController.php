<?php

namespace Backend\modules\admin\controllers;

use Backend\modules\common\controllers\BaseController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class TokenController extends BaseController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'juliardi\captcha\CaptchaAction'
            ],
        ];
    }
}