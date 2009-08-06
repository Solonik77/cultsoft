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

/*Table structure for table `prefix_acl_resources` */

DROP TABLE IF EXISTS `prefix_acl_resources`;

CREATE TABLE `prefix_acl_resources` (
  `id` int(11) NOT NULL,
  `resource` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `prefix_acl_resources` */

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
  `id` int(11) unsigned NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_allow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `prefix_acl_roles_resources` */

/*Table structure for table `prefix_blog` */

DROP TABLE IF EXISTS `prefix_blog`;

CREATE TABLE `prefix_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `type` smallint(1) DEFAULT NULL,
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  KEY `member_id` (`member_id`),
  CONSTRAINT `prefix_blog_comments_fk` FOREIGN KEY (`post_id`) REFERENCES `prefix_blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prefix_blog_comments_fk1` FOREIGN KEY (`member_id`) REFERENCES `prefix_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  KEY `member_id` (`member_id`),
  CONSTRAINT `prefix_blog_member_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `prefix_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prefix_blog_member_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_member` */

/*Table structure for table `prefix_blog_posts` */

DROP TABLE IF EXISTS `prefix_blog_posts`;

CREATE TABLE `prefix_blog_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned DEFAULT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `prefix_blog_posts_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prefix_blog_posts_fk1` FOREIGN KEY (`member_id`) REFERENCES `prefix_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_posts` */

/*Table structure for table `prefix_i18n_blog` */

DROP TABLE IF EXISTS `prefix_i18n_blog`;

CREATE TABLE `prefix_i18n_blog` (
  `i18n_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lang_id` int(1) DEFAULT NULL,
  `blog_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`i18n_id`),
  KEY `lang_id` (`lang_id`),
  KEY `blog_id` (`blog_id`),
  CONSTRAINT `prefix_i18n_blog_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_i18n_blog` */

/*Table structure for table `prefix_i18n_blog_posts` */

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

insert  into `prefix_session`(`id`,`modified`,`lifetime`,`user_agent`,`data`) values ('s5o7g138rg7r6scj64edv1dv74',1249575188,1440,'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 FirePHP/0.3','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:2:{s:7:\"profile\";a:1:{s:6:\"signin\";a:4:{i:0;d:1011.6460323333740234375;i:1;d:1011.6779804229736328125;i:2;d:661.54003143310546875;i:3;d:661.571979522705078125;}}s:19:\"backofficeDashboard\";a:1:{s:5:\"index\";a:4:{i:0;d:1103.56807708740234375;i:1;d:1103.5969257354736328125;i:2;d:550.4629611968994140625;i:3;d:550.4939556121826171875;}}}}}__ZF|a:1:{s:39:\"Zend_Form_Element_Hash_unique_csrf_hash\";a:1:{s:3:\"ENT\";i:1249575431;}}Zend_Auth|a:1:{s:7:\"storage\";O:8:\"stdClass\":3:{s:2:\"id\";i:1;s:7:\"role_id\";i:1;s:5:\"email\";s:17:\"admin@example.com\";}}');

/*Table structure for table `prefix_site_languages` */

DROP TABLE IF EXISTS `prefix_site_languages`;

CREATE TABLE `prefix_site_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_identificator` varchar(3) DEFAULT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_site_languages` */

insert  into `prefix_site_languages`(`id`,`language_identificator`,`locale`,`is_active`,`name`) values (1,'ru','ru_RU',1,'Russian'),(2,'en','en_US',1,'English');

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
