# 通用基础后台

base_backend是一套前后台完全分离的CMS管理基础程序。后台基于PHP框架 Yii2，前台基于 layui。

实现的基本功能目前有：管理员、登录、角色以及权限功能。

使用它有两种方式：

- 熟悉 Yii2 框架的，可以直接在基础程序的基础上。配置完成后，即可直接编写对应的业务。
- 不喜欢或不熟悉 Yii2 框架的，可以直接通过配置引入外部系统(任意的web服务)。外部系统与 Yii2 后台通过http对接。
前端页面通过规定的接口规范调用固定接口，Yii2 进行将接口转发给外部系统，Yii2 将结果数据返回给前端。前端进行数据渲染。


## 使用步骤

### 修改配置

配置文件位置：
/business/config/

```
修改添加业务模块        config.php的module参数
日志存放路径            config.php的runtimePath参数
数据路连接地址          db.php
外部系统配置地址        params.php的modules参数
```

后台接口的入口文件位于/public/index.php
前台入口在/public/views/start/index.html。也可以将整个view抽出去，跟后台程序分开。

### 创建业务的菜单、以及权限

数据结构，请直接查看 init.sql。

```
-- ----------------------------
-- 接入外部系统的部分
-- ----------------------------
-- 添加主菜单，分类菜单
INSERT INTO `system_menu` VALUES ('4', '业务管理', '/main/main/main', 'business', '', '0', '1', '0', '0', '1510544394', '1510544394');
-- 添加权限
INSERT INTO `system_priv` VALUES ('13', '4', '业务管理', 'main', 'main', 'main', '1510544394', '1510544394');
-- 赋权
INSERT INTO `system_group_priv` VALUES ('13', '1', '13', '1510544394', '1510544394');

-- 添加外部系统调用的菜单
-- 由于Yii2 本身的组织方式/module/controller/action，所以外部系统为了兼容权限控制。只支持三级的 pathinfo 模式。如果外部系统的path只有一级或两级，这里的module和controller默认为 system 即可。
INSERT INTO `system_menu` VALUES ('5', '账号管家配置', '/system/client-config/index', 'gamm3_config', '', '0', '1', '0', '4', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('14', '5', '账号管家配置', 'system', 'client-config', 'index', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('14', '1', '14', '1510544394', '1510544394');

-- ----------------------------
-- 直接连接业务数据库
-- ----------------------------
INSERT INTO `system_menu` VALUES ('6', '用户列表', '/test/user/index', 'user', '', '0', '1', '0', '4', '1510544394', '1510544394');
-- 添加权限
INSERT INTO `system_priv` VALUES ('15', '6', '用户列表', 'test', 'user', 'index', '1510544394', '1510544394');
-- 赋权
INSERT INTO `system_group_priv` VALUES ('15', '1', '15', '1510544394', '1510544394');
```

### 开发后台数据

注意，为了后期基础公用功能的升级，请在business目录的模块里进行开发业务。

上一步，我们分别添加外部系统、直接写业务两种方式的菜单。

- 如果你直接使用本程序的基础上，进行业务开发，直接编写对应权限的接口。
- 如果你是使用外部系统接入。直接在配置文件 params 的modules参数，增加外部系统模块即可。

### 前台调用渲染

接口调用文档，请参考[前台文档](http://192.168.39.61:81/doc/base_backend/views/)

```
对于外部系统的调用，固定接口为```/common/system/index```
参数示例：
module=gamm3    //外部系统模块，与配置文件里的params的modules一致。
api=/client-config/index    //外部系统的接口路由，数据库的菜单要对应上
```


## 前台样式示例

[前台layuiadmin](http://192.168.150.37:8027/layoutui/start/index.html)