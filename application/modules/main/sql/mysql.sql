/*
SQLyog Community Edition- MySQL GUI v5.27
Host - 5.1.35-community : Database - zfapp
*********************************************************************
Server version : 5.1.35-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `acl_resources` */

DROP TABLE IF EXISTS `acl_resources`;

CREATE TABLE `acl_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `acl_resources` */

insert  into `acl_resources`(`id`,`resource`) values (1,'main_backofficedashboard');

/*Table structure for table `acl_roles` */

DROP TABLE IF EXISTS `acl_roles`;

CREATE TABLE `acl_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `role` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `acl_roles` */

insert  into `acl_roles`(`id`,`parent_id`,`role`,`description`) values (1,0,'administrator','Administrator Account');

/*Table structure for table `acl_roles_resources` */

DROP TABLE IF EXISTS `acl_roles_resources`;

CREATE TABLE `acl_roles_resources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_allow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `acl_roles_resources` */

insert  into `acl_roles_resources`(`id`,`resource_id`,`role_id`,`is_allow`) values (1,1,1,1);

/*Table structure for table `members` */

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` smallint(1) DEFAULT NULL,
  `timezone_offset` double(12,2) DEFAULT '0.00',
  `date_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language_id` smallint(1) DEFAULT NULL,
  `is_active` smallint(1) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `members` */

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `session` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(255) DEFAULT NULL,
  `setting_description` text,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text,
  `module` varchar(255) NOT NULL DEFAULT 'main',
  PRIMARY KEY (`id`,`setting_key`),
  KEY `setting_name` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `settings` */

insert  into `settings`(`id`,`setting_name`,`setting_description`,`setting_key`,`setting_value`,`module`) values (1,'Log threshold','System log threshold','system_log_threshold','4','main'),(2,'Default items per page',NULL,'items_per_page','10','main'),(3,'',NULL,'project.template','simple','main'),(4,'',NULL,'project.email','info@example.com','main'),(5,'',NULL,'project.timezone','Europe/Helsinki','main'),(7,'',NULL,'remember_me_seconds ','864000','main'),(8,'',NULL,'mail.transport','smtp','main'),(9,'',NULL,'mail.host','localhost','main'),(10,'',NULL,'mail.password','smtppassword','main'),(11,'',NULL,'mail.username','smtpuser','main'),(12,'',NULL,'mail.port','25','main'),(13,'',NULL,'mail.auth','','main'),(14,'',NULL,'image.adapter','GD','main'),(15,'',NULL,'image.params.directory','','main'),(16,'',NULL,'encryption.default.key','Z4eN7D+PHP_7hE-SW!FtFraM3w0R|<','main'),(17,'',NULL,'encryption.default.mode','MCRYPT_MODE_NOFB','main'),(18,'',NULL,'encryption.default.cipher','MCRYPT_RIJNDAEL_128','main'),(19,'Blog languages','Languages for blog content','allowed_languages','ru,en','blog');

/*Table structure for table `site_languages` */

DROP TABLE IF EXISTS `site_languages`;

CREATE TABLE `site_languages` (
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

/*Data for the table `site_languages` */

insert  into `site_languages`(`id`,`name`,`request_lang`,`locale`,`territory`,`is_default`,`is_active`,`project_title`) values (1,'U.S. English','en','en_US','US',0,1,''),(2,'Russian','ru','ru_RU','RU',1,1,'');

/*Table structure for table `site_structure` */

DROP TABLE IF EXISTS `site_structure`;

CREATE TABLE `site_structure` (
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

/*Data for the table `site_structure` */

insert  into `site_structure`(`id`,`label`,`title`,`module`,`controller`,`action`,`visible`,`left_column_id`,`right_column_id`) values (1,'Home','','default','index','index',1,1,20),(2,'Blog','','blog','index','index',1,2,9),(3,'IT Blog','','blog','index','it',1,3,4),(4,'Music blog','','blog','index','music',1,5,6),(5,'3D Blog','','blog','index','3d',1,7,8),(6,'Shop','','store','index','index',1,10,19),(7,'Titanium','','store','product','titanium',1,11,14),(8,'FLASH','','store','product','flash',1,12,13),(9,'CD PLAYERS','','store','product','cd',1,15,16),(10,'2 WAY RADIOS','','store','product','radios',1,17,18);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
