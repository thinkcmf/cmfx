/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : spcms

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2014-05-21 22:27:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sp_access`
-- ----------------------------
DROP TABLE IF EXISTS `sp_access`;
CREATE TABLE `sp_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `g` varchar(20) NOT NULL COMMENT '项目',
  `m` varchar(20) NOT NULL COMMENT '模块',
  `a` varchar(20) NOT NULL COMMENT '方法',
  KEY `groupId` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_ad`
-- ----------------------------
DROP TABLE IF EXISTS `sp_ad`;
CREATE TABLE `sp_ad` (
  `ad_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(255) NOT NULL,
  `ad_content` text,
  `status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_ad
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_admin_panel`
-- ----------------------------
DROP TABLE IF EXISTS `sp_admin_panel`;
CREATE TABLE `sp_admin_panel` (
  `menuid` mediumint(8) unsigned NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(32) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `datetime` int(10) unsigned DEFAULT '0',
  UNIQUE KEY `userid` (`menuid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_admin_panel
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_asset`
-- ----------------------------
DROP TABLE IF EXISTS `sp_asset`;
CREATE TABLE `sp_asset` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT,
  `_unique` varchar(14) NOT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  `filepath` varchar(200) NOT NULL,
  `uploadtime` int(11) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `meta` text,
  `suffix` varchar(50) DEFAULT NULL,
  `download_times` int(6) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_asset
-- ----------------------------


-- ----------------------------
-- Table structure for `sp_comments`
-- ----------------------------
DROP TABLE IF EXISTS `sp_comments`;
CREATE TABLE `sp_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_table` varchar(100) NOT NULL,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `to_uid` int(11) NOT NULL DEFAULT '0',
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text NOT NULL,
  `type` smallint(1) NOT NULL DEFAULT '1',
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `comment_post_ID` (`post_id`),
  KEY `comment_approved_date_gmt` (`status`),
  KEY `comment_parent` (`parentid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_guestbook`
-- ----------------------------
DROP TABLE IF EXISTS `sp_guestbook`;
CREATE TABLE `sp_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `msg` text NOT NULL,
  `createtime` datetime NOT NULL,
  `status` smallint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_guestbook
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_links`
-- ----------------------------
DROP TABLE IF EXISTS `sp_links`;
CREATE TABLE `sp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '_blank',
  `link_description` text NOT NULL,
  `link_status` int(2) NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_rel` varchar(255) DEFAULT '',
  `listorder` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_links
-- ----------------------------
INSERT INTO `sp_links` VALUES ('1', 'http://www.thinkcmf.com', 'ThinkCMF', '', '_blank', '', '1', '0', '', '0');

-- ----------------------------
-- Table structure for `sp_members`
-- ----------------------------
DROP TABLE IF EXISTS `sp_members`;
CREATE TABLE `sp_members` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login_name` varchar(25) NOT NULL,
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nickname` varchar(50) NOT NULL,
  `user_pic_url` varchar(300) NOT NULL,
  `user_pic_assetid` int(8) NOT NULL,
  `sign_words` varchar(200) NOT NULL,
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `last_login_ip` varchar(16) NOT NULL,
  `last_login_time` int(12) NOT NULL,
  `create_time` int(12) NOT NULL,
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `user_nicename` (`user_nickname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_members
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_menu`
-- ----------------------------
DROP TABLE IF EXISTS `sp_menu`;
CREATE TABLE `sp_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `app` char(20) NOT NULL COMMENT '应用名称app',
  `model` char(20) NOT NULL DEFAULT '',
  `action` char(20) NOT NULL DEFAULT '',
  `data` char(50) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(50) DEFAULT NULL,
  `remark` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parentid`),
  KEY `model` (`model`)
) ENGINE=MyISAM AUTO_INCREMENT=567 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_menu
-- ----------------------------
INSERT INTO `sp_menu` VALUES ('239', '0', 'Admin', 'Setting', 'default', '', '0', '1', '设置', 'cogs', '', '0');
INSERT INTO `sp_menu` VALUES ('308', '307', 'Wx', 'Answeradmin', 'index', '', '1', '1', '默认回复', '', '', '0');
INSERT INTO `sp_menu` VALUES ('309', '307', 'Wx', 'Answeradmin', 'fixed', '', '1', '1', '固定回复', '', '', '0');
INSERT INTO `sp_menu` VALUES ('310', '307', 'Wx', 'Answeradmin', 'robot', '', '1', '1', '智能回复', '', '', '0');
INSERT INTO `sp_menu` VALUES ('51', '0', 'Admin', 'Content', 'default', '', '0', '1', '内容管理', 'th', '', '10');
INSERT INTO `sp_menu` VALUES ('307', '305', 'Wx', 'Answeradmin', 'index', '', '1', '1', '回复设置', '', '', '20');
INSERT INTO `sp_menu` VALUES ('245', '51', 'Admin', 'Term', 'index', '', '0', '1', '分类管理', '', '', '2');
INSERT INTO `sp_menu` VALUES ('314', '312', 'Wx', 'Collectadmin', 'answer', '', '1', '1', '回复数量', '', '', '0');
INSERT INTO `sp_menu` VALUES ('299', '260', 'Api', 'Oauthadmin', 'setting', '', '1', '1', '第三方登陆', 'leaf', '', '4');
INSERT INTO `sp_menu` VALUES ('252', '239', 'Admin', 'Setting', 'userdefault', '', '0', '1', '个人信息', '', '', '0');
INSERT INTO `sp_menu` VALUES ('253', '252', 'Admin', 'User', 'userinfo', '', '1', '1', '修改信息', '', '', '0');
INSERT INTO `sp_menu` VALUES ('254', '252', 'Admin', 'Setting', 'password', '', '1', '1', '修改密码', '', '', '0');
INSERT INTO `sp_menu` VALUES ('313', '312', 'Wx', 'Collectadmin', 'location', '', '1', '1', '地理位置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('260', '0', 'Admin', 'Extension', 'default', '', '0', '1', '扩展工具', 'cloud', '', '30');
INSERT INTO `sp_menu` VALUES ('262', '260', 'Admin', 'Slide', 'default', '', '1', '1', '幻灯片', '', '', '1');
INSERT INTO `sp_menu` VALUES ('264', '262', 'Admin', 'Slide', 'index', '', '1', '1', '幻灯片管理', '', '', '0');
INSERT INTO `sp_menu` VALUES ('265', '260', 'Admin', 'Ad', 'index', '', '1', '1', '网站广告', '', '', '2');
INSERT INTO `sp_menu` VALUES ('317', '312', 'Wx', 'Collectadmin', 'users', '', '1', '1', '关注趋势', '', '', '0');
INSERT INTO `sp_menu` VALUES ('316', '312', 'Wx', 'Collectadmin', 'userlist', '', '1', '1', '粉丝列表', '', '', '0');
INSERT INTO `sp_menu` VALUES ('268', '262', 'Admin', 'Slidecat', 'index', '', '1', '1', '幻灯片分类', '', '', '0');
INSERT INTO `sp_menu` VALUES ('270', '260', 'Admin', 'Link', 'index', '', '0', '1', '友情链接', '', '', '3');
INSERT INTO `sp_menu` VALUES ('312', '305', 'Wx', 'Collectadmin', 'location', '', '1', '1', '数据分析', '', '', '30');
INSERT INTO `sp_menu` VALUES ('277', '51', 'Admin', 'Page', 'index', '', '1', '1', '页面管理', '', '', '3');
INSERT INTO `sp_menu` VALUES ('301', '300', 'Admin', 'Page', 'recyclebin', '', '1', '1', '页面回收', '', '', '1');
INSERT INTO `sp_menu` VALUES ('302', '300', 'Admin', 'Post', 'recyclebin', '', '1', '1', '文章回收', '', '', '0');
INSERT INTO `sp_menu` VALUES ('305', '0', 'Wx', 'Indexadmin', 'default', '', '1', '1', '微信管理', 'tags', '', '20');
INSERT INTO `sp_menu` VALUES ('300', '51', 'Admin', 'Recycle', 'default', '', '1', '1', '回收站', '', '', '4');
INSERT INTO `sp_menu` VALUES ('284', '239', 'Admin', 'Setting', 'site', '', '1', '1', '网站信息', '', '', '0');
INSERT INTO `sp_menu` VALUES ('285', '51', 'Admin', 'Post', 'index', '', '1', '1', '文章管理', '', '', '1');
INSERT INTO `sp_menu` VALUES ('286', '0', 'Member', 'Indexadmin', 'default', '', '1', '1', '用户管理', 'group', '', '0');
INSERT INTO `sp_menu` VALUES ('287', '289', 'Member', 'Indexadmin', 'index', '', '1', '1', '本站用户', 'leaf', '', '0');
INSERT INTO `sp_menu` VALUES ('288', '289', 'Api', 'Oauthadmin', 'index', '', '1', '1', '第三方用户', 'leaf', '', '0');
INSERT INTO `sp_menu` VALUES ('289', '286', 'Member', 'Indexadmin', 'default1', '', '1', '1', '用户组', '', '', '0');
INSERT INTO `sp_menu` VALUES ('290', '286', 'Member', 'Indexadmin', 'default3', '', '1', '1', '管理组', '', '', '0');
INSERT INTO `sp_menu` VALUES ('291', '290', 'Admin', 'Rbac', 'index', '', '1', '1', '角色管理', '', '', '0');
INSERT INTO `sp_menu` VALUES ('292', '290', 'Admin', 'User', 'index', '', '1', '1', '管理员', '', '', '0');
INSERT INTO `sp_menu` VALUES ('293', '0', 'Admin', 'Menu', 'default', '', '1', '1', '菜单管理', 'list', '', '0');
INSERT INTO `sp_menu` VALUES ('294', '293', 'Admin', 'Navcat', 'default1', '', '1', '1', '前台菜单', '', '', '0');
INSERT INTO `sp_menu` VALUES ('295', '294', 'Admin', 'Nav', 'index', '', '1', '1', '菜单管理', '', '', '0');
INSERT INTO `sp_menu` VALUES ('296', '294', 'Admin', 'Navcat', 'index', '', '1', '1', '菜单分类', '', '', '0');
INSERT INTO `sp_menu` VALUES ('297', '293', 'Admin', 'Menu', 'index', '', '1', '1', '后台菜单', '', '', '0');
INSERT INTO `sp_menu` VALUES ('298', '239', 'Admin', 'Setting', 'clearcache', '', '1', '1', '清除缓存', '', '', '1');
INSERT INTO `sp_menu` VALUES ('318', '305', 'Wx', 'Menuadmin', 'index', '', '1', '1', '菜单管理', '', '', '30');
INSERT INTO `sp_menu` VALUES ('319', '260', 'Admin', 'Backup', 'default', '', '1', '1', '备份管理', '', '', '0');
INSERT INTO `sp_menu` VALUES ('480', '292', 'Admin', 'User', 'delete', '', '1', '0', '删除管理员', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('479', '292', 'Admin', 'User', 'edit', '', '1', '0', '编辑管理员', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('478', '292', 'Admin', 'User', 'add', '', '1', '0', '添加管理员', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('477', '245', 'Admin', 'Term', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('476', '245', 'Admin', 'Term', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('475', '245', 'Admin', 'Term', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('474', '268', 'Admin', 'Slidecat', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('473', '268', 'Admin', 'Slidecat', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('472', '268', 'Admin', 'Slidecat', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('471', '264', 'Admin', 'Slide', 'delete', '', '1', '0', '删除幻灯片', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('470', '264', 'Admin', 'Slide', 'edit', '', '1', '0', '编辑幻灯片', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('469', '264', 'Admin', 'Slide', 'add', '', '1', '0', '添加幻灯片', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('467', '291', 'Admin', 'Rbac', 'member', '', '1', '0', '成员管理', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('465', '291', 'Admin', 'Rbac', 'authorize', '', '1', '0', '权限设置', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('464', '291', 'Admin', 'Rbac', 'roleedit', '', '1', '0', '编辑角色', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('463', '291', 'Admin', 'Rbac', 'roledelete', '', '1', '1', '删除角色', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('462', '291', 'Admin', 'Rbac', 'roleadd', '', '1', '1', '添加角色', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('458', '302', 'Admin', 'Post', 'restore', '', '1', '0', '文章还原', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('457', '302', 'Admin', 'Post', 'clean', '', '1', '0', '彻底删除', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('456', '285', 'Admin', 'Post', 'move', '', '1', '0', '批量移动', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('455', '285', 'Admin', 'Post', 'check', '', '1', '0', '文章审核', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('454', '285', 'Admin', 'Post', 'delete', '', '1', '0', '删除文章', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('452', '285', 'Admin', 'Post', 'edit', '', '1', '0', '编辑文章', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('451', '285', 'Admin', 'Post', 'add', '', '1', '0', '添加文章', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('450', '301', 'Admin', 'Page', 'clean', '', '1', '0', '彻底删除', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('449', '301', 'Admin', 'Page', 'restore', '', '1', '0', '页面还原', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('448', '277', 'Admin', 'Page', 'delete', '', '1', '0', '删除页面', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('446', '277', 'Admin', 'Page', 'edit', '', '1', '0', '编辑页面', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('445', '277', 'Admin', 'Page', 'add', '', '1', '0', '添加页面', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('444', '296', 'Admin', 'Navcat', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('443', '296', 'Admin', 'Navcat', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('442', '296', 'Admin', 'Navcat', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('441', '295', 'Admin', 'Nav', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('440', '295', 'Admin', 'Nav', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('439', '295', 'Admin', 'Nav', 'add', '', '1', '0', '添加菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('436', '297', 'Admin', 'Menu', 'export_menu', '', '1', '0', '菜单备份', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('434', '297', 'Admin', 'Menu', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('433', '297', 'Admin', 'Menu', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('432', '297', 'Admin', 'Menu', 'lists', '', '1', '0', '所有菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('430', '270', 'Admin', 'Link', 'delete', '', '1', '0', '删除友情链接', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('429', '270', 'Admin', 'Link', 'edit', '', '1', '0', '编辑友情链接', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('428', '270', 'Admin', 'Link', 'add', '', '1', '0', '添加友情链接', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('424', '319', 'Admin', 'Backup', 'download', '', '1', '0', '下载备份', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('423', '319', 'Admin', 'Backup', 'del_backup', '', '1', '0', '删除备份', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('422', '319', 'Admin', 'Backup', 'import', '', '1', '0', '数据备份导入', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('421', '319', 'Admin', 'Backup', 'restore', '', '1', '1', '数据还原', '', '', '0');
INSERT INTO `sp_menu` VALUES ('420', '265', 'Admin', 'Ad', 'delete', '', '1', '0', '删除广告', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('419', '265', 'Admin', 'Ad', 'edit', '', '1', '0', '编辑广告', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('418', '265', 'Admin', 'Ad', 'add', '', '1', '0', '添加广告', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('482', '288', 'Api', 'Oauthadmin', 'delete', '', '1', '0', '删除第三方用户', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('483', '287', 'Member', 'Indexadmin', 'delete', '', '1', '0', '删除用户', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('488', '310', 'Wx', 'Answeradmin', 'split', '', '1', '0', '分词', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('490', '318', 'Wx', 'Menuadmin', 'createmenu', '', '1', '0', '生成微信菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('492', '318', 'Wx', 'Menuadmin', 'add', '', '1', '0', '添加微信菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('493', '318', 'Wx', 'Menuadmin', 'delete', '', '1', '0', '删除微信菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('494', '318', 'Wx', 'Menuadmin', 'edit', '', '1', '0', '编辑微信菜单', '', '', '1000');
INSERT INTO `sp_menu` VALUES ('495', '305', 'Wx', 'Indexadmin', 'index', '', '1', '1', '账号信息', '', '', '0');
INSERT INTO `sp_menu` VALUES ('496', '319', 'Admin', 'Backup', 'index', '', '1', '1', '数据备份', '', '', '0');
INSERT INTO `sp_menu` VALUES ('497', '418', 'Admin', 'Ad', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('498', '419', 'Admin', 'Ad', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('499', '428', 'Admin', 'Link', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('500', '429', 'Admin', 'Link', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('501', '536', 'Admin', 'Menu', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('502', '434', 'Admin', 'Menu', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('503', '439', 'Admin', 'Nav', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('504', '440', 'Admin', 'Nav', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('505', '442', 'Admin', 'Navcat', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('506', '443', 'Admin', 'Navcat', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('507', '445', 'Admin', 'Page', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('508', '446', 'Admin', 'Page', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('509', '451', 'Admin', 'Post', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('510', '452', 'Admin', 'Post', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('511', '462', 'Admin', 'Rbac', 'roleadd_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('512', '464', 'Admin', 'Rbac', 'roleedit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('513', '465', 'Admin', 'Rbac', 'authorize_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('514', '284', 'Admin', 'Setting', 'site_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `sp_menu` VALUES ('515', '254', 'Admin', 'Setting', 'password_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `sp_menu` VALUES ('516', '469', 'Admin', 'Slide', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('517', '470', 'Admin', 'Slide', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('518', '472', 'Admin', 'Slidecat', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('519', '473', 'Admin', 'Slidecat', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('520', '475', 'Admin', 'Term', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `sp_menu` VALUES ('521', '476', 'Admin', 'Term', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('522', '478', 'Admin', 'User', 'add_post', '', '1', '0', '提交保存', '', '', '0');
INSERT INTO `sp_menu` VALUES ('523', '479', 'Admin', 'User', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `sp_menu` VALUES ('524', '253', 'Admin', 'User', 'userinfo_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `sp_menu` VALUES ('525', '299', 'Api', 'Oauthadmin', 'setting_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('526', '533', 'Admin', 'Mailer', 'index', '', '1', '1', 'SMTP配置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('527', '526', 'Admin', 'Mailer', 'index_post', '', '1', '0', '提交配置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('528', '533', 'Admin', 'Mailer', 'active', '', '1', '1', '邮件模板', '', '', '0');
INSERT INTO `sp_menu` VALUES ('529', '528', 'Admin', 'Mailer', 'active_post', '', '1', '0', '提交模板', '', '', '0');
INSERT INTO `sp_menu` VALUES ('533', '239', 'Admin', 'Mailer', 'default', '', '1', '1', '邮箱配置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('538', '308', 'Wx', 'Answeradmin', 'index_post', '', '1', '0', '默认回复和欢迎辞设置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('536', '297', 'Admin', 'Menu', 'add', '', '1', '0', '添加菜单', '', '', '0');
INSERT INTO `sp_menu` VALUES ('539', '309', 'Wx', 'Answeradmin', 'fixed_post', '', '1', '0', '固定回答设置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('541', '310', 'Wx', 'Answeradmin', 'robot_post', '', '1', '0', '机器人问题集设置', '', '', '0');
INSERT INTO `sp_menu` VALUES ('542', '495', 'Wx', 'Indexadmin', 'index_post', '', '1', '0', '账号信息修改提交', '', '', '0');
INSERT INTO `sp_menu` VALUES ('543', '492', 'Wx', 'Menuadmin', 'add_post', '', '1', '0', ' 添加微信菜单提交', '', '', '0');
INSERT INTO `sp_menu` VALUES ('544', '494', 'Wx', 'Menuadmin', 'edit_post', '', '1', '0', '提交编辑微信菜单', '', '', '0');
INSERT INTO `sp_menu` VALUES ('546', '496', 'Admin', 'Backup', 'index_post', '', '1', '0', '提交数据备份', '', '', '0');
INSERT INTO `sp_menu` VALUES ('547', '270', 'Admin', 'Link', 'listorders', '', '1', '0', '友情链接排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('548', '297', 'Admin', 'Menu', 'listorders', '', '1', '0', '后台菜单排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('549', '295', 'Admin', 'Nav', 'listorders', '', '1', '0', '前台导航排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('550', '277', 'Admin', 'Page', 'listorders', '', '1', '0', '页面排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('551', '285', 'Admin', 'Post', 'listorders', '', '1', '0', '文章排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('552', '264', 'Admin', 'Slide', 'listorders', '', '1', '0', '幻灯片排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('553', '245', 'Admin', 'Term', 'listorders', '', '1', '0', '文章分类排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('554', '51', 'Api', 'Guestbookadmin', 'index', '', '1', '1', '所有留言', '', '', '0');
INSERT INTO `sp_menu` VALUES ('555', '554', 'Api', 'Guestbookadmin', 'delete', '', '1', '0', '删除网站留言', '', '', '0');
INSERT INTO `sp_menu` VALUES ('556', '318', 'Wx', 'Menuadmin', 'listorders', '', '1', '0', '微信菜单排序', '', '', '0');
INSERT INTO `sp_menu` VALUES ('557', '51', 'Comment', 'Commentadmin', 'index', '', '1', '1', '评论管理', '', '', '0');
INSERT INTO `sp_menu` VALUES ('559', '557', 'Comment', 'Commentadmin', 'delete', '', '1', '0', '删除评论', '', '', '0');
INSERT INTO `sp_menu` VALUES ('560', '557', 'Comment', 'Commentadmin', 'check', '', '1', '0', '评论审核', '', '', '0');

-- ----------------------------
-- Table structure for `sp_nav`
-- ----------------------------
DROP TABLE IF EXISTS `sp_nav`;
CREATE TABLE `sp_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `target` varchar(50) DEFAULT NULL,
  `href` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `listorder` int(6) DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_nav
-- ----------------------------

INSERT INTO `sp_nav` VALUES ('1', '1', '0', '首页', '', 'home', '', '1', '0', '0-1');

-- ----------------------------
-- Table structure for `sp_nav_cat`
-- ----------------------------
DROP TABLE IF EXISTS `sp_nav_cat`;
CREATE TABLE `sp_nav_cat` (
  `navcid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `remark` text,
  PRIMARY KEY (`navcid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_nav_cat
-- ----------------------------
INSERT INTO `sp_nav_cat` VALUES ('1', '主导航', '1', '主导航');

-- ----------------------------
-- Table structure for `sp_oauth_member`
-- ----------------------------
DROP TABLE IF EXISTS `sp_oauth_member`;
CREATE TABLE `sp_oauth_member` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `_from` varchar(20) NOT NULL,
  `_name` varchar(30) NOT NULL,
  `head_img` varchar(200) NOT NULL,
  `lock_to_id` int(20) NOT NULL,
  `create_time` int(12) NOT NULL,
  `last_login_time` int(12) NOT NULL,
  `last_login_ip` varchar(16) NOT NULL,
  `login_times` int(6) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `access_token` varchar(60) NOT NULL,
  `expires_date` int(12) NOT NULL,
  `openid` varchar(40) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_options`
-- ----------------------------
DROP TABLE IF EXISTS `sp_options`;
CREATE TABLE `sp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_options
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_postmeta`
-- ----------------------------
DROP TABLE IF EXISTS `sp_postmeta`;
CREATE TABLE `sp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_postmeta
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_posts`
-- ----------------------------
DROP TABLE IF EXISTS `sp_posts`;
CREATE TABLE `sp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned DEFAULT '0',
  `post_keywords` varchar(150) NOT NULL,
  `post_date` datetime DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext,
  `post_title` text,
  `post_excerpt` text,
  `post_status` int(2) DEFAULT '1',
  `comment_status` int(2) DEFAULT '1',
  `post_modified` datetime DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext,
  `post_parent` bigint(20) unsigned DEFAULT '0',
  `post_type` int(2) DEFAULT NULL,
  `post_mime_type` varchar(100) DEFAULT '',
  `comment_count` bigint(20) DEFAULT '0',
  `smeta` text,
  PRIMARY KEY (`ID`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_role`
-- ----------------------------
DROP TABLE IF EXISTS `sp_role`;
CREATE TABLE `sp_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `listorder` int(3) NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_role
-- ----------------------------
INSERT INTO `sp_role` VALUES ('1', '超级管理员', '0', '1', '拥有网站最高管理员权限！', '1329633709', '1329633709', '0');

-- ----------------------------
-- Table structure for `sp_role_user`
-- ----------------------------
DROP TABLE IF EXISTS `sp_role_user`;
CREATE TABLE `sp_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_slide`
-- ----------------------------
DROP TABLE IF EXISTS `sp_slide`;
CREATE TABLE `sp_slide` (
  `slide_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slide_cid` bigint(20) NOT NULL,
  `slide_name` varchar(255) NOT NULL,
  `slide_pic` varchar(255) DEFAULT NULL,
  `slide_url` varchar(255) DEFAULT NULL,
  `slide_des` varchar(255) DEFAULT NULL,
  `slide_content` text,
  `slide_status` int(2) NOT NULL DEFAULT '1',
  `listorder` int(10) DEFAULT '0',
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_slide
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_slide_cat`
-- ----------------------------
DROP TABLE IF EXISTS `sp_slide_cat`;
CREATE TABLE `sp_slide_cat` (
  `cid` bigint(20) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_idname` varchar(255) NOT NULL,
  `cat_remark` text,
  `cat_status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_slide_cat
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_terms`
-- ----------------------------
DROP TABLE IF EXISTS `sp_terms`;
CREATE TABLE `sp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT '',
  `slug` varchar(200) DEFAULT '',
  `taxonomy` varchar(32) DEFAULT '',
  `description` longtext,
  `parent` bigint(20) unsigned DEFAULT '0',
  `count` bigint(20) DEFAULT '0',
  `path` varchar(500) DEFAULT NULL,
  `seo_title` varchar(500) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `list_tpl` varchar(50) DEFAULT NULL,
  `one_tpl` varchar(50) DEFAULT NULL,
  `listorder` int(5) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`term_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `sp_terms` VALUES ('1', '未分类', '', 'article', '', '0', '0', '0-1', '', '', '', 'list', 'article', '0', '1');

-- ----------------------------
-- Table structure for `sp_term_relationships`
-- ----------------------------
DROP TABLE IF EXISTS `sp_term_relationships`;
CREATE TABLE `sp_term_relationships` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `listorder` int(10) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tid`),
  KEY `term_taxonomy_id` (`term_id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `sp_usermeta`
-- ----------------------------
DROP TABLE IF EXISTS `sp_usermeta`;
CREATE TABLE `sp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_usermeta
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_users`
-- ----------------------------
DROP TABLE IF EXISTS `sp_users`;
CREATE TABLE `sp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `last_login_ip` varchar(16) NOT NULL,
  `last_login_time` datetime NOT NULL,
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '1',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `role_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `sp_wx_answer`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_answer`;
CREATE TABLE `sp_wx_answer` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `_key` varchar(30) NOT NULL,
  `_value` text NOT NULL,
  `belong` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_wx_answer
-- ----------------------------
INSERT INTO `sp_wx_answer` VALUES ('5', '1', '回复天气+城市名称查询当地近三天天气情况，如“天气上海”，支持语音查询', 'gh_ef34a6c9f774');
INSERT INTO `sp_wx_answer` VALUES ('9', '2', '回复快递+快递名称+快递单号查询快递详情，如“快递申通768277296108”，支持语音查询', '');
INSERT INTO `sp_wx_answer` VALUES ('10', '3', '直接提交查询内容即可，支持语音哦。.', '');

-- ----------------------------
-- Table structure for `sp_wx_answer_robot`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_answer_robot`;
CREATE TABLE `sp_wx_answer_robot` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `question` varchar(50) NOT NULL,
  `answer` text NOT NULL,
  `key1` varchar(20) NOT NULL,
  `key2` varchar(20) NOT NULL,
  `key3` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_wx_answer_robot
-- ----------------------------
INSERT INTO `sp_wx_answer_robot` VALUES ('2', '你真可爱', '谢谢夸奖', '可爱', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('3', '你真聪明', '谢谢夸奖', '聪明', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('4', '你叫什么名字', '我叫小T，是个智能机器人，我可是很聪明的哦~', '你', '什么', '名字');
INSERT INTO `sp_wx_answer_robot` VALUES ('5', '你几岁了', '我才1岁，是个小萝莉', '你', '几', '岁');
INSERT INTO `sp_wx_answer_robot` VALUES ('6', '你好', '你好啊', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('7', '你好啊', '你好', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('8', '你吃饭了吗', '亲，您搞错了吧？我是不需要吃饭的', '你', '吃饭', '吗');
INSERT INTO `sp_wx_answer_robot` VALUES ('9', '你吃过了吗', '亲，您搞错了吧？俺是不需要吃饭滴', '你', '吃', '吗');
INSERT INTO `sp_wx_answer_robot` VALUES ('10', '你的爸爸妈妈呢', '我没有', '你', '爸爸', '妈妈');
INSERT INTO `sp_wx_answer_robot` VALUES ('11', '我喜欢你', '谢谢，我也喜欢你', '我喜欢', '你', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('12', '我爱你', '我也爱你', '我爱你', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('13', '你家在哪', '嘻嘻，我才不要告诉你呢', '你家', '在', '哪');
INSERT INTO `sp_wx_answer_robot` VALUES ('14', '你在干嘛', '我在认真工作', '你', '在', '嘛');
INSERT INTO `sp_wx_answer_robot` VALUES ('15', '你在做什么', '这都看不出来？我在认真工作', '你', '做', '什么');
INSERT INTO `sp_wx_answer_robot` VALUES ('16', '现在几点了', '你看看手机就知道了', '现在', '几', '点');
INSERT INTO `sp_wx_answer_robot` VALUES ('17', '王八蛋', '请不要说脏话', '王八', '蛋', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('18', '我感冒了', '呜呜~那我劝你多喝水，多运动。祝你早日康复！', '我', '感冒', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('19', '我生病了', '那你八成缺乏锻炼，记得多出去走走哦', '我', '生病', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('20', '呵呵', '你是在嘲笑我吗', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('21', '嘿嘿', '笑的让我很费解', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('22', '哈哈', '你在笑什么？没有什么好笑的吧', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('23', 'hello', 'hello !', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('24', 'hi', 'hi', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('25', '嗨', '嗨，您好啊！', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('26', '在吗？', '您好，在的', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('27', '在吗', '您好，在的', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('28', '有人在吗', '您好，在的', '', '', '');
INSERT INTO `sp_wx_answer_robot` VALUES ('29', '最近忙吗', '恩，老加班', '最近', '忙', '吗');

-- ----------------------------
-- Table structure for `sp_wx_answer_third`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_answer_third`;
CREATE TABLE `sp_wx_answer_third` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `question` varchar(50) NOT NULL,
  `answer` text NOT NULL,
  `key1` varchar(20) NOT NULL,
  `key2` varchar(20) NOT NULL,
  `key3` varchar(20) NOT NULL,
  `belong` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_wx_answer_third
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_wx_config`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_config`;
CREATE TABLE `sp_wx_config` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `_key` varchar(30) NOT NULL,
  `_value` text NOT NULL,
  `belong` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_wx_config
-- ----------------------------
INSERT INTO `sp_wx_config` VALUES ('1', 'ANSWER_DEFAULT_1', '回复数字编号查询以下内容：\n1.天气查询\n2.快递查询\n3.智能客服', 'gh_ef34a6c9f774');
INSERT INTO `sp_wx_config` VALUES ('2', 'ANSWER_WELCOME', '走过路过，幸好您没错过。精彩从此刻开始，谢谢您的关注！', 'gh_ef34a6c9f774');
INSERT INTO `sp_wx_config` VALUES ('3', 'ANSWER_DEFAULT_2', '回复数字编号查询以下内容：\n1.天气查询\n2.快递查询\n3.智能客服', 'gh_ef34a6c9f774');
INSERT INTO `sp_wx_config` VALUES ('4', 'ANSWER_DEFAULT_3', '呸呸呸，你说的我完全没听懂', 'gh_ef34a6c9f774');
INSERT INTO `sp_wx_config` VALUES ('5', 'ANSWER_DEFAULT_4', '装作没听懂......', 'gh_ef34a6c9f774');
INSERT INTO `sp_wx_config` VALUES ('11', 'WX_NUM', 'thinkcmf', '');
INSERT INTO `sp_wx_config` VALUES ('12', 'WX_INI_NUM', ' ', '');
INSERT INTO `sp_wx_config` VALUES ('13', 'WX_TOKEN', ' ', '');
INSERT INTO `sp_wx_config` VALUES ('14', 'WX_APPID', ' ', '');
INSERT INTO `sp_wx_config` VALUES ('15', 'WX_APPSECRET', ' ', '');
INSERT INTO `sp_wx_config` VALUES ('16', 'WX_ACCESS_TOKEN', '', '');

-- ----------------------------
-- Table structure for `sp_wx_menu`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_menu`;
CREATE TABLE `sp_wx_menu` (
  `menu_id` int(5) NOT NULL AUTO_INCREMENT,
  `menu_type` varchar(10) DEFAULT NULL,
  `menu_name` varchar(10) NOT NULL,
  `event_key` varchar(200) NOT NULL,
  `view_url` varchar(300) NOT NULL,
  `parentid` int(5) NOT NULL DEFAULT '0',
  `listorder` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_wx_message_image`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_message_image`;
CREATE TABLE `sp_wx_message_image` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(20) NOT NULL,
  `from` varchar(30) NOT NULL,
  `to` varchar(30) NOT NULL,
  `url` text NOT NULL,
  `time` int(12) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_wx_message_image
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_wx_message_location`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_message_location`;
CREATE TABLE `sp_wx_message_location` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(20) NOT NULL,
  `from` varchar(30) NOT NULL,
  `to` varchar(30) NOT NULL,
  `location_x` double NOT NULL,
  `location_y` double NOT NULL,
  `province` varchar(7) NOT NULL,
  `scale` int(3) NOT NULL,
  `label` varchar(200) NOT NULL,
  `time` int(12) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_wx_message_text`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_message_text`;
CREATE TABLE `sp_wx_message_text` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(20) NOT NULL,
  `from` varchar(30) NOT NULL,
  `to` varchar(30) NOT NULL,
  `content` varchar(150) NOT NULL,
  `time` int(12) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_wx_message_voice`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_message_voice`;
CREATE TABLE `sp_wx_message_voice` (
  `voice_id` int(10) NOT NULL AUTO_INCREMENT,
  `message_id` int(64) NOT NULL,
  `media_id` varchar(20) NOT NULL,
  `from` varchar(30) NOT NULL,
  `to` varchar(30) NOT NULL,
  `text` varchar(100) NOT NULL,
  `format` varchar(10) NOT NULL,
  `time` int(12) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`voice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `sp_wx_user`
-- ----------------------------
DROP TABLE IF EXISTS `sp_wx_user`;
CREATE TABLE `sp_wx_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `openid` varchar(30) NOT NULL,
  `subscribe_time` int(12) NOT NULL,
  `nickname` varchar(80) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `language` varchar(30) NOT NULL,
  `city` varchar(10) NOT NULL,
  `province` varchar(30) NOT NULL,
  `country` varchar(15) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `unsubscribe_time` int(12) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `belong` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

