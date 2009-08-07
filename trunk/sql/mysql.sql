/*
SQLyog Community Edition- MySQL GUI v5.27
Host - 5.0.45-community-nt : Database - zfapp
*********************************************************************
Server version : 5.0.45-community-nt
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `prefix_acl_resources` */

DROP TABLE IF EXISTS `prefix_acl_resources`;

CREATE TABLE `prefix_acl_resources` (
  `id` int(11) NOT NULL auto_increment,
  `resource` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_resources` */

insert  into `prefix_acl_resources`(`id`,`resource`) values (1,'main_backofficedashboard');

/*Table structure for table `prefix_acl_roles` */

DROP TABLE IF EXISTS `prefix_acl_roles`;

CREATE TABLE `prefix_acl_roles` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `role` varchar(64) default NULL,
  `description` varchar(256) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_roles` */

insert  into `prefix_acl_roles`(`id`,`parent_id`,`role`,`description`) values (1,0,'administrator','Administrator Account');

/*Table structure for table `prefix_acl_roles_resources` */

DROP TABLE IF EXISTS `prefix_acl_roles_resources`;

CREATE TABLE `prefix_acl_roles_resources` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `resource_id` int(11) default NULL,
  `role_id` int(11) default NULL,
  `is_allow` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_roles_resources` */

insert  into `prefix_acl_roles_resources`(`id`,`resource_id`,`role_id`,`is_allow`) values (1,1,1,1);

/*Table structure for table `prefix_blog` */

DROP TABLE IF EXISTS `prefix_blog`;

CREATE TABLE `prefix_blog` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fancy_url` varchar(100) default NULL,
  `type` smallint(1) default NULL,
  `created` datetime default '0000-00-00 00:00:00',
  `updated` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog` */

/*Table structure for table `prefix_blog_comments` */

DROP TABLE IF EXISTS `prefix_blog_comments`;

CREATE TABLE `prefix_blog_comments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `post_id` int(11) unsigned default NULL,
  `author` tinytext,
  `author_email` varchar(100) default NULL,
  `author_url` varchar(200) default NULL,
  `author_ip` varchar(100) default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `content` text,
  `approved` varchar(20) default NULL,
  `member_id` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_comments` */

/*Table structure for table `prefix_blog_member` */

DROP TABLE IF EXISTS `prefix_blog_member`;

CREATE TABLE `prefix_blog_member` (
  `blog_id` int(11) unsigned NOT NULL,
  `member_id` int(11) unsigned default NULL,
  `is_moderator` tinyint(1) unsigned NOT NULL default '0',
  `is_administrator` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`blog_id`),
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`member_id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_member` */

/*Table structure for table `prefix_blog_posts` */

DROP TABLE IF EXISTS `prefix_blog_posts`;

CREATE TABLE `prefix_blog_posts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `blog_id` int(11) unsigned default NULL,
  `member_id` int(11) unsigned default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_posts` */

/*Table structure for table `prefix_i18n_blog` */

DROP TABLE IF EXISTS `prefix_i18n_blog`;

CREATE TABLE `prefix_i18n_blog` (
  `i18n_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) default NULL,
  `description` varchar(255) default NULL,
  `lang_id` int(1) default NULL,
  `blog_id` int(11) unsigned default NULL,
  PRIMARY KEY  (`i18n_id`),
  KEY `lang_id` (`lang_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_i18n_blog` */

/*Table structure for table `prefix_i18n_blog_posts` */

DROP TABLE IF EXISTS `prefix_i18n_blog_posts`;

CREATE TABLE `prefix_i18n_blog_posts` (
  `i18n_id` int(11) unsigned NOT NULL auto_increment,
  `post_id` int(11) unsigned default NULL,
  `title` varchar(200) default NULL,
  `content` longtext,
  `lang_id` int(1) default NULL,
  PRIMARY KEY  (`i18n_id`),
  KEY `lang_id` (`lang_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_i18n_blog_posts` */

/*Table structure for table `prefix_members` */

DROP TABLE IF EXISTS `prefix_members`;

CREATE TABLE `prefix_members` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `password` varchar(64) default NULL,
  `email` varchar(100) default NULL,
  `role_id` smallint(1) default NULL,
  `timezone_offset` double(12,2) default '0.00',
  `registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `language_id` smallint(1) default NULL,
  `is_active` smallint(1) default NULL,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_members` */

insert  into `prefix_members`(`id`,`password`,`email`,`role_id`,`timezone_offset`,`registered`,`language_id`,`is_active`,`first_name`,`last_name`) values (1,'224cf2b695a5e8ecaecfb9015161fa4b','admin@example.com',1,2.00,'0000-00-00 00:00:00',1,1,NULL,NULL);

/*Table structure for table `prefix_session` */

DROP TABLE IF EXISTS `prefix_session`;

CREATE TABLE `prefix_session` (
  `id` char(32) NOT NULL default '',
  `modified` int(11) default NULL,
  `lifetime` int(11) default NULL,
  `user_agent` varchar(255) default NULL,
  `data` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_session` */

/*Table structure for table `prefix_settings` */

DROP TABLE IF EXISTS `prefix_settings`;

CREATE TABLE `prefix_settings` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `setting_name` varchar(255) NOT NULL default '',
  `setting_value` text,
  PRIMARY KEY  (`id`,`setting_name`),
  KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_settings` */

/*Table structure for table `prefix_site_languages` */

DROP TABLE IF EXISTS `prefix_site_languages`;

CREATE TABLE `prefix_site_languages` (
  `id` int(11) NOT NULL auto_increment,
  `language_identificator` varchar(3) default NULL,
  `locale` varchar(255) default NULL,
  `is_active` tinyint(1) default NULL,
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_site_languages` */

insert  into `prefix_site_languages`(`id`,`language_identificator`,`locale`,`is_active`,`name`) values (1,'ru','ru_RU',1,'Russian'),(2,'en','en_US',1,'English');

/*Table structure for table `prefix_site_structure` */

DROP TABLE IF EXISTS `prefix_site_structure`;

CREATE TABLE `prefix_site_structure` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `label` varchar(100) default NULL,
  `title` varchar(100) default NULL,
  `module` varchar(100) NOT NULL default 'main',
  `controller` varchar(100) NOT NULL default 'index',
  `action` varchar(100) NOT NULL default 'index',
  `visible` tinyint(1) default NULL,
  `left_column_id` int(11) default NULL,
  `right_column_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_site_structure` */

insert  into `prefix_site_structure`(`id`,`label`,`title`,`module`,`controller`,`action`,`visible`,`left_column_id`,`right_column_id`) values (1,'Home','','default','index','index',1,1,20),(2,'Blog','','blog','index','index',1,2,9),(3,'IT Blog','','blog','index','it',1,3,4),(4,'Music blog','','blog','index','music',1,5,6),(5,'3D Blog','','blog','index','3d',1,7,8),(6,'Shop','','store','index','index',1,10,19),(7,'Titanium','','store','product','titanium',1,11,14),(8,'FLASH','','store','product','flash',1,12,13),(9,'CD PLAYERS','','store','product','cd',1,15,16),(10,'2 WAY RADIOS','','store','product','radios',1,17,18);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
