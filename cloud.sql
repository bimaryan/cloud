-- --------------------------------------------------------
-- Host:                         db-9e8265e5-49d0-49c5-a75b-3fdf0a2421cb.ap-southeast-1.public.db.laravel.cloud
-- Versi server:                 8.0.39-30.1 - Percona XtraDB Cluster (GPL), Release rel30, Revision 46271a0, WSREP version 26.1.4.3
-- OS Server:                    Linux
-- HeidiSQL Versi:               12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk master
CREATE DATABASE IF NOT EXISTS `master` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `master`;

-- membuang struktur untuk table master.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.cache: ~2 rows (lebih kurang)
REPLACE INTO `cache` (`key`, `value`, `expiration`) VALUES
	('cloud_cache_bimagaminh@gmail.com|103.148.130.116', 'i:1;', 1742937033),
	('cloud_cache_bimagaminh@gmail.com|103.148.130.116:timer', 'i:1742937033;', 1742937033);

-- membuang struktur untuk table master.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.cache_locks: ~0 rows (lebih kurang)

-- membuang struktur untuk table master.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Membuang data untuk tabel master.failed_jobs: ~0 rows (lebih kurang)

-- membuang struktur untuk table master.files
CREATE TABLE IF NOT EXISTS `files` (
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `folder_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `files_uuid_unique` (`uuid`),
  KEY `files_user_id_foreign` (`user_id`),
  KEY `files_folder_id_foreign` (`folder_id`),
  CONSTRAINT `files_folder_id_foreign` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.files: ~10 rows (lebih kurang)
REPLACE INTO `files` (`uuid`, `id`, `name`, `path`, `mime_type`, `user_id`, `folder_id`, `created_at`, `updated_at`) VALUES
	('52fe5046-c55f-496c-99c2-1ae931dbd38c', 1, 'IMG_3712.jpeg', 'uploads/1/mdPIz1NKmwmm0sDjLVbpbVsjPxmq8UYvtJlwlmec.jpg', 'image/jpeg', 1, 3, '2025-03-26 03:53:11', '2025-03-26 03:53:11'),
	('a0cf4041-8bc9-4d9d-81f5-2919fabf2b5f', 2, 'Idgitaf - Satu-Satu (Official Music Video).mp3', 'uploads/1/UHBtvgY3nOQKdw8sDkvo4Jsmm3l9ZpbabCOnRcBz.mp3', 'audio/mpeg', 1, 4, '2025-03-26 03:59:44', '2025-03-26 03:59:44'),
	('b664da31-35d9-4895-848d-3851ba06222b', 3, 'IMG-20250320-WA0090.jpg', 'uploads/1/BUmD1DV5QZTbaD1vqV7qtcChjENT6DInx1Kif80f.jpg', 'image/jpeg', 1, 3, '2025-03-26 04:35:20', '2025-03-26 04:35:20'),
	('ea7e9c91-33ff-494d-a6bd-97679189e320', 4, 'IMG-20250317-WA0117.jpg', 'uploads/1/da59N2snCuoQj0pHpMnOem9zpUNEzlflncJ8WXdI.jpg', 'image/jpeg', 1, 11, '2025-03-26 04:38:01', '2025-03-26 04:38:01'),
	('bc397bed-5b30-4373-bfba-01d30202570e', 5, 'IMG-20250312-WA0008.jpg', 'uploads/1/acz6dMoIdLHrzYkJsgzotvWGgl5AjNH2RhVckYDX.jpg', 'image/jpeg', 1, 11, '2025-03-26 04:40:00', '2025-03-26 04:40:00'),
	('46d98d13-bc58-435c-a9a8-d17922b2b69a', 6, 'IMG-20250311-WA0093.jpg', 'uploads/1/6EuA2W2hIv46sa4OszdptGPRFAjlsmZXtOtLMR8o.jpg', 'image/jpeg', 1, 11, '2025-03-26 04:41:25', '2025-03-26 04:41:25'),
	('3cb4baa9-bfdd-4549-81e4-d79a2a268b92', 7, 'ssstik.io_1734715521743.mp3', 'uploads/1/F9SwkGZ3ydKe5qVFyih7Fzkq8aEeQUj3MZrpG4zp.mp3', 'audio/mpeg', 1, 4, '2025-03-26 04:42:22', '2025-03-26 04:42:22'),
	('c3d229fc-9fbf-42a5-a428-0b13c593db0c', 8, 'IMG-20250311-WA0088.jpg', 'uploads/1/Nl45kA3IfXrO354L9PSWTbOm6mkuDItaGFQM4nC4.jpg', 'image/jpeg', 1, 11, '2025-03-26 04:43:09', '2025-03-26 04:43:09'),
	('8fdf155e-a8aa-41f7-8ef4-ef3b43ec5e8e', 11, 'AUD-20230712-WA0084.mp3', 'uploads/1/eCPp9RlpxSToOSPQAMt5HvsXPasXyNG5slgUp0G7.mp3', 'audio/mpeg', 1, 4, '2025-03-26 04:55:37', '2025-03-26 04:55:37'),
	('01f0cf9d-0aa9-4296-afe9-02268413043a', 12, 'ai_repair_20250317234225973.jpeg', 'uploads/1/5TmV7ZrpW19fJXOO1MIyK0YtIHi1A3dA2R4skARi.jpg', 'image/jpeg', 1, 11, '2025-03-26 05:00:52', '2025-03-26 05:00:52');

-- membuang struktur untuk table master.folders
CREATE TABLE IF NOT EXISTS `folders` (
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `folders_uuid_unique` (`uuid`),
  KEY `folders_user_id_foreign` (`user_id`),
  KEY `folders_parent_id_foreign` (`parent_id`),
  CONSTRAINT `folders_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `folders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.folders: ~11 rows (lebih kurang)
REPLACE INTO `folders` (`uuid`, `id`, `name`, `user_id`, `parent_id`, `created_at`, `updated_at`) VALUES
	('45a029fa-8ea2-4363-9588-89c0fd34cea6', 1, 'Downloads', 1, NULL, '2025-03-26 03:38:38', '2025-03-26 03:38:38'),
	('8f7981dd-870e-4f0f-a785-6646aa019b09', 2, 'Documents', 1, NULL, '2025-03-26 03:38:38', '2025-03-26 03:38:38'),
	('56f64e23-c4ca-4723-a2e0-a2f91b640aa4', 3, 'Image', 1, NULL, '2025-03-26 03:38:38', '2025-03-26 03:38:38'),
	('9bfa724e-a3b0-4f24-92ae-a1822b1f6e94', 4, 'Music', 1, NULL, '2025-03-26 03:38:38', '2025-03-26 03:38:38'),
	('68bd2a17-428c-4594-8666-a7d6cee16f84', 5, 'Video', 1, NULL, '2025-03-26 03:38:38', '2025-03-26 03:38:38'),
	('27bf2589-3f8f-4986-a316-8017c4ab7965', 6, 'Downloads', 2, NULL, '2025-03-26 04:30:05', '2025-03-26 04:30:05'),
	('a110aa2f-41f5-4237-a209-7a3408041ef0', 7, 'Documents', 2, NULL, '2025-03-26 04:30:05', '2025-03-26 04:30:05'),
	('a67b1233-c0be-43f9-9b49-70b96a099956', 8, 'Images', 2, NULL, '2025-03-26 04:30:05', '2025-03-26 04:30:05'),
	('f6e34e55-922c-4660-b08a-4347a2cef99c', 9, 'Music', 2, NULL, '2025-03-26 04:30:05', '2025-03-26 04:30:05'),
	('028773c5-ff39-4cf6-a6dc-3b36d71e2605', 10, 'Video', 2, NULL, '2025-03-26 04:30:05', '2025-03-26 04:30:05'),
	('3995f8ed-cebe-4ab0-ab1d-f2627a5f1108', 11, 'Bibubbb', 1, 3, '2025-03-26 04:36:37', '2025-03-26 04:36:37');

-- membuang struktur untuk table master.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
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

-- Membuang data untuk tabel master.jobs: ~0 rows (lebih kurang)

-- membuang struktur untuk table master.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.job_batches: ~0 rows (lebih kurang)

-- membuang struktur untuk table master.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.migrations: ~0 rows (lebih kurang)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_03_25_142905_create_folders_table', 1),
	(5, '2025_03_25_142911_create_files_table', 1);

-- membuang struktur untuk table master.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.password_reset_tokens: ~0 rows (lebih kurang)

-- membuang struktur untuk table master.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.sessions: ~0 rows (lebih kurang)

-- membuang struktur untuk table master.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel master.users: ~2 rows (lebih kurang)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Dearyz', 'bimaryan046@gmail.com', NULL, '$2y$12$Z14XAA2XBkZVd2yZYwt0pe9BcgCQL76zLcGf/AYzyfz11rn9tg9lG', 'MB96csdWvDyw6feJ3tDK4Spn21bsB0HJrkJ6PuHGs0TbZx4ySOrkZvtSsOzz', '2025-03-26 03:38:37', '2025-03-26 03:38:37'),
	(2, 'Fadhil anugrah', 'fageza3568@gmail.com', NULL, '$2y$12$hGvFxxHDTQZe4Yjdl87zgulZTuCBIH/avoqN.lvSb9rP8hQUJfppi', NULL, '2025-03-26 04:30:05', '2025-03-26 04:30:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
