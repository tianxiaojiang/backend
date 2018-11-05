use integration_background;
set names utf8;

-- ----------------------------
-- system_group_game 角色管辖下的游戏
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_group_game`;
CREATE TABLE `s{{SID}}_system_group_game` (
  `system_group_game_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` char (8) NOT NULL DEFAULT '*' COMMENT '游戏id，*表示角色为不限制游戏',
  `group_id` int (11) NOT NULL COMMENT '角色id',
  `is_proprietary_priv` tinyint (1) NOT NULL DEFAULT 0 COMMENT '是否是专有权限，0非，1是',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_group_game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;








