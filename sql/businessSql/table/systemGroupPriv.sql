use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for system_group_priv
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_group_priv`;
CREATE TABLE `s{{SID}}_system_group_priv` (
  `sgp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应角色id',
  `sp_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应权限id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sgp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
