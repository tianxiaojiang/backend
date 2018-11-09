<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2017/10/16
 * Time: 14:16
 */

namespace Backend\helpers;

use Backend\Exception\CustomException;
use yii;

class Helpers
{
    static public $request_params;

    static public $heanders;

    public static function info($content)
    {
        file_put_contents('E:\project\backend\backend\runtime\\' . date('YmdH') . '.log', $content . "\n", FILE_APPEND);
    }

    /**
     * 生成AccessToken
     * @param $account
     * @return string
     */
    public static function generateAccessToken($account)
    {
        $account = ($account || rand(1000, 9999));
        $time = microtime();
        return md5($account . $time);
    }

    public static function getFirstError($model)
    {
        $errors = $model->errors;
        $error = array_shift($errors);

        return $error[0];
    }

    /**
     * 生成一个随机字符串
     * @param int $length
     * @param bool $ctype_alnum
     * @return string
     */
    public static function getStrBylength($length = 8, $ctype_alnum = true)
    {
        $charsLib = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM~!@#$%^&*()_+/';
        $ctypeAlnumALib = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';

        $str = $ctype_alnum ? $ctypeAlnumALib : $charsLib;

        $resultStr = '';
        for ($i = 0; $i < $length; $i++) {
            $key = rand(0, strlen($str) - 1);
            $resultStr .= $str[$key];
        }

        return $resultStr;
    }

    /**
     * 结果集筛选
     * @param $filter_type 0 表示全部，1 表示需要的字段，2 表示不想要的字段
     * @param $fields
     * @param $list
     * @return array
     */
    public static function filterResultItemFields($filter_type, $fields, $list)
    {
        $filter_type = intval($filter_type);
        $fields = explode(',', $fields);

        if ($filter_type == 0) {// 筛选所有
            $result = $list;
        } else {
            $result = array_map(function($item) use ($filter_type, $fields) {
                if ($filter_type == 1) {// 筛选交集
                    return array_intersect_key($item, array_flip($fields));
                } else if ($filter_type == 2) {// 筛选差集
                    return array_diff_key($item, array_flip($fields));
                }
            }, $list);
        }

        return $result;
    }

    /**
     * 发送邮件
     * @param $to
     * @param $subject
     * @param $body
     * @return bool
     */
    public static function sendMail($to, $subject, $body)
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($to); //要发送给那个人的邮箱
        $mail->setSubject($subject); //邮件主题
        //$mail->setTextBody('测试text'); //发布纯文字文本
        $mail->setHtmlBody($body); //发送的消息内容
        return $mail->send();
    }

    /**
     * 获取所有入参
     * @return array
     */
    public static function getRequestParams()
    {
        if (empty(self::$request_params)) {
            $queryParams = Yii::$app->request->getQueryParams(); //不传参数就返回整个数组
            $bodyParams  = Yii::$app->request->getBodyParams();
            self::$request_params = yii\helpers\ArrayHelper::merge($queryParams, $bodyParams);
        }

        return self::$request_params;
    }

    /**
     * 获取某个参数
     * @param $key
     * @return mixed|null
     */
    public static function getRequestParam($key)
    {
        $requestParams = self::getRequestParams();

        if (isset($requestParams[$key])) {
            return $requestParams[$key];
        }

        return null;
    }

    //获取heander
    public static function getHeader($key)
    {
        if (static::$heanders == null) {
            static::$heanders = Yii::$app->request->headers;
        }

        if (static::$heanders->get($key)) {
            return static::$heanders->get($key);
        }

        return null;
    }

    //验证参数不能为空
    public static function validateEmpty($value, $tip)
    {
        if (empty($value))
            throw new CustomException(sprintf("参数%s不能为空！", $tip));
    }
}