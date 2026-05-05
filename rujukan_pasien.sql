-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Apr 2026 pada 05.20
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `berkas_medis`
--

CREATE TABLE `berkas_medis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `soap_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uploader_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `berkas_medis`
--

INSERT INTO `berkas_medis` (`id`, `kunjungan_id`, `soap_id`, `uploader_id`, `kategori`, `nama_file`, `path`, `created_at`, `updated_at`) VALUES
(20, 10, NULL, 1, 'USG', 'Logo.jpeg', 'berkas/g2sKdv98URx486trPZJNB7qfliEwOMyDMLVEpv3p.jpg', '2025-11-12 08:22:24', '2025-11-12 08:30:34'),
(21, 10, NULL, 1, 'LAB', 'WhatsApp Image 2025-11-04 at 15.08.20_3ce3a691.jpg', 'berkas/YHT3Z2PcTmQAP7PzjNUxhx2nNaTiw5m1KNePEkrQ.jpg', '2025-11-12 08:22:24', '2025-11-12 08:30:34'),
(24, 10, NULL, 1, 'USG', 'logo-rujukan.png', 'berkas/KGGzX0DUFP5SujydiM2ccbLNocifPJCUo7UUpZOl.png', '2025-11-12 08:32:07', '2025-11-12 08:32:07'),
(25, 10, NULL, 1, 'LAB', 'Logo.jpeg', 'berkas/4FzKxEYmYM2UnoURccN1cL5nC5sAZsfGZmYZsEZt.jpg', '2025-11-12 08:32:07', '2025-11-12 08:32:07'),
(26, 10, NULL, 1, 'USG', 'revisi.jpg', 'berkas/GWcsBTu6c5Jhm0cEMxmocRbEyQWzKB2BozcN470s.jpg', '2025-11-12 08:47:09', '2025-11-12 08:47:09'),
(27, 10, NULL, 1, 'LAB', 'Logo.jpeg', 'berkas/bzSjVdkLFMOsrFtJkvGtdcXbXTdWGPhuT8vOebza.jpg', '2025-11-12 08:47:09', '2025-11-12 08:47:09'),
(28, 11, NULL, 1, 'USG', 'logo-rujukan.png', 'berkas/MqtLLPVclRmkzSDWzXHJunO5dKR4lGCAkyl2MuU9.png', '2025-11-13 12:37:37', '2025-11-13 12:37:37'),
(29, 11, NULL, 1, 'LAB', 'Logo.jpeg', 'berkas/X5KzLen17mduwRpr9cydJIb9A4YHBVMJJdG6maYo.jpg', '2025-11-13 12:37:37', '2025-11-13 12:37:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('andalasfetolink-cache-aswinboy@gmail.com|110.137.137.114', 'i:1;', 1763009512),
('andalasfetolink-cache-aswinboy@gmail.com|110.137.137.114:timer', 'i:1763009512;', 1763009512);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"751bc80f-40c7-4e66-bb87-3cbfdae90d40\",\"displayName\":\"App\\\\Notifications\\\\RujukanMasukNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\RujukanMasukNotification\\\":3:{s:7:\\\"rujukan\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Rujukan\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"pengirim\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"9b9e3893-1630-4466-94d6-4fd8335924b5\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761470268,\"delay\":null}', 0, NULL, 1761470268, 1761470268),
(2, 'default', '{\"uuid\":\"288b326f-db4c-4789-983c-8110fd07132e\",\"displayName\":\"App\\\\Notifications\\\\RujukanMasukNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\RujukanMasukNotification\\\":3:{s:7:\\\"rujukan\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Rujukan\\\";s:2:\\\"id\\\";i:20;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"pengirim\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"35fc427a-6c81-4d71-8a1c-248bdea76c3c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761787702,\"delay\":null}', 0, NULL, 1761787702, 1761787702);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_konsultasi` varchar(40) NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `pasien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rumah_sakit_asal_id` bigint(20) UNSIGNED NOT NULL,
  `rumah_sakit_tujuan_id` bigint(20) UNSIGNED NOT NULL,
  `dokter_pengirim_id` bigint(20) UNSIGNED NOT NULL,
  `dokter_tujuan_id` bigint(20) UNSIGNED NOT NULL,
  `escalated_to_rujukan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_ihs_number` varchar(255) DEFAULT NULL,
  `organization_ihs_asal` varchar(255) DEFAULT NULL,
  `organization_ihs_tujuan` varchar(255) DEFAULT NULL,
  `practitioner_ihs_pengirim` varchar(255) DEFAULT NULL,
  `practitioner_ihs_tujuan` varchar(255) DEFAULT NULL,
  `practitioner_role_pengirim` varchar(255) DEFAULT NULL,
  `practitioner_role_tujuan` varchar(255) DEFAULT NULL,
  `encounter_satusehat_id` varchar(255) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `urgensi` varchar(20) NOT NULL DEFAULT 'rutin',
  `alasan_konsultasi` text NOT NULL,
  `pertanyaan_klinis` text NOT NULL,
  `ringkasan_klinis` text DEFAULT NULL,
  `diagnosis_kerja` text DEFAULT NULL,
  `hasil_penunjang` text DEFAULT NULL,
  `terapi_berjalan` text DEFAULT NULL,
  `consent_status` varchar(30) NOT NULL DEFAULT 'belum_diminta',
  `consent_granted_by_name` varchar(255) DEFAULT NULL,
  `consent_granted_by_role` varchar(255) DEFAULT NULL,
  `consent_method` varchar(255) DEFAULT NULL,
  `consent_granted_at` timestamp NULL DEFAULT NULL,
  `consent_expires_at` timestamp NULL DEFAULT NULL,
  `consent_notes` text DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `answered_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `cancelled_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `no_konsultasi`, `kunjungan_id`, `pasien_id`, `rumah_sakit_asal_id`, `rumah_sakit_tujuan_id`, `dokter_pengirim_id`, `dokter_tujuan_id`, `escalated_to_rujukan_id`, `patient_ihs_number`, `organization_ihs_asal`, `organization_ihs_tujuan`, `practitioner_ihs_pengirim`, `practitioner_ihs_tujuan`, `practitioner_role_pengirim`, `practitioner_role_tujuan`, `encounter_satusehat_id`, `judul`, `urgensi`, `alasan_konsultasi`, `pertanyaan_klinis`, `ringkasan_klinis`, `diagnosis_kerja`, `hasil_penunjang`, `terapi_berjalan`, `consent_status`, `consent_granted_by_name`, `consent_granted_by_role`, `consent_method`, `consent_granted_at`, `consent_expires_at`, `consent_notes`, `status`, `submitted_at`, `accepted_at`, `answered_at`, `closed_at`, `last_message_at`, `cancelled_reason`, `created_at`, `updated_at`) VALUES
(2, 'KON-20260410-0001', 11, 6, 5, 3, 1, 42, NULL, NULL, NULL, NULL, NULL, 'Jantung', NULL, '1', NULL, 'nyeri dada', 'segera', 'dada membiru', 'dada membiru dikarenakan gula atau ada hal lain', 'nyeri dada', 'dada berdenyut', 'sakit ginjal', 'terapi rutin', 'menunggu', 'sabeni', 'suami', 'verbal_tercatat', '2026-04-10 14:15:00', '2026-04-11 14:15:00', 'tidak ada', 'awaiting_consent', NULL, NULL, NULL, NULL, '2026-04-10 07:15:35', NULL, '2026-04-10 07:15:35', '2026-04-10 07:16:57'),
(3, 'KON-20260411-0001', 7, 5, 4, 3, 22, 42, NULL, NULL, NULL, NULL, NULL, 'Jantung', NULL, '1', NULL, 'nyeri dada akut', 'segera', 'dada membiru namun keadaan baik', 'apakah itu normal terjadi karena dada membiru', 'keadaan pasien nyeri sedang', 'cek usg aman', 'ingin mendapatkan info mengenai hal baru', 'terapi rutin dan disiplin', 'disetujui', 'sabeni', 'suami', 'verbal_tercatat', '2026-04-11 03:15:00', '2026-04-12 03:16:00', NULL, 'closed', '2026-04-10 20:17:36', '2026-04-10 20:18:07', NULL, '2026-04-10 20:19:37', '2026-04-10 20:19:29', NULL, '2026-04-10 20:16:08', '2026-04-10 20:19:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsultasi_audit_logs`
--

CREATE TABLE `konsultasi_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `konsultasi_id` bigint(20) UNSIGNED NOT NULL,
  `actor_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konsultasi_audit_logs`
--

INSERT INTO `konsultasi_audit_logs` (`id`, `konsultasi_id`, `actor_user_id`, `event_type`, `deskripsi`, `payload`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(29, 2, 1, 'created', 'Konsultasi dibuat.', '{\"status\":\"awaiting_consent\",\"target_doctor_id\":42,\"target_rs_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 07:15:35', '2026-04-10 07:15:35'),
(30, 2, 1, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 07:15:36', '2026-04-10 07:15:36'),
(31, 2, 1, 'updated', 'Konsultasi diperbarui.', '{\"status\":\"awaiting_consent\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 07:16:57', '2026-04-10 07:16:57'),
(32, 2, 1, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 07:16:58', '2026-04-10 07:16:58'),
(33, 3, 22, 'created', 'Konsultasi dibuat.', '{\"status\":\"awaiting_consent\",\"target_doctor_id\":42,\"target_rs_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:16:08', '2026-04-10 20:16:08'),
(34, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:16:09', '2026-04-10 20:16:09'),
(35, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:17:18', '2026-04-10 20:17:18'),
(36, 3, 22, 'updated', 'Konsultasi diperbarui.', '{\"status\":\"awaiting_consent\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:17:24', '2026-04-10 20:17:24'),
(37, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:17:25', '2026-04-10 20:17:25'),
(38, 3, 22, 'updated', 'Konsultasi diperbarui.', '{\"status\":\"submitted\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:17:36', '2026-04-10 20:17:36'),
(39, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:17:36', '2026-04-10 20:17:36'),
(40, 3, 42, 'accepted', 'Konsultasi diterima dokter tujuan.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:18:07', '2026-04-10 20:18:07'),
(41, 3, 42, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:18:11', '2026-04-10 20:18:11'),
(42, 3, 42, 'message_sent', 'Pesan konsultasi ditambahkan.', '{\"jenis_pesan\":\"request_more_info\",\"status_baru\":\"awaiting_more_info\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:18:35', '2026-04-10 20:18:35'),
(43, 3, 42, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:18:36', '2026-04-10 20:18:36'),
(44, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:19:11', '2026-04-10 20:19:11'),
(45, 3, 22, 'message_sent', 'Pesan konsultasi ditambahkan.', '{\"jenis_pesan\":\"message\",\"status_baru\":\"in_discussion\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:19:29', '2026-04-10 20:19:29'),
(46, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:19:29', '2026-04-10 20:19:29'),
(47, 3, 22, 'closed', 'Konsultasi ditutup.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:19:37', '2026-04-10 20:19:37'),
(48, 3, 22, 'viewed', 'Detail konsultasi dibuka.', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 20:19:37', '2026-04-10 20:19:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsultasi_pesan`
--

CREATE TABLE `konsultasi_pesan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `konsultasi_id` bigint(20) UNSIGNED NOT NULL,
  `pengirim_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_pesan` varchar(30) NOT NULL DEFAULT 'message',
  `isi_pesan` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'sent',
  `dibaca_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konsultasi_pesan`
--

INSERT INTO `konsultasi_pesan` (`id`, `konsultasi_id`, `pengirim_id`, `jenis_pesan`, `isi_pesan`, `status`, `dibaca_at`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 'question', 'Pertanyaan klinis: dada membiru dikarenakan gula atau ada hal lain\n\nRingkasan klinis: nyeri dada\n\nDiagnosis kerja: dada berdenyut', 'sent', NULL, '2026-04-10 07:15:35', '2026-04-10 07:15:35'),
(3, 3, 22, 'question', 'Pertanyaan klinis: apakah itu normal terjadi karena dada membiru\n\nRingkasan klinis: keadaan pasien nyeri sedang\n\nDiagnosis kerja: cek usg aman', 'read', '2026-04-10 20:18:11', '2026-04-10 20:16:08', '2026-04-10 20:18:11'),
(4, 3, 42, 'request_more_info', 'minta info tambahan untuk pasien tsb kondisi kakinya bagaimana', 'read', '2026-04-10 20:19:10', '2026-04-10 20:18:35', '2026-04-10 20:19:10'),
(5, 3, 22, 'message', 'untuk kaki aman', 'sent', NULL, '2026-04-10 20:19:29', '2026-04-10 20:19:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_rawat` varchar(50) NOT NULL,
  `pasien_id` bigint(20) UNSIGNED NOT NULL,
  `dokter_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rumah_sakit_id` bigint(20) UNSIGNED NOT NULL,
  `satusehat_encounter_id` varchar(255) DEFAULT NULL,
  `rajalranap` varchar(255) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `keluhan_utama` text DEFAULT NULL,
  `status_pulang` tinyint(1) NOT NULL DEFAULT 0,
  `waktu_pulang` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kunjungan`
--

INSERT INTO `kunjungan` (`id`, `no_rawat`, `pasien_id`, `dokter_id`, `user_id`, `rumah_sakit_id`, `satusehat_encounter_id`, `rajalranap`, `tanggal_kunjungan`, `waktu_masuk`, `keluhan_utama`, `status_pulang`, `waktu_pulang`, `created_at`, `updated_at`) VALUES
(1, '2025/10/25/00001', 1, 3, 1, 1, NULL, 'Jantung', '2025-10-25', '2025-10-25 09:21:00', 'berdenyut', 0, NULL, '2025-10-25 02:22:32', '2025-10-25 02:22:32'),
(2, '2025/10/26/00001', 1, 3, 1, 1, NULL, 'jantung', '2025-10-26', '2025-10-26 11:40:00', 'berdenyut', 0, NULL, '2025-10-26 04:40:53', '2025-10-26 04:40:53'),
(3, '2025/10/29/00001', 2, 3, 3, 1, NULL, 'Kebidanan', '2025-10-29', '2025-10-29 17:27:00', NULL, 0, NULL, '2025-10-29 17:27:56', '2025-10-29 17:27:56'),
(5, '2025/11/04/00001', 3, 21, 10, 5, NULL, 'IGD', '2025-11-04', '2025-11-04 05:36:00', 'sakit pada bagian perut', 0, NULL, '2025-11-04 05:37:40', '2025-11-04 05:37:40'),
(6, '2025/11/05/00001', 4, 11, 11, 4, NULL, 'Ponek', '2025-11-05', '2025-11-05 01:12:00', 'hamil darah tinggi', 0, NULL, '2025-11-05 01:13:24', '2025-11-05 01:13:24'),
(7, '2025/11/06/00001', 5, 11, 11, 4, NULL, 'Ponek', '2025-11-06', '2025-11-06 05:11:00', 'Hamil dengan darah tinggi', 0, NULL, '2025-11-06 03:12:27', '2025-11-06 03:12:27'),
(8, '2025/11/06/00002', 6, 15, 15, 3, NULL, 'ponek', '2025-11-06', '2025-11-06 04:14:00', 'Hamil dengan darah tinggi', 0, NULL, '2025-11-06 04:14:24', '2025-11-06 04:14:24'),
(9, '2025/11/11/00001', 6, 22, 1, 5, NULL, 'Rawat Jalan', '2025-11-11', '2025-11-11 03:49:00', 'migran', 0, NULL, '2025-11-11 03:55:21', '2025-11-11 03:55:21'),
(10, '2025/11/12/00001', 6, 22, 1, 5, NULL, 'Rawat Jalan', '2025-11-12', '2025-11-12 01:56:00', 'migran', 0, NULL, '2025-11-12 01:56:18', '2025-11-12 01:56:18'),
(11, '2025/11/13/00001', 6, 22, 1, 5, NULL, 'Rawat Jalan', '2025-11-13', '2025-11-13 12:36:00', 'sss', 0, NULL, '2025-11-13 12:36:40', '2025-11-13 12:36:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
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
(18, '2025_09_18_110209_create_berkas_medis_table', 1),
(19, '2025_11_03_073321_add_avatar_path_to_users_table', 2),
(20, '2025_11_11_033344_add_advice_to_soap_table', 3),
(21, '2025_11_11_075458_add_vitals_to_soap', 4),
(22, '2025_11_11_081154_add_soap_id_and_kategori_to_berkas_medis', 5),
(23, '2025_11_13_024841_create_rujukan_dokter_cc_table', 6),
(24, '0001_01_01_000003_create_password_reset_tokens_table', 7),
(25, '2026_04_10_171000_add_satusehat_fields_to_core_tables', 7),
(26, '2026_04_10_171100_create_konsultasi_tables', 7),
(27, '2026_04_10_190000_create_notifications_table', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('2f0760a9-a4c6-4dff-99e7-8b016eeb7bff', 'App\\Notifications\\KonsultasiActivityNotification', 'App\\Models\\User', 42, '{\"title\":\"Konsultasi perlu ditinjau\",\"message\":\"Konsultasi KON-20260411-0001 telah dikirim atau dialihkan kepada Anda.\",\"category\":\"consultation_submitted\",\"konsultasi_id\":3,\"no_konsultasi\":\"KON-20260411-0001\",\"status\":\"submitted\",\"actor_name\":\"Dokter Jaga\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/konsultasi\\/3\"}', '2026-04-10 20:18:11', '2026-04-10 20:17:36', '2026-04-10 20:18:11'),
('49588288-4c25-45b1-aa16-a9c8aa8cbd2d', 'App\\Notifications\\KonsultasiActivityNotification', 'App\\Models\\User', 22, '{\"title\":\"Konsultasi butuh info tambahan\",\"message\":\"dr. dr Ikhsan meminta info tambahan untuk konsultasi KON-20260411-0001.\",\"category\":\"consultation_reply\",\"konsultasi_id\":3,\"no_konsultasi\":\"KON-20260411-0001\",\"status\":\"awaiting_more_info\",\"actor_name\":\"dr Ikhsan\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/konsultasi\\/3\"}', '2026-04-10 20:19:10', '2026-04-10 20:18:35', '2026-04-10 20:19:10'),
('8eff2c26-f095-4a71-8727-987fb33faac9', 'App\\Notifications\\KonsultasiActivityNotification', 'App\\Models\\User', 22, '{\"title\":\"Konsultasi diterima\",\"message\":\"Konsultasi KON-20260411-0001 telah diterima oleh dr. dr Ikhsan.\",\"category\":\"consultation_accepted\",\"konsultasi_id\":3,\"no_konsultasi\":\"KON-20260411-0001\",\"status\":\"accepted\",\"actor_name\":\"dr Ikhsan\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/konsultasi\\/3\"}', '2026-04-10 20:19:11', '2026-04-10 20:18:07', '2026-04-10 20:19:11'),
('dfef113f-0ef2-43ac-a3db-302a177193b2', 'App\\Notifications\\KonsultasiActivityNotification', 'App\\Models\\User', 42, '{\"title\":\"Pesan baru pada konsultasi\",\"message\":\"dr. Dokter Jaga mengirim pesan baru pada konsultasi KON-20260411-0001.\",\"category\":\"consultation_reply\",\"konsultasi_id\":3,\"no_konsultasi\":\"KON-20260411-0001\",\"status\":\"in_discussion\",\"actor_name\":\"Dokter Jaga\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/konsultasi\\/3\"}', NULL, '2026-04-10 20:19:29', '2026-04-10 20:19:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `no_rkm_medis`, `nik`, `patient_ihs_number`, `nama`, `tanggal_lahir`, `tempat_lahir`, `alamat`, `jenis_kelamin`, `telepon`, `created_at`, `updated_at`) VALUES
(1, '000001', '180310890017340812', NULL, 'sabeni', '2025-10-25', 'bandung', 'bandung', 'L', '0887393121432', '2025-10-25 02:21:22', '2025-10-25 02:21:22'),
(2, '000002', '1234567891', NULL, 'Ny. A', '1999-10-30', 'Pariaman', 'Dharmasraya', 'P', '00000””', '2025-10-29 17:27:05', '2025-10-29 17:27:05'),
(3, '000003', '18031017000578', NULL, 'Nafasya', '2023-09-01', 'kotabumi', 'Padang', 'P', '0811000345', '2025-11-04 05:36:17', '2025-11-04 05:36:17'),
(4, '000004', '99999', NULL, 'ny. x', '2009-02-03', 'dhrmsry', 'jl. mmmmmmmmmmm', 'P', '098766', '2025-11-05 01:09:40', '2025-11-05 01:09:40'),
(5, '000005', '888888', NULL, 'Ny. Apo', '2010-01-06', 'Dharmasraya', 'Dharmasraya', 'P', '01234', '2025-11-06 03:11:40', '2025-11-06 03:11:40'),
(6, '000006', '18080', NULL, 'aswin', '2014-02-06', 'dharmasra', 'jln....', 'P', '0000', '2025-11-06 04:13:50', '2025-11-06 04:13:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rujukan`
--

CREATE TABLE `rujukan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `origin_konsultasi_id` bigint(20) UNSIGNED DEFAULT NULL,
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
-- Dumping data untuk tabel `rujukan`
--

INSERT INTO `rujukan` (`id`, `kunjungan_id`, `origin_konsultasi_id`, `rumah_sakit_asal_id`, `rumah_sakit_tujuan_id`, `alasan`, `catatan`, `dokter_tujuan_id`, `alasan_rujukan`, `status`, `catatan_penerima`, `created_at`, `updated_at`, `penerima_id`) VALUES
(25, 3, NULL, 4, 3, 'test rujukan dan email pasien', 'test rujukan dan email pasien', 15, 'test rujukan dan email pasien', 'diterima', NULL, '2025-11-01 01:35:17', '2025-11-01 03:40:15', 15),
(26, 3, NULL, 3, 4, 'Preeklampsia berat', 'Membutuhkan intervensi lanjutan', 11, 'Penurunan kesadaran', 'diterima', NULL, '2025-11-01 03:44:04', '2025-11-03 05:40:43', 11),
(27, 2, NULL, 4, 3, 'peb berat banget', 'butuh enjoy', 16, 'penurunan kesadaran', 'menunggu', NULL, '2025-11-03 05:40:19', '2025-11-03 05:40:19', NULL),
(28, 3, NULL, 4, 3, 'PEB banget', 'butuh rilex dan enjoy', 21, 'damai gak jd cerai', 'menunggu', NULL, '2025-11-03 05:42:13', '2025-11-03 05:42:13', NULL),
(29, 3, NULL, 4, 3, 'PEB severe features', 'Butuh tatalaksana lanjutan dan ICU', 18, 'Penurunan kesadaran', 'menunggu', NULL, '2025-11-03 05:49:25', '2025-11-03 05:49:25', NULL),
(30, 5, NULL, 5, 3, 'sarana  dan prasarana', 'membutuhkan ICU', 15, 'penurun kesadaran', 'menunggu', NULL, '2025-11-04 05:48:26', '2025-11-04 05:48:26', NULL),
(31, 5, NULL, 4, 3, 'Ruangan Penuh', 'butuh ICU', 15, 'penurunan kesadaran', 'menunggu', NULL, '2025-11-04 06:10:44', '2025-11-04 06:10:44', NULL),
(32, 5, NULL, 5, 3, 'test 2 kirim rujukan', NULL, 15, '-', 'menunggu', NULL, '2025-11-04 06:15:58', '2025-11-04 06:15:58', NULL),
(33, 5, NULL, 5, 4, 'test coba pasien', NULL, 11, 'test coba pasien', 'menunggu', NULL, '2025-11-04 06:18:25', '2025-11-04 06:18:25', NULL),
(36, 5, NULL, 4, 3, 'PEB dengan severe features', 'Butuh ICU', 15, 'Penurunan kesadaran', 'menunggu', NULL, '2025-11-05 01:07:28', '2025-11-05 01:07:28', NULL),
(37, 6, NULL, 4, 3, 'peb severe features', 'butuh  icu', 13, 'penurunan lkesadaran', 'menunggu', NULL, '2025-11-05 03:52:35', '2025-11-05 03:52:35', NULL),
(38, 7, NULL, 4, 3, 'iiii', 'iii', 15, 'iii', 'diterima', NULL, '2025-11-06 03:49:52', '2025-11-06 03:51:40', 15),
(39, 8, NULL, 3, 5, 'PEB dengan Svere features', 'Butuh ICU', 10, 'Penurunan kesadaran', 'menunggu', NULL, '2025-11-06 04:16:31', '2025-11-06 04:16:31', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rujukan_dokter_cc`
--

CREATE TABLE `rujukan_dokter_cc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rujukan_id` bigint(20) UNSIGNED NOT NULL,
  `dokter_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rumah_sakit`
--

CREATE TABLE `rumah_sakit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `organization_ihs_number` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rumah_sakit`
--

INSERT INTO `rumah_sakit` (`id`, `nama`, `organization_ihs_number`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
(3, 'RSUP Dr. M. Djamil Padang', NULL, 'Jl. Perintis Kemerdekaan, Sawahan Tim., Kec. Padang Tim., Kota Padang, Sumatera Barat 25129', '07518956666', '2025-10-26 01:32:31', '2025-10-26 01:36:44'),
(4, 'RSUD Sungai Dareh', NULL, 'Jl. Lintas Sumatera No.KM.2, Empat Koto, Kec. Pulau Punjung, Kabupaten Dharmasraya, Sumatera Barat 27614', '075440347', '2025-10-26 01:36:34', '2025-10-26 01:36:34'),
(5, 'Rumah Sakit Universitas Andalas', NULL, 'Universitas Andalas, Komplek Kampus Jl. Limau Manis, Limau Manis, Kec. Pauh, Kota Padang, Sumatera Barat 25176', '07518465000', '2025-10-26 01:37:29', '2025-10-26 01:37:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rumah_sakits`
--

CREATE TABLE `rumah_sakits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('brL7wsbHx3sTQLzti4D3RJ2gBea53BbhVeiswlbA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemo3Smw0UzN5cmFDMkFKMVdkOWY4M25XUVRLRnBXcVlSUEZYb3VHayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1775877031),
('s5VgFKKyatuCoea3W1yIgJsxUoBuxgEudWYXpyol', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTHlKa2hSSGc2eHNROThrWDF3NVJaZERFRVlHQjFSSnBzbklrYUFiNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAva29uc3VsdGFzaSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIyO30=', 1775877582),
('t4Rbhz12FEM4VakRdvt9O9LBraVrpM2IfQ2PeIZE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXY1N0xsdVdEWXlwYXlOWVZldjZMWGpxbUcxMlpBc3BTWEpZZ0x0aiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1775877017);

-- --------------------------------------------------------

--
-- Struktur dari tabel `soap`
--

CREATE TABLE `soap` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunjungan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subjektif` text DEFAULT NULL,
  `objektif` text DEFAULT NULL,
  `td_sys` smallint(5) UNSIGNED DEFAULT NULL,
  `td_dia` smallint(5) UNSIGNED DEFAULT NULL,
  `map` smallint(5) UNSIGNED DEFAULT NULL,
  `assessment` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `soap`
--

INSERT INTO `soap` (`id`, `kunjungan_id`, `user_id`, `subjektif`, `objektif`, `td_sys`, `td_dia`, `map`, `assessment`, `plan`, `advice`, `created_at`, `updated_at`) VALUES
(5, 6, 11, 'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb', 'Pemeriksaan fisik :\r\n- Kesadaran: compos mentis / …\r\n- TD: …/… mmHg, N: …/menit, RR: …/menit, Suhu: …°C\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …', NULL, NULL, NULL, 'Diagnosis :\r\n- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya\r\nDiagnosis banding: …', 'Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga', NULL, '2025-11-05 01:13:59', '2025-11-05 01:13:59'),
(6, 6, 15, 'Keluhan utama: …\r\nRiwayat sekarang :\r\n- —\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb', 'Pemeriksaan fisik :\r\n- Kesadaran: compos mentis / …\r\n- TD: …/… mmHg, N: …/menit, RR: …/menit, Suhu: …°C\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …', NULL, NULL, NULL, 'Diagnosis :\r\n- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya\r\nDiagnosis banding: …', 'Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga\r\n\r\n\r\nDarat ?', NULL, '2025-11-05 02:12:38', '2025-11-05 03:56:00'),
(7, 7, 11, 'Riwayat sekarang :\r\n- Sakit kepala / Pandangan kabur', 'KU : Baik\r\nKes : CM\r\nTD : 170/100\r\nHR : 80x/menit\r\nRR : 20x/menit', NULL, NULL, NULL, 'Hamil 34-35 minggu dgn PEB severe features', 'Obs TVI dan DJJ\r\nCek lab Lengkap\r\nIVFD -----', NULL, '2025-11-06 03:14:04', '2025-11-06 03:14:04'),
(8, 8, 15, 'Keluhan utama: …\r\nRiwayat sekarang :\r\n- Sakit kepala / Pandangan kabur / Nyeri epigastrium\r\n\r\nRiwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada\r\nRiwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak\r\nFaktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb', 'Pemeriksaan fisik :\r\n- Kesadaran: compos mentis / …\r\n- TD: …/… mmHg, N: …/menit, RR: …/menit, Suhu: …°C\r\n- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam\r\nPenunjang :\r\n- Protein urin: …\r\n- Trombosit: …\r\n- SGOT/SGPT: …\r\n- Kreatinin: …\r\n- LDH: …\r\n- USG/NST/BPP: …', NULL, NULL, NULL, 'Diagnosis :\r\n- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya\r\nDiagnosis banding: …', 'Rencana :\r\n- Rawat inap / ICU bila indikasi\r\n- MgSO₄ sesuai protokol; monitor refleks & diuresis\r\n- Antihipertensi (Labetalol/Nifedipin/Hydralazin)\r\n- Serial lab fungsi hati, ginjal, darah\r\n- Penilaian janin (USG/NST/BPP)\r\n- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin\r\n- Edukasi keluarga', NULL, '2025-11-06 04:15:47', '2025-11-06 04:15:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `practitioner_ihs_number` varchar(255) DEFAULT NULL,
  `satusehat_practitioner_role_id` varchar(255) DEFAULT NULL,
  `spesialisasi` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dokter','perawat','admin') NOT NULL DEFAULT 'dokter',
  `rumah_sakit_id` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `practitioner_ihs_number`, `satusehat_practitioner_role_id`, `spesialisasi`, `avatar_path`, `email_verified_at`, `password`, `role`, `rumah_sakit_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin RSUNAD', 'andalasfetolink@gmail.com', NULL, NULL, NULL, 'avatars/KrQEG1mU9baRdYMaT2NoYJh9rlBE9lZLv9KDq3HH.png', NULL, '$2y$12$sheY3JRr3lQ4Ob.NIQPLu.9SX5aT44tQMuHJAF805Am8k1mpZw9rC', 'admin', 5, NULL, '2025-10-25 02:11:22', '2025-11-03 10:30:16'),
(8, 'Admin RSUD Sungai Dareh', 'sungaidareh@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$zppO/f/RtfSxGlfM4fcVzuZIQbH9Plrdor4d6RQFdvYcJuoFy/KbS', 'admin', 4, NULL, '2025-10-30 02:05:29', '2025-10-30 02:05:29'),
(9, 'Admin RS M. Djamil Padang', 'rsmjamil@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$oq.uxVCWLBiP8N/z8OFgF.fKXiTKcsouLasmZd7kchGRzzSmjzk/2', 'admin', 3, NULL, '2025-10-30 02:06:26', '2025-11-03 05:58:37'),
(10, 'dr. Rina Gustuti, SpOG,Subs-KFM', 'rinagustuti2308@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$uWgRuVvDrqi0yHxOHD9DYe6wQ3EihX/n4GynHjqC6QgXDMGiERmc.', 'dokter', 5, '46yMj1UmmLmXVB7szcxDzlHQp2qK4sgNI3cqMyePPsOGqKea64CoCp1IQ04a', '2025-10-30 02:08:36', '2025-10-30 02:08:36'),
(11, 'dr. Pom Harry Satria, SpOG,Subs-Obsos', 'harrysatria.pom@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$NGAeR3v4Qb7oxobPOrvRk.ncAki1D5k2cdaHUhyPR4/g6tdrl10lq', 'dokter', 4, 'KKgnFqWnEeKKGmrdAYlgXwTlD6xYFsTVb4y04cku9lKWR6HByAYkzyLbjLnd', '2025-10-30 02:09:50', '2025-10-30 06:01:30'),
(12, 'dr. Widayat, SpOG', 'dokterwidayat@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$q1prKYm4Qg3YiH7MBIYlgOftJnszqVFtHziHifZlD1AIVK/Jy61Yq', 'dokter', 4, NULL, '2025-10-30 02:11:26', '2025-10-30 06:02:17'),
(13, 'Prof. Dr. dr. Yusrawati, SpOG, Subs-KFM, MARS, M.H', 'yusrawati_65@yahoo.co.id', NULL, NULL, NULL, NULL, NULL, '$2y$12$D.OBrK9qqo.ju84PxVBfd.bpWnahJ.tJKD879sHZQ9M8JagltibFe', 'dokter', 3, '2kkA9QoJXI1iruKtWLw9l8krLRyZorw6T6Jf2oOLDDyg1svq050FWP2ceWR8', '2025-10-30 02:12:43', '2025-11-03 06:11:52'),
(14, 'Dr. dr. Vauline Basyir, SpOG, Subs-KFM', 'vaulinne@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$5ML7Ox2CKMqC2QB8gA7u/OJyR9meoXGlEYaebqZV8fTVjfx3SD4QO', 'dokter', 3, NULL, '2025-10-30 02:14:57', '2025-10-30 06:04:31'),
(15, 'dr. Aswin Boy,SpOG (Trainee)', 'aswinboy27@gmail.com', NULL, NULL, NULL, 'avatars/dE7dcB9YwL99ECJ3dc39LkTQxd3nENO69fW1z9K8.jpg', NULL, '$2y$12$8yGpC9tbkd8kRdTvh/57g.ToFgFtR1X9f7svP.TtxRt6sj5ost1LG', 'dokter', 3, 'eLwnkTt2ILn5dOkwDfHsT7EwdomDIE7QkTxD63cVjNKXVPJlkigByUuBXNjM', '2025-10-30 02:16:19', '2025-11-05 01:03:59'),
(16, 'dr. Subhan, SpOG (Trainee)', 'subhan.38@yahoo.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$s7nJ8VN/DtMsqRdekDItgeb1KR5GtnHP9OQX0.cl4ihuuuFkzjsXi', 'dokter', 3, NULL, '2025-10-30 02:17:43', '2025-10-30 06:14:19'),
(17, 'dr. Zeino,SpOG (Trainee)', 'zeino_fridsto@yahoo.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$FiogMlvOwIkbnQO8rCLfIelzjjjRr.haD.UWqisnp.sSmHfQZd7iO', 'dokter', 3, NULL, '2025-10-30 02:18:55', '2025-10-30 06:15:00'),
(18, 'dr. Dian, SpOG (Trainee)', 'diannoviyantyspog@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$e4JNWqBs3huaXt9gVcOkv.2ePVKdSjzul9k.KzrDpgB6sQIUBVUQm', 'dokter', 3, NULL, '2025-10-30 02:20:07', '2025-10-30 06:15:56'),
(19, 'dr. Edward, SpOG (Trainee)', 'edh121278@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$g3IAeEVKyKkrhUPi.uEUcOjxDkqDlqrY3EaEtiQPCrEKjOcNiMAo6', 'dokter', 3, NULL, '2025-10-30 02:20:58', '2025-10-30 06:16:25'),
(20, 'dr. Bobby,SpOG (Trainee)', 'bobbyfitriantoni65@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$VM2Ak8zczQvOPzE/ZLLmSueZO7zlCvlgIDUXJYKcIZ8zRnBvJ3nW6', 'dokter', 3, NULL, '2025-10-30 02:22:16', '2025-10-30 06:16:49'),
(21, 'dr. Irfan Kurnia, SpOG (Trainee)', 'dokterirfankurnia@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$4i6nJoalpz29IhDvFnx0huEF33qkWjtlPdl2vCWtoSD/DAoIfJiuy', 'dokter', 3, NULL, '2025-10-30 06:10:57', '2025-10-30 06:17:18'),
(22, 'Dokter Jaga', 'dokterrsudsungaidareh@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$rvscW6rrlcZW7NBNmil82uuaoqN4/odYYmUrIcEAXtd/mQRh2eiXO', 'dokter', 4, NULL, '2025-10-30 06:19:02', '2025-10-30 06:19:02'),
(23, 'Dokter Jaga', 'dokterrsupdjamil@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$oTY31k2pOgrrxa2tN/SHE.do2l3l3N3huoEYDahSlQCjUHAs8U14e', 'dokter', 3, NULL, '2025-10-30 06:20:02', '2025-10-30 06:20:02'),
(24, 'Dokter Jaga', 'dokterrsunpad@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$HURVXG28qBrxlDSQAhSUT.Q0B5aCiM3ml2x5vV34Zh4znoswUlLy2', 'dokter', 5, NULL, '2025-10-30 06:20:59', '2025-10-30 06:20:59'),
(25, 'PONEK', 'ponekrsudsungaidareh@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$vhFsp.scsoUCPu7ar5UqmexYNWVGa4LalBZZJMjjWukoSdDttXASu', 'perawat', 4, NULL, '2025-10-30 06:22:08', '2025-10-30 06:22:08'),
(26, 'PONEK 1', 'ponek1@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$Q09cZgzywqbiVBoVUpdhHO.xwaj0SmY20MbE/PpSxrphzNRaPdfg2', 'perawat', 3, NULL, '2025-10-30 07:01:06', '2025-10-30 07:01:06'),
(27, 'PONEK 3', 'ponekuniversitasandalas@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$aJPMU8nLjz2qd216BZbsE.E9YeMYhPTxCa4eSf9zStUj24N3Cpt8u', 'perawat', 5, NULL, '2025-10-30 07:02:16', '2025-10-30 07:02:16'),
(28, 'laboratorium', 'laboratoriumsungaidareh@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$ZaViNW6tQ.qhjmOF3zVV/efAjCANfUBOVidSuiVW21SNREBrpd80m', 'perawat', 4, NULL, '2025-10-30 07:04:19', '2025-10-30 07:04:19'),
(29, 'laboratorium 1', 'laboratoriumrsupdjamilpadang@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$Td2zOBcWMQN.xyeSxf3JYeX/R9ZUINffppzp8BUohNaSFNlN4.A.6', 'perawat', 3, NULL, '2025-10-30 07:07:21', '2025-10-30 07:07:21'),
(30, 'laboratorium 2', 'laboratoriumuniversitasandalas@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$u5WD2XZHnmkyolNRWZep2upKZMyyXv.X/1jOhXMnsBfuHBgGyV.IS', 'perawat', 5, NULL, '2025-10-30 07:08:03', '2025-10-30 07:08:03'),
(31, 'RADIOLOGI', 'radiologisungaidareh@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$o/3J5k8Bji5bpMtV.sA86OrVcjHO50k33W.VdaBoA/N9SMrhyuSJe', 'perawat', 4, NULL, '2025-10-30 07:09:04', '2025-10-30 07:09:04'),
(32, 'RADIOLOGI 1', 'radiologirsupdjamilpadang@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$fVwhGouzuAeGfOsSFxUXnOCo3TzS3YMVV3fN9MQcNqY2hldDDFKuO', 'perawat', 3, NULL, '2025-10-30 07:10:38', '2025-10-30 07:10:38'),
(33, 'RADIOLOGI 2', 'radiologiuniversitasandalas@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$lPrToAksM53MiSZxNOPFD.YBMJl3Wd1CaZJqzyCa9ftI9YCEAz5qW', 'perawat', 5, NULL, '2025-10-30 07:11:42', '2025-10-30 07:11:42'),
(34, 'Dr.dr. Dovy Djanas, SpOG, Subs-KFM', 'dovy.dj68@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$n03LrHGZ9Ya8YFuXsBSb..uJRgQzdYeCTLZXsQULwHrw7Y/rPWQQi', 'dokter', 3, NULL, '2025-11-03 06:03:53', '2025-11-03 06:03:53'),
(35, 'Dr.dr. Defrin, SpOG, Subs-KFM', 'defrin_pdg@yahoo.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$o1jvC2yzo9Uc3yveIwTwHO4fPpAzuhulRcRA14m.tAVv6wJPs0DzS', 'dokter', 3, NULL, '2025-11-03 06:05:02', '2025-11-03 06:05:02'),
(36, 'Dr. dr. Roza Sriyanti, SpOG, Subs-KFM', 'rozasyahrial@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$4YZnWmE1snCVzfklual2deohp.mmgI/hHv5vrVW2qTjRCkTk5AEFC', 'dokter', 3, NULL, '2025-11-03 06:06:31', '2025-11-03 06:06:31'),
(37, 'Dr. dr. Joserizal Serudji, SpOG, Subs-KFM', 'jrserudji@yahoo.co.id', NULL, NULL, NULL, NULL, NULL, '$2y$12$hx965EyybsO0TFzT34jcz.krU1yLPkkcY0Pei4PkomGgZWfO2rNBC', 'dokter', 5, NULL, '2025-11-03 06:07:38', '2025-11-03 06:07:38'),
(38, 'Dr. dr. Hudila Rifa Karmia, SpOG, Subs K-FM', 'hrkspogdr@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$wSnmglo9SyKM90cL3dUOoOelz5BU2HZHtDvn6ynj6Fp8vZOMwZcOG', 'dokter', 5, NULL, '2025-11-03 06:08:41', '2025-11-03 06:08:41'),
(40, 'Dokter Jaga2', 'sungaidareh2@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$B8IfSsPPQCY.DDrEqlOkeu6bK4NB3Ge3NM.oSjDVtohuxorVmVW0W', 'dokter', 4, NULL, '2025-11-04 09:03:57', '2025-11-04 09:03:57'),
(41, 'Dokter Jaga3', 'sungaidareh3@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$JAodk6gf/NPiuBW3eZMfieo/pcC7XJ0u0F5Ih8zrMa8ZJpIejTnBq', 'dokter', 4, NULL, '2025-11-13 01:43:28', '2025-11-13 01:43:28'),
(42, 'dr Ikhsan', 'ikhsanp34@gmail.com', 'Jantung', '1', 'Jantung', NULL, NULL, '$2y$12$rXYoLe9CsOYAb0HtwuslrepIIiwqrcdItedkmq./DF/FDLH/woopm', 'dokter', 3, NULL, '2026-04-10 07:13:04', '2026-04-10 07:13:04');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `berkas_medis`
--
ALTER TABLE `berkas_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berkas_medis_kunjungan_id_foreign` (`kunjungan_id`),
  ADD KEY `berkas_medis_uploader_id_foreign` (`uploader_id`),
  ADD KEY `berkas_medis_soap_id_foreign` (`soap_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konsultasi_no_konsultasi_unique` (`no_konsultasi`),
  ADD KEY `konsultasi_kunjungan_id_index` (`kunjungan_id`),
  ADD KEY `konsultasi_pasien_id_index` (`pasien_id`),
  ADD KEY `konsultasi_rumah_sakit_asal_id_index` (`rumah_sakit_asal_id`),
  ADD KEY `konsultasi_rumah_sakit_tujuan_id_index` (`rumah_sakit_tujuan_id`),
  ADD KEY `konsultasi_dokter_pengirim_id_index` (`dokter_pengirim_id`),
  ADD KEY `konsultasi_dokter_tujuan_id_index` (`dokter_tujuan_id`),
  ADD KEY `konsultasi_status_index` (`status`);

--
-- Indeks untuk tabel `konsultasi_audit_logs`
--
ALTER TABLE `konsultasi_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konsultasi_audit_logs_konsultasi_id_index` (`konsultasi_id`),
  ADD KEY `konsultasi_audit_logs_actor_user_id_index` (`actor_user_id`),
  ADD KEY `konsultasi_audit_logs_event_type_index` (`event_type`);

--
-- Indeks untuk tabel `konsultasi_pesan`
--
ALTER TABLE `konsultasi_pesan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konsultasi_pesan_konsultasi_id_index` (`konsultasi_id`),
  ADD KEY `konsultasi_pesan_pengirim_id_index` (`pengirim_id`);

--
-- Indeks untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kunjungan_no_rawat_unique` (`no_rawat`),
  ADD KEY `kunjungan_satusehat_encounter_id_index` (`satusehat_encounter_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pasien_no_rkm_medis_unique` (`no_rkm_medis`),
  ADD UNIQUE KEY `pasien_nik_unique` (`nik`),
  ADD KEY `pasien_patient_ihs_number_index` (`patient_ihs_number`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `rujukan`
--
ALTER TABLE `rujukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rujukan_penerima_id_foreign` (`penerima_id`),
  ADD KEY `rujukan_origin_konsultasi_id_index` (`origin_konsultasi_id`);

--
-- Indeks untuk tabel `rujukan_dokter_cc`
--
ALTER TABLE `rujukan_dokter_cc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rujukan_dokter_cc_rujukan_id_dokter_id_unique` (`rujukan_id`,`dokter_id`),
  ADD KEY `rujukan_dokter_cc_dokter_id_foreign` (`dokter_id`);

--
-- Indeks untuk tabel `rumah_sakit`
--
ALTER TABLE `rumah_sakit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rumah_sakit_organization_ihs_number_index` (`organization_ihs_number`);

--
-- Indeks untuk tabel `rumah_sakits`
--
ALTER TABLE `rumah_sakits`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `soap`
--
ALTER TABLE `soap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `soap_kunjungan_id_foreign` (`kunjungan_id`),
  ADD KEY `soap_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_practitioner_ihs_number_index` (`practitioner_ihs_number`),
  ADD KEY `users_sat_role_id_idx` (`satusehat_practitioner_role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `berkas_medis`
--
ALTER TABLE `berkas_medis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `konsultasi_audit_logs`
--
ALTER TABLE `konsultasi_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `konsultasi_pesan`
--
ALTER TABLE `konsultasi_pesan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `rujukan`
--
ALTER TABLE `rujukan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `rujukan_dokter_cc`
--
ALTER TABLE `rujukan_dokter_cc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `rumah_sakit`
--
ALTER TABLE `rumah_sakit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `rumah_sakits`
--
ALTER TABLE `rumah_sakits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `soap`
--
ALTER TABLE `soap`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `berkas_medis`
--
ALTER TABLE `berkas_medis`
  ADD CONSTRAINT `berkas_medis_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `berkas_medis_soap_id_foreign` FOREIGN KEY (`soap_id`) REFERENCES `soap` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `berkas_medis_uploader_id_foreign` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rujukan`
--
ALTER TABLE `rujukan`
  ADD CONSTRAINT `rujukan_penerima_id_foreign` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rujukan_dokter_cc`
--
ALTER TABLE `rujukan_dokter_cc`
  ADD CONSTRAINT `rujukan_dokter_cc_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rujukan_dokter_cc_rujukan_id_foreign` FOREIGN KEY (`rujukan_id`) REFERENCES `rujukan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `soap`
--
ALTER TABLE `soap`
  ADD CONSTRAINT `soap_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `soap_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
