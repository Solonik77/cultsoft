/*
SQLyog Community Edition- MySQL GUI v5.27
Host - 5.1.35-community : Database - zfapp
*********************************************************************
Server version : 5.1.35-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `zfapp`;

USE `zfapp`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `prefix_acl_resources` */

DROP TABLE IF EXISTS `prefix_acl_resources`;

CREATE TABLE `prefix_acl_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_resources` */

insert  into `prefix_acl_resources`(`id`,`resource`) values (1,'main_backofficedashboard');

/*Table structure for table `prefix_acl_roles` */

DROP TABLE IF EXISTS `prefix_acl_roles`;

CREATE TABLE `prefix_acl_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `role` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_roles` */

insert  into `prefix_acl_roles`(`id`,`parent_id`,`role`,`description`) values (1,0,'administrator','Administrator Account');

/*Table structure for table `prefix_acl_roles_resources` */

DROP TABLE IF EXISTS `prefix_acl_roles_resources`;

CREATE TABLE `prefix_acl_roles_resources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_allow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_roles_resources` */

insert  into `prefix_acl_roles_resources`(`id`,`resource_id`,`role_id`,`is_allow`) values (1,1,1,1);

/*Table structure for table `prefix_blog` */

DROP TABLE IF EXISTS `prefix_blog`;

CREATE TABLE `prefix_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `type` smallint(1) DEFAULT NULL,
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog` */

/*Table structure for table `prefix_blog_comments` */

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

/*Data for the table `prefix_blog_comments` */

/*Table structure for table `prefix_blog_member` */

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

/*Data for the table `prefix_blog_member` */

insert  into `prefix_blog_member`(`blog_id`,`member_id`,`is_moderator`,`is_administrator`) values (1,1,1,1),(2,1,1,1),(3,1,1,1),(4,1,1,1),(5,1,1,1),(6,1,1,1),(7,1,1,1);

/*Table structure for table `prefix_blog_posts` */

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

/*Data for the table `prefix_blog_posts` */

/*Table structure for table `prefix_i18n_blog` */

DROP TABLE IF EXISTS `prefix_i18n_blog`;

CREATE TABLE `prefix_i18n_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lang_id` int(1) DEFAULT NULL,
  `blog_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_i18n_blog` */

/*Table structure for table `prefix_i18n_blog_posts` */

DROP TABLE IF EXISTS `prefix_i18n_blog_posts`;

CREATE TABLE `prefix_i18n_blog_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `lang_id` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_i18n_blog_posts` */

/*Table structure for table `prefix_members` */

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

/*Data for the table `prefix_members` */

insert  into `prefix_members`(`id`,`password`,`email`,`role_id`,`timezone_offset`,`registered`,`language_id`,`is_active`,`first_name`,`last_name`) values (1,'224cf2b695a5e8ecaecfb9015161fa4b','admin@example.com',1,2.00,'0000-00-00 00:00:00',1,1,NULL,NULL);

/*Table structure for table `prefix_session` */

DROP TABLE IF EXISTS `prefix_session`;

CREATE TABLE `prefix_session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_session` */

/*Table structure for table `prefix_settings` */

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

/*Data for the table `prefix_settings` */

insert  into `prefix_settings`(`id`,`setting_name`,`setting_description`,`setting_key`,`setting_value`,`module`) values (1,'Log threshold','System log threshold','system_log_threshold','4','main'),(2,'Default items per page',NULL,'items_per_page','10','main'),(3,'',NULL,'project.template','simple','main'),(4,'',NULL,'project.email','info@example.com','main'),(5,'',NULL,'project.timezone','Europe/Helsinki','main'),(7,'',NULL,'remember_me_seconds ','864000','main'),(8,'',NULL,'mail.transport','smtp','main'),(9,'',NULL,'mail.host','localhost','main'),(10,'',NULL,'mail.password','smtppassword','main'),(11,'',NULL,'mail.username','smtpuser','main'),(12,'',NULL,'mail.port','25','main'),(13,'',NULL,'mail.auth','','main'),(14,'',NULL,'image.adapter','GD','main'),(15,'',NULL,'image.params.directory','','main'),(16,'',NULL,'encryption.default.key','Z4eN7D+PHP_7hE-SW!FtFraM3w0R|<','main'),(17,'',NULL,'encryption.default.mode','MCRYPT_MODE_NOFB','main'),(18,'',NULL,'encryption.default.cipher','MCRYPT_RIJNDAEL_128','main'),(19,'Blog languages','Languages for blog content','allowed_languages','ru,en','blog');

/*Table structure for table `prefix_site_languages` */

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

/*Data for the table `prefix_site_languages` */

insert  into `prefix_site_languages`(`id`,`name`,`request_lang`,`locale`,`territory`,`is_default`,`is_active`,`project_title`) values (1,'U.S. English','en','en_US','US',0,1,''),(2,'Russian','ru','ru_RU','RU',1,1,'');

/*Table structure for table `prefix_site_structure` */

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

/*Data for the table `prefix_site_structure` */

insert  into `prefix_site_structure`(`id`,`label`,`title`,`module`,`controller`,`action`,`visible`,`left_column_id`,`right_column_id`) values (1,'Home','','default','index','index',1,1,20),(2,'Blog','','blog','index','index',1,2,9),(3,'IT Blog','','blog','index','it',1,3,4),(4,'Music blog','','blog','index','music',1,5,6),(5,'3D Blog','','blog','index','3d',1,7,8),(6,'Shop','','store','index','index',1,10,19),(7,'Titanium','','store','product','titanium',1,11,14),(8,'FLASH','','store','product','flash',1,12,13),(9,'CD PLAYERS','','store','product','cd',1,15,16),(10,'2 WAY RADIOS','','store','product','radios',1,17,18);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
