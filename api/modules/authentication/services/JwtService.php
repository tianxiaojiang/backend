<?php

namespace Api\modules\authentication\services;

use Backend\Exception\CustomException;
use Backend\helpers\Lang;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Api\modules\authentication\models\Jwt;
use Lcobucci\JWT\ValidationData;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/10/25
 * Time: 14:13
 *
 * json web token类用于token的生成、解析与校验
 */

class JwtService
{
    protected $tokenString;
    public $jwtObj;
    public $tokenObj;

    public function __construct(Jwt $jwtObj, $tokenString = '')
    {
        $this->jwtObj = $jwtObj;
        $this->tokenString = $tokenString;
    }

    public function generateTokenString()
    {
        $tokenBuilder = new Builder();

        //set headers
        foreach ($this->jwtObj->Header as $key => $value) {
            if (!empty($value))
                $tokenBuilder->setHeader($key, $value);
        }

        //set payload
        foreach ($this->jwtObj->Payload as $key => $value) {
            if (!empty($value))
                $tokenBuilder->set($key, $value);
        }

        //sign
        $signer = new Sha256();
        $tokenBuilder->sign($signer, $this->jwtObj->admin->salt);

        $this->tokenObj = $tokenBuilder->getToken();
        $this->tokenString = $this->tokenObj->__toString();

        return $this->tokenString;
    }

    public function parseToken()
    {
        $this->tokenObj = (new Parser())->parse($this->tokenString);
        $this->jwtObj->Header = $this->tokenObj->getHeaders();
        $this->jwtObj->Payload = $this->tokenObj->getClaims();
        $this->jwtObj->setAdmin();
        $this->jwtObj->admin->jwt = $this->jwtObj;

        return $this;
    }

    public function validateToken()
    {
        $data = new ValidationData();
        $data->setIssuer($this->jwtObj->Payload['iss']);
        $data->setCurrentTime(time());

        if (!$this->tokenObj->validate($data)) {
            throw new CustomException(Lang::ERR_TOKEN_INVALID);
        }

        $signer = new Sha256();
        if (!$this->tokenObj->verify($signer, $this->jwtObj->admin->salt)) {
            throw new CustomException(Lang::ERR_TOKEN_INVALID);
        }

        return true;
    }

}