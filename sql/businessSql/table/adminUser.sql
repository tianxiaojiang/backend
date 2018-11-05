use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_admin_user`;
CREATE TABLE `s{{SID}}_admin_user` (
  `ad_uid` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL DEFAULT '' COMMENT '账号',
  `passwd` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(4) NOT NULL DEFAULT '' COMMENT '加密混淆',
  `mobile_phone` char(16) NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(16) NOT NULL DEFAULT '' COMMENT '用户名称',
  `access_token` CHAR (64) NOT NULL DEFAULT '' COMMENT '访问token',
  `access_token_expire` int (11) NOT NULL DEFAULT 0 COMMENT 'token过期时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '管理员状态 1、正常 2、禁用',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`ad_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
