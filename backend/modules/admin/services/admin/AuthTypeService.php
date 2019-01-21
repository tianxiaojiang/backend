<?php
/**
 * 管理员的不同认证方式
 * User: tianweimin
 * Date: 2018/12/13
 * Time: 10:16
 */

namespace Backend\modules\admin\services\admin;


use Backend\Exception\CustomException;
use Backend\modules\admin\models\Admin;
use yii\httpclient\Client;

class AuthTypeService
{
    public $adminModel = null;

    public $type_map_func = [
        Admin::AUTH_TYPE_DOMAIN => 'auth_by_domain',//域账号认证
        Admin::AUTH_TYPE_PASSWORD => 'auth_by_password',//密码认证
        Admin::AUTH_TYPE_CHANGZHOU => 'auth_by_changzhou',//常州账号认证
    ];

    public function __construct(Admin $adminModel)
    {
        $this->adminModel = $adminModel;
        //$this->adminModel->thirdData = new ThirdData();
    }

    //验证密码
    public function validatePassword()
    {
        $execFunc = $this->type_map_func[intval($this->adminModel->auth_type)];

        return $this->$execFunc();
    }

    //通过域账号验证
    protected function auth_by_domain() {
        if (!DomainAuthSoapClient::getInstance()->validatePassword($this->adminModel->account, $this->adminModel->password)) {
            throw new CustomException('域账号认证失败，请重新填写');
        }

        \Yii::info(sprintf('domain account auth success, account: %s', $this->adminModel->account));

        return true;
    }

    //通过密码算法验证
    protected function auth_by_password() {
        $execFunc = PasswordAlgorithmService::$systemMapAlgorithm[$this->adminModel->password_algorithm_system];
        \Yii::error('----debug:' . var_export($execFunc, true));
        if (!method_exists(PasswordAlgorithmService::class, $execFunc))
            throw new CustomException('密码认证方式错误，请联系管理员');

        if (!PasswordAlgorithmService::$execFunc($this->adminModel))
            throw new CustomException('账号或密码错误，请重新填写');

        \Yii::info(sprintf('common account auth success, account: %s', $this->adminModel->account));

        return true;
    }

    /**
     * 常州账号认证，并且返回账号信息
     * 测试数据
     * $user_name = "zhoufan";
     * $passwd = "xuguangdi119";
     * $ip = "192.168.101.147";
     */
    protected function auth_by_changzhou()
    {
        $url = "http://192.168.150.203/kfpt/KfptInterface/Auth/czPassword";
        $name = "kfccptmanager";
        $key = "d9876a7f568a8d5";
        $ip = \Yii::$app->request->getUserIP();

        $post_data = ["user_name" => $this->adminModel->account, "password" => $this->adminModel->password, "ip" => $ip];
        ksort($post_data);
        $pri_key = md5(md5(json_encode($post_data)) . $key);
        $post_data["name"] = $name;
        $post_data["key"] = $pri_key;
        $httpClient = new Client();
        $response = $httpClient->post($url, $post_data)->send();
        $login_data = $response->getData();

        if ($login_data["code"] != 0)
            throw new CustomException($login_data['message']);

        if (!empty($login_data["data"]['user_name']))
            $this->adminModel->username = $login_data["data"]['user_name'];
        if (!empty($login_data["data"]['staffnum']))
            $this->adminModel->staff_number = $login_data["data"]["staffnum"];

        \Yii::info(sprintf('common account auth success, account: %s', $this->adminModel->account));

        return true;
    }
}