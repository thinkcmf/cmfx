--
--把tableprefix_换你的表前缀再执行;
--

ALTER TABLE `tableprefix_users` CHANGE `birthday` `birthday` DATE NULL DEFAULT '2000-01-01' COMMENT '生日';