use integration_background;
set names utf8;

/*
    权限字段增加排序功能
*/

ALTER TABLE `s1_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s2_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s3_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s4_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s5_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s6_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s7_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s8_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s9_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s10_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s11_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s12_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s13_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
ALTER TABLE `s14_system_priv` add `sort_by` tinyint(1) unsigned NOT NULL default 0 COMMENT "排序号码" AFTER `sp_parent_id`;
