<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;
use Backend\modules\admin\services\AdminService;
use Backend\modules\common\controllers\BusinessController;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class AdminUserController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    public function actions()
    {
        $actions =  parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function prepareDataProvider()
    {
        $systems_id = Helpers::getRequestParam('sid');

        //增加系统过滤
        $this->query = Admin::find()->select([
            Admin::tableName().'.ad_uid',
            Admin::tableName().'.account',
            Admin::tableName().'.mobile_phone',
            Admin::tableName().'.username',
            Admin::tableName().'.status',
            Admin::tableName().'.created_at']);
        $this->query->innerJoinWith(['systemRelations' => function($query) use($systems_id){
            $query->where([SystemUser::tableName() . '.systems_id' => $systems_id]);
        }]);

        $gameId = intval(Helpers::getRequestParam('game_id'));
        if ($gameId > 0) {//如果有游戏id，则只获取跟游戏id关联的角色
            $sgIds = ArrayHelper::getColumn(SystemGroupGame::findAll(['game_id' => $gameId]), 'group_id');
            $this->query->innerJoinWith(['groupRelations' => function($query) use($sgIds) {
                $query->where(['`' . SystemUserGroup::tableName() . '`.`sg_id`' => $sgIds]);
            }]);
        }

        $status = Helpers::getRequestParam('status');
        $account = Helpers::getRequestParam('account');

        if ($status !== null) {
            $this->query->andWhere(['`'.Admin::tableName().'`.`status`' => intval($status)]);
        }

        if ($account !== null) {
            $this->query->andWhere(['account' => $account]);
        }

        $this->query->orderBy('`'.Admin::tableName().'`.`ad_uid` asc');

//        var_dump($this->query->createCommand()->getRawSql());exit;

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
            $item['is_login'] = empty($model->systemRelations->token_id) ? 0 : 1;//如果对应的系统用户有token_id，则视为登录
            $result[] = $item;
        }

        return $result;
    }

    //管理员的删除，仅仅是删除系统与管理员之间的关系
    public function actionDelete()
    {
        $ad_uid = intval(Helpers::getRequestParam('id'));
        $systems_id = intval(Helpers::getRequestParam('sid'));
        Helpers::validateEmpty($ad_uid, '用户ID错误');
        Helpers::validateEmpty($systems_id, '系统ID错误');
        $systemAdmin = SystemUser::findOne(['ad_uid' => $ad_uid, 'systems_id' => $systems_id]);
        if (empty($systemAdmin))
            throw new CustomException('操作的管理员不存在');

        $systemAdmin->delete();

        return [];
    }

    //踢某个管理员下线
    public function actionPunish()
    {
        $ad_uid = Helpers::getRequestParam('ad_uid');
        Helpers::validateEmpty($ad_uid, '账号ID');
        $sid = Helpers::getRequestParam('sid');

        AdminService::punishAdmin($ad_uid, $sid);

        return [];
    }
}