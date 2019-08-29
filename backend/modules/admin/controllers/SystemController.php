<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\System;
use Backend\modules\admin\services\SystemService;
use Backend\modules\common\controllers\BusinessController;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class SystemController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\System';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function prepareDataProvider()
    {
        $this->query = System::find();
        $this->query->orderBy('systems_id asc');

        return parent::prepareDataProvider();
    }

    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'systems_id' => $model->systems_id,
                'name' => $model->name,
                'status' => $model->status,
                'allow_api_call' => $model->allow_api_call,
                'statusName' => System::$_status[$model->status],
                'img_id' => $model->img_id,
                'active_img_id' => $model->active_img_id,
                'show_url' => (empty($model->img) ? '' : $model->img->content),
                'active_url' => (empty($model->activeImg) ? '' : $model->activeImg->content),
                'url' => $model->url,
                'auth_url' => $model->auth_url,
                'description' => $model->description,
                'dev_account' => $model->admin->account,
                'dev_account_type_show' => Admin::$_auth_types[$model->admin->auth_type],
                'dev_account_type' => $model->admin->auth_type,
                'staff_number' => $model->admin->staff_number,
                'game_type' => $model->game_type,
                'game_type_show' => Game::$_types[$model->game_type],
                'updated_at' => $model->updated_at,
                'created_at' => $model->created_at,
            ];
        }

        return $result;
    }


    public function actionDelete()
    {
        $system_id = Helpers::getRequestParam('system_id');
        SystemService::forbiddenSystem($system_id);

        return [];
    }

    /**
     * 把某个系统的数据从测试环境里同步到线上
     * 思路：
     *
     * 一  先直接把本地系统所有数据表格导入到线上
     *
     * 二  先在本地用navicat把admin_user里面跟对应系统相关的导出为json文件
     *     比如导出的本地系统是5，查询语句为:
     *     select a.* from `admin_user` a INNER JOIN `s5_admin` b on a.ad_uid=b.ad_uid;
     *
     * 三  修改json文件里的字段算法ID(password_algorithm_system)为对应的系统id
     *
     * 三  上传一个json文件，里面的数据是本地ad_uid
     *
     * 四  遍历本地用户，线上数据库没有则插入，并记录下来线上id和本地id的对应关系
     *
     * 五  遍历线上系统用户关系，然后每一条对应的旧id替换为新id即可
     *
     */
    public function actionImportDevData()
    {
        $productSystemId = Helpers::getRequestParam('systems_id');//线上系统id
        $system = System::findOne(['systems_id' => $productSystemId]);

        if (empty($system))
            throw new CustomException('请指定要同步的系统！');

        $userFile = UploadedFile::getInstanceByName('admin_user');
        $userFilePath = $userFile->tempName;
        $cont = json_decode(file_get_contents($userFilePath), true);

        $db = \Yii::$app->getDb()->beginTransaction();
        try {
            $result = SystemService::importDevelopAdmin($cont, $productSystemId);
            $uidMaps = $result['uidMaps'];
            $exitedAccounts = $result['exitedAccounts'];
            $res = SystemService::importSystemAdmin($uidMaps, $productSystemId);
        } catch (\Exception $exception) {
            $db->rollBack();
            \Yii::error('导入数据失败:' . var_export($exception->getTraceAsString(), true));
            throw new CustomException($exception->getMessage());
        }
        $db->commit();

        if ($res)
            return ['exitedAccounts' => $exitedAccounts];
        else
            throw new CustomException('未知错误');
    }
}