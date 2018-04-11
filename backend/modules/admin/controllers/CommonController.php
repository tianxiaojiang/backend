<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/30
 * Time: 14:09
 */

namespace Backend\modules\admin\controllers;


use Backend\modules\admin\models\Admin;

class CommonController extends \Backend\modules\common\controllers\CommonController
{
    public $modelClass = 'Backend\modules\admin\models\Admin';

    /**
     * 拉取管理员的可选状态列表
     * @return array
     */
    public function actionAdminStats()
    {
        return Admin::$_status;
    }

    public static function actionMenusExt()
    {
        return \Yii::$app->params['menus_ext'];
    }

    public function actionTest()
    {
        \Yii::info('aaaaaaa');
        \Yii::error('bbbbb');
        \Yii::debug('cccc');

        return [];
    }
}