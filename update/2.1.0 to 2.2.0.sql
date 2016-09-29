ALTER TABLE `cmf_menu` CHANGE `app` `app` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '应用名称app';
ALTER TABLE `cmf_menu` CHANGE `model` `model` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '控制器';
ALTER TABLE `cmf_menu` CHANGE `action` `action` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '操作名称';
ALTER TABLE `cmf_menu` CHANGE `data` `data` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '额外参数';
ALTER TABLE `cmf_menu` CHANGE `remark` `remark` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注';