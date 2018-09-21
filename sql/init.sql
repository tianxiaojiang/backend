use backend_2;
set names utf8;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL DEFAULT '' COMMENT '账号',
  `passwd` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(4) NOT NULL DEFAULT '' COMMENT '加密混淆',
  `mobile_phone` char(16) NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(16) NOT NULL DEFAULT '' COMMENT '用户名称',
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '组id',
  `access_token` CHAR (64) NOT NULL DEFAULT '' COMMENT '访问token',
  `access_token_expire` int (11) NOT NULL DEFAULT 0 COMMENT 'token过期时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '管理员状态 1、正常 2、禁用',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`ad_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'admin', 'dd02677c714da6a5becbfe9a36aeb8c1', 'ufob', '17717563803', 'BigWame1', '1', '', 0, '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `admin_user` VALUES ('2', 'tianweimin', 'dd02677c714da6a5becbfe9a36aeb8c1', 'ufob', '17717563803', 'BigWame1', '1', '', 0, '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for system_group
-- ----------------------------
DROP TABLE IF EXISTS `system_group`;
CREATE TABLE `system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '用户组描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '组名称',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_group
-- ----------------------------
INSERT INTO `system_group` VALUES ('1', '拥有所有权限', '系统管理员', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group` VALUES ('2', '查看权限', '域账号', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `system_group_priv`;
CREATE TABLE `system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应组id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_group_priv
-- ----------------------------
INSERT INTO `system_group_priv` VALUES ('1', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('2', '1', '2', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('3', '1', '3', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('4', '1', '4', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('5', '1', '5', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('6', '1', '6', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('7', '1', '7', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('8', '1', '8', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('9', '1', '9', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('10', '1', '10', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('11', '1', '11', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('12', '1', '12', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('13', '1', '13', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('14', '1', '14', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('15', '1', '15', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_priv` VALUES ('16', '1', '16', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu` (
  `sm_id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_label` char(16) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `sm_request_url` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单地址，路径',
  `sm_view` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单前端对应目录',
  `sm_icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单log',
  `sort_by` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show_sidebar` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否显示',
  `sm_status` int(1) NOT NULL DEFAULT 0 COMMENT '菜单状态',
  `sm_parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父菜单id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES ('1', '系统管理', '/main/main/main', 'system', '', '0', '1', '0', '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_menu` VALUES ('2', '管理员列表', '/admin/admin-user/index', 'admins', '', '0', '1', '0', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_menu` VALUES ('3', '角色列表', '/admin/system-group/index', 'groups', '', '0', '1', '0', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_menu` VALUES ('4', '产品管理', '/admin/game/index', 'groups', '', '0', '1', '0', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for system_priv
-- ----------------------------
DROP TABLE IF EXISTS `system_priv`;
CREATE TABLE `system_priv` (
  `sp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_id` int(11) NOT NULL DEFAULT 0 COMMENT '菜单id',
  `sp_label` varchar(16) NOT NULL DEFAULT '' COMMENT '权限名称',
  `sp_module` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应模块',
  `sp_controller` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应控制器',
  `sp_action` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应方法',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_priv
-- ----------------------------
INSERT INTO `system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('2', '2', '管理员列表', 'admin', 'admin-user', 'index', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('3', '2', '添加管理员', 'admin', 'admin-user', 'create', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('4', '2', '修改管理员', 'admin', 'admin-user', 'update', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('5', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('6', '3', '角色列表', 'admin', 'system-group', 'index', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('7', '3', '添加角色', 'admin', 'system-group', 'create', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('8', '3', '修改角色', 'admin', 'system-group', 'update', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('9', '3', '删除角色', 'admin', 'system-group', 'delete', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('10', '3', '组权限列表', 'admin', 'system-group', 'group-privilege-list', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('11', '3', '组权限修改', 'admin', 'system-group', 'group-privilege-update', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('12', '1', '通用接口调用', 'common', 'system', 'index', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('13', '4', '游戏列表', 'admin', 'game', 'index', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('14', '4', '添加游戏', 'admin', 'game', 'create', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('15', '4', '修改游戏', 'admin', 'game', 'update', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_priv` VALUES ('16', '4', '删除游戏', 'admin', 'game', 'delete', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- Table structure for system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `system_user_group`;
CREATE TABLE `system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_user_group
-- ----------------------------
INSERT INTO `system_user_group` VALUES ('1', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_user_group` VALUES ('2', '1', '2', '2018-09-12 12:09:00', '2018-09-12 12:09:00');


-- ----------------------------
-- game 游戏
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR (32) NOT NULL DEFAULT '' COMMENT '游戏名称，一般用汉字表示',
  `alias` VARCHAR (32) NOT NULL DEFAULT '' COMMENT '游戏别名，一般是英文代号',
  `status` tinyint (1) NOT NULL DEFAULT 0 COMMENT '状态，0启用（默认）， 1下架',
  `order_by` SMALLINT(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `game` VALUES (5012, '测试应用', 'test', 0, 99, '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `game` VALUES (5051, '街头篮球', 'streetBasketball', 0, 99, '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- system_group_game 角色管辖下的游戏
-- ----------------------------
DROP TABLE IF EXISTS `system_group_game`;
CREATE TABLE `system_group_game` (
  `system_group_game_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int (11) NOT NULL COMMENT '游戏id',
  `group_id` int (11) NOT NULL COMMENT '角色id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_group_game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `system_group_game` VALUES (1, 5012, 1, '2018-09-12 12:09:00', '2018-09-12 12:09:00');
INSERT INTO `system_group_game` VALUES (2, 5051, 1, '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- system_group_game_priv 角色下的游戏特殊权限，不设置即没有权限
-- ----------------------------
DROP TABLE IF EXISTS `system_group_game_priv`;
CREATE TABLE `system_group_game_priv` (
  `sggp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int (11) NOT NULL COMMENT '角色id',
  `game_id` int (11) NOT NULL COMMENT '游戏id',
  `priv_id` int (11) NOT NULL COMMENT '权限id',
  `attach_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '附加类型，0额外增加权限，1扣除权限',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sggp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;








