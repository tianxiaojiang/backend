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
use \Backend\modules\common\models\BaseModel;

class LoginWhiteList extends BaseModel
{
    public function scenarios()
    {
        return [
            'default' => ['login_white_list_id', 'account', 'type', 'updated_at', 'created_at'],
            'create' => ['login_white_list_id', 'account', 'type', 'updated_at', 'created_at'],
            'update' => ['login_white_list_id', 'account', 'type', 'updated_at', 'created_at'],
        ];
    }

    public function rules()
    {
        return [
            ['account', 'required', 'message' => '账号不能为空', 'on' => ['create', 'update']],
            ['type', 'required', 'message' => '账号类型不能为空', 'on' => ['create', 'update']],
        ];
    }

    static public function tableName() {
        return 'login_white_list';
    }

    public function fields()
    {
        return ['login_white_list_id', 'account', 'type', 'updated_at', 'created_at'];
    }
}