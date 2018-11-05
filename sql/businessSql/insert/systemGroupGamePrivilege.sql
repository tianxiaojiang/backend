use integration_background;
set names utf8;

-- ----------------------------
-- system_group_game_priv 角色下的游戏特殊权限，不设置即没有权限
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_group_game_priv`;
CREATE TABLE `s{{SID}}_system_group_game_priv` (
  `sggp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int (11) NOT NULL COMMENT '角色id',
  `game_id` int (11) NOT NULL COMMENT '游戏id',
  `priv_id` int (11) NOT NULL COMMENT '权限id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sggp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
