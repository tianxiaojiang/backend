<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2017/11/14
 * Time: 15:49
 */

namespace Business\components\helpers;

use \Yii;
use yii\db\Exception;

class Ftp
{
    private static $instance = null;

    private $ftp = null;

    private $_domain = null;
    private $_account = null;
    private $_password = null;
    private $_projectPath = '';

    private $_is_in_dir = 0;

    private function __construct($ftpChannel)
    {
        Yii::info('channel' . var_export($ftpChannel, true));
        // 输入配置
        $this->_account = $ftpChannel['account'];
        $this->_password = $ftpChannel['password'];
        $this->_domain = $ftpChannel['domain'];
        $this->_projectPath = $ftpChannel['projectPath'];
        $this->_projectPath = str_replace('{yyyy}', date('Y'), $this->_projectPath);
        $this->_projectPath = str_replace('{mm}', date('m'), $this->_projectPath);

        $this->initFtp();
    }


    private function initFtp()
    {

        if ($this->ftp == null) {
            $this->ftp = ftp_connect($this->_domain) or die("Couldn't connect to $this->_domain");
            try {
                ftp_login($this->ftp, $this->_account, $this->_password);   // 登录
            } catch (Exception $exception) {
                 throw new Exception($exception->getMessage(), $exception->getCode());
            }
        }
    }

    private function cd()
    {
        if (!$this->_is_in_dir) {
            $this->_is_in_dir = 1;
        } else {
            return true;
        }
        /*
        if (empty(ftp_nlist($this->ftp, $this->_projectPath))){
            ftp_mkdir($this->ftp, $this->_projectPath);
        }*/
        $this->ftp_mksubdirs();
    }

    private function ftp_mksubdirs() {
        //@ftp_chdir($this->ftp, $this->_projectPath); // /var/www/uploads
        $parts = explode('/', $this->_projectPath); // 2013/06/11/username
        foreach($parts as $part){
            if(!empty($part) && !@ftp_chdir($this->ftp, $part)){
                ftp_mkdir($this->ftp, $part);
                ftp_chdir($this->ftp, $part);
            }
        }
    }

    public static function getInstance($upload_plat = 'for_admin'){

        if (self::$instance == null) {
            self::$instance = new self($upload_plat);
        }

        return self::$instance;
    }

    public function pull($remoteFile, $localFile)
    {
        return ftp_get($this->ftp, $localFile, $remoteFile, FTP_BINARY);
    }

    public function push($localFile, $ext)
    {
        $this->cd();
        //$localFileInfo = explode('/', $localFile);
//        $fileName = array_pop($localFileInfo);
        $fileName = time() . rand(1000, 9999) . '.' . $ext;
        ftp_put($this->ftp, $fileName, $localFile, FTP_BINARY);
        return $this->_projectPath . $fileName;
    }
}