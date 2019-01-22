<?php

namespace Backend\modules\admin\controllers;


use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\UploadLimit;
use Backend\modules\common\controllers\BusinessController;
use Business\components\helpers\Ftp;
use Business\modules\index\models\Img;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * 文件上传类
 */
class FileController extends BusinessController {

    public $enableCsrfValidation = false;

    public $modelClass = 'Backend\modules\admin\models\Img';

    /**
     * 错误信息
     * @var string 
     */
    private $error;

    public function actionUpload()
    {
        $info = ['file_path' => '', 'web_path' => ''];

        $scenario = Helpers::getRequestParam('scenario');
        $file = UploadedFile::getInstanceByName('file');

        $res = UploadLimit::getInstance($scenario)->checkLimit($file);
        if ($res !== true) {
            throw new CustomException(implode("\n", $res));
        }

        $fullName = $this->getFullName($file, \Yii::$app->params['uploadConfig']['imagePathFormat']);

        // 根据上传场景执行后续图片保存地址
        $dest = \Yii::$app->params['uploadConfig']['scenarios'][$scenario]['dest_type'];
        $destConfig = \Yii::$app->params['upload_dest'][$dest];

        //需要返回访问host、图片路径、图片base64内容
        switch ($destConfig['type']) {
            case 'ftp':
                $saveRes = $this->destFtp($file, $fullName, $destConfig);
                break;

            case 'local':
                $saveRes = $this->destLocal($file, $fullName, $destConfig);
                break;

            case 'base64':
                $saveRes = $this->destBase64($file);
                break;

            default:
                $saveRes = $this->destLocal($file, $fullName, $destConfig);
                break;
        }

        //图片入库
        $size = getimagesize($file->tempName);
        $info['imgId'] = Img::InsertFeedBackImg($saveRes['file_path'], $size, $saveRes['img_content']);
        $info['web_path'] = $saveRes['access_domain'] . $saveRes['file_path'];

        //如果图片是反馈的IM回复，则特殊化返回结果
        if ($scenario === 'feedback_im_img') {
            $info = ["src" => $info['web_path']];
        }

        return $info;
    }

    //后续存入本地
    private function destLocal(UploadedFile $file, $fullName, $config)
    {
        $file_path = $config['absolute_dir'] . $fullName;
        FileHelper::createDirectory(dirname($file_path));
        if ($file->saveAs($file_path)) {
            return [
                'access_domain' => $config['accessDomain'],
                'file_path' => $fullName,
                'img_content' => ''
            ];
        } else {
            throw new CustomException($this->error);
        }
    }

    //后续传输到ftp上
    private function destFtp(UploadedFile $file, $fullName, $config)
    {
        $ftpInstance = Ftp::getInstance($config, dirname($fullName));
        $fullName = $ftpInstance->push($file->tempName, $file->getExtension());

        return [
            'access_domain' => $config['accessDomain'],
            'file_path' => $fullName,
            'img_content' => ''
        ];
    }

    private function destBase64(UploadedFile $file)
    {
        if($fp = fopen($file->tempName, "rb", 0)) {
            $binary = fread($fp, filesize($file->tempName)); // 文件读取
            fclose($fp);
            $base64 = base64_encode($binary); // 转码
        } else {
            throw new CustomException('上传图片失败！');
        }

        $base64Cont = 'data:image/'.$file->getExtension().';base64,' . $base64;

        return [
            'access_domain' => '',
            'file_path' => '',
            'img_content' => $base64Cont
        ];
    }

    /**
     * 获取文件全名(解析文件路径)
     * @param UploadedFile $file
     * @return string
     */
    private function getFullName(UploadedFile $file, $format = '')
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        //$format = Yii::$app->params['uploadConfig']['imagePathFormat'];
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);

        //过滤文件名的非法自负,并替换文件名
        $oriName = substr($file->getBaseName(), 0, strrpos($file->getBaseName(), '.'));
        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);

        //替换随机字符串
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        $ext = $file->getExtension();
        return $format . '.' . $ext;
    }

}
