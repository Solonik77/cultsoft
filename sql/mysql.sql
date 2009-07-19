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

/*Table structure for table `prefix_acl_roles` */

DROP TABLE IF EXISTS `prefix_acl_roles`;

CREATE TABLE `prefix_acl_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) NOT NULL DEFAULT '0',
  `role` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `res_module_system_controller_admindashboard` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_acl_roles` */

insert  into `prefix_acl_roles`(`id`,`parent`,`role`,`description`,`res_module_system_controller_admindashboard`) values (1,0,'administrator','Administrator Account',0);

/*Table structure for table `prefix_blog` */

DROP TABLE IF EXISTS `prefix_blog`;

CREATE TABLE `prefix_blog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `type` smallint(1) NOT NULL DEFAULT '1',
  `member_owner_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `member_owner_id` (`member_owner_id`),
  CONSTRAINT `prefix_blog_fk` FOREIGN KEY (`member_owner_id`) REFERENCES `prefix_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog` */

/*Table structure for table `prefix_blog_comments` */

DROP TABLE IF EXISTS `prefix_blog_comments`;

CREATE TABLE `prefix_blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `author` tinytext,
  `author_email` varchar(100) DEFAULT NULL,
  `author_url` varchar(200) DEFAULT NULL,
  `author_ip` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text,
  `approved` varchar(20) NOT NULL DEFAULT '1',
  `member_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `prefix_blog_comments_fk1` FOREIGN KEY (`member_id`) REFERENCES `prefix_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prefix_blog_comments_fk` FOREIGN KEY (`post_id`) REFERENCES `prefix_blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_comments` */

/*Table structure for table `prefix_blog_posts` */

DROP TABLE IF EXISTS `prefix_blog_posts`;

CREATE TABLE `prefix_blog_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `member_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `prefix_blog_posts_fk1` FOREIGN KEY (`member_id`) REFERENCES `prefix_members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prefix_blog_posts_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_blog_posts` */

/*Table structure for table `prefix_members` */

DROP TABLE IF EXISTS `prefix_members`;

CREATE TABLE `prefix_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` smallint(1) NOT NULL DEFAULT '0',
  `timezone_offset` double(12,2) DEFAULT '0.00',
  `registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language_id` smallint(1) NOT NULL DEFAULT '1',
  `is_active` smallint(1) NOT NULL DEFAULT '1',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_members` */

insert  into `prefix_members`(`id`,`password`,`email`,`role_id`,`timezone_offset`,`registered`,`language_id`,`is_active`,`first_name`,`last_name`) values (1,'224cf2b695a5e8ecaecfb9015161fa4b','admin@example.com',1,2.00,'0000-00-00 00:00:00',1,1,'',NULL);

/*Table structure for table `prefix_session` */

DROP TABLE IF EXISTS `prefix_session`;

CREATE TABLE `prefix_session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) NOT NULL DEFAULT '0',
  `lifetime` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_session` */

insert  into `prefix_session`(`id`,`modified`,`lifetime`,`user_agent`,`data`) values ('3lau4dvne08gbscr7qb3236un3',1248022144,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:675.961971282958984375;}}}}}'),('3mk0mh6uf94tqh2cdrad5fhgb3',1248022744,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:665.4911041259765625;}}}}}'),('486r4fk4stu69phbkgmc7pgnt6',1248022804,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:681.7150115966796875;}}}}}'),('48i4n1rqed61cd3b6fnbkj5gc1',1248021184,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:683.557987213134765625;}}}}}'),('5831d305hcd3lif49ra9q4d3t7',1248021124,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:692.4688816070556640625;}}}}}'),('5suuf5qe3l31e5da8t5k6t6sn3',1248020524,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:707.767963409423828125;}}}}}'),('6528jj8635er80gj5vqih1jtt4',1248020044,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:666.99504852294921875;}}}}}'),('6op20ct4tgk6sopk2tqkrd8734',1248020464,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:708.1470489501953125;}}}}}'),('78vjdq09gv0mmbj2cnk6tmro53',1248020824,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:694.0410137176513671875;}}}}}'),('85lddv4p1u751vgog0fdedn7d0',1248020704,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:715.3110504150390625;}}}}}'),('893s46ponopk1v3naes5ltrcv3',1248020884,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:711.8608951568603515625;}}}}}'),('955q1no5sir63fm03sbe6coqd2',1248019684,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:663.4929180145263671875;}}}}}'),('9ifnalthhi7f6bg989vo7ur0h5',1248022084,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:664.2739772796630859375;}}}}}'),('apk7co9o8jaehr86j5ipess647',1248020764,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:701.1330127716064453125;}}}}}'),('d4fq8d178ja0mhf7r9hgq45n64',1248022624,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:669.8238849639892578125;}}}}}'),('e4kdhit7g1gu9pm7kss1ms1nc7',1248020404,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:681.94293975830078125;}}}}}'),('e4m3vq6iqrgqle8clev8uba0l2',1248021424,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:668.2240962982177734375;}}}}}'),('e60l6v856tok95u518jtn0vao4',1248020224,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:690.5729770660400390625;}}}}}'),('eps3q24j41do84789tjbf542d5',1248022324,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:697.3590850830078125;}}}}}'),('errod4h5cirferr1a4s1it6ra4',1248020344,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:698.9428997039794921875;}}}}}'),('f0hg31fpa07ingtm45kari7d64',1248021064,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:685.5518817901611328125;}}}}}'),('f8vp5vjmvos41j3gu0pm952661',1248022689,1440,'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.11) Gecko/2009060215 Firefox/3.0.11 (.NET CLR 3.5.30729) FirePHP/0.3','ZFDebug_Time|a:1:{s:4:\"data\";a:2:{s:7:\"profile\";a:1:{s:5:\"index\";a:1:{s:6:\"signin\";a:1:{i:0;d:289.5629405975341796875;}}}s:6:\"system\";a:1:{s:14:\"admindashboard\";a:1:{s:5:\"index\";a:21:{i:0;d:522.1130847930908203125;i:1;d:807.5430393218994140625;i:2;d:284.09099578857421875;i:3;d:887.6841068267822265625;i:4;d:956.9089412689208984375;i:5;d:730.609893798828125;i:6;d:636.81697845458984375;i:7;d:1207.7519893646240234375;i:8;d:220.983028411865234375;i:9;d:261.0580921173095703125;i:10;d:459.434986114501953125;i:11;d:1011.1820697784423828125;i:12;d:803.97701263427734375;i:13;d:792.2649383544921875;i:14;d:708.29296112060546875;i:15;d:901.814937591552734375;i:16;d:476.0589599609375;i:17;d:523.6089229583740234375;i:18;d:779.1979312896728515625;i:19;d:928.9309978485107421875;i:20;d:260.6079578399658203125;}}}}}Zend_Auth|a:1:{s:7:\"storage\";O:8:\"stdClass\":2:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"admin@example.com\";}}'),('ffnsce21go8gfhtc4toj5779m6',1248021724,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:663.8829708099365234375;}}}}}'),('g5a8603sbm9d06lvu35v2hovf1',1248019564,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:701.84993743896484375;}}}}}'),('g6j160piq9glt431cb1ddatd47',1248021244,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:667.8779125213623046875;}}}}}'),('gk3qbetalkml4hebp9rmq96kt6',1248021844,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:694.5569515228271484375;}}}}}'),('gk40uitsffpt9p3rj9u1en66r3',1248022024,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:703.363895416259765625;}}}}}'),('harti2ot3996tarf4s78b13bj7',1248019744,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:665.4179096221923828125;}}}}}'),('hldb6ppngp05nobdgm787uft53',1248020584,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:699.7220516204833984375;}}}}}'),('hnmarshibm2lqnpk1cu5j5jff5',1248021364,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:680.982112884521484375;}}}}}'),('i00dg3d0r91j2820lohk225p86',1248022264,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:680.87100982666015625;}}}}}'),('ic4dm16pud2a03gi0e2fvhn7h6',1248019624,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:701.8299102783203125;}}}}}'),('jdueml6baeagga411l32j3tcr1',1248021964,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:677.2739887237548828125;}}}}}'),('l4etf3erpsutl2r92obj7eein3',1248019924,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:690.0060176849365234375;}}}}}'),('lhuavou6mnebsjhstoqtivvrj1',1248019804,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:698.5340118408203125;}}}}}'),('lk7dim3123a9168hs44dm1ddf2',1248021664,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:692.7280426025390625;}}}}}'),('lp9g7cg2s5vut9s5rc6cogvk66',1248020104,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:681.149959564208984375;}}}}}'),('m0ndocti069cjtpue3cg8ja350',1248020644,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:701.8020153045654296875;}}}}}'),('mqdl6ksou5f0okisjkegqarf95',1248021544,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:668.1060791015625;}}}}}'),('mqtf4934nklhujf6lgf90f22m7',1248021784,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:666.9089794158935546875;}}}}}'),('nk485t6lerp3sprh1qvfo7pvv4',1248022684,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:692.267894744873046875;}}}}}'),('o8jq4vg93a3dq6rd4kludm0ea6',1248020944,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:714.5240306854248046875;}}}}}'),('ocd1tf1g2khb5dajo20ueu1u22',1248021004,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:757.5600147247314453125;}}}}}'),('ol3seiabu9c0k7tvqg1sf2m5h5',1248020164,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:701.242923736572265625;}}}}}'),('pj45r77no2dn5uvprm9uhgj5l1',1248021304,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:724.749088287353515625;}}}}}'),('ppf3d3r2fq48vndc6qdrj66fq7',1248022864,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:703.9248943328857421875;}}}}}'),('saodd05u2hfrf2ceql0ndu5te3',1248022384,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:692.099094390869140625;}}}}}'),('soq15ev6n1v5criqu706dajgf3',1248019984,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:684.3850612640380859375;}}}}}'),('t2190lh29vn0f4utv25vmdb5i1',1248022564,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:679.3429851531982421875;}}}}}'),('ta3f4c8lgvikg4ebai5a3j6gs4',1248021904,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:691.3878917694091796875;}}}}}'),('tg1j8na5qibtr8fhh7son6rt71',1248022204,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:725.87299346923828125;}}}}}'),('u3vvgg69uc4de3dudhh92kpuh5',1248020284,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:663.9881134033203125;}}}}}'),('u7hmudh20pum6je7hhu4j6kmh2',1248019864,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:677.3378849029541015625;}}}}}'),('ult5424di23skpgre2iqhr92c4',1248021604,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:724.5500087738037109375;}}}}}'),('uvhlluebv25q19ijk41d5nvkh3',1248022504,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:705.7058811187744140625;}}}}}'),('v3o9cdlv1spqnb21ekds5i2f67',1248022444,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:689.2108917236328125;}}}}}'),('v9isq5ednljjs6v9oh0p8g8k51',1248021484,1440,'','ZFDebug_Time|a:1:{s:4:\"data\";a:1:{s:4:\"main\";a:1:{s:5:\"index\";a:1:{s:5:\"index\";a:1:{i:0;d:692.945957183837890625;}}}}}');

/*Table structure for table `prefix_site_languages` */

DROP TABLE IF EXISTS `prefix_site_languages`;

CREATE TABLE `prefix_site_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_identificator` varchar(3) DEFAULT NULL,
  `locale` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prefix_site_languages` */

/*Table structure for table `prefix_site_structure` */

DROP TABLE IF EXISTS `prefix_site_structure`;

CREATE TABLE `prefix_site_structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(100) DEFAULT NULL,
  `module` varchar(100) NOT NULL DEFAULT 'default',
  `controller` varchar(100) NOT NULL DEFAULT 'index',
  `action` varchar(100) NOT NULL DEFAULT 'index',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `left_column_id` int(11) NOT NULL DEFAULT '0',
  `right_column_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `prefix_site_structure` */

insert  into `prefix_site_structure`(`id`,`label`,`title`,`module`,`controller`,`action`,`visible`,`left_column_id`,`right_column_id`) values (1,'Home','','default','index','index',1,1,20),(2,'Blog','','blog','index','index',1,2,9),(3,'IT Blog','','blog','index','it',1,3,4),(4,'Music blog','','blog','index','music',1,5,6),(5,'3D Blog','','blog','index','3d',1,7,8),(6,'Shop','','store','index','index',1,10,19),(7,'Titanium','','store','product','titanium',1,11,14),(8,'FLASH','','store','product','flash',1,12,13),(9,'CD PLAYERS','','store','product','cd',1,15,16),(10,'2 WAY RADIOS','','store','product','radios',1,17,18);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
