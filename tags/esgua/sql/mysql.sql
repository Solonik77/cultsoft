/*
SQLyog Community Edition- MySQL GUI v5.27
Host - 5.1.35-community : Database - esg
*********************************************************************
Server version : 5.1.35-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `esg`;

USE `esg`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `files_for_content` */

DROP TABLE IF EXISTS `files_for_content`;

CREATE TABLE `files_for_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_attach` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `file_url` text,
  `file_type` varchar(255) DEFAULT NULL,
  `element_id` int(11) DEFAULT '0',
  `element_type` enum('static_page','news','team','tvprograms','partners') NOT NULL DEFAULT 'static_page',
  `file_size` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `files_for_content` */

/*Table structure for table `files_for_content_description` */

DROP TABLE IF EXISTS `files_for_content_description`;

CREATE TABLE `files_for_content_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `description` text,
  `file_id` int(11) DEFAULT NULL,
  `lang` enum('uk','ru','en') NOT NULL DEFAULT 'uk',
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `files_for_content_description` */

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_publish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `news` */

/*Table structure for table `news_content` */

DROP TABLE IF EXISTS `news_content`;

CREATE TABLE `news_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `news_id` int(11) DEFAULT NULL,
  `lang` enum('uk','ru','en') NOT NULL DEFAULT 'uk',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  CONSTRAINT `news_content_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `news_content` */

/*Table structure for table `partners` */

DROP TABLE IF EXISTS `partners`;

CREATE TABLE `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_url` varchar(255) DEFAULT NULL,
  `fancy_url` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `partners_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `partners` */

insert  into `partners`(`id`,`site_url`,`fancy_url`,`user_id`,`date_created`,`date_updated`,`is_show`) values (1,'','cnn',1,'2009-08-25 18:33:33','2009-08-25 18:33:33',1);

/*Table structure for table `partners_content` */

DROP TABLE IF EXISTS `partners_content`;

CREATE TABLE `partners_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `partners_id` int(11) DEFAULT NULL,
  `lang` enum('uk','ru','en') NOT NULL DEFAULT 'uk',
  PRIMARY KEY (`id`),
  KEY `partners_id` (`partners_id`),
  CONSTRAINT `partners_content_ibfk_1` FOREIGN KEY (`partners_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `partners_content` */

insert  into `partners_content`(`id`,`name`,`description`,`partners_id`,`lang`) values (1,'CNN','<p><strong>The Cable News Network</strong> или <strong>CNN</strong> (читается как <em>Си-Эн-Эн</em>) &mdash; телекомпания, созданная <a title=\"Тёрнер, Тед\" href=\"http://ru.wikipedia.org/wiki/%D0%A2%D1%91%D1%80%D0%BD%D0%B5%D1%80,_%D0%A2%D0%B5%D0%B4\">Тедом Тёрнером</a> в 1980 году. Является подразделением компании <a title=\"Turner Broadcasting System (страница отсутствует)\" href=\"http://ru.wikipedia.org/w/index.php?title=Turner_Broadcasting_System&amp;action=edit&amp;redlink=1\">Turner Broadcasting System</a> (Тёрнер), которой владеет <a title=\"Time Warner\" href=\"http://ru.wikipedia.org/wiki/Time_Warner\">Time Warner</a> (Тайм Уорнер).</p>',1,'uk'),(2,'CNN','<p><strong>The Cable News Network</strong> или <strong>CNN</strong> (читается как <em>Си-Эн-Эн</em>) &mdash; телекомпания, созданная <a title=\"Тёрнер, Тед\" href=\"http://ru.wikipedia.org/wiki/%D0%A2%D1%91%D1%80%D0%BD%D0%B5%D1%80,_%D0%A2%D0%B5%D0%B4\">Тедом Тёрнером</a> в 1980 году. Является подразделением компании <a title=\"Turner Broadcasting System (страница отсутствует)\" href=\"http://ru.wikipedia.org/w/index.php?title=Turner_Broadcasting_System&amp;action=edit&amp;redlink=1\">Turner Broadcasting System</a> (Тёрнер), которой владеет <a title=\"Time Warner\" href=\"http://ru.wikipedia.org/wiki/Time_Warner\">Time Warner</a> (Тайм Уорнер).</p>',1,'ru'),(3,'CNN','<p><strong>The Cable News Network</strong> или <strong>CNN</strong> (читается как <em>Си-Эн-Эн</em>) &mdash; телекомпания, созданная <a title=\"Тёрнер, Тед\" href=\"http://ru.wikipedia.org/wiki/%D0%A2%D1%91%D1%80%D0%BD%D0%B5%D1%80,_%D0%A2%D0%B5%D0%B4\">Тедом Тёрнером</a> в 1980 году. Является подразделением компании <a title=\"Turner Broadcasting System (страница отсутствует)\" href=\"http://ru.wikipedia.org/w/index.php?title=Turner_Broadcasting_System&amp;action=edit&amp;redlink=1\">Turner Broadcasting System</a> (Тёрнер), которой владеет <a title=\"Time Warner\" href=\"http://ru.wikipedia.org/wiki/Time_Warner\">Time Warner</a> (Тайм Уорнер).</p>',1,'en');

/*Table structure for table `sitetree` */

DROP TABLE IF EXISTS `sitetree`;

CREATE TABLE `sitetree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `page_id` int(11) DEFAULT '0',
  `element_type` enum('static_page','news','team','tvprograms','partners') NOT NULL DEFAULT 'static_page',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `name` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `sitetree` */

insert  into `sitetree`(`id`,`lft`,`rgt`,`level`,`page_id`,`element_type`,`is_active`) values (1,0,11,0,1,'static_page',1),(2,1,2,1,0,'news',1),(3,3,4,1,0,'team',1),(4,5,6,1,0,'partners',1),(5,7,10,1,2,'static_page',1),(6,8,9,2,0,'tvprograms',1);

/*Table structure for table `static_pages` */

DROP TABLE IF EXISTS `static_pages`;

CREATE TABLE `static_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `static_pages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `static_pages` */

insert  into `static_pages`(`id`,`fancy_url`,`user_id`,`date_created`,`date_updated`) values (1,'homepage',1,'2009-08-19 12:00:00','2009-08-20 17:47:03'),(2,'texnichne-zabezpechennya',1,'2009-08-26 16:58:22','2009-08-26 16:58:22'),(3,'page',1,'2009-08-28 13:01:29','2009-08-28 13:01:29');

/*Table structure for table `static_pages_content` */

DROP TABLE IF EXISTS `static_pages_content`;

CREATE TABLE `static_pages_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `page_id` int(11) DEFAULT NULL,
  `lang` enum('uk','ru','en') NOT NULL DEFAULT 'uk',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `static_pages_content_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `static_pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `static_pages_content` */

insert  into `static_pages_content`(`id`,`title`,`content`,`page_id`,`lang`) values (1,'Homepage','<p>UK First page content</p>',1,'uk'),(2,'Homepage','<p>RU First page content</p>',1,'ru'),(3,'Homapage','<p>EN First page content0</p>',1,'en'),(4,'Технічне забезпечення','<p>Комунікаційна група ESG завжди приділяла особливу увагу технічному забезпеченню своєї діяльності. Сьогодні в розпорядженні співробітників редакції перебуває <strong>19 телевізійно-<img src=\"http://esg.ua/img/forall/mk1.jpg\" border=\"0\" alt=\"\" hspace=\"5\" vspace=\"5\" width=\"200\" height=\"150\" align=\"right\" />журналістських комплектів</strong>, за допомогою яких глядачі отримують оперативні відеоматеріали, що охоплюють усі економічні події України.</p>\r\n<p align=\"justify\"><strong>Сучасний ньюз-рум</strong>, в якому створюються новини для всіх телевізійних проектів Комунікаційної групи ESG, був впроваджений в компанії з метою оптимізації виробництва за рахунок максимальної автоматизації процесу. Технологія передбачає, що з одним матеріалом, який оцифровується з ТЖК (телевізійно-журналістського комплекту) на сервер, можуть працювати одночасно декілька співробітників. Ньюз-рум складається з частин різних виробників: текстова частина забезпечується за допомогою софта компанії Octopus, відео - Ardendo, інтегратором є компанія Comtel.</p>',2,'uk'),(5,'Технічне забезпечення','<p>Комунікаційна група ESG завжди приділяла особливу увагу технічному забезпеченню своєї діяльності. Сьогодні в розпорядженні співробітників редакції перебуває <strong>19 телевізійно-<img src=\"http://esg.ua/img/forall/mk1.jpg\" border=\"0\" alt=\"\" hspace=\"5\" vspace=\"5\" width=\"200\" height=\"150\" align=\"right\" />журналістських комплектів</strong>, за допомогою яких глядачі отримують оперативні відеоматеріали, що охоплюють усі економічні події України.</p>\r\n<p align=\"justify\"><strong>Сучасний ньюз-рум</strong>, в якому створюються новини для всіх телевізійних проектів Комунікаційної групи ESG, був впроваджений в компанії з метою оптимізації виробництва за рахунок максимальної автоматизації процесу. Технологія передбачає, що з одним матеріалом, який оцифровується з ТЖК (телевізійно-журналістського комплекту) на сервер, можуть працювати одночасно декілька співробітників. Ньюз-рум складається з частин різних виробників: текстова частина забезпечується за допомогою софта компанії Octopus, відео - Ardendo, інтегратором є компанія Comtel.</p>',2,'ru'),(6,'Технічне забезпечення','<p>Комунікаційна група ESG завжди приділяла особливу увагу технічному забезпеченню своєї діяльності. Сьогодні в розпорядженні співробітників редакції перебуває <strong>19 телевізійно-<img src=\"http://esg.ua/img/forall/mk1.jpg\" border=\"0\" alt=\"\" hspace=\"5\" vspace=\"5\" width=\"200\" height=\"150\" align=\"right\" />журналістських комплектів</strong>, за допомогою яких глядачі отримують оперативні відеоматеріали, що охоплюють усі економічні події України.</p>\r\n<p align=\"justify\"><strong>Сучасний ньюз-рум</strong>, в якому створюються новини для всіх телевізійних проектів Комунікаційної групи ESG, був впроваджений в компанії з метою оптимізації виробництва за рахунок максимальної автоматизації процесу. Технологія передбачає, що з одним матеріалом, який оцифровується з ТЖК (телевізійно-журналістського комплекту) на сервер, можуть працювати одночасно декілька співробітників. Ньюз-рум складається з частин різних виробників: текстова частина забезпечується за допомогою софта компанії Octopus, відео - Ardendo, інтегратором є компанія Comtel.</p>',2,'en'),(7,'Page','<p>Page</p>',3,'uk'),(8,'Page','<p>Page</p>',3,'ru'),(9,'Page','<p>Page</p>',3,'en');

/*Table structure for table `team` */

DROP TABLE IF EXISTS `team`;

CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `team_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `team` */

insert  into `team`(`id`,`fancy_url`,`user_id`,`date_created`,`date_updated`,`is_show`) values (1,'kaplunenko',1,'2009-08-25 14:35:30','2009-08-25 18:36:37',1);

/*Table structure for table `team_content` */

DROP TABLE IF EXISTS `team_content`;

CREATE TABLE `team_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `post` text,
  `team_id` int(11) DEFAULT NULL,
  `lang` enum('uk','ru','en') NOT NULL DEFAULT 'uk',
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `team_content_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `team_content` */

insert  into `team_content`(`id`,`name`,`post`,`team_id`,`lang`) values (1,'Каплуненко Юрій Володимирович','<p>Генеральний директор</p>',1,'uk'),(2,'Каплуненко Юрій Володимирович','<p>Генеральний директор</p>',1,'ru'),(3,'Каплуненко Юрій Володимирович','<p>Генеральний директор</p>',1,'en');

/*Table structure for table `tvprograms` */

DROP TABLE IF EXISTS `tvprograms`;

CREATE TABLE `tvprograms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fancy_url` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tvprograms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tvprograms` */

/*Table structure for table `tvprograms_content` */

DROP TABLE IF EXISTS `tvprograms_content`;

CREATE TABLE `tvprograms_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `tvprograms_id` int(11) DEFAULT NULL,
  `lang` enum('uk','ru','en') NOT NULL DEFAULT 'uk',
  PRIMARY KEY (`id`),
  KEY `tvprograms_id` (`tvprograms_id`),
  CONSTRAINT `tvprograms_content_ibfk_1` FOREIGN KEY (`tvprograms_id`) REFERENCES `tvprograms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tvprograms_content` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`login`,`password`,`email`,`role`,`first_name`,`last_name`) values (1,'admin','admin','admin@example.com','admin','Admin','Tester');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
