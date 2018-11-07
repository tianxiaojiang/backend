use integration_background;
set names utf8;

-- ----------------------------
-- Records of system_priv
-- ----------------------------
INSERT INTO `s{{SID}}_system_priv` VALUES ('1', '1', '系统管理', 'main', 'main', 'main', '0', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('2', '2', '管理员维护', 'main', 'main', 'main', '1', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('3', '2', '管理员列表', 'admin', 'admin-user', 'index', '2', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('4', '2', '添加管理员', 'admin', 'admin-user', 'create', '2', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('5', '2', '修改管理员', 'admin', 'admin-user', 'update', '2', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('6', '2', '删除管理员', 'admin', 'admin-user', 'delete', '2', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('7', '3', '角色维护', 'main', 'main', 'main', '1', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('8', '3', '角色列表', 'admin', 'system-group', 'index', '7', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('9', '3', '添加角色', 'admin', 'system-group', 'create', '7', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('10', '3', '修改角色', 'admin', 'system-group', 'update', '7', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('11', '3', '删除角色', 'admin', 'system-group', 'delete', '7', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('12', '3', '角色权限列表', 'admin', 'system-group', 'group-privilege-list', '7', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('13', '3', '角色权限修改', 'admin', 'system-group', 'group-privilege-update', '7', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('14', '4', '菜单维护', 'main', 'main', 'main', '1', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('15', '4', '菜单列表', 'admin', 'menu', 'index', '14', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('16', '4', '添加菜单', 'admin', 'menu', 'create', '14', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('17', '4', '修改菜单', 'admin', 'menu', 'update', '14', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('18', '4', '删除菜单', 'admin', 'menu', 'delete', '14', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('19', '5', '权限维护', 'main', 'main', 'main', '1', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('20', '5', '权限列表', 'admin', 'privilege', 'index', '19', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('21', '5', '添加权限', 'admin', 'privilege', 'create', '19', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('22', '5', '修改权限', 'admin', 'privilege', 'update', '19', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('23', '5', '删除权限', 'admin', 'privilege', 'delete', '19', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('24', '6', '菜单组示例', 'main', 'main', 'main', '0', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('25', '7', '菜单示例', 'main', 'main', 'main', '24', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('26', '7', '菜单示例1列表', 'index', 'news1', 'index', '25', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('27', '7', '添加菜单示例1', 'index', 'news1', 'create', '25', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('28', '7', '修改菜单示例1', 'index', 'news1', 'update', '25', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('29', '7', '删除菜单示例1', 'index', 'news1', 'delete', '25', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('30', '8', '菜单示例2', 'main', 'main', 'main', '24', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('31', '8', '菜单示例2列表', 'index', 'news2', 'index', '30', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('32', '8', '添加菜单示例2', 'index', 'news2', 'create', '30', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('33', '8', '修改菜单示例2', 'index', 'news2', 'update', '30', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('34', '8', '删除菜单示例2', 'index', 'news2', 'delete', '30', '0', '{{CREATED_AT}}', '{{UPDATED_AT}}');