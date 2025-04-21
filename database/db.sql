/*
SQLyog Community v13.1.9 (64 bit)
MySQL - 8.0.30 : Database - absensi_jnt
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`absensi_jnt` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `absensi_jnt`;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_reset_tokens_table',1),
(3,'2014_10_12_100000_create_password_resets_table',1),
(4,'2019_08_19_000000_create_failed_jobs_table',1),
(5,'2019_12_14_000001_create_personal_access_tokens_table',1);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin','superadmin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'user.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'id',
  `notification_email` tinyint(1) NOT NULL DEFAULT '1',
  `notification_web` tinyint(1) NOT NULL DEFAULT '1',
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isActive` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`role`,`foto`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`,`theme_mode`,`timezone`,`language`,`notification_email`,`notification_web`,`nik`,`isActive`,`deleted_at`) values 
(1,'Super Admin','superadmin@example.com','superadmin','user.png',NULL,'$2y$12$5v8IS9RlFJuJqMbcx9HHVe3Z7TziKaVWF2FCmEQQcQiLw2vCaypYm',NULL,'2025-04-19 11:44:59','2025-04-19 11:44:59','light','Asia/Jakarta','id',1,1,NULL,0,NULL),
(2,'Admin User','admin@example.com','admin','68039cc038e41.jpg',NULL,'$2y$12$avwsAdJ5XIg9f9KfryzWAegvL8tBAEfcBohOBrsX5rR2n94U0WdIi',NULL,'2025-04-19 11:44:59','2025-04-19 13:02:15','light','Asia/Jakarta','id',1,1,'123456789',0,NULL),
(4,'Dean Batz','admin1@example.com','admin','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','2TrA5Qmvp0','2025-04-19 13:34:15','2025-04-19 13:34:15','light','Asia/Jakarta','id',1,1,NULL,0,NULL),
(5,'Meggie Goyette','admin2@example.com','admin','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','b31hsK1ls8','2025-04-19 13:34:15','2025-04-19 13:34:15','light','Asia/Jakarta','id',1,1,NULL,0,NULL),
(6,'Laurie Durgan DDS','user1@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','DSfRiNLi40','2025-04-19 13:34:15','2025-04-19 14:45:19','light','Asia/Jakarta','id',1,1,NULL,0,'2025-04-19 14:45:19'),
(7,'Velva Greenholt','user2@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','ooUiDmjUCD','2025-04-19 13:34:15','2025-04-19 14:54:51','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(8,'Albina Hermann','user3@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','SIteoF4D0t','2025-04-19 13:34:15','2025-04-19 14:55:06','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(9,'Oral Koelpin Jr.','user4@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','uf70zaLIkF','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(10,'Thad Erdman','user5@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','sfOEbasuZb','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(11,'Dina Bechtelar IV','user6@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','Migo3N0NLa','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(12,'Eldridge Dickens','user7@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','PsmKEwnZRN','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(13,'Annie Ryan III','user8@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','EuWyFndft2','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(14,'Karelle Schultz','user9@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','ZdWOHeQdY5','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(15,'Prof. Lavon Heller','user10@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','NHZMqoaf97','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(16,'Nora Keebler','user11@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','0cBxWIAvUx','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(17,'Minnie Pfeffer','user12@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','YfD6hScU1i','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(18,'Prof. Trenton Barton III','user13@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','sh0qPo3DOt','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(19,'Kaela Thompson','user14@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','4NQRjt2WcW','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(20,'Eveline Rempel','user15@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','RpuyS40FwC','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(21,'Israel Stark','user16@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','LVr2im3Q5L','2025-04-19 13:34:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(22,'Mayra Abernathy','user17@example.com','user','user.png','2025-04-19 13:34:15','$2y$12$bZMkHupopocLkL7w3m.2C.02YfbFFvhptOrOm5RsC3pOP40CT7lAu','qEU3aOekqN','2025-04-19 13:34:15','2025-04-19 13:40:55','light','Asia/Jakarta','id',1,1,NULL,0,'2025-04-19 13:40:55'),
(23,'Teti','teti@example.com','user','user.png',NULL,'$2y$12$9TNAv/kUop55UIpXLhSSpu2AAm7Lwde2eLOHbrRICHtiGo0PnlpO.',NULL,'2025-04-19 13:50:15','2025-04-19 15:03:21','light','Asia/Jakarta','id',1,1,NULL,1,NULL),
(24,'admin2','admin3@example.com','admin','user.png',NULL,'$2y$12$5w.ewKUj30uWcngPhYxc/ORJ9Wkrtael.m8laH.ODJ4Hsu/9pefXm',NULL,'2025-04-19 15:35:32','2025-04-19 15:36:19','light','Asia/Jakarta','id',1,1,NULL,0,'2025-04-19 15:36:19'),
(25,'admin4','admin4@example.com','admin','user.png',NULL,'$2y$12$Ul0/qRtevUzOzyIL4OYAVOziGCHa4WDklfldP3m8jU1pkCwXG87MC',NULL,'2025-04-19 15:36:54','2025-04-19 15:36:54','light','Asia/Jakarta','id',1,1,NULL,0,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
