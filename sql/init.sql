use admin_client;
set names utf8;

-- ----------------------------
-- img 上传图片的model
-- ----------------------------
DROP TABLE IF EXISTS `img`;
CREATE TABLE `img` (
`img_id`  int(11) NOT NULL AUTO_INCREMENT ,
`url_path`  varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片的目录地址，不包含域名' ,
`width`  mediumint(8) NOT NULL DEFAULT 0 COMMENT '宽度' ,
`height`  mediumint(8) NOT NULL DEFAULT 0 COMMENT '宽度' ,
`type`  tinyint(2) NOT NULL DEFAULT 0 COMMENT '图片使用处，1 反馈上传' ,
`status`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片使用状态，0 为正常 1 为已弃用' ,
`created_at`  int(11) NOT NULL ,
`updated_at`  int(11) NOT NULL ,
PRIMARY KEY (`img_id`),
INDEX `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ----------------------------
-- news 菜单示例
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar (11) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar (516) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint (1) NOT NULL DEFAULT 0 COMMENT '新闻状态 0已发布 1待发布 2已禁用',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

