use integration_background;
set names utf8;


-- ----------------------------
-- Table structure for s10_game
-- ----------------------------
DROP TABLE IF EXISTS `s10_game`;
CREATE TABLE `s10_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_game
-- ----------------------------

-- ----------------------------
-- Table structure for s10_system_group
-- ----------------------------
DROP TABLE IF EXISTS `s10_system_group`;
CREATE TABLE `s10_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `privilege_level` tinyint(1) NOT NULL DEFAULT 2 COMMENT '后台角色权限级别置位，一位前台权限、二位后台权限',
  `kind` tinyint(1) NOT NULL DEFAULT 0 COMMENT '角色类型，两张类型不能共存 0 普通角色，1 专有角色',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_system_group
-- ----------------------------
INSERT INTO `s10_system_group` VALUES ('1', '-1', '系统管理员', '3', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');

-- ----------------------------
-- Table structure for s10_system_group_game
-- ----------------------------
DROP TABLE IF EXISTS `s10_system_group_game`;
CREATE TABLE `s10_system_group_game` (
  `sggid` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `game_id` int(11) NOT NULL DEFAULT 0 COMMENT '游戏id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sggid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_system_group_game
-- ----------------------------
INSERT INTO `s10_system_group_game` VALUES ('1', '1', '-1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');

-- ----------------------------
-- Table structure for s10_system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s10_system_group_priv`;
CREATE TABLE `s10_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_system_group_priv
-- ----------------------------
INSERT INTO `s10_system_group_priv` VALUES ('1', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('2', '1', '2', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('3', '1', '3', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('4', '1', '4', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('5', '1', '5', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('6', '1', '6', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('7', '1', '7', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('8', '1', '8', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('9', '1', '9', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('10', '1', '10', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('11', '1', '11', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('12', '1', '12', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('13', '1', '13', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('14', '1', '14', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('15', '1', '15', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('16', '1', '16', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('17', '1', '17', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('18', '1', '18', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('19', '1', '19', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('20', '1', '20', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('21', '1', '21', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('22', '1', '22', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('23', '1', '23', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('24', '1', '24', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('25', '1', '25', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('26', '1', '26', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('27', '1', '27', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('28', '1', '28', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('29', '1', '29', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('30', '1', '30', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('31', '1', '31', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('32', '1', '32', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('33', '1', '33', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('34', '1', '34', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_group_priv` VALUES ('35', '1', '35', '2019-07-30 13:31:26', '2019-07-30 13:31:26');
INSERT INTO `s10_system_group_priv` VALUES ('36', '1', '36', '2019-07-30 13:31:26', '2019-07-30 13:31:26');
INSERT INTO `s10_system_group_priv` VALUES ('37', '1', '37', '2019-07-31 12:37:45', '2019-07-31 12:37:45');
INSERT INTO `s10_system_group_priv` VALUES ('38', '1', '38', '2019-07-31 16:02:45', '2019-07-31 16:02:45');
INSERT INTO `s10_system_group_priv` VALUES ('39', '1', '39', '2019-07-31 17:07:47', '2019-07-31 17:07:47');
INSERT INTO `s10_system_group_priv` VALUES ('40', '1', '40', '2019-07-31 18:03:23', '2019-07-31 18:03:23');
INSERT INTO `s10_system_group_priv` VALUES ('41', '1', '41', '2019-07-31 18:03:23', '2019-07-31 18:03:23');
INSERT INTO `s10_system_group_priv` VALUES ('42', '1', '42', '2019-07-31 18:38:09', '2019-07-31 18:38:09');
INSERT INTO `s10_system_group_priv` VALUES ('43', '1', '43', '2019-07-31 19:56:49', '2019-07-31 19:56:49');
INSERT INTO `s10_system_group_priv` VALUES ('44', '1', '44', '2019-07-31 19:56:49', '2019-07-31 19:56:49');
INSERT INTO `s10_system_group_priv` VALUES ('45', '1', '45', '2019-07-31 19:56:49', '2019-07-31 19:56:49');
INSERT INTO `s10_system_group_priv` VALUES ('47', '1', '46', '2019-07-31 19:57:44', '2019-07-31 19:57:44');
INSERT INTO `s10_system_group_priv` VALUES ('48', '1', '47', '2019-08-21 16:46:31', '2019-08-21 16:46:31');
INSERT INTO `s10_system_group_priv` VALUES ('49', '1', '48', '2019-08-21 16:46:31', '2019-08-21 16:46:31');
INSERT INTO `s10_system_group_priv` VALUES ('50', '1', '49', '2019-08-21 16:46:31', '2019-08-21 16:46:31');
INSERT INTO `s10_system_group_priv` VALUES ('51', '1', '50', '2019-08-21 16:46:31', '2019-08-21 16:46:31');
INSERT INTO `s10_system_group_priv` VALUES ('52', '1', '51', '2019-08-21 16:46:31', '2019-08-21 16:46:31');
INSERT INTO `s10_system_group_priv` VALUES ('53', '1', '52', '2019-08-21 16:46:31', '2019-08-21 16:46:31');

-- ----------------------------
-- Table structure for s10_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `s10_system_menu`;
CREATE TABLE `s10_system_menu` (
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_system_menu
-- ----------------------------
INSERT INTO `s10_system_menu` VALUES ('1', '系统设置', 'system', '', '0', '1', '0', '0', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_menu` VALUES ('2', '管理员维护', 'admins', '', '0', '1', '0', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_menu` VALUES ('3', '角色列表', 'groups', '', '0', '1', '0', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_menu` VALUES ('4', '菜单列表', 'menus', '', '0', '1', '0', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_menu` VALUES ('5', '权限列表', 'privileges', '', '0', '1', '0', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_menu` VALUES ('6', '藏宝阁', 'trade', 'layui-icon-template-1', '1', '1', '0', '0', '0', '2019-07-29 18:08:10', '2019-08-21 15:43:43');
INSERT INTO `s10_system_menu` VALUES ('7', '用户管理', 'members', 'layui-icon-username', '0', '1', '0', '6', '0', '2019-07-29 18:08:10', '2019-08-21 15:47:28');
INSERT INTO `s10_system_menu` VALUES ('8', '收藏管理', 'favorites', '', '7', '1', '0', '6', '0', '2019-07-29 18:08:10', '2019-07-30 13:34:05');
INSERT INTO `s10_system_menu` VALUES ('9', '支付系统', 'pay', 'layui-icon-rmb', '6', '1', '0', '0', '0', '2019-07-30 11:34:52', '2019-07-31 19:47:59');
INSERT INTO `s10_system_menu` VALUES ('10', '系统配置', 'sys', 'layui-icon-username', '9', '1', '0', '0', '0', '2019-07-30 13:28:27', '2019-07-31 14:50:12');
INSERT INTO `s10_system_menu` VALUES ('11', '参数管理', 'settings', '', '0', '1', '0', '10', '0', '2019-07-30 13:29:02', '2019-07-30 16:31:58');
INSERT INTO `s10_system_menu` VALUES ('12', '支付配置', 'apps', '', '11', '1', '0', '10', '0', '2019-07-31 12:37:20', '2019-07-31 12:39:19');
INSERT INTO `s10_system_menu` VALUES ('13', '反馈管理', 'feedbacks', '', '8', '1', '0', '6', '0', '2019-07-31 16:01:37', '2019-07-31 16:03:06');
INSERT INTO `s10_system_menu` VALUES ('14', '角色售卖', 'roles', '', '13', '1', '0', '6', '0', '2019-07-31 17:07:13', '2019-07-31 17:11:55');
INSERT INTO `s10_system_menu` VALUES ('15', '身份认证', 'idcards', '', '14', '1', '0', '6', '0', '2019-07-31 18:02:17', '2019-07-31 18:13:20');
INSERT INTO `s10_system_menu` VALUES ('16', '手机认证', 'iphones', '', '15', '1', '0', '6', '0', '2019-07-31 18:02:40', '2019-07-31 18:31:03');
INSERT INTO `s10_system_menu` VALUES ('17', '站内消息', 'messages', '', '16', '1', '0', '6', '0', '2019-07-31 18:37:26', '2019-07-31 18:37:26');
INSERT INTO `s10_system_menu` VALUES ('18', '支付订单', 'payorders', '', '0', '1', '0', '9', '0', '2019-07-31 19:48:34', '2019-07-31 19:48:34');
INSERT INTO `s10_system_menu` VALUES ('19', '原路退单', 'refundorders', '', '18', '1', '0', '9', '0', '2019-07-31 19:51:41', '2019-08-27 12:10:02');
INSERT INTO `s10_system_menu` VALUES ('20', '提现订单', 'payoutorders', '', '19', '1', '0', '9', '0', '2019-07-31 19:52:06', '2019-08-27 13:41:00');
INSERT INTO `s10_system_menu` VALUES ('22', '销售订单', 'sale', '', '17', '1', '0', '6', '0', '2019-08-21 15:45:53', '2019-08-21 15:45:53');
INSERT INTO `s10_system_menu` VALUES ('23', '平台资金', 'pt_business', '', '22', '1', '0', '6', '0', '2019-08-21 15:46:51', '2019-08-21 15:55:23');
INSERT INTO `s10_system_menu` VALUES ('24', '用户流水', 'memberflows', '', '23', '1', '0', '6', '0', '2019-08-21 15:47:18', '2019-08-21 16:00:26');
INSERT INTO `s10_system_menu` VALUES ('25', '用户快照', 'snaps', '', '24', '1', '0', '6', '0', '2019-08-21 15:47:46', '2019-08-21 16:00:13');
INSERT INTO `s10_system_menu` VALUES ('26', '退单管控', 'refunds', '', '25', '1', '0', '6', '0', '2019-08-21 15:55:15', '2019-08-21 16:00:33');
INSERT INTO `s10_system_menu` VALUES ('27', '提现管控', 'cashes', '', '26', '1', '0', '6', '0', '2019-08-21 15:55:35', '2019-08-21 16:00:37');

-- ----------------------------
-- Table structure for s10_system_priv
-- ----------------------------
DROP TABLE IF EXISTS `s10_system_priv`;
CREATE TABLE `s10_system_priv` (
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_system_priv
-- ----------------------------
INSERT INTO `s10_system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '0', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('2', '2', '管理员维护', 'main', 'main', 'main', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('3', '2', '管理员列表', 'admin', 'admin-user', 'index', '2', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('4', '2', '添加管理员', 'admin', 'admin-user', 'create', '2', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('5', '2', '修改管理员', 'admin', 'admin-user', 'update', '2', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('6', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('7', '3', '角色维护', 'main', 'main', 'main', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('8', '3', '角色列表', 'admin', 'system-group', 'index', '7', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('9', '3', '添加角色', 'admin', 'system-group', 'create', '7', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('10', '3', '修改角色', 'admin', 'system-group', 'update', '7', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('11', '3', '删除角色', 'admin', 'system-group', 'delete', '7', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('12', '3', '角色权限列表', 'admin', 'system-group', 'group-privilege-list', '7', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('13', '3', '角色权限修改', 'admin', 'system-group', 'group-privilege-update', '7', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('14', '4', '菜单维护', 'main', 'main', 'main', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('15', '4', '菜单列表', 'admin', 'menu', 'index', '14', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('16', '4', '添加菜单', 'admin', 'menu', 'create', '14', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('17', '4', '修改菜单', 'admin', 'menu', 'update', '14', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('18', '4', '删除菜单', 'admin', 'menu', 'delete', '14', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('19', '5', '权限维护', 'main', 'main', 'main', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('20', '5', '权限列表', 'admin', 'privilege', 'index', '19', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('21', '5', '添加权限', 'admin', 'privilege', 'create', '19', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('22', '5', '修改权限', 'admin', 'privilege', 'update', '19', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('23', '5', '删除权限', 'admin', 'privilege', 'delete', '19', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('24', '6', '藏宝阁', 'main', 'main', 'main', '0', '0', '2019-07-29 18:08:10', '2019-08-21 15:43:55');
INSERT INTO `s10_system_priv` VALUES ('25', '7', '成员管理', 'main', 'main', 'main', '24', '0', '2019-07-29 18:08:10', '2019-07-30 13:30:27');
INSERT INTO `s10_system_priv` VALUES ('26', '7', '菜单示例1列表', 'index', 'news1', 'index', '25', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('27', '7', '添加菜单示例1', 'index', 'news1', 'create', '25', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('28', '7', '修改菜单示例1', 'index', 'news1', 'update', '25', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('29', '7', '删除菜单示例1', 'index', 'news1', 'delete', '25', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('30', '8', '收藏管理', 'main', 'main', 'main', '24', '0', '2019-07-29 18:08:10', '2019-07-31 16:01:58');
INSERT INTO `s10_system_priv` VALUES ('31', '8', '菜单示例2列表', 'index', 'news2', 'index', '30', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('32', '8', '添加菜单示例2', 'index', 'news2', 'create', '30', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('33', '8', '修改菜单示例2', 'index', 'news2', 'update', '30', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('34', '8', '删除菜单示例2', 'index', 'news2', 'delete', '30', '0', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
INSERT INTO `s10_system_priv` VALUES ('35', '10', '系统配置', 'main', 'main', 'main', '0', '0', '2019-07-30 13:30:52', '2019-07-30 13:30:52');
INSERT INTO `s10_system_priv` VALUES ('36', '11', '参数管理', 'main', 'main', 'main', '35', '0', '2019-07-30 13:31:06', '2019-07-30 13:31:06');
INSERT INTO `s10_system_priv` VALUES ('37', '12', '支付配置', 'main', 'main', 'main', '35', '0', '2019-07-31 12:37:38', '2019-07-31 12:37:38');
INSERT INTO `s10_system_priv` VALUES ('38', '13', '反馈管理', 'main', 'main', 'main', '24', '0', '2019-07-31 16:02:13', '2019-07-31 16:02:13');
INSERT INTO `s10_system_priv` VALUES ('39', '14', '角色售卖', 'main', 'main', 'main', '24', '0', '2019-07-31 17:07:42', '2019-07-31 17:07:42');
INSERT INTO `s10_system_priv` VALUES ('40', '15', '身份认证', 'main', 'main', 'main', '24', '0', '2019-07-31 18:03:03', '2019-07-31 18:03:51');
INSERT INTO `s10_system_priv` VALUES ('41', '16', '手机认证', 'main', 'main', 'main', '24', '0', '2019-07-31 18:03:18', '2019-07-31 18:03:18');
INSERT INTO `s10_system_priv` VALUES ('42', '17', '站内消息', 'main', 'main', 'main', '24', '0', '2019-07-31 18:37:41', '2019-07-31 18:37:41');
INSERT INTO `s10_system_priv` VALUES ('43', '9', '支付系统', 'main', 'main', 'main', '0', '0', '2019-07-31 19:52:40', '2019-07-31 19:52:40');
INSERT INTO `s10_system_priv` VALUES ('44', '18', '支付订单', 'main', 'main', 'main', '43', '0', '2019-07-31 19:55:09', '2019-07-31 19:55:09');
INSERT INTO `s10_system_priv` VALUES ('45', '19', '原路退单', 'main', 'main', 'main', '43', '0', '2019-07-31 19:55:45', '2019-07-31 19:56:32');
INSERT INTO `s10_system_priv` VALUES ('46', '20', '提现订单', 'main', 'main', 'main', '43', '0', '2019-07-31 19:56:03', '2019-07-31 19:59:22');
INSERT INTO `s10_system_priv` VALUES ('47', '22', '销售订单', 'main', 'main', 'main', '24', '0', '2019-08-21 15:46:15', '2019-08-21 15:46:15');
INSERT INTO `s10_system_priv` VALUES ('48', '24', '用户流水', 'main', 'main', 'main', '24', '0', '2019-08-21 15:56:12', '2019-08-21 15:56:12');
INSERT INTO `s10_system_priv` VALUES ('49', '25', '用户快照', 'main', 'main', 'main', '24', '0', '2019-08-21 15:56:34', '2019-08-21 15:56:34');
INSERT INTO `s10_system_priv` VALUES ('50', '23', '平台资金', 'main', 'main', 'main', '24', '0', '2019-08-21 15:56:58', '2019-08-21 15:56:58');
INSERT INTO `s10_system_priv` VALUES ('51', '26', '退关管控', 'main', 'main', 'main', '24', '0', '2019-08-21 15:57:51', '2019-08-21 15:57:51');
INSERT INTO `s10_system_priv` VALUES ('52', '27', '提现管控', 'main', 'main', 'main', '24', '0', '2019-08-21 15:58:14', '2019-08-21 15:58:14');

-- ----------------------------
-- Table structure for s10_system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s10_system_user_group`;
CREATE TABLE `s10_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s10_system_user_group
-- ----------------------------
INSERT INTO `s10_system_user_group` VALUES ('1', '1', '1', '2019-07-29 18:08:10', '2019-07-29 18:08:10');
