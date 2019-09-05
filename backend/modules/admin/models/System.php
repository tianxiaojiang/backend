<?php
/**
 * Created by PhpStorm.
 * User: tianweimin
 * Date: 2018/3/21
 * Time: 19:55
 */

namespace Backend\modules\admin\models;

use Backend\Exception\CustomException;
use Backend\helpers\Helpers;
use Backend\modules\admin\services\ImportSystemSqlService;
use Backend\modules\admin\services\SystemAdminService;
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

    public $auth_type;
    public $developer_password;
    public $staff_number;

    public function scenarios()
    {
        return [
            'default' => ['systems_id', 'allow_api_call', 'name', 'img_id', 'active_img_id', 'url', 'auth_url', 'dev_account', 'game_type', 'status', 'description', 'updated_at', 'created_at'],
            'update' => ['systems_id', 'allow_api_call', 'developer_password', 'auth_type', 'staff_number', 'name', 'img_id', 'active_img_id', 'url', 'auth_url', 'dev_account', 'game_type', 'status', 'description', 'updated_at', 'created_at'],
            'create' => ['systems_id', 'allow_api_call', 'developer_password', 'auth_type', 'staff_number', 'name', 'img_id', 'active_img_id', 'url', 'auth_url', 'dev_account', 'game_type', 'status', 'description', 'updated_at', 'created_at'],
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => '系统名不能为空', 'on' => ['create', 'update']],
            ['url', 'required', 'message' => '系统url不能为空', 'on' => ['create', 'update']],
            ['game_type', 'required', 'message' => '游戏类型必选', 'on' => ['create', 'update']],
            ['developer_password', 'validateDeveloper', 'on' => ['create', 'update']],
        ];
    }

    static public function tableName() {
        return 'systems';
    }

    public function fields()
    {
        return ['systems_id', 'name', 'url', 'dev_account', 'status', 'description', 'updated_at', 'created_at'];
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

    public function validateDeveloper()
    {
        if ($this->hasErrors())
            return false;

        if ($this->auth_type == Admin::AUTH_TYPE_PASSWORD && empty($this->developer_password)) {
            $this->addError('developer_password', '普通账户请设置密码');
        }

        if (in_array($this->auth_type, [Admin::AUTH_TYPE_DOMAIN, Admin::AUTH_TYPE_CHANGZHOU]) && empty($this->staff_number)) {
            $this->addError('developer_password', '员工账户必须设置员工工号');
        }
    }

    public function insert($runValidation = true, $attributes = null) {

        //系统数据入库
        $res = parent::insert($runValidation, $attributes);
        if (!$res)
            throw new CustomException('数据库操作添加系统失败');

        $systems_id = $this->systems_id;

        //生成管理员账号
        $auth_type = intval($this->auth_type);
        if ($this->auth_type == Admin::AUTH_TYPE_PASSWORD) {
            $adminUser = Admin::findOne(['account' => $this->dev_account, 'auth_type' => $auth_type]);
        } else {
            $adminUser = Admin::findOne(['staff_number' => $this->staff_number, 'auth_type' => $auth_type]);
        }
        if (empty($adminUser)) {
            $adminUser = new Admin();
            $adminUser->system_id = $systems_id;
            $adminUser->auth_type = $auth_type;
            if ($this->auth_type == Admin::AUTH_TYPE_PASSWORD) {
                $adminUser->account = strval($this->dev_account);
                $adminUser->username = strval($this->dev_account);
                $adminUser->password = strval($this->developer_password);
            }
            $adminUser->staff_number = $this->staff_number;

            $adminUser->save();
        }

        //添加用户系统关系
        //SystemAdminService::addSystemAdmin($adminUser->ad_uid, $systems_id);

        $this->dev_account = $adminUser->ad_uid;
        $this->save();


        //生成系统的数据库表格
        ImportSystemSqlService::importSystemSql($this, $adminUser->ad_uid);

        return true;
    }

    public function update($runValidation = true, $attributes = null) {

        //生成管理员账号
        $auth_type = intval($this->auth_type);

        if ($this->auth_type == Admin::AUTH_TYPE_PASSWORD) {
            $adminUser = Admin::findOne(['account' => $this->dev_account, 'auth_type' => $auth_type]);
        } else {
            $adminUser = Admin::findOne(['staff_number' => $this->staff_number, 'auth_type' => $auth_type]);
        }
        if (empty($adminUser)) {
            $adminUser = new Admin();
            $adminUser->system_id = $this->systems_id;
            $adminUser->auth_type = $auth_type;
            if ($this->auth_type == Admin::AUTH_TYPE_PASSWORD) {
                $adminUser->account = strval($this->dev_account);
                $adminUser->username = strval($this->dev_account);
                $adminUser->password = strval($this->developer_password);
            }
            $adminUser->staff_number = $this->staff_number;

            $adminUser->save();
        }

        $this->dev_account = $adminUser->ad_uid;

        //校验是否更换管理员
        $oldAdminId = $this->getOldAttribute("dev_account");
        if (intval($oldAdminId) != $adminUser->ad_uid) {
            //替换了管理员，则把对应系统的角色id替换掉
            Helpers::$request_params["sid"] = $this->systems_id;
            SystemAdmin::updateAll(["ad_uid" => $adminUser->ad_uid], ["ad_uid" => $oldAdminId]);
            //改回sid
            Helpers::$request_params["sid"] = 1;
        }

        //系统数据入库
        $res = parent::update($runValidation, $attributes);
        if (!$res)
            throw new CustomException('数据库操作更新系统失败');

        return true;
    }

    /**
     * 对应图标
     * @return \yii\db\ActiveQuery
     */
    public function getImg()
    {
        return $this->hasOne(Img::class, ['img_id' => 'img_id']);
    }

    /**
     * 对应图标
     * @return \yii\db\ActiveQuery
     */
    public function getActiveImg()
    {
        return $this->hasOne(Img::class, ['img_id' => 'active_img_id']);
    }



    public function getAdmin()
    {
        return $this->hasOne(Admin::class, ['ad_uid' => 'dev_account']);
    }
}