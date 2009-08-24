# MySQL-Front 5.1  (Build 3.58)

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;


# Host: localhost    Database: zfapp
# ------------------------------------------------------
# Server version 5.1.35-community

#
# Source for table prefix_acl_resources
#

DROP TABLE IF EXISTS `prefix_acl_resources`;
CREATE TABLE `prefix_acl_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_acl_resources
#
LOCK TABLES `prefix_acl_resources` WRITE;
/*!40000 ALTER TABLE `prefix_acl_resources` DISABLE KEYS */;

INSERT INTO `prefix_acl_resources` VALUES (1,'main_backofficedashboard');
/*!40000 ALTER TABLE `prefix_acl_resources` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_acl_roles
#

DROP TABLE IF EXISTS `prefix_acl_roles`;
CREATE TABLE `prefix_acl_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `role` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_acl_roles
#
LOCK TABLES `prefix_acl_roles` WRITE;
/*!40000 ALTER TABLE `prefix_acl_roles` DISABLE KEYS */;

INSERT INTO `prefix_acl_roles` VALUES (1,0,'administrator','Administrator Account');
/*!40000 ALTER TABLE `prefix_acl_roles` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_acl_roles_resources
#

DROP TABLE IF EXISTS `prefix_acl_roles_resources`;
CREATE TABLE `prefix_acl_roles_resources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_allow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_acl_roles_resources
#
LOCK TABLES `prefix_acl_roles_resources` WRITE;
/*!40000 ALTER TABLE `prefix_acl_roles_resources` DISABLE KEYS */;

INSERT INTO `prefix_acl_roles_resources` VALUES (1,1,1,1);
/*!40000 ALTER TABLE `prefix_acl_roles_resources` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_blog
#

DROP TABLE IF EXISTS `prefix_blog`;
CREATE TABLE `prefix_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `type` smallint(1) DEFAULT NULL,
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_blog
#
LOCK TABLES `prefix_blog` WRITE;
/*!40000 ALTER TABLE `prefix_blog` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_blog` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_blog_comments
#

DROP TABLE IF EXISTS `prefix_blog_comments`;
CREATE TABLE `prefix_blog_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned DEFAULT NULL,
  `author` tinytext,
  `author_email` varchar(100) DEFAULT NULL,
  `author_url` varchar(200) DEFAULT NULL,
  `author_ip` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text,
  `approved` varchar(20) DEFAULT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_blog_comments
#
LOCK TABLES `prefix_blog_comments` WRITE;
/*!40000 ALTER TABLE `prefix_blog_comments` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_blog_member
#

DROP TABLE IF EXISTS `prefix_blog_member`;
CREATE TABLE `prefix_blog_member` (
  `blog_id` int(11) unsigned NOT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  `is_moderator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_administrator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_id`),
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`member_id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_blog_member
#
LOCK TABLES `prefix_blog_member` WRITE;
/*!40000 ALTER TABLE `prefix_blog_member` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_blog_member` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_blog_posts
#

DROP TABLE IF EXISTS `prefix_blog_posts`;
CREATE TABLE `prefix_blog_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned DEFAULT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_blog_posts
#
LOCK TABLES `prefix_blog_posts` WRITE;
/*!40000 ALTER TABLE `prefix_blog_posts` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_i18n_blog
#

DROP TABLE IF EXISTS `prefix_i18n_blog`;
CREATE TABLE `prefix_i18n_blog` (
  `i18n_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lang_id` int(1) DEFAULT NULL,
  `blog_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`i18n_id`),
  KEY `lang_id` (`lang_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_i18n_blog
#
LOCK TABLES `prefix_i18n_blog` WRITE;
/*!40000 ALTER TABLE `prefix_i18n_blog` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_i18n_blog` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_i18n_blog_posts
#

DROP TABLE IF EXISTS `prefix_i18n_blog_posts`;
CREATE TABLE `prefix_i18n_blog_posts` (
  `i18n_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `lang_id` int(1) DEFAULT NULL,
  PRIMARY KEY (`i18n_id`),
  KEY `lang_id` (`lang_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_i18n_blog_posts
#
LOCK TABLES `prefix_i18n_blog_posts` WRITE;
/*!40000 ALTER TABLE `prefix_i18n_blog_posts` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_i18n_blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_members
#

DROP TABLE IF EXISTS `prefix_members`;
CREATE TABLE `prefix_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` smallint(1) DEFAULT NULL,
  `timezone_offset` double(12,2) DEFAULT '0.00',
  `registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language_id` smallint(1) DEFAULT NULL,
  `is_active` smallint(1) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_members
#
LOCK TABLES `prefix_members` WRITE;
/*!40000 ALTER TABLE `prefix_members` DISABLE KEYS */;

INSERT INTO `prefix_members` VALUES (1,'224cf2b695a5e8ecaecfb9015161fa4b','admin@example.com',1,2,'0000-00-00 00:00:00',1,1,NULL,NULL);
/*!40000 ALTER TABLE `prefix_members` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_session
#

DROP TABLE IF EXISTS `prefix_session`;
CREATE TABLE `prefix_session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_session
#
LOCK TABLES `prefix_session` WRITE;
/*!40000 ALTER TABLE `prefix_session` DISABLE KEYS */;

/*!40000 ALTER TABLE `prefix_session` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_settings
#

DROP TABLE IF EXISTS `prefix_settings`;
CREATE TABLE `prefix_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(255) DEFAULT NULL,
  `setting_description` text,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text,
  `module` varchar(255) NOT NULL DEFAULT 'main',
  PRIMARY KEY (`id`,`setting_key`),
  KEY `setting_name` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_settings
#
LOCK TABLES `prefix_settings` WRITE;
/*!40000 ALTER TABLE `prefix_settings` DISABLE KEYS */;

INSERT INTO `prefix_settings` VALUES (1,'Log threshold','System log threshold','system_log_threshold','4','main');
INSERT INTO `prefix_settings` VALUES (2,'Default items per page',NULL,'items_per_page','10','main');
INSERT INTO `prefix_settings` VALUES (3,'',NULL,'project.template','simple','main');
INSERT INTO `prefix_settings` VALUES (4,'',NULL,'project.email','info@example.com','main');
INSERT INTO `prefix_settings` VALUES (5,'',NULL,'project.timezone','Europe/Helsinki','main');
INSERT INTO `prefix_settings` VALUES (7,'',NULL,'remember_me_seconds ','864000','main');
INSERT INTO `prefix_settings` VALUES (8,'',NULL,'mail.transport','smtp','main');
INSERT INTO `prefix_settings` VALUES (9,'',NULL,'mail.host','localhost','main');
INSERT INTO `prefix_settings` VALUES (10,'',NULL,'mail.password','smtppassword','main');
INSERT INTO `prefix_settings` VALUES (11,'',NULL,'mail.username','smtpuser','main');
INSERT INTO `prefix_settings` VALUES (12,'',NULL,'mail.port','25','main');
INSERT INTO `prefix_settings` VALUES (13,'',NULL,'mail.auth','','main');
INSERT INTO `prefix_settings` VALUES (14,'',NULL,'image.adapter','GD','main');
INSERT INTO `prefix_settings` VALUES (15,'',NULL,'image.params.directory','','main');
INSERT INTO `prefix_settings` VALUES (16,'',NULL,'encryption.default.key','Z4eN7D+PHP_7hE-SW!FtFraM3w0R|<','main');
INSERT INTO `prefix_settings` VALUES (17,'',NULL,'encryption.default.mode','MCRYPT_MODE_NOFB','main');
INSERT INTO `prefix_settings` VALUES (18,'',NULL,'encryption.default.cipher','MCRYPT_RIJNDAEL_128','main');
INSERT INTO `prefix_settings` VALUES (19,'Blog languages','Languages for blog content','allowed_languages','ru','blog');
/*!40000 ALTER TABLE `prefix_settings` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_site_languages
#

DROP TABLE IF EXISTS `prefix_site_languages`;
CREATE TABLE `prefix_site_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `request_lang` varchar(8) DEFAULT NULL,
  `locale` varchar(8) DEFAULT NULL,
  `territory` varchar(8) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `project_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_site_languages
#
LOCK TABLES `prefix_site_languages` WRITE;
/*!40000 ALTER TABLE `prefix_site_languages` DISABLE KEYS */;

INSERT INTO `prefix_site_languages` VALUES (1,'U.S. English','en','en_US','US',0,1,'');
INSERT INTO `prefix_site_languages` VALUES (2,'Russian','ru','ru_RU','RU',1,1,'');
/*!40000 ALTER TABLE `prefix_site_languages` ENABLE KEYS */;
UNLOCK TABLES;

#
# Source for table prefix_site_structure
#

DROP TABLE IF EXISTS `prefix_site_structure`;
CREATE TABLE `prefix_site_structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `module` varchar(100) NOT NULL DEFAULT 'main',
  `controller` varchar(100) NOT NULL DEFAULT 'index',
  `action` varchar(100) NOT NULL DEFAULT 'index',
  `visible` tinyint(1) DEFAULT NULL,
  `left_column_id` int(11) DEFAULT NULL,
  `right_column_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Dumping data for table prefix_site_structure
#
LOCK TABLES `prefix_site_structure` WRITE;
/*!40000 ALTER TABLE `prefix_site_structure` DISABLE KEYS */;

INSERT INTO `prefix_site_structure` VALUES (1,'Home','','default','index','index',1,1,20);
INSERT INTO `prefix_site_structure` VALUES (2,'Blog','','blog','index','index',1,2,9);
INSERT INTO `prefix_site_structure` VALUES (3,'IT Blog','','blog','index','it',1,3,4);
INSERT INTO `prefix_site_structure` VALUES (4,'Music blog','','blog','index','music',1,5,6);
INSERT INTO `prefix_site_structure` VALUES (5,'3D Blog','','blog','index','3d',1,7,8);
INSERT INTO `prefix_site_structure` VALUES (6,'Shop','','store','index','index',1,10,19);
INSERT INTO `prefix_site_structure` VALUES (7,'Titanium','','store','product','titanium',1,11,14);
INSERT INTO `prefix_site_structure` VALUES (8,'FLASH','','store','product','flash',1,12,13);
INSERT INTO `prefix_site_structure` VALUES (9,'CD PLAYERS','','store','product','cd',1,15,16);
INSERT INTO `prefix_site_structure` VALUES (10,'2 WAY RADIOS','','store','product','radios',1,17,18);
/*!40000 ALTER TABLE `prefix_site_structure` ENABLE KEYS */;
UNLOCK TABLES;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
