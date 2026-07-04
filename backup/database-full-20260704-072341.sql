-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: signature_local
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_type` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_created_at_index` (`created_at`),
  KEY `activity_logs_action_index` (`action`),
  KEY `activity_logs_subject_type_index` (`subject_type`),
  KEY `activity_logs_subject_id_index` (`subject_id`),
  KEY `activity_logs_company_id_index` (`company_id`),
  CONSTRAINT `activity_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `all_day` tinyint(1) NOT NULL DEFAULT '0',
  `event_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'meeting',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#7c3aed',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_events_start_datetime_end_datetime_index` (`start_datetime`,`end_datetime`),
  KEY `calendar_events_created_by_index` (`created_by`),
  KEY `calendar_events_company_id_index` (`company_id`),
  CONSTRAINT `calendar_events_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `calendar_events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_events`
--

LOCK TABLES `calendar_events` WRITE;
/*!40000 ALTER TABLE `calendar_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `database_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenant_ready_at` timestamp NULL DEFAULT NULL,
  `tenant_provision_failed_at` timestamp NULL DEFAULT NULL,
  `tenant_provision_error` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_slug_unique` (`slug`),
  UNIQUE KEY `companies_database_name_unique` (`database_name`),
  KEY `companies_active_index` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (2,'Default Company','default',NULL,NULL,NULL,NULL,1,'2026-07-04 07:02:40','2026-07-04 07:02:40');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_installed_features`
--

DROP TABLE IF EXISTS `company_installed_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_installed_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `feature_key` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `installed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `installed_by` bigint unsigned DEFAULT NULL,
  `source_company_update_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_installed_features_company_id_feature_key_unique` (`company_id`,`feature_key`),
  KEY `company_installed_features_installed_by_foreign` (`installed_by`),
  KEY `company_installed_features_source_company_update_id_foreign` (`source_company_update_id`),
  CONSTRAINT `company_installed_features_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_installed_features_installed_by_foreign` FOREIGN KEY (`installed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `company_installed_features_source_company_update_id_foreign` FOREIGN KEY (`source_company_update_id`) REFERENCES `company_updates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_installed_features`
--

LOCK TABLES `company_installed_features` WRITE;
/*!40000 ALTER TABLE `company_installed_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_installed_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_updates`
--

DROP TABLE IF EXISTS `company_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_updates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feature_key` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_updates_company_id_published_at_index` (`company_id`,`published_at`),
  CONSTRAINT `company_updates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_updates`
--

LOCK TABLES `company_updates` WRITE;
/*!40000 ALTER TABLE `company_updates` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_name_index` (`name`),
  KEY `contacts_phone_index` (`phone`),
  KEY `contacts_active_index` (`active`),
  KEY `contacts_company_id_index` (`company_id`),
  KEY `contacts_category_index` (`category`),
  CONSTRAINT `contacts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_ledger`
--

DROP TABLE IF EXISTS `credit_ledger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `credit_ledger` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `contact_id` bigint unsigned NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'credit',
  `pos_order_id` bigint unsigned DEFAULT NULL,
  `description` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `balance_after` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entry_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `credit_ledger_pos_order_id_foreign` (`pos_order_id`),
  KEY `credit_ledger_created_by_foreign` (`created_by`),
  KEY `credit_ledger_contact_id_entry_date_index` (`contact_id`,`entry_date`),
  KEY `credit_ledger_company_id_index` (`company_id`),
  CONSTRAINT `credit_ledger_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `credit_ledger_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `credit_ledger_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `credit_ledger_pos_order_id_foreign` FOREIGN KEY (`pos_order_id`) REFERENCES `pos_orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_ledger`
--

LOCK TABLES `credit_ledger` WRITE;
/*!40000 ALTER TABLE `credit_ledger` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit_ledger` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_form_reports`
--

DROP TABLE IF EXISTS `custom_form_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_form_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `template_id` bigint unsigned NOT NULL,
  `month` tinyint unsigned NOT NULL,
  `year` smallint unsigned NOT NULL,
  `values_json` json DEFAULT NULL,
  `saved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cfr_company_template_period_unique` (`company_id`,`template_id`,`month`,`year`),
  KEY `custom_form_reports_template_id_foreign` (`template_id`),
  KEY `custom_form_reports_company_id_index` (`company_id`),
  CONSTRAINT `custom_form_reports_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `custom_form_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_form_reports`
--

LOCK TABLES `custom_form_reports` WRITE;
/*!40000 ALTER TABLE `custom_form_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_form_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_form_templates`
--

DROP TABLE IF EXISTS `custom_form_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_form_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `heading` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rows_json` json DEFAULT NULL,
  `show_remarks` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_form_templates_company_id_index` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_form_templates`
--

LOCK TABLES `custom_form_templates` WRITE;
/*!40000 ALTER TABLE `custom_form_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_form_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_attendances`
--

DROP TABLE IF EXISTS `employee_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `employee_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `clock_in` datetime DEFAULT NULL,
  `clock_out` datetime DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `source` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'self',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_attendances_employee_id_attendance_date_unique` (`employee_id`,`attendance_date`),
  KEY `employee_attendances_user_id_foreign` (`user_id`),
  KEY `employee_attendances_attendance_date_index` (`attendance_date`),
  KEY `employee_attendances_status_index` (`status`),
  KEY `employee_attendances_company_id_index` (`company_id`),
  CONSTRAINT `employee_attendances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_attendances`
--

LOCK TABLES `employee_attendances` WRITE;
/*!40000 ALTER TABLE `employee_attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_departments`
--

DROP TABLE IF EXISTS `employee_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_departments_name_unique` (`name`),
  KEY `employee_departments_active_index` (`active`),
  KEY `employee_departments_company_id_index` (`company_id`),
  CONSTRAINT `employee_departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_departments`
--

LOCK TABLES `employee_departments` WRITE;
/*!40000 ALTER TABLE `employee_departments` DISABLE KEYS */;
INSERT INTO `employee_departments` VALUES (2,2,'Administration',1,'2026-07-04 07:02:40','2026-07-04 07:02:40');
/*!40000 ALTER TABLE `employee_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_designations`
--

DROP TABLE IF EXISTS `employee_designations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_designations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_designations_name_unique` (`name`),
  KEY `employee_designations_active_index` (`active`),
  KEY `employee_designations_company_id_index` (`company_id`),
  CONSTRAINT `employee_designations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_designations`
--

LOCK TABLES `employee_designations` WRITE;
/*!40000 ALTER TABLE `employee_designations` DISABLE KEYS */;
INSERT INTO `employee_designations` VALUES (7,2,'Super Administrator',1,'2026-07-04 07:02:40','2026-07-04 07:02:40');
/*!40000 ALTER TABLE `employee_designations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `user_id` bigint unsigned DEFAULT NULL,
  `employee_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `designation_id` bigint unsigned DEFAULT NULL,
  `department` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `salary` decimal(14,2) NOT NULL DEFAULT '0.00',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_company_id_employee_no_unique` (`company_id`,`employee_no`),
  KEY `employees_name_active_index` (`name`,`active`),
  KEY `employees_email_index` (`email`),
  KEY `employees_department_index` (`department`),
  KEY `employees_designation_index` (`designation`),
  KEY `employees_join_date_index` (`join_date`),
  KEY `employees_active_index` (`active`),
  KEY `employees_user_id_foreign` (`user_id`),
  KEY `employees_department_id_foreign` (`department_id`),
  KEY `employees_designation_id_foreign` (`designation_id`),
  KEY `employees_company_id_index` (`company_id`),
  CONSTRAINT `employees_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `employee_departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `employee_designations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (9,2,5,'EMP-ADMIN-001','Company Admin','admin@example.com',NULL,2,7,NULL,NULL,'2026-07-04',0.00,NULL,1,'2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_name_unique` (`name`),
  KEY `expense_categories_active_index` (`active`),
  KEY `expense_categories_company_id_index` (`company_id`),
  CONSTRAINT `expense_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
INSERT INTO `expense_categories` VALUES (9,2,'Travel','Flights, hotels, transport, fuel',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(10,2,'Meals & Entertainment','Client lunches, team dinners',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(11,2,'Office Supplies','Stationery, printing, consumables',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(12,2,'Communication','Phone, internet, courier',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(13,2,'Training','Courses, books, conferences',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(14,2,'Software & Tools','Licenses, subscriptions, SaaS tools',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(15,2,'Medical','Medical, health, insurance related',1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(16,2,'Miscellaneous','Other business expenses',1,'2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `employee_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `qty` decimal(10,3) NOT NULL DEFAULT '1.000',
  `unit_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_percent` decimal(6,3) NOT NULL DEFAULT '0.000',
  `tax_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `receipt_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `refuse_reason` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_category_id_foreign` (`category_id`),
  KEY `expenses_approved_by_foreign` (`approved_by`),
  KEY `expenses_employee_id_status_index` (`employee_id`,`status`),
  KEY `expenses_expense_date_index` (`expense_date`),
  KEY `expenses_status_index` (`status`),
  KEY `expenses_company_id_index` (`company_id`),
  CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guest_rooms`
--

DROP TABLE IF EXISTS `guest_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guest_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `room_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type_id` bigint unsigned DEFAULT NULL,
  `room_category_id` bigint unsigned DEFAULT NULL,
  `floor` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `cleaning_checklist` json DEFAULT NULL,
  `cleaning_started_at` timestamp NULL DEFAULT NULL,
  `maintenance_reason` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `maintenance_cost` decimal(12,2) DEFAULT NULL,
  `maintenance_bill_reference` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_started_at` timestamp NULL DEFAULT NULL,
  `maintenance_checklist` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guest_rooms_company_id_room_number_unique` (`company_id`,`room_number`),
  KEY `guest_rooms_room_type_id_foreign` (`room_type_id`),
  KEY `guest_rooms_room_category_id_foreign` (`room_category_id`),
  KEY `guest_rooms_company_id_index` (`company_id`),
  KEY `guest_rooms_status_index` (`status`),
  KEY `guest_rooms_active_index` (`active`),
  CONSTRAINT `guest_rooms_room_category_id_foreign` FOREIGN KEY (`room_category_id`) REFERENCES `room_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `guest_rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guest_rooms`
--

LOCK TABLES `guest_rooms` WRITE;
/*!40000 ALTER TABLE `guest_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `guest_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_categories`
--

DROP TABLE IF EXISTS `inventory_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_categories_parent_id_name_unique` (`parent_id`,`name`),
  KEY `inventory_categories_company_id_index` (`company_id`),
  CONSTRAINT `inventory_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_categories`
--

LOCK TABLES `inventory_categories` WRITE;
/*!40000 ALTER TABLE `inventory_categories` DISABLE KEYS */;
INSERT INTO `inventory_categories` VALUES (26,2,'All Products',NULL,'2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `inventory_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_cost_layers`
--

DROP TABLE IF EXISTS `inventory_cost_layers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_cost_layers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `product_id` bigint unsigned NOT NULL,
  `qty_remaining` decimal(14,3) NOT NULL,
  `unit_cost` decimal(14,6) NOT NULL,
  `source` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_cost_layers_product_id_received_at_index` (`product_id`,`received_at`),
  KEY `inventory_cost_layers_company_id_index` (`company_id`),
  CONSTRAINT `inventory_cost_layers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_cost_layers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=538 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_cost_layers`
--

LOCK TABLES `inventory_cost_layers` WRITE;
/*!40000 ALTER TABLE `inventory_cost_layers` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_cost_layers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_moves`
--

DROP TABLE IF EXISTS `inventory_moves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_moves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` decimal(14,3) NOT NULL,
  `qty_uom` decimal(14,3) DEFAULT NULL,
  `factor_to_base` decimal(18,6) DEFAULT NULL,
  `unit_cost` decimal(14,6) DEFAULT NULL,
  `total_cost` decimal(14,6) DEFAULT NULL,
  `qty_before` decimal(14,3) NOT NULL,
  `qty_after` decimal(14,3) NOT NULL,
  `reference` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_moves_user_id_foreign` (`user_id`),
  KEY `inventory_moves_product_id_created_at_index` (`product_id`,`created_at`),
  KEY `inventory_moves_type_created_at_index` (`type`,`created_at`),
  KEY `inventory_moves_company_id_index` (`company_id`),
  CONSTRAINT `inventory_moves_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_moves_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_moves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=810 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_moves`
--

LOCK TABLES `inventory_moves` WRITE;
/*!40000 ALTER TABLE `inventory_moves` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_moves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_product_favorites`
--

DROP TABLE IF EXISTS `inventory_product_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_product_favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_product_favorites_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `inventory_product_favorites_product_id_user_id_index` (`product_id`,`user_id`),
  KEY `inventory_product_favorites_company_id_index` (`company_id`),
  CONSTRAINT `inventory_product_favorites_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_product_favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_product_favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_product_favorites`
--

LOCK TABLES `inventory_product_favorites` WRITE;
/*!40000 ALTER TABLE `inventory_product_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_product_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_product_uom_conversions`
--

DROP TABLE IF EXISTS `inventory_product_uom_conversions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_product_uom_conversions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `product_id` bigint unsigned NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `factor_to_base` decimal(18,6) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_product_uom_conversions_product_id_uom_unique` (`product_id`,`uom`),
  KEY `inventory_product_uom_conversions_active_index` (`active`),
  KEY `inventory_product_uom_conversions_company_id_index` (`company_id`),
  CONSTRAINT `inventory_product_uom_conversions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_product_uom_conversions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=334 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_product_uom_conversions`
--

LOCK TABLES `inventory_product_uom_conversions` WRITE;
/*!40000 ALTER TABLE `inventory_product_uom_conversions` DISABLE KEYS */;
INSERT INTO `inventory_product_uom_conversions` VALUES (332,2,404,'pkt',10.000000,1,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(333,2,404,'Box',100.000000,1,'2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `inventory_product_uom_conversions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_products`
--

DROP TABLE IF EXISTS `inventory_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `category_id` bigint unsigned DEFAULT NULL,
  `sku` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Units',
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gas_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `service_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `extra_costs` json DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `qty_on_hand` decimal(14,3) NOT NULL DEFAULT '0.000',
  `reorder_level` decimal(14,3) NOT NULL DEFAULT '0.000' COMMENT 'Alert when qty_on_hand falls at or below this value. 0 = no alert.',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `for_pos` tinyint(1) NOT NULL DEFAULT '1',
  `for_purchase` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `package_contents_qty` decimal(14,6) DEFAULT NULL,
  `package_contents_uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_products_company_id_sku_unique` (`company_id`,`sku`),
  KEY `inventory_products_category_id_name_index` (`category_id`,`name`),
  KEY `inventory_products_active_index` (`active`),
  KEY `inventory_products_barcode_index` (`barcode`),
  KEY `inventory_products_company_id_index` (`company_id`),
  CONSTRAINT `inventory_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=405 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_products`
--

LOCK TABLES `inventory_products` WRITE;
/*!40000 ALTER TABLE `inventory_products` DISABLE KEYS */;
INSERT INTO `inventory_products` VALUES (404,2,26,'STAIR-001',NULL,'Stair Sample Product','tablet',10.00,0.00,0.00,0.00,NULL,15.00,0.000,0.000,1,1,1,'2026-07-04 07:02:41','2026-07-04 07:02:41',NULL,NULL);
/*!40000 ALTER TABLE `inventory_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_unit_conversions`
--

DROP TABLE IF EXISTS `inventory_unit_conversions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_unit_conversions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_unit_id` bigint unsigned NOT NULL,
  `to_unit_id` bigint unsigned NOT NULL,
  `factor` decimal(24,12) NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_unit_conversions_from_unit_id_to_unit_id_unique` (`from_unit_id`,`to_unit_id`),
  KEY `inventory_unit_conversions_to_unit_id_foreign` (`to_unit_id`),
  CONSTRAINT `inventory_unit_conversions_from_unit_id_foreign` FOREIGN KEY (`from_unit_id`) REFERENCES `inventory_units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_unit_conversions_to_unit_id_foreign` FOREIGN KEY (`to_unit_id`) REFERENCES `inventory_units` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_unit_conversions`
--

LOCK TABLES `inventory_unit_conversions` WRITE;
/*!40000 ALTER TABLE `inventory_unit_conversions` DISABLE KEYS */;
INSERT INTO `inventory_unit_conversions` VALUES (4,11,10,0.001000000000,'1 g = 0.001 kg','2026-07-04 07:02:41','2026-07-04 07:02:41'),(5,12,10,0.001000000000,'Same as g → kg','2026-07-04 07:02:41','2026-07-04 07:02:41'),(6,14,13,0.001000000000,'1 ml = 0.001 ltr','2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `inventory_unit_conversions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_units`
--

DROP TABLE IF EXISTS `inventory_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_units_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_units`
--

LOCK TABLES `inventory_units` WRITE;
/*!40000 ALTER TABLE `inventory_units` DISABLE KEYS */;
INSERT INTO `inventory_units` VALUES (10,'kg','Kilogram','2026-07-04 07:02:41','2026-07-04 07:02:41'),(11,'g','Gram','2026-07-04 07:02:41','2026-07-04 07:02:41'),(12,'gm','Gram (alias)','2026-07-04 07:02:41','2026-07-04 07:02:41'),(13,'ltr','Litre','2026-07-04 07:02:41','2026-07-04 07:02:41'),(14,'ml','Millilitre','2026-07-04 07:02:41','2026-07-04 07:02:41'),(15,'pcs','Pieces','2026-07-04 07:02:41','2026-07-04 07:02:41'),(16,'box','Box','2026-07-04 07:02:41','2026-07-04 07:02:41'),(17,'pkt','Packet','2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `inventory_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_categories`
--

DROP TABLE IF EXISTS `maintenance_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maintenance_categories_name_unique` (`name`),
  KEY `maintenance_categories_company_id_index` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_categories`
--

LOCK TABLES `maintenance_categories` WRITE;
/*!40000 ALTER TABLE `maintenance_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_demand_lines`
--

DROP TABLE IF EXISTS `maintenance_demand_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_demand_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `demand_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `item_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  `line_location` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_category` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_uom` decimal(14,3) NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_base` decimal(14,3) NOT NULL DEFAULT '0.000',
  `expected_rate` decimal(14,2) NOT NULL DEFAULT '0.00',
  `expected_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `received_qty_uom` decimal(14,3) NOT NULL DEFAULT '0.000',
  `received_qty_base` decimal(14,3) NOT NULL DEFAULT '0.000',
  `actual_rate` decimal(14,2) NOT NULL DEFAULT '0.00',
  `actual_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_demand_lines_product_id_foreign` (`product_id`),
  KEY `maintenance_demand_lines_demand_id_product_id_index` (`demand_id`,`product_id`),
  KEY `maintenance_demand_lines_company_id_index` (`company_id`),
  CONSTRAINT `maintenance_demand_lines_demand_id_foreign` FOREIGN KEY (`demand_id`) REFERENCES `maintenance_demands` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_demand_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_demand_lines`
--

LOCK TABLES `maintenance_demand_lines` WRITE;
/*!40000 ALTER TABLE `maintenance_demand_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_demand_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_demands`
--

DROP TABLE IF EXISTS `maintenance_demands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_demands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `requested_by` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_uom` decimal(14,3) NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_base` decimal(14,3) NOT NULL DEFAULT '0.000',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `demand_date` date DEFAULT NULL,
  `needed_date` date DEFAULT NULL,
  `location` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demand_category` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_demands_product_id_status_index` (`product_id`,`status`),
  KEY `maintenance_demands_company_id_index` (`company_id`),
  CONSTRAINT `maintenance_demands_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_demands`
--

LOCK TABLES `maintenance_demands` WRITE;
/*!40000 ALTER TABLE `maintenance_demands` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_demands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_issues`
--

DROP TABLE IF EXISTS `maintenance_issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_issues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `issued_location` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_to` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_uom` decimal(14,3) NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_base` decimal(14,3) NOT NULL DEFAULT '0.000',
  `reference` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_issues_product_id_created_at_index` (`product_id`,`created_at`),
  KEY `maintenance_issues_company_id_index` (`company_id`),
  CONSTRAINT `maintenance_issues_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_issues`
--

LOCK TABLES `maintenance_issues` WRITE;
/*!40000 ALTER TABLE `maintenance_issues` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_locations`
--

DROP TABLE IF EXISTS `maintenance_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maintenance_locations_name_unique` (`name`),
  KEY `maintenance_locations_company_id_index` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_locations`
--

LOCK TABLES `maintenance_locations` WRITE;
/*!40000 ALTER TABLE `maintenance_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturing_bom_lines`
--

DROP TABLE IF EXISTS `manufacturing_bom_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manufacturing_bom_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `bom_id` bigint unsigned NOT NULL,
  `component_product_id` bigint unsigned NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manufacturing_bom_lines_component_product_id_foreign` (`component_product_id`),
  KEY `manufacturing_bom_lines_bom_id_sort_order_index` (`bom_id`,`sort_order`),
  KEY `manufacturing_bom_lines_company_id_index` (`company_id`),
  CONSTRAINT `manufacturing_bom_lines_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `manufacturing_boms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `manufacturing_bom_lines_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `manufacturing_bom_lines_component_product_id_foreign` FOREIGN KEY (`component_product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2559 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manufacturing_bom_lines`
--

LOCK TABLES `manufacturing_bom_lines` WRITE;
/*!40000 ALTER TABLE `manufacturing_bom_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `manufacturing_bom_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturing_boms`
--

DROP TABLE IF EXISTS `manufacturing_boms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manufacturing_boms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `finished_product_id` bigint unsigned NOT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default',
  `batch_qty` decimal(14,3) NOT NULL DEFAULT '1.000',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manufacturing_boms_finished_product_id_active_index` (`finished_product_id`,`active`),
  KEY `manufacturing_boms_active_index` (`active`),
  KEY `manufacturing_boms_company_id_index` (`company_id`),
  CONSTRAINT `manufacturing_boms_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `manufacturing_boms_finished_product_id_foreign` FOREIGN KEY (`finished_product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manufacturing_boms`
--

LOCK TABLES `manufacturing_boms` WRITE;
/*!40000 ALTER TABLE `manufacturing_boms` DISABLE KEYS */;
/*!40000 ALTER TABLE `manufacturing_boms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturing_orders`
--

DROP TABLE IF EXISTS `manufacturing_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manufacturing_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `bom_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `qty_ordered` decimal(14,3) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `reference` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manufacturing_orders_bom_id_foreign` (`bom_id`),
  KEY `manufacturing_orders_user_id_foreign` (`user_id`),
  KEY `manufacturing_orders_status_index` (`status`),
  KEY `manufacturing_orders_company_id_index` (`company_id`),
  CONSTRAINT `manufacturing_orders_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `manufacturing_boms` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `manufacturing_orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `manufacturing_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manufacturing_orders`
--

LOCK TABLES `manufacturing_orders` WRITE;
/*!40000 ALTER TABLE `manufacturing_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `manufacturing_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2026_04_02_104147_add_role_to_users_table',1),(7,'2026_04_02_110139_create_inventory_categories_table',1),(8,'2026_04_02_110141_create_inventory_products_table',1),(9,'2026_04_02_110142_create_inventory_moves_table',1),(10,'2026_04_02_112207_create_inventory_product_uom_conversions_table',1),(11,'2026_04_02_112208_add_uom_fields_to_inventory_moves',1),(12,'2026_04_02_113546_create_purchase_vendors_table',1),(13,'2026_04_02_113547_create_purchase_orders_table',1),(14,'2026_04_02_113553_create_purchase_order_lines_table',1),(15,'2026_04_02_115812_create_inventory_cost_layers_table',1),(16,'2026_04_02_115814_add_cost_fields_to_inventory_moves',1),(17,'2026_04_02_120408_create_notifications_table',1),(18,'2026_04_02_121946_create_pos_sessions_table',1),(19,'2026_04_02_121948_create_pos_cash_movements_table',1),(20,'2026_04_02_121950_create_pos_orders_table',1),(21,'2026_04_02_121952_create_pos_order_items_table',1),(22,'2026_04_02_121954_create_pos_payments_table',1),(23,'2026_04_02_122022_add_barcode_to_inventory_products_table',1),(24,'2026_04_02_130117_create_inventory_product_favorites_table',1),(25,'2026_04_02_145426_create_employees_table',1),(26,'2026_04_02_150532_create_employee_departments_table',1),(27,'2026_04_02_150535_create_employee_designations_table',1),(28,'2026_04_02_150537_add_user_id_to_employees_table',1),(29,'2026_04_02_150539_add_permissions_to_users_table',1),(30,'2026_04_03_000001_create_settings_table',1),(31,'2026_04_03_100001_create_expense_categories_table',1),(32,'2026_04_03_100002_create_expenses_table',1),(33,'2026_04_03_120000_create_manufacturing_boms_table',1),(34,'2026_04_03_120001_create_manufacturing_bom_lines_table',1),(35,'2026_04_03_120002_create_manufacturing_orders_table',1),(36,'2026_04_03_180000_add_cash_tender_fields_to_pos_orders_table',1),(37,'2026_04_03_200000_add_bill_tax_percent_to_pos_orders_table',1),(38,'2026_04_03_200001_create_calendar_events_table',1),(39,'2026_04_03_210000_add_uom_to_manufacturing_bom_lines_table',1),(40,'2026_04_03_220000_create_inventory_uom_library_tables',1),(41,'2026_04_03_300001_create_contacts_table',1),(42,'2026_04_03_300002_add_contact_credit_to_pos_orders',1),(43,'2026_04_03_300003_create_credit_ledger_table',1),(44,'2026_04_03_400001_add_reorder_level_to_inventory_products',1),(45,'2026_04_03_500001_create_report_templates_table',1),(46,'2026_04_04_100000_add_package_contents_to_inventory_products',1),(47,'2026_04_05_000001_ensure_inventory_products_package_contents_columns',1),(48,'2026_04_05_120000_add_for_pos_for_purchase_to_inventory_products',1),(49,'2026_04_05_200000_create_activity_logs_attendance_payroll_tables',1),(50,'2026_04_06_200000_companies_and_multi_tenancy',1),(51,'2026_04_07_140000_add_database_name_to_companies_table',1),(52,'2026_04_08_120000_add_tenant_provision_status_to_companies_table',1),(53,'2026_04_10_000001_create_company_updates_table',1),(54,'2026_04_11_100000_add_feature_key_to_company_updates_table',1),(55,'2026_04_11_100001_create_company_installed_features_table',1),(56,'2026_04_12_120000_create_stock_check_tables',2),(57,'2026_04_09_150000_add_payment_fields_to_purchase_orders_table',3),(58,'2026_04_13_100000_create_password_reset_requests_table',3),(59,'2026_04_09_160000_create_pos_tables_table',4),(60,'2026_04_09_160100_add_table_id_to_pos_orders_table',4),(61,'2026_04_09_170000_add_gas_charges_and_profit_to_inventory_products_table',5),(62,'2026_04_14_120000_add_guest_room_waiter_to_pos_orders_table',6),(63,'2026_04_22_120000_add_extra_costs_to_inventory_products_table',6),(64,'2026_05_07_100000_create_guest_rooms_tables',7),(65,'2026_05_18_140000_guest_rooms_rates_use_category',8),(66,'2026_05_18_150000_room_rates_charge_breakdown',9),(67,'2026_05_18_160000_create_room_person_types_table',10),(68,'2026_05_18_170000_room_rates_nullable_room_type_id',11),(69,'2026_05_18_180000_add_booking_type_to_room_bookings',12),(70,'2026_05_18_190000_add_pa_no_rank_to_room_bookings',13),(71,'2026_05_18_200000_add_care_of_to_room_bookings',14),(72,'2026_05_18_210000_create_room_booking_guest_room_table',15),(73,'2026_05_18_220000_add_released_at_to_room_booking_guest_room',16),(74,'2026_05_18_230000_add_cleaning_checklist_to_guest_rooms',17),(75,'2026_05_18_240000_add_maintenance_fields_to_guest_rooms',18),(76,'2026_06_02_100000_add_guest_category_to_room_bookings',19),(77,'2026_06_03_120000_add_charge_type_to_room_booking_charges',20),(78,'2026_06_03_130000_clear_guest_room_maintenance_status',21),(79,'2026_06_03_140000_create_room_booking_guest_changes_table',22),(80,'2026_06_04_100000_add_rooms_count_to_room_bookings',23),(81,'2026_06_08_100000_add_voucher_no_to_room_bookings',23),(82,'2026_06_09_100000_create_room_booking_members_table',24),(83,'2026_06_10_100000_add_primary_guest_staying_to_room_bookings',24),(84,'2026_06_11_100000_add_vehicles_to_room_bookings',25),(85,'2026_06_20_120000_add_order_taker_fields_to_pos_orders',26),(86,'2026_06_21_120000_add_order_notes_to_pos_orders',27),(87,'2026_06_22_120000_drop_unused_pos_order_columns',28),(88,'2026_06_23_120000_add_kitchen_completed_at_to_pos_orders',29),(89,'2026_06_17_120000_add_bill_discount_percent_to_pos_orders_table',30),(90,'2026_06_17_200000_add_customer_type_to_pos_orders_table',30),(91,'2026_06_24_100000_add_maintenance_expense_fields_to_guest_rooms',30),(92,'2026_06_08_120000_add_must_change_password_to_users_table',31),(93,'2026_06_08_140000_add_kitchen_sort_to_pos_orders',32),(94,'2026_06_08_150000_add_kitchen_position_to_pos_orders',33),(95,'2026_06_08_160000_add_kitchen_status_to_pos_orders',34),(96,'2026_06_20_203500_add_serve_time_to_pos_orders_table',35),(97,'2026_06_20_210000_add_kitchen_pending_to_pos_order_items',36),(98,'2026_06_20_220000_add_kitchen_served_at_to_pos_order_items',37),(99,'2026_06_24_100000_add_serve_date_to_pos_orders_table',38),(100,'2026_06_24_110000_add_serve_meal_to_pos_orders_table',38),(101,'2026_06_24_120000_add_kitchen_timestamps_to_pos_orders_table',39),(102,'2026_06_20_120000_add_daily_closing_columns_to_pos_sessions_table',40),(103,'2026_06_20_140000_add_category_to_contacts_table',40),(104,'2026_06_24_130000_add_order_notes_to_pos_orders_table',40),(105,'2026_07_04_100000_create_sync_tables',40);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_requests`
--

DROP TABLE IF EXISTS `password_reset_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_requests_user_id_foreign` (`user_id`),
  KEY `password_reset_requests_company_id_foreign` (`company_id`),
  KEY `password_reset_requests_resolved_by_foreign` (`resolved_by`),
  KEY `password_reset_requests_status_index` (`status`),
  CONSTRAINT `password_reset_requests_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `password_reset_requests_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `password_reset_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_requests`
--

LOCK TABLES `password_reset_requests` WRITE;
/*!40000 ALTER TABLE `password_reset_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_entries`
--

DROP TABLE IF EXISTS `payroll_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `employee_id` bigint unsigned NOT NULL,
  `period` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_salary` decimal(14,2) NOT NULL DEFAULT '0.00',
  `bonus` decimal(14,2) NOT NULL DEFAULT '0.00',
  `deduction` decimal(14,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(14,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payroll_entries_employee_id_period_unique` (`employee_id`,`period`),
  KEY `payroll_entries_created_by_foreign` (`created_by`),
  KEY `payroll_entries_period_index` (`period`),
  KEY `payroll_entries_status_index` (`status`),
  KEY `payroll_entries_company_id_index` (`company_id`),
  CONSTRAINT `payroll_entries_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payroll_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payroll_entries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_entries`
--

LOCK TABLES `payroll_entries` WRITE;
/*!40000 ALTER TABLE `payroll_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_cash_movements`
--

DROP TABLE IF EXISTS `pos_cash_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_cash_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `session_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_cash_movements_user_id_foreign` (`user_id`),
  KEY `pos_cash_movements_session_id_created_at_index` (`session_id`,`created_at`),
  KEY `pos_cash_movements_company_id_index` (`company_id`),
  CONSTRAINT `pos_cash_movements_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_cash_movements_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `pos_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_cash_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_cash_movements`
--

LOCK TABLES `pos_cash_movements` WRITE;
/*!40000 ALTER TABLE `pos_cash_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `pos_cash_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_order_items`
--

DROP TABLE IF EXISTS `pos_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(6,3) NOT NULL DEFAULT '0.000',
  `tax_percent` decimal(6,3) NOT NULL DEFAULT '0.000',
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kitchen_pending` tinyint(1) NOT NULL DEFAULT '1',
  `kitchen_served_at` timestamp NULL DEFAULT NULL,
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_order_items_product_id_foreign` (`product_id`),
  KEY `pos_order_items_order_id_product_id_index` (`order_id`,`product_id`),
  KEY `pos_order_items_company_id_index` (`company_id`),
  CONSTRAINT `pos_order_items_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `pos_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_order_items`
--

LOCK TABLES `pos_order_items` WRITE;
/*!40000 ALTER TABLE `pos_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `pos_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_orders`
--

DROP TABLE IF EXISTS `pos_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `order_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_id` bigint unsigned DEFAULT NULL,
  `table_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `contact_id` bigint unsigned DEFAULT NULL,
  `customer_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mess_use',
  `sale_mode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `guest_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waiter_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `serve_time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serve_date` date DEFAULT NULL,
  `serve_meal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_credit` tinyint(1) NOT NULL DEFAULT '0',
  `refund_of_order_id` bigint unsigned DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sale',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `order_source` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pos',
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `bill_tax_percent` decimal(8,3) DEFAULT NULL,
  `bill_discount_percent` decimal(8,3) DEFAULT NULL,
  `grand_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `cash_tendered` decimal(12,2) DEFAULT NULL,
  `cash_change` decimal(12,2) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `ready_for_pos_at` timestamp NULL DEFAULT NULL,
  `kitchen_completed_at` timestamp NULL DEFAULT NULL,
  `kitchen_preparing_at` timestamp NULL DEFAULT NULL,
  `kitchen_ready_at` timestamp NULL DEFAULT NULL,
  `kitchen_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kitchen_sort` int unsigned DEFAULT NULL,
  `kitchen_pos_x` smallint unsigned DEFAULT NULL,
  `kitchen_pos_y` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_orders_company_id_order_no_unique` (`company_id`,`order_no`),
  KEY `pos_orders_user_id_foreign` (`user_id`),
  KEY `pos_orders_refund_of_order_id_foreign` (`refund_of_order_id`),
  KEY `pos_orders_session_id_status_index` (`session_id`,`status`),
  KEY `pos_orders_status_index` (`status`),
  KEY `pos_orders_contact_id_foreign` (`contact_id`),
  KEY `pos_orders_company_id_index` (`company_id`),
  KEY `pos_orders_table_id_foreign` (`table_id`),
  CONSTRAINT `pos_orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_orders_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pos_orders_refund_of_order_id_foreign` FOREIGN KEY (`refund_of_order_id`) REFERENCES `pos_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pos_orders_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `pos_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `pos_tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pos_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_orders`
--

LOCK TABLES `pos_orders` WRITE;
/*!40000 ALTER TABLE `pos_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `pos_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_payments`
--

DROP TABLE IF EXISTS `pos_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `order_id` bigint unsigned NOT NULL,
  `method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_payments_order_id_method_index` (`order_id`,`method`),
  KEY `pos_payments_company_id_index` (`company_id`),
  CONSTRAINT `pos_payments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `pos_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_payments`
--

LOCK TABLES `pos_payments` WRITE;
/*!40000 ALTER TABLE `pos_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `pos_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_sessions`
--

DROP TABLE IF EXISTS `pos_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `session_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_date` date DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `opening_cash` decimal(14,2) NOT NULL DEFAULT '0.00',
  `closing_cash` decimal(14,2) DEFAULT NULL,
  `closing_bank` decimal(14,2) DEFAULT NULL,
  `closing_card` decimal(14,2) DEFAULT NULL,
  `amount_to_collect` decimal(14,2) DEFAULT NULL,
  `expected_cash` decimal(14,2) DEFAULT NULL,
  `cash_difference` decimal(14,2) DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_sessions_session_no_unique` (`session_no`),
  KEY `pos_sessions_user_id_status_index` (`user_id`,`status`),
  KEY `pos_sessions_status_index` (`status`),
  KEY `pos_sessions_company_id_index` (`company_id`),
  CONSTRAINT `pos_sessions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pos_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_sessions`
--

LOCK TABLES `pos_sessions` WRITE;
/*!40000 ALTER TABLE `pos_sessions` DISABLE KEYS */;
INSERT INTO `pos_sessions` VALUES (13,2,'DAY-040726-6','2026-07-04',6,'open',0.00,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-04 07:19:42',NULL,NULL,'2026-07-04 07:19:42','2026-07-04 07:19:42');
/*!40000 ALTER TABLE `pos_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_tables`
--

DROP TABLE IF EXISTS `pos_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_tables_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_tables`
--

LOCK TABLES `pos_tables` WRITE;
/*!40000 ALTER TABLE `pos_tables` DISABLE KEYS */;
/*!40000 ALTER TABLE `pos_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_lines`
--

DROP TABLE IF EXISTS `purchase_order_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `purchase_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(14,3) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_percent` decimal(6,3) NOT NULL DEFAULT '0.000',
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_lines_product_id_foreign` (`product_id`),
  KEY `purchase_order_lines_purchase_order_id_product_id_index` (`purchase_order_id`,`product_id`),
  KEY `purchase_order_lines_company_id_index` (`company_id`),
  CONSTRAINT `purchase_order_lines_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_order_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_order_lines_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=706 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_lines`
--

LOCK TABLES `purchase_order_lines` WRITE;
/*!40000 ALTER TABLE `purchase_order_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rfq',
  `purchase_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'debit',
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `order_date` date DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_company_id_number_unique` (`company_id`,`number`),
  KEY `purchase_orders_created_by_foreign` (`created_by`),
  KEY `purchase_orders_vendor_id_created_at_index` (`vendor_id`,`created_at`),
  KEY `purchase_orders_status_index` (`status`),
  KEY `purchase_orders_company_id_index` (`company_id`),
  CONSTRAINT `purchase_orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `purchase_vendors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_vendors`
--

DROP TABLE IF EXISTS `purchase_vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_vendors_name_active_index` (`name`,`active`),
  KEY `purchase_vendors_active_index` (`active`),
  KEY `purchase_vendors_company_id_index` (`company_id`),
  CONSTRAINT `purchase_vendors_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_vendors`
--

LOCK TABLES `purchase_vendors` WRITE;
/*!40000 ALTER TABLE `purchase_vendors` DISABLE KEYS */;
INSERT INTO `purchase_vendors` VALUES (4,2,'Stair Supplies','vendor@example.com','0300-0000000',NULL,NULL,1,'2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `purchase_vendors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_templates`
--

DROP TABLE IF EXISTS `report_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preset` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'this_month',
  `cols` json NOT NULL,
  `filters` json DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_templates_created_by_foreign` (`created_by`),
  KEY `report_templates_company_id_index` (`company_id`),
  CONSTRAINT `report_templates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `report_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_templates`
--

LOCK TABLES `report_templates` WRITE;
/*!40000 ALTER TABLE `report_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_bills`
--

DROP TABLE IF EXISTS `room_bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_bills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `room_booking_id` bigint unsigned NOT NULL,
  `bill_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `extra_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `billed_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_bills_company_id_bill_no_unique` (`company_id`,`bill_no`),
  KEY `room_bills_room_booking_id_foreign` (`room_booking_id`),
  KEY `room_bills_company_id_index` (`company_id`),
  KEY `room_bills_bill_no_index` (`bill_no`),
  KEY `room_bills_payment_status_index` (`payment_status`),
  CONSTRAINT `room_bills_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_bills`
--

LOCK TABLES `room_bills` WRITE;
/*!40000 ALTER TABLE `room_bills` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_bills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_booking_charges`
--

DROP TABLE IF EXISTS `room_booking_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_booking_charges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `room_booking_id` bigint unsigned NOT NULL,
  `charge_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit_amount` decimal(12,2) DEFAULT NULL,
  `charge_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_booking_charges_room_booking_id_foreign` (`room_booking_id`),
  KEY `room_booking_charges_company_id_index` (`company_id`),
  CONSTRAINT `room_booking_charges_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_booking_charges`
--

LOCK TABLES `room_booking_charges` WRITE;
/*!40000 ALTER TABLE `room_booking_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_booking_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_booking_guest_changes`
--

DROP TABLE IF EXISTS `room_booking_guest_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_booking_guest_changes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `room_booking_id` bigint unsigned NOT NULL,
  `field` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_label` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `changed_by` bigint unsigned DEFAULT NULL,
  `changed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_booking_guest_changes_room_booking_id_changed_at_index` (`room_booking_id`,`changed_at`),
  KEY `room_booking_guest_changes_company_id_index` (`company_id`),
  KEY `room_booking_guest_changes_changed_by_index` (`changed_by`),
  CONSTRAINT `room_booking_guest_changes_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_booking_guest_changes`
--

LOCK TABLES `room_booking_guest_changes` WRITE;
/*!40000 ALTER TABLE `room_booking_guest_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_booking_guest_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_booking_guest_room`
--

DROP TABLE IF EXISTS `room_booking_guest_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_booking_guest_room` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_booking_id` bigint unsigned NOT NULL,
  `guest_room_id` bigint unsigned NOT NULL,
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_booking_guest_room_room_booking_id_guest_room_id_unique` (`room_booking_id`,`guest_room_id`),
  KEY `room_booking_guest_room_guest_room_id_foreign` (`guest_room_id`),
  CONSTRAINT `room_booking_guest_room_guest_room_id_foreign` FOREIGN KEY (`guest_room_id`) REFERENCES `guest_rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_booking_guest_room_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_booking_guest_room`
--

LOCK TABLES `room_booking_guest_room` WRITE;
/*!40000 ALTER TABLE `room_booking_guest_room` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_booking_guest_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_booking_members`
--

DROP TABLE IF EXISTS `room_booking_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_booking_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_booking_id` bigint unsigned NOT NULL,
  `member_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_booking_members_room_booking_id_member_type_index` (`room_booking_id`,`member_type`),
  CONSTRAINT `room_booking_members_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_booking_members`
--

LOCK TABLES `room_booking_members` WRITE;
/*!40000 ALTER TABLE `room_booking_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_booking_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_booking_vehicles`
--

DROP TABLE IF EXISTS `room_booking_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_booking_vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_booking_id` bigint unsigned NOT NULL,
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `vehicle_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_accompanying` tinyint(1) NOT NULL DEFAULT '0',
  `driver_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_cnic` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_phone` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_booking_vehicles_room_booking_id_index` (`room_booking_id`),
  CONSTRAINT `room_booking_vehicles_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_booking_vehicles`
--

LOCK TABLES `room_booking_vehicles` WRITE;
/*!40000 ALTER TABLE `room_booking_vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_booking_vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_bookings`
--

DROP TABLE IF EXISTS `room_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `booking_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `voucher_no` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pa_no` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_rank` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `care_of` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_room_id` bigint unsigned DEFAULT NULL,
  `room_category_id` bigint unsigned DEFAULT NULL,
  `person_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_category` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_type_id` bigint unsigned DEFAULT NULL,
  `room_rate_id` bigint unsigned DEFAULT NULL,
  `guest_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_phone` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_cnic` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_guest_staying` tinyint(1) NOT NULL DEFAULT '1',
  `adults` tinyint unsigned NOT NULL DEFAULT '1',
  `children` tinyint unsigned NOT NULL DEFAULT '0',
  `vehicles_count` tinyint unsigned NOT NULL DEFAULT '0',
  `rooms_count` tinyint unsigned NOT NULL DEFAULT '1',
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `actual_check_in` datetime DEFAULT NULL,
  `actual_check_out` datetime DEFAULT NULL,
  `nights` smallint unsigned NOT NULL DEFAULT '1',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reserved',
  `rate_per_night` decimal(12,2) NOT NULL DEFAULT '0.00',
  `room_rent` decimal(12,2) NOT NULL DEFAULT '0.00',
  `electric_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gas_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `media_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `room_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `extra_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_bookings_company_id_booking_no_unique` (`company_id`,`booking_no`),
  KEY `room_bookings_guest_room_id_foreign` (`guest_room_id`),
  KEY `room_bookings_room_type_id_foreign` (`room_type_id`),
  KEY `room_bookings_room_rate_id_foreign` (`room_rate_id`),
  KEY `room_bookings_company_id_index` (`company_id`),
  KEY `room_bookings_booking_no_index` (`booking_no`),
  KEY `room_bookings_status_index` (`status`),
  KEY `room_bookings_room_category_id_index` (`room_category_id`),
  KEY `room_bookings_booking_type_index` (`booking_type`),
  KEY `room_bookings_guest_category_index` (`guest_category`),
  KEY `room_bookings_voucher_no_index` (`voucher_no`),
  CONSTRAINT `room_bookings_guest_room_id_foreign` FOREIGN KEY (`guest_room_id`) REFERENCES `guest_rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `room_bookings_room_rate_id_foreign` FOREIGN KEY (`room_rate_id`) REFERENCES `room_rates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `room_bookings_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_bookings`
--

LOCK TABLES `room_bookings` WRITE;
/*!40000 ALTER TABLE `room_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_categories`
--

DROP TABLE IF EXISTS `room_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_categories_company_id_name_unique` (`company_id`,`name`),
  KEY `room_categories_company_id_index` (`company_id`),
  KEY `room_categories_active_index` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_categories`
--

LOCK TABLES `room_categories` WRITE;
/*!40000 ALTER TABLE `room_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_person_types`
--

DROP TABLE IF EXISTS `room_person_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_person_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_person_types_company_id_name_unique` (`company_id`,`name`),
  KEY `room_person_types_company_id_index` (`company_id`),
  KEY `room_person_types_active_index` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_person_types`
--

LOCK TABLES `room_person_types` WRITE;
/*!40000 ALTER TABLE `room_person_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_person_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_rates`
--

DROP TABLE IF EXISTS `room_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `room_category_id` bigint unsigned DEFAULT NULL,
  `person_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_rent` decimal(12,2) NOT NULL DEFAULT '0.00',
  `electric_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gas_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `media_charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `room_type_id` bigint unsigned DEFAULT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nightly',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_rates_company_id_index` (`company_id`),
  KEY `room_rates_active_index` (`active`),
  KEY `room_rates_room_category_id_index` (`room_category_id`),
  KEY `room_rates_room_type_id_foreign` (`room_type_id`),
  CONSTRAINT `room_rates_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_rates`
--

LOCK TABLES `room_rates` WRITE;
/*!40000 ALTER TABLE `room_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_types`
--

DROP TABLE IF EXISTS `room_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `room_category_id` bigint unsigned DEFAULT NULL,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_occupancy` tinyint unsigned NOT NULL DEFAULT '2',
  `bed_count` tinyint unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_types_company_id_name_unique` (`company_id`,`name`),
  KEY `room_types_room_category_id_foreign` (`room_category_id`),
  KEY `room_types_company_id_index` (`company_id`),
  KEY `room_types_active_index` (`active`),
  CONSTRAINT `room_types_room_category_id_foreign` FOREIGN KEY (`room_category_id`) REFERENCES `room_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_types`
--

LOCK TABLES `room_types` WRITE;
/*!40000 ALTER TABLE `room_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL DEFAULT '1',
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_company_id_key_unique` (`company_id`,`key`),
  CONSTRAINT `settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_check_lines`
--

DROP TABLE IF EXISTS `stock_check_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_check_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `stock_check_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `expected_qty` decimal(18,6) NOT NULL,
  `counted_qty` decimal(18,6) DEFAULT NULL,
  `note` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_check_lines_company_id_foreign` (`company_id`),
  KEY `stock_check_lines_stock_check_id_foreign` (`stock_check_id`),
  KEY `stock_check_lines_product_id_foreign` (`product_id`),
  CONSTRAINT `stock_check_lines_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_check_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_check_lines_stock_check_id_foreign` FOREIGN KEY (`stock_check_id`) REFERENCES `stock_checks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_check_lines`
--

LOCK TABLES `stock_check_lines` WRITE;
/*!40000 ALTER TABLE `stock_check_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_check_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_checks`
--

DROP TABLE IF EXISTS `stock_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_checks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reject_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_checks_company_id_number_unique` (`company_id`,`number`),
  KEY `stock_checks_status_index` (`status`),
  KEY `stock_checks_created_by_index` (`created_by`),
  CONSTRAINT `stock_checks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_checks`
--

LOCK TABLES `stock_checks` WRITE;
/*!40000 ALTER TABLE `stock_checks` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sync_meta`
--

DROP TABLE IF EXISTS `sync_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sync_meta` (
  `meta_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sync_meta`
--

LOCK TABLES `sync_meta` WRITE;
/*!40000 ALTER TABLE `sync_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `sync_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sync_queue`
--

DROP TABLE IF EXISTS `sync_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sync_queue` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `last_error` text COLLATE utf8mb4_unicode_ci,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sync_queue_synced_at_id_index` (`synced_at`,`id`),
  KEY `sync_queue_table_name_record_key_synced_at_index` (`table_name`,`record_key`,`synced_at`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sync_queue`
--

LOCK TABLES `sync_queue` WRITE;
/*!40000 ALTER TABLE `sync_queue` DISABLE KEYS */;
INSERT INTO `sync_queue` VALUES (1,'companies','2','upsert','{\"id\": 2, \"name\": \"Default Company\", \"slug\": \"default\", \"active\": true, \"created_at\": \"2026-07-04 12:02:40\", \"updated_at\": \"2026-07-04 12:02:40\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:40','2026-07-04 07:23:29'),(2,'employee_departments','2','upsert','{\"id\": 2, \"name\": \"Administration\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:40\", \"updated_at\": \"2026-07-04 12:02:40\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:40','2026-07-04 07:23:29'),(3,'employee_designations','7','upsert','{\"id\": 7, \"name\": \"Super Administrator\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:40\", \"updated_at\": \"2026-07-04 12:02:40\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:40','2026-07-04 07:23:29'),(4,'users','5','upsert','{\"id\": 5, \"name\": \"Company Admin\", \"role\": \"company_admin\", \"email\": \"admin@example.com\", \"password\": \"$2y$12$ohkIPAtSfOsthzepZBF7EOCkLbECpgur6Mn2JA3HbyNKixPs/iaEK\", \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"permissions\": null, \"email_verified_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(5,'employees','9','upsert','{\"id\": 9, \"name\": \"Company Admin\", \"email\": \"admin@example.com\", \"phone\": null, \"active\": true, \"salary\": 0, \"address\": null, \"user_id\": 5, \"join_date\": \"2026-07-04 00:00:00\", \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"employee_no\": \"EMP-ADMIN-001\", \"department_id\": 2, \"designation_id\": 7}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(6,'users','6','upsert','{\"id\": 6, \"name\": \"Super Admin\", \"role\": \"super_admin\", \"email\": \"superadmin@example.com\", \"password\": \"$2y$12$D2Q5o.g5JhF1dlrK1D0jUOVI2oMw/ow.0rADXsaGpQqk4WGwvUzjy\", \"company_id\": null, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"permissions\": null, \"email_verified_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(7,'inventory_units','10','upsert','{\"id\": 10, \"code\": \"kg\", \"name\": \"Kilogram\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(8,'inventory_units','11','upsert','{\"id\": 11, \"code\": \"g\", \"name\": \"Gram\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(9,'inventory_units','12','upsert','{\"id\": 12, \"code\": \"gm\", \"name\": \"Gram (alias)\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(10,'inventory_units','13','upsert','{\"id\": 13, \"code\": \"ltr\", \"name\": \"Litre\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(11,'inventory_units','14','upsert','{\"id\": 14, \"code\": \"ml\", \"name\": \"Millilitre\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(12,'inventory_units','15','upsert','{\"id\": 15, \"code\": \"pcs\", \"name\": \"Pieces\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(13,'inventory_units','16','upsert','{\"id\": 16, \"code\": \"box\", \"name\": \"Box\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(14,'inventory_units','17','upsert','{\"id\": 17, \"code\": \"pkt\", \"name\": \"Packet\", \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(15,'inventory_unit_conversions','4','upsert','{\"id\": 4, \"note\": \"1 g = 0.001 kg\", \"factor\": 0.001, \"created_at\": \"2026-07-04 12:02:41\", \"to_unit_id\": 10, \"updated_at\": \"2026-07-04 12:02:41\", \"from_unit_id\": 11}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(16,'inventory_unit_conversions','5','upsert','{\"id\": 5, \"note\": \"Same as g → kg\", \"factor\": 0.001, \"created_at\": \"2026-07-04 12:02:41\", \"to_unit_id\": 10, \"updated_at\": \"2026-07-04 12:02:41\", \"from_unit_id\": 12}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(17,'inventory_unit_conversions','6','upsert','{\"id\": 6, \"note\": \"1 ml = 0.001 ltr\", \"factor\": 0.001, \"created_at\": \"2026-07-04 12:02:41\", \"to_unit_id\": 13, \"updated_at\": \"2026-07-04 12:02:41\", \"from_unit_id\": 14}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(18,'expense_categories','9','upsert','{\"id\": 9, \"name\": \"Travel\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Flights, hotels, transport, fuel\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(19,'expense_categories','10','upsert','{\"id\": 10, \"name\": \"Meals & Entertainment\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Client lunches, team dinners\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(20,'expense_categories','11','upsert','{\"id\": 11, \"name\": \"Office Supplies\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Stationery, printing, consumables\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(21,'expense_categories','12','upsert','{\"id\": 12, \"name\": \"Communication\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Phone, internet, courier\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(22,'expense_categories','13','upsert','{\"id\": 13, \"name\": \"Training\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Courses, books, conferences\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(23,'expense_categories','14','upsert','{\"id\": 14, \"name\": \"Software & Tools\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Licenses, subscriptions, SaaS tools\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(24,'expense_categories','15','upsert','{\"id\": 15, \"name\": \"Medical\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Medical, health, insurance related\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(25,'expense_categories','16','upsert','{\"id\": 16, \"name\": \"Miscellaneous\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"description\": \"Other business expenses\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(26,'inventory_categories','26','upsert','{\"id\": 26, \"name\": \"All Products\", \"parent_id\": null, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(27,'inventory_products','404','upsert','{\"id\": 404, \"sku\": \"STAIR-001\", \"uom\": \"tablet\", \"cost\": 10, \"name\": \"Stair Sample Product\", \"price\": 15, \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\", \"category_id\": 26, \"qty_on_hand\": 0}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(28,'inventory_product_uom_conversions','332','upsert','{\"id\": 332, \"uom\": \"pkt\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"product_id\": 404, \"updated_at\": \"2026-07-04 12:02:41\", \"factor_to_base\": 10}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(29,'inventory_product_uom_conversions','333','upsert','{\"id\": 333, \"uom\": \"Box\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"product_id\": 404, \"updated_at\": \"2026-07-04 12:02:41\", \"factor_to_base\": 100}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(30,'purchase_vendors','4','upsert','{\"id\": 4, \"name\": \"Stair Supplies\", \"email\": \"vendor@example.com\", \"phone\": \"0300-0000000\", \"active\": true, \"company_id\": 2, \"created_at\": \"2026-07-04 12:02:41\", \"updated_at\": \"2026-07-04 12:02:41\"}',16,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:02:41','2026-07-04 07:23:29'),(31,'pos_sessions','13','upsert','{\"id\": 13, \"status\": \"open\", \"user_id\": 6, \"opened_at\": \"2026-07-04 12:19:42\", \"company_id\": 2, \"created_at\": \"2026-07-04 12:19:42\", \"session_no\": \"DAY-040726-6\", \"updated_at\": \"2026-07-04 12:19:42\", \"opening_cash\": 0, \"business_date\": \"2026-07-04 00:00:00\"}',7,'<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL was not found on this server.</p>\n</body></html>\n',NULL,'2026-07-04 07:19:42','2026-07-04 07:23:29');
/*!40000 ALTER TABLE `sync_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `company_id` bigint unsigned DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_company_id_index` (`company_id`),
  CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'Company Admin','admin@example.com','2026-07-04 07:02:41','$2y$12$ohkIPAtSfOsthzepZBF7EOCkLbECpgur6Mn2JA3HbyNKixPs/iaEK',0,'company_admin',2,NULL,NULL,'2026-07-04 07:02:41','2026-07-04 07:02:41'),(6,'Super Admin','superadmin@example.com','2026-07-04 07:02:41','$2y$12$D2Q5o.g5JhF1dlrK1D0jUOVI2oMw/ow.0rADXsaGpQqk4WGwvUzjy',0,'super_admin',NULL,NULL,NULL,'2026-07-04 07:02:41','2026-07-04 07:02:41');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'signature_local'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-04 12:23:43
