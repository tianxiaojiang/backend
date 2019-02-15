<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2019/1/25
 * Time: 10:23
 */

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;
use Backend\modules\common\controllers\BusinessController;
use yii\helpers\ArrayHelper;

class OtherController extends BusinessController
{
    public $modelClass = SystemUser::class;

    /**
     * 调整架构时(将用户与系统关系对应表从 `system_admin` 转移到 `s{%d}_admin` )
     * 需要做用户的复制，以及用户角色id的复制
     * 做数据迁移同步
     * @return array
     * @throws CustomException
     * @throws \yii\db\Exception
     */
    public function actionSynAdmin()
    {
        $db = \Yii::$app->db->beginTransaction();
        $connection = \Yii::$app->db;

        $systemId = Helpers::getRequestParam('systems_id');
        try {
            //读取所有的系统
            $systems = System::find()->where(["systems_id" => $systemId])->all();
            foreach ($systems as $system) {
                //先用账号管家即 sid 为 4 做实验
                if (!in_array($system->systems_id, [2, 4])) continue;
                \Yii::error("mydebug:" . $system->systems_id);

                //清空新用户表
                $connection->createCommand("truncate table s" . $system->systems_id . "_admin")->execute();
                Helpers::$request_params['sid'] = $system->systems_id;
                //校验系统是否是新数据
                $newUsers = SystemAdmin::find()->one();
                if (!empty($newUsers)) continue;

                //读取系统下的老的用户
                $systemUsers = SystemUser::find()->where(['systems_id' => $system->systems_id])->all();
                $newAdUidMapRoleId = [];
                foreach ($systemUsers as $systemUser) {
                    //插入新的系统用户
                    $systemAdmin = new SystemAdmin();
                    $systemAdmin->ad_uid = $systemUser->ad_uid;
                    $systemAdmin->save();

                    //$oldAdUidMapNewAdUid[$systemUser->ad_uid] = $systemAdmin->system_ad_uid;
                    //找到老用户的角色
                    $systemUserGroups = SystemUserGroup::find()->where(['ad_uid' => $systemUser->ad_uid])->all();
                    $newAdUidMapRoleId[$systemAdmin->system_ad_uid] = ArrayHelper::getColumn($systemUserGroups, 'sug_id');
                }

                //更新老的用户id与角色关系 --> 新的对应关系
                foreach ($newAdUidMapRoleId as $key => $item) {
                    SystemUserGroup::updateAll(['ad_uid' => $key], ['sug_id' => $item]);
                }
            }
            Helpers::$request_params['sid'] = 1;
        } catch (\Exception $exception) {
            Helpers::$request_params['sid'] = 1;
            $db->rollBack();
            throw new CustomException($exception->getMessage());
        }

        $db->commit();

        return [];
    }
}