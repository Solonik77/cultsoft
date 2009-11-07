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

/*Table structure for table `blog` */

DROP TABLE IF EXISTS `blog`;

CREATE TABLE `blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `member_id` int(11) DEFAULT NULL,
  `type` enum('collaborative','private') NOT NULL DEFAULT 'private',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog` */

/*Table structure for table `blog_comments` */

DROP TABLE IF EXISTS `blog_comments`;

CREATE TABLE `blog_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned DEFAULT NULL,
  `author` tinytext,
  `author_email` varchar(100) DEFAULT NULL,
  `author_url` varchar(200) DEFAULT NULL,
  `author_ip` varchar(100) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text,
  `approved` tinyint(1) DEFAULT '0',
  `member_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_comments` */

/*Table structure for table `blog_member` */

DROP TABLE IF EXISTS `blog_member`;

CREATE TABLE `blog_member` (
  `blog_id` int(11) unsigned NOT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  `is_moderator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_administrator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_id`),
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`member_id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_member` */

/*Table structure for table `blog_posts` */

DROP TABLE IF EXISTS `blog_posts`;

CREATE TABLE `blog_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned DEFAULT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blog_posts` */

/*Table structure for table `i18n_blog` */

DROP TABLE IF EXISTS `i18n_blog`;

CREATE TABLE `i18n_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lang_id` int(1) DEFAULT NULL,
  `blog_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `i18n_blog` */

/*Table structure for table `i18n_blog_posts` */

DROP TABLE IF EXISTS `i18n_blog_posts`;

CREATE TABLE `i18n_blog_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `lang_id` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `i18n_blog_posts` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
