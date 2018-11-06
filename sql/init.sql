use admin_client;
set names utf8;

-- ----------------------------
-- news 菜单示例
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar (11) NOT NULL DEFAULT '' COMMENT '标题',
  `descrption` varchar (516) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint (1) NOT NULL DEFAULT 0 COMMENT '新闻状态 0已发布 1待发布 2已禁用',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;