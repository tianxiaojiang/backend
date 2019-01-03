use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for system_group_game
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_group_game`;
CREATE TABLE `s{{SID}}_system_group_game` (
  `sggid` int(11) NOT NULL AUTO_INCREMENT,
  `sg_id` int (11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `game_id` int (11) NOT NULL DEFAULT 0 COMMENT '游戏id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sggid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;