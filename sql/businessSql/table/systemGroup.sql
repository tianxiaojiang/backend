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
  `sg_limit_game` int (1) NOT NULL DEFAULT 0 COMMENT '是否限制游戏，0：不限制，1：限制',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
