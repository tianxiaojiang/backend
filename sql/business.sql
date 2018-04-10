use backend;

SET NAMES utf8;


-- ----------------------------
-- 接入外部系统的部分
-- ----------------------------
-- 添加外部调用测试接口
INSERT INTO `system_menu` VALUES ('4', '业务管理', '/main/main/main', 'business', '', '0', '1', '0', '0', '1510544394', '1510544394');
INSERT INTO `system_menu` VALUES ('5', '账号管家配置', '/system/client-config/index', 'gamm3_config', '', '0', '1', '0', '4', '1510544394', '1510544394');
-- 添加权限
INSERT INTO `system_priv` VALUES ('13', '4', '业务管理', 'main', 'main', 'main', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('14', '5', '账号管家配置', 'system', 'client-config', 'index', '1510544394', '1510544394');
-- 赋权
INSERT INTO `system_group_priv` VALUES ('13', '1', '13', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('14', '1', '14', '1510544394', '1510544394');

-- ----------------------------
-- 直接连接业务数据库
-- ----------------------------
INSERT INTO `system_menu` VALUES ('6', '用户列表', '/test/user/index', 'user', '', '0', '1', '0', '4', '1510544394', '1510544394');
-- 添加权限
INSERT INTO `system_priv` VALUES ('15', '6', '用户列表', 'test', 'user', 'index', '1510544394', '1510544394');
-- 赋权
INSERT INTO `system_group_priv` VALUES ('15', '1', '15', '1510544394', '1510544394');