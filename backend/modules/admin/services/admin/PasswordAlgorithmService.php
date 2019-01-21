<?php
/**
 * 旧系统中所有关于密码的加密算法统计
 * User: tianweimin
 * Date: 2018/12/12
 * Time: 19:18
 */

namespace Backend\modules\admin\services\admin;


use Backend\modules\admin\models\Admin;

class PasswordAlgorithmService
{
    /**
     * 系统id对应的加密算法
     * @var array
     */
    public static $systemMapAlgorithm = [
        '0' => 'encryptNewest',//数据兼容
        '1' => 'encryptNewest',
        '5' => 'encryptInIdip',
    ];

    public static function encryptInIdip(Admin $adminModel)
    {
        $pre = '4567890123';
        return md5($pre . $adminModel->password);
    }

    //统一后的最新加密算法
    public static function encryptNewest(Admin $adminModel)
    {
        return md5(md5($adminModel->password) . $adminModel->salt) == $adminModel->passwd;
    }
}