-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 30, 2024 alle 22:08
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neurohubdb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `admins`
--

INSERT INTO `admins` (`id`, `created_at`, `updated_at`) VALUES
(1, '2024-09-28 10:01:24', '2024-09-28 10:01:24');

-- --------------------------------------------------------

--
-- Struttura della tabella `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `failed_jobs`
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
-- Struttura della tabella `image_questions`
--

CREATE TABLE `image_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scores`)),
  `jump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jump`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `image_question_results`
--

CREATE TABLE `image_question_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_question_id` bigint(20) UNSIGNED NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`value`)),
  `score` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `interviews`
--

CREATE TABLE `interviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `visit_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `diagnosis` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `jobs`
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

-- --------------------------------------------------------

--
-- Struttura della tabella `job_batches`
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
-- Struttura della tabella `meds`
--

CREATE TABLE `meds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_07_20_092441_create_meds_table', 1),
(5, '2024_07_20_092450_create_test_meds_table', 1),
(6, '2024_07_20_092526_create_admins_table', 1),
(7, '2024_07_30_143010_create_tests_table', 1),
(8, '2024_08_03_142245_create_sections_table', 1),
(9, '2024_08_05_151119_create_questions_table', 1),
(10, '2024_08_05_151437_create_specializedquestions_table', 1),
(11, '2024_08_08_191206_create_patients_table', 1),
(12, '2024_08_23_125537_create_interviews_table', 1),
(13, '2024_08_23_130655_create_visits_table', 1),
(14, '2024_09_01_162630_create_results_table', 1),
(15, '2024_09_02_163812_create_question_results_table', 1),
(16, '2024_09_12_101955_create_operation_on_score_table', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `multiple_questions`
--

CREATE TABLE `multiple_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`fields`)),
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scores`)),
  `jump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jump`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `multiple_questions`
--

INSERT INTO `multiple_questions` (`id`, `title`, `text`, `fields`, `scores`, `jump`, `created_at`, `updated_at`) VALUES
(1, 'Tipo di soggetto', 'Tipo di soggetto', '[\"Verbale\",\"Non verbale\"]', NULL, '[\"15\",\"14\"]', '2024-09-28 11:10:20', '2024-09-28 12:49:06'),
(2, 'Prima valutazione dei genitori', 'Età della prima valutazione dei genitori < 36 mesi', '[\"Si\",\"No\"]', '[\"1\",\"0\"]', NULL, '2024-09-28 11:31:17', '2024-09-28 12:53:35'),
(3, 'Prime parole', 'Età delle prime parole pronunciate > 24 mesi', '[\"Si\",\"No\"]', '[\"1\",\"0\"]', NULL, '2024-09-28 11:31:46', '2024-09-28 12:53:42'),
(4, 'Prime frasi', 'Età delle prime frasi > 33 mesi', '[\"Si\",\"No\"]', '[\"1\",\"0\"]', NULL, '2024-09-28 11:32:21', '2024-09-28 12:53:45'),
(5, 'Opinione Esaminatore', 'Età opinione dell\'esaminatore sull\'epoca in cui si sono manifestati i primi sintomi < 36 mesi', '[\"Si\",\"No\"]', '[\"1\",\"0\"]', NULL, '2024-09-28 11:35:40', '2024-09-28 12:53:48');

-- --------------------------------------------------------

--
-- Struttura della tabella `multiple_question_results`
--

CREATE TABLE `multiple_question_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `multiple_question_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `score` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `multiple_selection_questions`
--

CREATE TABLE `multiple_selection_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`fields`)),
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scores`)),
  `jump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jump`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `multiple_selection_question_results`
--

CREATE TABLE `multiple_selection_question_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `multiple_selection_question_id` bigint(20) UNSIGNED NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`value`)),
  `score` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `open_questions`
--

CREATE TABLE `open_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scores`)),
  `jump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jump`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `open_question_results`
--

CREATE TABLE `open_question_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `open_question_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `score` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `operation_on_scores`
--

CREATE TABLE `operation_on_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scorable_id` bigint(20) UNSIGNED NOT NULL,
  `scorable_type` varchar(255) NOT NULL,
  `formula` varchar(255) DEFAULT NULL,
  `conversion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conversion`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `operation_on_scores`
--

INSERT INTO `operation_on_scores` (`id`, `scorable_id`, `scorable_type`, `formula`, `conversion`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Test', NULL, NULL, '2024-09-28 11:46:18', '2024-09-28 11:46:18'),
(2, 6, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:46:09', '2024-09-28 12:46:09'),
(3, 7, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"3\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:47:12', '2024-09-28 12:47:12'),
(4, 8, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:47:41', '2024-09-28 12:47:41'),
(5, 9, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:48:12', '2024-09-28 12:48:12'),
(6, 5, 'App\\Models\\Section', 'S1+S2+S3+S4', NULL, '2024-09-28 12:48:26', '2024-09-28 12:48:26'),
(7, 15, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:49:37', '2024-09-28 12:49:37'),
(8, 16, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:50:06', '2024-09-28 12:50:06'),
(9, 14, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:50:32', '2024-09-28 12:50:32'),
(10, 17, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:51:00', '2024-09-28 12:51:00'),
(11, 10, 'App\\Models\\Section', 'S2+S3+S4+S5', NULL, '2024-09-28 12:51:26', '2024-09-28 12:51:26'),
(12, 19, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:51:52', '2024-09-28 12:51:52'),
(13, 20, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:52:13', '2024-09-28 12:52:13'),
(14, 21, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:52:34', '2024-09-28 12:52:34'),
(15, 22, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-28 12:52:56', '2024-09-28 12:52:56'),
(16, 18, 'App\\Models\\Section', 'S1+S2+S3+S4', NULL, '2024-09-28 12:53:19', '2024-09-28 12:53:19'),
(17, 23, 'App\\Models\\Section', 'Q1+Q2+Q3+Q4', NULL, '2024-09-28 12:54:03', '2024-09-28 12:54:03'),
(18, 2, 'App\\Models\\Test', 'S1+S2', NULL, '2024-09-30 18:00:38', '2024-09-30 18:07:28'),
(19, 24, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-30 18:04:08', '2024-09-30 18:04:08'),
(20, 25, 'App\\Models\\Section', NULL, '{\"0\":\"0\",\"1\":\"1\",\"2\":\"2\",\"3\":\"2\",\"7\":\"0\",\"8\":\"0\",\"9\":\"0\"}', '2024-09-30 18:06:10', '2024-09-30 18:06:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `progressive` int(11) NOT NULL,
  `questionable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `questionable_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `questions`
--

INSERT INTO `questions` (`id`, `section_id`, `progressive`, `questionable_id`, `questionable_type`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 1, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:49:41', '2024-09-28 10:50:31'),
(2, 6, 2, 2, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:50:36', '2024-09-28 10:51:07'),
(3, 6, 3, 3, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:51:11', '2024-09-28 10:51:55'),
(4, 7, 1, 4, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:52:05', '2024-09-28 10:52:36'),
(5, 7, 2, 5, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:52:41', '2024-09-28 10:54:49'),
(6, 7, 3, 6, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:55:12', '2024-09-28 10:55:46'),
(7, 7, 4, 7, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:57:11', '2024-09-28 10:59:39'),
(8, 8, 1, 8, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 10:59:51', '2024-09-28 11:00:24'),
(9, 8, 2, 9, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:00:29', '2024-09-28 11:00:47'),
(10, 8, 3, 10, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:00:54', '2024-09-28 11:01:37'),
(11, 9, 1, 11, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:02:19', '2024-09-28 11:04:41'),
(12, 9, 2, 12, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:04:46', '2024-09-28 11:05:06'),
(13, 9, 3, 13, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:05:12', '2024-09-28 11:05:31'),
(14, 9, 4, 14, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:05:38', '2024-09-28 11:06:00'),
(15, 9, 5, 15, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:06:07', '2024-09-28 11:06:27'),
(17, 13, 1, 1, 'App\\Models\\Questions\\MultipleQuestion', '2024-09-28 11:10:00', '2024-09-28 11:10:20'),
(18, 14, 2, 16, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:14:20', '2024-09-28 11:21:31'),
(19, 14, 1, 17, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:15:00', '2024-09-28 11:21:31'),
(20, 14, 3, 18, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:15:16', '2024-09-28 11:15:37'),
(21, 14, 4, 19, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:15:44', '2024-09-28 11:16:30'),
(22, 15, 1, 20, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:16:38', '2024-09-28 11:17:03'),
(23, 15, 2, 21, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:17:07', '2024-09-28 11:17:23'),
(24, 16, 1, 22, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:17:30', '2024-09-28 11:18:12'),
(25, 16, 2, 23, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:18:17', '2024-09-28 11:18:44'),
(26, 16, 3, 24, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:18:48', '2024-09-28 11:19:05'),
(27, 16, 4, 25, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:19:10', '2024-09-28 11:19:36'),
(28, 17, 1, 26, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:19:50', '2024-09-28 11:20:06'),
(29, 17, 2, 27, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:20:11', '2024-09-28 11:20:34'),
(30, 17, 3, 28, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:20:38', '2024-09-28 11:20:59'),
(31, 19, 1, 29, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:25:14', '2024-09-28 11:25:32'),
(32, 19, 2, 30, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:25:35', '2024-09-28 11:25:49'),
(33, 20, 1, 31, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:26:01', '2024-09-28 11:26:17'),
(34, 20, 2, 32, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:26:22', '2024-09-28 11:26:35'),
(35, 21, 1, 33, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:26:42', '2024-09-28 11:27:06'),
(36, 21, 2, 34, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:27:09', '2024-09-28 11:27:31'),
(37, 22, 1, 35, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:27:47', '2024-09-28 11:28:35'),
(38, 22, 2, 36, 'App\\Models\\Questions\\ValueQuestion', '2024-09-28 11:28:40', '2024-09-28 11:28:56'),
(40, 23, 1, 2, 'App\\Models\\Questions\\MultipleQuestion', '2024-09-28 11:30:44', '2024-09-28 11:31:17'),
(41, 23, 2, 3, 'App\\Models\\Questions\\MultipleQuestion', '2024-09-28 11:31:23', '2024-09-28 11:31:46'),
(42, 23, 3, 4, 'App\\Models\\Questions\\MultipleQuestion', '2024-09-28 11:31:50', '2024-09-28 11:32:21'),
(43, 23, 4, 5, 'App\\Models\\Questions\\MultipleQuestion', '2024-09-28 11:32:35', '2024-09-28 11:35:40'),
(45, 24, 1, 37, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:48:51', '2024-09-30 17:49:09'),
(46, 24, 2, 38, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:49:13', '2024-09-30 17:49:30'),
(47, 24, 3, 39, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:49:36', '2024-09-30 17:50:13'),
(48, 24, 4, 40, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:50:20', '2024-09-30 17:50:41'),
(49, 24, 5, 41, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:51:01', '2024-09-30 17:51:29'),
(50, 24, 6, 42, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:51:38', '2024-09-30 17:52:05'),
(51, 24, 7, 43, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:52:11', '2024-09-30 17:53:05'),
(52, 24, 8, 44, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:53:21', '2024-09-30 17:53:48'),
(53, 24, 9, 45, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:53:57', '2024-09-30 17:54:26'),
(54, 24, 10, 46, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:54:32', '2024-09-30 17:55:01'),
(55, 25, 1, 47, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:55:46', '2024-09-30 17:56:29'),
(56, 25, 2, 48, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:56:35', '2024-09-30 17:57:11'),
(57, 25, 3, 49, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:57:21', '2024-09-30 17:57:54'),
(58, 25, 4, 50, 'App\\Models\\Questions\\ValueQuestion', '2024-09-30 17:58:05', '2024-09-30 17:59:41');

-- --------------------------------------------------------

--
-- Struttura della tabella `question_results`
--

CREATE TABLE `question_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `section_result_id` bigint(20) UNSIGNED NOT NULL,
  `progressive` int(11) NOT NULL,
  `jump` tinyint(1) NOT NULL DEFAULT 0,
  `questionable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `questionable_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sectionable_id` bigint(20) UNSIGNED NOT NULL,
  `sectionable_type` varchar(255) NOT NULL,
  `progressive` int(11) NOT NULL,
  `jump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jump`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `sections`
--

INSERT INTO `sections` (`id`, `name`, `sectionable_id`, `sectionable_type`, `progressive`, `jump`, `created_at`, `updated_at`) VALUES
(5, 'A. Anomalie qualitative nell\'interazione sociale reciproca', 1, 'App\\Models\\Test', 1, NULL, '2024-09-28 10:48:24', '2024-09-28 10:48:36'),
(6, 'A1. Difficoltà nell\'uso di comportamenti non verbali per regolare l\'interazione sociale', 5, 'App\\Models\\Section', 1, NULL, '2024-09-28 10:48:46', '2024-09-28 10:48:46'),
(7, 'A2. Difficoltà a sviluppare relazioni con i coetanei', 5, 'App\\Models\\Section', 2, NULL, '2024-09-28 10:48:51', '2024-09-28 10:48:51'),
(8, 'A3. Difficoltà a condividere il divertimento', 5, 'App\\Models\\Section', 3, NULL, '2024-09-28 10:48:57', '2024-09-28 10:48:57'),
(9, 'A4. Difficoltà nella reciprocità socioemotiva', 5, 'App\\Models\\Section', 4, NULL, '2024-09-28 10:49:02', '2024-09-28 10:49:02'),
(10, 'B. Anomalie qualitative nella comunicazione', 1, 'App\\Models\\Test', 2, NULL, '2024-09-28 11:07:44', '2024-09-28 11:07:44'),
(13, 'Tipo di Soggetto', 10, 'App\\Models\\Section', 1, NULL, '2024-09-28 11:09:57', '2024-09-28 11:09:57'),
(14, 'B1. Assenza o ritardo di linguaggio e difficoltà a compensare attraverso l\'uso di gesti', 10, 'App\\Models\\Section', 4, NULL, '2024-09-28 11:12:18', '2024-09-28 11:21:44'),
(15, 'B2. Difficoltà relative a iniziare o sostenere la conversazione reciproca', 10, 'App\\Models\\Section', 2, NULL, '2024-09-28 11:13:08', '2024-09-28 11:21:37'),
(16, 'B3. Verbalizzazioni stereotipate, ripetitive o idiosincratiche', 10, 'App\\Models\\Section', 3, NULL, '2024-09-28 11:13:37', '2024-09-28 11:21:37'),
(17, 'B4. Difficoltà nella varietà di giochi spontanei di far finta o nel gioco imitativo', 10, 'App\\Models\\Section', 5, NULL, '2024-09-28 11:14:15', '2024-09-28 11:21:44'),
(18, 'C. Modelli di comportamento ristretti, ripetitivi e stereotipati', 1, 'App\\Models\\Test', 3, NULL, '2024-09-28 11:22:36', '2024-09-28 11:22:36'),
(19, 'C1. Preoccupazioni circoscritte o modelli limitati di interessi', 18, 'App\\Models\\Section', 1, NULL, '2024-09-28 11:23:18', '2024-09-28 11:23:18'),
(20, 'C2. Apparente adesione compulsiva a routine o rituali non funzionali', 18, 'App\\Models\\Section', 2, NULL, '2024-09-28 11:23:56', '2024-09-28 11:23:56'),
(21, 'C3. Stereotipie e manierismi ripetitivi del corpo', 18, 'App\\Models\\Section', 3, NULL, '2024-09-28 11:24:22', '2024-09-28 11:24:22'),
(22, 'C4. Preoccupazione per parti di oggetti o elementi non funzionali del materiale', 18, 'App\\Models\\Section', 4, NULL, '2024-09-28 11:25:03', '2024-09-28 11:25:03'),
(23, 'D. Anomalie dello sviluppo evidenti a/o prima dei 36 mesi', 1, 'App\\Models\\Test', 4, NULL, '2024-09-28 11:29:47', '2024-09-28 11:29:47'),
(24, 'Affetto Sociale(AS)', 2, 'App\\Models\\Test', 1, NULL, '2024-09-30 17:48:20', '2024-09-30 17:48:20'),
(25, 'Comportamento rispetto al  ripetitivo(CRR)', 2, 'App\\Models\\Test', 2, NULL, '2024-09-30 17:55:33', '2024-09-30 17:55:33');

-- --------------------------------------------------------

--
-- Struttura della tabella `section_results`
--

CREATE TABLE `section_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `sectionable_id` bigint(20) UNSIGNED NOT NULL,
  `sectionable_type` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `progressive` int(11) NOT NULL,
  `result` varchar(255) DEFAULT NULL,
  `score` double NOT NULL DEFAULT 0,
  `jump` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sessions`
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
-- Dump dei dati per la tabella `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('68iB1D4CMDcgtOUorE9yUirHpkObM6Ru1t3PG7iy', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmN1U0hpUERGMHZ4bE1lOW5VbXRlZFVEM0lGWjhXUkpCZ2VPV0xhWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90ZXN0bWVkL2NyZWF0ZXRlc3Q/c3RhdHVzPTEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1727726849);

-- --------------------------------------------------------

--
-- Struttura della tabella `tests`
--

CREATE TABLE `tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `test_med_id` bigint(20) UNSIGNED NOT NULL,
  `labels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`labels`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tests`
--

INSERT INTO `tests` (`id`, `name`, `status`, `test_med_id`, `labels`, `created_at`, `updated_at`) VALUES
(1, 'ADI-R', 1, 1, NULL, '2024-09-28 10:23:31', '2024-09-28 12:54:11'),
(2, 'ADOS-2', 1, 1, '[[\"0\",\"2\",\"Minimo\"],[\"3\",\"4\",\"Basso\"],[\"5\",\"7\",\"Medio\"],[\"8\",\"10\",\"Alto\"]]', '2024-09-30 17:46:52', '2024-09-30 18:07:28');

-- --------------------------------------------------------

--
-- Struttura della tabella `test_meds`
--

CREATE TABLE `test_meds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `test_meds`
--

INSERT INTO `test_meds` (`id`, `name`, `surname`, `telephone`, `birthdate`, `created_at`, `updated_at`) VALUES
(1, 'TestMed1', 'TestMed1', '1234567891', '2024-09-14', '2024-09-28 10:22:31', '2024-09-28 10:22:31');

-- --------------------------------------------------------

--
-- Struttura della tabella `test_results`
--

CREATE TABLE `test_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `test_id` bigint(20) UNSIGNED NOT NULL,
  `interview_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `result` varchar(255) DEFAULT NULL,
  `score` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `userable_type` varchar(255) NOT NULL DEFAULT 'AppModelsMed',
  `userable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `userable_type`, `userable_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.it', NULL, '$2y$12$ZR2gMLCTqzucxC1B4G21R.6PpdnLszv/bDOteI74doA8IM4m00wNi', NULL, 'App\\Models\\Admin', NULL, '2024-09-28 10:01:24', '2024-09-28 10:01:24'),
(2, 'Med1', 'med1@med.it', NULL, '$2y$12$x9ILAUqScccO0zY4WawHqOk88a.7eDSuid3G/Fusnfb99utdzxLFq', NULL, 'App\\Models\\Med', NULL, '2024-09-28 10:01:24', '2024-09-28 10:01:24'),
(3, 'TestMed1', 'testmed1@testmed.it', NULL, '$2y$12$fYQ8H18QARRXEKAZmwo5J.RjWvQ8vK9bewz/oGakerNlB3yLm4cZm', NULL, 'App\\Models\\TestMed', 1, '2024-09-28 10:01:24', '2024-09-28 10:22:31');

-- --------------------------------------------------------

--
-- Struttura della tabella `value_questions`
--

CREATE TABLE `value_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`fields`)),
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scores`)),
  `jump` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jump`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `value_questions`
--

INSERT INTO `value_questions` (`id`, `title`, `text`, `fields`, `scores`, `jump`, `created_at`, `updated_at`) VALUES
(1, 'Sguardo diretto', 'Sguardo diretto', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:50:31', '2024-09-28 11:51:30'),
(2, 'Sorriso sociale', 'Sorriso sociale', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:51:07', '2024-09-28 11:51:34'),
(3, 'Espressioni facciali', 'Varietà delle espressioni facciali usate per comunicare', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:51:55', '2024-09-28 11:51:50'),
(4, 'Gioco immaginativo', 'Gioco immaginativo con i coetanei', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:52:36', '2024-09-28 12:46:11'),
(5, 'Interesse negli altri bambini', 'Interesse nei confronti degli altri bambini', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:54:49', '2024-09-28 12:46:13'),
(6, 'Risposta agli approcci', 'Risposta agli approcci degli altri bambini', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:55:46', '2024-09-28 12:46:19'),
(7, 'Gioco di gruppo / Amicizia', 'Gioco di gruppo (tra 4.0 e 9.11 anni) / Amicizia (se ha più di 10 anni)', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 10:59:39', '2024-09-28 12:46:47'),
(8, 'Attirare l\'attenzione', 'Mostrare e attirare l\'attenzione', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:00:24', '2024-09-28 12:47:15'),
(9, 'Offrire per condividere', 'Offrire per condividere', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:00:47', '2024-09-28 12:47:17'),
(10, 'Condividere il divertimento', 'Cercare di condividere il proprio divertimento con altri', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:01:37', '2024-09-28 12:47:20'),
(11, 'Corpo dell\'altro e comunicare', 'Uso del corpo dell\'altro per comunicare', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:04:41', '2024-09-28 12:47:43'),
(12, 'Offrire conforto', 'Offrire conforto', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:05:06', '2024-09-28 12:47:46'),
(13, 'Qualità delle aperture sociali', 'Qualità delle aperture sociali', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:05:31', '2024-09-28 12:47:48'),
(14, 'Espressioni facciali', 'Espressioni facciali inappropriate', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:06:00', '2024-09-28 12:47:50'),
(15, 'Risposte facciali appropriate', 'Risposte facciali appropriate', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:06:27', '2024-09-28 12:47:53'),
(16, 'Pointing e interesse', 'Pointing per esprimere interesse', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:14:56', '2024-09-28 12:50:10'),
(17, 'Annuire', 'Annuire', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:15:11', '2024-09-28 12:50:08'),
(18, 'Scuotere la testa per dire NO', 'Scuotere la testa per dire NO', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:15:37', '2024-09-28 12:50:12'),
(19, 'Convenzionali/Strumentali', 'Gesti convenzionali/strumentali', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:16:30', '2024-09-28 12:50:14'),
(20, 'Verbalizzazione', 'Verbalizzazione sociale/chiacchiera', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:17:03', '2024-09-28 12:49:12'),
(21, 'Conversazione reciproca', 'Conversazione reciproca', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:17:23', '2024-09-28 12:49:13'),
(22, 'Espressioni stereotipate', 'Espressioni stereotipate ed ecolalia differita', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:18:12', '2024-09-28 12:49:39'),
(23, 'Affermazioni inappropriate', 'Domande o affermazioni inappropriate', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:18:44', '2024-09-28 12:49:41'),
(24, 'Inversione dei pronomi', 'Inversione dei pronomi', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:19:05', '2024-09-28 12:49:43'),
(25, 'Neologismi', 'Neologismi/linguaggio idiosincratico', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:19:36', '2024-09-28 12:49:46'),
(26, 'Imitazione spontanea di azioni', 'Imitazione spontanea di azioni', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:20:06', '2024-09-28 12:50:34'),
(27, 'Gioco immaginativo', 'Gioco immaginativo', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:20:34', '2024-09-28 12:50:36'),
(28, 'Gioco sociale di imitazione', 'Gioco sociale di imitazione', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:20:59', '2024-09-28 12:50:37'),
(29, 'Preoccupazioni insolite', 'Preoccupazioni insolite', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:25:32', '2024-09-28 12:51:31'),
(30, 'Interessi circoscritti', 'Interessi circoscritti', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:25:49', '2024-09-28 12:51:33'),
(31, 'Rituali verbali', 'Rituali verbali', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:26:17', '2024-09-28 12:51:56'),
(32, 'Compulsioni/rituali', 'Compulsioni/rituali', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:26:35', '2024-09-28 12:51:57'),
(33, 'Manierismi delle mani', 'Manierismi delle mani e delle dita', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:27:06', '2024-09-28 12:52:15'),
(34, 'Altri manierismi', 'Altri manierismi o movimenti stereotipati del corpo', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:27:31', '2024-09-28 12:52:17'),
(35, 'Uso ripetitivo', 'Uso ripetitivo di oggetti o interesse per parti di oggetti', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:28:35', '2024-09-28 12:52:36'),
(36, 'Interessi sensoriali insoliti', 'Interessi sensoriali insoliti', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-28 11:28:56', '2024-09-28 12:52:37'),
(37, 'Racconto di eventi', 'Racconto di eventi', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:49:09', '2024-09-30 18:00:52'),
(38, 'Conversazione', 'Conversazione', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:49:30', '2024-09-30 18:00:54'),
(39, 'Gesti descrittivi', 'Gesti descrittivi, convenzionali, strumentali o informativi', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:50:13', '2024-09-30 18:01:01'),
(40, 'Contatto oculare insolito', 'Contatto oculare insolito', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:50:41', '2024-09-30 18:01:02'),
(41, 'Espressioni facciali', 'Espressioni facciali dirette all\'esaminatore', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:51:29', '2024-09-30 18:01:05'),
(42, 'Divertimento condiviso', 'Divertimento condiviso nell\'interazione', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:52:05', '2024-09-30 18:01:08'),
(43, 'Qualità delle aperture sociali', 'Qualità delle aperture sociali', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:53:05', '2024-09-30 18:01:10'),
(44, 'Qualità della risposta sociale', 'Qualità della risposta sociale', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:53:48', '2024-09-30 18:03:36'),
(45, 'Qualità della comunicazione', 'Qualità della comunicazione sociale reciproca', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:54:26', '2024-09-30 18:03:38'),
(46, 'Qualità generale del rapporto', 'Qualità generale del rapporto', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:55:01', '2024-09-30 18:03:41'),
(47, 'Uso stereotipato di frasi', 'Uso stereotipato/idiosincratico di parole o frasi', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:56:29', '2024-09-30 18:05:42'),
(48, 'Interesse sensoriale', 'Interesse sensoriale insolito per materiali di gioco/persone', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:57:11', '2024-09-30 18:05:44'),
(49, 'Manierismi', 'Manierismi delle mani e delle dita e altri manierismi complessi', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:57:54', '2024-09-30 18:05:46'),
(50, 'Interesse eccessivo', 'Interesse o riferimento eccessivo ad argomenti insoliti o ad oggetti o a comportamenti ripetitivi o altamente specifici', '{\"singular\":[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"],\"personal\":[]}', '[\"0\",\"1\",\"2\",\"3\",\"7\",\"8\",\"9\"]', NULL, '2024-09-30 17:59:41', '2024-09-30 18:05:48');

-- --------------------------------------------------------

--
-- Struttura della tabella `value_question_results`
--

CREATE TABLE `value_question_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `value_question_id` bigint(20) UNSIGNED NOT NULL,
  `value` int(11) NOT NULL,
  `score` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `visits`
--

CREATE TABLE `visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `med_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `diagnosis` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indici per le tabelle `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indici per le tabelle `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indici per le tabelle `image_questions`
--
ALTER TABLE `image_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `image_question_results`
--
ALTER TABLE `image_question_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indici per le tabelle `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `meds`
--
ALTER TABLE `meds`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `multiple_questions`
--
ALTER TABLE `multiple_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `multiple_question_results`
--
ALTER TABLE `multiple_question_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `multiple_selection_questions`
--
ALTER TABLE `multiple_selection_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `multiple_selection_question_results`
--
ALTER TABLE `multiple_selection_question_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `open_questions`
--
ALTER TABLE `open_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `open_question_results`
--
ALTER TABLE `open_question_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `operation_on_scores`
--
ALTER TABLE `operation_on_scores`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`username`);

--
-- Indici per le tabelle `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `question_results`
--
ALTER TABLE `question_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `section_results`
--
ALTER TABLE `section_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indici per le tabelle `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tests_name_unique` (`name`);

--
-- Indici per le tabelle `test_meds`
--
ALTER TABLE `test_meds`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indici per le tabelle `value_questions`
--
ALTER TABLE `value_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `value_question_results`
--
ALTER TABLE `value_question_results`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `image_questions`
--
ALTER TABLE `image_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `image_question_results`
--
ALTER TABLE `image_question_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `interviews`
--
ALTER TABLE `interviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `meds`
--
ALTER TABLE `meds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `multiple_questions`
--
ALTER TABLE `multiple_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `multiple_question_results`
--
ALTER TABLE `multiple_question_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `multiple_selection_questions`
--
ALTER TABLE `multiple_selection_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `multiple_selection_question_results`
--
ALTER TABLE `multiple_selection_question_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `open_questions`
--
ALTER TABLE `open_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `open_question_results`
--
ALTER TABLE `open_question_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `operation_on_scores`
--
ALTER TABLE `operation_on_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT per la tabella `question_results`
--
ALTER TABLE `question_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT per la tabella `section_results`
--
ALTER TABLE `section_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tests`
--
ALTER TABLE `tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `test_meds`
--
ALTER TABLE `test_meds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `value_questions`
--
ALTER TABLE `value_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT per la tabella `value_question_results`
--
ALTER TABLE `value_question_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `visits`
--
ALTER TABLE `visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
