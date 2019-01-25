use integration_background;
set names utf8;

INSERT INTO `s{{SID}}_system_priv` VALUES ('35', '9', '游戏列表', 'main', 'main', 'main', '1', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('36', '9', '游戏列表', 'admin', 'system-game', 'index', '35', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');
INSERT INTO `s{{SID}}_system_priv` VALUES ('37', '9', '设置游戏', 'admin', 'system-game', 'create', '35', '1', '{{CREATED_AT}}', '{{UPDATED_AT}}');