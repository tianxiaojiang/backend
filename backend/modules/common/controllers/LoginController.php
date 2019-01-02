<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/9
 * Time: 9:39
 */

namespace Backend\modules\common\controllers;


use Backend\behavior\ModulePrivileges;
use Backend\behavior\ValidateGame;
use Backend\behavior\ValidateIsLogin;
use Backend\behavior\ValidateSystem;

/**
 * Class SystemController
 * @package Backend\modules\common\controllers
 *
 * 验证系统和游戏、登录态
 */
class LoginController extends JwtController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ValidateIsLogin'] = ValidateIsLogin::class;

        return $behaviors;
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $this->validateIsLoggedIn();//验证用户的token是否在系统里还有效

        return true;
    }
}
