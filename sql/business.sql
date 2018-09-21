use backend_2;

SET NAMES utf8;


-- ----------------------------
-- 接入外部系统的部分
-- ----------------------------
-- 添加外部调用测试接口
INSERT INTO `system_menu` VALUES ('5', '测试业务', '/main/main/main', 'business', '', '0', '1', '0', '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_menu` VALUES ('6', '账号管家配置', '/system/client-config/index', 'gamm3_config', '', '0', '1', '0', '5', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
-- 添加权限
INSERT INTO `system_priv` VALUES ('17', '5', '业务管理', 'main', 'main', 'main', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('18', '6', '账号管家配置', 'system', 'client-config', 'index', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
-- 赋权
INSERT INTO `system_group_priv` VALUES ('17', '1', '17', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('18', '1', '18', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- 直接连接业务数据库
-- ----------------------------
INSERT INTO `system_menu` VALUES ('7', '用户列表', '/test/user/index', 'user', '', '0', '1', '0', '5', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
-- 添加权限
INSERT INTO `system_priv` VALUES ('19', '7', '用户列表', 'test', 'user', 'index', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
-- 赋权
INSERT INTO `system_group_priv` VALUES ('19', '1', '19', '2018-09-12 12:09:00', '2018-09-12 12:09:00');