-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: nrna_de
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignments_user_id_foreign` (`user_id`),
  KEY `assignments_role_id_foreign` (`role_id`),
  CONSTRAINT `assignments_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendars`
--

DROP TABLE IF EXISTS `calendars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `google_account_id` bigint unsigned NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendars_google_account_id_foreign` (`google_account_id`),
  CONSTRAINT `calendars_google_account_id_foreign` FOREIGN KEY (`google_account_id`) REFERENCES `google_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendars`
--

LOCK TABLES `calendars` WRITE;
/*!40000 ALTER TABLE `calendars` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidacies`
--

DROP TABLE IF EXISTS `candidacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidacies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `candidacy_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_order` int NOT NULL DEFAULT '0' COMMENT 'Display order of candidate within post',
  `proposer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supporter_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `election_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `candidacies_candidacy_id_unique` (`candidacy_id`),
  UNIQUE KEY `candidacies_user_id_unique` (`user_id`),
  UNIQUE KEY `candidacies_proposer_id_unique` (`proposer_id`),
  UNIQUE KEY `candidacies_supporter_id_unique` (`supporter_id`),
  KEY `candidacies_post_id_foreign` (`post_id`),
  KEY `candidacies_election_id_foreign` (`election_id`),
  KEY `candidacies_organisation_id_index` (`organisation_id`),
  CONSTRAINT `candidacies_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `candidacies_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  CONSTRAINT `candidacies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidacies`
--

LOCK TABLES `candidacies` WRITE;
/*!40000 ALTER TABLE `candidacies` DISABLE KEYS */;
/*!40000 ALTER TABLE `candidacies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `session_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `election_id` bigint unsigned DEFAULT NULL COMMENT 'Reference to elections table - scopes verification codes per election',
  `code1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vote_show_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_code1_usable` tinyint(1) NOT NULL DEFAULT '0',
  `code1_sent_at` timestamp NULL DEFAULT NULL,
  `is_code2_usable` tinyint(1) NOT NULL DEFAULT '0',
  `code2_sent_at` timestamp NULL DEFAULT NULL,
  `is_code3_usable` tinyint(1) NOT NULL DEFAULT '0',
  `is_code4_usable` tinyint(1) NOT NULL DEFAULT '0',
  `can_vote_now` tinyint(1) NOT NULL DEFAULT '0',
  `has_voted` tinyint(1) NOT NULL DEFAULT '0',
  `voting_time_in_minutes` int unsigned DEFAULT NULL,
  `vote_last_seen` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vote_completed_at` datetime DEFAULT NULL,
  `code1_used_at` timestamp NULL DEFAULT NULL,
  `code2_used_at` timestamp NULL DEFAULT NULL,
  `code3_used_at` timestamp NULL DEFAULT NULL,
  `code4_used_at` timestamp NULL DEFAULT NULL,
  `code_for_vote` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vote_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `vote_submitted_at` timestamp NULL DEFAULT NULL,
  `has_code1_sent` tinyint(1) NOT NULL DEFAULT '0',
  `has_code2_sent` tinyint(1) NOT NULL DEFAULT '0',
  `client_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_agreed_to_vote` tinyint(1) NOT NULL DEFAULT '0',
  `has_agreed_to_vote_at` timestamp NULL DEFAULT NULL,
  `voting_started_at` timestamp NULL DEFAULT NULL,
  `has_used_code1` tinyint(1) NOT NULL DEFAULT '0',
  `has_used_code2` tinyint NOT NULL DEFAULT '0',
  `is_codemodel_valid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codes_user_id_election_id_unique` (`user_id`,`election_id`),
  KEY `codes_election_id_user_id_index` (`election_id`,`user_id`),
  KEY `codes_election_id_can_vote_now_index` (`election_id`,`can_vote_now`),
  KEY `codes_organisation_id_index` (`organisation_id`),
  CONSTRAINT `codes_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codes`
--

LOCK TABLES `codes` WRITE;
/*!40000 ALTER TABLE `codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `committee_types`
--

DROP TABLE IF EXISTS `committee_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `committee_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hierarchical_number` tinyint unsigned NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committee_types`
--

LOCK TABLES `committee_types` WRITE;
/*!40000 ALTER TABLE `committee_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `committee_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `committees`
--

DROP TABLE IF EXISTS `committees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `committees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hierarchal_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_from` datetime DEFAULT NULL,
  `period_to` datetime DEFAULT NULL,
  `period_number` int unsigned DEFAULT NULL,
  `election_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committees`
--

LOCK TABLES `committees` WRITE;
/*!40000 ALTER TABLE `committees` DISABLE KEYS */;
/*!40000 ALTER TABLE `committees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deligate_candidacies`
--

DROP TABLE IF EXISTS `deligate_candidacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deligate_candidacies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `nrna_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `post_id` bigint unsigned NOT NULL,
  `image_path_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deligate_candidacies_organisation_id_index` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deligate_candidacies`
--

LOCK TABLES `deligate_candidacies` WRITE;
/*!40000 ALTER TABLE `deligate_candidacies` DISABLE KEYS */;
/*!40000 ALTER TABLE `deligate_candidacies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deligate_votes`
--

DROP TABLE IF EXISTS `deligate_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deligate_votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `conformation_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_vote_option` tinyint(1) NOT NULL DEFAULT '0',
  `member1_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member2_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member3_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member4_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member5_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member6_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member7_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member8_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member9_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member10_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member11_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member12_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member13_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member14_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member15_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member16_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member17_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member18_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member19_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member20_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member21_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member22_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member23_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member24_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member25_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member26_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member27_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member28_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member29_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member30_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member31_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member32_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member33_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member34_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member35_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deligate_votes_organisation_id_index` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deligate_votes`
--

LOCK TABLES `deligate_votes` WRITE;
/*!40000 ALTER TABLE `deligate_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `deligate_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_candidacies`
--

DROP TABLE IF EXISTS `demo_candidacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_candidacies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `candidacy_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candidacy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `election_id` bigint unsigned NOT NULL DEFAULT '1',
  `organisation_id` bigint unsigned DEFAULT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_order` int NOT NULL DEFAULT '0' COMMENT 'Display order of candidate within post',
  `post_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_nepali_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proposer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proposer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supporter_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supporter_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demo_candidacies_candidacy_id_unique` (`candidacy_id`),
  KEY `demo_candidacies_election_id_index` (`election_id`),
  KEY `demo_candidacies_post_id_index` (`post_id`),
  KEY `demo_candidacies_election_id_post_id_index` (`election_id`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_candidacies`
--

LOCK TABLES `demo_candidacies` WRITE;
/*!40000 ALTER TABLE `demo_candidacies` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_candidacies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_codes`
--

DROP TABLE IF EXISTS `demo_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `election_id` bigint unsigned NOT NULL,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `code1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vote_show_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_code1_usable` tinyint(1) NOT NULL DEFAULT '0',
  `is_code2_usable` tinyint(1) NOT NULL DEFAULT '0',
  `is_code3_usable` tinyint(1) NOT NULL DEFAULT '0',
  `is_code4_usable` tinyint(1) NOT NULL DEFAULT '0',
  `code1_sent_at` datetime DEFAULT NULL,
  `code2_sent_at` datetime DEFAULT NULL,
  `code3_sent_at` datetime DEFAULT NULL,
  `code4_sent_at` datetime DEFAULT NULL,
  `code1_used_at` datetime DEFAULT NULL,
  `code2_used_at` datetime DEFAULT NULL,
  `code3_used_at` datetime DEFAULT NULL,
  `code4_used_at` datetime DEFAULT NULL,
  `can_vote_now` tinyint(1) NOT NULL DEFAULT '0',
  `has_voted` tinyint(1) NOT NULL DEFAULT '0',
  `vote_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `vote_submitted_at` datetime DEFAULT NULL,
  `has_agreed_to_vote` tinyint(1) NOT NULL DEFAULT '0',
  `has_agreed_to_vote_at` datetime DEFAULT NULL,
  `has_code1_sent` tinyint(1) NOT NULL DEFAULT '0',
  `has_code2_sent` tinyint(1) NOT NULL DEFAULT '0',
  `has_used_code1` tinyint(1) NOT NULL DEFAULT '0',
  `has_used_code2` tinyint(1) NOT NULL DEFAULT '0',
  `voting_time_in_minutes` int NOT NULL DEFAULT '30',
  `vote_last_seen` datetime DEFAULT NULL,
  `client_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voting_started_at` datetime DEFAULT NULL,
  `code_for_vote` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demo_codes_user_id_election_id_organisation_id_unique` (`user_id`,`election_id`,`organisation_id`),
  KEY `demo_codes_user_id_index` (`user_id`),
  KEY `demo_codes_election_id_index` (`election_id`),
  KEY `demo_codes_organisation_id_index` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_codes`
--

LOCK TABLES `demo_codes` WRITE;
/*!40000 ALTER TABLE `demo_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_posts`
--

DROP TABLE IF EXISTS `demo_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nepali_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `election_id` bigint unsigned NOT NULL,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `state_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_order` int NOT NULL DEFAULT '0',
  `required_number` int NOT NULL DEFAULT '1',
  `is_national_wide` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demo_posts_post_id_election_id_unique` (`post_id`,`election_id`),
  KEY `demo_posts_election_id_foreign` (`election_id`),
  CONSTRAINT `demo_posts_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_posts`
--

LOCK TABLES `demo_posts` WRITE;
/*!40000 ALTER TABLE `demo_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_results`
--

DROP TABLE IF EXISTS `demo_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `election_id` bigint unsigned NOT NULL DEFAULT '1' COMMENT 'Reference to elections table - scopes results per election',
  `vote_id` bigint unsigned NOT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidacy_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demo_results_election_id_index` (`election_id`),
  KEY `demo_results_election_id_vote_id_index` (`election_id`,`vote_id`),
  KEY `demo_results_organisation_id_index` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_results`
--

LOCK TABLES `demo_results` WRITE;
/*!40000 ALTER TABLE `demo_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_voter_slug_steps`
--

DROP TABLE IF EXISTS `demo_voter_slug_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_voter_slug_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `demo_voter_slug_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `election_id` bigint unsigned DEFAULT NULL,
  `step` int NOT NULL,
  `step_data` json DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demo_voter_slug_steps_demo_voter_slug_id_step_index` (`demo_voter_slug_id`,`step`),
  KEY `demo_voter_slug_steps_election_id_step_index` (`election_id`,`step`),
  KEY `demo_voter_slug_steps_organisation_id_index` (`organisation_id`),
  KEY `demo_voter_slug_steps_demo_voter_slug_id_index` (`demo_voter_slug_id`),
  KEY `demo_voter_slug_steps_election_id_index` (`election_id`),
  CONSTRAINT `demo_voter_slug_steps_demo_voter_slug_id_foreign` FOREIGN KEY (`demo_voter_slug_id`) REFERENCES `demo_voter_slugs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_voter_slug_steps`
--

LOCK TABLES `demo_voter_slug_steps` WRITE;
/*!40000 ALTER TABLE `demo_voter_slug_steps` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_voter_slug_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_voter_slugs`
--

DROP TABLE IF EXISTS `demo_voter_slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_voter_slugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `election_id` bigint unsigned DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `current_step` int NOT NULL DEFAULT '0',
  `step_meta` json DEFAULT NULL,
  `has_voted` tinyint(1) NOT NULL DEFAULT '0',
  `can_vote_now` tinyint(1) NOT NULL DEFAULT '1',
  `voting_time_min` int DEFAULT NULL,
  `step_1_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_1_completed_at` timestamp NULL DEFAULT NULL,
  `step_2_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_2_completed_at` timestamp NULL DEFAULT NULL,
  `step_3_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_3_completed_at` timestamp NULL DEFAULT NULL,
  `step_4_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_4_completed_at` timestamp NULL DEFAULT NULL,
  `step_5_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `step_5_completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demo_voter_slugs_slug_unique` (`slug`),
  KEY `demo_voter_slugs_election_id_user_id_index` (`election_id`,`user_id`),
  KEY `demo_voter_slugs_organisation_id_election_id_index` (`organisation_id`,`election_id`),
  KEY `demo_voter_slugs_user_id_election_id_index` (`user_id`,`election_id`),
  KEY `demo_voter_slugs_organisation_id_index` (`organisation_id`),
  KEY `demo_voter_slugs_user_id_index` (`user_id`),
  KEY `demo_voter_slugs_election_id_index` (`election_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_voter_slugs`
--

LOCK TABLES `demo_voter_slugs` WRITE;
/*!40000 ALTER TABLE `demo_voter_slugs` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_voter_slugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_votes`
--

DROP TABLE IF EXISTS `demo_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `election_id` bigint unsigned NOT NULL DEFAULT '1' COMMENT 'Reference to elections table - scopes demo votes per election',
  `no_vote_option` tinyint(1) NOT NULL DEFAULT '0',
  `voting_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candidate_01` json DEFAULT NULL,
  `candidate_02` json DEFAULT NULL,
  `candidate_03` json DEFAULT NULL,
  `candidate_04` json DEFAULT NULL,
  `candidate_05` json DEFAULT NULL,
  `candidate_06` json DEFAULT NULL,
  `candidate_07` json DEFAULT NULL,
  `candidate_08` json DEFAULT NULL,
  `candidate_09` json DEFAULT NULL,
  `candidate_10` json DEFAULT NULL,
  `candidate_11` json DEFAULT NULL,
  `candidate_12` json DEFAULT NULL,
  `candidate_13` json DEFAULT NULL,
  `candidate_14` json DEFAULT NULL,
  `candidate_15` json DEFAULT NULL,
  `candidate_16` json DEFAULT NULL,
  `candidate_17` json DEFAULT NULL,
  `candidate_18` json DEFAULT NULL,
  `candidate_19` json DEFAULT NULL,
  `candidate_20` json DEFAULT NULL,
  `candidate_21` json DEFAULT NULL,
  `candidate_22` json DEFAULT NULL,
  `candidate_23` json DEFAULT NULL,
  `candidate_24` json DEFAULT NULL,
  `candidate_25` json DEFAULT NULL,
  `candidate_26` json DEFAULT NULL,
  `candidate_27` json DEFAULT NULL,
  `candidate_28` json DEFAULT NULL,
  `candidate_29` json DEFAULT NULL,
  `candidate_30` json DEFAULT NULL,
  `candidate_31` json DEFAULT NULL,
  `candidate_32` json DEFAULT NULL,
  `candidate_33` json DEFAULT NULL,
  `candidate_34` json DEFAULT NULL,
  `candidate_35` json DEFAULT NULL,
  `candidate_36` json DEFAULT NULL,
  `candidate_37` json DEFAULT NULL,
  `candidate_38` json DEFAULT NULL,
  `candidate_39` json DEFAULT NULL,
  `candidate_40` json DEFAULT NULL,
  `candidate_41` json DEFAULT NULL,
  `candidate_42` json DEFAULT NULL,
  `candidate_43` json DEFAULT NULL,
  `candidate_44` json DEFAULT NULL,
  `candidate_45` json DEFAULT NULL,
  `candidate_46` json DEFAULT NULL,
  `candidate_47` json DEFAULT NULL,
  `candidate_48` json DEFAULT NULL,
  `candidate_49` json DEFAULT NULL,
  `candidate_50` json DEFAULT NULL,
  `candidate_51` json DEFAULT NULL,
  `candidate_52` json DEFAULT NULL,
  `candidate_53` json DEFAULT NULL,
  `candidate_54` json DEFAULT NULL,
  `candidate_55` json DEFAULT NULL,
  `candidate_56` json DEFAULT NULL,
  `candidate_57` json DEFAULT NULL,
  `candidate_58` json DEFAULT NULL,
  `candidate_59` json DEFAULT NULL,
  `candidate_60` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demo_votes_voting_code_unique` (`voting_code`),
  KEY `demo_votes_election_id_index` (`election_id`),
  KEY `demo_votes_verification_code_index` (`verification_code`),
  KEY `demo_votes_organisation_id_index` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_votes`
--

LOCK TABLES `demo_votes` WRITE;
/*!40000 ALTER TABLE `demo_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `election_commission_members`
--

DROP TABLE IF EXISTS `election_commission_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `election_commission_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `election_id` bigint unsigned NOT NULL,
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `election_commission_members_user_id_election_id_unique` (`user_id`,`election_id`),
  KEY `election_commission_members_election_id_foreign` (`election_id`),
  CONSTRAINT `election_commission_members_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `election_commission_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `election_commission_members`
--

LOCK TABLES `election_commission_members` WRITE;
/*!40000 ALTER TABLE `election_commission_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `election_commission_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `elections`
--

DROP TABLE IF EXISTS `elections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `elections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('demo','real') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'demo',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('planned','active','completed','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `settings` json DEFAULT NULL COMMENT 'Election-specific settings as JSON',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organisation_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `elections_slug_unique` (`slug`),
  UNIQUE KEY `elections_id_organisation_id_unique` (`id`,`organisation_id`),
  KEY `elections_type_index` (`type`),
  KEY `elections_is_active_index` (`is_active`),
  KEY `elections_type_is_active_index` (`type`,`is_active`),
  KEY `elections_status_index` (`status`),
  KEY `elections_organisation_id_status_index` (`organisation_id`,`status`),
  CONSTRAINT `elections_organisation_id_foreign` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `elections`
--

LOCK TABLES `elections` WRITE;
/*!40000 ALTER TABLE `elections` DISABLE KEYS */;
/*!40000 ALTER TABLE `elections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `allday` tinyint(1) NOT NULL DEFAULT '0',
  `started_at` datetime NOT NULL,
  `ended_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `calendar_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `events_calendar_id_foreign` (`calendar_id`),
  CONSTRAINT `events_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `google_accounts`
--

DROP TABLE IF EXISTS `google_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `google_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `google_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `google_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `google_accounts`
--

LOCK TABLES `google_accounts` WRITE;
/*!40000 ALTER TABLE `google_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `google_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(750) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `images_path_unique` (`path`),
  KEY `images_user_id_foreign` (`user_id`),
  CONSTRAINT `images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incomes`
--

DROP TABLE IF EXISTS `incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `committee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `membership_fee` double DEFAULT NULL,
  `nomination_fee` double DEFAULT NULL,
  `sponser_fee` double DEFAULT NULL,
  `donation` double DEFAULT NULL,
  `levy` double DEFAULT NULL,
  `event_fee` double DEFAULT NULL,
  `event_income` double DEFAULT NULL,
  `event_contribution` double DEFAULT NULL,
  `deligate_fee` double DEFAULT NULL,
  `deligate_contribution` double DEFAULT NULL,
  `interest_income` double DEFAULT NULL,
  `business_income` double DEFAULT NULL,
  `other_incomes` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incomes`
--

LOCK TABLES `incomes` WRITE;
/*!40000 ALTER TABLE `incomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `incomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_receiver_id` bigint unsigned NOT NULL,
  `message_receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_sender_id` bigint unsigned NOT NULL,
  `message_sender_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2020_05_21_100000_create_teams_table',1),(7,'2020_05_21_200000_create_team_user_table',1),(8,'2020_05_21_300000_create_team_invitations_table',1),(9,'2021_07_07_191214_create_uploads_table',1),(10,'2021_07_15_205524_create_sessions_table',1),(11,'2021_07_17_050000_create_posts_table',1),(12,'2021_07_17_183332_create_notices_table',1),(13,'2021_07_18_154509_add_more_fields_to_users_table',1),(14,'2021_07_18_205316_create_candidacies_table',1),(15,'2021_07_20_122714_add_more_fields_to_table_users',1),(16,'2021_07_21_134700_create_messages_table',1),(17,'2021_07_21_140827_create_sms_table',1),(18,'2021_07_22_071951_create_permission_tables',1),(19,'2021_07_22_074215_create_assignments_table',1),(20,'2021_07_22_192509_create_votes_table',1),(21,'2021_07_22_195040_create_codes_table',1),(22,'2021_07_23_154239_add_more_columns_to_users_table',1),(23,'2021_07_24_053252_add_more_fields_to_votes',1),(24,'2021_07_26_152809_add_more_column_to_users-table',1),(25,'2021_07_27_033403_add_lcc_to_users_table',1),(26,'2021_08_12_136004_create_deligate_candidacies_table',1),(27,'2021_08_12_144251_create_deligate_votes_table',1),(28,'2021_12_27_134643_add_foreign_keys_to_candidacies',1),(29,'2022_01_03_182522_add_more_columns_to_codes_table',1),(30,'2022_01_09_182800_add_code_for_vote_to_codes',1),(31,'2022_01_10_192127_create_results_table',1),(32,'2022_01_12_114051_add_foreign_key_to_results',1),(33,'2022_01_12_124900_add_vote_submitted_to_codes',1),(34,'2022_01_15_111722_add_ip_address_to_codes',1),(35,'2022_01_19_163354_create_students_table',1),(36,'2022_01_23_180240_add_birth_year_to_students',1),(37,'2022_05_08_164130_create_committee_types_table',1),(38,'2022_05_18_202855_add_designation_column_to_users_table',1),(39,'2022_05_19_143356_create_images_table',1),(40,'2022_05_19_151447_add_user_as_foreignkey_to_images',1),(41,'2022_05_22_162749_create_google_accounts_table',1),(42,'2022_05_22_170832_create_events_table',1),(43,'2022_05_24_161715_create_calendars_table',1),(44,'2022_05_24_162033_add_foreign_key_calendar_id_to_events',1),(45,'2022_05_24_162527_create_synchronizations_table',1),(46,'2022_05_25_191926_add_social_login_column_to_users_table',1),(47,'2022_05_26_125951_add_profile_icon_path_to_users_table',1),(48,'2022_05_29_073227_create_jobs_table',1),(49,'2022_06_07_144135_add_facebook_id_to_users_table',1),(50,'2022_06_09_150012_create_openions_table',1),(51,'2022_06_09_153009_create_committees_table',1),(52,'2022_10_02_113256_create_income_table',1),(53,'2022_10_03_103214_create_outcomes_table',1),(54,'2025_08_03_014233_add_code2_sent_at_to_codes_table',1),(55,'2025_08_03_112238_add_is_committte_member_and_more_columns_to_users_table',1),(56,'2025_08_03_115951_add_approved_by_to_users_table',1),(57,'2025_08_03_125026_add_suspended_by_to_users_table',1),(58,'2025_08_04_001814_add_voter_status_index_to_users_table',1),(59,'2025_08_04_191121_add_code1_sent_at_to_table_codes',1),(60,'2025_08_04_220254_add_agreed_to_vote_fields_to_codes_table',1),(61,'2025_08_04_222754_add_voting_started_at_to_codes_table',1),(62,'2025_08_04_230938_add_has_used_codes_to_codes_table',1),(63,'2025_08_05_162701_remove_user_foreign_key_from_votes',1),(64,'2025_08_05_165123_add_vote_session_name_to_votes_table',1),(65,'2025_08_05_212137_add_session_name_to_codes_table',1),(66,'2025_08_07_005510_add_vote_completed_at_to_table_codes',1),(67,'2025_08_07_012929_add_user_ip_to_users_table',1),(68,'2025_08_07_152147_add_is_valid_to_table_codes',1),(69,'2025_08_08_151004_add_voting_ip_to_users_table',1),(70,'2025_08_08_153716_add_voting_tracking_columns_to_users_table',1),(71,'2025_09_13_210629_create_voter_slugs_table',1),(72,'2025_09_13_212143_add_step_columns_to_voter_slugs_table',1),(73,'2025_09_16_000001_add_unique_active_slug_constraint',1),(74,'2025_11_24_210521_fix_election_database_logs_table',1),(75,'2026_02_03_193521_add_wants_to_vote_flag_to_users_table',1),(76,'2026_02_03_193800_create_elections_table',1),(77,'2026_02_03_193900_create_voter_registrations_table',1),(78,'2026_02_03_194100_add_election_id_to_codes_table',1),(79,'2026_02_03_194200_add_election_id_to_votes_table',1),(80,'2026_02_03_194300_create_demo_votes_table',1),(81,'2026_02_03_194305_add_election_id_to_demo_votes_table',1),(82,'2026_02_03_194350_add_election_id_to_results_table',1),(83,'2026_02_03_194400_create_demo_results_table',1),(84,'2026_02_04_140930_create_demo_candidacies_table',1),(85,'2026_02_04_142310_add_election_id_to_voter_slugs_table',1),(86,'2026_02_04_143856_create_voter_slug_steps_table',1),(87,'2026_02_04_150544_add_slug_to_voter_slug_steps_table',1),(88,'2026_02_04_194403_add_verification_code_to_demo_votes_table',1),(89,'2026_02_06_132041_make_user_id_nullable_on_demo_votes_table',1),(90,'2026_02_07_131712_create_role_system_tables',1),(91,'2026_02_08_140213_add_position_order_to_posts_table',1),(92,'2026_02_08_141740_add_position_order_to_demo_candidacies_table',1),(93,'2026_02_08_141807_add_position_order_to_candidacies_table',1),(94,'2026_02_08_213248_add_election_id_to_posts_table',1),(95,'2026_02_08_213548_add_election_id_to_candidacies_table',1),(96,'2026_02_13_120000_add_organization_details_to_organizations_table',1),(97,'2026_02_13_130000_add_assigned_at_to_user_organization_roles',1),(98,'2026_02_15_202546_add_organisation_id_to_users_table',1),(99,'2026_02_19_185532_add_organisation_id_to_elections_table',1),(100,'2026_02_19_190927_add_organisation_id_to_posts_table',1),(101,'2026_02_19_190928_add_organisation_id_to_candidacies_table',1),(102,'2026_02_19_190930_add_organisation_id_to_codes_table',1),(103,'2026_02_19_190931_add_organisation_id_to_votes_table',1),(104,'2026_02_19_190933_add_organisation_id_to_results_table',1),(105,'2026_02_19_192311_add_organisation_id_to_voter_registrations_table',1),(106,'2026_02_19_192312_add_organisation_id_to_voter_slugs_table',1),(107,'2026_02_19_192313_add_organisation_id_to_voter_slug_steps_table',1),(108,'2026_02_19_192315_add_organisation_id_to_deligate_candidacies_table',1),(109,'2026_02_19_192316_add_organisation_id_to_deligate_posts_table',1),(110,'2026_02_19_192317_add_organisation_id_to_deligate_votes_table',1),(111,'2026_02_19_204554_add_organisation_id_to_demo_votes_table',1),(112,'2026_02_19_204602_add_organisation_id_to_demo_results_table',1),(113,'2026_02_20_000001_make_votes_organisation_id_not_null',1),(114,'2026_02_20_000002_make_results_organisation_id_not_null',1),(115,'2026_02_20_000003_add_composite_foreign_keys_to_voting_tables',1),(116,'2026_02_20_010507_add_organisation_id_to_demo_candidacies_table',1),(117,'2026_02_20_011053_create_demo_posts_table',1),(118,'2026_02_20_012741_create_demo_codes_table',1),(119,'2026_02_23_000245_add_unique_constraint_to_users_email_column',1),(120,'2026_02_23_110653_add_voter_optimization_indexes',1),(121,'2026_02_23_140000_update_role_enum_to_include_member',1),(122,'2026_02_25_000000_create_demo_voter_slugs_table',1),(123,'2026_02_25_000001_create_demo_voter_slug_steps_table',1),(124,'2026_02_25_140821_fix_demo_posts_composite_unique_constraint',1),(125,'2026_02_25_223424_remove_two_factor_from_users_table',1),(126,'2026_02_28_183851_update_organisation_id_defaults_to_zero',1),(127,'2026_02_28_184215_add_platform_organisation',1),(128,'2026_02_28_190310_add_status_field_to_elections_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notices`
--

LOCK TABLES `notices` WRITE;
/*!40000 ALTER TABLE `notices` DISABLE KEYS */;
/*!40000 ALTER TABLE `notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `openions`
--

DROP TABLE IF EXISTS `openions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `openions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_tag` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `openions_user_id_foreign` (`user_id`),
  CONSTRAINT `openions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `openions`
--

LOCK TABLES `openions` WRITE;
/*!40000 ALTER TABLE `openions` DISABLE KEYS */;
/*!40000 ALTER TABLE `openions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organisations`
--

DROP TABLE IF EXISTS `organisations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organisations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` json DEFAULT NULL,
  `representative` json DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('diaspora','ngo','professional','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `settings` json DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organisations_slug_unique` (`slug`),
  KEY `organisations_created_by_foreign` (`created_by`),
  CONSTRAINT `organisations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisations`
--

LOCK TABLES `organisations` WRITE;
/*!40000 ALTER TABLE `organisations` DISABLE KEYS */;
INSERT INTO `organisations` VALUES (1,'Platform',NULL,NULL,NULL,NULL,'platform','other',NULL,NULL,'2026-03-01 21:28:22','2026-03-01 21:28:22');
/*!40000 ALTER TABLE `organisations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outcomes`
--

DROP TABLE IF EXISTS `outcomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outcomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `committee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deligate_fee` double DEFAULT NULL,
  `membership_fee` double DEFAULT NULL,
  `sponser_fee` double DEFAULT NULL,
  `donation` double DEFAULT NULL,
  `salary` double DEFAULT NULL,
  `rent` double DEFAULT NULL,
  `software` double DEFAULT NULL,
  `communication` double DEFAULT NULL,
  `office_cost` double DEFAULT NULL,
  `postage` double DEFAULT NULL,
  `bank_charge` double DEFAULT NULL,
  `election_cost` double DEFAULT NULL,
  `equipment` double DEFAULT NULL,
  `vechicle` double DEFAULT NULL,
  `website` double DEFAULT NULL,
  `consulting_charge` double DEFAULT NULL,
  `training_charge` double DEFAULT NULL,
  `insurance_charge` double DEFAULT NULL,
  `guest_invitation` double DEFAULT NULL,
  `tax_charge` double DEFAULT NULL,
  `drink` double DEFAULT NULL,
  `food` double DEFAULT NULL,
  `event_cost` double DEFAULT NULL,
  `investment` double DEFAULT NULL,
  `other_expense` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outcomes`
--

LOCK TABLES `outcomes` WRITE;
/*!40000 ALTER TABLE `outcomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `outcomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
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
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nepali_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_national_wide` tinyint(1) NOT NULL DEFAULT '1',
  `state_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required_number` int NOT NULL,
  `position_order` int NOT NULL DEFAULT '0' COMMENT 'Order of posts in election ballot',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `election_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_post_id_unique` (`post_id`),
  KEY `posts_election_id_foreign` (`election_id`),
  KEY `posts_organisation_id_index` (`organisation_id`),
  CONSTRAINT `posts_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned NOT NULL,
  `election_id` bigint unsigned NOT NULL DEFAULT '1' COMMENT 'Reference to elections table - scopes results per election',
  `vote_id` bigint unsigned NOT NULL,
  `post_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidacy_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `results_election_id_index` (`election_id`),
  KEY `results_election_id_vote_id_index` (`election_id`,`vote_id`),
  KEY `results_organisation_id_index` (`organisation_id`),
  KEY `results_vote_id_organisation_id_foreign` (`vote_id`,`organisation_id`),
  CONSTRAINT `results_vote_id_organisation_id_foreign` FOREIGN KEY (`vote_id`, `organisation_id`) REFERENCES `votes` (`id`, `organisation_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` longtext COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms`
--

LOCK TABLES `sms` WRITE;
/*!40000 ALTER TABLE `sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `child_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `child_grade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `child_language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `birth_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `synchronizations`
--

DROP TABLE IF EXISTS `synchronizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `synchronizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `synchronizable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `synchronizable_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `last_synchronized_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `synchronizations_synchronizable_type_synchronizable_id_index` (`synchronizable_type`,`synchronizable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `synchronizations`
--

LOCK TABLES `synchronizations` WRITE;
/*!40000 ALTER TABLE `synchronizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `synchronizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_invitations`
--

DROP TABLE IF EXISTS `team_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_invitations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_invitations_team_id_email_unique` (`team_id`,`email`),
  CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_invitations`
--

LOCK TABLES `team_invitations` WRITE;
/*!40000 ALTER TABLE `team_invitations` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_invitations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_user`
--

LOCK TABLES `team_user` WRITE;
/*!40000 ALTER TABLE `team_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_organisation_roles`
--

DROP TABLE IF EXISTS `user_organisation_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_organisation_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `organisation_id` bigint unsigned NOT NULL,
  `role` enum('admin','member','staff','commission','voter') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `permissions` json DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_organisation_roles_user_id_organisation_id_role_unique` (`user_id`,`organisation_id`,`role`),
  KEY `user_organisation_roles_organisation_id_foreign` (`organisation_id`),
  CONSTRAINT `user_organisation_roles_organisation_id_foreign` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_organisation_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_organisation_roles`
--

LOCK TABLES `user_organisation_roles` WRITE;
/*!40000 ALTER TABLE `user_organisation_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_organisation_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voting_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_bg_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_icon_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `housenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postalcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nrna_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_voter` tinyint(1) NOT NULL DEFAULT '0',
  `wants_to_vote` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'User intends to participate in voting',
  `voter_registration_at` timestamp NULL DEFAULT NULL COMMENT 'When user first requested voter status',
  `name_prefex` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `can_vote` tinyint(1) NOT NULL DEFAULT '0',
  `approvedBy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspendedBy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `has_voted` tinyint(1) NOT NULL DEFAULT '0',
  `has_candidacy` tinyint(1) NOT NULL DEFAULT '0',
  `lcc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_committee_member` tinyint(1) NOT NULL DEFAULT '0',
  `committee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nrna_id_unique` (`nrna_id`),
  UNIQUE KEY `users_telephone_unique` (`telephone`),
  UNIQUE KEY `users_user_id_unique` (`user_id`),
  UNIQUE KEY `users_profile_icon_photo_path_unique` (`profile_icon_photo_path`),
  UNIQUE KEY `users_profile_bg_photo_path_unique` (`profile_bg_photo_path`),
  UNIQUE KEY `users_facebook_id_unique` (`facebook_id`),
  KEY `users_is_voter_can_vote_index` (`is_voter`,`can_vote`),
  KEY `idx_wants_voter` (`wants_to_vote`,`is_voter`),
  KEY `idx_org_voter` (`organisation_id`,`is_voter`),
  KEY `idx_search_fields` (`name`,`user_id`,`email`),
  KEY `idx_approved_by` (`approvedBy`),
  KEY `idx_has_voted` (`has_voted`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voter_registrations`
--

DROP TABLE IF EXISTS `voter_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voter_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL COMMENT 'Reference to users table',
  `election_id` bigint unsigned NOT NULL COMMENT 'Reference to elections table',
  `status` enum('pending','approved','rejected','voted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `election_type` enum('demo','real') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'demo' COMMENT 'Cached from elections table for performance',
  `registered_at` timestamp NULL DEFAULT NULL COMMENT 'When user registered to vote in this election',
  `approved_at` timestamp NULL DEFAULT NULL COMMENT 'When committee approved voter',
  `voted_at` timestamp NULL DEFAULT NULL COMMENT 'When voter submitted their vote',
  `approved_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name of committee member who approved',
  `rejected_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name of committee member who rejected',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason for rejection if rejected',
  `metadata` json DEFAULT NULL COMMENT 'Additional data like IP address, browser, etc.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voter_registrations_user_id_election_id_unique` (`user_id`,`election_id`),
  KEY `voter_registrations_user_id_election_type_index` (`user_id`,`election_type`),
  KEY `voter_registrations_election_id_status_index` (`election_id`,`status`),
  KEY `voter_registrations_election_type_status_index` (`election_type`,`status`),
  KEY `voter_registrations_status_index` (`status`),
  KEY `voter_registrations_created_at_index` (`created_at`),
  KEY `voter_registrations_organisation_id_index` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voter_registrations`
--

LOCK TABLES `voter_registrations` WRITE;
/*!40000 ALTER TABLE `voter_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `voter_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voter_slug_steps`
--

DROP TABLE IF EXISTS `voter_slug_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voter_slug_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `voter_slug_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `election_id` bigint unsigned NOT NULL,
  `step` tinyint unsigned NOT NULL,
  `step_data` json DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voter_slug_steps_voter_slug_id_election_id_step_unique` (`voter_slug_id`,`election_id`,`step`),
  KEY `voter_slug_steps_voter_slug_id_election_id_index` (`voter_slug_id`,`election_id`),
  KEY `voter_slug_steps_election_id_completed_at_index` (`election_id`,`completed_at`),
  KEY `voter_slug_steps_organisation_id_index` (`organisation_id`),
  CONSTRAINT `voter_slug_steps_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `voter_slug_steps_voter_slug_id_foreign` FOREIGN KEY (`voter_slug_id`) REFERENCES `voter_slugs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voter_slug_steps`
--

LOCK TABLES `voter_slug_steps` WRITE;
/*!40000 ALTER TABLE `voter_slug_steps` DISABLE KEYS */;
/*!40000 ALTER TABLE `voter_slug_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voter_slugs`
--

DROP TABLE IF EXISTS `voter_slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voter_slugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `current_step` tinyint unsigned NOT NULL DEFAULT '1',
  `step_meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `election_id` bigint unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `voter_slugs_slug_unique` (`slug`),
  KEY `voter_slugs_user_id_expires_at_is_active_index` (`user_id`,`expires_at`,`is_active`),
  KEY `voter_slugs_user_active_idx` (`user_id`,`is_active`),
  KEY `voter_slugs_election_id_index` (`election_id`),
  KEY `voter_slugs_organisation_id_index` (`organisation_id`),
  CONSTRAINT `voter_slugs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voter_slugs`
--

LOCK TABLES `voter_slugs` WRITE;
/*!40000 ALTER TABLE `voter_slugs` DISABLE KEYS */;
/*!40000 ALTER TABLE `voter_slugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint unsigned NOT NULL,
  `election_id` bigint unsigned NOT NULL DEFAULT '1' COMMENT 'Reference to elections table - scopes votes per election',
  `no_vote_option` tinyint(1) NOT NULL DEFAULT '0',
  `voting_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vote_session_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candidate_01` json DEFAULT NULL,
  `candidate_02` json DEFAULT NULL,
  `candidate_03` json DEFAULT NULL,
  `candidate_04` json DEFAULT NULL,
  `candidate_05` json DEFAULT NULL,
  `candidate_06` json DEFAULT NULL,
  `candidate_07` json DEFAULT NULL,
  `candidate_08` json DEFAULT NULL,
  `candidate_09` json DEFAULT NULL,
  `candidate_10` json DEFAULT NULL,
  `candidate_11` json DEFAULT NULL,
  `candidate_12` json DEFAULT NULL,
  `candidate_13` json DEFAULT NULL,
  `candidate_14` json DEFAULT NULL,
  `candidate_15` json DEFAULT NULL,
  `candidate_16` json DEFAULT NULL,
  `candidate_17` json DEFAULT NULL,
  `candidate_18` json DEFAULT NULL,
  `candidate_19` json DEFAULT NULL,
  `candidate_20` json DEFAULT NULL,
  `candidate_21` json DEFAULT NULL,
  `candidate_22` json DEFAULT NULL,
  `candidate_23` json DEFAULT NULL,
  `candidate_24` json DEFAULT NULL,
  `candidate_25` json DEFAULT NULL,
  `candidate_26` json DEFAULT NULL,
  `candidate_27` json DEFAULT NULL,
  `candidate_28` json DEFAULT NULL,
  `candidate_29` json DEFAULT NULL,
  `candidate_30` json DEFAULT NULL,
  `candidate_31` json DEFAULT NULL,
  `candidate_32` json DEFAULT NULL,
  `candidate_33` json DEFAULT NULL,
  `candidate_34` json DEFAULT NULL,
  `candidate_35` json DEFAULT NULL,
  `candidate_36` json DEFAULT NULL,
  `candidate_37` json DEFAULT NULL,
  `candidate_38` json DEFAULT NULL,
  `candidate_39` json DEFAULT NULL,
  `candidate_40` json DEFAULT NULL,
  `candidate_41` json DEFAULT NULL,
  `candidate_42` json DEFAULT NULL,
  `candidate_43` json DEFAULT NULL,
  `candidate_44` json DEFAULT NULL,
  `candidate_45` json DEFAULT NULL,
  `candidate_46` json DEFAULT NULL,
  `candidate_47` json DEFAULT NULL,
  `candidate_48` json DEFAULT NULL,
  `candidate_49` json DEFAULT NULL,
  `candidate_50` json DEFAULT NULL,
  `candidate_51` json DEFAULT NULL,
  `candidate_52` json DEFAULT NULL,
  `candidate_53` json DEFAULT NULL,
  `candidate_54` json DEFAULT NULL,
  `candidate_55` json DEFAULT NULL,
  `candidate_56` json DEFAULT NULL,
  `candidate_57` json DEFAULT NULL,
  `candidate_58` json DEFAULT NULL,
  `candidate_59` json DEFAULT NULL,
  `candidate_60` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `votes_id_organisation_id_unique` (`id`,`organisation_id`),
  KEY `votes_election_id_index` (`election_id`),
  KEY `votes_organisation_id_index` (`organisation_id`),
  KEY `votes_election_id_organisation_id_foreign` (`election_id`,`organisation_id`),
  CONSTRAINT `votes_election_id_organisation_id_foreign` FOREIGN KEY (`election_id`, `organisation_id`) REFERENCES `elections` (`id`, `organisation_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-01 23:36:09
