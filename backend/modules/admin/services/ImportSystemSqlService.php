<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/11/5
 * Time: 11:16
 */

namespace Backend\modules\admin\services;


/**
 * 系统业务处理
 * Class SystemService
 * @package Backend\modules\admin\services
 */
class ImportSystemSqlService
{
    public static $systemId = 0;

    public static function importSystemSql($systemId) {
        self::$systemId = $systemId;
        \Yii::info('SYstem generate id:' . self::$systemId);
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            self::importTable($db);
            self::importInsert($db);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        $transaction->commit();

        return true;
    }

    public static function importTable($db)
    {
        $sqlDir = \Yii::$app->params['sql_file_dir'];
        $tableDir = $sqlDir . 'table' . DIRECTORY_SEPARATOR;

        if ($dir = opendir($tableDir)) {
            while (false !== ($sqlFile = readdir($dir))) {
                \Yii::info('table file:' . var_export($sqlFile, true));
                $tableSqlFile = $tableDir . $sqlFile;
                $createSql = file_get_contents($tableSqlFile);
                $sqlReplaceedPlaceholder = str_replace('{{SID}}', self::$systemId, $createSql);
                \Yii::info('table replaced Placeholder sql:' . var_export($sqlReplaceedPlaceholder, true));
                $db->createCommand($sqlReplaceedPlaceholder)->execute();
            }

            closedir($dir);
        }
        return true;
    }

    public static function importInsert($db)
    {
        $sqlDir = \Yii::$app->params['sql_file_dir'];
        $insertDir = $sqlDir . 'insert' . DIRECTORY_SEPARATOR;

        if ($dir = opendir($insertDir)) {
            while (false !== ($sqlFile = readdir($dir))) {
                \Yii::info('insert file:' . var_export($sqlFile, true));
                $tableSqlFile = $insertDir . $sqlFile;
                $createSql = file_get_contents($tableSqlFile);
                $sqlReplaceedPlaceholder = str_replace('{{SID}}', self::$systemId, $createSql);
                \Yii::info('insert replaced Placeholder sql:' . var_export($sqlReplaceedPlaceholder, true));
                $db->createCommand($sqlReplaceedPlaceholder)->execute();
            }

            closedir($dir);
        }
        return true;
    }

}