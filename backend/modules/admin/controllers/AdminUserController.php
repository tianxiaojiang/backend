<?php

namespace Backend\modules\admin\controllers;

use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class AdminUserController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    public function prepareDataProvider()
    {
        $this->query = Admin::find()->select(['ad_uid', 'sg_id', 'account', 'mobile_phone', 'username', 'status', 'created_at']);
        $status = Helpers::getRequestParam('status');
        $account = Helpers::getRequestParam('account');

        if ($status !== null) {
            $this->query->andWhere(['status' => intval($status)]);
        }

        if ($account !== null) {
            $this->query->andWhere(['account' => $account]);
        }

        $this->query->orderBy('ad_uid asc');

        return parent::prepareDataProvider();
    }

    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $key => $model) {
            $item = [];
            $item['ad_uid'] = $model->ad_uid;
            $item['status'] = Admin::$_status[$model->status];
            $item['role']   = $model->roleName;
            $item['account'] = $model->account;
            $item['mobile_phone'] = $model->mobile_phone;
            $item['username'] = $model->username;
            $item['created_at'] = $model->created_at;
            $result[] = $item;
        }

        return $result;
    }

}