use integration_background;
set names utf8;

-- ----------------------------
-- Table structure for s1_system_priv
-- ----------------------------
DROP TABLE IF EXISTS `s{{SID}}_system_priv`;
CREATE TABLE `s{{SID}}_system_priv` (
  `sp_id` int(11) NOT NULL AUTO_INCREMENT,
  `sm_id` int(11) NOT NULL DEFAULT 0 COMMENT '菜单id',
  `sp_label` varchar(16) NOT NULL DEFAULT '' COMMENT '权限名称',
  `sp_module` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应模块',
  `sp_controller` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应控制器',
  `sp_action` varchar(32) NOT NULL DEFAULT '' COMMENT '权限对应方法',
  `sp_parent_id` int (11) NOT NULL DEFAULT 0 COMMENT '权限父id',
  `sp_set_or_business` tinyint (1) NOT NULL DEFAULT 0 COMMENT '权限属于系统设置还是业务，默认0：业务，1：系统设置',
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`sp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
