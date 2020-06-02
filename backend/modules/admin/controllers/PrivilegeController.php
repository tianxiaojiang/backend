<?php

namespace Backend\modules\admin\controllers;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\SystemGroup;
use Backend\modules\admin\models\SystemMenu;
use Backend\modules\admin\models\SystemPriv;
use Backend\modules\common\controllers\BusinessController;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:05
 */
class PrivilegeController extends BusinessController
{
    public $modelClass = 'Backend\modules\admin\models\SystemPriv';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['delete']);

        return $actions;
    }

    /**
     * 后台维护权限
     * @return array
     */
    public function actionIndex()
    {
//        $sid = intval(Helpers::getRequestParam('sid'));
//        $menuType = $sid === 1 ? SystemPriv::PRIVILEGE_TYPE_SETTING : SystemPriv::PRIVILEGE_TYPE_BUSINESS;
//        $privileges = SystemPriv::find()->where(['sp_set_or_business' => $menuType])->all();//取出所有的权限
        $privileges = SystemPriv::find()->indexBy("sp_id")->orderBy('sp_id asc')->asArray()->all();//取出所有的权限

        $privResults = [];
        $sortType = Helpers::getRequestParam('useSort');
        if (empty($sortType)) {
            foreach ($privileges as $item) {
                if ($item['sort_by'] == 0) {
                    $this->setSortNum1($privileges, $privResults, $item);
                }
            }
            //如果有菜单排序非0，但前置菜单无权限，则直接跟最后
            if (!empty($privileges)) {
                foreach ($privileges as $menu) {
                    array_push($privResults, $menu);
                }
            }
        } else {
            $privResults = array_values($privileges);
        }

        $results = [];
        foreach ($privResults as $item) {
            $results[] = [
                'sp_id' => $item['sp_id'],
                'sp_label' => $item['sp_label'],
                'sp_parent_id' => $item['sp_parent_id'],
                'sp_set_or_business' => $item['sp_set_or_business'],
                'sp_module' => $item['sp_module'],
                'sp_controller' => $item['sp_controller'],
                'sp_action' => $item['sp_action'],
                'sm_id' => $item['sm_id'],
                'sort_by' => $item['sort_by'],
            ];
        }

        return $results;
    }



    /**
     * 批量更新排序
     */
    public function actionUpdateSort()
    {
        $params = Helpers::getRequestParam('nodes');

        $db = \Yii::$app->db;
        $db->beginTransaction();
        try{
            foreach ($params as $param) {
                $priv = SystemPriv::findOne(['sp_id' => $param['sp_id']]);
                $priv->setScenario('update');
                if (isset($param['sort_by'])) {
                    if ($param['sort_by'] == 0) {
                        $priv->sort_by = $param['sort_by'];
                    } else {
                        $prevPriv = SystemPriv::findOne(['sp_id' => intval($param['sort_by'])]);
                        if ($priv->sp_set_or_business === $prevPriv->sp_set_or_business) {
                            $priv->sort_by = $param['sort_by'];
                        } else {
                            $priv->sort_by = 0;
                        }
                    }
                }
                if (isset($param['sp_parent_id'])) {
                    if ($param['sp_parent_id'] == 0) {
                        $priv->sp_parent_id = $param['sp_parent_id'];
                    } else {
                        $parentPriv = SystemPriv::findOne(['sp_id' => intval($param['sp_parent_id'])]);
                        if ($priv->sp_set_or_business === $parentPriv->sp_set_or_business) $priv->sp_parent_id = $param['sp_parent_id'];
                    }
                }
                $priv->save();
            }
        } catch (\Exception $exception) {
            $db->transaction->rollBack();
            \Yii::error($exception->getTraceAsString());
            throw new CustomException($exception->getMessage());
        }

        $db->transaction->commit();

        return [];
    }

    protected function setSortNum1(&$privs, &$results, $priv)
    {
        if ($priv['sort_by'] === 0) {
            array_unshift($results, $priv);
        } else {
            array_push($results, $priv);
        }
        unset($privs[$priv['sp_id']]);

        foreach ($privs as $m) {
            if ($m['sort_by'] === $priv['sp_id']) {
                return $this->setSortNum1($privs, $results, $m);
                break;
            }
        }
        return true;
    }

    /**
     * 重写删除操作
     * 删除一个权限后，重置它后面的顺序id
     */
    public function actionDelete()
    {
        $id = intval(Helpers::getRequestParam("id"));
        if ($id <= 0)
            throw new CustomException("id 不能为空");

        $deletingPriv = SystemPriv::find()->where(["sp_id" => $id])->one();
        if (empty($deletingPriv))
            throw new CustomException("权限不存在");

        // 如果子权限，不允许删除
        $childrenMenu = SystemPriv::find()->where(["sp_parent_id" => $id])->one();
        if (!empty($childrenMenu)) {
            throw new CustomException("请先删除子权限");
        }

        $followPriv = SystemPriv::find()->where(["sort_by" => $id])->one();
        if (!empty($followPriv)) {
            $followPriv->sort_by = $deletingPriv->sort_by;
            $followPriv->save();
        }

        $deletingPriv->delete();

        return [];
    }
}