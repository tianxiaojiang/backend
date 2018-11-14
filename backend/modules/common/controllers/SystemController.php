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
use Backend\behavior\ValidateSystem;

/**
 * Class SystemController
 * @package Backend\modules\common\controllers
 *
 * 验证系统和游戏
 */
class SystemController extends JwtController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //验证时间的行为
        $behaviors['ValidateSystem'] = ValidateSystem::class;
        $behaviors['ValidateGame'] = ValidateGame::class;

        return $behaviors;
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->validateSystem();//验证账号有没有对应访问系统的权限
        $this->validateGame();//验证账号有没有对应系统的游戏权限

        return true;
    }
}
