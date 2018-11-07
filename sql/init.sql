use integration_background;
set names utf8;

-- ----------------------------
-- systems 后台系统
-- ----------------------------
DROP TABLE IF EXISTS `systems`;
CREATE TABLE `systems` (
  `systems_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char (20) NOT NULL COMMENT '后台名称',
  `icon` char (64) NOT NULL DEFAULT '' COMMENT '图标',
  `url` char (128) NOT NULL DEFAULT '' COMMENT '链接地址',
  `dev_account` varchar (32) NOT NULL DEFAULT '' COMMENT '系统开发者账号',
  `description` varchar (128) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint (1) NOT NULL DEFAULT 0 COMMENT '状态 0、正常；1、关闭中',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`systems_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `systems` values (1, '中心后台', '', 'http://integration.background.com', 'admin', '管理所有接入后台的角色、权限、菜单、账号等', '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');


-- ----------------------------
-- game 游戏
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `game_id` int(11) NOT NULL,
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
  `access_token` CHAR (64) NOT NULL DEFAULT '' COMMENT '访问token',
  `access_token_expire` int (11) NOT NULL DEFAULT 0 COMMENT 'token过期时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '管理员状态 1、正常 2、禁用',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'admin', 'dd02677c714da6a5becbfe9a36aeb8c1', 'ufob', '17717563803', 'wame', '', 0, '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');


-- ----------------------------
-- Table structure for system_admin
-- ----------------------------
DROP TABLE IF EXISTS `system_admin`;
CREATE TABLE `system_admin` (
  `sa_id` int(11) NOT NULL AUTO_INCREMENT,
  `systems_id` int (11) NOT NULL DEFAULT 0 COMMENT '系统id',
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `system_admin` VALUES ('1', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');


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
  `sm_set_or_business` tinyint (1) NOT NULL DEFAULT 0 COMMENT '菜单属于系统设置还是业务，默认0：业务，1：系统设置',
  `created_at` datetime,
  `updated_at` datetime,
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
  `sp_parent_id` int (11) NOT NULL DEFAULT 0 COMMENT '权限父id',
  `sp_set_or_business` tinyint (1) NOT NULL DEFAULT 0 COMMENT '权限属于系统设置还是业务，默认0：业务，1：系统设置',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_priv
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
-- Table structure for system_group
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group`;
CREATE TABLE `s1_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `sg_limit_game` int (1) NOT NULL DEFAULT 0 COMMENT '是否限制游戏，0：不限制，1：限制',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_group
-- ----------------------------
INSERT INTO `s1_system_group` VALUES ('1', '拥有所有权限', '系统管理员', '0', '2018-09-12 12:09:00', '2018-09-12 12:09:00');


-- ----------------------------
-- Table structure for system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group_priv`;
CREATE TABLE `s1_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_group_priv
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


-- ----------------------------
-- Table structure for system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_user_group`;
CREATE TABLE `s1_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_user_group
-- ----------------------------
INSERT INTO `s1_system_user_group` VALUES ('1', '1', '1', '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- system_group_game 角色管辖下的游戏
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group_game`;
CREATE TABLE `s1_system_group_game` (
  `system_group_game_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` char (8) NOT NULL DEFAULT '*' COMMENT '游戏id，*表示角色为不限制游戏',
  `group_id` int (11) NOT NULL COMMENT '角色id',
  `is_proprietary_priv` tinyint (1) NOT NULL DEFAULT 0 COMMENT '是否是专有权限，0非，1是',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_group_game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `s1_system_group_game` value (1, '*', 1, 0, '2018-09-12 12:09:00', '2018-09-12 12:09:00');

-- ----------------------------
-- system_group_game_priv 角色下的游戏特殊权限，不设置即没有权限
-- ----------------------------
DROP TABLE IF EXISTS `s1_system_group_game_priv`;
CREATE TABLE `s1_system_group_game_priv` (
  `sggp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int (11) NOT NULL COMMENT '角色id',
  `game_id` int (11) NOT NULL COMMENT '游戏id',
  `priv_id` int (11) NOT NULL COMMENT '权限id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sggp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;








