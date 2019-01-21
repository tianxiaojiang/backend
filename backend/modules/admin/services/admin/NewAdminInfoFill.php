<?php
/**
 * 新账号的信息填充
 * User: tianweimin
 * Date: 2018/12/13
 * Time: 10:16
 */

namespace Backend\modules\admin\services\admin;

use Backend\Exception\CustomException;
use Backend\modules\admin\models\Admin;

class NewAdminInfoFill
{

    public $type_map_func = [
        Admin::AUTH_TYPE_PASSWORD => 'info_by_password',//普通账号信息填充
        Admin::AUTH_TYPE_DOMAIN => 'info_by_domain',//域账号信息填充
        Admin::AUTH_TYPE_CHANGZHOU => 'info_by_changzhou',//常州账号信息填充
    ];

    public function __construct(Admin $adminModel)
    {
        $this->adminModel = $adminModel;
    }

    //获取昵称、工号等账户信息
    public function fillFieldAtCreate()
    {
        $execFunc = $this->type_map_func[$this->adminModel->auth_type];
        return $this->$execFunc();
    }

    //普通账密账号，加密密码
    protected function info_by_password()
    {
        if (empty($this->adminModel->password))
            throw new CustomException('新的普通账号填充信息时的密码不能为空!');

        if (empty($this->adminModel->salt))
            throw new CustomException('新增普通账号填充信息时的salt字段未设置!');

        if ($this->adminModel->password_algorithm_system != 1) {
            $this->adminModel->password_algorithm_system = 1;
            $this->adminModel->passwd = $this->adminModel->password;
        }
    }

    //通过域账号获取
    protected function info_by_domain() {
        if (empty($this->adminModel->account)) {
            throw new CustomException('新增域账号填充信息时缺少字段account!');
        }
        $login_data = DomainAuthSoapClient::getInstance()->getDomainInfo($this->adminModel->account);
        $nickname = $this->adminModel->account;
        $mobile_phone = 0;

        if (!empty($login_data->display_name))
            $nickname = $login_data->display_name;

        if (!empty($login_data->staffnum)) {
            $staff_number = $login_data->staffnum;
        } else {
            //特殊的域账号，没有工号的，初始化一个时间戳为工号
            $staff_number = time();
        }

        if (!empty($login_data->mobile))
            $mobile_phone = $login_data->mobile;

        return $this->_fillInAdminData($nickname, $staff_number, $mobile_phone);
    }

    /**
     * 第三方信息，在认证的时候，已经赋值过
     */
    protected function info_by_changzhou()
    {
        return true;
    }

    /**
     * 认证后回填用户信息
     * @param $nickname
     * @param int $staffNum
     */
    private function _fillInAdminData($nickname, $staffNum = 0, $mobile_phone = 0)
    {
        $this->adminModel->username = $nickname;
        $this->adminModel->staff_number = $staffNum;
        $this->adminModel->mobile_phone = $mobile_phone;

        return true;
    }

}