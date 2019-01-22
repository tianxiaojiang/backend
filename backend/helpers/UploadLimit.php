<?php

namespace Backend\helpers;

use Backend\Exception\CustomException;
use Yii;
use yii\web\UploadedFile;

/**
* 文件上传限制类
*/
class UploadLimit
{
    public $err = null;

    private $scenario;
    private static $scenarios = [];

    private function __construct($scenario){
        $allowScenarios = Yii::$app->params['uploadConfig']['scenarios'];
        if (!isset($allowScenarios[$scenario])) {
            throw new CustomException('图片上传场景缺失');
        }
        $this->scenario = $allowScenarios[$scenario];
    }

    public static function getInstance($scenario)
    {
        if (empty(self::$scenarios[$scenario])) {
            self::$scenarios[$scenario] = new static($scenario);
        }

        return self::$scenarios[$scenario];
    }

    protected $options = [
        'w' => [
            'alias' => '宽度',
            'index_res_getimagesize' => 0,
        ],
        'h' => [
            'alias' => '高度',
            'index_res_getimagesize' => 1,
        ],
    ];

    public function checkLimit(UploadedFile $uploadedFile)
    {
        $this->checkWidthHeight($uploadedFile);
        $this->checkQuality($uploadedFile);
        $this->checkImageType($uploadedFile);

        Yii::info('upload validate res:' . var_export($this->err, true));

        if (!empty($this->err)) {
            return $this->err;
        } else {
            return true;
        }
    }

    /**
     * 检查图片的宽高
     * @param UploadedFile $uploadedFile
     * @return bool|string
     */
    public function checkWidthHeight(UploadedFile $uploadedFile)
    {
        $limit = $this->scenario['size'];

        if (empty($limit) || $limit == 'undefined')
            return true;

        $value  = getimagesize($uploadedFile->tempName);
        $arrs = $limit;

        foreach ($arrs as $type => $arr) {
            if ($type === "prop") {//比例判断
                $propArr = explode('x', $arr);
                if ($value[0]/$value[1] != $propArr[0]/$propArr[1]) {
                    $this->err[] = '图片比例应该是：' . $arr;
                }
                continue;
            }

            foreach ($arr as $limitType => $item) {
                $limitValue = $item;
                $res = $this->compareValue($value[$this->options[$type]['index_res_getimagesize']], $limitValue, $limitType);
                if (true !== $res)
                    throw new CustomException('图片' . $this->options[$type]['alias'] . $res . $limitValue . '像素！');
            }
        }

        return true;
    }

    /**
     * 检查质量大小
     * @param UploadedFile $uploadedFile
     * @return bool|string
     */
    public function checkQuality(UploadedFile $uploadedFile)
    {
        $limit = $this->scenario['quality'];
        if (empty($limit) || $limit == 'undefined')
            return true;

        $size = $uploadedFile->size;
        $arrs = $limit;
        foreach ($arrs as $type => $limitValue) {
            $compare_res = $this->compareValue($size, $limitValue * 1024, $type);
            if ($compare_res !== true)
                throw new CustomException('图片大小' . $compare_res . $limitValue['v'] . 'Kb');
        }

        return true;
    }

    /**
     * 检查图片类型
     * @param UploadedFile $uploadedFile
     * @return bool|string
     */
    public function checkImageType(UploadedFile $uploadedFile)
    {
        $limit = $this->scenario['type'];
        if (empty($limit) || $limit == 'undefined')
            return true;

        $type = $uploadedFile->type;

        $typeArr = $limit;
        foreach ($typeArr as $item) {
            if (strpos(strtolower($type), strtolower($item)) !== false) {
                return true;
            }
        }

        $this->err[] = '图片支持的格式有:' . $limit;
    }

    /**
     * 大小比较
     * @param $v
     * @param $limit
     * @param $type
     * @return bool|string
     */
    private function compareValue($v, $limit, $type)
    {
        switch ($type) {
            case '=':
                return ($v == $limit) ? true : '必须等于';
                break;
            case '>':
                return ($v > $limit) ? true : '必须大于';
                break;
            case '<':
                return ($v < $limit) ? true : '必须小于';
                break;
        }
    }
}