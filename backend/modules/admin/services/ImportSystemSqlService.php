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
    public static $accountId = 0;

    public static function importSystemSql($system, $accountId) {
        self::$systemId = $system->systems_id;
        self::$accountId = $accountId;
        \Yii::info('System generate id:' . self::$systemId);
        \Yii::info('System admin id:' . self::$accountId);
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            self::importTable($db);
            self::importInsert($db);
            if ($system->game_type != 2) {//如果系统区分游戏，则自动增加系统的游戏管理权限
                self::importSystemGamePrivilege($db);
            }
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
                $sqlReplaceedPlaceholder = str_replace('{{ACCOUNT_ID}}', self::$accountId, $sqlReplaceedPlaceholder);
                $sqlReplaceedPlaceholder = str_replace('{{CREATED_AT}}', date('Y-m-d H:i:s'), $sqlReplaceedPlaceholder);
                $sqlReplaceedPlaceholder = str_replace('{{UPDATED_AT}}', date('Y-m-d H:i:s'), $sqlReplaceedPlaceholder);
                \Yii::info('insert replaced Placeholder sql:' . var_export($sqlReplaceedPlaceholder, true));
                $db->createCommand($sqlReplaceedPlaceholder)->execute();
            }

            closedir($dir);
        }
        return true;
    }

    public static function importSystemGamePrivilege($db)
    {
        $sqlDir = \Yii::$app->params['sql_file_dir'];
        $insertDir = $sqlDir . 'setGame' . DIRECTORY_SEPARATOR;

        if ($dir = opendir($insertDir)) {
            while (false !== ($sqlFile = readdir($dir))) {
                \Yii::info('insert file:' . var_export($sqlFile, true));
                $tableSqlFile = $insertDir . $sqlFile;
                $createSql = file_get_contents($tableSqlFile);
                $sqlReplaceedPlaceholder = str_replace('{{SID}}', self::$systemId, $createSql);
                $sqlReplaceedPlaceholder = str_replace('{{ACCOUNT_ID}}', self::$accountId, $sqlReplaceedPlaceholder);
                $sqlReplaceedPlaceholder = str_replace('{{CREATED_AT}}', date('Y-m-d H:i:s'), $sqlReplaceedPlaceholder);
                $sqlReplaceedPlaceholder = str_replace('{{UPDATED_AT}}', date('Y-m-d H:i:s'), $sqlReplaceedPlaceholder);
                \Yii::info('insert replaced Placeholder sql:' . var_export($sqlReplaceedPlaceholder, true));
                $db->createCommand($sqlReplaceedPlaceholder)->execute();
            }

            closedir($dir);
        }
        return true;
    }

}