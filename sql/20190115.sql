use integration_background;
set names utf8;

-- 增加系统活跃图标字段
alter table `img` add `content` text default '' comment '图片base64内容' after `url_path`;
