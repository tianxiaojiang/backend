<?php

namespace Backend\modules\admin\controllers;


use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\helpers\UploadLimit;
use Backend\modules\admin\models\Img;
use Backend\modules\common\controllers\JwtController;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\validators\FileValidator;
use yii\validators\ImageValidator;
use yii\web\UploadedFile;

/**
 * 文件上传类
 */
class FileController extends JwtController {

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

        $file_path = \Yii::$app->getBasePath() . '/public' . $fullName;

        FileHelper::createDirectory(dirname($file_path));
        if ($file->saveAs($file_path)) {
            $info['file_path'] = $fullName;
            $info['web_path'] = \Yii::$app->params['uploadConfig']['imageUrlPrefix'] . $fullName;
        } else {
            throw new CustomException($this->error);
        }

        // 图片后期推送cdn上
//        $scenario = \Yii::$app->request->getQueryParam('scenario');
//        $ftpPlat = Helpers::getFtpChannelByScenario($scenario);
//        if (empty($ftpPlat)) {
//            $info['status'] = FALSE;
//            $info['error'] = '缺失上传场景或场景非法';
//            return Json::encode($info);
//        }
//        $info['file_path'] = \common\components\Ftp::getInstance($ftpPlat)->push($file_path);
//        $info['web_path'] = $ftpPlat['accessDomain'] . $info['file_path'];

        //图片入库
        $size = getimagesize($file_path);
        $info['imgId'] = Img::InsertFeedBackImg($info['file_path'], $size);

        //删除本地文件
        //unlink($file_path);

        return $info;
    }

    /**
     * 验证文件
     * @param UploadedFile $file
     * @return bool
     */
    private function fileValidator(UploadedFile $file)
    {
        return (new FileValidator([
            'extensions' => \Yii::$app->params['uploadConfig']['imageAllowFiles'],
            'minSize' => NULL,
            'maxSize' => \Yii::$app->params['uploadConfig']['imageMaxSize'],
                ]))->validate($file, $this->error);
    }

    private function attachmentFileValidator(UploadedFile $file, $maxSize, $extensions, $denyExtensions){
        $ext = $file->getExtension();
        if(in_array(strtolower($ext), $denyExtensions)){
            $this->error = '文件不合法';
            return false;
        }
        return (new FileValidator([
            'extensions' => $extensions,
            'minSize' => NULL,
            'maxSize' => $maxSize,
                ]))->validate($file, $this->error);
    }

    /**
     * 验证图片
     * @param UploadedFile $file
     * @return bool
     */
    private function imageValidator(UploadedFile $file)
    {
        return (new ImageValidator([
            'minWidth' => NULL,
            'maxWidth' => NULL,
            'minHeight' => NULL,
            'maxHeight' => NULL
                ]))->validate($file, $this->error);
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
