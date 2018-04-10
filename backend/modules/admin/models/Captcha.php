<?php
namespace Backend\modules\admin\models;

use yii\captcha\CaptchaAction;

class Captcha extends CaptchaAction {
    private $verifycode;

    public function __construct(){
        $this->init();
        //更多api请访问yii\captcha\CaptchaAction类文档
        $this->minLength = 4;
        $this->maxLength = 5;
        $this->foreColor = 0x00ff00;
        $this->width = 80;
        $this->height = 45;
    }
    //return image data//返回图片二进制
    public function inline(){
        return $this->renderImage($this->getPhrase());
    }
    //return image code//返回图片验证码
    public function getPhrase(){
        if($this->verifycode){
            return $this->verifycode;
        }else{
            return $this->verifycode = $this->generateVerifyCode();
        }
    }
}
?>