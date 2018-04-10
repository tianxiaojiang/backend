<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use Backend\modules\admin\models\AccessToken;
use Backend\modules\common\controllers\BaseController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class TokenController extends BaseController
{
    public $modelClass = 'Backend\modules\admin\models\AccessToken';

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'juliardi\captcha\CaptchaAction'
            ],
        ];
    }

    /**
     * 获取token
     */
    public function actionGet()
    {
        $model = AccessToken::findOne(['account' => Helpers::getRequestParam('account')]);

        if (empty($model)) {
            throw new CustomException(Lang::ERR_LOGIN_FAIL);
        }

        return $model->generateToken();
    }
}