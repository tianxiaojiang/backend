use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for system_group
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_group`;
CREATE TABLE `s{{SID}}_system_group` (
  `sg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_desc` varchar(128) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sg_name` varchar(16) NOT NULL DEFAULT '' COMMENT '角色名称',
  `privilege_level` tinyint(1) not null default 2 comment '后台角色权限级别置位，一位前台权限、二位后台权限',
  `kind` tinyint (1) NOT NULL DEFAULT 0 COMMENT '角色类型，两张类型不能共存 0 普通角色，1 专有角色',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
