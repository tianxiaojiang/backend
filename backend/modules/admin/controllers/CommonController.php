<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/30
 * Time: 14:09
 */

namespace Backend\modules\admin\controllers;


use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\Game;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemMenu;
use yii\httpclient\Client;

class CommonController extends \Backend\modules\common\controllers\BaseController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    /**
     * 拉取管理员的可选状态列表
     * @return array
     */
    public function actionAdminStats()
    {
        return ['stats' => Admin::$_status];
    }

    public function actionAdminAuthTypes()
    {
        return ['types' => Admin::$_auth_types];
    }

    /**
     * 拉取游戏状态列表
     * @return array
     */
    public function actionGameStats()
    {
        return ['stats' => Game::$_status];
    }

    /**
     * 拉取系统状态列表
     * @return array
     */
    public function actionSystemStats()
    {
        return ['stats' => System::$_status];
    }

    /**
     * 菜单下拉选项，供权限选择
     * @return array
     */
    public function actionMenuSelect()
    {
        $sid = intval(Helpers::getRequestParam('sid'));
        $menuType = ($sid === 1) ? SystemMenu::SM_TYPE_SETTING : SystemMenu::SM_TYPE_BUSINESS;
        $systemMenu = new SystemMenu();
        $menus = $systemMenu::find()->where([
                'sm_set_or_business' => $menuType,
                'sm_status'=> 0,
                'is_show_sidebar' => 1
            ])
            ->orderBy('sort_by asc,sm_id asc')
            ->indexBy('sm_id')->asArray()->all();

        $treeMenus = $systemMenu->formatList2Tree($menus);

        $result = [];
        foreach ($treeMenus as $treeMenu) {
            $loopDepth = 0;
            $result[] = $this->formatMenu($treeMenu, $loopDepth);
            if (!isset($treeMenu['list'])) continue;
            foreach ($treeMenu['list'] as $child) {
                $loopDepth = 2;
                $result[] = $this->formatMenu($child, $loopDepth);
                if (!isset($child['list'])) continue;
                foreach ($child['list'] as $grandson) {
                    $loopDepth = 4;
                    $result[] = $this->formatMenu($grandson, $loopDepth);
                }
            }
        }

        return $result;
    }

    /**
     * 登录页展现的系统
     * @return array
     */
    public function actionSystems()
    {
        $systems = System::findAll(['status' => System::SYSTEM_STAT_NORMAL]);

        $res = [];
        foreach ($systems as $system) {
            $res[] = [
                'description' => $system->description,
                'name' => $system->name,
                'systems_id' => $system->systems_id,
                'show_url' => empty($system->img) ? '' :\Yii::$app->params['uploadConfig']['imageUrlPrefix'] . $system->img->url_path,
            ];
        }

        return ['systems' => $res];
    }

    public function actionGetToken()
    {
        $code = Helpers::getRequestParam('code');
        $sid = Helpers::getRequestParam('sid');

        if (!preg_match("/^\w{8}$/", $code)) {
            throw new CustomException('code码不合法');
        }

        $client = new Client();
        $url = \Yii::$app->params['integration_backend']['url'] . \Yii::$app->params['integration_backend']['gain_token'];
        $response = $client->get($url, ['code' => $code, 'sid' => $sid])->send();

//        var_dump($url . '?code=' . $code . '&sid='. $sid);exit;

        if (!$response->getIsOk())
            throw new CustomException('授权失败，请联系中心后台管理人员');

        $data = $response->getData();
        \Yii::info('get token data:' . var_export($data, true));
        if ($data['code'] != 0)
            throw new CustomException($data['msg']);

        return $data['data'];
    }

    /**
     * 拉取所有正常管理用户
     * @return array
     */
    public function actionAdmins()
    {
        $admins = Admin::find()->where(['status' => Admin::STATUS_NORMAL])->asArray()->all();

        return ['admins' => $admins];
    }

    protected function formatMenu($treeMenu, $loopDepth)
    {
        return [
            'sm_id' => $treeMenu['sm_id'],
            'sm_label' => str_pad('', $loopDepth, "--") . ' ' . $treeMenu['sm_label'],
            'sm_parent_id' => $treeMenu['sm_parent_id'],
            'sm_view' => $treeMenu['sm_view'],
        ];
    }
}