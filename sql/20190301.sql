use integration_background;
set names utf8;

--
-- 发票管理后台上线数据
--

-- ----------------------------
-- Table structure for s5_game
-- ----------------------------
DROP TABLE IF EXISTS `s5_game`;
CREATE TABLE `s5_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_game
-- ----------------------------

-- ----------------------------
-- Table structure for s5_system_group
-- ----------------------------
DROP TABLE IF EXISTS `s5_system_group`;
CREATE TABLE `s5_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `privilege_level` tinyint(1) NOT NULL DEFAULT 2 COMMENT '后台角色权限级别置位，一位前台权限、二位后台权限',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_system_group
-- ----------------------------
INSERT INTO `s5_system_group` VALUES ('1', '-1', '系统管理员', '3', '2019-01-25 14:06:44', '2019-01-25 14:06:44');

-- ----------------------------
-- Table structure for s5_system_group_game
-- ----------------------------
DROP TABLE IF EXISTS `s5_system_group_game`;
CREATE TABLE `s5_system_group_game` (
  `sggid` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `game_id` int(11) NOT NULL DEFAULT 0 COMMENT '游戏id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sggid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_system_group_game
-- ----------------------------
INSERT INTO `s5_system_group_game` VALUES ('1', '1', '-1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');

-- ----------------------------
-- Table structure for s5_system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s5_system_group_priv`;
CREATE TABLE `s5_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_system_group_priv
-- ----------------------------
INSERT INTO `s5_system_group_priv` VALUES ('1', '1', '1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('2', '1', '2', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('3', '1', '3', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('4', '1', '4', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('5', '1', '5', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('6', '1', '6', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('7', '1', '7', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('8', '1', '8', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('9', '1', '9', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('10', '1', '10', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('11', '1', '11', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('12', '1', '12', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('13', '1', '13', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('14', '1', '14', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('15', '1', '15', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('16', '1', '16', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('17', '1', '17', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('18', '1', '18', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('19', '1', '19', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('20', '1', '20', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('21', '1', '21', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('22', '1', '22', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('23', '1', '23', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('24', '1', '24', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('25', '1', '25', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('26', '1', '26', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('27', '1', '27', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('28', '1', '28', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('29', '1', '29', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('30', '1', '30', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('31', '1', '31', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('32', '1', '32', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('33', '1', '33', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('34', '1', '34', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_group_priv` VALUES ('35', '1', '35', '2019-01-30 18:29:26', '2019-01-30 18:29:26');
INSERT INTO `s5_system_group_priv` VALUES ('36', '1', '36', '2019-01-30 18:29:26', '2019-01-30 18:29:26');
INSERT INTO `s5_system_group_priv` VALUES ('37', '1', '37', '2019-01-31 16:25:38', '2019-01-31 16:25:38');
INSERT INTO `s5_system_group_priv` VALUES ('38', '1', '38', '2019-02-01 12:14:45', '2019-02-01 12:14:45');
INSERT INTO `s5_system_group_priv` VALUES ('39', '1', '39', '2019-02-01 19:14:21', '2019-02-01 19:14:21');
INSERT INTO `s5_system_group_priv` VALUES ('40', '1', '40', '2019-02-01 19:14:21', '2019-02-01 19:14:21');
INSERT INTO `s5_system_group_priv` VALUES ('41', '1', '41', '2019-02-02 12:27:16', '2019-02-02 12:27:16');
INSERT INTO `s5_system_group_priv` VALUES ('42', '1', '42', '2019-02-02 12:27:16', '2019-02-02 12:27:16');
INSERT INTO `s5_system_group_priv` VALUES ('43', '1', '43', '2019-02-02 12:27:16', '2019-02-02 12:27:16');
INSERT INTO `s5_system_group_priv` VALUES ('44', '1', '44', '2019-02-02 12:27:16', '2019-02-02 12:27:16');
INSERT INTO `s5_system_group_priv` VALUES ('45', '1', '45', '2019-02-27 18:32:23', '2019-02-27 18:32:23');
INSERT INTO `s5_system_group_priv` VALUES ('46', '1', '46', '2019-02-28 10:54:47', '2019-02-28 10:54:47');

-- ----------------------------
-- Table structure for s5_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `s5_system_menu`;
CREATE TABLE `s5_system_menu` (
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_system_menu
-- ----------------------------
INSERT INTO `s5_system_menu` VALUES ('1', '系统设置', 'system', '', '0', '1', '0', '0', '1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_menu` VALUES ('2', '管理员维护', 'admins', '', '0', '1', '0', '1', '1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_menu` VALUES ('3', '角色列表', 'groups', '', '0', '1', '0', '1', '1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_menu` VALUES ('4', '菜单列表', 'menus', '', '0', '1', '0', '1', '1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_menu` VALUES ('5', '权限列表', 'privileges', '', '0', '1', '0', '1', '1', '2019-01-25 14:06:45', '2019-01-25 14:06:45');
INSERT INTO `s5_system_menu` VALUES ('6', '系统配置', 'invsys', 'layui-icon-app', '0', '1', '0', '0', '0', '2019-01-25 14:06:45', '2019-02-11 12:18:27');
INSERT INTO `s5_system_menu` VALUES ('7', '发票配置', 'settings', '', '0', '1', '0', '6', '0', '2019-01-25 14:06:45', '2019-01-30 18:18:06');
INSERT INTO `s5_system_menu` VALUES ('8', '商户管理', 'app', '', '0', '1', '0', '6', '0', '2019-01-25 14:06:45', '2019-02-01 19:10:04');
INSERT INTO `s5_system_menu` VALUES ('9', '游戏管理', 'game', '', '8', '1', '0', '6', '0', '2019-02-01 12:14:08', '2019-02-01 12:14:08');
INSERT INTO `s5_system_menu` VALUES ('10', '发票管理', 'index', 'layui-icon-template', '6', '1', '0', '0', '0', '2019-02-01 19:12:18', '2019-02-02 13:40:25');
INSERT INTO `s5_system_menu` VALUES ('11', '机构抬头', 'orgs', '', '0', '1', '0', '10', '0', '2019-02-01 19:13:03', '2019-02-01 19:16:59');
INSERT INTO `s5_system_menu` VALUES ('12', '开票管理', 'invs', '', '11', '1', '0', '10', '0', '2019-02-02 12:23:42', '2019-02-02 14:34:43');
INSERT INTO `s5_system_menu` VALUES ('13', '开票额度', 'invlimits', '', '12', '1', '0', '10', '0', '2019-02-02 12:24:27', '2019-02-11 12:16:50');
INSERT INTO `s5_system_menu` VALUES ('14', '端游订单', 'index', '', '13', '1', '0', '10', '0', '2019-02-02 12:25:00', '2019-02-02 12:25:00');
INSERT INTO `s5_system_menu` VALUES ('15', '手游订单', 'index', '', '14', '1', '0', '10', '0', '2019-02-02 12:25:18', '2019-02-02 12:25:18');
INSERT INTO `s5_system_menu` VALUES ('16', '会员管理', 'member', '', '15', '1', '0', '10', '0', '2019-02-27 18:31:36', '2019-02-27 18:31:36');
INSERT INTO `s5_system_menu` VALUES ('17', '额度扣除', 'dedut', '', '16', '1', '0', '10', '0', '2019-02-28 10:54:10', '2019-02-28 10:54:10');

-- ----------------------------
-- Table structure for s5_system_priv
-- ----------------------------
DROP TABLE IF EXISTS `s5_system_priv`;
CREATE TABLE `s5_system_priv` (
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_system_priv
-- ----------------------------
INSERT INTO `s5_system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '0', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('2', '2', '管理员维护', 'main', 'main', 'main', '1', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('3', '2', '管理员列表', 'admin', 'admin-user', 'index', '2', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('4', '2', '添加管理员', 'admin', 'admin-user', 'create', '2', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('5', '2', '修改管理员', 'admin', 'admin-user', 'update', '2', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('6', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('7', '3', '角色维护', 'main', 'main', 'main', '1', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('8', '3', '角色列表', 'admin', 'system-group', 'index', '7', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('9', '3', '添加角色', 'admin', 'system-group', 'create', '7', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('10', '3', '修改角色', 'admin', 'system-group', 'update', '7', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('11', '3', '删除角色', 'admin', 'system-group', 'delete', '7', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('12', '3', '角色权限列表', 'admin', 'system-group', 'group-privilege-list', '7', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('13', '3', '角色权限修改', 'admin', 'system-group', 'group-privilege-update', '7', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('14', '4', '菜单维护', 'main', 'main', 'main', '1', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('15', '4', '菜单列表', 'admin', 'menu', 'index', '14', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('16', '4', '添加菜单', 'admin', 'menu', 'create', '14', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('17', '4', '修改菜单', 'admin', 'menu', 'update', '14', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('18', '4', '删除菜单', 'admin', 'menu', 'delete', '14', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('19', '5', '权限维护', 'main', 'main', 'main', '1', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('20', '5', '权限列表', 'admin', 'privilege', 'index', '19', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('21', '5', '添加权限', 'admin', 'privilege', 'create', '19', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('22', '5', '修改权限', 'admin', 'privilege', 'update', '19', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('23', '5', '删除权限', 'admin', 'privilege', 'delete', '19', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');
INSERT INTO `s5_system_priv` VALUES ('35', '6', '系统配置', 'main', 'main', 'main', '0', '0', '2019-01-30 14:48:16', '2019-01-30 18:30:37');
INSERT INTO `s5_system_priv` VALUES ('36', '7', '发票系统管理', 'main', 'main', 'main', '35', '0', '2019-01-30 18:25:37', '2019-01-30 18:30:43');
INSERT INTO `s5_system_priv` VALUES ('37', '8', '商户管理', 'main', 'main', 'main', '35', '0', '2019-01-31 16:24:05', '2019-01-31 16:25:26');
INSERT INTO `s5_system_priv` VALUES ('38', '9', '游戏管理', 'main', 'main', 'main', '35', '0', '2019-02-01 12:14:37', '2019-02-01 12:14:37');
INSERT INTO `s5_system_priv` VALUES ('39', '10', '发票管理', 'main', 'main', 'main', '0', '0', '2019-02-01 19:13:41', '2019-02-01 19:13:41');
INSERT INTO `s5_system_priv` VALUES ('40', '11', '机构抬头', 'main', 'main', 'main', '39', '0', '2019-02-01 19:14:05', '2019-02-01 19:14:05');
INSERT INTO `s5_system_priv` VALUES ('41', '12', '开票管理', 'main', 'main', 'main', '39', '0', '2019-02-02 12:26:07', '2019-02-02 12:26:07');
INSERT INTO `s5_system_priv` VALUES ('42', '13', '开票额度', 'main', 'main', 'main', '39', '0', '2019-02-02 12:26:27', '2019-02-02 12:26:27');
INSERT INTO `s5_system_priv` VALUES ('43', '14', '端游订单', 'main', 'main', 'main', '39', '0', '2019-02-02 12:26:52', '2019-02-02 12:26:52');
INSERT INTO `s5_system_priv` VALUES ('44', '15', '手游订单', 'main', 'main', 'main', '39', '0', '2019-02-02 12:27:08', '2019-02-02 12:27:08');
INSERT INTO `s5_system_priv` VALUES ('45', '16', '成员管理', 'main', 'main', 'main', '39', '0', '2019-02-27 18:32:07', '2019-02-27 18:32:07');
INSERT INTO `s5_system_priv` VALUES ('46', '17', '额度扣除', 'main', 'main', 'main', '39', '0', '2019-02-28 10:54:37', '2019-02-28 10:54:37');

-- ----------------------------
-- Table structure for s5_system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s5_system_user_group`;
CREATE TABLE `s5_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s5_system_user_group
-- ----------------------------
INSERT INTO `s5_system_user_group` VALUES ('1', '1', '1', '2019-01-25 14:06:44', '2019-01-25 14:06:44');