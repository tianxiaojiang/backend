use integration_background;
set names utf8;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `staff_number` int(11) DEFAULT 0 COMMENT '工号',
  `auth_type` tinyint(1) DEFAULT 0 COMMENT '认证类型，0 域账号，1 普通账号，2 常州账号',
  `password_algorithm_system` smallint(2) DEFAULT 1 COMMENT '密码算法对应的系统，默认为最新的1',
  `account` varchar(32) NOT NULL DEFAULT '' COMMENT '账号',
  `passwd` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(4) NOT NULL DEFAULT '' COMMENT '加密混淆',
  `mobile_phone` char(16) NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(16) NOT NULL DEFAULT '' COMMENT '用户名称',
  `access_token` char(64) NOT NULL DEFAULT '' COMMENT '访问token',
  `access_token_expire` int(11) NOT NULL DEFAULT 0 COMMENT 'token过期时间',
  `status` tinyint(1) DEFAULT 0 COMMENT '管理员状态 0、正常 1、禁用',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reset_password` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否重置过密码，0 没有 1 已重置',
  PRIMARY KEY (`ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', '0', '1', '0', 'admin', 'be7411311433923a565a5b322b3f3146', 'nCY5', '17717563803', 'wame', '', '0', '0', '2018-09-12 12:09:00', '2018-11-14 15:00:37', '1');

-- ----------------------------
-- Table structure for game
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `game_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏名称，一般用汉字表示',
  `alias` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏别名，一般是英文代号',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态，0启用（默认）， 1下架',
  `type` tinyint(1) DEFAULT 0 COMMENT '游戏类型，0 端游，1 手游',
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----------------------------
-- Table structure for img
-- ----------------------------
DROP TABLE IF EXISTS `img`;
CREATE TABLE `img` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `url_path` varchar(64) NOT NULL DEFAULT '' COMMENT '图片的目录地址，不包含域名',
  `width` mediumint(8) NOT NULL DEFAULT 0 COMMENT '宽度',
  `height` mediumint(8) NOT NULL DEFAULT 0 COMMENT '宽度',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '图片使用处，1 反馈上传',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '图片使用状态，0 为正常 1 为已弃用',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of img
-- ----------------------------
INSERT INTO `img` VALUES ('1', '/uploads/20181218/1545126681879404.png', '91', '88', '0', '0', '2018-12-18 17:51:21', '2018-12-18 17:51:21');
INSERT INTO `img` VALUES ('2', '/uploads/20181225/1545722514503576.png', '91', '88', '0', '0', '2018-12-25 15:21:54', '2018-12-25 15:21:54');
INSERT INTO `img` VALUES ('3', '/uploads/20181227/1545877177364587.png', '91', '88', '0', '0', '2018-12-27 10:19:37', '2018-12-27 10:19:37');
INSERT INTO `img` VALUES ('4', '/uploads/20181227/1545877543579946.png', '91', '88', '0', '0', '2018-12-27 10:25:43', '2018-12-27 10:25:43');
INSERT INTO `img` VALUES ('5', '/uploads/20181227/1545878135479763.png', '91', '88', '0', '0', '2018-12-27 10:35:35', '2018-12-27 10:35:35');
INSERT INTO `img` VALUES ('6', '/uploads/20181227/1545878318372732.png', '91', '88', '0', '0', '2018-12-27 10:38:38', '2018-12-27 10:38:38');

-- ----------------------------
-- Table structure for s1_system_group
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group`;
CREATE TABLE `s1_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `privilege_level` tinyint(1) NOT NULL DEFAULT 1 COMMENT '后台角色权限级别置位，一位前台权限、二位后台权限',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s1_system_group
-- ----------------------------
INSERT INTO `s1_system_group` VALUES ('1', '拥有所有权限', '系统管理员', '2', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group` VALUES ('2', '哈哈哈哈', '通用角色', '2', '2018-12-27 10:03:52', '2018-12-27 10:07:07');

-- ----------------------------
-- s1_system_group_game 角色对应的游戏
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group_game`;
CREATE TABLE `s1_system_group_game` (
  `sggid` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int (11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `game_id` int (11) NOT NULL DEFAULT 0 COMMENT '游戏id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sggid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s1_system_group_game
-- ----------------------------
INSERT INTO `s1_system_group_game` VALUES ('1', '1', '-1', '2019-01-02 17:28:23', '2019-01-02 17:28:23');

-- ----------------------------
-- Table structure for s1_system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group_priv`;
CREATE TABLE `s1_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s1_system_group_priv
-- ----------------------------
INSERT INTO `s1_system_group_priv` VALUES ('1', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('2', '1', '2', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('3', '1', '3', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('4', '1', '4', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('5', '1', '5', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('6', '1', '6', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('7', '1', '7', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('8', '1', '8', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('9', '1', '9', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('10', '1', '10', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('11', '1', '11', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('12', '1', '12', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('13', '1', '13', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('14', '1', '14', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('15', '1', '15', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('16', '1', '16', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('17', '1', '17', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('18', '1', '18', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('19', '1', '19', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('20', '1', '20', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('21', '1', '21', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('22', '1', '22', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('23', '1', '23', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('24', '1', '24', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('25', '1', '25', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('26', '1', '26', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('27', '1', '27', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('28', '1', '28', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('29', '1', '29', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('30', '1', '30', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('31', '1', '31', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('32', '1', '32', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('33', '1', '33', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('34', '2', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('35', '2', '7', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('36', '2', '8', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('37', '2', '9', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('38', '2', '10', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('39', '2', '11', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('40', '2', '12', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('41', '2', '13', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('42', '2', '14', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('43', '2', '15', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('44', '2', '16', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('47', '2', '24', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('48', '2', '25', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_group_priv` VALUES ('49', '2', '26', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for s1_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_menu`;
CREATE TABLE `s1_system_menu` (
  `sm_id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_label` char(16) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `sm_view` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单前端对应目录',
  `sm_icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单logo',
  `sort_by` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show_sidebar` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否显示',
  `sm_status` int(1) NOT NULL DEFAULT 0 COMMENT '菜单状态',
  `sm_parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父菜单id',
  `sm_set_or_business` tinyint(1) NOT NULL DEFAULT 0 COMMENT '菜单属于系统设置还是业务，默认0：业务，1：系统设置',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s1_system_menu
-- ----------------------------
INSERT INTO `s1_system_menu` VALUES ('1', '系统设置', 'system', '', '0', '1', '0', '0', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_menu` VALUES ('2', '管理员列表', 'admins', '', '0', '1', '0', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_menu` VALUES ('3', '角色列表', 'groups', '', '0', '1', '0', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_menu` VALUES ('4', '游戏列表', 'games', '', '0', '1', '0', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_menu` VALUES ('5', '菜单列表', 'menus', '', '0', '1', '0', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_menu` VALUES ('6', '权限列表', 'privileges', '', '0', '1', '0', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_menu` VALUES ('7', '模块列表', 'systems', '', '0', '1', '0', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for s1_system_priv
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_priv`;
CREATE TABLE `s1_system_priv` (
  `sp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_id` int(11) NOT NULL DEFAULT 0 COMMENT '菜单id',
  `sp_label` varchar(16) NOT NULL DEFAULT '' COMMENT '权限名称',
  `sp_module` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应模块',
  `sp_controller` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应控制器',
  `sp_action` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应方法',
  `sp_parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '权限父id',
  `sp_set_or_business` tinyint(1) NOT NULL DEFAULT 0 COMMENT '权限属于系统设置还是业务，默认0：业务，1：系统设置',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s1_system_priv
-- ----------------------------
INSERT INTO `s1_system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '0', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('2', '2', '管理员维护', 'main', 'main', 'main', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('3', '2', '管理员列表', 'admin', 'admin-user', 'index', '2', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('4', '2', '添加管理员', 'admin', 'admin-user', 'create', '2', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('5', '2', '修改管理员', 'admin', 'admin-user', 'update', '2', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('6', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('7', '3', '角色维护', 'main', 'main', 'main', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('8', '3', '角色列表', 'admin', 'system-group', 'index', '7', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('9', '3', '添加角色', 'admin', 'system-group', 'create', '7', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('10', '3', '修改角色', 'admin', 'system-group', 'update', '7', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('11', '3', '删除角色', 'admin', 'system-group', 'delete', '7', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('12', '3', '角色权限列表', 'admin', 'system-group', 'group-privilege-list', '7', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('13', '3', '角色权限修改', 'admin', 'system-group', 'group-privilege-update', '7', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('14', '4', '游戏维护', 'main', 'main', 'main', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('15', '4', '游戏列表', 'admin', 'game', 'index', '14', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('16', '4', '添加游戏', 'admin', 'game', 'create', '14', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('17', '4', '修改游戏', 'admin', 'game', 'update', '14', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('18', '4', '删除游戏', 'admin', 'game', 'delete', '14', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('19', '5', '菜单维护', 'main', 'main', 'main', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('20', '5', '菜单列表', 'admin', 'menu', 'index', '19', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('21', '5', '添加菜单', 'admin', 'menu', 'create', '19', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('22', '5', '修改菜单', 'admin', 'menu', 'update', '19', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('23', '5', '删除菜单', 'admin', 'menu', 'delete', '19', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('24', '6', '权限维护', 'main', 'main', 'main', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('25', '6', '权限列表', 'admin', 'privilege', 'index', '24', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('26', '6', '添加权限', 'admin', 'privilege', 'create', '24', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('27', '6', '修改权限', 'admin', 'privilege', 'update', '24', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('28', '6', '删除权限', 'admin', 'privilege', 'delete', '24', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('29', '7', '模块维护', 'main', 'main', 'main', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('30', '7', '模块列表', 'admin', 'system', 'index', '29', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('31', '7', '添加模块', 'admin', 'system', 'create', '29', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('32', '7', '修改模块', 'admin', 'system', 'update', '29', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `s1_system_priv` VALUES ('33', '7', '删除模块', 'admin', 'system', 'delete', '29', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for s1_system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_user_group`;
CREATE TABLE `s1_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s1_system_user_group
-- ----------------------------
INSERT INTO `s1_system_user_group` VALUES ('1', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for system_admin
-- ----------------------------
DROP TABLE IF EXISTS `system_admin`;
CREATE TABLE `system_admin` (
  `sa_id` int(11) NOT NULL AUTO_INCREMENT,
  `systems_id` int(11) NOT NULL DEFAULT 0 COMMENT '系统id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `token_id` char(32) NOT NULL DEFAULT '' COMMENT '登录的有效token_id',
  `setting_token_id` char(32) NOT NULL DEFAULT '' COMMENT '登录管理后台的有效token_id',
  PRIMARY KEY (`sa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_admin
-- ----------------------------
INSERT INTO `system_admin` VALUES ('1', '1', '1', '2018-11-12 11:57:03', '2019-01-02 10:30:07', '', '021fcc105c0a793c00dad9c84327df09');

-- ----------------------------
-- Table structure for systems
-- ----------------------------
DROP TABLE IF EXISTS `systems`;
CREATE TABLE `systems` (
  `systems_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL COMMENT '后台名称',
  `img_id` int(11) NOT NULL DEFAULT 0 COMMENT '图标id',
  `url` char(128) NOT NULL DEFAULT '' COMMENT '链接地址',
  `dev_account` int(11) DEFAULT 0 COMMENT '系统开发者账号',
  `description` varchar(128) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0、正常；1、关闭中',
  `game_type` tinyint(1) DEFAULT 0 COMMENT '游戏类型，0 端游，1 手游, 2 不区分',
  `auth_url` char(64) DEFAULT NULL COMMENT '系统授权跳转地址',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`systems_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of systems
-- ----------------------------
INSERT INTO `systems` VALUES ('1', '中心后台', '0', 'https://unify-admin.sdk.mobileztgame.com', '1', '管理所有接入后台的角色、权限、菜单、账号等', '0', '0', '/views/start/index.html', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
