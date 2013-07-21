USE ser;

DROP TABLE IF EXISTS `data_file`;
CREATE TABLE `data_file` (
  `id` int(11) NOT NULL auto_increment,
  `data_id` int(11) default NULL,
  `file_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312;

DROP TABLE IF EXISTS `data_profile`;
CREATE TABLE `data_profile` (
  `id` int(11) NOT NULL auto_increment,    
  `data_id` int(11) default NULL,
  `user_id` int(11) default NULL,  
  `integrity` decimal(10,2) default NULL,
  `support_analysis` varchar(255) default NULL,
  `organization_scope` varchar(255) default NULL,
  `project_scope` varchar(255) default NULL,
  `research_area` varchar(255) default NULL,
  `life_cycle_phase` varchar(255) default NULL,
  `architecture` varchar(255) default NULL,
  `design_technique` varchar(255) default NULL,
  `size_technique` varchar(255) default NULL,
  `effort` varchar(255) default NULL,
  `time` varchar(255) default NULL,
  `member` varchar(255) default NULL,
  `size` varchar(255) default NULL,
  `defect` varchar(255) default NULL,
  `program_language` varchar(255) default NULL,
  `operating_system` varchar(255) default NULL,
  `database_system` varchar(255) default NULL,
  `development_site` varchar(255) default NULL,
  `development_technique` varchar(255) default NULL,
  `development_tool` varchar(255) default NULL,
  `development_platform` varchar(255) default NULL,
  `organization_type` varchar(255) default NULL,
  `business_area` varchar(255) default NULL,
  `application_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `file_desc`;
CREATE TABLE `file_desc` (
  `id` int(11) NOT NULL auto_increment,
  `original_filename` varchar(255) character set utf8 default NULL,
  `file_size` bigint(15) default NULL,
  `file_format` varchar(20) character set utf8 default NULL,
  `current_filename` varchar(255) default NULL,
  `creation_time` datetime default NULL,
  `update_time` datetime default NULL,
  `download_count` int(11) default NULL,  
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `authority_info`;
CREATE TABLE `authority_info` (
  `id` int(11) NOT NULL auto_increment,  
  `level` varchar(255) character set utf8 default NULL,  
  `upload` bit default NULL,  
  `download` bit default NULL,  
  `update_all` bit default NULL,  
  `update_own` bit default NULL,  
  `delete_all` bit default NULL,  
  `delete_own` bit default NULL,  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,  
  `username` varchar(255) character set utf8 default NULL,  
  `password` varchar(255) character set utf8 default NULL,  
  `auth_id` tinyint default NULL,  
  `affiliation` varchar(255) character set utf8 default NULL, 
  `title` varchar(20) character set utf8 default NULL,
  `email` varchar(255) character set utf8 default NULL,
  `interest` varchar(255) character set utf8 default NULL,  
  `country` varchar(255) character set utf8 default NULL, 
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
   `id` int(11) NOT NULL auto_increment,
   `email` varchar(255) character set utf8 default NULL,
   `title` varchar(255) character set utf8 default NULL,
   `content` tinyText character set utf8 default NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `download_record`;
CREATE TABLE `download_record` (
   `id` int(11) NOT NULL auto_increment,   
   `file_id` int(11) default NULL,   
   `download_time` datetime default NULL,   
   `ip` varchar(100) default NULL,   
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gb2312 ROW_FORMAT=FIXED;

INSERT INTO `users` VALUES (1, 'test', 'test', 1, '', '', 'eseg@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (2, 'yangda', 'yangda', 1, '', '', 'yangda@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (3, 'shufengdi', 'shufengdi', 1, '', '', 'fdshu@intec.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (4, 'wangxu', 'wangxu', 1, '', '', 'wangxu@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (5, 'dujing', 'dujing', 1, '', '', 'dujing@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (6, 'yangye', 'yangye', 1, '', '', 'yangye@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (7, 'zhanghaopeng', 'zhanghaopeng', 1, '', '', 'zhanghaopeng@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (8, 'chenxinguang', 'chenxinguang', 1, '', '', 'chenxinguang@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (9, 'wangdandan', 'wangdandan', 1, '', '', 'wangdandan@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (10, 'kuyan', 'kuyan', 1, '', '', 'kuyan@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (11, 'chenjia', 'chenjia', 1, '', '', 'chenjia@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (12, 'wuwenjin', 'wuwenjin', 1, '', '', 'wuwenjin@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (13, 'xielang', 'xielang', 1, '', '', 'xielang@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (14, 'hezhimin', 'hezhimin', 1, '', '', 'hezhimin@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (15, 'sunyueming', 'sunyueming', 1, '', '', 'sunyueming@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (16, 'liuwenpei', 'liuwenpei', 1, '', '', 'liuwenpei@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (17, 'zhangwen', 'zhangwen', 1, '', '', 'zhangwen@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (18, 'hujie', 'hujie', 1, '', '', 'hujie@itechs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (19, 'liuyanbin', 'liuyanbin', 1, '', '', 'yanbin@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (20, 'caolihua', 'caolihua', 1, '', '', 'lihua@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (21, 'liangran', 'liangran', 1, '', '', 'liangran@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (22, 'qirongbo', 'qirongbo', 1, '', '', 'rongbo@nfs.iscas.ac.cn', '', 'China');
INSERT INTO `users` VALUES (23, 'xiexihao', 'xiexihao', 1, '', '', 'xihao@nfs.iscas.ac.cn', '', 'China');

INSERT INTO `authority_info`(level,upload,download,update_all,update_own,delete_all,delete_own) VALUES ('advanced',true,true,true,true,true,true);
INSERT INTO `authority_info`(level,upload,download,update_all,update_own,delete_all,delete_own) VALUES ('ordinary',true,true,false,true,false,true);
INSERT INTO `authority_info`(level,upload,download,update_all,update_own,delete_all,delete_own) VALUES ('other',false,true,false,false,false,false);
