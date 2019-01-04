<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/12/13
 * Time: 11:31
 */

namespace Backend\modules\admin\services\admin;


class DomainAuthSoapClient
{
    public $_soapClient = null;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->_soapClient = new \SoapClient(\Yii::$app->params['passport']['url']);
    }

    /**
     * 验证账密
     * @param $username
     * @param $password
     * @return bool
     */
    public function validatePassword($username, $password)
    {
        $return = $this->_soapClient->ValidateAdByPasswd(
            $username,
            $password,
            \Yii::$app->request->getUserIP(),
            \Yii::$app->request->getUserIP(),
            \Yii::$app->request->getPort(),
            \Yii::$app->params['passport']['source_system_code']
        );
        \Yii::info('[sso_response] password login,username:'.$username.';password len:'.strlen($password).';return_flag:'.intval($return->return_flag));
        \Yii::info('return_flag:' . var_export($return->return_flag, true));
        \Yii::info('return_flag:' . var_export($return->return_remark, true));
        if($return->return_flag == true)
            return true;
        else
            return false;
    }

    /**
     * 查询域账户信息
     * @param $username
     * @return mixed
     */
    public function getDomainInfo($username)
    {
        return $this->_soapClient->QueryAdUserInfoByName($username);
    }
}