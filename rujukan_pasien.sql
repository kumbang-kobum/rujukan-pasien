-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 27, 2025 at 01:49 PM
-- Server version: 10.4.28-MariaDB-log
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rujukan_pasien`
--

-- --------------------------------------------------------

--
-- Table structure for table `berkas_medis`
--

CREATE TABLE `berkas_medis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `uploader_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berkas_medis`
--

INSERT INTO `berkas_medis` (`id`, `kunjungan_id`, `uploader_id`, `jenis`, `nama_file`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Lab', 'Screenshot 2025-10-25 160446.png', 'berkas/igIWGqaQuraXCwiZQ5RbkHj7OJ5S0ev8FS3QiOOu.png', '2025-10-25 02:28:47', '2025-10-25 02:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin.rsa@example.com|127.0.0.1', 'i:1;', 1761500176),
('laravel-cache-admin.rsa@example.com|127.0.0.1:timer', 'i:1761500176;', 1761500176),
('laravel-cache-dokter@rsa.com|127.0.0.1', 'i:1;', 1761500148),
('laravel-cache-dokter@rsa.com|127.0.0.1:timer', 'i:1761500148;', 1761500148);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"751bc80f-40c7-4e66-bb87-3cbfdae90d40\",\"displayName\":\"App\\\\Notifications\\\\RujukanMasukNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\RujukanMasukNotification\\\":3:{s:7:\\\"rujukan\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Rujukan\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"pengirim\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"9b9e3893-1630-4466-94d6-4fd8335924b5\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761470268,\"delay\":null}', 0, NULL, 1761470268, 1761470268);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_rawat` varchar(50) NOT NULL,
  `pasien_id` bigint(20) UNSIGNED NOT NULL,
  `dokter_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rumah_sakit_id` bigint(20) UNSIGNED NOT NULL,
  `poli` varchar(255) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `keluhan_utama` text DEFAULT NULL,
  `status_pulang` tinyint(1) NOT NULL DEFAULT 0,
  `waktu_pulang` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`id`, `no_rawat`, `pasien_id`, `dokter_id`, `user_id`, `rumah_sakit_id`, `poli`, `tanggal_kunjungan`, `waktu_masuk`, `keluhan_utama`, `status_pulang`, `waktu_pulang`, `created_at`, `updated_at`) VALUES
(1, '2025/10/25/00001', 1, 3, 1, 1, 'Jantung', '2025-10-25', '2025-10-25 09:21:00', 'berdenyut', 0, NULL, '2025-10-25 02:22:32', '2025-10-25 02:22:32'),
(2, '2025/10/26/00001', 1, 3, 1, 1, 'jantung', '2025-10-26', '2025-10-26 11:40:00', 'berdenyut', 0, NULL, '2025-10-26 04:40:53', '2025-10-26 04:40:53');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_15_145824_create_rumah_sakit_table', 1),
(5, '2025_09_15_145845_create_pasien_table', 1),
(6, '2025_09_15_145902_create_kunjungan_table', 1),
(7, '2025_09_15_145919_create_rujukan_table', 1),
(8, '2025_09_15_145944_create_soap_table', 1),
(9, '2025_09_15_161559_create_sessions_table', 1),
(10, '2025_09_17_042339_update_kunjungan_table_add_fields', 1),
(11, '2025_09_17_081150_add_fields_to_rujukan_table', 1),
(12, '2025_09_17_081337_update_rujukan_table_add_fields', 1),
(13, '2025_09_18_040948_update_no_rawat_in_kunjungan_table', 1),
(14, '2025_09_18_044216_add_status_pulang_to_kunjungan_table', 1),
(15, '2025_09_18_044735_add_waktu_pulang_to_kunjungan_table', 1),
(16, '2025_09_18_080044_create_rumah_sakits_table', 1),
(17, '2025_09_18_081743_add_penerima_to_rujukan_table', 1),
(18, '2025_09_18_110209_create_berkas_medis_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_rkm_medis` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `no_rkm_medis`, `nik`, `nama`, `tanggal_lahir`, `tempat_lahir`, `alamat`, `jenis_kelamin`, `telepon`, `created_at`, `updated_at`) VALUES
(1, '000001', '180310890017340812', 'sabeni', '2025-10-25', 'bandung', 'bandung', 'L', '0887393121432', '2025-10-25 02:21:22', '2025-10-25 02:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `rujukan`
--

CREATE TABLE `rujukan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `rumah_sakit_asal_id` bigint(20) UNSIGNED NOT NULL,
  `rumah_sakit_tujuan_id` bigint(20) UNSIGNED NOT NULL,
  `alasan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `dokter_tujuan_id` bigint(20) UNSIGNED NOT NULL,
  `alasan_rujukan` text NOT NULL,
  `status` enum('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_penerima` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `penerima_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rujukan`
--

INSERT INTO `rujukan` (`id`, `kunjungan_id`, `rumah_sakit_asal_id`, `rumah_sakit_tujuan_id`, `alasan`, `catatan`, `dokter_tujuan_id`, `alasan_rujukan`, `status`, `catatan_penerima`, `created_at`, `updated_at`, `penerima_id`) VALUES
(19, 1, 1, 4, 'fxfx', 'zvddz', 7, 'zdv', 'menunggu', NULL, '2025-10-26 06:46:07', '2025-10-26 06:46:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rumah_sakit`
--

CREATE TABLE `rumah_sakit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rumah_sakit`
--

INSERT INTO `rumah_sakit` (`id`, `nama`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
(1, 'RS A', 'Jl. Contoh No.1, Kota A', '021-1234567', '2025-10-25 02:11:22', '2025-10-25 02:11:22'),
(2, 'RS B', 'Jl. Contoh No.2, Kota B', '021-7654321', '2025-10-25 02:11:22', '2025-10-25 02:11:22'),
(3, 'RSUP Dr. M. Djamil Padang', 'Jl. Perintis Kemerdekaan, Sawahan Tim., Kec. Padang Tim., Kota Padang, Sumatera Barat 25129', '07518956666', '2025-10-26 01:32:31', '2025-10-26 01:36:44'),
(4, 'RSUD Sungai Dareh', 'Jl. Lintas Sumatera No.KM.2, Empat Koto, Kec. Pulau Punjung, Kabupaten Dharmasraya, Sumatera Barat 27614', '075440347', '2025-10-26 01:36:34', '2025-10-26 01:36:34'),
(5, 'Rumah Sakit Universitas Andalas', 'Universitas Andalas, Komplek Kampus Jl. Limau Manis, Limau Manis, Kec. Pauh, Kota Padang, Sumatera Barat 25176', '07518465000', '2025-10-26 01:37:29', '2025-10-26 01:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `rumah_sakits`
--

CREATE TABLE `rumah_sakits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dvyMYglFCM2ZJmNxfPuBH06d1dntHOxiV3FiZ9ke', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiclhxenhmRDZidFVVZlBvcTdzWDNDUVBHSU13M2IzZXBiNlpRdjNmbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1761498216),
('iUgA487trGHeZihIZI18G6r0LPrUYRks7FnXM89E', 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWDBZYXEyUXl1YUtiRHAxajFLZnRYa1Z4Z2JzelNKelhSRGh1SFJBWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcnVqdWthbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1761500620),
('tbWtIbeLHbdsfIZfVtDSNpkFyOyNIRfhKZiBMqKI', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYW0wNW4xR0JzSjJ2eUtzY2UzajJnVUlkR3pZNzloaTVwMkUzMHRibiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvdXNlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1761569277),
('TjBQ5NSRedpzdvHzQ6kDF5Cy4C33WA3mNnmU8wUY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidHZlSGFGWmwzRElTSUlITG1YMU9kZ1NwS1ltV1NCZ3lBYlZTWGFvZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761497064);

-- --------------------------------------------------------

--
-- Table structure for table `soap`
--

CREATE TABLE `soap` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subjektif` text DEFAULT NULL,
  `objektif` text DEFAULT NULL,
  `assessment` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `soap`
--

INSERT INTO `soap` (`id`, `kunjungan_id`, `user_id`, `subjektif`, `objektif`, `assessment`, `plan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'fwef', 'efw', 'wfef', 'ewfw', '2025-10-25 02:22:48', '2025-10-25 02:22:48'),
(2, 2, 1, 'Keluhan utama: …\r\nRiwayat sekarang (II):\r\n- Sakit kepala / pandangan kabur / nyeri epigastrium / mual muntah / bengkak wajah-tangan-tungkai / kejang (…x)\r\nRiwayat dahulu (III): HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri (IV): ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko (V): usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb', 'Pemeriksaan fisik (VI):\r\n- Kesadaran: compos mentis / …\r\n- TD: …/… mmHg, N: …/menit, RR: …/menit, Suhu: …°C\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang (VII):\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …', 'Diagnosis (VIII):\r\n- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya\r\nDiagnosis banding: …', 'Rencana (IX):\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga', '2025-10-26 04:43:19', '2025-10-26 04:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dokter','perawat','admin') NOT NULL DEFAULT 'dokter',
  `rumah_sakit_id` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `rumah_sakit_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin RSA', 'icantp11@gmail.com', NULL, '$2y$12$K2xfNN/DMrSVqyxnSdT3SOjAJfpY2.tF8v3oIBj0Uk5XZ4cTLq34.', 'admin', 1, NULL, '2025-10-25 02:11:22', '2025-10-26 02:29:52'),
(2, 'Admin RSB', 'admin.rsb@example.com', NULL, '$2y$12$fRMFUDHHZ6VLMRud81DeJuhyQnMIoCrPIuY44mG4j8e4Sd1kblTvW', 'admin', 2, NULL, '2025-10-25 02:11:23', '2025-10-25 02:11:23'),
(3, 'dr. RSA', 'dokter.rsa@example.com', NULL, '$2y$12$tdSzfhoy0YE0IY6nNAkQn.Y/W6S3n1kTAG2BABiXbpZOi1/DE3E02', 'dokter', 1, NULL, '2025-10-25 02:11:23', '2025-10-25 02:11:23'),
(4, 'dr. RSB', 'dokter.rsb@example.com', NULL, '$2y$12$a0Uj8e0t4iwWUbzP9F2Nfuve8E8KZ578DUPOY4cOXKwoEm2K3HPZq', 'dokter', 2, NULL, '2025-10-25 02:11:23', '2025-10-25 02:11:23'),
(5, 'Perawat RSA', 'perawat.rsa@example.com', NULL, '$2y$12$JRzdzcaGKVTShNfOtsjkt.SMgvyy1udW17rFvaWSXB.CQobl.PSCq', 'perawat', 1, NULL, '2025-10-25 02:11:24', '2025-10-25 02:11:24'),
(6, 'Perawat RSB', 'perawat.rsb@example.com', NULL, '$2y$12$ic1Kf2zIgYQaBMBCEA/B0ev.Z9AupIGUZOfbeDVv6KwsMY2dSDruS', 'perawat', 2, NULL, '2025-10-25 02:11:24', '2025-10-25 02:11:24'),
(7, 'ikhsan', 'ikhsanp34@gmail.com', NULL, '$2y$12$dwip3PRPavKv9AVnr6pCZOpXU/ZJOSkWUYr1.oLI.y4tpPT4kkn7i', 'dokter', 4, NULL, '2025-10-26 01:48:53', '2025-10-26 04:18:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berkas_medis`
--
ALTER TABLE `berkas_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berkas_medis_kunjungan_id_foreign` (`kunjungan_id`),
  ADD KEY `berkas_medis_uploader_id_foreign` (`uploader_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kunjungan_no_rawat_unique` (`no_rawat`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pasien_no_rkm_medis_unique` (`no_rkm_medis`),
  ADD UNIQUE KEY `pasien_nik_unique` (`nik`);

--
-- Indexes for table `rujukan`
--
ALTER TABLE `rujukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rujukan_penerima_id_foreign` (`penerima_id`);

--
-- Indexes for table `rumah_sakit`
--
ALTER TABLE `rumah_sakit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rumah_sakits`
--
ALTER TABLE `rumah_sakits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `soap`
--
ALTER TABLE `soap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `soap_kunjungan_id_foreign` (`kunjungan_id`),
  ADD KEY `soap_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berkas_medis`
--
ALTER TABLE `berkas_medis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rujukan`
--
ALTER TABLE `rujukan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rumah_sakit`
--
ALTER TABLE `rumah_sakit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rumah_sakits`
--
ALTER TABLE `rumah_sakits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soap`
--
ALTER TABLE `soap`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berkas_medis`
--
ALTER TABLE `berkas_medis`
  ADD CONSTRAINT `berkas_medis_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `berkas_medis_uploader_id_foreign` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rujukan`
--
ALTER TABLE `rujukan`
  ADD CONSTRAINT `rujukan_penerima_id_foreign` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `soap`
--
ALTER TABLE `soap`
  ADD CONSTRAINT `soap_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `soap_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
