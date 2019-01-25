<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;
use Backend\modules\admin\services\admin\DomainAuthSoapClient;
use Backend\modules\admin\services\AdminService;
use Backend\modules\admin\services\SystemAdminService;
use Backend\modules\common\controllers\BusinessController;
use PhpOffice\PhpSpreadsheet\Exception;
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
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function prepareDataProvider()
    {
        $systems_id = Helpers::getRequestParam('sid');

        $this->query = Admin::find()->select([
            Admin::tableName().'.ad_uid',
            Admin::tableName().'.account',
            Admin::tableName().'.mobile_phone',
            Admin::tableName().'.username',
            Admin::tableName().'.status',
            Admin::tableName().'.auth_type',
            Admin::tableName().'.staff_number',
            Admin::tableName().'.created_at']);

        //增加系统过滤
        if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
            $this->query->innerJoinWith('systemAdmin');
        } else {
            $this->query->innerJoinWith(['systemRelations' => function($query) use($systems_id){
                $query->where([SystemUser::tableName() . '.systems_id' => $systems_id]);
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

        $data = DomainAuthSoapClient::getInstance()->getDomainInfo('tianweimin');
        \Yii::info('test auth:' . var_export($data, true));

        return parent::prepareDataProvider();
    }

    public function formatModel($models)
    {
        $result = [];
        foreach ($models as $key => $model) {
            $roles = $model->getRoles();
            $rolesName = implode('、', ArrayHelper::getColumn($roles, 'sg_name'));
            $item = [];
            $item['ad_uid'] = $model->ad_uid;
            $item['status'] = Admin::$_status[$model->status];
            $item['role']   = $rolesName;
            $item['account'] = $model->account;
            $item['mobile_phone'] = $model->mobile_phone;
            $item['username'] = $model->username;
            $item['created_at'] = $model->created_at;
            $item['auth_type'] = $model->auth_type;
            $item['auth_type_title'] = Admin::$_auth_types[$model->auth_type];
            $item['staff_number'] = $model->staff_number;
            $item['is_login'] = empty($model->systemRelations->token_id) ? 0 : 1;//如果对应的系统用户有token_id，则视为登录
            $result[] = $item;
        }

        return $result;
    }

    public function actionCreate()
    {
        $account = Helpers::getRequestParam('account');
        $auth_type = Helpers::getRequestParam('auth_type');
        $staff_number = Helpers::getRequestParam('staff_number');
        $sid = intval(Helpers::getRequestParam('sid'));

        if ($auth_type == Admin::AUTH_TYPE_PASSWORD) {
            $admin = Admin::findOne(['account' => $account, 'auth_type' => $auth_type]);
        } else {
            $admin = Admin::findOne(['staff_number' => $staff_number, 'auth_type' => $auth_type]);
        }

        if (empty($admin)) {
            $admin = new Admin();
            $admin->setScenario('create');
            $admin->load(Helpers::getRequestParams(), '');
            if ($admin->validate()) {
                $admin->save(false);
            }
        }

        //添加系统管理员
        $systemAdmin = SystemAdminService::addSystemAdmin($admin->ad_uid, $sid);

        //添加角色
        $sg_ids = empty($admin->sg_id) ? [] : explode(',', strval($admin->sg_id));
        $oldSg_id = ArrayHelper::getColumn($admin->getRoles(), 'sg_id');
        $game_id = intval(Helpers::getRequestParam('game_id'));
        $currentGameRoleIds = \Yii::$app->user->identity->getMyRoleIdsOnGame($game_id);
        $sg_ids = array_map(function($col) {
            return intval($col);
        }, $sg_ids);

        if (SystemAdminService::checkUseNewSystemAdminSchedule())
            $systemAdminId = $systemAdmin->system_ad_uid;
        else
            $systemAdminId = $admin->ad_uid;

        //更新角色
        SystemUserGroup::updateAdminUserGroup($systemAdminId, $sg_ids, $oldSg_id, $currentGameRoleIds);

        return $admin;
    }

    //管理员的删除，仅仅是删除系统与管理员之间的关系
    public function actionDelete()
    {
        $ad_uid = intval(Helpers::getRequestParam('id'));
        $systems_id = intval(Helpers::getRequestParam('sid'));
        Helpers::validateEmpty($ad_uid, '用户ID错误');
        Helpers::validateEmpty($systems_id, '系统ID错误');

        SystemAdminService::deleteSystemAdmin($ad_uid, $systems_id);

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

    /**
     * 这里只能修改角色
     * @return Admin|null
     * @throws CustomException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate()
    {
        $id = Helpers::getRequestParam('id');
        Helpers::validateEmpty($id, 'id');

        $admin = Admin::findOne(['ad_uid' => $id]);
        $admin->setScenario('update');
        if (empty($admin))
            throw new CustomException('用户不存在');

        $admin->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if (isset($admin->updated_at)) {
            $admin->updated_at = date('Y-m-d H:i:s');
        }

        if ($admin->validate()) {
            if ($admin->save()) {
            } elseif (!$admin->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        } else {
            $errors = $admin->getErrors();
            $error  = array_shift($errors);
            \Yii::error(var_export($error, true));
            throw new CustomException($error[0]);
        }

        $sg_ids = empty($admin->sg_id) ? [] : explode(',', strval($admin->sg_id));
        $oldSg_id = ArrayHelper::getColumn($admin->getRoles(), 'sg_id');
        $game_id = intval(Helpers::getRequestParam('game_id'));
        $currentGameRoleIds = \Yii::$app->user->identity->getMyRoleIdsOnGame($game_id);

        $sg_ids = array_map(function($col) {
            return intval($col);
        }, $sg_ids);

        if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
            $systemAdmin = SystemAdmin::findOne(['ad_uid' => $admin->ad_uid]);
            $systemAdminId = $systemAdmin->system_ad_uid;
        } else {
            $systemAdminId = $admin->ad_uid;
        }

        //更新角色
        SystemUserGroup::updateAdminUserGroup($systemAdminId, $sg_ids, $oldSg_id, $currentGameRoleIds);

        return $admin;
    }
}