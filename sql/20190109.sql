use integration_background;
set names utf8;

-- 增加系统活跃图标字段
alter table `systems` add `active_img_id` int(11) UNSIGNED default 0 comment '系统的鼠标hover图标' after `img_id`;
