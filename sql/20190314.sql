use integration_background;
set names utf8;

-- 设置独立权限
alter table `s1_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s2_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s3_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s4_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s5_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s6_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s7_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
alter table `s8_system_group` add `kind` tinyint(1) not null default 0 comment "角色类型 0,普通角色 1,独有角色" after `privilege_level`;
