<?php

namespace Backend\modules\admin\controllers;

use Api\modules\authentication\models\AccessToken;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\LoginWhiteList;
use Backend\modules\admin\models\System;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class WhiteListController extends BusinessController
{
    public $modelClass = LoginWhiteList::class;

//    public function prepareDataProvider()
//    {
//        $this->query = System::find();
//        $this->query->orderBy('systems_id asc');
//
//        return parent::prepareDataProvider();
//    }

    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'login_white_list_id' => $model->login_white_list_id,
                'account' => $model->account,
                'show_type' => AccessToken::$_auth_types[$model->type],
                'type' => $model->type,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }

}