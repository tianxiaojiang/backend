# 通用后台客户端
 
 此分支是通用后台的业务客户端。使用步骤如下：
 
 ### 部署代码
 
 将此代码下载后，部署到一个web下。
 
 nginx配置可参考示例文件： nginx_server.conf。
 
 
 ### 配置数据库
 
 初始化示例数据示例sql文件：位于 sql 目录下的 init.sql
 
 修改 /business/config/db.php 或 db-local.php 配置为自己的数据库。
 
 ### 登录
 
 统一登录后台地址：
 
 http://integration.background.com/views/start/index.html?#/user/login
 
 使用分配的账号登录。
 
 然后点击相应的系统即可授权跳转到系统页面。
 
 