<?php

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\Lang;
use juliardi\captcha\CaptchaValidator;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 15:55
 */
class AccessToken extends Admin
{
    const TOKEN_EXPIRE_DURATION = 60 * 60 * 24 * 7;//7天

    public $password;
    public $captcha;

    static public function tableName()
    {
        return 'admin_user';
    }

    public function fields()
    {
        return ['access_token', 'username'];
    }

    public function scenarios()
    {
        return [
            'login' => ['captcha', 'account', 'password', 'access_token', 'access_token_expire'],
        ];
    }

    public function rules()
    {
        return [
            ['captcha', \yii\captcha\CaptchaValidator::className(), 'message' => '验证码错误', 'captchaAction'=>'admin/token/captcha', 'on' => ['login']],
            ['account', 'required', 'message' => '请输入账号', 'on' => ['login']],
            ['password', 'required', 'message' => '请输入密码', 'on' => ['login']],
            ['password', 'validatePassword', 'message' => '账号或密码错误', 'on' => ['login']],
        ];
    }

    public function generateToken()
    {
        $this->setScenario('login');
        $this->setAttributes(\Yii::$app->request->post());

        if ($this->validate()) {
            $this->access_token         = Helpers::generateAccessToken($this->account);
            $this->access_token_expire  = time() + self::TOKEN_EXPIRE_DURATION;
            $this->passwd               = '';

            $this->save();
        } else {
            $error = Helpers::getFirstError($this);
            throw new CustomException($error);
        }
        //var_dump(ArrayHelper::toArray($this));exit;
        return ArrayHelper::toArray($this);
    }

    //校验密码
    public function validatePassword() {
        if ($this->hasErrors()) {
            return false;
        }

        if ($this->passwd != md5(md5($this->password) . $this->salt)) {
            $this->addError('password', Lang::getMsg(Lang::ERR_LOGIN_FAIL));
        }
    }
}