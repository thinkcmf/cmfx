--
--把tableprefix_换你的表前缀再执行;
--

ALTER TABLE  `tableprefix_asset` ADD  `uid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '用户 id' AFTER  `aid` ;

--
--tableprefix_users表
--
ALTER TABLE  `tableprefix_users` ADD  `coin` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '金币';
ALTER TABLE  `tableprefix_users` ADD  `mobile` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT  '手机号',
ADD INDEX (  `mobile` );