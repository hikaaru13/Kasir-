/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - project_kasir
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`project_kasir` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `project_kasir`;

/*Table structure for table `attributes` */

DROP TABLE IF EXISTS `attributes`;

CREATE TABLE `attributes` (
  `attribute_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `attribute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`attribute_id`),
  KEY `attributes_user_id_foreign` (`user_id`),
  CONSTRAINT `attributes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `attributes` */

/*Table structure for table `menu_types` */

DROP TABLE IF EXISTS `menu_types`;

CREATE TABLE `menu_types` (
  `menu_type_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `menu_types` */

insert  into `menu_types`(`menu_type_id`,`menu_type`,`created_at`,`updated_at`) values 
(1,'Menu','2024-11-29 11:33:00','2024-11-29 11:33:00'),
(2,'Submenu','2024-11-29 11:33:00','2024-11-29 11:33:00');

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `menu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_redirect` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_sort` int NOT NULL DEFAULT '0',
  `menu_type_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`menu_id`),
  KEY `menus_menu_type_id_foreign` (`menu_type_id`),
  CONSTRAINT `menus_menu_type_id_foreign` FOREIGN KEY (`menu_type_id`) REFERENCES `menu_types` (`menu_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `menus` */

insert  into `menus`(`menu_id`,`menu`,`menu_slug`,`menu_icon`,`menu_redirect`,`menu_sort`,`menu_type_id`,`created_at`,`updated_at`) values 
(1,'users','users','users','/users',1,1,'2024-11-29 11:33:00','2024-11-29 11:33:00'),
(2,'product','product','box','/product',2,1,'2024-11-29 11:37:36','2024-11-29 11:37:36'),
(3,'transaction','transaction','shopping-cart','/transaction',3,1,'2024-11-29 12:12:13','2024-11-29 12:12:13');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2019_12_14_000001_create_personal_access_tokens_table',1),
(2,'2024_07_25_095047_create_roles_table',1),
(3,'2024_07_25_095511_create_users_table',1),
(4,'2024_07_30_093021_create_attributes_table',1),
(5,'2024_08_20_085946_create_role_access_table',1),
(6,'2024_08_20_092612_create_menu_types_table',1),
(7,'2024_08_20_092649_create_menus_table',1),
(8,'2024_08_21_044059_create_submenus_table',1),
(12,'2024_08_21_044060_create_role_permission_table',2),
(13,'2024_11_29_113736_create_products_table',2),
(14,'2024_11_29_121213_create_transactions_table',3);

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `product_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`product_id`,`name`,`stock`,`price`,`variant`,`category`,`created_at`,`updated_at`) values 
(1,'Product 1','17','4205','Variant vuYkP','Category 8','2024-03-26 10:23:23','2024-12-13 10:23:23'),
(2,'Product 2','43','4172','Variant Z2dqc','Category 9','2024-02-07 10:23:23','2024-12-13 10:23:23'),
(3,'Product 3','46','6870','Variant 22Jbp','Category 6','2024-02-25 10:23:23','2024-12-13 10:23:23'),
(4,'Product 4','59','8132','Variant 7kiAQ','Category 4','2024-03-07 10:23:23','2024-12-13 10:23:23'),
(5,'Product 5','55','3433','Variant OXqHM','Category 5','2023-12-28 10:23:23','2024-12-13 10:23:23'),
(6,'Product 6','79','1743','Variant WF9Bp','Category 3','2024-08-08 10:23:23','2024-12-13 10:23:23'),
(7,'Product 7','13','1452','Variant UhTOi','Category 8','2024-06-12 10:23:23','2024-12-13 10:23:23'),
(8,'Product 8','91','2273','Variant dsqpB','Category 7','2024-01-05 10:23:23','2024-12-13 10:23:23'),
(9,'Product 9','58','5463','Variant YxSkZ','Category 2','2024-02-29 10:23:23','2024-12-13 10:23:23'),
(10,'Product 10','19','2759','Variant m0agI','Category 9','2024-08-21 10:23:23','2024-12-13 10:23:23'),
(11,'Product 11','61','8041','Variant WvUNd','Category 2','2024-10-10 10:23:23','2024-12-13 10:23:23'),
(12,'Product 12','75','9300','Variant 9zec2','Category 3','2024-05-12 10:23:23','2024-12-13 10:23:23'),
(13,'Product 13','28','5958','Variant Cy4Fu','Category 2','2024-08-23 10:23:23','2024-12-13 10:23:23'),
(14,'Product 14','33','5067','Variant SW4yQ','Category 7','2024-11-06 10:23:23','2024-12-13 10:23:23'),
(15,'Product 15','43','3540','Variant jPSCM','Category 10','2024-07-16 10:23:23','2024-12-13 10:23:23'),
(16,'Product 16','57','8321','Variant REa9Y','Category 6','2024-12-08 10:23:23','2024-12-13 10:23:23'),
(17,'Product 17','86','7641','Variant Qck7f','Category 3','2024-11-12 10:23:23','2024-12-13 10:23:23'),
(18,'Product 18','74','6539','Variant PbPWm','Category 4','2024-04-21 10:23:23','2024-12-13 10:23:23'),
(19,'Product 19','93','1244','Variant Bq401','Category 6','2024-10-01 10:23:23','2024-12-13 10:23:23'),
(20,'Product 20','68','6238','Variant ONloo','Category 5','2024-10-08 10:23:23','2024-12-13 10:23:23'),
(21,'Product 21','94','4942','Variant tJD09','Category 2','2024-03-14 10:23:23','2024-12-13 10:23:23'),
(22,'Product 22','50','2806','Variant yb7hF','Category 5','2024-07-10 10:23:23','2024-12-13 10:23:23'),
(23,'Product 23','35','9250','Variant ltAMA','Category 7','2024-07-07 10:23:23','2024-12-13 10:23:23'),
(24,'Product 24','49','7478','Variant VYy2S','Category 5','2024-10-18 10:23:23','2024-12-13 10:23:23'),
(25,'Product 25','36','7406','Variant lwAbv','Category 10','2024-07-11 10:23:23','2024-12-13 10:23:23'),
(26,'Product 26','37','4140','Variant z7nOM','Category 4','2024-05-14 10:23:23','2024-12-13 10:23:23'),
(27,'Product 27','87','6524','Variant w1Tvk','Category 5','2024-12-10 10:23:23','2024-12-13 10:23:23'),
(28,'Product 28','79','3065','Variant mhJe0','Category 3','2024-05-24 10:23:23','2024-12-13 10:23:23'),
(29,'Product 29','29','5941','Variant Hs1n9','Category 2','2024-10-13 10:23:23','2024-12-13 10:23:23'),
(30,'Product 30','66','9247','Variant 7H2MU','Category 9','2024-02-06 10:23:23','2024-12-13 10:23:23'),
(31,'Product 31','97','5902','Variant UAJgu','Category 2','2024-11-11 10:23:23','2024-12-13 10:23:23'),
(32,'Product 32','30','5069','Variant vewM7','Category 7','2024-01-09 10:23:23','2024-12-13 10:23:23'),
(33,'Product 33','80','5760','Variant s6P9J','Category 3','2024-01-11 10:23:23','2024-12-13 10:23:23'),
(34,'Product 34','23','3515','Variant wtxz8','Category 6','2024-08-08 10:23:23','2024-12-13 10:23:23'),
(35,'Product 35','81','7420','Variant IJYRH','Category 1','2024-09-24 10:23:23','2024-12-13 10:23:23'),
(36,'Product 36','15','9210','Variant SLbK7','Category 8','2024-10-01 10:23:23','2024-12-13 10:23:23'),
(37,'Product 37','17','9145','Variant CbAVv','Category 4','2023-12-18 10:23:23','2024-12-13 10:23:23'),
(38,'Product 38','98','5089','Variant 8DdOs','Category 2','2024-01-07 10:23:23','2024-12-13 10:23:23'),
(39,'Product 39','70','8838','Variant 9cGmN','Category 9','2024-06-25 10:23:23','2024-12-13 10:23:23'),
(40,'Product 40','100','6484','Variant 1zbKR','Category 8','2024-02-02 10:23:23','2024-12-13 10:23:23'),
(41,'Product 41','12','2933','Variant OIgGd','Category 9','2024-10-02 10:23:23','2024-12-13 10:23:23'),
(42,'Product 42','57','9963','Variant wdi1o','Category 4','2024-06-29 10:23:23','2024-12-13 10:23:23'),
(43,'Product 43','21','2866','Variant pgWHS','Category 5','2024-02-06 10:23:23','2024-12-13 10:23:23'),
(44,'Product 44','65','9796','Variant zsWsp','Category 8','2024-01-17 10:23:23','2024-12-13 10:23:23'),
(45,'Product 45','49','5998','Variant 6Xya0','Category 3','2024-07-18 10:23:23','2024-12-13 10:23:23'),
(46,'Product 46','79','7372','Variant 9hVem','Category 2','2024-01-30 10:23:23','2024-12-13 10:23:23'),
(47,'Product 47','64','6621','Variant eaJV5','Category 9','2024-08-07 10:23:23','2024-12-13 10:23:23'),
(48,'Product 48','78','7109','Variant YK75y','Category 5','2024-07-07 10:23:23','2024-12-13 10:23:23'),
(49,'Product 49','18','8690','Variant PsyYa','Category 9','2024-07-26 10:23:23','2024-12-13 10:23:23'),
(50,'Product 50','68','6861','Variant 2OmQ4','Category 4','2024-03-30 10:23:23','2024-12-13 10:23:23'),
(51,'cobaaa','97','4000','cobaaa','cobaaa','2024-12-13 10:28:24','2024-12-13 10:30:06');

/*Table structure for table `role_access` */

DROP TABLE IF EXISTS `role_access`;

CREATE TABLE `role_access` (
  `role_access_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_access_id`),
  KEY `role_access_role_id_foreign` (`role_id`),
  KEY `role_access_user_id_foreign` (`user_id`),
  CONSTRAINT `role_access_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE,
  CONSTRAINT `role_access_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_access` */

insert  into `role_access`(`role_access_id`,`role_id`,`user_id`,`created_at`,`updated_at`) values 
(1,1,1,'2024-11-29 11:33:00','2024-11-29 11:33:00'),
(2,2,2,'2024-11-29 11:33:00','2024-11-29 11:33:00'),
(3,2,1,'2024-11-29 11:34:03','2024-11-29 11:34:03'),
(4,3,3,'2024-11-29 12:29:27','2024-11-29 12:29:27'),
(5,3,2,'2024-11-29 12:29:54','2024-11-29 12:29:54'),
(6,2,4,'2024-12-13 09:34:01','2024-12-13 09:34:01'),
(8,3,5,'2024-12-13 10:32:02','2024-12-13 10:32:02');

/*Table structure for table `role_permission` */

DROP TABLE IF EXISTS `role_permission`;

CREATE TABLE `role_permission` (
  `role_permission_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `menu_id` bigint unsigned NOT NULL,
  `can_read` tinyint(1) NOT NULL DEFAULT '0',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_update` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_permission_id`),
  KEY `role_permission_role_id_foreign` (`role_id`),
  KEY `role_permission_menu_id_foreign` (`menu_id`),
  CONSTRAINT `role_permission_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE,
  CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_permission` */

insert  into `role_permission`(`role_permission_id`,`role_id`,`menu_id`,`can_read`,`can_create`,`can_update`,`can_delete`,`created_at`,`updated_at`) values 
(1,1,2,1,1,1,1,'2024-11-29 12:27:40','2024-11-29 12:27:40'),
(2,1,3,1,1,1,1,'2024-11-29 12:27:40','2024-11-29 12:27:40'),
(3,1,1,1,1,1,1,'2024-11-29 12:27:40','2024-11-29 12:27:40'),
(4,2,2,1,1,1,1,'2024-11-29 12:28:01','2024-11-29 12:28:01'),
(5,2,3,1,1,1,1,'2024-11-29 12:28:01','2024-11-29 12:28:01'),
(6,2,1,1,1,1,1,'2024-11-29 12:28:01','2024-11-29 12:28:01'),
(7,3,2,1,1,1,1,'2024-11-29 12:28:17','2024-11-29 12:28:17'),
(8,3,3,1,1,1,1,'2024-11-29 12:28:17','2024-11-29 12:28:17'),
(9,3,1,0,0,0,0,'2024-11-29 12:28:17','2024-11-29 16:38:47');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `roles_role_name_unique` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`role_id`,`role_name`,`description`,`created_at`,`updated_at`) values 
(1,'Superadmin','Superadmin role with highest permissions','2024-11-29 11:32:59','2024-11-29 11:32:59'),
(2,'Admin','Administrator role with full permissions','2024-11-29 11:32:59','2024-11-29 11:32:59'),
(3,'User','Standard user role with limited permissions','2024-11-29 11:32:59','2024-11-29 11:32:59');

/*Table structure for table `submenus` */

DROP TABLE IF EXISTS `submenus`;

CREATE TABLE `submenus` (
  `submenu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `submenu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `submenu_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `submenu_redirect` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submenu_sort` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`submenu_id`),
  UNIQUE KEY `submenus_submenu_slug_unique` (`submenu_slug`),
  KEY `submenus_menu_id_foreign` (`menu_id`),
  CONSTRAINT `submenus_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `submenus` */

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `transaction_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `method_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `transactions_product_id_foreign` (`product_id`),
  CONSTRAINT `transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transactions` */

insert  into `transactions`(`transaction_id`,`customer_name`,`qty`,`method_payment`,`total`,`product_id`,`created_at`,`updated_at`) values 
(1,'Customer XoMQr',3,'Cash',14826.00,21,'2024-05-19 10:23:23','2024-05-20 04:23:23'),
(2,'Customer Yvw7F',8,'Cash',40536.00,14,'2024-08-05 10:23:23','2024-08-05 22:23:23'),
(3,'Customer X4Hvn',1,'Card',9247.00,30,'2024-03-30 10:23:23','2024-03-31 04:23:23'),
(4,'Customer mpdK2',2,'Cash',7080.00,15,'2024-08-03 10:23:23','2024-08-04 00:23:23'),
(5,'Customer xYv5g',2,'Cash',8410.00,1,'2024-04-14 10:23:23','2024-04-15 03:23:23'),
(6,'Customer V7r9u',6,'Cash',24840.00,26,'2024-11-25 10:23:23','2024-11-26 10:23:23'),
(7,'Customer dB4Yq',8,'Card',33120.00,26,'2024-07-20 10:23:23','2024-07-21 09:23:23'),
(8,'Customer grd6p',3,'Card',27630.00,36,'2024-04-29 10:23:23','2024-04-29 21:23:23'),
(9,'Customer LmIAn',4,'Card',37200.00,12,'2024-03-27 10:23:23','2024-03-27 21:23:23'),
(10,'Customer vfOCA',1,'Card',6539.00,18,'2024-01-28 10:23:23','2024-01-29 10:23:23'),
(11,'Customer rLeo4',6,'Cash',37428.00,20,'2024-06-13 10:23:23','2024-06-14 01:23:23'),
(12,'Customer CFU73',6,'Cash',39726.00,47,'2024-07-10 10:23:23','2024-07-10 17:23:23'),
(13,'Customer csuWE',10,'Cash',50690.00,32,'2024-08-10 10:23:23','2024-08-11 02:23:23'),
(14,'Customer GHSwM',5,'Cash',32420.00,40,'2024-03-15 10:23:23','2024-03-15 18:23:23'),
(15,'Customer ooz9r',10,'Card',62380.00,20,'2024-07-15 10:23:23','2024-07-15 15:23:23'),
(16,'Customer Wueso',7,'Card',58247.00,16,'2023-12-22 10:23:23','2023-12-23 10:23:23'),
(17,'Customer Shp8J',2,'Cash',11520.00,33,'2024-08-21 10:23:23','2024-08-21 16:23:23'),
(18,'Customer RPIKq',3,'Card',24963.00,16,'2024-05-09 10:23:23','2024-05-09 22:23:23'),
(19,'Customer fOoX3',9,'Cash',45621.00,32,'2024-12-10 10:23:23','2024-12-11 05:23:23'),
(20,'Customer e3rPz',4,'Card',29680.00,35,'2023-12-19 10:23:23','2023-12-19 22:23:23'),
(21,'Customer 9pIBi',7,'Cash',8708.00,19,'2024-11-27 10:23:23','2024-11-28 10:23:23'),
(22,'Customer IMmwY',5,'Cash',14330.00,43,'2024-08-20 10:23:23','2024-08-21 07:23:23'),
(23,'Customer k9orU',1,'Card',9247.00,30,'2024-08-10 10:23:23','2024-08-11 07:23:23'),
(24,'Customer PloJj',10,'Cash',65390.00,18,'2024-11-30 10:23:23','2024-11-30 23:23:23'),
(25,'Customer E5qZ1',1,'Card',8132.00,4,'2024-10-29 10:23:23','2024-10-29 17:23:23'),
(26,'Customer g0Y34',8,'Cash',49904.00,20,'2024-09-03 10:23:23','2024-09-04 03:23:23'),
(27,'Customer OQsGL',4,'Cash',30564.00,17,'2024-02-25 10:23:23','2024-02-26 02:23:23'),
(28,'Customer YsOjW',4,'Cash',14160.00,15,'2024-12-02 10:23:23','2024-12-03 07:23:23'),
(29,'Customer T1Ir6',1,'Card',6861.00,50,'2023-12-31 10:23:23','2023-12-31 18:23:23'),
(30,'Customer qtNme',2,'Card',11882.00,29,'2024-05-13 10:23:23','2024-05-13 20:23:23'),
(31,'Customer tTbxr',5,'Cash',49815.00,42,'2024-03-03 10:23:23','2024-03-04 01:23:23'),
(32,'Customer uZQ4g',5,'Card',29705.00,29,'2024-08-20 10:23:23','2024-08-21 08:23:23'),
(33,'Customer aKUVP',10,'Card',42050.00,1,'2024-03-13 10:23:23','2024-03-13 15:23:23'),
(34,'Customer hdxtg',3,'Card',12516.00,2,'2024-02-23 10:23:23','2024-02-23 20:23:23'),
(35,'Customer AT2mS',10,'Card',88380.00,39,'2024-06-06 10:23:23','2024-06-07 06:23:23'),
(36,'Customer yArk9',8,'Cash',73976.00,30,'2024-06-02 10:23:23','2024-06-03 00:23:23'),
(37,'Customer X0gPq',6,'Card',21090.00,34,'2024-04-24 10:23:23','2024-04-24 14:23:23'),
(38,'Customer plarV',2,'Cash',11804.00,31,'2024-03-02 10:23:23','2024-03-03 03:23:23'),
(39,'Customer uNqDM',6,'Card',21240.00,15,'2024-04-06 10:23:23','2024-04-07 07:23:23'),
(40,'Customer L22dk',8,'Cash',79704.00,42,'2024-01-21 10:23:23','2024-01-22 05:23:23'),
(41,'Customer KVyLu',8,'Card',33640.00,1,'2024-10-21 10:23:23','2024-10-21 11:23:23'),
(42,'Customer wBRgE',4,'Card',23992.00,45,'2024-07-05 10:23:23','2024-07-05 17:23:23'),
(43,'Customer 2BxXK',7,'Cash',64470.00,36,'2024-11-20 10:23:23','2024-11-21 07:23:23'),
(44,'Customer Dr1iw',7,'Cash',45668.00,27,'2024-11-20 10:23:23','2024-11-20 18:23:23'),
(45,'Customer HMMX5',10,'Card',50690.00,32,'2024-11-02 10:23:23','2024-11-02 11:23:23'),
(46,'Customer 55kg1',8,'Cash',33640.00,1,'2024-11-21 10:23:23','2024-11-21 19:23:23'),
(47,'Customer qxPQt',3,'Card',17994.00,45,'2023-12-16 10:23:23','2023-12-16 18:23:23'),
(48,'Customer OWXZw',9,'Cash',68769.00,17,'2024-03-23 10:23:23','2024-03-23 16:23:23'),
(49,'Customer VN4Ks',4,'Cash',11036.00,10,'2024-03-20 10:23:23','2024-03-21 05:23:23'),
(50,'Customer c5AnE',7,'Card',20531.00,41,'2024-01-12 10:23:23','2024-01-12 22:23:23'),
(51,'Customer inFai',8,'Card',33640.00,1,'2024-03-04 10:23:23','2024-03-05 02:23:23'),
(52,'Customer g0UrH',7,'Cash',8708.00,19,'2024-09-04 10:23:23','2024-09-04 19:23:23'),
(53,'Customer 0QdVA',3,'Card',22923.00,17,'2024-10-25 10:23:23','2024-10-25 15:23:23'),
(54,'Customer gTMpv',6,'Card',35646.00,29,'2024-06-26 10:23:23','2024-06-27 05:23:23'),
(55,'Customer cu59P',10,'Cash',14520.00,7,'2024-03-05 10:23:23','2024-03-05 21:23:23'),
(56,'Customer C9kZz',9,'Card',44478.00,21,'2024-03-29 10:23:23','2024-03-30 04:23:23'),
(57,'Customer Lt3n8',8,'Card',73160.00,37,'2024-08-25 10:23:23','2024-08-25 17:23:23'),
(58,'Customer aQGhI',1,'Card',3515.00,34,'2024-07-22 10:23:23','2024-07-22 13:23:23'),
(59,'Customer GjAtg',1,'Cash',9247.00,30,'2024-08-05 10:23:23','2024-08-05 14:23:23'),
(60,'Customer EFJof',8,'Cash',22448.00,22,'2024-04-15 10:23:23','2024-04-15 16:23:23'),
(61,'Customer 9hckp',5,'Card',34305.00,50,'2023-12-23 10:23:23','2023-12-24 01:23:23'),
(62,'Customer gOHu4',8,'Card',56872.00,48,'2024-01-08 10:23:23','2024-01-08 14:23:23'),
(63,'Customer Yu5pv',8,'Cash',47664.00,13,'2024-02-28 10:23:23','2024-02-29 09:23:23'),
(64,'Customer 36SJx',9,'Cash',83223.00,30,'2024-06-09 10:23:23','2024-06-09 12:23:23'),
(65,'Customer b6ZkU',4,'Cash',26096.00,27,'2024-01-10 10:23:23','2024-01-11 10:23:23'),
(66,'Customer b2Jzu',4,'Cash',36988.00,30,'2024-07-27 10:23:23','2024-07-28 10:23:23'),
(67,'Customer WhDmv',2,'Card',2488.00,19,'2024-02-14 10:23:23','2024-02-14 15:23:23'),
(68,'Customer JwNyr',1,'Card',6870.00,3,'2024-11-08 10:23:23','2024-11-08 22:23:23'),
(69,'Customer RkhYF',6,'Card',13638.00,8,'2024-01-29 10:23:23','2024-01-30 09:23:23'),
(70,'Customer XGa2e',6,'Cash',35646.00,29,'2024-08-01 10:23:23','2024-08-02 06:23:23'),
(71,'Customer uxPai',1,'Cash',9963.00,42,'2024-06-25 10:23:23','2024-06-25 21:23:23'),
(72,'Customer LevUj',3,'Card',17823.00,29,'2024-02-24 10:23:23','2024-02-25 02:23:23'),
(73,'Customer tX5rc',6,'Card',10458.00,6,'2024-01-08 10:23:23','2024-01-08 14:23:23'),
(74,'Customer Jyzhx',10,'Cash',74200.00,35,'2024-04-12 10:23:23','2024-04-12 12:23:23'),
(75,'Customer 1nURH',6,'Cash',24840.00,26,'2024-01-22 10:23:23','2024-01-22 11:23:23'),
(76,'Customer 98euj',6,'Cash',35646.00,29,'2024-10-29 10:23:23','2024-10-30 08:23:23'),
(77,'Customer 2W6fx',4,'Card',36580.00,37,'2024-01-09 10:23:23','2024-01-09 21:23:23'),
(78,'Customer OyLg1',1,'Cash',3515.00,34,'2024-08-23 10:23:23','2024-08-23 22:23:23'),
(79,'Customer 7TDRe',4,'Card',35352.00,39,'2024-09-01 10:23:23','2024-09-01 18:23:23'),
(80,'Customer 11EiC',6,'Card',49926.00,16,'2024-04-09 10:23:23','2024-04-09 16:23:23'),
(81,'Customer 0KoYz',9,'Cash',79542.00,39,'2024-12-06 10:23:23','2024-12-07 01:23:23'),
(82,'Customer aM3EG',3,'Cash',22923.00,17,'2024-08-19 10:23:23','2024-08-19 15:23:23'),
(83,'Customer f1R7m',3,'Card',17280.00,33,'2024-02-07 10:23:23','2024-02-08 06:23:23'),
(84,'Customer uUvcz',1,'Cash',8132.00,4,'2024-03-22 10:23:23','2024-03-23 06:23:23'),
(85,'Customer DP0GV',2,'Cash',6130.00,28,'2024-11-13 10:23:23','2024-11-13 20:23:23'),
(86,'Customer Bh0ye',7,'Cash',43666.00,20,'2024-07-17 10:23:23','2024-07-18 07:23:23'),
(87,'Customer SzHZm',8,'Cash',9952.00,19,'2024-03-02 10:23:23','2024-03-02 16:23:23'),
(88,'Customer Pbo2a',2,'Card',13722.00,50,'2024-11-13 10:23:23','2024-11-13 22:23:23'),
(89,'Customer 2lXy6',5,'Card',29990.00,45,'2024-12-03 10:23:23','2024-12-03 13:23:23'),
(90,'Customer Murkq',5,'Cash',46050.00,36,'2024-06-24 10:23:23','2024-06-24 11:23:23'),
(91,'Customer 7RC32',4,'Cash',23992.00,45,'2024-10-30 10:23:23','2024-10-31 02:23:23'),
(92,'Customer C9qas',4,'Card',26156.00,18,'2024-09-13 10:23:23','2024-09-14 01:23:23'),
(93,'Customer aX60J',6,'Cash',10458.00,6,'2024-01-11 10:23:23','2024-01-12 00:23:23'),
(94,'Customer MOQof',1,'Card',3540.00,15,'2024-03-25 10:23:23','2024-03-26 09:23:23'),
(95,'Customer Grgzi',7,'Cash',41706.00,13,'2024-07-03 10:23:23','2024-07-04 02:23:23'),
(96,'Customer l0puw',5,'Card',20860.00,2,'2024-10-10 10:23:23','2024-10-11 00:23:23'),
(97,'Customer 9xx3k',9,'Cash',53622.00,13,'2024-09-24 10:23:23','2024-09-24 14:23:23'),
(98,'Customer jl0OA',7,'Cash',49763.00,48,'2024-03-05 10:23:23','2024-03-06 06:23:23'),
(99,'Customer DQnrK',1,'Card',9300.00,12,'2024-12-02 10:23:23','2024-12-03 07:23:23'),
(100,'Customer 6O5Tv',4,'Cash',33284.00,16,'2024-11-21 10:23:23','2024-11-21 19:23:23'),
(101,'frank',3,'cash',12000.00,51,'2024-12-13 10:29:08','2024-12-13 10:30:06');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nonce` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verify` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_code_unique` (`code`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`code`,`name`,`phone`,`email`,`password`,`token`,`nonce`,`is_verify`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'superadmin','superadmin','1234567890','superadmin@yopmail.com','$2y$10$bUsAXFtlluslLbWtv73qou.m8p8hxKIhoLcSuzphLyTXvcXvilMmi','d93b7f40ef114e52b773e9b6308b58c1',NULL,'1','2024-11-29 11:32:59','2024-11-29 16:38:25',NULL),
(2,'admin','admin','0987654321','admin@gmail.com','$2y$10$8Ug2KRxt42mtrHhQ8r4W0OuAbFeOzHgApu5NWHjAlWXL7irEC7CJm','962c62b3f2d84d1cad19286e91f90961',NULL,'1','2024-11-29 11:33:00','2024-12-13 10:46:09',NULL),
(3,'kasir','kasir','08512345678','kasir@gmail.com','$2y$10$PFfxJmxZHpo0uL9HwhHxoO6uovcGIPp09UT9xBlcL0R8/N9I2cpDu','f759f49e41444573ad780616cd9a315c',NULL,'1','2024-11-29 12:29:16','2024-12-13 10:27:43',NULL),
(4,'admin2','admin2','12345','admin2@gmail.com','$2y$10$6HLANT2L0whrExYZ5QIFM.6nyPc1y9qX1wWwX9E01JwaqtvMgZDJO','820775d2d6c845a9913688503b70fc95',NULL,'1','2024-12-13 09:33:10','2024-12-13 09:37:35',NULL),
(5,'kasir3','kasir2','987654','kasir2@gmail.com','$2y$10$MYlGMu4V3YzxWljG2gNqT.M5AGmWUUN6wa87BtLa0J5U1fvCdyjoO','76ad3ad9efa147f2867d4f3d2c8a005e',NULL,'1','2024-12-13 10:30:49','2024-12-13 10:32:16',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
