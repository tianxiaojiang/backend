use backend;
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
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`ad_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'admin', 'dd02677c714da6a5becbfe9a36aeb8c1', 'ufob', '17717563803', 'BigWame1', '1', '', 0, '0', '1510544394', '1510569559');
INSERT INTO `admin_user` VALUES ('2', 'tianweimin', 'dd02677c714da6a5becbfe9a36aeb8c1', 'ufob', '17717563803', 'BigWame1', '1', '', 0, '0', '1510544394', '1510569559');

-- ----------------------------
-- Table structure for system_group
-- ----------------------------
DROP TABLE IF EXISTS `system_group`;
CREATE TABLE `system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '用户组描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '组名称',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_group
-- ----------------------------
INSERT INTO `system_group` VALUES ('1', '拥有所有权限', '系统管理员', '1510544394', '1510544394');
INSERT INTO `system_group` VALUES ('2', '查看权限', '域账号', '1510544394', '1510544394');

-- ----------------------------
-- Table structure for system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `system_group_priv`;
CREATE TABLE `system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应组id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_group_priv
-- ----------------------------
INSERT INTO `system_group_priv` VALUES ('1', '1', '1', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('2', '1', '2', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('3', '1', '3', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('4', '1', '4', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('5', '1', '5', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('6', '1', '6', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('7', '1', '7', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('8', '1', '8', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('9', '1', '9', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('10', '1', '10', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('11', '1', '11', '1510544394', '1510544394');
INSERT INTO `system_group_priv` VALUES ('12', '1', '12', '1510544394', '1510544394');

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
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`sm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES ('1', '系统管理', '/main/main/main', 'system', '', '0', '1', '0', '0', '1510544394', '1510544394');
INSERT INTO `system_menu` VALUES ('2', '管理员列表', '/admin/admin-user/index', 'admins', '', '0', '1', '0', '1', '1510544394', '1510544394');
INSERT INTO `system_menu` VALUES ('3', '角色列表', '/admin/system-group/index', 'groups', '', '0', '1', '0', '1', '1510544394', '1510544394');

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
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`sp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_priv
-- ----------------------------
INSERT INTO `system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('2', '2', '管理员列表', 'admin', 'admin-user', 'index', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('3', '2', '添加管理员', 'admin', 'admin-user', 'create', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('4', '2', '修改管理员', 'admin', 'admin-user', 'update', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('5', '2', '删除管理员', 'admin', 'admin-user', 'delete', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('6', '3', '角色列表', 'admin', 'system-group', 'index', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('7', '3', '添加角色', 'admin', 'system-group', 'create', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('8', '3', '修改角色', 'admin', 'system-group', 'update', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('9', '3', '删除角色', 'admin', 'system-group', 'delete', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('10', '3', '组权限列表', 'admin', 'system-group', 'group-privilege-list', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('11', '3', '组权限修改', 'admin', 'system-group', 'group-privilege-update', '1510544394', '1510544394');
INSERT INTO `system_priv` VALUES ('12', '1', '通用接口调用', 'common', 'system', 'index', '1510544394', '1510544394');

-- ----------------------------
-- Table structure for system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `system_user_group`;
CREATE TABLE `system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of system_user_group
-- ----------------------------
INSERT INTO `system_user_group` VALUES ('1', '1', '1', '1510569559', '1510569559');
INSERT INTO `system_user_group` VALUES ('2', '1', '2', '1510569578', '1510569578');
