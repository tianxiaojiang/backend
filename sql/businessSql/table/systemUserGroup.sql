use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for system_user_group
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_user_group`;
CREATE TABLE `s{{SID}}_system_user_group` (
  `sug_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `ad_uid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
