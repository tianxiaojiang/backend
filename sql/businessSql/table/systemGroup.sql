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
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
