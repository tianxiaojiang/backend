use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for s1_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_menu`;
CREATE TABLE `s{{SID}}_system_menu` (
  `sm_id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_label` char(16) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `sm_view` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单前端对应目录',
  `sm_icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单logo',
  `sort_by` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show_sidebar` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否显示',
  `sm_status` int(1) NOT NULL DEFAULT 0 COMMENT '菜单状态',
  `sm_parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父菜单id',
  `sm_set_or_business` tinyint (1) NOT NULL DEFAULT 0 COMMENT '菜单属于系统设置还是业务，默认0：业务，1：系统设置',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;