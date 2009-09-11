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
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `member_id` int(11) DEFAULT NULL,
  `type` enum('collaborative','private') NOT NULL DEFAULT 'private',
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
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text,
  `approved` tinyint(1) DEFAULT '0',
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

/*Table structure for table `prefix_blog_posts` */

DROP TABLE IF EXISTS `prefix_blog_posts`;

CREATE TABLE `prefix_blog_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned DEFAULT NULL,
  `member_id` int(11) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
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
  `date_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language_id` smallint(1) DEFAULT NULL,
  `is_active` smallint(1) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_members` */

insert  into `prefix_members`(`id`,`password`,`email`,`role_id`,`timezone_offset`,`date_registered`,`language_id`,`is_active`,`first_name`,`last_name`) values (1,'224cf2b695a5e8ecaecfb9015161fa4b','admin@example.com',1,2.00,'0000-00-00 00:00:00',1,1,'Piter','Pen');

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

insert  into `prefix_session`(`id`,`modified`,`lifetime`,`user_agent`,`data`) values ('0m00r87af706hn7d0sjfo3iph2',1252663693,1440,'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 FirePHP/0.3','ZFDebug_Time|a:1:{s:4:\"data\";a:2:{s:4:\"main\";a:3:{s:7:\"profile\";a:1:{s:6:\"signin\";a:2:{i:0;d:886.456012725830078125;i:1;d:886.475086212158203125;}}s:19:\"backofficeDashboard\";a:1:{s:5:\"index\";a:2:{i:0;d:838.944911956787109375;i:1;d:838.962078094482421875;}}s:5:\"error\";a:1:{s:5:\"error\";a:16:{i:0;d:917.7439212799072265625;i:1;d:917.75989532470703125;i:2;d:820.7409381866455078125;i:3;d:820.7569122314453125;i:4;d:292.109966278076171875;i:5;d:292.1268939971923828125;i:6;d:363.915920257568359375;i:7;d:363.933086395263671875;i:8;d:154.560089111328125;i:9;d:154.5770168304443359375;i:10;d:818.3040618896484375;i:11;d:818.3209896087646484375;i:12;d:398.7519741058349609375;i:13;d:398.777008056640625;i:14;d:964.13898468017578125;i:15;d:964.1559123992919921875;}}}s:4:\"blog\";a:1:{s:5:\"admin\";a:4:{s:12:\"manage-blogs\";a:72:{i:0;d:496.64306640625;i:1;d:496.6590404510498046875;i:2;d:441.090106964111328125;i:3;d:441.1070346832275390625;i:4;d:573.028087615966796875;i:5;d:573.0459690093994140625;i:6;d:386.8849277496337890625;i:7;d:386.9020938873291015625;i:8;d:924.9279499053955078125;i:9;d:924.9439239501953125;i:10;d:579.4689655303955078125;i:11;d:579.4849395751953125;i:12;d:520.718097686767578125;i:13;d:520.7350254058837890625;i:14;d:851.6418933868408203125;i:15;d:851.6581058502197265625;i:16;d:650.28095245361328125;i:17;d:650.29811859130859375;i:18;d:936.4669322967529296875;i:19;d:936.4840984344482421875;i:20;d:190.105915069580078125;i:21;d:190.123081207275390625;i:22;d:335.6530666351318359375;i:23;d:335.669994354248046875;i:24;d:654.60109710693359375;i:25;d:654.6180248260498046875;i:26;d:515.4869556427001953125;i:27;d:515.5029296875;i:28;d:575.4261016845703125;i:29;d:575.4430294036865234375;i:30;d:593.0740833282470703125;i:31;d:593.09101104736328125;i:32;d:980.8781147003173828125;i:33;d:980.8940887451171875;i:34;d:644.6011066436767578125;i:35;d:644.6170806884765625;i:36;d:166.91303253173828125;i:37;d:166.9290065765380859375;i:38;d:54.214000701904296875;i:39;d:54.2309284210205078125;i:40;d:644.59991455078125;i:41;d:644.6158885955810546875;i:42;d:401.74102783203125;i:43;d:401.7579555511474609375;i:44;d:776.43489837646484375;i:45;d:776.45206451416015625;i:46;d:267.489910125732421875;i:47;d:267.5058841705322265625;i:48;d:781.3661098480224609375;i:49;d:781.382083892822265625;i:50;d:110.066890716552734375;i:51;d:110.085010528564453125;i:52;d:1031.57711029052734375;i:53;d:1031.5940380096435546875;i:54;d:156.8450927734375;i:55;d:156.8629741668701171875;i:56;d:994.77100372314453125;i:57;d:994.7869777679443359375;i:58;d:200.090885162353515625;i:59;d:200.108051300048828125;i:60;d:924.03697967529296875;i:61;d:924.0520000457763671875;i:62;d:384.191036224365234375;i:63;d:384.2060565948486328125;i:64;d:276.9649028778076171875;i:65;d:276.9811153411865234375;i:66;d:665.0750637054443359375;i:67;d:665.091037750244140625;i:68;d:787.8301143646240234375;i:69;d:787.846088409423828125;i:70;d:936.5689754486083984375;i:71;d:936.585903167724609375;}s:11:\"update-blog\";a:24:{i:0;d:868.8869476318359375;i:1;d:868.90506744384765625;i:2;d:657.165050506591796875;i:3;d:657.1829319000244140625;i:4;d:314.281940460205078125;i:5;d:314.299106597900390625;i:6;d:496.097087860107421875;i:7;d:496.1149692535400390625;i:8;d:750.7750988006591796875;i:9;d:750.792980194091796875;i:10;d:885.66493988037109375;i:11;d:885.68401336669921875;i:12;d:232.3648929595947265625;i:13;d:232.3839664459228515625;i:14;d:986.4690303802490234375;i:15;d:986.485958099365234375;i:16;d:426.8310070037841796875;i:17;d:426.847934722900390625;i:18;d:676.947116851806640625;i:19;d:676.9649982452392578125;i:20;d:577.927112579345703125;i:21;d:577.9459476470947265625;i:22;d:639.43004608154296875;i:23;d:639.4479274749755859375;}s:11:\"create-blog\";a:8:{i:0;d:948.7059116363525390625;i:1;d:948.72188568115234375;i:2;d:565.56606292724609375;i:3;d:565.6020641326904296875;i:4;d:513.46492767333984375;i:5;d:513.4830474853515625;i:6;d:972.526073455810546875;i:7;d:972.5439548492431640625;}s:5:\"index\";a:2:{i:0;d:613.7940883636474609375;i:1;d:613.811016082763671875;}}}}}Zend_Auth|a:1:{s:7:\"storage\";O:8:\"stdClass\":3:{s:2:\"id\";i:1;s:7:\"role_id\";i:1;s:5:\"email\";s:17:\"admin@example.com\";}}__ZF|a:1:{s:39:\"Zend_Form_Element_Hash_unique_csrf_hash\";a:1:{s:4:\"ENNH\";i:1;}}');

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
