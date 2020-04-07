# ************************************************************
# Sequel Pro SQL dump
# Version (null)
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.29-0ubuntu0.18.04.1)
# Database: voltage
# Generation Time: 2020-04-07 07:09:38 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_menu`;

CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;

INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`)
VALUES
	(1,0,1,'Dashboard','fa-bar-chart-o','/',NULL,NULL,'2020-04-03 07:59:04'),
	(2,0,9,'Admin','fas fa-tasks','',NULL,NULL,'2020-04-03 07:57:16'),
	(3,2,10,'Users','fas fa-users','auth/users',NULL,NULL,'2020-04-03 07:57:16'),
	(4,2,11,'Roles','fas fa-user','auth/roles',NULL,NULL,'2020-04-03 07:57:16'),
	(5,2,12,'Permission','fas fa-ban','auth/permissions',NULL,NULL,'2020-04-03 07:57:16'),
	(6,2,13,'Menu','fas fa-bars','auth/menu',NULL,NULL,'2020-04-03 07:57:16'),
	(7,2,14,'Operation log','fas fa-history','auth/logs',NULL,NULL,'2020-04-03 07:57:16'),
	(8,14,4,'Company','fa-institution','clients',NULL,'2020-03-04 02:51:42','2020-04-03 07:59:04'),
	(9,0,2,'Projects','fa-product-hunt','projects',NULL,'2020-03-04 02:57:23','2020-04-03 07:59:04'),
	(10,14,5,'Contacts','fa-user','contacts',NULL,'2020-03-20 05:49:27','2020-04-03 07:59:04'),
	(11,0,6,'Carriers','fa-connectdevelop','carriers',NULL,'2020-03-30 03:40:00','2020-04-03 07:57:16'),
	(12,0,7,'Factories','fa-legal','factories',NULL,'2020-03-30 08:14:22','2020-04-03 07:57:16'),
	(13,0,8,'Ports','fa-ship','ports',NULL,'2020-04-01 05:18:45','2020-04-03 07:57:16'),
	(14,0,3,'Clients','fa-users',NULL,NULL,'2020-04-03 07:57:02','2020-04-03 07:59:04');

/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_operation_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_operation_log`;

CREATE TABLE `admin_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_operation_log_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_operation_log` WRITE;
/*!40000 ALTER TABLE `admin_operation_log` DISABLE KEYS */;

INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`)
VALUES
	(1,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:52:36','2020-04-07 06:52:36'),
	(2,1,'admin','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:38','2020-04-07 06:52:38'),
	(3,1,'admin/projects','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:38','2020-04-07 06:52:38'),
	(4,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:40','2020-04-07 06:52:40'),
	(5,1,'admin/contacts','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:40','2020-04-07 06:52:40'),
	(6,1,'admin/carriers','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:41','2020-04-07 06:52:41'),
	(7,1,'admin/factories','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:42','2020-04-07 06:52:42'),
	(8,1,'admin/ports','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:42','2020-04-07 06:52:42'),
	(9,1,'admin/auth/users','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:44','2020-04-07 06:52:44'),
	(10,1,'admin/auth/roles','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:45','2020-04-07 06:52:45'),
	(11,1,'admin/auth/users','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:46','2020-04-07 06:52:46'),
	(12,1,'admin/projects','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:47','2020-04-07 06:52:47'),
	(13,1,'admin/projects/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:49','2020-04-07 06:52:49'),
	(14,1,'admin/projects/create','GET','183.132.190.221','[]','2020-04-07 06:52:49','2020-04-07 06:52:49'),
	(15,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:52','2020-04-07 06:52:52'),
	(16,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:52:54','2020-04-07 06:52:54'),
	(17,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Voltage NC, LLC\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:53:31','2020-04-07 06:53:31'),
	(18,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:53:31','2020-04-07 06:53:31'),
	(19,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:53:35','2020-04-07 06:53:35'),
	(20,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Moss\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:53:40','2020-04-07 06:53:40'),
	(21,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:53:40','2020-04-07 06:53:40'),
	(22,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:53:47','2020-04-07 06:53:47'),
	(23,1,'admin/clients','POST','183.132.190.221','{\"name\":\"McCarthy Building Companies\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:53:49','2020-04-07 06:53:49'),
	(24,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:53:50','2020-04-07 06:53:50'),
	(25,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:53:55','2020-04-07 06:53:55'),
	(26,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Baker Electric\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:53:57','2020-04-07 06:53:57'),
	(27,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:53:57','2020-04-07 06:53:57'),
	(28,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:54:02','2020-04-07 06:54:02'),
	(29,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Strata Solar\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:54:04','2020-04-07 06:54:04'),
	(30,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:54:04','2020-04-07 06:54:04'),
	(31,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:54:13','2020-04-07 06:54:13'),
	(32,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Axis Energy\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:54:14','2020-04-07 06:54:14'),
	(33,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:54:14','2020-04-07 06:54:14'),
	(34,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:54:21','2020-04-07 06:54:21'),
	(35,1,'admin/clients','POST','183.132.190.221','{\"name\":\"City Electric Supply\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:54:24','2020-04-07 06:54:24'),
	(36,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:54:24','2020-04-07 06:54:24'),
	(37,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:54:32','2020-04-07 06:54:32'),
	(38,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Consolidated Electrical Distributors\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:54:34','2020-04-07 06:54:34'),
	(39,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:54:34','2020-04-07 06:54:34'),
	(40,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:54:36','2020-04-07 06:54:36'),
	(41,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Consolidated Electrical Distributors\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:54:37','2020-04-07 06:54:37'),
	(42,1,'admin/clients/create','GET','183.132.190.221','[]','2020-04-07 06:54:37','2020-04-07 06:54:37'),
	(43,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Cypress Creek Renewables\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\"}','2020-04-07 06:54:46','2020-04-07 06:54:46'),
	(44,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:54:46','2020-04-07 06:54:46'),
	(45,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:54:47','2020-04-07 06:54:47'),
	(46,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Depcom Power\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:54:56','2020-04-07 06:54:56'),
	(47,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:54:56','2020-04-07 06:54:56'),
	(48,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:02','2020-04-07 06:55:02'),
	(49,1,'admin/clients','POST','183.132.190.221','{\"name\":\"E8 Energy Group\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:04','2020-04-07 06:55:04'),
	(50,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:04','2020-04-07 06:55:04'),
	(51,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:09','2020-04-07 06:55:09'),
	(52,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Gexpro\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:11','2020-04-07 06:55:11'),
	(53,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:11','2020-04-07 06:55:11'),
	(54,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:17','2020-04-07 06:55:17'),
	(55,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Vallen\\/Hagemeyer\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:19','2020-04-07 06:55:19'),
	(56,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:19','2020-04-07 06:55:19'),
	(57,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:26','2020-04-07 06:55:26'),
	(58,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Helix Electric Inc\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:28','2020-04-07 06:55:28'),
	(59,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:28','2020-04-07 06:55:28'),
	(60,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:35','2020-04-07 06:55:35'),
	(61,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Hypower Inc\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:37','2020-04-07 06:55:37'),
	(62,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:37','2020-04-07 06:55:37'),
	(63,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:42','2020-04-07 06:55:42'),
	(64,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Miller Brothers Solar\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:45','2020-04-07 06:55:45'),
	(65,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:45','2020-04-07 06:55:45'),
	(66,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:50','2020-04-07 06:55:50'),
	(67,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Mortenson\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:55:52','2020-04-07 06:55:52'),
	(68,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:55:52','2020-04-07 06:55:52'),
	(69,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:55:58','2020-04-07 06:55:58'),
	(70,1,'admin/clients','POST','183.132.190.221','{\"name\":\"National Renewable Energy Corporation\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:56:00','2020-04-07 06:56:00'),
	(71,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:56:00','2020-04-07 06:56:00'),
	(72,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:06','2020-04-07 06:56:06'),
	(73,1,'admin/clients','POST','183.132.190.221','{\"name\":\"NextEra Energy\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:56:08','2020-04-07 06:56:08'),
	(74,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:56:08','2020-04-07 06:56:08'),
	(75,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:14','2020-04-07 06:56:14'),
	(76,1,'admin/clients','POST','183.132.190.221','{\"name\":\"O2 Energy\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:56:16','2020-04-07 06:56:16'),
	(77,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:56:16','2020-04-07 06:56:16'),
	(78,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:23','2020-04-07 06:56:23'),
	(79,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Pacific Patriot Electrical\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients\"}','2020-04-07 06:56:25','2020-04-07 06:56:25'),
	(80,1,'admin/clients','GET','183.132.190.221','[]','2020-04-07 06:56:25','2020-04-07 06:56:25'),
	(81,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\",\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:35','2020-04-07 06:56:35'),
	(82,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:37','2020-04-07 06:56:37'),
	(83,1,'admin/clients','POST','183.132.190.221','{\"name\":\"SolarBos\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:56:39','2020-04-07 06:56:39'),
	(84,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:56:39','2020-04-07 06:56:39'),
	(85,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:46','2020-04-07 06:56:46'),
	(86,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Summit Solar\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:56:48','2020-04-07 06:56:48'),
	(87,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:56:48','2020-04-07 06:56:48'),
	(88,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:56:53','2020-04-07 06:56:53'),
	(89,1,'admin/clients','POST','183.132.190.221','{\"name\":\"SunEnergy1\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:56:55','2020-04-07 06:56:55'),
	(90,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:56:55','2020-04-07 06:56:55'),
	(91,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:57:02','2020-04-07 06:57:02'),
	(92,1,'admin/clients','POST','183.132.190.221','{\"name\":\"The Whiting-Turner Contracting Company\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:57:04','2020-04-07 06:57:04'),
	(93,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:57:04','2020-04-07 06:57:04'),
	(94,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:57:10','2020-04-07 06:57:10'),
	(95,1,'admin/clients','POST','183.132.190.221','{\"name\":\"United Renewable Energy LLC\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:57:12','2020-04-07 06:57:12'),
	(96,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:57:12','2020-04-07 06:57:12'),
	(97,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:57:17','2020-04-07 06:57:17'),
	(98,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Vanguard Energy Partners\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:57:19','2020-04-07 06:57:19'),
	(99,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:57:19','2020-04-07 06:57:19'),
	(100,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:57:25','2020-04-07 06:57:25'),
	(101,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Wanzek Construction Inc\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:57:26','2020-04-07 06:57:26'),
	(102,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:57:26','2020-04-07 06:57:26'),
	(103,1,'admin/clients/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 06:57:31','2020-04-07 06:57:31'),
	(104,1,'admin/clients','POST','183.132.190.221','{\"name\":\"Wesco Assembly\",\"tel\":null,\"address\":null,\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\",\"_previous_\":\"http:\\/\\/erocode.com\\/admin\\/clients?page=2\"}','2020-04-07 06:57:33','2020-04-07 06:57:33'),
	(105,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 06:57:33','2020-04-07 06:57:33'),
	(106,1,'admin/clients','GET','183.132.190.221','{\"page\":\"2\"}','2020-04-07 07:06:46','2020-04-07 07:06:46'),
	(107,1,'admin/projects','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:47','2020-04-07 07:06:47'),
	(108,1,'admin','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:48','2020-04-07 07:06:48'),
	(109,1,'admin/projects','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:49','2020-04-07 07:06:49'),
	(110,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:50','2020-04-07 07:06:50'),
	(111,1,'admin/contacts','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:50','2020-04-07 07:06:50'),
	(112,1,'admin/carriers','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:51','2020-04-07 07:06:51'),
	(113,1,'admin/factories','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:52','2020-04-07 07:06:52'),
	(114,1,'admin/ports','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:52','2020-04-07 07:06:52'),
	(115,1,'admin/auth/users','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:53','2020-04-07 07:06:53'),
	(116,1,'admin/auth/roles','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:54','2020-04-07 07:06:54'),
	(117,1,'admin/auth/permissions','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:54','2020-04-07 07:06:54'),
	(118,1,'admin/projects','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:57','2020-04-07 07:06:57'),
	(119,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:06:58','2020-04-07 07:06:58'),
	(120,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\",\"page\":\"2\"}','2020-04-07 07:07:01','2020-04-07 07:07:01'),
	(121,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\",\"page\":\"1\"}','2020-04-07 07:07:03','2020-04-07 07:07:03'),
	(122,1,'admin/clients','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\",\"page\":\"2\"}','2020-04-07 07:07:08','2020-04-07 07:07:08'),
	(123,1,'admin/projects','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:07:13','2020-04-07 07:07:13'),
	(124,1,'admin/projects/create','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:07:15','2020-04-07 07:07:15'),
	(125,1,'admin/projects/create','GET','183.132.190.221','[]','2020-04-07 07:07:15','2020-04-07 07:07:15'),
	(126,1,'admin/projects','POST','183.132.190.221','{\"client_id\":\"1\",\"name\":\"test\",\"number\":null,\"_token\":\"dN3OQMNddJa2DmDGZZrnwVYhwFefphUQg7o9Oj1H\"}','2020-04-07 07:07:42','2020-04-07 07:07:42'),
	(127,1,'admin/projects','GET','183.132.190.221','[]','2020-04-07 07:07:42','2020-04-07 07:07:42'),
	(128,1,'admin/projects/1','GET','183.132.190.221','{\"_pjax\":\"#pjax-container\"}','2020-04-07 07:07:48','2020-04-07 07:07:48'),
	(129,1,'admin/projects/1','GET','183.132.190.221','[]','2020-04-07 07:07:48','2020-04-07 07:07:48'),
	(130,1,'admin/projects/1','GET','183.132.190.221','[]','2020-04-07 07:07:49','2020-04-07 07:07:49'),
	(131,1,'admin/carrier-list','GET','183.132.190.221','[]','2020-04-07 07:07:49','2020-04-07 07:07:49'),
	(132,1,'admin/factory-list','GET','183.132.190.221','[]','2020-04-07 07:07:49','2020-04-07 07:07:49'),
	(133,1,'admin/carrier-list','GET','183.132.190.221','[]','2020-04-07 07:07:49','2020-04-07 07:07:49'),
	(134,1,'admin/factory-list','GET','183.132.190.221','[]','2020-04-07 07:07:49','2020-04-07 07:07:49'),
	(135,1,'admin/port-list','GET','183.132.190.221','[]','2020-04-07 07:07:49','2020-04-07 07:07:49');

/*!40000 ALTER TABLE `admin_operation_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_permissions`;

CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_permissions_name_unique` (`name`),
  UNIQUE KEY `admin_permissions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`)
VALUES
	(1,'All permission','*','','*',NULL,NULL),
	(2,'Dashboard','dashboard','GET','/',NULL,NULL),
	(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),
	(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),
	(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL);

/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_menu`;

CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`, `created_at`, `updated_at`)
VALUES
	(1,2,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_permissions`;

CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
VALUES
	(1,1,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_users`;

CREATE TABLE `admin_role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`)
VALUES
	(1,1,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_roles_name_unique` (`name`),
  UNIQUE KEY `admin_roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,'Administrator','administrator','2020-03-04 02:49:04','2020-03-04 02:49:04');

/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_user_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user_permissions`;

CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_user_permissions_user_id_permission_id_index` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'admin','$2y$10$RGH3SMWrPHV977BxsP1qY.Fjtfy55I1aGaZr8C.J6PAIbLop8BG.O','Administrator','images/Logo.jpg','1dT0z24AyjkDhJIzg34KqIxS1fChnv7OXXI2u16WYDbTTMpKo6dgHM0qoYsO','2020-03-04 02:49:04','2020-03-04 09:27:51');

/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `batches`;

CREATE TABLE `batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `po_factory_id` int(10) unsigned NOT NULL COMMENT 'Po Factory ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '发货批次',
  `sequence` int(10) unsigned NOT NULL COMMENT '序号',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `estimated_production_completion` timestamp NULL DEFAULT NULL COMMENT '预计生产完成时间',
  `etd_port` timestamp NULL DEFAULT NULL COMMENT '预计离岗时间',
  `eta_port` timestamp NULL DEFAULT NULL COMMENT '预计到港时间',
  `eta_job_site` timestamp NULL DEFAULT NULL COMMENT '预计到项目点时间',
  `actual_production_completion` timestamp NULL DEFAULT NULL COMMENT '实际生产完成时间',
  `atd_port` timestamp NULL DEFAULT NULL COMMENT '实际离岗时间',
  `ata_port` timestamp NULL DEFAULT NULL COMMENT '实际到港时间',
  `ata_job_site` timestamp NULL DEFAULT NULL COMMENT '实际到项目点时间',
  `carrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '船公司名称',
  `b_l` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '提单号码',
  `vessel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '船的编码',
  `container_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '柜子编码',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '有几个柜子',
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '运输方式',
  `rmb` decimal(10,2) DEFAULT NULL COMMENT '人民币',
  `foreign_currency` decimal(10,2) DEFAULT NULL COMMENT '外币',
  `foreign_currency_type` int(11) DEFAULT NULL COMMENT '外币类型',
  `port_of_departure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '启运港口',
  `destination_port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '目的地港口',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table carriers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `carriers`;

CREATE TABLE `carriers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客户名称',
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号',
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户电话',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户联系地址',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_number_unique` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;

INSERT INTO `clients` (`id`, `name`, `number`, `tel`, `address`, `created_at`, `updated_at`)
VALUES
	(1,'Voltage NC, LLC','000',NULL,NULL,'2020-04-07 06:53:31','2020-04-07 06:53:31'),
	(2,'Moss','100',NULL,NULL,'2020-04-07 06:53:40','2020-04-07 06:53:40'),
	(3,'McCarthy Building Companies','101',NULL,NULL,'2020-04-07 06:53:49','2020-04-07 06:53:49'),
	(4,'Baker Electric','102',NULL,NULL,'2020-04-07 06:53:57','2020-04-07 06:53:57'),
	(5,'Strata Solar','103',NULL,NULL,'2020-04-07 06:54:04','2020-04-07 06:54:04'),
	(6,'Axis Energy','104',NULL,NULL,'2020-04-07 06:54:14','2020-04-07 06:54:14'),
	(7,'City Electric Supply','105',NULL,NULL,'2020-04-07 06:54:24','2020-04-07 06:54:24'),
	(8,'Consolidated Electrical Distributors','106',NULL,NULL,'2020-04-07 06:54:34','2020-04-07 06:54:34'),
	(9,'Cypress Creek Renewables','107',NULL,NULL,'2020-04-07 06:54:46','2020-04-07 06:54:46'),
	(10,'Depcom Power','108',NULL,NULL,'2020-04-07 06:54:56','2020-04-07 06:54:56'),
	(11,'E8 Energy Group','109',NULL,NULL,'2020-04-07 06:55:04','2020-04-07 06:55:04'),
	(12,'Gexpro','110',NULL,NULL,'2020-04-07 06:55:11','2020-04-07 06:55:11'),
	(13,'Vallen/Hagemeyer','111',NULL,NULL,'2020-04-07 06:55:19','2020-04-07 06:55:19'),
	(14,'Helix Electric Inc','112',NULL,NULL,'2020-04-07 06:55:28','2020-04-07 06:55:28'),
	(15,'Hypower Inc','113',NULL,NULL,'2020-04-07 06:55:37','2020-04-07 06:55:37'),
	(16,'Miller Brothers Solar','114',NULL,NULL,'2020-04-07 06:55:45','2020-04-07 06:55:45'),
	(17,'Mortenson','115',NULL,NULL,'2020-04-07 06:55:52','2020-04-07 06:55:52'),
	(18,'National Renewable Energy Corporation','116',NULL,NULL,'2020-04-07 06:56:00','2020-04-07 06:56:00'),
	(19,'NextEra Energy','117',NULL,NULL,'2020-04-07 06:56:08','2020-04-07 06:56:08'),
	(20,'O2 Energy','118',NULL,NULL,'2020-04-07 06:56:16','2020-04-07 06:56:16'),
	(21,'Pacific Patriot Electrical','119',NULL,NULL,'2020-04-07 06:56:25','2020-04-07 06:56:25'),
	(22,'SolarBos','120',NULL,NULL,'2020-04-07 06:56:39','2020-04-07 06:56:39'),
	(23,'Summit Solar','121',NULL,NULL,'2020-04-07 06:56:48','2020-04-07 06:56:48'),
	(24,'SunEnergy1','122',NULL,NULL,'2020-04-07 06:56:55','2020-04-07 06:56:55'),
	(25,'The Whiting-Turner Contracting Company','123',NULL,NULL,'2020-04-07 06:57:04','2020-04-07 06:57:04'),
	(26,'United Renewable Energy LLC','124',NULL,NULL,'2020-04-07 06:57:12','2020-04-07 06:57:12'),
	(27,'Vanguard Energy Partners','125',NULL,NULL,'2020-04-07 06:57:19','2020-04-07 06:57:19'),
	(28,'Wanzek Construction Inc','126',NULL,NULL,'2020-04-07 06:57:26','2020-04-07 06:57:26'),
	(29,'Wesco Assembly','127',NULL,NULL,'2020-04-07 06:57:33','2020-04-07 06:57:33');

/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL COMMENT '客户ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '电话',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table factories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `factories`;

CREATE TABLE `factories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '工厂名称',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工厂地址',
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工厂电话',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `factories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2016_01_04_173148_create_admin_tables',1),
	(3,'2019_08_19_000000_create_failed_jobs_table',1),
	(4,'2020_03_04_022127_create_clients_table',1),
	(5,'2020_03_04_022128_create_projects_table',1),
	(6,'2020_03_04_063848_create_po_factories_table',1),
	(7,'2020_03_04_082907_create_batches_table',1),
	(8,'2020_03_10_053147_create_po_clients_table',1),
	(9,'2020_03_30_032908_create_carriers_table',1),
	(10,'2020_03_30_080111_create_contacts_table',1),
	(11,'2020_03_30_081124_create_factories_table',1),
	(12,'2020_03_31_082456_create_ports_table',1),
	(13,'2020_04_03_021955_create_po_factory_histories_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table po_clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `po_clients`;

CREATE TABLE `po_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL COMMENT '项目ID',
  `no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户订单编号',
  `client_delivery_time` timestamp NULL DEFAULT NULL COMMENT '客户要求到货时间',
  `po_date` timestamp NULL DEFAULT NULL COMMENT '客户下单日期',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table po_factories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `po_factories`;

CREATE TABLE `po_factories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `po_client_id` int(10) unsigned NOT NULL,
  `factory_id` int(10) unsigned DEFAULT NULL,
  `type` int(10) unsigned NOT NULL,
  `no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号自动生成',
  `number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前版本号',
  `remarks` longtext COLLATE utf8mb4_unicode_ci COMMENT 'remarks',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table po_factory_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `po_factory_histories`;

CREATE TABLE `po_factory_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `po_factory_id` int(10) unsigned NOT NULL,
  `po_client_id` int(10) unsigned NOT NULL,
  `factory_id` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号自动生成',
  `number` int(10) unsigned NOT NULL COMMENT '当前版本号',
  `remarks` longtext COLLATE utf8mb4_unicode_ci COMMENT 'remarks',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ports
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ports`;

CREATE TABLE `ports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '港口名称',
  `type` tinyint(1) NOT NULL COMMENT '0国内，1海外',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL COMMENT '客户表ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '项目名称',
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '项目编号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
