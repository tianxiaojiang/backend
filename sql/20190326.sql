use integration_background;
set names utf8;

DROP TABLE IF EXISTS `s6_game`;
CREATE TABLE `s6_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_game
-- ----------------------------
INSERT INTO `s6_game` VALUES ('45', '0', '2019-02-26 13:34:38', '2019-02-26 13:34:38');
INSERT INTO `s6_game` VALUES ('5051', '0', '2019-02-26 13:34:38', '2019-02-26 13:34:38');
INSERT INTO `s6_game` VALUES ('5078', '0', '2019-02-26 13:34:38', '2019-02-26 13:34:38');
INSERT INTO `s6_game` VALUES ('5174', '0', '2019-02-26 13:34:38', '2019-02-26 13:34:38');

-- ----------------------------
-- Table structure for s6_system_group
-- ----------------------------
DROP TABLE IF EXISTS `s6_system_group`;
CREATE TABLE `s6_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `privilege_level` tinyint(1) NOT NULL DEFAULT 2 COMMENT '后台角色权限级别置位，一位前台权限、二位后台权限',
  `kind` tinyint(1) NOT NULL DEFAULT 0 COMMENT '角色类型 0,普通角色 1,独有角色',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_system_group
-- ----------------------------
INSERT INTO `s6_system_group` VALUES ('1', '系统管理员', '系统管理员', '3', '0', '2019-02-14 15:19:50', '2019-03-26 10:05:31');
INSERT INTO `s6_system_group` VALUES ('2', '开发人员', '开发人员', '1', '0', '2019-01-07 10:25:35', '2019-01-07 18:07:55');
INSERT INTO `s6_system_group` VALUES ('3', '测试人员', '测试人员', '1', '0', '2019-01-07 15:45:15', '2019-01-07 15:46:42');
INSERT INTO `s6_system_group` VALUES ('4', '游戏产品', '游戏产品', '1', '0', '2019-01-07 15:45:44', '2019-01-07 15:46:47');

-- ----------------------------
-- Table structure for s6_system_group_game
-- ----------------------------
DROP TABLE IF EXISTS `s6_system_group_game`;
CREATE TABLE `s6_system_group_game` (
  `sggid` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `game_id` int(11) NOT NULL DEFAULT 0 COMMENT '游戏id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sggid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_system_group_game
-- ----------------------------
INSERT INTO `s6_system_group_game` VALUES ('1', '1', '-1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_game` VALUES ('3', '1', '5051', '2019-02-26 13:34:50', '2019-02-26 13:34:50');
INSERT INTO `s6_system_group_game` VALUES ('4', '1', '5078', '2019-02-26 13:34:50', '2019-02-26 13:34:50');
INSERT INTO `s6_system_group_game` VALUES ('5', '1', '5174', '2019-02-26 13:34:50', '2019-02-26 13:34:50');

-- ----------------------------
-- Table structure for s6_system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s6_system_group_priv`;
CREATE TABLE `s6_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_system_group_priv
-- ----------------------------
INSERT INTO `s6_system_group_priv` VALUES ('1', '1', '1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('2', '1', '2', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('3', '1', '3', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('4', '1', '4', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('5', '1', '5', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('6', '1', '6', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('7', '1', '7', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('8', '1', '8', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('9', '1', '9', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('10', '1', '10', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('11', '1', '11', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('12', '1', '12', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('13', '1', '13', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('14', '1', '14', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('15', '1', '15', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('16', '1', '16', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('17', '1', '17', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('18', '1', '18', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('19', '1', '19', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('20', '1', '20', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('21', '1', '21', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('22', '1', '22', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('23', '1', '23', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('24', '1', '24', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('25', '1', '25', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('26', '1', '26', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('27', '1', '27', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('28', '1', '28', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('29', '1', '29', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('30', '1', '30', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('31', '1', '31', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('32', '1', '32', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('33', '1', '33', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('34', '1', '34', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_group_priv` VALUES ('35', '1', '35', '2019-02-14 17:25:33', '2019-02-14 17:25:33');
INSERT INTO `s6_system_group_priv` VALUES ('38', '1', '38', '2019-02-19 12:10:34', '2019-02-19 12:10:34');
INSERT INTO `s6_system_group_priv` VALUES ('39', '1', '39', '2019-02-19 12:10:34', '2019-02-19 12:10:34');
INSERT INTO `s6_system_group_priv` VALUES ('40', '1', '1034', '2019-02-26 13:21:15', '2019-02-26 13:21:15');
INSERT INTO `s6_system_group_priv` VALUES ('41', '1', '1035', '2019-02-26 13:21:15', '2019-02-26 13:21:15');
INSERT INTO `s6_system_group_priv` VALUES ('42', '1', '1036', '2019-02-26 13:21:15', '2019-02-26 13:21:15');
INSERT INTO `s6_system_group_priv` VALUES ('43', '1', '200', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('44', '1', '201', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('45', '1', '202', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('46', '1', '203', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('47', '1', '204', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('48', '1', '205', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('49', '1', '206', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('50', '1', '1003', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('51', '1', '211', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('52', '1', '212', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('53', '1', '213', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('54', '1', '214', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('55', '1', '1016', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('56', '1', '1017', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('57', '1', '1018', '2019-02-26 13:56:38', '2019-02-26 13:56:38');
INSERT INTO `s6_system_group_priv` VALUES ('58', '1', '1037', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('59', '1', '1038', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('60', '1', '1039', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('61', '1', '1040', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('62', '1', '1041', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('63', '1', '1042', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('64', '1', '1043', '2019-02-26 14:24:10', '2019-02-26 14:24:10');
INSERT INTO `s6_system_group_priv` VALUES ('65', '1', '1044', '2019-02-26 14:38:03', '2019-02-26 14:38:03');
INSERT INTO `s6_system_group_priv` VALUES ('66', '1', '1045', '2019-02-26 15:25:59', '2019-02-26 15:25:59');

-- ----------------------------
-- Table structure for s6_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `s6_system_menu`;
CREATE TABLE `s6_system_menu` (
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_system_menu
-- ----------------------------
INSERT INTO `s6_system_menu` VALUES ('1', '系统设置', 'system', '', '0', '1', '0', '0', '1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_menu` VALUES ('2', '管理员维护', 'admins', '', '0', '1', '0', '1', '1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_menu` VALUES ('3', '角色列表', 'groups', '', '0', '1', '0', '1', '1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_menu` VALUES ('4', '菜单列表', 'menus', '', '0', '1', '0', '1', '1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_menu` VALUES ('5', '权限列表', 'privileges', '', '0', '1', '0', '1', '1', '2019-02-14 15:19:51', '2019-02-14 15:19:51');
INSERT INTO `s6_system_menu` VALUES ('10', '产品管理', 'product', 'layui-icon-theme', '0', '1', '0', '0', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_menu` VALUES ('11', '产品列表', 'product', '', '0', '1', '0', '10', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_menu` VALUES ('15', '操作日志', 'operation-log', '', '0', '1', '0', '40', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_menu` VALUES ('34', '产品联系人', 'linkman', '', '0', '1', '0', '10', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_menu` VALUES ('40', '日志', 'logs', 'layui-icon-zuoduiqi', '66', '1', '0', '0', '0', '2019-01-07 17:58:38', '2019-02-26 15:29:50');
INSERT INTO `s6_system_menu` VALUES ('65', '游戏列表', 'system-game', '', '0', '1', '0', '1', '1', '2019-02-11 11:14:31', '2019-02-11 11:14:40');
INSERT INTO `s6_system_menu` VALUES ('66', '家长监护', 'jianhu', 'layui-icon-auz', '10', '1', '0', '0', '0', '2019-02-26 14:11:15', '2019-02-26 15:29:50');
INSERT INTO `s6_system_menu` VALUES ('67', '家长信息', 'parents', '', '0', '1', '0', '66', '0', '2019-02-26 14:15:09', '2019-02-26 14:15:09');
INSERT INTO `s6_system_menu` VALUES ('68', '小孩信息', 'children', '', '0', '1', '0', '66', '0', '2019-02-26 14:17:56', '2019-02-26 14:23:59');
INSERT INTO `s6_system_menu` VALUES ('69', '配置调整', 'config', '', '0', '1', '0', '66', '0', '2019-02-26 14:17:56', '2019-02-26 14:23:59');

-- ----------------------------
-- Table structure for s6_system_priv
-- ----------------------------
DROP TABLE IF EXISTS `s6_system_priv`;
CREATE TABLE `s6_system_priv` (
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
) ENGINE=InnoDB AUTO_INCREMENT=1046 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_system_priv
-- ----------------------------
INSERT INTO `s6_system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '0', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('2', '2', '管理员维护', 'main', 'main', 'main', '1', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('3', '2', '管理员列表', 'admin', 'admin-user', 'index', '2', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('4', '2', '添加管理员', 'admin', 'admin-user', 'create', '2', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('5', '2', '修改管理员', 'admin', 'admin-user', 'update', '2', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('6', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('7', '3', '角色维护', 'main', 'main', 'main', '1', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('8', '3', '角色列表', 'admin', 'system-group', 'index', '7', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('9', '3', '添加角色', 'admin', 'system-group', 'create', '7', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('10', '3', '修改角色', 'admin', 'system-group', 'update', '7', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('11', '3', '删除角色', 'admin', 'system-group', 'delete', '7', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('12', '3', '角色权限列表', 'admin', 'system-group', 'group-privilege-list', '7', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('13', '3', '角色权限修改', 'admin', 'system-group', 'group-privilege-update', '7', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('14', '4', '菜单维护', 'main', 'main', 'main', '1', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('15', '4', '菜单列表', 'admin', 'menu', 'index', '14', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('16', '4', '添加菜单', 'admin', 'menu', 'create', '14', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('17', '4', '修改菜单', 'admin', 'menu', 'update', '14', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('18', '4', '删除菜单', 'admin', 'menu', 'delete', '14', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('19', '5', '权限维护', 'main', 'main', 'main', '1', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('20', '5', '权限列表', 'admin', 'privilege', 'index', '19', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('21', '5', '添加权限', 'admin', 'privilege', 'create', '19', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('22', '5', '修改权限', 'admin', 'privilege', 'update', '19', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('23', '5', '删除权限', 'admin', 'privilege', 'delete', '19', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_priv` VALUES ('200', '10', '产品管理', 'main', 'main', 'main', '0', '0', '2019-01-04 16:21:08', '2019-01-07 17:08:58');
INSERT INTO `s6_system_priv` VALUES ('201', '11', '产品列表', 'main', 'main', 'main', '200', '0', '2019-01-04 16:21:08', '2019-01-07 17:09:09');
INSERT INTO `s6_system_priv` VALUES ('202', '11', '添加产品', 'index', 'product', 'create', '201', '0', '2019-01-04 16:21:08', '2019-01-07 17:09:26');
INSERT INTO `s6_system_priv` VALUES ('203', '11', '修改产品', 'index', 'product', 'update', '201', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('204', '11', '删除产品', 'index', 'product', 'delete', '201', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('205', '11', '我的产品', 'index', 'product', 'my-product', '201', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('206', '11', '切换产品', 'index', 'product', 'switch-product', '201', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('211', '34', '产品联系人', 'main', 'main', 'main', '200', '0', '2019-01-04 16:21:08', '2019-01-07 16:11:01');
INSERT INTO `s6_system_priv` VALUES ('212', '34', '添加产品联系人', 'index', 'linkman', 'create', '211', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('213', '34', '修改产品联系人', 'index', 'linkman', 'update', '211', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('214', '34', '删除产品联系人', 'index', 'linkman', 'delete', '211', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('1003', '11', '产品列表', 'index', 'product', 'index', '201', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('1016', '40', '日志', 'main', 'main', 'main', '0', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('1017', '15', '操作日志', 'main', 'main', 'main', '1016', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('1018', '15', '操作日志', 'admin', 'operation-log', 'index', '1017', '0', '2019-01-04 16:21:08', '2019-01-04 16:21:08');
INSERT INTO `s6_system_priv` VALUES ('1034', '65', '游戏列表', 'main', 'main', 'main', '1', '1', '2019-02-11 11:15:43', '2019-02-11 11:15:43');
INSERT INTO `s6_system_priv` VALUES ('1035', '65', '游戏列表', 'admin', 'system-game', 'index', '1034', '1', '2019-02-11 11:16:07', '2019-02-11 11:16:07');
INSERT INTO `s6_system_priv` VALUES ('1036', '65', '设置游戏', 'admin', 'system-game', 'create', '1034', '1', '2019-02-11 11:16:33', '2019-02-11 11:16:33');
INSERT INTO `s6_system_priv` VALUES ('1037', '66', '家长监护', 'main', 'main', 'main', '0', '0', '2019-02-26 14:18:57', '2019-02-26 14:18:57');
INSERT INTO `s6_system_priv` VALUES ('1038', '67', '家长信息', 'main', 'main', 'main', '1037', '0', '2019-02-26 14:19:24', '2019-02-26 14:19:24');
INSERT INTO `s6_system_priv` VALUES ('1039', '67', '家长信息', 'index', 'jianhu', 'parents', '1038', '0', '2019-02-26 14:19:56', '2019-02-26 14:23:35');
INSERT INTO `s6_system_priv` VALUES ('1040', '66', '小孩信息', 'index', 'jianhu', 'children', '1045', '0', '2019-02-26 14:20:44', '2019-02-26 14:23:39');
INSERT INTO `s6_system_priv` VALUES ('1041', '67', '解绑小孩', 'index', 'jianhu', 'unbind', '1045', '0', '2019-02-26 14:21:42', '2019-02-26 14:23:42');
INSERT INTO `s6_system_priv` VALUES ('1042', '69', '配置调整', 'main', 'main', 'main', '1037', '0', '2019-02-26 14:22:50', '2019-02-26 14:22:50');
INSERT INTO `s6_system_priv` VALUES ('1043', '70', '配置调整', 'index', 'jianhu', 'config', '1042', '0', '2019-02-26 14:23:23', '2019-02-26 15:22:36');
INSERT INTO `s6_system_priv` VALUES ('1044', '4', '菜单排序', 'admin', 'menu', 'update-sort', '14', '1', '2019-02-26 14:37:22', '2019-02-26 14:37:22');
INSERT INTO `s6_system_priv` VALUES ('1045', '68', '小孩信息', 'main', 'main', 'main', '1037', '0', '2019-02-26 15:19:30', '2019-02-26 15:19:30');

-- ----------------------------
-- Table structure for s6_system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s6_system_user_group`;
CREATE TABLE `s6_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s6_system_user_group
-- ----------------------------
INSERT INTO `s6_system_user_group` VALUES ('1', '1', '1', '2019-02-14 15:19:50', '2019-02-14 15:19:50');
INSERT INTO `s6_system_user_group` VALUES ('2', '1', '3', '2019-02-26 14:34:55', '2019-02-26 14:34:55');