use integration_background;
set names utf8;

DROP TABLE IF EXISTS `s{{SID}}_admin`;
CREATE TABLE `s{{SID}}_admin` (
  `system_ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ad_uid` int (11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `token_id` char (32) NOT NULL DEFAULT '' COMMENT '业务后台token_id',
  `setting_token_id` char (32) NOT NULL DEFAULT '' COMMENT '维护后台token_id',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`system_ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;