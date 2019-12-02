use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for s14_game
-- ----------------------------
DROP TABLE IF EXISTS `s12_game`;
CREATE TABLE `s12_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_game
-- ----------------------------
INSERT INTO `s12_game` VALUES ('5012', '0', '2019-11-19 17:38:20', '2019-11-19 17:38:20');
INSERT INTO `s12_game` VALUES ('5201', '0', '2019-11-19 17:42:49', '2019-11-19 17:42:49');

-- ----------------------------
-- Table structure for s14_system_group
-- ----------------------------
DROP TABLE IF EXISTS `s12_system_group`;
CREATE TABLE `s12_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `privilege_level` tinyint(1) NOT NULL DEFAULT 2 COMMENT '后台角色权限级别置位，一位前台权限、二位后台权限',
  `kind` tinyint(1) NOT NULL DEFAULT 0 COMMENT '角色类型，两张类型不能共存 0 普通角色，1 专有角色',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_system_group
-- ----------------------------
INSERT INTO `s12_system_group` VALUES ('1', '-1', '系统管理员', '3', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group` VALUES ('2', '游戏开发者', '激斗火柴人-集成', '1', '0', '2019-11-19 17:37:16', '2019-12-02 16:42:43');
INSERT INTO `s12_system_group` VALUES ('3', '查看反外挂数据', '激斗火柴人-数据', '0', '0', '2019-12-02 16:43:10', '2019-12-02 16:43:15');

-- ----------------------------
-- Table structure for s14_system_group_game
-- ----------------------------
DROP TABLE IF EXISTS `s12_system_group_game`;
CREATE TABLE `s12_system_group_game` (
  `sggid` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `game_id` int(11) NOT NULL DEFAULT 0 COMMENT '游戏id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sggid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_system_group_game
-- ----------------------------
INSERT INTO `s12_system_group_game` VALUES ('1', '1', '-1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_game` VALUES ('3', '1', '5012', '2019-11-19 18:06:58', '2019-11-19 18:06:58');
INSERT INTO `s12_system_group_game` VALUES ('4', '1', '5201', '2019-11-19 18:06:58', '2019-11-19 18:06:58');
INSERT INTO `s12_system_group_game` VALUES ('5', '2', '5012', '2019-11-19 18:14:44', '2019-11-19 18:14:44');
INSERT INTO `s12_system_group_game` VALUES ('6', '2', '5201', '2019-11-19 18:14:44', '2019-11-19 18:14:44');
INSERT INTO `s12_system_group_game` VALUES ('8', '3', '5012', '2019-12-02 16:43:15', '2019-12-02 16:43:15');
INSERT INTO `s12_system_group_game` VALUES ('9', '3', '5201', '2019-12-02 16:43:15', '2019-12-02 16:43:15');

-- ----------------------------
-- Table structure for s14_system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s12_system_group_priv`;
CREATE TABLE `s12_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_system_group_priv
-- ----------------------------
INSERT INTO `s12_system_group_priv` VALUES ('1', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('2', '1', '2', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('3', '1', '3', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('4', '1', '4', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('5', '1', '5', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('6', '1', '6', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('7', '1', '7', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('8', '1', '8', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('9', '1', '9', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('10', '1', '10', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('11', '1', '11', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('12', '1', '12', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('13', '1', '13', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('14', '1', '14', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('15', '1', '15', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('16', '1', '16', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('17', '1', '17', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('18', '1', '18', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('19', '1', '19', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('20', '1', '20', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('21', '1', '21', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('22', '1', '22', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('23', '1', '23', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('24', '1', '24', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('25', '1', '25', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('26', '1', '26', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('27', '1', '27', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('28', '1', '28', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('29', '1', '29', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('30', '1', '30', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('31', '1', '31', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('32', '1', '32', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('33', '1', '33', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('34', '1', '34', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('35', '1', '35', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('36', '1', '36', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('37', '1', '37', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_group_priv` VALUES ('38', '1', '38', '2019-11-19 17:47:43', '2019-11-19 17:47:43');
INSERT INTO `s12_system_group_priv` VALUES ('39', '2', '38', '2019-11-19 17:47:57', '2019-11-19 17:47:57');
INSERT INTO `s12_system_group_priv` VALUES ('40', '2', '39', '2019-11-19 21:12:05', '2019-11-19 21:12:05');

-- ----------------------------
-- Table structure for s14_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `s12_system_menu`;
CREATE TABLE `s12_system_menu` (
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_system_menu
-- ----------------------------
INSERT INTO `s12_system_menu` VALUES ('1', '系统设置', 'system', '', '0', '1', '0', '0', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('2', '管理员维护', 'admins', '', '0', '1', '0', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('3', '角色列表', 'groups', '', '0', '1', '0', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('4', '菜单列表', 'menus', '', '0', '1', '0', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('5', '权限列表', 'privileges', '', '0', '1', '0', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('6', '菜单组示例', 'index', '', '0', '1', '0', '0', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('7', '菜单示例1', 'news1', '', '0', '1', '0', '6', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('8', '菜单示例2', 'news2', '', '0', '1', '0', '6', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('9', '游戏列表', 'system-game', '', '0', '1', '0', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_menu` VALUES ('11', '加固配置', 'pack', '', '6', '1', '0', '0', '0', '2019-11-19 17:49:51', '2019-11-19 21:05:30');
INSERT INTO `s12_system_menu` VALUES ('12', '游戏设置', 'game_config', '', '0', '1', '0', '11', '0', '2019-11-19 21:05:44', '2019-11-19 21:05:44');

-- ----------------------------
-- Table structure for s14_system_priv
-- ----------------------------
DROP TABLE IF EXISTS `s12_system_priv`;
CREATE TABLE `s12_system_priv` (
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_system_priv
-- ----------------------------
INSERT INTO `s12_system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '0', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('2', '2', '管理员维护', 'main', 'main', 'main', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('3', '2', '管理员列表', 'admin', 'admin-user', 'index', '2', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('4', '2', '添加管理员', 'admin', 'admin-user', 'create', '2', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('5', '2', '修改管理员', 'admin', 'admin-user', 'update', '2', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('6', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('7', '3', '角色维护', 'main', 'main', 'main', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('8', '3', '角色列表', 'admin', 'system-group', 'index', '7', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('9', '3', '添加角色', 'admin', 'system-group', 'create', '7', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('10', '3', '修改角色', 'admin', 'system-group', 'update', '7', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('11', '3', '删除角色', 'admin', 'system-group', 'delete', '7', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('12', '3', '角色权限列表', 'admin', 'system-group', 'group-privilege-list', '7', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('13', '3', '角色权限修改', 'admin', 'system-group', 'group-privilege-update', '7', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('14', '4', '菜单维护', 'main', 'main', 'main', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('15', '4', '菜单列表', 'admin', 'menu', 'index', '14', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('16', '4', '添加菜单', 'admin', 'menu', 'create', '14', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('17', '4', '修改菜单', 'admin', 'menu', 'update', '14', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('18', '4', '删除菜单', 'admin', 'menu', 'delete', '14', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('19', '5', '权限维护', 'main', 'main', 'main', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('20', '5', '权限列表', 'admin', 'privilege', 'index', '19', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('21', '5', '添加权限', 'admin', 'privilege', 'create', '19', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('22', '5', '修改权限', 'admin', 'privilege', 'update', '19', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('23', '5', '删除权限', 'admin', 'privilege', 'delete', '19', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('24', '6', '菜单组示例', 'main', 'main', 'main', '0', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('25', '7', '菜单示例', 'main', 'main', 'main', '24', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('26', '7', '菜单示例1列表', 'index', 'news1', 'index', '25', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('27', '7', '添加菜单示例1', 'index', 'news1', 'create', '25', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('28', '7', '修改菜单示例1', 'index', 'news1', 'update', '25', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('29', '7', '删除菜单示例1', 'index', 'news1', 'delete', '25', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('30', '8', '菜单示例2', 'main', 'main', 'main', '24', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('31', '8', '菜单示例2列表', 'index', 'news2', 'index', '30', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('32', '8', '添加菜单示例2', 'index', 'news2', 'create', '30', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('33', '8', '修改菜单示例2', 'index', 'news2', 'update', '30', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('34', '8', '删除菜单示例2', 'index', 'news2', 'delete', '30', '0', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('35', '9', '游戏列表', 'main', 'main', 'main', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('36', '9', '游戏列表', 'admin', 'system-game', 'index', '35', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('37', '9', '设置游戏', 'admin', 'system-game', 'create', '35', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_priv` VALUES ('38', '11', '加固配置', 'main', 'main', 'main', '0', '0', '2019-11-19 17:47:27', '2019-11-19 21:12:33');
INSERT INTO `s12_system_priv` VALUES ('39', '11', '修改游戏加固配置', 'hprotect', 'config', 'modify', '38', '0', '2019-11-19 18:05:11', '2019-11-19 21:12:43');

-- ----------------------------
-- Table structure for s14_system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s12_system_user_group`;
CREATE TABLE `s12_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s14_system_user_group
-- ----------------------------
INSERT INTO `s12_system_user_group` VALUES ('1', '1', '1', '2019-11-19 16:32:15', '2019-11-19 16:32:15');
INSERT INTO `s12_system_user_group` VALUES ('2', '2', '2', '2019-11-19 17:38:02', '2019-11-19 17:38:02');
INSERT INTO `s12_system_user_group` VALUES ('3', '2', '3', '2019-11-22 10:44:56', '2019-11-22 10:44:56');
INSERT INTO `s12_system_user_group` VALUES ('4', '3', '2', '2019-12-02 16:43:47', '2019-12-02 16:43:47');