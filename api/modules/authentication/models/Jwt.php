<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/10/25
 * Time: 14:21
 */

namespace Api\modules\authentication\models;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\models\Admin;
use Backend\modules\admin\models\System;
use Backend\modules\admin\models\SystemGroupGame;
use yii\helpers\ArrayHelper;

class Jwt
{
    public $admin;   //关联用户

    public $Header = [
        'alg' => 'HS256',
        'typ' => 'JWT',
    ];

    public $Payload = [
        'iss' => null,//签发人
        'exp' => null,//过期时间
        'sub' => null,//主题
        'aud' => null,//受众
        'nbf' => null,//生效时间
        'iat' => null,//签发时间
        'jti' => null,//编号
    ];

    public $Signature;

    public function __construct(Admin $admin = null)
    {
        $this->Payload['iss'] = \Yii::$app->params['jwt']['iss'];
        $this->Payload['exp'] = \Yii::$app->params['jwt']['exp'];
        $this->Payload['sub'] = \Yii::$app->params['jwt']['sub'];
        $this->Payload['aud'] = \Yii::$app->params['jwt']['aud'];
        $this->Payload['nbf'] = \Yii::$app->params['jwt']['nbf'];
        $this->Payload['iat'] = \Yii::$app->params['jwt']['iat'];
        $this->Payload['jti'] = \Yii::$app->params['jwt']['jti'];

        $this->admin = $admin;
    }

    public function setAdmin()
    {
        $this->admin = (AccessToken::find()->where(['ad_uid' => $this->Payload['uid']])->one());
        if (empty($this->admin))
            throw new CustomException('管理员不存在');
        return $this;
    }

    public function supplementPayloadByAdmin(System $system)
    {
        if (empty($this->admin)) {
            throw new CustomException('jwt未关联管理员');
        }

        $roles = $this->admin->getRoleInfo();
        $this->Payload['uid'] = $this->admin->ad_uid;
        $this->Payload['name'] = $this->admin->username;
        $this->Payload['role_info'] = json_encode($roles);
        $this->Payload['sid'] = $system->systems_id;
        $this->Payload['system_name'] = $system->name;

        return $this;
    }

    public function getGameIdsByToken()
    {
        $roleInfo = \Yii::$app->user->identity->jwt->Payload['role_info']->getValue();
        $roleInfo = json_decode($roleInfo, true);
        return array_merge(...$roleInfo);
    }

    public function getSystemGroupIdsByToken()
    {
        $roleInfo = \Yii::$app->user->identity->jwt->Payload['role_info']->getValue();
        $roleInfo = json_decode($roleInfo, true);
        return array_keys($roleInfo);
    }
}