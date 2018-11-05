<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
use Backend\modules\admin\services\ImportSystemSqlService;
use Backend\modules\admin\services\SystemService;
use \Backend\modules\common\models\BaseModel;

class System extends BaseModel
{
    const SYSTEM_STAT_NORMAL = 0;//启用中
    const SYSTEM_STAT_FORBIDDEN = 1;//已禁用

    public static $_status = [
        self::SYSTEM_STAT_NORMAL => '正常',
        self::SYSTEM_STAT_FORBIDDEN => '禁用中',
    ];

    public function scenarios()
    {
        return [
            'default' => ['systems_id', 'name', 'url', 'status', 'description', 'updated_at', 'created_at'],
            'update' => ['systems_id', 'name', 'url', 'status', 'description', 'updated_at', 'created_at'],
            'create' => ['systems_id', 'name', 'url', 'status', 'description', 'updated_at', 'created_at'],
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => '系统名不能为空', 'on' => ['create', 'update']],
            ['url', 'required', 'message' => '系统url不能为空', 'on' => ['create', 'update']],
        ];
    }

    static public function tableName() {
        return 'systems';
    }

    public function fields()
    {
        return ['systems_id', 'name', 'url', 'status', 'description', 'updated_at', 'created_at'];
    }

    public function getStatusName()
    {
        return self::$_status[$this->status];
    }

    //禁用系统
    public function forbiddenSystem()
    {
        if ($this->status === self::SYSTEM_STAT_FORBIDDEN)
            throw new CustomException('系统已经是禁用状态!');

        $this->status = self::SYSTEM_STAT_FORBIDDEN;
        $this->save();
        return true;
    }

    public function insert($runValidation = true, $attributes = null) {

        $systems_id = parent::insert($runValidation, $attributes);

        if (!$systems_id)
            throw new CustomException('数据库操作添加系统失败');

        //生成系统的数据库表格
        ImportSystemSqlService::importSystemSql($this->systems_id);

        return true;
    }
}