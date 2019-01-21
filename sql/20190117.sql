use integration_background;
set names utf8;

-- 增加系统白名单登录账户
-- ----------------------------
-- login_white_list 登录免验证码白名单
-- ----------------------------
DROP TABLE IF EXISTS `login_white_list`;
CREATE TABLE `login_white_list` (
  `login_white_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar (32) NOT NULL DEFAULT '' COMMENT '账号',
  `type` tinyint (1) NOT NULL DEFAULT 0 COMMENT '账号类型 0、域账号，1、普通账号，2、常州账号',
  `created_at` datetime,
  `updated_at` datetime,
  unique key `account_type`(`account`, `type`),
  PRIMARY KEY (`login_white_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
