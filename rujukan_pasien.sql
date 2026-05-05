-- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: rujukan_pasien
-- ------------------------------------------------------
-- Server version	10.11.6-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `berkas_medis`
--

DROP TABLE IF EXISTS `berkas_medis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `berkas_medis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kunjungan_id` bigint(20) unsigned NOT NULL,
  `soap_id` bigint(20) unsigned DEFAULT NULL,
  `uploader_id` bigint(20) unsigned DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `berkas_medis_kunjungan_id_foreign` (`kunjungan_id`),
  KEY `berkas_medis_uploader_id_foreign` (`uploader_id`),
  KEY `berkas_medis_soap_id_foreign` (`soap_id`),
  CONSTRAINT `berkas_medis_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `berkas_medis_soap_id_foreign` FOREIGN KEY (`soap_id`) REFERENCES `soap` (`id`) ON DELETE SET NULL,
  CONSTRAINT `berkas_medis_uploader_id_foreign` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `berkas_medis`
--

LOCK TABLES `berkas_medis` WRITE;
/*!40000 ALTER TABLE `berkas_medis` DISABLE KEYS */;
INSERT INTO `berkas_medis` VALUES
(28,11,NULL,1,'USG','logo-rujukan.png','berkas/MqtLLPVclRmkzSDWzXHJunO5dKR4lGCAkyl2MuU9.png','2025-11-13 12:37:37','2025-11-13 12:37:37'),
(29,11,NULL,1,'LAB','Logo.jpeg','berkas/X5KzLen17mduwRpr9cydJIb9A4YHBVMJJdG6maYo.jpg','2025-11-13 12:37:37','2025-11-13 12:37:37'),
(30,14,35,15,'USG','mte.jpg','berkas/8OGwour513img4ZLcYnhgCyrDxGvq21KZf2fpppa.jpg','2026-01-20 15:18:16','2026-01-20 15:18:16'),
(31,16,36,8,'USG','Logo rs.jpg','berkas/BssU3S5NIZDKqk1Fp7hwhx1Wlc2MTQQk5pufweXQ.jpg','2026-01-21 07:02:52','2026-01-21 07:02:52'),
(32,17,NULL,1,NULL,'n2n.png','berkas/FCMQcBdDcTccotAhfez5y1k6BEhxJtw1OpzxSUDh.png','2026-04-12 01:27:24','2026-04-12 01:27:24'),
(33,22,38,1,'LAIN','ftrmh.jpeg','berkas/NPZ4aPoOV3v1lizirllQsYDfTuVVk0DvGQ6AUSPH.jpg','2026-04-28 08:43:50','2026-04-28 08:43:50');
/*!40000 ALTER TABLE `berkas_medis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('andalasfetolink-cache-diannoviyantidr@gmail.com|103.212.43.220','i:1;',1777966209),
('andalasfetolink-cache-diannoviyantidr@gmail.com|103.212.43.220:timer','i:1777966209;',1777966209);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES
(1,'default','{\"uuid\":\"751bc80f-40c7-4e66-bb87-3cbfdae90d40\",\"displayName\":\"App\\\\Notifications\\\\RujukanMasukNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\RujukanMasukNotification\\\":3:{s:7:\\\"rujukan\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Rujukan\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"pengirim\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"9b9e3893-1630-4466-94d6-4fd8335924b5\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761470268,\"delay\":null}',0,NULL,1761470268,1761470268),
(2,'default','{\"uuid\":\"288b326f-db4c-4789-983c-8110fd07132e\",\"displayName\":\"App\\\\Notifications\\\\RujukanMasukNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\RujukanMasukNotification\\\":3:{s:7:\\\"rujukan\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Rujukan\\\";s:2:\\\"id\\\";i:20;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"pengirim\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"35fc427a-6c81-4d71-8a1c-248bdea76c3c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761787702,\"delay\":null}',0,NULL,1761787702,1761787702);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konsultasi`
--

DROP TABLE IF EXISTS `konsultasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `konsultasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kunjungan_id` bigint(20) unsigned NOT NULL,
  `rumah_sakit_asal_id` bigint(20) unsigned NOT NULL,
  `rumah_sakit_tujuan_id` bigint(20) unsigned NOT NULL,
  `dokter_pengirim_id` bigint(20) unsigned NOT NULL,
  `dokter_tujuan_id` bigint(20) unsigned NOT NULL,
  `rujukan_id` bigint(20) unsigned DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `ringkasan_klinis` text DEFAULT NULL,
  `diagnosis_kerja` text DEFAULT NULL,
  `terapi_berjalan` text DEFAULT NULL,
  `hasil_penunjang` text DEFAULT NULL,
  `alasan_konsultasi` text NOT NULL,
  `pertanyaan_konsultasi` text DEFAULT NULL,
  `status` enum('draft','terkirim','dibaca','diterima','diskusi','butuh_info','dijawab','ditutup','dijadikan_rujukan') NOT NULL DEFAULT 'draft',
  `consent_status` enum('menunggu','diberikan','ditolak') NOT NULL DEFAULT 'menunggu',
  `consent_nama_pemberi` varchar(255) DEFAULT NULL,
  `consent_hubungan` varchar(255) DEFAULT NULL,
  `consent_metode` enum('lisan','tertulis','digital') DEFAULT NULL,
  `consent_diberikan_pada` datetime DEFAULT NULL,
  `consent_catatan` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `closed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konsultasi`
--

LOCK TABLES `konsultasi` WRITE;
/*!40000 ALTER TABLE `konsultasi` DISABLE KEYS */;
INSERT INTO `konsultasi` VALUES
(1,22,5,4,1,22,101,'nyeri dada akut','nyeri dada akut','nyeri dada akut','nyeri dada akut','nyeri dada akut','nyeri dada akut','nyeri dada akut','dijadikan_rujukan','diberikan','sabeni','pasien sendiri','lisan','2026-04-28 16:04:00','noting','2026-04-28 09:04:54','2026-04-28 09:05:17','2026-04-28 09:05:36',NULL,NULL,'2026-04-28 09:04:54','2026-04-28 09:07:05');
/*!40000 ALTER TABLE `konsultasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konsultasi_audit_logs`
--

DROP TABLE IF EXISTS `konsultasi_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `konsultasi_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `konsultasi_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konsultasi_audit_logs`
--

LOCK TABLES `konsultasi_audit_logs` WRITE;
/*!40000 ALTER TABLE `konsultasi_audit_logs` DISABLE KEYS */;
INSERT INTO `konsultasi_audit_logs` VALUES
(1,1,1,'dibuat','{\"status\":\"terkirim\",\"dokter_tujuan_id\":\"22\"}','2026-04-28 09:04:54','2026-04-28 09:04:54'),
(2,1,1,'dikirim','{\"consent_status\":\"diberikan\"}','2026-04-28 09:04:54','2026-04-28 09:04:54'),
(3,1,22,'dibaca',NULL,'2026-04-28 09:05:17','2026-04-28 09:05:17'),
(4,1,22,'diterima',NULL,'2026-04-28 09:05:36','2026-04-28 09:05:36'),
(5,1,22,'balas','{\"tipe\":\"pesan\",\"pesan_id\":1}','2026-04-28 09:05:51','2026-04-28 09:05:51'),
(6,1,1,'balas','{\"tipe\":\"pesan\",\"pesan_id\":2}','2026-04-28 09:06:26','2026-04-28 09:06:26'),
(7,1,22,'balas','{\"tipe\":\"pesan\",\"pesan_id\":3}','2026-04-28 09:06:51','2026-04-28 09:06:51'),
(8,1,1,'dijadikan_rujukan','{\"rujukan_id\":101}','2026-04-28 09:07:05','2026-04-28 09:07:05');
/*!40000 ALTER TABLE `konsultasi_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konsultasi_pesan`
--

DROP TABLE IF EXISTS `konsultasi_pesan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `konsultasi_pesan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `konsultasi_id` bigint(20) unsigned NOT NULL,
  `pengirim_id` bigint(20) unsigned NOT NULL,
  `tipe` enum('pesan','jawaban','minta_info') NOT NULL DEFAULT 'pesan',
  `pesan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konsultasi_pesan`
--

LOCK TABLES `konsultasi_pesan` WRITE;
/*!40000 ALTER TABLE `konsultasi_pesan` DISABLE KEYS */;
INSERT INTO `konsultasi_pesan` VALUES
(1,1,22,'pesan','kenapa pasien tersebut bisa jelaskan secara rinci','2026-04-28 09:05:51','2026-04-28 09:05:51'),
(2,1,1,'pesan','pasien tersebut sedang mengalami kejang setelah minum es','2026-04-28 09:06:26','2026-04-28 09:06:26'),
(3,1,22,'pesan','kemungkinan butuh dirujuk','2026-04-28 09:06:51','2026-04-28 09:06:51');
/*!40000 ALTER TABLE `konsultasi_pesan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kunjungan`
--

DROP TABLE IF EXISTS `kunjungan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kunjungan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_rawat` varchar(50) NOT NULL,
  `pasien_id` bigint(20) unsigned NOT NULL,
  `dokter_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `rumah_sakit_id` bigint(20) unsigned NOT NULL,
  `rajalranap` varchar(255) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `keluhan_utama` text DEFAULT NULL,
  `status_pulang` tinyint(1) NOT NULL DEFAULT 0,
  `waktu_pulang` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kunjungan_no_rawat_unique` (`no_rawat`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kunjungan`
--

LOCK TABLES `kunjungan` WRITE;
/*!40000 ALTER TABLE `kunjungan` DISABLE KEYS */;
INSERT INTO `kunjungan` VALUES
(6,'2025/11/05/00001',4,11,11,4,'Ponek','2025-11-05','2025-11-05 01:12:00','hamil darah tinggi',0,NULL,'2025-11-05 01:13:24','2025-11-05 01:13:24'),
(7,'2025/11/06/00001',5,11,11,4,'Ponek','2025-11-06','2025-11-06 05:11:00','Hamil dengan darah tinggi',0,NULL,'2025-11-06 03:12:27','2025-11-06 03:12:27'),
(11,'2025/11/13/00001',4,11,11,4,'Ponek','2025-11-13','2025-11-13 12:37:37',NULL,0,NULL,'2025-11-13 12:37:37','2025-11-13 12:37:37'),
(12,'2025/12/31/00001',7,14,15,3,'Ponek','2025-12-31','2025-12-31 02:28:00','hamil dengan keluar air-air',0,NULL,'2025-12-31 02:29:05','2025-12-31 02:29:05'),
(13,'2026/01/20/00001',6,11,11,4,'Ponek','2026-01-20','2026-01-20 15:06:00','Hamil dengan darah tinggi',0,NULL,'2026-01-20 15:06:51','2026-01-20 15:06:51'),
(14,'2026/01/20/00002',7,15,15,3,'Ponek','2026-01-20','2026-01-20 21:55:00','hamil dengan darah tinggi',0,NULL,'2026-01-20 15:17:09','2026-01-20 15:17:09'),
(15,'2026/01/21/00001',8,11,8,4,'ponek','2026-01-21','2026-01-21 04:18:00','darah tinggi',0,NULL,'2026-01-21 04:19:01','2026-01-21 04:19:01'),
(16,'2026/01/21/00002',9,11,8,4,'ponek','2026-01-21','2026-01-21 14:00:00','hamil dengan darah tinggi',0,NULL,'2026-01-21 06:59:29','2026-01-21 06:59:29'),
(17,'2026/04/12/00001',3,15,1,5,'rawat jalan','2026-04-12','2026-04-12 01:24:00','lorepsum',0,NULL,'2026-04-12 01:25:53','2026-04-12 01:25:53'),
(18,'2026/04/12/00002',9,43,43,6,'rawat jalan','2026-04-12','2026-04-12 01:40:00','mual muntah',0,NULL,'2026-04-12 01:41:14','2026-04-12 01:41:14'),
(19,'2026/04/14/00001',11,11,47,4,'rawat inap','2026-04-14','2026-04-14 09:58:00','nyeri kepala',0,NULL,'2026-04-14 10:01:03','2026-04-14 10:01:03'),
(20,'2026/04/19/00001',12,12,47,4,'rawat inap','2026-12-04','2026-12-04 09:28:00','gerak anak tidak dirasakan sejak 8 jam smrs',0,NULL,'2026-04-19 05:43:21','2026-04-19 05:43:21'),
(21,'2026/04/19/00002',12,12,47,4,'rawat inap','2026-04-17','2026-04-17 09:28:00','gerak anak tidak dirasakan sejak 12 jam smrs',0,NULL,'2026-04-19 05:48:01','2026-04-19 05:48:01'),
(22,'2026/04/28/00001',14,22,1,5,'Rawat Inap','2026-04-28','2026-04-28 08:42:00','nyeri dada',0,NULL,'2026-04-28 08:42:46','2026-04-28 08:42:46'),
(23,'2026/05/05/00001',15,15,15,3,'rawat jalan','2026-05-05','2026-05-05 07:19:00','panas samapai 37 derajat',0,NULL,'2026-05-05 07:20:34','2026-05-05 07:20:34'),
(24,'2026/05/05/00002',11,11,46,4,'rawat jalan','2026-05-05','2026-05-05 07:36:00','lorepsum',0,NULL,'2026-05-05 07:37:19','2026-05-05 07:37:19'),
(25,'2026/05/05/00003',12,46,46,4,'Rawat Jalan','2026-05-05','2026-05-05 07:56:00','lorepsum',0,NULL,'2026-05-05 07:57:11','2026-05-05 07:57:11'),
(26,'2026/05/05/00004',16,46,46,4,'rawat inap','2026-05-05','2026-05-05 08:08:00','sakit kepala',0,NULL,'2026-05-05 08:08:48','2026-05-05 08:08:48');
/*!40000 ALTER TABLE `kunjungan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_09_15_145824_create_rumah_sakit_table',1),
(5,'2025_09_15_145845_create_pasien_table',1),
(6,'2025_09_15_145902_create_kunjungan_table',1),
(7,'2025_09_15_145919_create_rujukan_table',1),
(8,'2025_09_15_145944_create_soap_table',1),
(9,'2025_09_15_161559_create_sessions_table',1),
(10,'2025_09_17_042339_update_kunjungan_table_add_fields',1),
(11,'2025_09_17_081150_add_fields_to_rujukan_table',1),
(12,'2025_09_17_081337_update_rujukan_table_add_fields',1),
(13,'2025_09_18_040948_update_no_rawat_in_kunjungan_table',1),
(14,'2025_09_18_044216_add_status_pulang_to_kunjungan_table',1),
(15,'2025_09_18_044735_add_waktu_pulang_to_kunjungan_table',1),
(16,'2025_09_18_080044_create_rumah_sakits_table',1),
(17,'2025_09_18_081743_add_penerima_to_rujukan_table',1),
(18,'2025_09_18_110209_create_berkas_medis_table',1),
(19,'2025_11_03_073321_add_avatar_path_to_users_table',2),
(20,'2025_11_11_033344_add_advice_to_soap_table',3),
(21,'2025_11_11_075458_add_vitals_to_soap',4),
(22,'2025_11_11_081154_add_soap_id_and_kategori_to_berkas_medis',5),
(23,'2025_11_13_024841_create_rujukan_dokter_cc_table',6),
(24,'0001_01_01_000003_create_password_reset_tokens_table',7),
(26,'2026_04_10_171100_create_konsultasi_tables',7),
(27,'2026_04_10_190000_create_notifications_table',7),
(28,'2026_04_10_171000_add_satusehat_fields_to_core_tables',8),
(29,'2026_04_28_120000_create_konsultasi_tables',8),
(30,'2026_04_28_120100_sync_rajalranap_in_kunjungan_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
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
INSERT INTO `notifications` VALUES
('2d7bcc14-473d-44ff-9b4a-d83a86f0cfb5','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',15,'{\"title\":\"Jawaban konsultasi masuk\",\"message\":\"dr. dr. Rina Gustuti, SpOG,Subs-KFM mengirim jawaban klinis untuk konsultasi KON-20260412-0001.\",\"category\":\"consultation_reply\",\"konsultasi_id\":1,\"no_konsultasi\":\"KON-20260412-0001\",\"status\":\"answered\",\"actor_name\":\"dr. Rina Gustuti, SpOG,Subs-KFM\",\"url\":\"https:\\/\\/andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-12 01:18:14','2026-04-12 01:17:53','2026-04-12 01:18:14'),
('2df838ba-5ab5-4f95-a81e-a65ba452edbd','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',22,'{\"domain\":\"konsultasi\",\"event_type\":\"pesan_baru\",\"konsultasi_id\":1,\"judul\":\"nyeri dada akut\",\"pasien\":\"wawdwa\",\"actor_name\":\"Admin RS Unand\",\"message\":\"Admin RS Unand mengirim pesan baru pada konsultasi nyeri dada akut.\",\"url\":\"https:\\/\\/www.andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-28 09:06:42','2026-04-28 09:06:26','2026-04-28 09:06:42'),
('4e9a75ba-5a75-4d36-9fc7-d4e92696aa86','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',1,'{\"domain\":\"konsultasi\",\"event_type\":\"pesan_baru\",\"konsultasi_id\":1,\"judul\":\"nyeri dada akut\",\"pasien\":\"wawdwa\",\"actor_name\":\"Dokter Jaga\",\"message\":\"Dokter Jaga mengirim pesan baru pada konsultasi nyeri dada akut.\",\"url\":\"https:\\/\\/www.andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-28 09:07:03','2026-04-28 09:06:51','2026-04-28 09:07:03'),
('6e5ad9c9-e0bb-485f-a516-d1df4b2332a5','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',22,'{\"domain\":\"konsultasi\",\"event_type\":\"konsultasi_baru\",\"konsultasi_id\":1,\"judul\":\"nyeri dada akut\",\"pasien\":\"wawdwa\",\"actor_name\":\"Admin RS Unand\",\"message\":\"Admin RS Unand mengirim konsultasi baru untuk pasien wawdwa.\",\"url\":\"https:\\/\\/www.andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-28 09:05:17','2026-04-28 09:04:54','2026-04-28 09:05:17'),
('80158e2c-581f-49ff-afb4-6feeddee6652','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',38,'{\"title\":\"Konsultasi baru masuk\",\"message\":\"Konsultasi KON-20260412-0001 dari dr. dr. Aswin Boy,SpOG (Trainee) menunggu tindak lanjut Anda.\",\"category\":\"new_consultation\",\"konsultasi_id\":1,\"no_konsultasi\":\"KON-20260412-0001\",\"status\":\"submitted\",\"actor_name\":\"dr. Aswin Boy,SpOG (Trainee)\",\"url\":\"https:\\/\\/andalasfetolink.com\\/konsultasi\\/1\"}',NULL,'2026-04-12 01:13:15','2026-04-12 01:13:15'),
('90e5c19a-6265-442f-afe3-c2a76df3a7ac','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',15,'{\"title\":\"Konsultasi diterima\",\"message\":\"Konsultasi KON-20260412-0001 telah diterima oleh dr. dr. Rina Gustuti, SpOG,Subs-KFM.\",\"category\":\"consultation_accepted\",\"konsultasi_id\":1,\"no_konsultasi\":\"KON-20260412-0001\",\"status\":\"accepted\",\"actor_name\":\"dr. Rina Gustuti, SpOG,Subs-KFM\",\"url\":\"https:\\/\\/andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-12 01:18:14','2026-04-12 01:17:39','2026-04-12 01:18:14'),
('c209ca39-63bc-4959-aac6-5bf186d8fc40','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',1,'{\"domain\":\"konsultasi\",\"event_type\":\"pesan_baru\",\"konsultasi_id\":1,\"judul\":\"nyeri dada akut\",\"pasien\":\"wawdwa\",\"actor_name\":\"Dokter Jaga\",\"message\":\"Dokter Jaga mengirim pesan baru pada konsultasi nyeri dada akut.\",\"url\":\"https:\\/\\/www.andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-28 09:06:09','2026-04-28 09:05:51','2026-04-28 09:06:09'),
('c46f0709-a744-43f8-b0d7-d20c6c1d7ef9','App\\Notifications\\KonsultasiActivityNotification','App\\Models\\User',10,'{\"title\":\"Konsultasi perlu ditinjau\",\"message\":\"Konsultasi KON-20260412-0001 telah dikirim atau dialihkan kepada Anda.\",\"category\":\"consultation_submitted\",\"konsultasi_id\":1,\"no_konsultasi\":\"KON-20260412-0001\",\"status\":\"submitted\",\"actor_name\":\"dr. Aswin Boy,SpOG (Trainee)\",\"url\":\"https:\\/\\/andalasfetolink.com\\/konsultasi\\/1\"}','2026-04-12 01:17:26','2026-04-12 01:16:43','2026-04-12 01:17:26');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasien`
--

DROP TABLE IF EXISTS `pasien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pasien` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_rkm_medis` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `patient_ihs_number` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pasien_no_rkm_medis_unique` (`no_rkm_medis`),
  UNIQUE KEY `pasien_nik_unique` (`nik`),
  KEY `pasien_patient_ihs_number_index` (`patient_ihs_number`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien`
--

LOCK TABLES `pasien` WRITE;
/*!40000 ALTER TABLE `pasien` DISABLE KEYS */;
INSERT INTO `pasien` VALUES
(1,'000001','180310890017340812',NULL,'sabeni','2025-10-25','bandung','bandung','L','0887393121432','2025-10-25 02:21:22','2025-10-25 02:21:22'),
(2,'000002','1234567891',NULL,'Ny. A','1999-10-30','Pariaman','Dharmasraya','P','00000””','2025-10-29 17:27:05','2025-10-29 17:27:05'),
(3,'000003','18031017000578',NULL,'Nafasya','2023-09-01','kotabumi','Padang','P','0811000345','2025-11-04 05:36:17','2025-11-04 05:36:17'),
(4,'000004','99999',NULL,'ny. x','2009-02-03','dhrmsry','jl. mmmmmmmmmmm','P','098766','2025-11-05 01:09:40','2025-11-05 01:09:40'),
(5,'000005','888888',NULL,'Ny. Apo','2010-01-06','Dharmasraya','Dharmasraya','P','01234','2025-11-06 03:11:40','2025-11-06 03:11:40'),
(6,'000006','18080',NULL,'aswin','2014-02-06','dharmasra','jln....','P','0000','2025-11-06 04:13:50','2025-11-06 04:13:50'),
(7,'000007','1808012001870003',NULL,'Aswin Boy Pratama','1987-01-20','Way Kanan','Way  Kanan','L','08888','2025-12-31 02:27:03','2025-12-31 02:27:03'),
(8,'000008','19800000',NULL,'gada','1987-01-20','daharmasraya','jjjjj','P','8888888888888','2026-01-21 04:18:04','2026-01-21 04:18:04'),
(9,'000009','1900000',NULL,'susan','1987-01-20','dharmasraya','jl. lintas sumatera','P','0821','2026-01-21 06:58:00','2026-01-21 06:58:00'),
(10,'000010','123456789',NULL,'sabeni','1996-12-30','lampung','Jl Kelapa 7','L','00000000009999999999','2026-04-10 10:22:33','2026-04-10 10:22:33'),
(11,'000011','123',NULL,'ibu lala','2003-01-20','kota','darmasraya','P','12345','2026-04-14 09:54:48','2026-04-14 09:54:48'),
(12,'000012','0011',NULL,'Leni Marlina','1984-02-22','dharmasraya','dharmasraya','P','081313455676','2026-04-19 05:37:19','2026-04-19 05:37:19'),
(13,'000013','1310114705850002',NULL,'Siti rukayah','1985-05-07','Ngawi','Koto Besar,','P','081275041161','2026-04-20 07:33:22','2026-04-20 07:33:22'),
(14,'000014','1122307482324',NULL,'wawdwa','2005-02-01','lampung','wadwda','L','24234242','2026-04-28 07:26:18','2026-04-28 07:26:18'),
(15,'000015','1803101111',NULL,'adi triono','2000-05-18','kotabumi','alamat saja','L','08130000','2026-05-05 07:18:59','2026-05-05 07:18:59'),
(16,'000016','123456',NULL,'Siti rukayah','2010-07-05','Batu Raja','ujun gurun','P','0878968973452','2026-05-05 08:07:02','2026-05-05 08:07:02');
/*!40000 ALTER TABLE `pasien` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `rujukan`
--

DROP TABLE IF EXISTS `rujukan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rujukan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kunjungan_id` bigint(20) unsigned NOT NULL,
  `origin_konsultasi_id` bigint(20) unsigned DEFAULT NULL,
  `rumah_sakit_asal_id` bigint(20) unsigned NOT NULL,
  `rumah_sakit_tujuan_id` bigint(20) unsigned NOT NULL,
  `alasan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `dokter_tujuan_id` bigint(20) unsigned NOT NULL,
  `alasan_rujukan` text NOT NULL,
  `status` enum('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_penerima` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `penerima_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rujukan_penerima_id_foreign` (`penerima_id`),
  KEY `rujukan_origin_konsultasi_id_index` (`origin_konsultasi_id`),
  CONSTRAINT `rujukan_penerima_id_foreign` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rujukan`
--

LOCK TABLES `rujukan` WRITE;
/*!40000 ALTER TABLE `rujukan` DISABLE KEYS */;
INSERT INTO `rujukan` VALUES
(26,12,NULL,3,4,'Preeklampsia berat','Membutuhkan intervensi lanjutan',11,'Penurunan kesadaran','diterima',NULL,'2025-11-01 03:44:04','2026-01-17 08:08:03',11),
(33,5,NULL,5,4,'test coba pasien',NULL,11,'test coba pasien','diterima',NULL,'2025-11-04 06:18:25','2026-01-20 02:47:04',8),
(50,11,NULL,4,5,'test','test',38,'test','menunggu',NULL,'2026-01-20 02:51:48','2026-01-20 02:51:48',NULL),
(51,13,NULL,4,3,'Ruangan Penuh','000',14,'sesak  nafas','menunggu',NULL,'2026-01-20 15:10:53','2026-01-20 15:10:53',NULL),
(52,13,NULL,4,3,'Ruangan Penuh','wwww',16,'ruangan penuh dan butuh icu','menunggu',NULL,'2026-01-20 15:12:59','2026-01-20 15:12:59',NULL),
(53,14,NULL,3,5,'sesak berat','kesadaran menurun',38,'membuthkan ICU segera','menunggu',NULL,'2026-01-20 15:19:15','2026-01-20 15:19:15',NULL),
(54,14,NULL,3,4,'sesak berat','kesadaran menurun',11,'membuthkan ICU segera','menunggu',NULL,'2026-01-20 15:21:12','2026-01-20 15:21:12',NULL),
(55,13,NULL,3,4,'Ss','Sss',12,'Ss','menunggu',NULL,'2026-01-20 15:22:26','2026-01-20 15:22:26',NULL),
(56,13,NULL,4,3,'wwwwwwww','wwwwwwwww',15,'wwwwwwwww','menunggu',NULL,'2026-01-20 15:26:59','2026-01-20 15:26:59',NULL),
(57,13,NULL,4,3,'wwww','qqqq',15,'rrrr','menunggu',NULL,'2026-01-20 15:28:40','2026-01-20 15:28:40',NULL),
(58,14,NULL,4,3,'yyyy','yyyy',15,'yyyy','menunggu',NULL,'2026-01-20 15:36:16','2026-01-20 15:36:16',NULL),
(59,13,NULL,4,3,'sesak berat','sesak dan penurunan kesadaran',15,'butuhh icu','menunggu',NULL,'2026-01-20 15:41:35','2026-01-20 15:41:35',NULL),
(60,14,NULL,3,5,'test','test',38,'test','menunggu',NULL,'2026-01-20 15:44:01','2026-01-20 15:44:01',NULL),
(61,14,NULL,3,5,'test','test',38,'test','menunggu',NULL,'2026-01-20 15:50:58','2026-01-20 15:50:58',NULL),
(62,11,NULL,5,4,'tidak ada alat operasi','-',22,'tidak ada alat operasi','menunggu',NULL,'2026-01-20 15:56:35','2026-01-20 15:56:35',NULL),
(63,13,NULL,4,3,'sesak berat','yyyy',15,'ggg','menunggu',NULL,'2026-01-20 16:15:04','2026-01-20 16:15:04',NULL),
(64,7,NULL,5,4,'22','22',41,'22','menunggu',NULL,'2026-01-20 16:25:51','2026-01-20 16:25:51',NULL),
(65,13,NULL,3,5,'Sesak','Terpasang oxygen',38,'Butuh icu','menunggu',NULL,'2026-01-20 16:31:55','2026-01-20 16:31:55',NULL),
(66,13,NULL,3,5,'Sesak','Terpasang oxygen',38,'Butuh icu','menunggu',NULL,'2026-01-20 16:31:57','2026-01-20 16:31:57',NULL),
(67,13,NULL,3,5,'Sesak','Terpasang oxygen',38,'Butuh icu','menunggu',NULL,'2026-01-20 16:32:03','2026-01-20 16:32:03',NULL),
(68,13,NULL,3,5,'Sesak','Aaaaa',38,'Butuh icu','menunggu',NULL,'2026-01-20 16:40:17','2026-01-20 16:40:17',NULL),
(69,13,NULL,3,5,'Sesak','Aaaaa',38,'Butuh icu','menunggu',NULL,'2026-01-20 16:40:52','2026-01-20 16:40:52',NULL),
(70,13,NULL,5,4,'33','33',41,'33','menunggu',NULL,'2026-01-20 16:50:10','2026-01-20 16:50:10',NULL),
(71,14,NULL,5,4,'44','44',22,'44','menunggu',NULL,'2026-01-20 17:00:03','2026-01-20 17:00:03',NULL),
(72,13,NULL,3,5,'Hhh','Jjj',38,'Jjj','menunggu',NULL,'2026-01-20 17:01:25','2026-01-20 17:01:25',NULL),
(73,14,NULL,5,4,'44','44',22,'44','menunggu',NULL,'2026-01-20 17:03:55','2026-01-20 17:03:55',NULL),
(74,14,NULL,5,4,'44','44',22,'44','menunggu',NULL,'2026-01-20 17:09:40','2026-01-20 17:09:40',NULL),
(75,13,NULL,3,4,'Tt','Tt',11,'Uuu','menunggu',NULL,'2026-01-20 18:08:38','2026-01-20 18:08:38',NULL),
(76,13,NULL,3,4,'Tt','Tt',11,'Uuu','menunggu',NULL,'2026-01-20 18:08:42','2026-01-20 18:08:42',NULL),
(77,13,NULL,4,3,'Yy','Iii',15,'Yyy','diterima',NULL,'2026-01-20 18:10:45','2026-01-20 18:34:13',15),
(78,11,NULL,4,3,'qqq','qwe',15,'qqq','menunggu',NULL,'2026-01-20 18:35:53','2026-01-20 18:35:53',NULL),
(79,13,NULL,5,3,'chan coba test mailio','mailio',15,'mailio','menunggu',NULL,'2026-01-20 18:44:52','2026-01-20 18:44:52',NULL),
(80,11,NULL,4,3,'qqq','qwe',15,'qqq','menunggu',NULL,'2026-01-20 18:50:09','2026-01-20 18:50:09',NULL),
(81,11,NULL,4,3,'qqq','qwe',15,'qqq','menunggu',NULL,'2026-01-20 18:50:52','2026-01-20 18:50:52',NULL),
(82,7,NULL,4,3,'qqq','eeee',15,'wwww','menunggu',NULL,'2026-01-20 18:56:46','2026-01-20 18:56:46',NULL),
(83,13,NULL,4,3,'yyyy','yyy',15,'yyy','menunggu',NULL,'2026-01-20 19:01:30','2026-01-20 19:01:30',NULL),
(84,7,NULL,4,3,'PEB dengan severe features','eee',15,'icu','menunggu',NULL,'2026-01-20 19:10:17','2026-01-20 19:10:17',NULL),
(85,13,NULL,5,4,'55','55',22,'55','menunggu',NULL,'2026-01-20 19:10:43','2026-01-20 19:10:43',NULL),
(86,7,NULL,4,3,'PEB dengan severe features','ffff',15,'ddd','menunggu',NULL,'2026-01-20 19:11:17','2026-01-20 19:11:17',NULL),
(87,12,NULL,5,4,'66','66',41,'66','menunggu',NULL,'2026-01-20 19:11:37','2026-01-20 19:11:37',NULL),
(88,7,NULL,4,3,'Peb','Butuh icu',15,'Peb severe features','menunggu',NULL,'2026-01-20 19:24:46','2026-01-20 19:24:46',NULL),
(89,13,NULL,4,3,'Aa','Cc',15,'Bb','menunggu',NULL,'2026-01-20 19:54:39','2026-01-20 19:54:39',NULL),
(90,13,NULL,4,3,'Aa','Cc',21,'Bb','menunggu',NULL,'2026-01-20 19:55:45','2026-01-20 19:55:45',NULL),
(91,13,NULL,4,3,'Aa','Cc',20,'Bb','menunggu',NULL,'2026-01-20 19:58:39','2026-01-20 19:58:39',NULL),
(92,13,NULL,4,3,'Aa','Cc',20,'Bb','menunggu',NULL,'2026-01-20 19:59:11','2026-01-20 19:59:11',NULL),
(93,14,NULL,4,3,'Aa','Cc',21,'Gg','diterima',NULL,'2026-01-20 20:13:13','2026-01-20 20:33:28',18),
(94,13,NULL,4,3,'Sa','Re',21,'Dd','menunggu',NULL,'2026-01-20 20:41:37','2026-01-20 20:41:37',NULL),
(95,6,NULL,4,3,'A','S',15,'B','menunggu',NULL,'2026-01-21 01:11:15','2026-01-21 01:11:15',NULL),
(96,7,NULL,4,3,'b',',',15,'n','menunggu',NULL,'2026-01-21 02:45:43','2026-01-21 02:45:43',NULL),
(97,16,NULL,4,3,'aa','cc',15,'bb','diterima',NULL,'2026-01-21 07:07:17','2026-01-21 07:30:31',15),
(98,18,NULL,6,3,'lorepsum','lorepsum',15,'lorepsum','menunggu',NULL,'2026-04-12 01:44:38','2026-04-12 01:44:38',NULL),
(99,19,NULL,4,3,'testing','terimakasih Prof, Dokter,',13,'tes pengisian','menunggu',NULL,'2026-04-14 10:07:47','2026-04-14 10:07:47',NULL),
(100,22,NULL,5,3,'ww','ww',23,'ww','menunggu',NULL,'2026-04-28 08:44:20','2026-04-28 08:44:20',NULL),
(101,22,NULL,5,4,'nyeri dada akut','Pertanyaan konsultasi: nyeri dada akut\n\nRingkasan klinis: nyeri dada akut',22,'nyeri dada akut','menunggu',NULL,'2026-04-28 09:07:05','2026-04-28 09:07:05',NULL),
(102,24,NULL,4,3,'lorepsum','lorepsum',18,'lorepsum','diterima',NULL,'2026-05-05 07:38:43','2026-05-05 07:47:29',18),
(103,26,NULL,4,3,'fasilitas terbatas','-',15,'tidak tersedia ventilator','menunggu',NULL,'2026-05-05 08:12:58','2026-05-05 08:12:58',NULL);
/*!40000 ALTER TABLE `rujukan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rujukan_dokter_cc`
--

DROP TABLE IF EXISTS `rujukan_dokter_cc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rujukan_dokter_cc` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rujukan_id` bigint(20) unsigned NOT NULL,
  `dokter_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rujukan_dokter_cc_rujukan_id_dokter_id_unique` (`rujukan_id`,`dokter_id`),
  KEY `rujukan_dokter_cc_dokter_id_foreign` (`dokter_id`),
  CONSTRAINT `rujukan_dokter_cc_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rujukan_dokter_cc_rujukan_id_foreign` FOREIGN KEY (`rujukan_id`) REFERENCES `rujukan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rujukan_dokter_cc`
--

LOCK TABLES `rujukan_dokter_cc` WRITE;
/*!40000 ALTER TABLE `rujukan_dokter_cc` DISABLE KEYS */;
INSERT INTO `rujukan_dokter_cc` VALUES
(7,50,10,'2026-01-20 02:52:30','2026-01-20 02:52:30'),
(8,52,15,'2026-01-20 15:12:59','2026-01-20 15:12:59'),
(9,53,37,'2026-01-20 15:19:15','2026-01-20 15:19:15'),
(10,56,18,'2026-01-20 15:26:59','2026-01-20 15:26:59'),
(11,57,20,'2026-01-20 15:28:40','2026-01-20 15:28:40'),
(12,58,20,'2026-01-20 15:36:16','2026-01-20 15:36:16'),
(13,59,20,'2026-01-20 15:41:35','2026-01-20 15:41:35'),
(14,60,37,'2026-01-20 15:44:01','2026-01-20 15:44:01'),
(15,61,37,'2026-01-20 15:50:58','2026-01-20 15:50:58'),
(16,62,40,'2026-01-20 15:56:35','2026-01-20 15:56:35'),
(17,62,41,'2026-01-20 15:56:35','2026-01-20 15:56:35'),
(18,63,20,'2026-01-20 16:15:04','2026-01-20 16:15:04'),
(19,64,40,'2026-01-20 16:25:51','2026-01-20 16:25:51'),
(20,65,24,'2026-01-20 16:31:55','2026-01-20 16:31:55'),
(21,65,37,'2026-01-20 16:31:55','2026-01-20 16:31:55'),
(22,66,24,'2026-01-20 16:31:57','2026-01-20 16:31:57'),
(23,66,37,'2026-01-20 16:31:57','2026-01-20 16:31:57'),
(24,67,24,'2026-01-20 16:32:03','2026-01-20 16:32:03'),
(25,67,37,'2026-01-20 16:32:03','2026-01-20 16:32:03'),
(26,68,37,'2026-01-20 16:40:17','2026-01-20 16:40:17'),
(27,69,37,'2026-01-20 16:40:52','2026-01-20 16:40:52'),
(28,70,22,'2026-01-20 16:50:10','2026-01-20 16:50:10'),
(29,70,40,'2026-01-20 16:50:10','2026-01-20 16:50:10'),
(30,71,40,'2026-01-20 17:00:03','2026-01-20 17:00:03'),
(31,71,41,'2026-01-20 17:00:03','2026-01-20 17:00:03'),
(32,72,37,'2026-01-20 17:01:25','2026-01-20 17:01:25'),
(33,72,10,'2026-01-20 17:01:25','2026-01-20 17:01:25'),
(34,73,40,'2026-01-20 17:03:55','2026-01-20 17:03:55'),
(35,73,41,'2026-01-20 17:03:55','2026-01-20 17:03:55'),
(36,74,40,'2026-01-20 17:09:40','2026-01-20 17:09:40'),
(37,74,41,'2026-01-20 17:09:40','2026-01-20 17:09:40'),
(38,75,40,'2026-01-20 18:08:38','2026-01-20 18:08:38'),
(39,76,40,'2026-01-20 18:08:42','2026-01-20 18:08:42'),
(40,77,20,'2026-01-20 18:10:45','2026-01-20 18:10:45'),
(41,78,20,'2026-01-20 18:35:53','2026-01-20 18:35:53'),
(42,79,20,'2026-01-20 18:44:52','2026-01-20 18:44:52'),
(43,80,18,'2026-01-20 18:50:09','2026-01-20 18:50:09'),
(44,81,20,'2026-01-20 18:50:52','2026-01-20 18:50:52'),
(45,82,20,'2026-01-20 18:56:46','2026-01-20 18:56:46'),
(46,83,20,'2026-01-20 19:01:30','2026-01-20 19:01:30'),
(47,84,18,'2026-01-20 19:10:17','2026-01-20 19:10:17'),
(48,85,40,'2026-01-20 19:10:43','2026-01-20 19:10:43'),
(49,85,41,'2026-01-20 19:10:43','2026-01-20 19:10:43'),
(50,86,20,'2026-01-20 19:11:17','2026-01-20 19:11:17'),
(51,87,22,'2026-01-20 19:11:37','2026-01-20 19:11:37'),
(52,87,40,'2026-01-20 19:11:37','2026-01-20 19:11:37'),
(53,88,20,'2026-01-20 19:24:46','2026-01-20 19:24:46'),
(54,89,20,'2026-01-20 19:54:39','2026-01-20 19:54:39'),
(55,90,18,'2026-01-20 19:55:45','2026-01-20 19:55:45'),
(56,91,19,'2026-01-20 19:58:39','2026-01-20 19:58:39'),
(57,92,19,'2026-01-20 19:59:11','2026-01-20 19:59:11'),
(60,94,18,'2026-01-20 20:41:37','2026-01-20 20:41:37'),
(61,95,20,'2026-01-21 01:11:15','2026-01-21 01:11:15'),
(62,96,20,'2026-01-21 02:45:43','2026-01-21 02:45:43'),
(63,96,14,'2026-01-21 02:45:43','2026-01-21 02:45:43'),
(64,97,20,'2026-01-21 07:07:17','2026-01-21 07:07:17'),
(65,97,18,'2026-01-21 07:07:17','2026-01-21 07:07:17'),
(66,99,14,'2026-04-14 10:07:47','2026-04-14 10:07:47');
/*!40000 ALTER TABLE `rujukan_dokter_cc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rumah_sakit`
--

DROP TABLE IF EXISTS `rumah_sakit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rumah_sakit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rumah_sakit`
--

LOCK TABLES `rumah_sakit` WRITE;
/*!40000 ALTER TABLE `rumah_sakit` DISABLE KEYS */;
INSERT INTO `rumah_sakit` VALUES
(3,'RSUP Dr. M. Djamil Padang','Jl. Perintis Kemerdekaan, Sawahan Tim., Kec. Padang Tim., Kota Padang, Sumatera Barat 25129','07518956666','2025-10-26 01:32:31','2025-10-26 01:36:44'),
(4,'RSUD Sungai Dareh','Jl. Lintas Sumatera No.KM.2, Empat Koto, Kec. Pulau Punjung, Kabupaten Dharmasraya, Sumatera Barat 27614','075440347','2025-10-26 01:36:34','2025-10-26 01:36:34'),
(5,'Rumah Sakit Universitas Andalas','Universitas Andalas, Komplek Kampus Jl. Limau Manis, Limau Manis, Kec. Pauh, Kota Padang, Sumatera Barat 25176','07518465000','2025-10-26 01:37:29','2025-10-26 01:37:29'),
(6,'Rumah Sakit Haji Kamino Lampung','jl.baradatu, waykanan Lampung','085768000','2026-04-12 01:35:02','2026-04-12 01:35:02');
/*!40000 ALTER TABLE `rumah_sakit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rumah_sakits`
--

DROP TABLE IF EXISTS `rumah_sakits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rumah_sakits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rumah_sakits`
--

LOCK TABLES `rumah_sakits` WRITE;
/*!40000 ALTER TABLE `rumah_sakits` DISABLE KEYS */;
/*!40000 ALTER TABLE `rumah_sakits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES
('1KnxQMAOyFIleuMpJLQaW8O2dSqsNyxR44L1BxqT',15,'110.137.36.138','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Safari/605.1.15','YTo1OntzOjY6Il90b2tlbiI7czo0MDoid0o2azFROGFBME1tYkxMRFI3VE55T1FWOU05cVhtaDlEOFY5cFp2USI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2FuZGFsYXNmZXRvbGluay5jb20va29uc3VsdGFzaSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE1O30=',1777983288),
('3Y21FDwe0aGjwsI2FTQiHso0Zosyyw9o5ndhrtUo',NULL,'93.123.109.163','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYTFmbGs4S3RwZ1VtN3hiUVFLQ1VKdmRtb1FkaHNXQWVDb2puSjNmUCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cHM6Ly9hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777976532),
('4MRdzK44sEUsLhc4mTuVIwZzH7awh5tazhABghsF',15,'110.137.136.170','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Safari/605.1.15','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUmZpYklSOWsyWVc1YlpVY2ZtVTR4ZE91bGxKWXJlakkwYlhQVDVEOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjg2OiJodHRwczovL2FuZGFsYXNmZXRvbGluay5jb20va3VuanVuZ2FuP2VuZF9kYXRlPTIwMjYtMDUtMDUmcGFnZT0xJnN0YXJ0X2RhdGU9MjAyMi0wNS0wNSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE1O30=',1777969389),
('4QENvfIkZ3YlmgCil6gcwsLV4KTOtijr6a2hSuOo',NULL,'216.226.76.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV0pWR3dmUFZHdGlQbm9lWjF6cTltR2ViaDJZeVlaaXZ5Qkd2RHdwRSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cHM6Ly93d3cuYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL3d3dy5hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1777970806),
('6jRFOKeWSigUYX76BF8cV7IjTZKacuA0Cm6HDY5O',NULL,'170.106.197.109','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiekFmR2duVEh5YTFJb0FCcmZoOTFlSTZsZFloTWp5bGV2TnNxdjFKTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovL3d3dy5hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly93d3cuYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777976759),
('ASAi4HUILU1XKFj1stLmj1rYk7OlQVlcJqhklS9x',NULL,'91.98.178.81','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEpYTjRuRzQyTTJxb21sclVnNDFnOTZ1cnNQRzRiS012OWVDRXYwWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777970099),
('BtN75Z73BcTuy2cKu83dOTP9SQAR7oceprezW4R3',NULL,'176.65.139.168','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM0NTNlFuekFBQVFvN1RRekJsbVlGa1p3WFNJeWt0dGFGWWgyUTlFTSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cHM6Ly9hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777966067),
('cRMNDWTFrtsJjpQixl5XYRCE3GOqq2aIdzkZ7lQt',NULL,'192.227.164.101','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQlNwN201Q3c4Z3JSemp5QVk2dUM1UHpHV2RkaG93TmxxTVBZcEROSiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777961851),
('cXgWUr3eoQj54EOYIHnZ2KVGzfvP3Iu7T9FbLasz',NULL,'145.239.65.175','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiakloSWppbkFLdVJGS3BjblhMdGxLRnoyMUxmd2JhRnNKVk5HcVJ6dCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cHM6Ly9hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777962046),
('dc7LnFLzVnMvnLnho0hej1TQPKObExTA93zQldYY',NULL,'170.106.197.109','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0pManEyUmZSTjFsME9ORjVWWklROXpkclRWdW5CWkVNZWpBcjFvSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly93d3cuYW5kYWxhc2ZldG9saW5rLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777976760),
('Ejy4yyJQJwY8rYCSw67aYMdIEJ7yxTbSby7rUVI8',18,'103.212.43.220','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZTNYTjJiSHUweFNqMFpBZVl3N1VBQnZOUTFTUUozTEQxbnY5Ykl4VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQxOiJodHRwczovL2FuZGFsYXNmZXRvbGluay5jb20vcGFzaWVuP3BhZ2U9MiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE4O30=',1777969367),
('ep373l1rDBD9zIWGFOQkauSlJy1g3N4yLQ3Eg0EC',NULL,'43.164.190.28','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaGtoRFRjN3BGRHhaUDNDVUd2cVpIY0ZZYzdSNjVFZlI5YWhwWE16ViI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovL3d3dy5hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly93d3cuYW5kYWxhc2ZldG9saW5rLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777993183),
('fFVmcDTxdoFuY2dB333zG2lFCinj9MpiRhUu9W6J',NULL,'35.221.183.156','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4240.193 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRWpueDJEQUFwQXdCanY0UVB5VmVlWkkyZTNCZlJkYmpLamFPSDJJMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777969589),
('Fg4SaC62763KcJTvYHdEo0fSq9k5YCIQZ63Ju7yw',NULL,'101.33.55.204','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT2dQWVdjWXFmdG1PckV5SlRFVnpDRzMwNGFRcm00Z3dxRURyRXN2ZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1778002148),
('FqW5i9O7zLXWXDeGLHanafzJCZt2V0mGNFwwuoUE',NULL,'57.141.2.45','meta-externalagent/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFliUVF6VTlURlFuM0pmaW5CaUNTa2dWNmVtNXN0RjhMTnBkT2FkdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cHM6Ly93d3cuYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL3d3dy5hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1777988400),
('l6qRHPtjokRkW1W89RmZVqPXi7oA8CXjG4e9Tr1n',46,'182.4.69.85','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjJPb0VKd2tNeWZpWkpqQ2tSRnNDQnVaanRRTVVuMjBkUEl1WmYwciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbS9ydWp1a2FuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDY7fQ==',1777968778),
('n2xOH5GB0QtOEMczxboa5Xyq2D10XqGU0TkK0s1Q',NULL,'64.227.135.189','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoia1p2b1lvUk9PQ0dqWEJ3dEhjWVQ1OVFPVDBwbGxmWTVnTTcwM1ZESiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777979066),
('nqXyqppEIe62r29kSA0ahP84d8uzp6QHxDJwSl7g',NULL,'194.164.107.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHlxY1c5UkhLUnMwazR6cGEzTFo2bHpsVXdObERYRzNnV2pYdGJ2UiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cHM6Ly9hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777976404),
('PobxMMEHLbY3L3XDwE15vUtxMLTADRjsNcZjyRFz',NULL,'194.164.107.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWZnUHBveFlyUVJoOGlnRHNsbUZLQ0ZsMTlJR3dEdmJPSGJKWklmOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777976405),
('PTgeRO8BDiEG2SRW8LMwGMGNft4yVJmTtMuQWKBR',NULL,'43.131.253.14','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoib2JyUGpzbnY1UGNzUFZkTlRub0luQWNYeVJCZEc0bWdwTWxPUjNDaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9hbmRhbGFzZmV0b2xpbmsuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1777985724),
('QxMhmda9mNdjSRrUTmYLJ2BotZIDWPQSC49XFUgW',NULL,'43.131.253.14','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieXJEcTVOdHU4NXl5Tk1adXdhWXVSRnpoT3dZZzNxVVB3NFhsQWZ5QSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777985722),
('rhv6fXERhpdgGV8yJURIeIITDR19SUdIaCMlMRs1',NULL,'101.33.55.204','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmE1Z2RvNGFTalVJWTloQlR3Wmg4T0Rydjdjb0U3ODNMQ2Z6MzU1eSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9hbmRhbGFzZmV0b2xpbmsuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778002149),
('RxiorvbUphfikRqCA3MEhAK0R4FnIAzNr2DLDbyj',NULL,'216.226.76.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTU1yMm9wRHhQRW91OEV4Vnh1QThIZU4wR3VGZlpNRDBlWXAwY3hvYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vd3d3LmFuZGFsYXNmZXRvbGluay5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777970806),
('s1RQh0JwGvbsbOhmQuoU6ySEhm0H3EPn4jLypAFa',NULL,'43.153.123.3','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieVo1OHZSYUFRVGxlWm05NElHVFZNY2kzUWgwQXl6V1JwcXBtM0tBbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777968706),
('uGteLVuvBMMqKYs3RRD0pJcnZ2HfcOA7J7tJo1ul',NULL,'93.123.109.163','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZWVQTml3VHU4Q0RPNVh4WnBBU2J2RFNHWjBiZks2REgxdGFnc2g2YSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cHM6Ly93d3cuYW5kYWxhc2ZldG9saW5rLmNvbSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL3d3dy5hbmRhbGFzZmV0b2xpbmsuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1777972132),
('vB2xaZTWyGJqPTyBoKS0ANbYf33ISabtApnjNyh8',NULL,'35.229.189.191','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4240.193 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieXNJNHhDVGY2d3VtQVo3SWF1aWNENXVGbHV0ckZ6b2xRTk9SSXRUMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2FuZGFsYXNmZXRvbGluay5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777983049),
('x68FTnL6olpU4l2njMGYgNKIDnQ8wGsPbYFHUTIQ',NULL,'43.153.123.3','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFhxN3BjaTh3Q0xzcEVrNEdvT3ZiWU16ZFljSFJXUGQ1M0RmUUhYViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9hbmRhbGFzZmV0b2xpbmsuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1777968707),
('XfadLobnD9VrJlR5lRNXwpN2Fp95a7wDhK4GjOQO',NULL,'176.65.139.168','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUhsb29OZWpqTzRhNXZHNGFEcmVYd1ZsNmtOTnJzTG52Nlg1bnlqbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777966067),
('yAa2HNrTneQQIVm40lx5J9gYM57MquQJqf7Xr42R',NULL,'145.239.65.175','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDFsaGtpQ2V6dnV3RkVwMzBiS0VXenliQlBFR2hBQlROUVlYSHNadSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYW5kYWxhc2ZldG9saW5rLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777962048);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soap`
--

DROP TABLE IF EXISTS `soap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soap` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kunjungan_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `subjektif` text DEFAULT NULL,
  `objektif` text DEFAULT NULL,
  `td_sys` smallint(5) unsigned DEFAULT NULL,
  `td_dia` smallint(5) unsigned DEFAULT NULL,
  `map` smallint(5) unsigned DEFAULT NULL,
  `assessment` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soap_kunjungan_id_foreign` (`kunjungan_id`),
  KEY `soap_user_id_foreign` (`user_id`),
  CONSTRAINT `soap_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `soap_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soap`
--

LOCK TABLES `soap` WRITE;
/*!40000 ALTER TABLE `soap` DISABLE KEYS */;
INSERT INTO `soap` VALUES
(7,7,11,'Riwayat sekarang :\r\n- Sakit kepala / Pandangan kabur','KU : Baik\r\nKes : CM\r\nTD : 170/100\r\nHR : 80x/menit\r\nRR : 20x/menit',NULL,NULL,NULL,'Hamil 34-35 minggu dgn PEB severe features\r\nDiagnosis :\r\n\r\nDiagnosis banding: …','Obs TVI dan DJJ\r\nCek lab Lengkap\r\nIVFD -----',NULL,'2025-11-06 03:14:04','2026-01-17 08:07:50'),
(34,13,11,'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb','Pemeriksaan fisik :\r\n- TD: 160/110 mmHg, MAP: 127 mmHg , N: …/menit, RR: …/menit, Suhu: …°C\r\n- Kesadaran: compos mentis / …\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nUSG Obstetri:\r\n- Biometri: ...\r\n- AFI/ICA: ...\r\n- Posisi plasenta: ...\r\n- Doppler: S/D AU: ... | PI AU: ... | CPR: ...',160,110,127,'Diagnosis :\r\n- Preeklampsia berat\r\n- Eklampsia\r\n- Edema paru\r\nDiagnosis banding: …','Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga','inj. MgSO4','2026-01-20 15:09:49','2026-01-20 15:09:49'),
(35,14,15,'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb','Pemeriksaan fisik :\r\n- TD: 160/100 mmHg, MAP: 120 mmHg , N: …/menit, RR: …/menit, Suhu: …°C\r\n- Kesadaran: compos mentis / …\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n\r\nUSG Obstetri:\r\n- Biometri: ...\r\n- AFI/ICA: ...\r\n- Posisi plasenta: ...\r\n- Doppler: S/D AU: ... | PI AU: ... | CPR: ...\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …',160,100,120,'Diagnosis :\r\n- Preeklampsia berat\r\nDiagnosis banding: …','Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga','berikan Nifedipine 10 mg','2026-01-20 15:18:16','2026-01-20 15:18:16'),
(36,16,8,'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala / Pandangan kabur / Nyeri epigastrium\r\n\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb','Pemeriksaan fisik :\r\n- TD: 160/110 mmHg, MAP: 127 mmHg , N: …/menit, RR: …/menit, Suhu: …°C\r\n- Kesadaran: compos mentis / …\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …',160,110,127,'Diagnosis :\r\n- Preeklampsia berat\r\nDiagnosis banding: …','Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis','oooo','2026-01-21 07:02:52','2026-01-21 07:02:52'),
(37,18,43,'Riwayat sekarang :\r\n- Sakit kepala / Mual muntah','- TD: 110/90 mmHg, MAP: 97 mmHg , N: …/menit, RR: …/menit, Suhu: …°C',110,90,97,'Diagnosis banding: …\r\nDiagnosis :\r\nloresum\r\nDiagnosis banding: …','lorepsum','lorepsum','2026-04-12 01:43:28','2026-04-12 01:43:28'),
(38,22,1,'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala / Pandangan kabur / Nyeri epigastrium / Mual muntah\r\n\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb','Pemeriksaan fisik :\r\n- TD: 223/23 mmHg, MAP: 90 mmHg , N: …/menit, RR: …/menit, Suhu: …°C\r\n- Kesadaran: compos mentis / …\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n\r\nUSG Obstetri:\r\n- Biometri: ...\r\n- AFI/ICA: ...\r\n- Posisi plasenta: ...\r\n- Doppler: S/D AU: ... | PI AU: ... | CPR: ...\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …',223,23,90,'Diagnosis :\r\n- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya\r\nDiagnosis banding: …','Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga','eee','2026-04-28 08:43:50','2026-04-28 08:43:50'),
(39,23,15,'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala / Pandangan kabur\r\n\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb','Pemeriksaan fisik :\r\n- TD: 180/110 mmHg, MAP: 133 mmHg , N: …/menit, RR: …/menit, Suhu: …°C\r\n- Kesadaran: compos mentis / …\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …',180,110,133,'Diagnosis :\r\n- Preeklampsia berat\r\nDiagnosis banding: …','Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga','lorepsum','2026-05-05 07:24:03','2026-05-05 07:28:57'),
(40,24,46,'Keluhan utama: …\r\nRiwayat sekarang :\r\n- —\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb','Pemeriksaan fisik :\r\n- TD: 180/110 mmHg, MAP: 133 mmHg , N: …/menit, RR: …/menit, Suhu: …°C\r\n- Kesadaran: compos mentis / …\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …',180,110,133,'Diagnosis :\r\n- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya\r\nDiagnosis banding: …','Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga','lorepsum','2026-05-05 07:37:54','2026-05-05 07:37:54'),
(41,26,46,'Riwayat sekarang :\r\n- Sakit kepala / Nyeri epigastrium / Bengkak wajah-tangan-tungkai','- TD: 170/110 mmHg, MAP: 130 mmHg , N: …/menit, RR: …/menit, Suhu: …°C',170,110,130,'Diagnosis banding: …\r\nDiagnosis :\r\n- Preeklampsia berat\r\nDiagnosis banding: …','konsul dpjp','terapi mgso4 sesuai protap','2026-05-05 08:10:39','2026-05-05 08:10:39');
/*!40000 ALTER TABLE `soap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dokter','perawat','admin') NOT NULL DEFAULT 'dokter',
  `rumah_sakit_id` bigint(20) unsigned NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin RS Unand','afetolink@gmail.com','avatars/KrQEG1mU9baRdYMaT2NoYJh9rlBE9lZLv9KDq3HH.png',NULL,'$2y$12$sheY3JRr3lQ4Ob.NIQPLu.9SX5aT44tQMuHJAF805Am8k1mpZw9rC','admin',5,NULL,'2025-10-25 02:11:22','2026-01-21 03:11:54'),
(8,'Admin RSUD Sungai Dareh','sungaidareh@gmail.com',NULL,NULL,'$2y$12$zppO/f/RtfSxGlfM4fcVzuZIQbH9Plrdor4d6RQFdvYcJuoFy/KbS','admin',4,'5TKvKaWzaMEVEmeDHbywmTLAF0HvsCr6gXTB24kFSc6l3gR7egofjW8tepIa','2025-10-30 02:05:29','2025-10-30 02:05:29'),
(9,'Admin RS M. Djamil Padang','rsmjamil@gmail.com',NULL,NULL,'$2y$12$oq.uxVCWLBiP8N/z8OFgF.fKXiTKcsouLasmZd7kchGRzzSmjzk/2','admin',3,NULL,'2025-10-30 02:06:26','2025-11-03 05:58:37'),
(10,'dr. Rina Gustuti, SpOG,Subs-KFM','rinagustuti2308@gmail.com',NULL,NULL,'$2y$12$uWgRuVvDrqi0yHxOHD9DYe6wQ3EihX/n4GynHjqC6QgXDMGiERmc.','dokter',5,'sZN5RUSqO0PPZ66imYD3EvDJOX1lWzslyHjC9bwlrpKom1BwjnTb8Jbf8VNI','2025-10-30 02:08:36','2025-10-30 02:08:36'),
(11,'dr. Pom Harry Satria, SpOG,Subs-Obsos','harrysatria.pom@gmail.com',NULL,NULL,'$2y$12$NGAeR3v4Qb7oxobPOrvRk.ncAki1D5k2cdaHUhyPR4/g6tdrl10lq','dokter',4,'xJyBjwcKj2bzd5FF7gW83ZsEhqw93DwdkvM0yJqGXvzzoh8qX5zjqZHuvWe3','2025-10-30 02:09:50','2025-10-30 06:01:30'),
(12,'dr. Widayat, SpOG','dokterwidayat@gmail.com',NULL,NULL,'$2y$12$q1prKYm4Qg3YiH7MBIYlgOftJnszqVFtHziHifZlD1AIVK/Jy61Yq','dokter',4,NULL,'2025-10-30 02:11:26','2025-10-30 06:02:17'),
(13,'Prof. Dr. dr. Yusrawati, SpOG, Subs-KFM, MARS, M.H','yusrawati_65@yahoo.co.id',NULL,NULL,'$2y$12$D.OBrK9qqo.ju84PxVBfd.bpWnahJ.tJKD879sHZQ9M8JagltibFe','dokter',3,'RZ0N8CYb0UwnXVbfE3I3S0IfCJtYye1B3yICk0ZOgkW5XvGPGxBw9HcCSeVF','2025-10-30 02:12:43','2025-11-03 06:11:52'),
(14,'Dr. dr. Vauline Basyir, SpOG, Subs-KFM','vaulinne@gmail.com',NULL,NULL,'$2y$12$5ML7Ox2CKMqC2QB8gA7u/OJyR9meoXGlEYaebqZV8fTVjfx3SD4QO','dokter',3,NULL,'2025-10-30 02:14:57','2025-10-30 06:04:31'),
(15,'dr. Aswin Boy,SpOG (Trainee)','aswinboy27@gmail.com','avatars/dE7dcB9YwL99ECJ3dc39LkTQxd3nENO69fW1z9K8.jpg',NULL,'$2y$12$8yGpC9tbkd8kRdTvh/57g.ToFgFtR1X9f7svP.TtxRt6sj5ost1LG','dokter',3,'229CVJT7UtgjbwSNX9apvMfdh1oTdgqCjSdSoXlVxGcKLjLRaJ5ekkh5DbN8','2025-10-30 02:16:19','2025-11-05 01:03:59'),
(16,'dr. Subhan, SpOG (Trainee)','subhan.38@yahoo.com',NULL,NULL,'$2y$12$s7nJ8VN/DtMsqRdekDItgeb1KR5GtnHP9OQX0.cl4ihuuuFkzjsXi','dokter',3,NULL,'2025-10-30 02:17:43','2025-10-30 06:14:19'),
(17,'dr. Zeino,SpOG (Trainee)','zeino_fridsto@yahoo.com',NULL,NULL,'$2y$12$FiogMlvOwIkbnQO8rCLfIelzjjjRr.haD.UWqisnp.sSmHfQZd7iO','dokter',3,NULL,'2025-10-30 02:18:55','2025-10-30 06:15:00'),
(18,'dr. Dian, SpOG (Trainee)','diannoviyantyspog@gmail.com',NULL,NULL,'$2y$12$e4JNWqBs3huaXt9gVcOkv.2ePVKdSjzul9k.KzrDpgB6sQIUBVUQm','dokter',3,'VOHj5HMQ9sQQeS9dCHPhmuCxb96axBwCsyqlJQgBAPAjWhqz5A5sQecTT22J','2025-10-30 02:20:07','2025-10-30 06:15:56'),
(19,'dr. Edward, SpOG (Trainee)','edh121278@gmail.com',NULL,NULL,'$2y$12$g3IAeEVKyKkrhUPi.uEUcOjxDkqDlqrY3EaEtiQPCrEKjOcNiMAo6','dokter',3,NULL,'2025-10-30 02:20:58','2025-10-30 06:16:25'),
(20,'dr. Bobby,SpOG (Trainee)','bobbyfitriantoni65@gmail.com',NULL,NULL,'$2y$12$VM2Ak8zczQvOPzE/ZLLmSueZO7zlCvlgIDUXJYKcIZ8zRnBvJ3nW6','dokter',3,NULL,'2025-10-30 02:22:16','2025-10-30 06:16:49'),
(21,'dr. Irfan Kurnia, SpOG (Trainee)','dokterirfankurnia@gmail.com',NULL,NULL,'$2y$12$4i6nJoalpz29IhDvFnx0huEF33qkWjtlPdl2vCWtoSD/DAoIfJiuy','dokter',3,NULL,'2025-10-30 06:10:57','2025-10-30 06:17:18'),
(22,'Dokter Jaga','dokterrsudsungaidareh@gmail.com',NULL,NULL,'$2y$12$rvscW6rrlcZW7NBNmil82uuaoqN4/odYYmUrIcEAXtd/mQRh2eiXO','dokter',4,NULL,'2025-10-30 06:19:02','2025-10-30 06:19:02'),
(23,'Dokter Jaga','dokterrsupdjamil@gmail.com',NULL,NULL,'$2y$12$oTY31k2pOgrrxa2tN/SHE.do2l3l3N3huoEYDahSlQCjUHAs8U14e','dokter',3,NULL,'2025-10-30 06:20:02','2025-10-30 06:20:02'),
(24,'Dokter Jaga','dokterrsunpad@gmail.com',NULL,NULL,'$2y$12$HURVXG28qBrxlDSQAhSUT.Q0B5aCiM3ml2x5vV34Zh4znoswUlLy2','dokter',5,NULL,'2025-10-30 06:20:59','2025-10-30 06:20:59'),
(25,'PONEK','ponekrsudsungaidareh@gmail.com',NULL,NULL,'$2y$12$vhFsp.scsoUCPu7ar5UqmexYNWVGa4LalBZZJMjjWukoSdDttXASu','perawat',4,NULL,'2025-10-30 06:22:08','2025-10-30 06:22:08'),
(26,'PONEK 1','ponek1@gmail.com',NULL,NULL,'$2y$12$Q09cZgzywqbiVBoVUpdhHO.xwaj0SmY20MbE/PpSxrphzNRaPdfg2','perawat',3,NULL,'2025-10-30 07:01:06','2025-10-30 07:01:06'),
(27,'PONEK 3','ponekuniversitasandalas@gmail.com',NULL,NULL,'$2y$12$aJPMU8nLjz2qd216BZbsE.E9YeMYhPTxCa4eSf9zStUj24N3Cpt8u','perawat',5,NULL,'2025-10-30 07:02:16','2025-10-30 07:02:16'),
(28,'laboratorium','laboratoriumsungaidareh@gmail.com',NULL,NULL,'$2y$12$ZaViNW6tQ.qhjmOF3zVV/efAjCANfUBOVidSuiVW21SNREBrpd80m','perawat',4,NULL,'2025-10-30 07:04:19','2025-10-30 07:04:19'),
(29,'laboratorium 1','laboratoriumrsupdjamilpadang@gmail.com',NULL,NULL,'$2y$12$Td2zOBcWMQN.xyeSxf3JYeX/R9ZUINffppzp8BUohNaSFNlN4.A.6','perawat',3,NULL,'2025-10-30 07:07:21','2025-10-30 07:07:21'),
(30,'laboratorium 2','laboratoriumuniversitasandalas@gmail.com',NULL,NULL,'$2y$12$u5WD2XZHnmkyolNRWZep2upKZMyyXv.X/1jOhXMnsBfuHBgGyV.IS','perawat',5,NULL,'2025-10-30 07:08:03','2025-10-30 07:08:03'),
(31,'RADIOLOGI','radiologisungaidareh@gmail.com',NULL,NULL,'$2y$12$o/3J5k8Bji5bpMtV.sA86OrVcjHO50k33W.VdaBoA/N9SMrhyuSJe','perawat',4,NULL,'2025-10-30 07:09:04','2025-10-30 07:09:04'),
(32,'RADIOLOGI 1','radiologirsupdjamilpadang@gmail.com',NULL,NULL,'$2y$12$fVwhGouzuAeGfOsSFxUXnOCo3TzS3YMVV3fN9MQcNqY2hldDDFKuO','perawat',3,NULL,'2025-10-30 07:10:38','2025-10-30 07:10:38'),
(33,'RADIOLOGI 2','radiologiuniversitasandalas@gmail.com',NULL,NULL,'$2y$12$lPrToAksM53MiSZxNOPFD.YBMJl3Wd1CaZJqzyCa9ftI9YCEAz5qW','perawat',5,NULL,'2025-10-30 07:11:42','2025-10-30 07:11:42'),
(34,'Dr.dr. Dovy Djanas, SpOG, Subs-KFM','dovy.dj68@gmail.com',NULL,NULL,'$2y$12$n03LrHGZ9Ya8YFuXsBSb..uJRgQzdYeCTLZXsQULwHrw7Y/rPWQQi','dokter',3,NULL,'2025-11-03 06:03:53','2025-11-03 06:03:53'),
(35,'Dr.dr. Defrin, SpOG, Subs-KFM','defrin_pdg@yahoo.com',NULL,NULL,'$2y$12$o1jvC2yzo9Uc3yveIwTwHO4fPpAzuhulRcRA14m.tAVv6wJPs0DzS','dokter',3,NULL,'2025-11-03 06:05:02','2025-11-03 06:05:02'),
(36,'Dr. dr. Roza Sriyanti, SpOG, Subs-KFM','rozasyahrial@gmail.com',NULL,NULL,'$2y$12$4YZnWmE1snCVzfklual2deohp.mmgI/hHv5vrVW2qTjRCkTk5AEFC','dokter',3,NULL,'2025-11-03 06:06:31','2025-11-03 06:06:31'),
(37,'Dr. dr. Joserizal Serudji, SpOG, Subs-KFM','jrserudji@yahoo.co.id',NULL,NULL,'$2y$12$hx965EyybsO0TFzT34jcz.krU1yLPkkcY0Pei4PkomGgZWfO2rNBC','dokter',5,NULL,'2025-11-03 06:07:38','2025-11-03 06:07:38'),
(38,'Dr. dr. Hudila Rifa Karmia, SpOG, Subs K-FM','hrkspogdr@gmail.com',NULL,NULL,'$2y$12$wSnmglo9SyKM90cL3dUOoOelz5BU2HZHtDvn6ynj6Fp8vZOMwZcOG','dokter',5,NULL,'2025-11-03 06:08:41','2025-11-03 06:08:41'),
(40,'Dokter Jaga2','sungaidareh2@gmail.com',NULL,NULL,'$2y$12$B8IfSsPPQCY.DDrEqlOkeu6bK4NB3Ge3NM.oSjDVtohuxorVmVW0W','dokter',4,NULL,'2025-11-04 09:03:57','2025-11-04 09:03:57'),
(41,'Dokter Jaga3','sungaidareh3@gmail.com',NULL,NULL,'$2y$12$JAodk6gf/NPiuBW3eZMfieo/pcC7XJ0u0F5Ih8zrMa8ZJpIejTnBq','dokter',4,NULL,'2025-11-13 01:43:28','2025-11-13 01:43:28'),
(42,'ns. alnaira','irawan@gmail.com',NULL,NULL,'$2y$12$A4DINUa1IbeVD6K9JT.7W.EJFtJbRmo9zlNU17BCJOVfBSB7SRR.K','perawat',6,NULL,'2026-04-12 01:36:21','2026-04-12 01:36:21'),
(43,'dr nafasya','nafasya@gmail.com',NULL,NULL,'$2y$12$.RA0jVgcf/Rtoegn51UtT.QGol.meiGZtMUQWGmqh5nBWthvrqLJC','dokter',6,NULL,'2026-04-12 01:37:25','2026-04-12 01:37:25'),
(44,'Admin RS Hj Kamino','rshajikamino@gmail.com',NULL,NULL,'$2y$12$ukgd.bjH1C3TpNwVzEAA8.TnW6CCrPu71rHASkcJzUob/JXlVBOpa','admin',6,NULL,'2026-04-12 01:38:33','2026-04-12 01:38:33'),
(46,'dr. Juliana Askim','askimjuli93@gmail.com',NULL,NULL,'$2y$12$xoYH4l0QrfobvcGR88Y7Z.5szuJW7Drf1axTwk/XOfaR3ZBkikKYu','dokter',4,NULL,'2026-04-14 09:26:59','2026-04-14 09:26:59'),
(47,'dr. Embun Dini','Embundini82@gmail.com',NULL,NULL,'$2y$12$1OqCGrxnNqogl40/PK0AdeMET6C9.WpKmwu.JWtyrX8pUFSNJSn.m','dokter',4,'uS0xYaeoI0RiEoo68t7fzGzM9CWxKseWKBTvd4FFb29I66f3tu8do7JGH9Y2','2026-04-14 09:30:19','2026-04-14 09:30:19'),
(48,'dr. Muthiah R. Agus','muthiramadhani12@gmail.com',NULL,NULL,'$2y$12$fwho1pC0vUMMEoswvB9ol.lPFEyEA4k9CJSk0UPStZ18qJEUC3Cpm','dokter',4,NULL,'2026-04-14 09:31:33','2026-04-14 09:31:33'),
(49,'dr. Aziagtma Trangguna','trangguna@gmail.com',NULL,NULL,'$2y$12$fJCnXc0fASfPm9B5erKYteq3/2QL.qx7vFyr7B.MZ4q19Efn38TQu','dokter',4,NULL,'2026-04-14 09:32:57','2026-04-14 09:32:57'),
(50,'dr.nurafdaliza','nurafdaliza09@gmail.com',NULL,NULL,'$2y$12$ccJqf0Q/Ld.d.RpXljY.g.GbP08PV1fG3C5YOynX5RCRP1zfggUKu','dokter',4,NULL,'2026-04-15 07:49:42','2026-04-15 07:49:42'),
(51,'dr.iwan setiawan','iwansetiawan007.is2781@gmail.com',NULL,NULL,'$2y$12$k1sczKftW2Zu9MeE6hheMeDyq9MY07PaEuGv1BkiRVOXoyQSbgmna','dokter',4,NULL,'2026-04-15 07:54:07','2026-04-15 07:54:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'rujukan_pasien'
--

--
-- Dumping routines for database 'rujukan_pasien'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-06  0:30:01
