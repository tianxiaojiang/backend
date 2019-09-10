<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\SystemAdmin;
use Backend\modules\admin\models\SystemGame;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemGroupGame;
use Backend\modules\admin\models\SystemGroupPriv;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\admin\models\SystemUser;
use Backend\modules\admin\models\SystemUserGroup;
use Backend\modules\admin\services\admin\DomainAuthSoapClient;
use Backend\modules\admin\services\AdminService;
use Backend\modules\admin\services\SystemAdminService;
use Backend\modules\admin\services\SystemService;
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

        $game_id = Helpers::getRequestParam('game_id');
        //如果有专项游戏id，则过滤有此游戏管理权限的角色。并且加上所有没有角色的管理员
        if ($game_id != -1) {
            //查找所有没有角色的管理员
//            $hasRoleAdminId = ArrayHelper::getColumn(SystemUserGroup::find()->select('ad_uid')->asArray()->all(), 'ad_uid');
//            if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
//                $noRoleAdminId = ArrayHelper::getColumn(SystemAdmin::find()->select('system_ad_uid')->where(['not in', 'system_ad_uid', $hasRoleAdminId])->asArray()->all(), 'system_ad_uid');
//            } else {
//                $noRoleAdminId = ArrayHelper::getColumn(Admin::find()->select('ad_uid')->where(['not in', 'ad_uid', $hasRoleAdminId])->asArray()->all(), 'ad_uid');
//            }

            //拿出游戏相关的所有角色id，再根据角色id拿出所有的ad_uid
            $roleIds = ArrayHelper::getColumn(SystemGroupGame::find()->select('sg_id')->where(['game_id' => $game_id])->asArray()->all(), 'sg_id');
            $adminIds =  ArrayHelper::getColumn(SystemUserGroup::find()->select('ad_uid')->distinct()->where(['in', 'sg_id', $roleIds])->asArray()->all(), 'ad_uid');
//            $adminIds = array_merge($adminIds, $noRoleAdminId);

            if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
                $this->query->where(['in', SystemAdmin::tableName().'.system_ad_uid', array_values($adminIds)]);
            } else {
                $this->query->where(['in', Admin::tableName().'.ad_uid', array_values($adminIds)]);
            }
        }

        $status = intval(Helpers::getRequestParam('status'));
        $account = Helpers::getRequestParam('account');
        $username = Helpers::getRequestParam('username');
        $staff_number = Helpers::getRequestParam('staff_number');

        if ($status != -1) {
            $this->query->andWhere(['`'.Admin::tableName().'`.`status`' => intval($status)]);
        }

        if (!empty($account)) {
            $this->query->andWhere(['like', '`'.Admin::tableName().'`.`account`', $account]);
        }

        if (!empty($username)) {
            $this->query->andWhere(['like', '`'.Admin::tableName().'`.`username`', $username]);
        }

        if (!empty($staff_number)) {
            $this->query->andWhere(['`'.Admin::tableName().'`.`staff_number`' => $staff_number]);
        }

        $this->query->orderBy('`'.Admin::tableName().'`.`ad_uid` asc');
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

        //添加基本锁，防止多次提交
        $lockAccountKey = "create_admin_lock_" . $staff_number . "_" . $auth_type;
        $redis = \Yii::$app->redis;
        if (!empty($redis->get($lockAccountKey))) {
            throw new CustomException('此账号添加中，请稍后刷新重试！');
        } else {
            $redis->set($lockAccountKey, $sid);
            $redis->expire($lockAccountKey, 5);
        }

        if ($auth_type == Admin::AUTH_TYPE_PASSWORD) {
            $admin = Admin::findOne(['account' => $account, 'auth_type' => $auth_type]);
        } else {
            $admin = Admin::findOne(['staff_number' => $staff_number, 'auth_type' => $auth_type]);
        }

        $confirmTag = Helpers::getRequestParam("confirm");
        if (empty($admin)) {
            $admin = new Admin();
            $admin->setScenario('create');
            $admin->load(Helpers::getRequestParams(), '');
            if ($admin->validate()) {
                $admin->save(false);
            }
        } else if (empty($confirmTag) && $auth_type == Admin::AUTH_TYPE_PASSWORD) {//非空时，并且是普通账密，提示管理员确认信息
            //操作完成，删除redis对账号的锁定
            $redis->del($lockAccountKey);
            return [
                'code' => -2,
                'msg' => "当前普通账号（{$account}）已存在<br>原姓名为: {$admin->username}<br>原手机号为：{$admin->mobile_phone}。<br>信息无误，请点确认；否则，请修改账号重新提交！",
            ];
        }

        $admin->load(Helpers::getRequestParams(), '');

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

        //操作完成，删除redis对账号的锁定
        $redis->del($lockAccountKey);

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

        $sg_ids = empty($admin->sg_id) ? [] : explode(',', strval($admin->sg_id));//提交上来的角色id
        $oldSg_id = ArrayHelper::getColumn($admin->getRoles(), 'sg_id');//原来的角色id
        $game_id = intval(Helpers::getRequestParam('game_id'));
        $currentGameRoleIds = \Yii::$app->user->identity->getMyRoleIdsOnGame($game_id);//当前操作员的对应游戏的角色id

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

    //专有角色权限
    public function actionProperRole()
    {
        $id = Helpers::getRequestParam('id');
        Helpers::validateEmpty($id, 'id');

        $admin = Admin::findOne(['ad_uid' => $id]);
        if (empty($admin))
            throw new CustomException('用户不存在');

        //开发超管不允许设置专有角色
        $currentSystem = SystemService::getCurrentSystem();
        if ($currentSystem->dev_account === $admin->ad_uid)
            throw new CustomException("开发人员管理员不允许被设置为无角色");

        //先查看角色id
        $roles = $admin->getRoles();
        if (\Yii::$app->request->isGet) {//get则拉取专有角色的游戏和数据
            $result = [
                'privileges' => [],//权限列表
                'games' => [],//游戏列表
            ];

            if (empty($roles) || $roles[0]->kind == SystemGroup::SYSTEM_ROLE_KIND_COMMON) {
                //如果是空，或者第一个角色不是专有角色，返回空的所有可选游戏列表和可选权限列表
                $roleGames = [];
                $groupPrivileges = [];
            } else {
                //否则，拉取专有角色的游戏和权限
                $roleGames = ArrayHelper::getColumn($roles[0]->gameIds, 'game_id');
                //角色权限
                $groupPrivileges = ArrayHelper::getColumn(SystemGroupPriv::getPrivilegesIdsByGroupId($roles[0]->sg_id), 'privilege');
            }

            //所有权限
            $allPrivileges = SystemPriv::getAll();

            //管理员当前操作所在游戏拥有的权限
            $gameId = intval(Helpers::getRequestParam('game_id'));
            $selfPrivileges = \Yii::$app->user->identity->getPrivileges($gameId, '*');
            $selfPrivilegeIds = ArrayHelper::getColumn($selfPrivileges, 'sp_id');
            foreach ($allPrivileges as $sp_id => $allPrivilege) {
                if (!empty($groupPrivileges[$sp_id])) {//已有权限，默认选中
                    $allPrivileges[$sp_id]['is_checked'] = 1;
                }
                if (!in_array($sp_id, $selfPrivilegeIds)) {//要禁掉的是自己没有的权限
                    $allPrivileges[$sp_id]['chkDisabled'] = 1;
                }
            }

            $result['privileges'] = array_values($allPrivileges);

            //拉取游戏列表
            $currentSystem = SystemService::getCurrentSystem();
            //如果系统本身不区分游戏，只返回不区分游戏的标志
            if ($currentSystem === Game::GAME_TYPE_NONE) {//系统本身不区分游戏
                $games = [['game_id' => '-1', 'name' => '不区分游戏', 'selected' => intval(in_array('-1', $roleGames))]];
            } else {//系统区分游戏
                $games = [];
                $gameWhere = ['status' => Game::GAME_STAT_NORMAL];
                //区分游戏的，只能设置为当前游戏，除非是不区分游戏的管理员
                if ($gameId != -1) {
                    $gameWhere['game_id'] = $gameId;
                } else {
                    $games = [['game_id' => '-1', 'name' => '不区分游戏', 'selected' => intval(in_array('-1', $roleGames))]];
                }
                if (empty($gameWhere['game_id'])) {
                    $ids = SystemGame::getSystemGameIds();
                    $gameWhere['game_id'] = $ids;
                }
                $realGames = Game::find()->where($gameWhere)->asArray()->all();
                foreach ($realGames as $key => $realGame) {
                    if (in_array($realGame['game_id'], $roleGames)) {
                        $realGames[$key]['selected'] = 1;
                    }
                }
                //系统管理员，增加不区分游戏按钮
                $games = array_merge($games, $realGames);
            }
            $result['games'] = $games;

            return $result;

        } elseif (\Yii::$app->request->isPost) {//post则修改专有角色的游戏和数据

            //添加基本锁，防止多次提交
            $lockAccountKey = "update_admin_proper_priv_" . $id;
            $redis = \Yii::$app->redis;
            if (!empty($redis->get($lockAccountKey))) {
                throw new CustomException('此账号操作中，请稍后刷新重试！');
            } else {
                $redis->set($lockAccountKey, $id);
                $redis->expire($lockAccountKey, 5);
            }

            $on_game = explode(',', Helpers::getRequestParam('proper_on_game'));
            if (empty($on_game)) {
                throw new CustomException("必须设置管理的游戏");
            }
            $gameId = intval(Helpers::getRequestParam('game_id'));
            if ($gameId != -1 && $gameId != implode($on_game)) {//区分游戏的只能设置当前登录的游戏
                throw new CustomException('你只能设置角色关联游戏为当前游戏');
            }

            $params = Helpers::getRequestParams();
            $newPrivileges = [];
            foreach ($params as $key => $val) {
                //组装通用权限数据
                substr($key, 0, 11) == 'privileges_' && array_push($newPrivileges, $val);
            }

            $db = \Yii::$app->getDb()->beginTransaction();
            try {
                //修改用户角色
                if (empty($roles) || $roles[0]->kind == SystemGroup::SYSTEM_ROLE_KIND_COMMON) {
                    //如果是空，则创建一个专有角色，并修改用户角色关联
                    $group = new SystemGroup();
                    $group->sg_name = "无角色";
                    $group->sg_desc = "这是用户的专有角色，不可单独修改权限。且与通用角色不兼容使用";
                    $group->kind = SystemGroup::SYSTEM_ROLE_KIND_PROPER;
                    $group->save();

                    //将角色和用户关联起来
                    $oldSg_id = ArrayHelper::getColumn($roles, 'sg_id');
                    //取所有的角色id
                    $sg_ids = [$group->sg_id];
                    $currentGameRoleIds = array_merge($oldSg_id, $sg_ids);

                    if (SystemAdminService::checkUseNewSystemAdminSchedule()) {
                        $systemAdmin = SystemAdmin::findOne(['ad_uid' => $admin->ad_uid]);
                        $systemAdminId = $systemAdmin->system_ad_uid;
                    } else {
                        $systemAdminId = $admin->ad_uid;
                    }

                    //更新专有角色时，取消所有角色，再添加新专有角色
                    SystemUserGroup::updateAdminUserGroup($systemAdminId, $sg_ids, $oldSg_id, $currentGameRoleIds);
                } else {
                    //否则，直接更新角色的负责游戏和权限
                    $group = $roles[0];
                }

                //我所选游戏拥有的所有权限
                $currentSystem = SystemService::getCurrentSystem();
                if (\Yii::$app->user->identity->ad_uid === $currentSystem->dev_account) {//管理员取所有权限
                    $myPrivilege = SystemPriv::getAll();
                } else {
                    $myPrivilege = \Yii::$app->user->identity->getPrivilegesOnGame($gameId, '*');
                }
                $myPrivilegeIds = ArrayHelper::getColumn($myPrivilege, 'sp_id');

                $oldPrivileges = SystemGroupPriv::getPrivilegesIdsByGroupId($group->sg_id);
                $diffPrivileges = SystemGroupPriv::diffPrivList($oldPrivileges, $newPrivileges, $myPrivilegeIds);

                //设置权限修改
                SystemGroupPriv::deleteDeductPrivileges($group->sg_id, $diffPrivileges['delPrivilegesIds']);
                SystemGroupPriv::createAddPrivileges($group->sg_id, $diffPrivileges['addPrivilegesIds']);

                //修改完毕，校验角色的权限级别
                $sp_ids = SystemGroupPriv::find()->select('sp_id')->where(['sg_id' => $group->sg_id])->all();
                $businessPriv = SystemPriv::findOne(['sp_set_or_business' => SystemPriv::PRIVILEGE_TYPE_BUSINESS, 'sp_id' => $sp_ids]);
                $settingPriv = SystemPriv::findOne(['sp_set_or_business' => SystemPriv::PRIVILEGE_TYPE_SETTING, 'sp_id' => $sp_ids]);
                $newPrivilegeLevel = 0;
                //重置角色的业务权限
                if (!empty($businessPriv)) {
                    $newPrivilegeLevel |= SystemGroup::SYSTEM_PRIVILEGE_LEVEL_FRONT;
                }
                //重置角色的管理权限
                if (!empty($settingPriv)) {
                    $newPrivilegeLevel |= SystemGroup::SYSTEM_PRIVILEGE_LEVEL_ADMIN;
                }
                SystemGroup::setRolePrivilegeLevel($group, $newPrivilegeLevel);

                //设置管理游戏
                if ($gameId === -1) {
                    $myGames = ArrayHelper::getColumn(Game::getAllGames(['type' => $currentSystem->game_type]), 'game_id');
                    array_push($myGames, -1);
                } else {
                    $myGames = [$gameId];
                }
                SystemGroup::updateRoleGames($group, $on_game, $myGames);

            } catch (\Exception $exception) {
                $db->rollBack();
                $errMsg = $exception->getMessage();
                throw new CustomException($errMsg);
            }

            $db->commit();

            //操作完成，删除redis对账号的锁定
            $redis->del($lockAccountKey);

            return [];
        }
    }

    public function actionResetPassword()
    {
        //重置密码，先不踢所有用户下线
        $resetUser = Admin::find()->where(["ad_uid" => intval(Helpers::getRequestParam("ad_uid"))])->one();

        if (empty($resetUser))
            throw new CustomException('用户不存在');

        //只允许普通账号
        if ($resetUser->auth_type != Admin::AUTH_TYPE_PASSWORD)
            throw new CustomException('只能修改普通账号的密码');

        $resetUser->resetPasswd();

        return [];
    }
}