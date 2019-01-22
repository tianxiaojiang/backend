# 通用后台客户端

此分支是通用后台的业务客户端。包含了统一登录和权限分配。

## 安装

### 申请后台项目

向统一后台管理人员(目前是田卫民)申请新的后台。
申请时，提供项目的资料有。

- 项目名称
- 项目的URL
- 项目开发人员域账号
- 项目开发人员工号
- 简要描述
- icon(目前尺寸为91*88px)

### 部署代码

将此代码下载后，部署到一个web下。
nginx配置可参考根目录下的示例文件： nginx_server.conf

### 安装composer类包

在项目根目录下执行：
```
composer install
```

### 配置数据库

示例的数据示例sql文件：位于 sql 目录下的 init.sql

修改 /business/config/db.php 或 db-local.php 配置为自己的数据库。

### 登录

统一登录后台地址：

https://unify-admin.sdk.mobileztgame.com

使用开发人员自己的域账号登录

### 进入维护后台

1. 鼠标移到右上角的头像，弹出下拉框。
2. 然后点击维护后台，进入维护总页面。
3. 再点击相应要维护的项目，即可进入维护后台。

目前维护后台的操作有：
- 维护角色
- 维护菜单
- 维护权限
- 维护角色
- 维护管理员

整体全体使用rbac权限。整体开发流程

一、添加菜单
在菜单管理页面，根据需要添加。前端对应目录，跟前端html地址保持一致。

二、添加权限
权限要附加在某个菜单上，菜单也要有对应的权限。
如果权限为菜单的权限，对应的module、controller、action均填写为 main 即可。

三、添加对应的角色
角色管理中，添加一个角色。

四、给角色赋权
角色管理中，点击权限。
如果需要区分游戏，在权限管理下的游戏管理面框中，选择区分游戏，然后选中要管理的游戏。

### 管理业务

在登录页页面，点击对应的项目即进入对应的项目后台。
(如果当前在维护页，需要点击右上角头像的下来菜单中的业务后台，即可返回到登录页)。

现有的默认demo操作有两个

- 菜单组示例
    - 菜单示例1
    - 菜单示例2

菜单示例1的一组接口为：/index/news1/index
菜单示例2的一组接口为：/index/news2/index

后台功能代码都在目录 /business 下，方便后期功能升级。

前端代码在目录 /public/views/src/views/ 下。


### 查询某些接口是否有权限

此方法可以用来，根据权限判断是否要显示某些按钮元素。

代码示例：

```
var actions = ['/index/news1/index', '/index/news2/index', '/index/vvv/jjj'];
ztutil.getHasPrivilege(actions, function (res) {
    console.log(res);
    //根据结果处理后续页面显示
});

返回的内容是对应的对象: {"/index/news1/index": true, "/index/news2/index": true, "/index/vvv/jjj": false}

```