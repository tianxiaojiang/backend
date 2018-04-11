<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/4/11
 * Time: 9:59
 */

namespace Backend\helpers;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelSimple
{

    /**
     * 导出为xls格式表格
     * @param $file 保存的文件
     * @param $title 表格的标题
     * @param $dataList 数据列表，三维数据，第一维表示表格，第二维表示行，第三维表示列
     * @param array $sheets 表格对应标题，与数据库列表的第一位对应
     */
    public static function exportXls($file, $title, $dataList, $sheets = ['表格1'])
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator(\Yii::$app->user->identity->username)
            ->setLastModifiedBy(\Yii::$app->user->identity->username)
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription($title)
            ->setKeywords($title)
            ->setCategory($title);

        foreach ($dataList as $sheetIndex => $sheetValue) {
            $spreadsheet->setActiveSheetIndex($sheetIndex);
            $spreadsheet->getActiveSheet()->setTitle($sheets[$sheetIndex]);
            $j=1;
            foreach ($sheetValue as $line) {
                $c = '0';
                foreach ($line as $col) {
                    $c = static::getColIndex($c);
                    $spreadsheet->setCellValue($c . $j, $col);
                }
                ++$j;
            }
        }

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Simple');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $file .'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public static function loadDataFromSheet()
    {
        
    }
    
    private static function getColIndex($c)
    {
        if($c === '0') return 'A';
        $length = strlen($c);
        $res = str_split($c, 1);
        for ($i = $length; $i >= 1; $i--) {
            $char = substr($c, $i - 1, 1);
            $nextChar = self::getColNextIndex($char);
            $res[$i - 1] = $nextChar;
            if ($nextChar != 'A') {
                break;
            } elseif($i == 1) {//增加进位
                array_unshift($res, 'A');
            }
        }

        return implode('', $res);
    }

    private static function getColNextIndex($colItem)
    {
        $colIndexLib = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        //先判断下一位
        $key = array_search($colItem, $colIndexLib);
        if (isset($colIndexLib[$key + 1])) {
            return $colIndexLib[$key + 1];
        } else {
            //进位
            return $colIndexLib[0];
        }
    }
}