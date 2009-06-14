/*
SQLyog Community Edition- MySQL GUI v5.27
Host - 5.1.31-community : Database - zfapp
*********************************************************************
Server version : 5.1.31-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `blog_comments` */

DROP TABLE IF EXISTS `blog_comments`;

CREATE TABLE `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `author` tinytext NOT NULL,
  `author_email` varchar(100) NOT NULL DEFAULT '',
  `author_url` varchar(200) NOT NULL DEFAULT '',
  `author_ip` varchar(100) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text NOT NULL,
  `approved` varchar(20) NOT NULL DEFAULT '1',
  `member_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_comments` */

/*Table structure for table `blog_posts` */

DROP TABLE IF EXISTS `blog_posts`;

CREATE TABLE `blog_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `author_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_posts` */

/*Table structure for table `blogs` */

DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `type` smallint(1) NOT NULL DEFAULT '1',
  `owner_member_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blogs` */

/*Table structure for table `prefix_acl_roles` */

DROP TABLE IF EXISTS `prefix_acl_roles`;

CREATE TABLE `prefix_acl_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) NOT NULL DEFAULT '0',
  `role` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL,
  `res_module_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `prefix_acl_roles` */

insert  into `prefix_acl_roles`(`id`,`parent`,`role`,`description`,`res_module_admin`) values (1,0,'administrator','Administrator Account',1);

/*Table structure for table `prefix_members` */

DROP TABLE IF EXISTS `prefix_members`;

CREATE TABLE `prefix_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `role_id` smallint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_members` */

insert  into `prefix_members`(`id`,`password`,`email`,`role_id`,`created`,`is_active`) values (1,'224cf2b695a5e8ecaecfb9015161fa4b','admin@example.com',1,'0000-00-00 00:00:00',1);

/*Table structure for table `prefix_navigation_menu` */

DROP TABLE IF EXISTS `prefix_navigation_menu`;

CREATE TABLE `prefix_navigation_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL DEFAULT 'Home',
  `title` varchar(100) NOT NULL,
  `controller` varchar(100) NOT NULL DEFAULT 'index',
  `module` varchar(100) NOT NULL DEFAULT 'default',
  `action` varchar(100) NOT NULL DEFAULT 'index',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `left_column_id` int(11) NOT NULL,
  `right_column_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_navigation_menu` */

insert  into `prefix_navigation_menu`(`id`,`label`,`title`,`controller`,`module`,`action`,`visible`,`left_column_id`,`right_column_id`) values (1,'Home','','index','default','index',1,1,20),(2,'Blog','','index','default','index',1,2,9),(3,'IT Blog','','index','default','index',1,3,4),(4,'Music blog','','index','default','index',1,5,6),(5,'3D Blog','','index','default','index',1,7,8),(6,'Shop','','index','default','index',1,10,19),(7,'Titanium','','index','default','index',1,11,14),(8,'FLASH','','index','default','index',1,12,13),(9,'CD PLAYERS','','index','default','index',1,15,16),(10,'2 WAY RADIOS','','index','default','index',1,17,18);

/*Table structure for table `prefix_session` */

DROP TABLE IF EXISTS `prefix_session`;

CREATE TABLE `prefix_session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_session` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
