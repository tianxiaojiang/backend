use integration_background;
set names utf8;

alter table `systems` add `allow_api_call` tinyint(1) not null default 0 comment '是否允许通过接口调用直接授权或修改密码' after `status`;

-- ----------------------------
-- Table structure for game
-- ----------------------------
DROP TABLE IF EXISTS `s1_game`;
CREATE TABLE `s1_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s2_game`;
CREATE TABLE `s2_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s3_game`;
CREATE TABLE `s3_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s4_game`;
CREATE TABLE `s4_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s5_game`;
CREATE TABLE `s5_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `s6_game`;
CREATE TABLE `s6_game` (
  `game_id` int(11) NOT NULL,
  `order_by` smallint(4) NOT NULL DEFAULT 0 COMMENT '显示排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----------------------------
-- s1_admin 子系统对应的管理员
-- ----------------------------
DROP TABLE IF EXISTS `s1_admin`;
CREATE TABLE `s1_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s2_admin`;
CREATE TABLE `s2_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s3_admin`;
CREATE TABLE `s3_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s4_admin`;
CREATE TABLE `s4_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s5_admin`;
CREATE TABLE `s5_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `s6_admin`;
CREATE TABLE `s6_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;