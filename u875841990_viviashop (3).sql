-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 16, 2025 at 03:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u875841990_viviashop`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_unique` tinyint(1) NOT NULL DEFAULT '0',
  `is_filterable` tinyint(1) NOT NULL DEFAULT '0',
  `is_configurable` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `code`, `name`, `type`, `validation`, `is_required`, `is_unique`, `is_filterable`, `is_configurable`, `created_at`, `updated_at`) VALUES
(1, 'HVS', 'HVS', 'Text', NULL, 1, 1, 1, 1, '2024-11-12 11:52:48', '2025-01-05 02:27:02'),
(2, 'APP', 'Art Paper', 'Text', NULL, 1, 1, 1, 1, '2025-01-05 02:24:43', '2025-01-05 02:26:40'),
(4, 'DUMMY', 'dummy', 'Text', NULL, 0, 0, 0, 0, '2025-08-06 14:01:22', '2025-08-06 14:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_options`
--

CREATE TABLE `attribute_options` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attribute_variant_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_options`
--

INSERT INTO `attribute_options` (`id`, `name`, `created_at`, `updated_at`, `attribute_variant_id`) VALUES
(1, 'HVS 70Gr', '2025-01-05 02:23:16', '2025-01-05 02:23:16', 1),
(2, 'HVS 80Gr', '2025-01-05 02:23:41', '2025-01-05 02:23:41', 1),
(3, 'HVS 100Gr', '2025-01-05 02:23:51', '2025-01-05 02:23:51', 1),
(4, 'APP 100Gr', '2025-01-05 02:25:02', '2025-01-05 02:25:02', 1),
(5, 'APP 120Gr', '2025-01-05 02:25:09', '2025-01-05 02:25:09', 1),
(6, 'APP 150Gr', '2025-01-05 02:25:18', '2025-01-05 02:25:18', 1),
(7, 'APP 200Gr', '2025-01-05 02:25:30', '2025-01-05 02:25:30', 1),
(8, 'APP 210Gr', '2025-01-05 02:25:39', '2025-01-05 02:25:39', 1),
(9, 'APP 230Gr', '2025-01-05 02:25:51', '2025-01-05 02:25:51', 1),
(10, 'APP 260Gr', '2025-01-05 02:26:21', '2025-01-05 02:26:21', 1),
(11, 'DUMMY 1', '2025-08-06 13:10:44', '2025-08-06 13:10:44', 1),
(12, 'A3', '2025-08-06 14:15:20', '2025-08-06 14:15:20', 2),
(13, 'VINYL HIGH QUALITY', '2025-08-14 14:23:02', '2025-08-14 14:23:02', 3),
(14, 'BERWARNA HIGH QUALITY', '2025-08-14 14:23:23', '2025-08-14 14:23:23', 4);

-- --------------------------------------------------------

--
-- Table structure for table `attribute_variants`
--

CREATE TABLE `attribute_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_variants`
--

INSERT INTO `attribute_variants` (`id`, `name`, `attribute_id`, `created_at`, `updated_at`) VALUES
(1, 'HVS APP', 1, '2025-08-06 13:45:41', '2025-08-14 14:22:31'),
(2, 'Vinyl', 4, '2025-08-06 14:01:43', '2025-08-06 14:01:43'),
(3, 'HVS VINYL', 1, '2025-08-14 14:22:40', '2025-08-14 14:22:40'),
(4, 'HVS BERWARNA', 1, '2025-08-14 14:22:49', '2025-08-14 14:22:49');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'APP', 'app', 'Asia Pulp & Paper - Merek kertas terkemuka', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(2, 'Sinar Dunia', 'sinar-dunia', 'Sinar Dunia - Kertas berkualitas untuk kebutuhan kantor', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(3, 'PaperOne', 'paperone', 'PaperOne - Premium office paper', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(4, 'Double A', 'double-a', 'Double A - The Original Paper', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(5, 'Faber Castell', 'faber-castell', 'Faber Castell - Alat tulis berkualitas tinggi', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(6, 'Pilot', 'pilot', 'Pilot - Innovative writing instruments', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(7, 'Joyko', 'joyko', 'Joyko - Alat tulis kantor terpercaya', NULL, 1, '2025-09-06 03:49:00', '2025-09-06 03:49:00'),
(8, 'ViVia Print Service', 'vivia-print-service', 'Layanan cetak terpercaya', NULL, 1, '2025-09-11 04:02:27', '2025-09-11 04:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Layanan Cetak', 'layanan-cetak', NULL, '2024-11-12 11:43:46', '2024-11-12 11:44:20'),
(2, 'Seminar Kit', 'seminar-kit', NULL, '2025-01-05 01:59:34', '2025-01-05 01:59:34'),
(3, 'ATK', 'atk', NULL, '2025-01-05 02:00:51', '2025-01-05 02:00:51'),
(4, 'Cetak Digital', 'cetak-digital', 1, '2025-01-05 02:04:11', '2025-01-05 02:05:14'),
(5, 'Cetak Offset', 'cetak-offset', 1, '2025-01-05 02:04:56', '2025-01-05 02:04:56'),
(6, 'Cetak Khusus', 'cetak-khusus', 1, '2025-01-05 02:05:26', '2025-01-05 02:05:34'),
(7, 'Cetak Besar', 'cetak-besar', 1, '2025-01-05 02:05:48', '2025-01-05 02:05:48'),
(8, 'Perlengkapan Tulis', 'perlengkapan-tulis', 3, '2025-01-05 02:07:35', '2025-01-05 02:07:35'),
(9, 'Perlengkapan Kantor', 'perlengkapan-kantor', 3, '2025-01-05 02:07:49', '2025-01-05 02:07:49'),
(10, 'Perlengkapan Presentasi', 'perlengkapan-presentasi', 3, '2025-01-05 02:08:37', '2025-01-05 02:08:37'),
(11, 'Perlengkapan Lain-lain', 'perlengkapan-lain-lain', 3, '2025-01-05 02:08:59', '2025-01-05 02:08:59'),
(12, 'Komputer dan Aksesoris', 'komputer-dan-aksesoris', NULL, '2025-01-05 02:14:57', '2025-01-05 02:14:57'),
(13, 'Komputer', 'komputer', 12, '2025-01-05 02:15:52', '2025-01-05 02:15:52'),
(14, 'Aksesoris Komputer', 'aksesoris-komputer', 12, '2025-01-05 02:16:11', '2025-01-05 02:16:11'),
(15, 'Jaringan', 'jaringan', 12, '2025-01-05 02:16:29', '2025-01-05 02:16:29'),
(16, 'Perangkat Lunak', 'perangkat-lunak', 12, '2025-01-05 02:16:43', '2025-01-05 02:16:43'),
(17, 'Paket Dasar', 'paket-dasar', 2, '2025-01-05 02:17:05', '2025-01-05 02:17:05'),
(18, 'Paket Premium', 'paket-premium', 2, '2025-01-05 02:17:17', '2025-01-05 02:17:17'),
(19, 'Paket Khusus', 'paket-khusus', 2, '2025-01-05 02:17:59', '2025-01-05 02:17:59'),
(20, 'SURVEY KIT', 'survey-kit', NULL, '2025-05-10 13:15:47', '2025-05-10 13:15:47'),
(21, 'ELEKTRONIK', 'elektronik', NULL, '2025-05-10 14:22:33', '2025-05-10 14:22:33'),
(22, 'DUMMY', 'dummy', NULL, '2025-05-10 18:06:25', '2025-05-10 18:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `dymantic_instagram_basic_profiles`
--

CREATE TABLE `dymantic_instagram_basic_profiles` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dymantic_instagram_feed_tokens`
--

CREATE TABLE `dymantic_instagram_feed_tokens` (
  `id` int UNSIGNED NOT NULL,
  `profile_id` int UNSIGNED NOT NULL,
  `access_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_bonuses`
--

CREATE TABLE `employee_bonuses` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_amount` decimal(15,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `given_by` bigint UNSIGNED NOT NULL,
  `given_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_bonuses`
--

INSERT INTO `employee_bonuses` (`id`, `employee_name`, `bonus_amount`, `description`, `period_start`, `period_end`, `notes`, `given_by`, `given_at`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Susanto', '354239.00', NULL, '2025-08-10', '2025-09-09', 'Performance bonus for good work', 1, '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(2, 'Budi Rahman', '233076.00', NULL, '2025-08-10', '2025-09-09', 'Performance bonus for good work', 1, '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(3, 'Citra Dewi', '346144.00', NULL, '2025-08-10', '2025-09-09', 'Performance bonus for good work', 1, '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(4, 'Diana Putri', '273998.00', NULL, '2025-08-10', '2025-09-09', 'Performance bonus for good work', 1, '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(5, 'Eko Setiawan', '92202.00', NULL, '2025-08-10', '2025-09-09', 'Performance bonus for good work', 1, '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(6, 'Test Employee', '100000.00', NULL, '2025-09-01', '2025-09-30', 'Test bonus from command', 1, '2025-09-09 03:24:25', '2025-09-09 03:24:25', '2025-09-09 03:24:25'),
(7, 'Reza', '50000.00', 'Test bonus for excellent performance', '2024-09-01', '2024-09-30', 'Additional notes for the bonus', 1, '2025-09-09 03:35:08', '2025-09-09 03:35:08', '2025-09-09 03:35:08'),
(8, 'Reza', '50000.00', 'Test bonus for excellent performance', '2024-09-01', '2024-09-30', 'Additional notes for the bonus', 1, '2025-09-09 03:35:43', '2025-09-09 03:35:43', '2025-09-09 03:35:43'),
(9, NULL, '25000.00', 'General bonus for all employees', '2024-09-01', '2024-09-30', 'Monthly performance bonus', 1, '2025-09-09 03:35:43', '2025-09-09 03:35:43', '2025-09-09 03:35:43'),
(10, 'Ahmad', '75000.00', 'Exceptional customer service', '2024-09-01', '2024-09-30', 'Customer feedback was outstanding', 1, '2025-09-09 03:38:00', '2025-09-09 03:38:00', '2025-09-09 03:38:00'),
(11, NULL, '30000.00', 'Monthly team performance bonus', '2024-09-01', '2024-09-30', 'Great teamwork this month', 1, '2025-09-09 03:38:00', '2025-09-09 03:38:00', '2025-09-09 03:38:00'),
(12, 'Budi Rahman', '100000.00', 'makasih udah handle project gede ya', '2025-09-01', '2025-09-30', NULL, 1, '2025-09-09 03:39:13', '2025-09-09 03:39:13', '2025-09-09 03:39:13'),
(14, 'Budi Rahman', '125000.00', 'Stress test individual bonus (Updated during stress test)', '2024-09-01', '2024-09-30', 'Created during stress testing (Updated notes)', 1, '2025-09-09 03:50:13', '2025-09-09 03:50:13', '2025-09-09 03:50:13'),
(15, NULL, '50000.00', 'Stress test general bonus for all employees', '2024-09-01', '2024-09-30', 'Created during stress testing for all employees', 1, '2025-09-09 03:50:13', '2025-09-09 03:50:13', '2025-09-09 03:50:13'),
(16, 'Reza', '100000.00', 'bagus sekali', '2025-09-01', '2025-09-30', 'bagus sekali', 1, '2025-09-09 03:52:53', '2025-09-09 03:52:53', '2025-09-09 03:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `employee_performances`
--

CREATE TABLE `employee_performances` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_value` decimal(15,2) NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_performances`
--

INSERT INTO `employee_performances` (`id`, `order_id`, `employee_name`, `transaction_value`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 51, 'Another Employee 1757391468', '656.00', '2025-09-09 04:17:48', '2025-09-09 02:48:39', '2025-09-09 04:17:48'),
(2, 59, 'Budi Rahman', '711.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(3, 61, 'Budi Rahman', '407.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(4, 62, 'Diana Putri', '7273.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(5, 63, 'Budi Rahman', '21419.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(6, 64, 'Eko Setiawan', '7287.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(7, 65, 'Diana Putri', '7929.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(8, 68, 'Diana Putri', '623.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(9, 69, 'Citra Dewi', '93.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(10, 71, 'Budi Rahman', '360.00', '2025-09-09 02:48:39', '2025-09-09 02:48:39', '2025-09-09 02:48:39'),
(11, 142, 'Reza', '3.00', '2025-09-09 03:08:08', '2025-09-09 03:08:08', '2025-09-09 03:08:08'),
(12, 143, 'Reza', '3.00', '2025-09-09 04:09:19', '2025-09-09 03:55:19', '2025-09-09 04:16:18'),
(13, 6, 'TestEmployee_1757390917', '720.00', '2025-09-09 04:08:37', '2025-09-09 04:07:59', '2025-09-09 04:08:37'),
(14, 144, 'Reza', '3.00', '2025-09-09 04:11:05', '2025-09-09 04:11:05', '2025-09-09 04:11:05'),
(15, 145, 'Reza', '3.00', '2025-09-09 04:21:33', '2025-09-09 04:21:33', '2025-09-09 04:21:33'),
(16, 146, 'Reza', '3.00', '2025-09-09 06:59:45', '2025-09-09 06:59:45', '2025-09-09 06:59:45'),
(17, 147, 'Reza', '15000.00', '2025-09-10 02:09:49', '2025-09-10 02:09:49', '2025-09-10 02:09:49'),
(18, 152, 'Reza', '15000.00', '2025-09-10 03:13:57', '2025-09-10 03:13:57', '2025-09-10 03:13:57'),
(19, 213, 'Reza', '15000.00', '2025-09-10 05:16:27', '2025-09-10 05:16:27', '2025-09-10 05:16:27'),
(20, 215, 'Reza', '30000.00', '2025-09-13 05:40:51', '2025-09-13 05:40:51', '2025-09-13 05:40:51'),
(21, 235, 'Reza', '1015000.00', '2025-09-14 11:04:48', '2025-09-14 11:04:48', '2025-09-14 11:04:48'),
(22, 238, 'Reza', '300000.00', '2025-09-14 12:31:32', '2025-09-14 12:31:32', '2025-09-14 12:31:32'),
(23, 245, 'Reza', '20.00', '2025-09-14 14:59:55', '2025-09-14 14:59:55', '2025-09-14 14:59:55'),
(24, 246, 'Reza', '70000.00', '2025-09-15 06:48:43', '2025-09-15 06:48:43', '2025-09-15 06:48:43'),
(25, 247, 'Reza', '2.00', '2025-09-15 15:36:17', '2025-09-15 15:36:17', '2025-09-15 15:36:17'),
(26, 248, 'Reza', '15000.00', '2025-09-15 17:54:06', '2025-09-15 17:54:06', '2025-09-15 17:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_03_08_003606_create_categories_table', 1),
(7, '2023_03_08_003938_create_products_table', 1),
(8, '2023_03_08_011102_create_attributes_table', 1),
(9, '2023_03_08_011524_create_product_attribute_values_table', 1),
(10, '2023_03_08_012756_create_product_inventories_table', 1),
(11, '2023_03_08_013422_create_product_categories_table', 1),
(12, '2023_03_08_013611_create_product_images_table', 1),
(13, '2023_03_08_013906_create_attribute_options_table', 1),
(14, '2023_03_08_014314_create_orders_table', 1),
(15, '2023_03_08_015219_create_order_items_table', 1),
(16, '2023_03_08_015501_create_payments_table', 1),
(17, '2023_03_08_015812_create_wish_lists_table', 1),
(18, '2023_03_13_070603_create_shipments_table', 1),
(19, '2023_03_22_154139_create_slides_table', 1),
(20, '2024_01_20_133045_add_payment_method_field_to_orders_table', 1),
(21, '2024_07_30_072607_add_attachments_field_to_order_items_table', 1),
(22, '2024_07_31_182203_add_attachments_field_to_orders_table', 1),
(23, '2024_11_02_201810_create_suppliers_table', 1),
(24, '2024_11_02_202102_create_rekaman_stoks_table', 1),
(25, '2024_11_02_202149_create_settings_table', 1),
(26, '2024_11_02_203128_create_pengeluarans_table', 1),
(27, '2024_11_08_085927_create_pembelians_table', 1),
(28, '2024_11_08_085939_create_pembelian_details_table', 1),
(29, '2025_02_19_060925_create_testimonials_table', 2),
(30, '2025_03_10_075247_add_notes_field_to_orders_table', 3),
(31, '2025_04_13_054734_create_instagram_basic_profile_table', 3),
(32, '2025_04_13_054734_create_instagram_feed_token_table', 3),
(33, '2025_04_13_054734_create_shoppingcart_table', 3),
(34, '2025_04_18_192428_add_instagram_access_token_field_to_users_table', 3),
(35, '2025_06_27_131821_add_barcode_field_to_products_table', 3),
(37, '2025_08_06_134346_migrate_existing_attribute_options_to_variants', 4),
(38, '2025_08_13_000316_add_district_id_to_users_table', 5),
(39, '2025_09_06_104150_create_brands_table', 6),
(40, '2025_09_06_104212_create_product_variants_table', 7),
(41, '2025_09_06_104229_create_variant_attributes_table', 8),
(42, '2025_09_06_104310_add_brand_and_base_price_to_products_table', 9),
(43, '2025_09_06_104728_add_variant_id_to_order_items_table', 10),
(44, '2025_09_09_093753_add_employee_tracking_to_orders_table', 11),
(45, '2025_09_09_093814_create_employee_performances_table', 12),
(46, '2025_09_09_093845_create_employee_bonuses_table', 13),
(47, '2024_01_15_000001_add_payment_slip_to_orders_table', 14),
(48, '2025_09_09_103257_add_description_to_employee_bonuses_table', 15),
(49, '2025_09_09_103514_update_employee_bonuses_table_nullable_employee_name', 16),
(50, '2025_09_11_100000_add_print_service_to_products_table', 17),
(51, '2025_09_11_101000_add_print_fields_to_product_variants_table', 18),
(52, '2025_09_11_102000_add_order_type_to_orders_table', 19),
(53, '2025_09_11_110000_create_print_sessions_table', 20),
(54, '2025_09_11_120000_create_print_orders_table', 21),
(55, '2025_09_11_130000_create_print_files_table', 22),
(56, '2025_09_11_114442_add_session_id_to_print_files_table', 23),
(57, '2025_09_11_114923_fix_print_sessions_started_at_field', 24),
(58, '2025_09_11_115011_fix_print_orders_payment_method_enum', 25),
(59, '2025_09_11_171837_create_stock_movements_table', 26),
(60, '2025_09_13_120424_add_shipping_adjustment_fields_to_orders_table', 27),
(61, '2025_09_14_093507_add_variant_support_to_pembelian_details_table', 28),
(62, '2025_09_14_093536_add_status_and_payment_method_to_pembelians_table', 29),
(63, '2025_09_16_004813_add_smart_print_to_products_table', 30);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('ecommerce','print_service') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ecommerce',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` datetime NOT NULL,
  `payment_due` datetime NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_total_price` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tax_percent` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(16,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(16,2) NOT NULL DEFAULT '0.00',
  `original_shipping_cost` decimal(16,2) DEFAULT NULL,
  `grand_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `customer_first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_province_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_postcode` int DEFAULT NULL,
  `shipping_courier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_shipping_courier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_shipping_service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost_adjusted` tinyint(1) NOT NULL DEFAULT '0',
  `shipping_adjustment_note` text COLLATE utf8mb4_unicode_ci,
  `shipping_adjusted_at` timestamp NULL DEFAULT NULL,
  `shipping_adjusted_by` bigint UNSIGNED DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `cancelled_by` bigint UNSIGNED DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancellation_note` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `handled_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `use_employee_tracking` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_slip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `code`, `order_type`, `status`, `order_date`, `payment_due`, `payment_status`, `payment_token`, `payment_url`, `base_total_price`, `tax_amount`, `tax_percent`, `discount_amount`, `discount_percent`, `shipping_cost`, `original_shipping_cost`, `grand_total`, `note`, `customer_first_name`, `customer_last_name`, `customer_address1`, `customer_address2`, `customer_phone`, `customer_email`, `customer_city_id`, `customer_province_id`, `customer_postcode`, `shipping_courier`, `original_shipping_courier`, `shipping_service_name`, `original_shipping_service_name`, `shipping_cost_adjusted`, `shipping_adjustment_note`, `shipping_adjusted_at`, `shipping_adjusted_by`, `approved_by`, `approved_at`, `cancelled_by`, `cancelled_at`, `cancellation_note`, `user_id`, `handled_by`, `use_employee_tracking`, `created_at`, `updated_at`, `deleted_at`, `payment_method`, `payment_slip`, `attachments`, `notes`) VALUES
(6, 'INV/20250511/V/XI/00002', 'ecommerce', 'created', '2025-05-11 10:26:42', '2025-05-18 10:26:42', 'paid', 'cc889c75-33f0-48c6-8a5c-89dab7b5049a', 'https://app.midtrans.com/snap/v4/redirection/cc889c75-33f0-48c6-8a5c-89dab7b5049a', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '720.00', 'cobacoba', 'admin', 'admin', 'haujfgua9ho', 'gauhjsbugaosfnbaugosn', '80298498231489', 'admin@admin.com', '177', '10', 412980, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'TestEmployee_1757390917', 1, '2025-05-11 10:26:42', '2025-09-09 04:08:37', NULL, 'automatic', NULL, NULL, NULL),
(8, 'INV/20250511/V/XI/00004', 'ecommerce', 'confirmed', '2025-05-11 10:37:58', '2025-05-18 10:37:58', 'paid', '58a4b120-3e2c-4d81-a4e3-f6ed40d06066', 'https://app.midtrans.com/snap/v4/redirection/58a4b120-3e2c-4d81-a4e3-f6ed40d06066', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '101.00', 'coba', 'admin', 'admin', 'haujfgua9ho', 'gauhjsbugaosfnbaugosn', '80298498231489', 'admin@admin.com', '28', '2', 412980, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-05-11 10:38:19', NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 10:37:58', '2025-05-11 10:38:19', NULL, 'automatic', NULL, 'assets/slides/t3JVZLEzFOTZBCry4n00PAwg4EJvDTk19YN5dTiI.png', '\nPayment pending using qris\nPayment settled using qris'),
(9, 'INV/20250511/V/XI/00005', 'ecommerce', 'confirmed', '2025-05-11 10:41:20', '2025-05-18 10:41:20', 'paid', 'e9f7043a-d2dc-4b5f-a255-cd32c45abd45', 'https://app.midtrans.com/snap/v4/redirection/e9f7043a-d2dc-4b5f-a255-cd32c45abd45', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '226.00', 'INI yaaaa', 'admin', 'admin', 'haujfgua9ho', 'gauhjsbugaosfnbaugosn', '80298498231489', 'admin@admin.com', '403', '3', 412980, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-05-11 10:42:26', NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 10:41:20', '2025-05-11 10:42:26', NULL, 'automatic', NULL, 'assets/slides/F3wTHIJKBNRGEaFkdQHLQrSKZ4P1JibuFNEw8sjF.png', '\nPayment pending using qris\nPayment settled using qris'),
(41, 'INV/20250511/V/XI/00006', 'ecommerce', 'created', '2025-05-11 13:23:33', '2025-05-18 13:23:33', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '943.00', NULL, 'fjhfsjf', 'jsfjsgfjg', 'jagfjsgfj', NULL, '9319461', 'araihanrizki@gmail.com', NULL, NULL, 3197319, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:23:33', '2025-05-11 13:23:33', NULL, 'toko', NULL, NULL, NULL),
(42, 'INV/20250511/V/XI/00007', 'ecommerce', 'created', '2025-05-11 13:24:51', '2025-05-18 13:24:51', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '777.00', NULL, 'jagdjagfjg', 'agdjafgaj', 'ajgfjafgjag', NULL, '916491469164', 'ajfajvfaj@gmail.com', NULL, NULL, 927927592, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:24:51', '2025-05-11 13:24:51', NULL, 'toko', NULL, NULL, NULL),
(45, 'INV/20250511/V/XI/00008', 'ecommerce', 'created', '2025-05-11 13:28:41', '2025-05-18 13:28:41', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '384.00', 'akakakfhkahkfa', 'akhakfhk', 'afbakfwb', 'kafhwihfik', NULL, '19461964196491', 'araihanrizki@gmail.com', NULL, NULL, 196914691, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:28:41', '2025-05-11 13:28:41', NULL, 'toko', NULL, NULL, NULL),
(46, 'INV/20250511/V/XI/00009', 'ecommerce', 'created', '2025-05-11 13:32:30', '2025-05-18 13:32:30', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '744.00', 'fakhfakhfka', 'ruqyruq', 'qurquru', 'qurtuqr', NULL, '1845815418', 'araihanrizki@gmail.com', NULL, NULL, 816381638, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:32:30', '2025-05-11 13:32:30', NULL, 'toko', NULL, NULL, NULL),
(47, 'INV/20250511/V/XI/00010', 'ecommerce', 'created', '2025-05-11 13:34:58', '2025-05-18 13:34:58', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '394.00', 'jahjagfjfsj', 'aidahkadhk', 'bakfkafk', 'akhdkafhk', NULL, '924692692', 'araihanrizki@gmail.com', NULL, NULL, 424424, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:34:58', '2025-05-11 13:34:58', NULL, 'toko', NULL, NULL, NULL),
(48, 'INV/20250511/V/XI/00011', 'ecommerce', 'created', '2025-05-11 13:39:17', '2025-05-18 13:39:17', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '738.00', 'adkhafka', 'adkhkadhkda', 'kahdkahdk', 'kadhkahdk', NULL, '18618484811', 'araihanrizki@gmail.com', NULL, NULL, 1864184, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:39:17', '2025-05-11 13:39:17', NULL, 'toko', NULL, 'assets/slides/3ZKY4QyJdKVyWDr44MatuT8sdVQZoSkgMtOmFCZZ.png', NULL),
(49, 'INV/20250511/V/XI/00012', 'ecommerce', 'confirmed', '2025-05-11 13:40:38', '2025-05-18 13:40:38', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '251.00', 'adkahkh', 'akhkfhakfha', 'akhfkahfk', 'akhfkahf', NULL, '17351745174', 'araihanrizki@gmail.com', NULL, NULL, 17517, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:40:38', '2025-05-11 13:40:49', NULL, 'toko', NULL, 'assets/slides/PFRlYy8Uel9jCpBa3tmg7uSNEmjsuct0HotglIuy.png', NULL),
(50, 'INV/20250511/V/XI/00013', 'ecommerce', 'confirmed', '2025-05-11 13:44:17', '2025-05-18 13:44:17', 'paid', 'cbe62237-b9e5-41fe-98d3-a5a89e7476de', 'https://app.midtrans.com/snap/v4/redirection/cbe62237-b9e5-41fe-98d3-a5a89e7476de', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '764.00', NULL, 'admin', 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'araihanrizki@gmail.com', '17', '1', 17517, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-05-11 13:45:49', NULL, NULL, NULL, 1, NULL, 0, '2025-05-11 13:44:17', '2025-05-11 13:45:49', NULL, 'automatic', NULL, NULL, '\nPayment pending using qris\nPayment settled using qris'),
(51, 'INV/20250511/V/XI/00014', 'ecommerce', 'completed', '2025-05-11 13:47:09', '2025-05-18 13:47:09', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '656.00', 'khadkdak', 'admin', 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'araihanrizki@gmail.com', '27', '2', 17517, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-05-11 13:48:49', NULL, NULL, NULL, 1, '', 0, '2025-05-11 13:47:09', '2025-09-09 04:17:48', NULL, 'manual', 'assets/bukti_pembayaran/fSKZCxfsPFWvZaC77rsVGaIqe8b9K4BqWcsW08eo.png', 'assets/slides/dY2FJmwDSxXOMcg2VIDsjrni6oy1L7PZkMFzYYcW.png', NULL),
(52, 'INV/20250525/V/XXV/00001', 'ecommerce', 'confirmed', '2025-05-25 15:12:20', '2025-06-01 15:12:20', 'paid', 'ffb09b98-7174-4d10-b058-d5eab0b4311f', 'https://app.midtrans.com/snap/v4/redirection/ffb09b98-7174-4d10-b058-d5eab0b4311f', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '1072.00', NULL, 'admin', 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'admin@admin.com', '290', '11', 17517, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-05-25 15:14:09', NULL, NULL, NULL, 1, NULL, 0, '2025-05-25 15:12:20', '2025-05-25 15:14:09', NULL, 'automatic', NULL, NULL, '\nPayment pending using qris\nPayment settled using qris'),
(53, 'INV/20250525/V/XXV/00002', 'ecommerce', 'confirmed', '2025-05-25 16:56:27', '2025-06-01 16:56:27', 'paid', 'a3ffd746-4338-4d0e-81df-6b8dfd645da0', 'https://app.midtrans.com/snap/v4/redirection/a3ffd746-4338-4d0e-81df-6b8dfd645da0', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '727.00', NULL, 'admin', 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'admin@admin.com', '290', '11', 17517, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-05-25 16:57:39', NULL, NULL, NULL, 1, NULL, 0, '2025-05-25 16:56:27', '2025-05-25 16:57:39', NULL, 'automatic', NULL, NULL, '\nPayment pending using qris\nPayment settled using qris'),
(54, 'INV/20250525/V/XXV/00003', 'ecommerce', 'confirmed', '2025-05-25 17:11:04', '2025-06-01 17:11:04', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '684.00', 'fotokopi 2 warna', 'dajgdajgdjag', 'jagdjagdjag', 'ajdgajdgajgd', NULL, '19319461964914', 'user@gmail.com', NULL, NULL, 61471, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-05-25 17:11:04', '2025-05-25 17:12:23', NULL, 'toko', NULL, 'assets/slides/do7yGbtb9b3J3cFnfEpUjOcQkbqE3KUGvVG9P7qS.png', NULL),
(55, 'INV/20250606/VI/VI/00001', 'ecommerce', 'confirmed', '2025-06-06 20:42:32', '2025-06-13 20:42:32', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '561.00', 'Testing update', 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, 0, '2025-06-06 20:42:32', '2025-06-06 20:43:03', NULL, 'toko', NULL, 'assets/slides/Q4aqXbiEMJcwCFGwSjF2TKSs1519yy4w2QUtxgwl.png', NULL),
(56, 'INV/20250627/VI/XXVII/00001', 'ecommerce', 'confirmed', '2025-06-27 15:27:08', '2025-07-04 15:27:08', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '860.00', 'gcvj', 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-06-27 15:27:08', '2025-06-27 15:27:40', NULL, 'toko', NULL, NULL, NULL),
(57, 'INV/20250627/VI/XXVII/00002', 'ecommerce', 'confirmed', '2025-06-27 16:16:41', '2025-07-04 16:16:41', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '605.00', 'ty', 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-06-27 16:16:41', '2025-06-27 16:17:10', NULL, 'toko', NULL, 'assets/slides/zRTNFK7cCTGhgj303UE0rQqXSGaOGbQMKSfHmVVo.jpg', NULL),
(58, 'INV/20250630/VI/XXX/00001', 'ecommerce', 'created', '2025-06-30 10:45:41', '2025-07-07 10:45:41', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '814.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-06-30 10:45:41', '2025-06-30 10:45:41', NULL, 'cod', NULL, NULL, NULL),
(59, 'INV/20250630/VI/XXX/00002', 'ecommerce', 'completed', '2025-06-30 12:00:35', '2025-07-07 12:00:35', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '711.00', 'Coba-coba', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-06-30 12:19:06', NULL, NULL, NULL, 1, 'Budi Rahman', 1, '2025-06-30 12:00:35', '2025-09-09 02:48:39', NULL, 'cod', NULL, 'assets/slides/DrsbnncTBglg8M61oDjcuQPexilcvo3b7EaGvp4k.png', NULL),
(60, 'INV/20250630/VI/XXX/00003', 'ecommerce', 'confirmed', '2025-06-30 12:25:02', '2025-07-07 12:25:02', 'paid', '9133054b-2409-45e5-ae80-5cd9a57dfb33', 'https://app.midtrans.com/snap/v4/redirection/9133054b-2409-45e5-ae80-5cd9a57dfb33', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '459.00', 'awas', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-06-30 12:25:23', NULL, NULL, NULL, 1, NULL, 0, '2025-06-30 12:25:02', '2025-06-30 12:25:23', NULL, 'automatic', NULL, 'assets/slides/cZLtBcjq7gvGrbLIMreB4kWrlwLNriEKmPgHN7h8.png', '\nPayment pending using qris\nPayment settled using qris'),
(61, 'INV/20250630/VI/XXX/00004', 'ecommerce', 'completed', '2025-06-30 12:28:19', '2025-07-07 12:28:19', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '407.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-06-30 12:32:11', NULL, NULL, NULL, 1, 'Budi Rahman', 1, '2025-06-30 12:28:19', '2025-09-09 02:48:39', NULL, 'manual', 'assets/bukti_pembayaran/jmQ397tU0F26c4Y4b5PFESr6qq8xC8FtafQrqdtI.png', NULL, NULL),
(62, 'INV/20250630/VI/XXX/00005', 'ecommerce', 'delivered', '2025-06-30 12:35:46', '2025-07-07 12:35:46', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7273.00', 'coba', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'jne', NULL, 'JNE - CTC', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Diana Putri', 1, '2025-06-30 12:35:46', '2025-09-09 02:48:39', NULL, 'qris', 'assets/bukti_pembayaran/WoEYwRvIvuGYKpfML7W1OwGgipTNIneQbey5aLuL.png', 'assets/slides/F3xAskQDJaUIQRXRcrq3kBee3Xp6r1E7Ca6zuNiO.png', NULL),
(63, 'INV/20250630/VI/XXX/00006', 'ecommerce', 'completed', '2025-06-30 12:44:32', '2025-07-07 12:44:32', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '21000.00', NULL, '21419.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'jne', NULL, 'JNE - CTC', NULL, 0, NULL, NULL, NULL, 1, '2025-06-30 12:46:01', NULL, NULL, NULL, 1, 'Budi Rahman', 1, '2025-06-30 12:44:32', '2025-09-09 02:48:39', NULL, 'qris', 'assets/bukti_pembayaran/RTRvX5jwqh7SVKuy9k5cnNqsIkBsWPna0E8DT2F3.png', NULL, NULL),
(64, 'INV/20250630/VI/XXX/00007', 'ecommerce', 'completed', '2025-06-30 14:14:58', '2025-07-07 14:14:58', 'paid', NULL, NULL, '15.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7287.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'jne', NULL, 'JNE - CTC', NULL, 0, NULL, NULL, NULL, 1, '2025-06-30 14:16:47', NULL, NULL, NULL, 1, 'Eko Setiawan', 1, '2025-06-30 14:14:58', '2025-09-09 02:48:39', NULL, 'qris', 'assets/bukti_pembayaran/nc7uNMrgm1vR9HFLUQxA0SdLCWqIoPq9WTRWhR6c.png', NULL, NULL),
(65, 'INV/20250630/VI/XXX/00008', 'ecommerce', 'completed', '2025-06-30 14:19:39', '2025-07-07 14:19:39', 'paid', NULL, NULL, '15.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7929.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'jne', NULL, 'JNE - CTC', NULL, 0, NULL, NULL, NULL, 1, '2025-06-30 14:20:28', NULL, NULL, NULL, 1, 'Diana Putri', 1, '2025-06-30 14:19:39', '2025-09-09 02:48:39', NULL, 'qris', 'assets/bukti_pembayaran/zA0y0V3LujfsKPSMBKj7LEwVnwz4siO7BMYyYnRd.png', NULL, NULL),
(66, 'INV/20250702/VII/II/00001', 'ecommerce', 'cancelled', '2025-07-02 21:25:29', '2025-07-09 21:25:29', 'cancelled', '40efac5c-bf28-400a-92fa-7138a5113ddc', 'https://app.midtrans.com/snap/v4/redirection/40efac5c-bf28-400a-92fa-7138a5113ddc', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '207.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-07-02 21:25:29', '2025-07-09 21:26:32', NULL, 'automatic', NULL, NULL, '\nPayment pending using gopay\nPayment expired for gopay'),
(67, 'INV/20250707/VII/VII/00001', 'ecommerce', 'created', '2025-07-07 22:58:34', '2025-07-14 22:58:34', 'unpaid', NULL, NULL, '200.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '728.00', 'cek', 'Fanani Agung', 'Fanani Agung', 'Tebuireng IV Cukir', 'Jombang', '08113476769', 'fanani5758@gmail.com', '164', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, 0, '2025-07-07 22:58:34', '2025-07-07 22:58:34', NULL, 'qris', NULL, NULL, NULL),
(68, 'INV/20250708/VII/VIII/00001', 'ecommerce', 'completed', '2025-07-08 14:12:25', '2025-07-15 14:12:25', 'paid', NULL, NULL, '35.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '623.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-07-08 14:18:55', NULL, NULL, NULL, 1, 'Diana Putri', 1, '2025-07-08 14:12:25', '2025-09-09 02:48:39', NULL, 'manual', 'assets/bukti_pembayaran/ABlKIp7KMIKMuROBIFOyL3gNMnXi6uCMSxKNGT2P.png', NULL, NULL),
(69, 'INV/20250708/VII/VIII/00002', 'ecommerce', 'completed', '2025-07-08 14:23:01', '2025-07-15 14:23:01', 'paid', '88603196-c612-4b3c-84bf-f9eafb56358b', 'https://app.midtrans.com/snap/v4/redirection/88603196-c612-4b3c-84bf-f9eafb56358b', '35.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '93.00', 'coba', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-07-08 14:25:36', NULL, NULL, NULL, 1, 'Citra Dewi', 1, '2025-07-08 14:23:01', '2025-09-09 02:48:39', NULL, 'automatic', NULL, 'assets/slides/jMyHlwjgCOS9h67CYTTpgQALJOMEaoGQMAnimEJ9.png', '\nPayment pending using qris\nPayment settled using qris'),
(71, 'INV/20250708/VII/VIII/00003', 'ecommerce', 'completed', '2025-07-08 14:37:13', '2025-07-15 14:37:13', 'paid', NULL, NULL, '15.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '360.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-07-08 14:49:00', 1, '2025-07-08 14:46:32', 'ga bayar', 1, 'Budi Rahman', 1, '2025-07-08 14:37:13', '2025-09-09 02:48:39', NULL, 'cod', NULL, NULL, NULL),
(72, 'INV/20250708/VII/VIII/00004', 'ecommerce', 'confirmed', '2025-07-08 15:00:33', '2025-07-15 15:00:33', 'paid', '79e063a7-f318-437f-a702-ff69a129e1a5', 'https://app.midtrans.com/snap/v4/redirection/79e063a7-f318-437f-a702-ff69a129e1a5', '35.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '330.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, '2025-07-08 15:02:20', NULL, NULL, NULL, 1, NULL, 0, '2025-07-08 15:00:33', '2025-07-08 15:02:20', NULL, 'automatic', NULL, NULL, '\nPayment pending using qris\nPayment settled using qris'),
(73, 'INV/20250708/VII/VIII/00005', 'ecommerce', 'completed', '2025-07-08 15:05:21', '2025-07-15 15:05:21', 'paid', NULL, NULL, '35.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '364.00', 'coba-coba', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, 1, '2025-07-08 15:06:40', 1, '2025-07-08 15:06:03', 'Belum Bayar', 1, NULL, 0, '2025-07-08 15:05:21', '2025-07-08 15:06:40', NULL, 'cod', NULL, 'assets/slides/pia0dnugOlfCT9q3KraMWthcl1tfS5mXGoybtUz4.png', NULL),
(74, 'INV/20250708/VII/VIII/00006', 'ecommerce', 'cancelled', '2025-07-08 15:12:29', '2025-07-15 15:12:29', 'unpaid', NULL, NULL, '35.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '90.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-08 15:12:59', 'belum bayar', 1, NULL, 0, '2025-07-08 15:12:29', '2025-07-08 15:12:59', NULL, 'cod', NULL, NULL, NULL),
(75, 'INV/20250726/VII/XXVI/00001', 'ecommerce', 'confirmed', '2025-07-26 12:24:58', '2025-08-02 12:24:58', 'paid', NULL, NULL, '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '646.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-07-26 12:24:58', '2025-07-26 12:25:32', NULL, 'toko', NULL, NULL, NULL),
(81, 'INV/20250812/VIII/XII/00001', 'ecommerce', 'created', '2025-08-12 13:50:36', '2025-08-19 13:50:36', 'unpaid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '272.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 06:50:36', '2025-08-12 06:50:36', NULL, 'toko', NULL, NULL, NULL),
(82, 'INV/20250812/VIII/XII/00002', 'ecommerce', 'confirmed', '2025-08-12 13:55:38', '2025-08-19 13:55:38', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '794.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 06:55:38', '2025-08-12 06:55:54', NULL, 'toko', NULL, NULL, NULL),
(83, 'INV/20250812/VIII/XII/00003', 'ecommerce', 'confirmed', '2025-08-12 13:59:01', '2025-08-19 13:59:01', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '361.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', NULL, '9121240210', 'admin@gmail.com', NULL, NULL, 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 06:59:01', '2025-08-12 06:59:13', NULL, 'qris', NULL, NULL, NULL),
(84, 'INV/20250812/VIII/XII/00004', 'ecommerce', 'confirmed', '2025-08-12 14:15:14', '2025-08-19 14:15:14', 'paid', NULL, NULL, '30.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '70.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 07:15:14', '2025-08-12 07:15:24', NULL, 'qris', NULL, NULL, NULL),
(85, 'INV/20250812/VIII/XII/00005', 'ecommerce', 'created', '2025-08-12 14:18:50', '2025-08-19 14:18:50', 'unpaid', '3812da97-01f5-4268-b318-5e3b424251a7', 'https://app.midtrans.com/snap/v4/redirection/3812da97-01f5-4268-b318-5e3b424251a7', '50.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '626.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 07:18:50', '2025-08-12 07:54:08', NULL, 'qris', NULL, NULL, NULL),
(86, 'INV/20250812/VIII/XII/00006', 'ecommerce', 'confirmed', '2025-08-12 15:16:54', '2025-08-19 15:16:54', 'paid', '3195a521-54ed-4b31-8606-c69c668bfec7', 'https://app.midtrans.com/snap/v4/redirection/3195a521-54ed-4b31-8606-c69c668bfec7', '20.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '449.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2025-08-12 15:18:18', NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 08:16:54', '2025-08-12 08:18:18', NULL, 'qris', NULL, NULL, '\nPayment completed successfully via qris'),
(87, 'INV/20250812/VIII/XII/00007', 'ecommerce', 'completed', '2025-08-12 15:26:10', '2025-08-19 15:26:10', 'paid', 'e869527f-91fb-4d8c-8d54-d83c64f04665', 'https://app.midtrans.com/snap/v4/redirection/e869527f-91fb-4d8c-8d54-d83c64f04665', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '297.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-08-12 15:34:39', NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 08:26:10', '2025-08-12 08:34:39', NULL, 'qris', NULL, NULL, '\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(88, 'INV/20250812/VIII/XII/00008', 'ecommerce', 'completed', '2025-08-12 17:08:11', '2025-08-19 17:08:11', 'paid', 'e4a8be97-37de-4007-8c2d-a3d05b553398', 'https://app.midtrans.com/snap/v4/redirection/e4a8be97-37de-4007-8c2d-a3d05b553398', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '281.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-08-12 17:24:13', NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 10:08:11', '2025-08-12 10:24:13', NULL, 'midtrans', NULL, NULL, '\nPayment completed successfully via midtrans\nOrder completed for offline store purchase'),
(89, 'INV-12-08-2025-17-24-44', 'ecommerce', 'completed', '2025-08-12 17:24:44', '2025-08-19 17:24:44', 'paid', '9c6a4491-8123-460a-a2ff-9b319294907c', 'https://app.midtrans.com/snap/v4/redirection/9c6a4491-8123-460a-a2ff-9b319294907c', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-08-12 17:26:33', NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 10:24:45', '2025-08-12 10:26:33', NULL, 'qris', NULL, 'assets/slides/Kgn3xHdKTnuLKMVqsUq9Yhrfqwm2oooFWHdOpIES.jpg', 'coba\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(90, 'INV-12-08-2025-20-05-46', 'ecommerce', 'completed', '2025-08-12 20:05:46', '2025-08-19 20:05:46', 'paid', '1ae02745-a3bd-42c5-ab4d-765dbf2d5f8b', 'https://app.midtrans.com/snap/v4/redirection/1ae02745-a3bd-42c5-ab4d-765dbf2d5f8b', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-08-12 20:09:31', NULL, NULL, NULL, 1, NULL, 0, '2025-08-12 13:05:46', '2025-08-12 13:09:31', NULL, 'qris', NULL, NULL, '\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(91, 'INV-13-08-2025-13-06-43', 'ecommerce', 'completed', '2025-08-13 13:06:43', '2025-08-20 13:06:43', 'paid', NULL, NULL, '100.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7100.00', 'coba', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'jne', NULL, 'JNE - REG', NULL, 0, NULL, NULL, NULL, 1, '2025-09-06 09:53:35', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 06:06:44', '2025-09-06 02:53:35', NULL, 'cod', NULL, 'assets/slides/5cQVIOj6nsuuZblWMrdGYvuLLWRDQfmeqllcZBTQ.jpg', '\nCOD order completed after payment confirmation'),
(94, 'INV-13-08-2025-13-28-19', 'ecommerce', 'created', '2025-08-13 13:28:19', '2025-08-20 13:28:19', 'unpaid', '483b3a8e-2c27-4fb5-aad6-1b7a2e196ffa', 'https://app.midtrans.com/snap/v4/redirection/483b3a8e-2c27-4fb5-aad6-1b7a2e196ffa', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 06:28:19', '2025-08-13 06:28:19', NULL, 'automatic', NULL, NULL, NULL),
(95, 'INV-13-08-2025-13-41-41', 'ecommerce', 'created', '2025-08-13 13:41:41', '2025-08-20 13:41:41', 'unpaid', '38040ebe-1f94-42c9-8beb-52b525c41543', 'https://app.midtrans.com/snap/v4/redirection/38040ebe-1f94-42c9-8beb-52b525c41543', '20.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '20.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 06:41:41', '2025-08-13 06:41:42', NULL, 'automatic', NULL, NULL, NULL),
(96, 'INV-13-08-2025-13-46-03', 'ecommerce', 'created', '2025-08-13 13:46:03', '2025-08-20 13:46:03', 'unpaid', '41b090b4-0d67-4b91-9139-fe946e47fd9f', 'https://app.midtrans.com/snap/v4/redirection/41b090b4-0d67-4b91-9139-fe946e47fd9f', '20.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '20.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 06:46:03', '2025-08-13 06:46:03', NULL, 'automatic', NULL, NULL, NULL),
(97, 'INV-13-08-2025-13-50-02', 'ecommerce', 'completed', '2025-08-13 13:50:02', '2025-08-20 13:50:02', 'paid', '6ee1fe70-d675-495a-8aa3-d16c30759253', 'https://app.midtrans.com/snap/v4/redirection/6ee1fe70-d675-495a-8aa3-d16c30759253', '20.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '20.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-13 13:50:35', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 06:50:02', '2025-08-13 06:50:35', NULL, 'automatic', NULL, NULL, NULL),
(98, 'INV-13-08-2025-13-59-39', 'ecommerce', 'completed', '2025-08-13 13:59:39', '2025-08-20 13:59:39', 'paid', '8e4b3307-0584-4c69-98c5-c317d8a633d4', 'https://app.midtrans.com/snap/v4/redirection/8e4b3307-0584-4c69-98c5-c317d8a633d4', '20.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7020.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'jne', NULL, 'JNE - REG', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-13 14:00:59', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 06:59:39', '2025-08-13 07:00:59', NULL, 'automatic', NULL, NULL, NULL),
(99, 'INV-13-08-2025-14-07-49', 'ecommerce', 'created', '2025-08-13 14:07:49', '2025-08-20 14:07:49', 'unpaid', '3c72f332-8f04-446f-8d9d-3aab63cb9c44', 'https://app.midtrans.com/snap/v4/redirection/3c72f332-8f04-446f-8d9d-3aab63cb9c44', '20.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7020.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'jne', NULL, 'JNE - REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 07:07:49', '2025-08-13 07:07:49', NULL, 'automatic', NULL, NULL, NULL),
(100, 'INV-13-08-2025-20-14-46', 'ecommerce', 'completed', '2025-08-13 20:14:46', '2025-08-20 20:14:46', 'paid', NULL, NULL, '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-08-13 20:31:28', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 13:14:46', '2025-08-13 13:31:28', NULL, 'manual', 'assets/bukti_pembayaran/PAWQjPP3XEC69wPpOMKDtelRSFCY8ioTtVjopeXh.jpg', NULL, NULL),
(101, 'INV-13-08-2025-20-33-17', 'ecommerce', 'completed', '2025-08-13 20:33:17', '2025-08-20 20:33:17', 'paid', '013309a5-937c-4d3f-8300-52ff6284b0f1', 'https://app.midtrans.com/snap/v4/redirection/013309a5-937c-4d3f-8300-52ff6284b0f1', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-13 20:34:04', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 13:33:17', '2025-08-13 13:34:04', NULL, 'automatic', NULL, NULL, NULL),
(102, 'INV-13-08-2025-20-45-44', 'ecommerce', 'completed', '2025-08-13 20:45:44', '2025-08-20 20:45:44', 'paid', 'f3046b7c-5cc5-412d-bab6-caf2b52c3d74', 'https://app.midtrans.com/snap/v4/redirection/f3046b7c-5cc5-412d-bab6-caf2b52c3d74', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-08-13 21:26:38', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 13:45:44', '2025-08-13 14:26:38', NULL, 'automatic', NULL, NULL, '\nSelf pickup confirmed by admin - customer has collected items from store\nSelf pickup confirmed by admin - customer has collected items from store'),
(103, 'INV-13-08-2025-21-27-43', 'ecommerce', 'completed', '2025-08-13 21:27:43', '2025-08-20 21:27:43', 'paid', 'b21ee919-1d2e-4fb7-9360-84fb396ea6cf', 'https://app.midtrans.com/snap/v4/redirection/b21ee919-1d2e-4fb7-9360-84fb396ea6cf', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-08-13 21:29:16', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 14:27:43', '2025-08-13 14:29:16', NULL, 'automatic', NULL, NULL, '\nPayment confirmed via finish redirect. Waiting for pickup confirmation.\nSelf pickup confirmed by admin - customer has collected items from store'),
(104, 'INV-13-08-2025-21-34-33', 'ecommerce', 'completed', '2025-08-13 21:34:33', '2025-08-20 21:34:33', 'paid', '042510c6-f8f5-4506-9ca7-645ed4508a6e', 'https://app.midtrans.com/snap/v4/redirection/042510c6-f8f5-4506-9ca7-645ed4508a6e', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-08-13 21:35:46', NULL, NULL, NULL, 1, NULL, 0, '2025-08-13 14:34:33', '2025-08-13 14:35:46', NULL, 'qris', NULL, NULL, 'pppp\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(105, 'INV-15-08-2025-00-38-52', 'ecommerce', 'completed', '2025-08-15 00:38:52', '2025-08-22 00:38:52', 'paid', '645c6e2f-bb87-4f7a-89d3-44d2c1f26bff', 'https://app.midtrans.com/snap/v4/redirection/645c6e2f-bb87-4f7a-89d3-44d2c1f26bff', '6.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '6.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-08-15 00:40:23', NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 17:38:52', '2025-08-14 17:40:23', NULL, 'qris', NULL, NULL, '\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(106, 'TEST-1755196633', 'ecommerce', '0', '2025-08-15 01:37:13', '2025-08-16 01:37:13', 'pending', NULL, NULL, '6.00', '0.00', '0.00', '0.00', '0.00', '10000.00', NULL, '10006.00', 'Test order from automated testing', 'Test', 'User', NULL, NULL, '081234567890', 'admin@admin.com', '1', '1', NULL, 'JNE', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:37:13', '2025-08-14 18:37:13', '2025-08-14 18:37:13', 'bank_transfer', NULL, NULL, NULL),
(107, 'TEST-1755196652', 'ecommerce', '0', '2025-08-15 01:37:32', '2025-08-16 01:37:32', 'pending', NULL, NULL, '6.00', '0.00', '0.00', '0.00', '0.00', '10000.00', NULL, '10006.00', 'Test order from automated testing', 'Test', 'User', NULL, NULL, '081234567890', 'admin@admin.com', '1', '1', NULL, 'JNE', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:37:32', '2025-08-14 18:37:32', '2025-08-14 18:37:32', 'bank_transfer', NULL, NULL, NULL),
(108, 'TEST-1755196669', 'ecommerce', '0', '2025-08-15 01:37:49', '2025-08-16 01:37:49', 'pending', NULL, NULL, '6.00', '0.00', '0.00', '0.00', '0.00', '10000.00', NULL, '10006.00', 'Test order from automated testing', 'Test', 'User', NULL, NULL, '081234567890', 'admin@admin.com', '1', '1', NULL, 'JNE', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:37:49', '2025-08-14 18:37:49', '2025-08-14 18:37:49', 'bank_transfer', NULL, NULL, NULL),
(109, 'TEST-1755196686', 'ecommerce', '0', '2025-08-15 01:38:06', '2025-08-16 01:38:06', 'pending', NULL, NULL, '6.00', '0.00', '0.00', '0.00', '0.00', '10000.00', NULL, '10006.00', 'Test order from automated testing', 'Test', 'User', NULL, NULL, '081234567890', 'admin@admin.com', '1', '1', NULL, 'JNE', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:38:06', '2025-08-14 18:38:06', '2025-08-14 18:38:06', 'bank_transfer', NULL, NULL, NULL),
(110, 'TEST-1755196706', 'ecommerce', '0', '2025-08-15 01:38:26', '2025-08-16 01:38:26', 'pending', NULL, NULL, '6.00', '0.00', '0.00', '0.00', '0.00', '10000.00', NULL, '10006.00', 'Test order from automated testing', 'Test', 'User', NULL, NULL, '081234567890', 'admin@admin.com', '1', '1', NULL, 'JNE', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:38:26', '2025-08-14 18:38:26', '2025-08-14 18:38:26', 'bank_transfer', NULL, NULL, NULL),
(111, 'TEST-1755196727', 'ecommerce', '0', '2025-08-15 01:38:47', '2025-08-16 01:38:47', 'pending', NULL, NULL, '6.00', '0.00', '0.00', '0.00', '0.00', '10000.00', NULL, '10006.00', 'Test order from automated testing', 'Test', 'User', NULL, NULL, '081234567890', 'admin@admin.com', '1', '1', NULL, 'JNE', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:38:47', '2025-08-14 18:38:47', '2025-08-14 18:38:47', 'bank_transfer', NULL, NULL, NULL),
(112, 'INV-15-08-2025-01-47-41', 'ecommerce', 'created', '2025-08-15 01:47:41', '2025-08-22 01:47:41', 'unpaid', 'e995a530-c4d0-4ad9-997f-bca8f745cd14', 'https://app.midtrans.com/snap/v4/redirection/e995a530-c4d0-4ad9-997f-bca8f745cd14', '12.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7012.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'jne', NULL, 'JNE - REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:47:41', '2025-08-14 18:47:41', NULL, 'automatic', NULL, NULL, NULL),
(113, 'INV-15-08-2025-01-48-40', 'ecommerce', 'completed', '2025-08-15 01:48:40', '2025-08-22 01:48:40', 'paid', 'b5919dad-6afe-4d51-8fa5-8689759de5ab', 'https://app.midtrans.com/snap/v4/redirection/b5919dad-6afe-4d51-8fa5-8689759de5ab', '12.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '12.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-08-15 01:50:02', NULL, NULL, NULL, 1, NULL, 0, '2025-08-14 18:48:40', '2025-08-14 18:50:02', NULL, 'automatic', NULL, NULL, '\nPayment confirmed via finish redirect. Waiting for pickup confirmation.\nSelf pickup confirmed by admin - customer has collected items from store'),
(114, 'INV-06-09-2025-18-04-14', 'ecommerce', 'created', '2025-09-06 18:04:14', '2025-09-13 18:04:14', 'unpaid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-06 11:04:14', '2025-09-06 11:04:14', NULL, 'manual', NULL, NULL, NULL),
(115, 'INV-06-09-2025-18-05-03', 'ecommerce', 'created', '2025-09-06 18:05:03', '2025-09-13 18:05:03', 'unpaid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-06 11:05:03', '2025-09-06 11:05:03', NULL, 'manual', NULL, NULL, NULL),
(116, 'INV-06-09-2025-18-05-59', 'ecommerce', 'completed', '2025-09-06 18:05:59', '2025-09-13 18:05:59', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-09-08 10:01:20', NULL, NULL, NULL, 1, NULL, 0, '2025-09-06 11:05:59', '2025-09-08 03:01:20', NULL, 'manual', 'assets/bukti_pembayaran/mk85b91iLflVGJ0gKfUNU3r9lMypkFNKVcqXvVfH.png', NULL, '\nSelf pickup confirmed by admin - customer has collected items from store'),
(117, 'INV-08-09-2025-10-22-46', 'ecommerce', 'completed', '2025-09-08 10:22:46', '2025-09-15 10:22:46', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '7000.00', NULL, '7003.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'jne', NULL, 'JNE - REG', NULL, 0, NULL, NULL, NULL, 1, '2025-09-08 10:24:17', NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 03:22:46', '2025-09-08 03:24:17', NULL, 'manual', 'assets/bukti_pembayaran/HaGon4QZ61lmI2vf5OwFHtTXTdbHcSIhWr65FMUh.png', NULL, NULL),
(118, 'INV-08-09-2025-11-19-42', 'ecommerce', 'completed', '2025-09-08 11:19:42', '2025-09-15 11:19:42', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-08 11:19:56', NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:19:42', '2025-09-08 04:19:56', NULL, 'toko', NULL, 'assets/slides/fvwyNTOs8xaEGY77NdvcSyD7o0okGUu9Z46vP9lW.png', 'yayaya\nOrder completed for offline store purchase'),
(119, 'INV-08-09-2025-11-21-47', 'ecommerce', 'completed', '2025-09-08 11:21:47', '2025-09-15 11:21:47', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-08 11:21:52', NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:21:47', '2025-09-08 04:21:52', NULL, 'toko', NULL, NULL, 'jajaja\nOrder completed for offline store purchase'),
(120, 'TEST-1757305589', 'ecommerce', 'created', '2025-09-08 11:26:29', '2025-09-15 11:26:29', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Test', 'Admin', 'Test Address', NULL, '123456789', 'test@admin.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:26:29', '2025-09-08 04:26:29', '2025-09-08 04:26:29', 'toko', NULL, NULL, NULL),
(121, 'SIMPLE-1757305682', 'ecommerce', 'created', '2025-09-08 11:28:02', '2025-09-15 11:28:02', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Test', 'Simple', 'Test Address', NULL, '123456789', 'test@simple.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:28:02', '2025-09-08 04:28:02', '2025-09-08 04:28:02', 'toko', NULL, NULL, NULL),
(122, 'VARIANT-1757305682', 'ecommerce', 'created', '2025-09-08 11:28:02', '2025-09-15 11:28:02', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Test', 'Variant', 'Test Address', NULL, '123456789', 'test@variant.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:28:02', '2025-09-08 04:28:02', '2025-09-08 04:28:02', 'toko', NULL, NULL, NULL),
(124, 'VALIDTEST-1757305774', 'ecommerce', 'created', '2025-09-08 11:29:34', '2025-09-15 11:29:34', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Test', 'Valid', 'Test Address', NULL, '123456789', 'test@valid.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:29:34', '2025-09-08 04:29:34', '2025-09-08 04:29:34', 'toko', NULL, NULL, NULL),
(125, 'MIXED-1757305774', 'ecommerce', 'created', '2025-09-08 11:29:34', '2025-09-15 11:29:34', 'unpaid', NULL, NULL, '115000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '115000.00', NULL, 'Test', 'Mixed', 'Test Address', NULL, '123456789', 'test@mixed.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:29:34', '2025-09-08 04:29:34', '2025-09-08 04:29:34', 'toko', NULL, NULL, NULL),
(126, 'FRONTEND-1757305841', 'ecommerce', 'created', '2025-09-08 11:30:41', '2025-09-15 11:30:41', 'unpaid', NULL, NULL, '30000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '30000.00', NULL, 'Frontend', 'Customer', 'Frontend Address', NULL, '123456789', 'frontend@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:30:41', '2025-09-08 04:30:41', NULL, 'midtrans', NULL, NULL, NULL),
(129, 'FRONTEND-1757305860-9238', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '30000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '30000.00', NULL, 'Frontend', 'Customer', 'Frontend Address', NULL, '123456789', 'frontend@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:00', '2025-09-08 04:31:00', 'midtrans', NULL, NULL, NULL),
(130, 'FRONTEND-1757305860-8843', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Frontend', 'Customer', 'Frontend Address', NULL, '123456789', 'frontend@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:00', '2025-09-08 04:31:00', 'midtrans', NULL, NULL, NULL),
(131, 'ADMIN-1757305860-1599', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Customer', 'Admin Address', NULL, '123456789', 'admin@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:00', '2025-09-08 04:31:00', 'toko', NULL, NULL, NULL),
(132, 'ADMIN-1757305860-8040', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '200000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '200000.00', NULL, 'Admin', 'Customer', 'Admin Address', NULL, '123456789', 'admin@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:00', '2025-09-08 04:31:00', 'toko', NULL, NULL, NULL),
(133, 'FRONTEND-1757305860-7264', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Frontend', 'Customer', 'Frontend Address', NULL, '123456789', 'frontend@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:01', '2025-09-08 04:31:01', 'midtrans', NULL, NULL, NULL),
(134, 'FRONTEND-1757305860-3691', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Frontend', 'Customer', 'Frontend Address', NULL, '123456789', 'frontend@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:01', '2025-09-08 04:31:01', 'midtrans', NULL, NULL, NULL),
(135, 'FRONTEND-1757305860-9817', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Frontend', 'Customer', 'Frontend Address', NULL, '123456789', 'frontend@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:01', '2025-09-08 04:31:01', 'midtrans', NULL, NULL, NULL),
(136, 'ADMIN-1757305860-5960', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Admin', 'Customer', 'Admin Address', NULL, '123456789', 'admin@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:01', '2025-09-08 04:31:01', 'toko', NULL, NULL, NULL),
(137, 'ADMIN-1757305860-1745', 'ecommerce', 'created', '2025-09-08 11:31:00', '2025-09-15 11:31:00', 'unpaid', NULL, NULL, '100000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '100000.00', NULL, 'Admin', 'Customer', 'Admin Address', NULL, '123456789', 'admin@test.com', '1', '1', 12345, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:31:00', '2025-09-08 04:31:01', '2025-09-08 04:31:01', 'toko', NULL, NULL, NULL);
INSERT INTO `orders` (`id`, `code`, `order_type`, `status`, `order_date`, `payment_due`, `payment_status`, `payment_token`, `payment_url`, `base_total_price`, `tax_amount`, `tax_percent`, `discount_amount`, `discount_percent`, `shipping_cost`, `original_shipping_cost`, `grand_total`, `note`, `customer_first_name`, `customer_last_name`, `customer_address1`, `customer_address2`, `customer_phone`, `customer_email`, `customer_city_id`, `customer_province_id`, `customer_postcode`, `shipping_courier`, `original_shipping_courier`, `shipping_service_name`, `original_shipping_service_name`, `shipping_cost_adjusted`, `shipping_adjustment_note`, `shipping_adjusted_at`, `shipping_adjusted_by`, `approved_by`, `approved_at`, `cancelled_by`, `cancelled_at`, `cancellation_note`, `user_id`, `handled_by`, `use_employee_tracking`, `created_at`, `updated_at`, `deleted_at`, `payment_method`, `payment_slip`, `attachments`, `notes`) VALUES
(138, 'INV-08-09-2025-11-34-45', 'ecommerce', 'completed', '2025-09-08 11:34:45', '2025-09-15 11:34:45', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-08 11:34:50', NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:34:45', '2025-09-08 04:34:50', NULL, 'toko', NULL, NULL, 'jajajaja\nOrder completed for offline store purchase'),
(139, 'INV-08-09-2025-11-47-31', 'ecommerce', 'completed', '2025-09-08 11:47:31', '2025-09-15 11:47:31', 'paid', '9c64b4c6-ee05-4404-a8f2-bf0c085427dc', 'https://app.midtrans.com/snap/v4/redirection/9c64b4c6-ee05-4404-a8f2-bf0c085427dc', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-08 11:48:45', NULL, NULL, NULL, 1, NULL, 0, '2025-09-08 04:47:31', '2025-09-08 04:48:45', NULL, 'qris', NULL, NULL, 'bismillah\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(140, 'INV-09-09-2025-08-47-47', 'ecommerce', 'completed', '2025-09-09 08:47:47', '2025-09-16 08:47:47', 'paid', NULL, NULL, '7.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '7.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 08:49:58', NULL, NULL, NULL, 1, NULL, 0, '2025-09-09 01:47:47', '2025-09-09 01:49:58', NULL, 'manual', 'assets/bukti_pembayaran/BdTKfr9cJKgTuKViJmfrmDFmXEtv9Kfgy3oYSxVU.png', NULL, '\nSelf pickup confirmed by admin - customer has collected items from store'),
(141, 'INV-09-09-2025-08-54-37', 'ecommerce', 'completed', '2025-09-09 08:54:37', '2025-09-16 08:54:37', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 09:11:45', NULL, NULL, NULL, 1, NULL, 0, '2025-09-09 01:54:37', '2025-09-09 02:11:45', NULL, 'manual', 'assets/bukti_pembayaran/ITWmxeqFUJPhWofsvIVFTI1UvrrCkBM6HpTiurKE.png', NULL, '\nSelf pickup confirmed by admin - customer has collected items from store'),
(142, 'INV-09-09-2025-09-55-07', 'ecommerce', 'completed', '2025-09-09 09:55:07', '2025-09-16 09:55:07', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', 'test', 'admin', 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 09:57:07', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-09 02:55:07', '2025-09-09 03:08:08', NULL, 'manual', 'assets/bukti_pembayaran/Urv6qNhtdHZO6fBiEip25lq4Xk1V4VYEaGBmpfUj.png', 'assets/slides/RB7Vj8jduuVbvvEJVSFMan9kiJsCFkrumHDTTpkh.png', '\nSelf pickup confirmed by admin - customer has collected items from store'),
(143, 'INV-09-09-2025-10-53-55', 'ecommerce', 'completed', '2025-09-09 10:53:55', '2025-09-16 10:53:55', 'paid', 'fdd2c476-4216-4480-b324-f0034245824f', 'https://app.midtrans.com/snap/v4/redirection/fdd2c476-4216-4480-b324-f0034245824f', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 10:55:19', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-09 03:53:55', '2025-09-09 04:09:19', NULL, 'qris', NULL, 'assets/slides/kZTf4QrSuz8WdJsUR5lDUwBc1BBN0Jby1jw0TyfW.png', 'hehehe\nPayment completed successfully via qris\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(144, 'INV-09-09-2025-11-10-37', 'ecommerce', 'completed', '2025-09-09 11:10:37', '2025-09-16 11:10:37', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 11:11:05', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-09 04:10:37', '2025-09-09 04:11:05', NULL, 'toko', NULL, NULL, 'testing\nOrder completed for offline store purchase'),
(145, 'INV-09-09-2025-11-21-21', 'ecommerce', 'completed', '2025-09-09 11:21:21', '2025-09-16 11:21:21', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 11:21:33', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-09 04:21:21', '2025-09-09 04:21:33', NULL, 'toko', NULL, NULL, 'tes lagi\nOrder completed for offline store purchase'),
(146, 'INV-09-09-2025-13-59-35', 'ecommerce', 'completed', '2025-09-09 13:59:35', '2025-09-16 13:59:35', 'paid', NULL, NULL, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-09 13:59:45', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-09 06:59:35', '2025-09-09 06:59:45', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(147, 'INV-10-09-2025-09-09-27', 'ecommerce', 'completed', '2025-09-10 09:09:27', '2025-09-17 09:09:27', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-10 09:09:49', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-10 02:09:27', '2025-09-10 02:09:49', NULL, 'toko', NULL, NULL, 'coba seh\nOrder completed for offline store purchase'),
(152, 'INV-10-09-2025-10-13-36', 'ecommerce', 'completed', '2025-09-10 10:13:36', '2025-09-17 10:13:36', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-10 10:13:57', NULL, NULL, NULL, 1, 'Reza', 1, '2025-09-10 03:13:36', '2025-09-10 03:13:57', NULL, 'transfer', NULL, NULL, 'testing\nOrder completed for offline store purchase'),
(154, 'INV-10-09-2025-10-18-13', 'ecommerce', 'completed', '2025-09-10 10:18:13', '2025-09-17 10:18:13', 'paid', '27d09914-cac9-429c-8b5d-b1a1157dbbc0', 'https://app.midtrans.com/snap/v4/redirection/27d09914-cac9-429c-8b5d-b1a1157dbbc0', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2025-09-10 10:19:09', NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 03:18:13', '2025-09-10 03:19:09', NULL, 'qris', NULL, NULL, 'tes\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(155, 'INV-10-09-2025-10-20-09', 'ecommerce', 'created', '2025-09-10 10:20:09', '2025-09-17 10:20:09', 'unpaid', '224aa80a-fca3-4b1a-9111-0d14bdd16a75', 'https://app.midtrans.com/snap/v4/redirection/224aa80a-fca3-4b1a-9111-0d14bdd16a75', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 03:20:09', '2025-09-10 03:20:23', '2025-09-10 03:20:23', 'qris', NULL, NULL, NULL),
(156, 'INV-10-09-2025-10-20-34', 'ecommerce', 'created', '2025-09-10 10:20:34', '2025-09-17 10:20:34', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 03:20:34', '2025-09-10 03:20:41', '2025-09-10 03:20:41', 'toko', NULL, NULL, NULL),
(165, 'INV-10-09-2025-10-40-19', 'ecommerce', 'created', '2025-09-10 10:40:19', '2025-09-17 10:40:19', 'unpaid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 'Test order for Direct Bank Transfer', 'Test User', 'Test User', 'Jl. Test No. 123', 'RT 01 RW 02', '081234567890', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-10 03:40:19', '2025-09-10 03:40:19', NULL, 'manual', NULL, NULL, NULL),
(167, 'INV-10-09-2025-10-40-23', 'ecommerce', 'created', '2025-09-10 10:40:23', '2025-09-17 10:40:23', 'unpaid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 'Test order for Cash on Delivery', 'Test User', 'Test User', 'Jl. Test No. 123', 'RT 01 RW 02', '081234567890', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-10 03:40:23', '2025-09-10 03:40:23', NULL, 'cod', NULL, NULL, NULL),
(168, 'INV-10-09-2025-10-40-25', 'ecommerce', 'created', '2025-09-10 10:40:25', '2025-09-17 10:40:25', 'unpaid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 'Test order for Bayar Di Toko', 'Test User', 'Test User', 'Jl. Test No. 123', 'RT 01 RW 02', '081234567890', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-10 03:40:25', '2025-09-10 03:40:25', NULL, 'toko', NULL, NULL, NULL),
(171, 'INV-10-09-2025-11-01-33', 'ecommerce', 'created', '2025-09-10 11:01:33', '2025-09-17 11:01:33', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test order', 'Test Customer 1757476892', 'Test Customer 1757476892', 'Jl. Test No. 123', '', '081234567890', 'test1757476892@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:01:33', '2025-09-10 04:01:33', NULL, 'manual', NULL, NULL, NULL),
(172, 'INV-10-09-2025-11-04-23', 'ecommerce', 'created', '2025-09-10 11:04:23', '2025-09-17 11:04:23', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Debug test order', 'Debug Test 1757477063', 'Debug Test 1757477063', 'Jl. Debug No. 123', '', '081234567890', 'debug1757477063@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:04:23', '2025-09-10 04:04:23', NULL, 'manual', NULL, NULL, NULL),
(173, 'INV-10-09-2025-11-04-34', 'ecommerce', 'created', '2025-09-10 11:04:34', '2025-09-17 11:04:34', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test order for manual', 'Test Customer 17574770743138', 'Test Customer 17574770743138', 'Jl. Test No. 123', '', '081234567890', 'test17574770743138@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:04:34', '2025-09-10 04:04:34', NULL, 'manual', NULL, NULL, NULL),
(174, 'INV-10-09-2025-11-04-35', 'ecommerce', 'created', '2025-09-10 11:04:34', '2025-09-17 11:04:34', 'unpaid', '1af1846e-d93a-46d6-8061-bab68f6ce20b', 'https://app.midtrans.com/snap/v4/redirection/1af1846e-d93a-46d6-8061-bab68f6ce20b', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test order for automatic', 'Test Customer 17574770743899', 'Test Customer 17574770743899', 'Jl. Test No. 123', '', '081234567890', 'test17574770743899@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:04:35', '2025-09-10 04:04:35', NULL, 'automatic', NULL, NULL, NULL),
(175, 'INV-10-09-2025-11-04-36', 'ecommerce', 'created', '2025-09-10 11:04:35', '2025-09-17 11:04:35', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test order for cod', 'Test Customer 17574770757582', 'Test Customer 17574770757582', 'Jl. Test No. 123', '', '081234567890', 'test17574770757582@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:04:36', '2025-09-10 04:04:36', NULL, 'cod', NULL, NULL, NULL),
(176, 'INV-10-09-2025-11-04-37', 'ecommerce', 'created', '2025-09-10 11:04:37', '2025-09-17 11:04:37', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test order for toko', 'Test Customer 17574770774809', 'Test Customer 17574770774809', 'Jl. Test No. 123', '', '081234567890', 'test17574770774809@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:04:37', '2025-09-10 04:04:37', NULL, 'toko', NULL, NULL, NULL),
(177, 'INV-10-09-2025-11-08-48', 'ecommerce', 'created', '2025-09-10 11:08:48', '2025-09-17 11:08:48', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Stress test for manual_self', 'Test manual_self 17574773283950', 'Test manual_self 17574773283950', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_manual_self_17574773283950@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:48', '2025-09-10 04:08:48', NULL, 'manual', NULL, NULL, NULL),
(178, 'INV-10-09-2025-11-08-49', 'ecommerce', 'created', '2025-09-10 11:08:49', '2025-09-17 11:08:49', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', NULL, '30000.00', 'Stress test for manual_courier', 'Test manual_courier 17574773289726', 'Test manual_courier 17574773289726', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_manual_courier_17574773289726@example.com', '388', '18', 12345, 'jne', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:49', '2025-09-10 04:08:49', NULL, 'manual', NULL, NULL, NULL),
(179, 'INV-10-09-2025-11-08-50', 'ecommerce', 'created', '2025-09-10 11:08:49', '2025-09-17 11:08:49', 'unpaid', 'cfac530c-4e95-499d-8356-a70293592f04', 'https://app.midtrans.com/snap/v4/redirection/cfac530c-4e95-499d-8356-a70293592f04', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Stress test for automatic_self', 'Test automatic_self 17574773292092', 'Test automatic_self 17574773292092', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_automatic_self_17574773292092@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:50', '2025-09-10 04:08:50', NULL, 'automatic', NULL, NULL, NULL),
(180, 'INV-10-09-2025-11-08-51', 'ecommerce', 'created', '2025-09-10 11:08:50', '2025-09-17 11:08:50', 'unpaid', 'd08cc9c2-81a6-485d-98cc-e05c9949c59c', 'https://app.midtrans.com/snap/v4/redirection/d08cc9c2-81a6-485d-98cc-e05c9949c59c', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', NULL, '30000.00', 'Stress test for automatic_courier', 'Test automatic_courier 17574773303840', 'Test automatic_courier 17574773303840', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_automatic_courier_17574773303840@example.com', '388', '18', 12345, 'jne', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:51', '2025-09-10 04:08:52', NULL, 'automatic', NULL, NULL, NULL),
(181, 'INV-10-09-2025-11-08-52', 'ecommerce', 'created', '2025-09-10 11:08:52', '2025-09-17 11:08:52', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Stress test for cod_self', 'Test cod_self 17574773323157', 'Test cod_self 17574773323157', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_cod_self_17574773323157@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:52', '2025-09-10 04:08:52', NULL, 'cod', NULL, NULL, NULL),
(182, 'INV-10-09-2025-11-08-53', 'ecommerce', 'created', '2025-09-10 11:08:52', '2025-09-17 11:08:52', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', NULL, '30000.00', 'Stress test for cod_courier', 'Test cod_courier 17574773324633', 'Test cod_courier 17574773324633', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_cod_courier_17574773324633@example.com', '388', '18', 12345, 'jne', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:53', '2025-09-10 04:08:53', NULL, 'cod', NULL, NULL, NULL),
(183, 'INV-10-09-2025-11-08-54', 'ecommerce', 'created', '2025-09-10 11:08:53', '2025-09-17 11:08:53', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Stress test for toko_self', 'Test toko_self 17574773339025', 'Test toko_self 17574773339025', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_toko_self_17574773339025@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:54', '2025-09-10 04:08:54', NULL, 'toko', NULL, NULL, NULL),
(184, 'INV-10-09-2025-11-08-55', 'ecommerce', 'created', '2025-09-10 11:08:55', '2025-09-17 11:08:55', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', NULL, '30000.00', 'Stress test for toko_courier', 'Test toko_courier 17574773345951', 'Test toko_courier 17574773345951', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_toko_courier_17574773345951@example.com', '388', '18', 12345, 'jne', NULL, 'REG', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:08:55', '2025-09-10 04:08:55', NULL, 'toko', NULL, NULL, NULL),
(187, 'INV-10-09-2025-11-13-06', 'ecommerce', 'created', '2025-09-10 11:13:06', '2025-09-17 11:13:06', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Frontend test order', 'Test User Frontend', 'Test User Frontend', 'Jl. Test Frontend No. 123', 'Suite 456', '081234567890', 'frontend_test_1757477586@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:13:06', '2025-09-10 04:13:06', NULL, 'manual', NULL, NULL, NULL),
(188, 'INV-10-09-2025-11-18-11', 'ecommerce', 'created', '2025-09-10 11:18:11', '2025-09-17 11:18:11', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test manual self pickup', 'John Doe', 'John Doe', 'Jl. Merdeka No. 123', '', '081234567890', 'john_1757477891@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:18:11', '2025-09-10 04:18:11', NULL, 'manual', NULL, NULL, NULL),
(189, 'INV-10-09-2025-11-18-12', 'ecommerce', 'created', '2025-09-10 11:18:11', '2025-09-17 11:18:11', 'unpaid', '296c5299-aaaa-45c7-969b-93355a8f6a49', 'https://app.midtrans.com/snap/v4/redirection/296c5299-aaaa-45c7-969b-93355a8f6a49', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test midtrans self pickup', 'Jane Smith', 'Jane Smith', 'Jl. Sudirman No. 456', 'Apt 789', '081987654321', 'jane_1757477891@example.com', '388', '18', 54321, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:18:12', '2025-09-10 04:18:12', NULL, 'automatic', NULL, NULL, NULL),
(190, 'INV-10-09-2025-11-19-09', 'ecommerce', 'created', '2025-09-10 11:19:09', '2025-09-17 11:19:09', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test manual self pickup', 'John Doe', 'John Doe', 'Jl. Merdeka No. 123', '', '081234567890', 'john_1757477949@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:19:09', '2025-09-10 04:19:09', NULL, 'manual', NULL, NULL, NULL),
(191, 'INV-10-09-2025-11-19-10', 'ecommerce', 'created', '2025-09-10 11:19:09', '2025-09-17 11:19:09', 'unpaid', 'ce449b82-0023-4cd6-af3c-06e61b39c709', 'https://app.midtrans.com/snap/v4/redirection/ce449b82-0023-4cd6-af3c-06e61b39c709', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test midtrans self pickup', 'Jane Smith', 'Jane Smith', 'Jl. Sudirman No. 456', 'Apt 789', '081987654321', 'jane_1757477949@example.com', '388', '18', 54321, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:19:10', '2025-09-10 04:19:10', NULL, 'automatic', NULL, NULL, NULL),
(192, 'INV-10-09-2025-11-20-25', 'ecommerce', 'created', '2025-09-10 11:20:25', '2025-09-17 11:20:25', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test manual self pickup', 'John Doe', 'John Doe', 'Jl. Merdeka No. 123', '', '081234567890', 'john_1757478025@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:20:25', '2025-09-10 04:20:25', NULL, 'manual', NULL, NULL, NULL),
(193, 'INV-10-09-2025-11-20-26', 'ecommerce', 'created', '2025-09-10 11:20:25', '2025-09-17 11:20:25', 'unpaid', 'd218deb4-ddbe-4fb7-8332-fcacd1a59521', 'https://app.midtrans.com/snap/v4/redirection/d218deb4-ddbe-4fb7-8332-fcacd1a59521', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test midtrans self pickup', 'Jane Smith', 'Jane Smith', 'Jl. Sudirman No. 456', 'Apt 789', '081987654321', 'jane_1757478025@example.com', '388', '18', 54321, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:20:26', '2025-09-10 04:20:27', NULL, 'automatic', NULL, NULL, NULL),
(194, 'INV-10-09-2025-11-21-42', 'ecommerce', 'created', '2025-09-10 11:21:42', '2025-09-17 11:21:42', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test manual payment with self pickup', 'Test User Manual', 'Test User Manual', 'Jl. Test Manual No. 123', '', '081234567890', 'manual_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:21:42', '2025-09-10 04:21:42', NULL, 'manual', NULL, NULL, NULL),
(195, 'INV-10-09-2025-11-21-43', 'ecommerce', 'created', '2025-09-10 11:21:42', '2025-09-17 11:21:42', 'unpaid', '21d1c364-0767-490e-a89f-415c2701d4f2', 'https://app.midtrans.com/snap/v4/redirection/21d1c364-0767-490e-a89f-415c2701d4f2', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test midtrans payment with self pickup', 'Test User Midtrans', 'Test User Midtrans', 'Jl. Test Midtrans No. 456', 'Unit 789', '081987654321', 'midtrans_1757478102@example.com', '388', '18', 54321, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:21:43', '2025-09-10 04:21:43', NULL, 'automatic', NULL, NULL, NULL),
(196, 'INV-10-09-2025-11-21-44', 'ecommerce', 'created', '2025-09-10 11:21:43', '2025-09-17 11:21:43', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test COD payment with self pickup', 'Test User COD', 'Test User COD', 'Jl. Test COD No. 789', '', '081555444333', 'cod_1757478102@example.com', '388', '18', 67890, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:21:44', '2025-09-10 04:21:44', NULL, 'cod', NULL, NULL, NULL),
(197, 'INV-10-09-2025-11-21-45', 'ecommerce', 'created', '2025-09-10 11:21:45', '2025-09-17 11:21:45', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test toko payment with self pickup', 'Test User Toko', 'Test User Toko', 'Jl. Test Toko No. 999', 'Blok A', '081666777888', 'toko_1757478102@example.com', '388', '18', 99999, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:21:45', '2025-09-10 04:21:45', NULL, 'toko', NULL, NULL, NULL),
(201, 'INV-10-09-2025-11-38-45', 'ecommerce', 'created', '2025-09-10 11:38:45', '2025-09-17 11:38:45', 'unpaid', '98302292-efd3-4744-b15d-e86ed95f375e', 'https://app.midtrans.com/snap/v4/redirection/98302292-efd3-4744-b15d-e86ed95f375e', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'reza', 'reza', 'Jalan Gedongan VII/12 A', 'Magersari', '085155228237', 'reza@gmail.com', '388', '18', 61319, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 189, NULL, 0, '2025-09-10 04:38:45', '2025-09-10 04:38:45', NULL, 'automatic', NULL, NULL, NULL),
(202, 'INV-10-09-2025-11-41-42', 'ecommerce', 'confirmed', '2025-09-10 11:41:42', '2025-09-17 11:41:42', 'paid', '2e00776d-4192-455c-b330-b03211e4f20b', 'https://app.midtrans.com/snap/v4/redirection/2e00776d-4192-455c-b330-b03211e4f20b', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '3.00', NULL, 'reza', 'reza', 'Jalan Gedongan VII/12 A', 'Magersari', '085155228237', 'reza@gmail.com', '388', '18', 61319, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, '2025-09-10 11:42:16', NULL, NULL, NULL, 189, NULL, 0, '2025-09-10 04:41:42', '2025-09-10 04:42:16', NULL, 'automatic', NULL, NULL, '\nPayment confirmed via finish redirect. Waiting for pickup confirmation.'),
(204, 'INV-10-09-2025-11-53-15', 'ecommerce', 'created', '2025-09-10 11:53:15', '2025-09-17 11:53:15', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test checkout for simple product - Full Flow', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:53:15', '2025-09-10 04:53:15', NULL, 'toko', NULL, NULL, NULL),
(205, 'INV-10-09-2025-11-53-16', 'ecommerce', 'created', '2025-09-10 11:53:16', '2025-09-17 11:53:16', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test courier delivery', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:53:16', '2025-09-10 04:53:16', NULL, 'toko', NULL, NULL, NULL),
(206, 'INV-10-09-2025-11-53-17', 'ecommerce', 'created', '2025-09-10 11:53:16', '2025-09-17 11:53:16', 'unpaid', 'f63a9485-f12e-43da-bb90-f4f11c9e2f1e', 'https://app.midtrans.com/snap/v4/redirection/f63a9485-f12e-43da-bb90-f4f11c9e2f1e', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test automatic payment', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 04:53:17', '2025-09-10 04:53:17', NULL, 'automatic', NULL, NULL, NULL),
(207, 'INV-10-09-2025-12-00-37', 'ecommerce', 'created', '2025-09-10 12:00:37', '2025-09-17 12:00:37', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test checkout for simple product - Full Flow', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 05:00:37', '2025-09-10 05:00:37', NULL, 'toko', NULL, NULL, NULL),
(208, 'INV-10-09-2025-12-00-38', 'ecommerce', 'created', '2025-09-10 12:00:38', '2025-09-17 12:00:38', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test courier delivery', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 05:00:38', '2025-09-10 05:00:38', NULL, 'toko', NULL, NULL, NULL),
(209, 'INV-10-09-2025-12-00-39', 'ecommerce', 'created', '2025-09-10 12:00:38', '2025-09-17 12:00:38', 'unpaid', '9f749972-7088-404b-8488-0a5af21f7da5', 'https://app.midtrans.com/snap/v4/redirection/9f749972-7088-404b-8488-0a5af21f7da5', '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Test automatic payment', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 05:00:39', '2025-09-10 05:00:39', NULL, 'automatic', NULL, NULL, NULL),
(210, 'INV-10-09-2025-12-01-19', 'ecommerce', 'created', '2025-09-10 12:01:19', '2025-09-17 12:01:19', 'unpaid', NULL, NULL, '2500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '2500.00', 'Stress test: Self Pickup + Store Payment', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 05:01:19', '2025-09-10 05:01:19', NULL, 'toko', NULL, NULL, NULL),
(211, 'INV-10-09-2025-12-01-20', 'ecommerce', 'created', '2025-09-10 12:01:19', '2025-09-17 12:01:19', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Stress test: Self Pickup + Manual Transfer', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 05:01:20', '2025-09-10 05:01:20', NULL, 'manual', NULL, NULL, NULL),
(212, 'INV-10-09-2025-12-01-21', 'ecommerce', 'created', '2025-09-10 12:01:20', '2025-09-17 12:01:20', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', 'Stress test: Courier + Store Payment', 'Test User Toko', 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-10 05:01:21', '2025-09-10 05:01:21', NULL, 'toko', NULL, NULL, NULL),
(213, 'INV-10-09-2025-12-09-39', 'ecommerce', 'completed', '2025-09-10 12:09:39', '2025-09-17 12:09:39', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'reza', 'reza', 'Jalan Gedongan VII/12 A', 'Magersari', '085155228237', 'reza@gmail.com', '388', '18', 61319, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, 190, '2025-09-10 12:16:27', NULL, NULL, NULL, 189, 'Reza', 1, '2025-09-10 05:09:39', '2025-09-10 05:16:27', NULL, 'manual', NULL, NULL, '\nSelf pickup confirmed by admin - customer has collected items from store'),
(214, 'INV-13-09-2025-11-47-42', 'ecommerce', 'created', '2025-09-13 11:47:42', '2025-09-20 11:47:42', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Raihan Rizki Alfareza', 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 190, NULL, 0, '2025-09-13 04:47:42', '2025-09-13 04:47:42', NULL, 'toko', NULL, NULL, NULL),
(215, 'INV-13-09-2025-11-49-06', 'ecommerce', 'completed', '2025-09-13 11:49:06', '2025-09-20 11:49:06', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '13000.00', '30000.00', NULL, 'Raihan Rizki Alfareza', 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, 'sicepat', 'jne', 'CEPAT | OKE', 'JNE - YES', 1, 'DI TEMPAT SAYA GAADA KAK', '2025-09-13 05:39:48', 190, 190, '2025-09-13 12:40:51', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-13 04:49:06', '2025-09-13 05:40:51', NULL, 'manual', NULL, NULL, NULL),
(223, 'INV-14-09-2025-14-15-58', 'ecommerce', 'created', '2025-09-14 14:15:58', '2025-09-21 14:15:58', 'unpaid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15123.00', 'Test order with fixed cart', 'Test User', 'Test User', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:15:58', '2025-09-14 07:15:58', NULL, 'manual', NULL, NULL, NULL),
(224, 'INV-14-09-2025-14-16-59', 'ecommerce', 'created', '2025-09-14 14:16:59', '2025-09-21 14:16:59', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33135.00', 'Test order: Manual/Direct Bank Transfer + Self Pickup', 'Test User 1757834219', 'Test User 1757834219', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:16:59', '2025-09-14 07:16:59', NULL, 'manual', NULL, NULL, NULL),
(225, 'INV-14-09-2025-14-17-39', 'ecommerce', 'created', '2025-09-14 14:17:39', '2025-09-21 14:17:39', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33296.00', 'Test order: Manual/Direct Bank Transfer + Self Pickup', 'Test User 1757834259', 'Test User 1757834259', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:39', '2025-09-14 07:17:39', NULL, 'manual', NULL, NULL, NULL),
(226, 'INV-14-09-2025-14-17-40', 'ecommerce', 'created', '2025-09-14 14:17:40', '2025-09-21 14:17:40', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33271.00', 'Test order: Manual/Direct Bank Transfer + Courier Delivery', 'Test User 1757834259', 'Test User 1757834259', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:40', '2025-09-14 07:17:40', NULL, 'manual', NULL, NULL, NULL),
(227, 'INV-14-09-2025-14-17-41', 'ecommerce', 'created', '2025-09-14 14:17:41', '2025-09-21 14:17:41', 'unpaid', '23f199e5-830c-4c02-9b93-63f2d339d7cd', 'https://app.midtrans.com/snap/v4/redirection/23f199e5-830c-4c02-9b93-63f2d339d7cd', '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33048.00', 'Test order: Automatic/Credit Card/E-wallet + Self Pickup', 'Test User 1757834261', 'Test User 1757834261', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:41', '2025-09-14 07:17:41', NULL, 'automatic', NULL, NULL, NULL),
(228, 'INV-14-09-2025-14-17-42', 'ecommerce', 'created', '2025-09-14 14:17:42', '2025-09-21 14:17:42', 'unpaid', '6e4e5b71-f073-4e22-a495-962ee848a4c2', 'https://app.midtrans.com/snap/v4/redirection/6e4e5b71-f073-4e22-a495-962ee848a4c2', '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33174.00', 'Test order: Automatic/Credit Card/E-wallet + Courier Delivery', 'Test User 1757834261', 'Test User 1757834261', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:42', '2025-09-14 07:17:42', NULL, 'automatic', NULL, NULL, NULL),
(229, 'INV-14-09-2025-14-17-43', 'ecommerce', 'created', '2025-09-14 14:17:43', '2025-09-21 14:17:43', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '32823.00', 'Test order: Cash on Delivery + Self Pickup', 'Test User 1757834263', 'Test User 1757834263', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:43', '2025-09-14 07:17:43', NULL, 'cod', NULL, NULL, NULL),
(230, 'INV-14-09-2025-14-17-44', 'ecommerce', 'created', '2025-09-14 14:17:44', '2025-09-21 14:17:44', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33023.00', 'Test order: Cash on Delivery + Courier Delivery', 'Test User 1757834263', 'Test User 1757834263', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:44', '2025-09-14 07:17:44', NULL, 'cod', NULL, NULL, NULL),
(231, 'INV-14-09-2025-14-17-45', 'ecommerce', 'created', '2025-09-14 14:17:44', '2025-09-21 14:17:44', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33062.00', 'Test order: Pay at Store + Self Pickup', 'Test User 1757834264', 'Test User 1757834264', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:45', '2025-09-14 07:17:45', NULL, 'toko', NULL, NULL, NULL),
(232, 'INV-14-09-2025-14-17-46', 'ecommerce', 'created', '2025-09-14 14:17:46', '2025-09-21 14:17:46', 'unpaid', NULL, NULL, '32500.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '33319.00', 'Test order: Pay at Store + Courier Delivery', 'Test User 1757834266', 'Test User 1757834266', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'COURIER', NULL, 'Standard Delivery', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:17:46', '2025-09-14 07:17:46', NULL, 'toko', NULL, NULL, NULL),
(233, 'INV-14-09-2025-14-19-08', 'ecommerce', 'confirmed', '2025-09-14 14:19:08', '2025-09-21 14:19:08', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15123.00', 'Test payment confirmation flow', 'Test Payment Flow User', 'Test Payment Flow User', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 188, NULL, 0, '2025-09-14 07:19:08', '2025-09-14 07:19:08', NULL, 'manual', NULL, NULL, NULL),
(234, 'INV-14-09-2025-14-19-46', 'ecommerce', 'created', '2025-09-14 14:19:46', '2025-09-21 14:19:46', 'waiting', NULL, NULL, '1401350.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '1401350.00', NULL, 'Raihan Rizki Alfareza', 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, 'SELF', NULL, 'Self Pickup', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 190, NULL, 0, '2025-09-14 07:19:46', '2025-09-14 07:19:58', NULL, 'manual', 'assets/bukti_pembayaran/8mr4kXouCzhJsya5FsBSum9g6Ep0bBan4xy9Atry.png', NULL, NULL),
(235, 'INV-14-09-2025-17-49-26', 'ecommerce', 'completed', '2025-09-14 17:49:26', '2025-09-21 17:49:26', 'paid', NULL, NULL, '1005000.00', '0.00', '0.00', '0.00', '0.00', '10000.00', '7000.00', '1015000.00', NULL, 'Raihan Rizki Alfareza', 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, 'sicepat', 'jne', 'SICEPAT - REG', 'JNE - REG', 1, 'disini gaada bang', '2025-09-14 10:51:55', 190, 1, '2025-09-14 18:04:48', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-14 10:49:26', '2025-09-14 11:04:48', NULL, 'manual', 'assets/bukti_pembayaran/Omhf0D21RwK1cIuMBWcWWpjZKcWVWNKOtm7rszdd.png', NULL, NULL),
(236, 'INV-14-09-2025-18-45-09', 'ecommerce', 'completed', '2025-09-14 18:45:09', '2025-09-21 18:45:09', 'paid', NULL, NULL, '45000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '45000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-14 18:55:13', NULL, NULL, NULL, 190, NULL, 0, '2025-09-14 11:45:09', '2025-09-14 11:55:13', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(237, 'INV-14-09-2025-18-56-03', 'ecommerce', 'confirmed', '2025-09-14 18:56:03', '2025-09-21 18:56:03', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 190, NULL, 0, '2025-09-14 11:56:03', '2025-09-14 11:56:08', NULL, 'toko', NULL, NULL, NULL),
(238, 'INV-14-09-2025-19-31-17', 'ecommerce', 'completed', '2025-09-14 19:31:17', '2025-09-21 19:31:17', 'paid', NULL, NULL, '300000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '300000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-14 19:31:32', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-14 12:31:17', '2025-09-14 12:31:32', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(239, 'INV-14-09-2025-19-44-59', 'ecommerce', 'completed', '2025-09-14 19:44:59', '2025-09-21 19:44:59', 'paid', NULL, NULL, '75000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '75000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-14 19:45:04', NULL, NULL, NULL, 190, NULL, 0, '2025-09-14 12:44:59', '2025-09-14 12:45:04', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(240, 'TEST-1757854489', 'ecommerce', 'created', '2025-09-14 19:54:49', '2025-09-21 19:54:49', 'paid', 'test-token-1757854489', NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Test', 'Customer', 'Test Address', NULL, '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-14 12:54:49', '2025-09-14 12:54:49', NULL, 'manual', NULL, NULL, NULL),
(241, 'TEST-1757854503', 'ecommerce', 'created', '2025-09-14 19:55:03', '2025-09-21 19:55:03', 'paid', 'test-token-1757854503', NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Test', 'Customer', 'Test Address', NULL, '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-14 12:55:03', '2025-09-14 12:55:03', NULL, 'manual', NULL, NULL, NULL),
(242, 'TEST-1757854514', 'ecommerce', 'created', '2025-09-14 19:55:14', '2025-09-21 19:55:14', 'paid', 'test-token-1757854514', NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Test', 'Customer', 'Test Address', NULL, '08123456789', 'test@example.com', '1', '1', 12345, 'SELF', NULL, 'SELF', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-14 12:55:14', '2025-09-14 12:55:14', '2025-09-14 12:55:14', 'manual', NULL, NULL, NULL),
(243, 'INV-14-09-2025-19-56-40', 'ecommerce', 'completed', '2025-09-14 19:56:40', '2025-09-21 19:56:40', 'paid', NULL, NULL, '75000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '75000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-14 19:56:45', NULL, NULL, NULL, 190, NULL, 0, '2025-09-14 12:56:40', '2025-09-14 12:56:45', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(244, 'INV-14-09-2025-20-36-43', 'ecommerce', 'completed', '2025-09-14 20:36:43', '2025-09-21 20:36:43', 'paid', NULL, NULL, '75000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '75000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-14 20:36:49', NULL, NULL, NULL, 190, NULL, 0, '2025-09-14 13:36:43', '2025-09-14 13:36:49', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(245, 'INV-14-09-2025-21-59-30', 'ecommerce', 'completed', '2025-09-14 21:59:30', '2025-09-21 21:59:30', 'paid', NULL, NULL, '20.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '20.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-14 21:59:55', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-14 14:59:30', '2025-09-14 14:59:55', NULL, 'toko', NULL, NULL, '\nOrder completed for offline store purchase'),
(246, 'INV-15-09-2025-12-46-14', 'ecommerce', 'completed', '2025-09-15 12:46:14', '2025-09-22 12:46:14', 'paid', NULL, NULL, '60000.00', '0.00', '0.00', '0.00', '0.00', '10000.00', '7000.00', '70000.00', NULL, 'Raihan Rizki Alfareza', 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, 'tiki', 'jne', 'TIKI - REG', 'JNE - REG', 1, 'GAONOK WAK', '2025-09-15 06:48:18', 190, 190, '2025-09-15 13:48:43', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-15 05:46:14', '2025-09-15 06:48:43', NULL, 'manual', 'assets/bukti_pembayaran/Q2V8WPUHEiv2M1nSgpMEYS5yuqekQOE4DCHNZREH.png', NULL, NULL),
(247, 'INV-15-09-2025-22-35-22', 'ecommerce', 'completed', '2025-09-15 22:35:22', '2025-09-22 22:35:22', 'paid', 'd251aaff-5a13-429e-beab-747ef4c28ea3', 'https://app.midtrans.com/snap/v4/redirection/d251aaff-5a13-429e-beab-747ef4c28ea3', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '2.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-15 22:36:17', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-15 15:35:22', '2025-09-15 15:36:17', NULL, 'qris', NULL, NULL, '\nPayment completed successfully via qris\nOrder completed for offline store purchase'),
(248, 'INV-16-09-2025-00-53-52', 'ecommerce', 'completed', '2025-09-16 00:53:52', '2025-09-23 00:53:52', 'paid', NULL, NULL, '15000.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15000.00', NULL, 'Admin', 'Toko', 'Cukir, Jombang', '', '9121240210', 'admin@gmail.com', '1', '1', 102112, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 190, '2025-09-16 00:54:06', NULL, NULL, NULL, 190, 'Reza', 1, '2025-09-15 17:53:52', '2025-09-15 17:54:06', NULL, 'transfer', NULL, NULL, '\nOrder completed for offline store purchase');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `base_price` decimal(16,2) NOT NULL DEFAULT '0.00',
  `base_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tax_percent` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(16,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `qty`, `base_price`, `base_total`, `tax_amount`, `tax_percent`, `discount_amount`, `discount_percent`, `sub_total`, `sku`, `type`, `name`, `weight`, `attributes`, `order_id`, `product_id`, `variant_id`, `created_at`, `updated_at`, `attachments`) VALUES
(5, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 6, 101, NULL, '2025-05-11 10:26:42', '2025-05-11 10:26:42', NULL),
(7, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 8, 101, NULL, '2025-05-11 10:37:58', '2025-05-11 10:37:58', NULL),
(8, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 9, 101, NULL, '2025-05-11 10:41:20', '2025-05-11 10:41:20', NULL),
(9, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 41, 101, NULL, '2025-05-11 13:23:33', '2025-05-11 13:23:33', NULL),
(10, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 42, 101, NULL, '2025-05-11 13:24:51', '2025-05-11 13:24:51', NULL),
(11, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 45, 101, NULL, '2025-05-11 13:28:41', '2025-05-11 13:28:41', NULL),
(12, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 45, 101, NULL, '2025-05-11 13:28:41', '2025-05-11 13:28:41', NULL),
(13, 2, '100.00', '200.00', '0.00', '0.00', '0.00', '0.00', '200.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 46, 101, NULL, '2025-05-11 13:32:30', '2025-05-11 13:32:30', NULL),
(14, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 46, 101, NULL, '2025-05-11 13:32:30', '2025-05-11 13:32:30', NULL),
(15, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 47, 101, NULL, '2025-05-11 13:34:58', '2025-05-11 13:34:58', NULL),
(16, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 47, 101, NULL, '2025-05-11 13:34:58', '2025-05-11 13:34:58', NULL),
(17, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 48, 101, NULL, '2025-05-11 13:39:17', '2025-05-11 13:39:17', NULL),
(18, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 48, 101, NULL, '2025-05-11 13:39:17', '2025-05-11 13:39:17', NULL),
(19, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 49, 101, NULL, '2025-05-11 13:40:38', '2025-05-11 13:40:38', NULL),
(20, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 49, 101, NULL, '2025-05-11 13:40:38', '2025-05-11 13:40:38', NULL),
(21, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 50, 101, NULL, '2025-05-11 13:44:17', '2025-05-11 13:44:17', NULL),
(22, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 51, 101, NULL, '2025-05-11 13:47:09', '2025-05-11 13:47:09', NULL),
(23, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 52, 101, NULL, '2025-05-25 15:12:20', '2025-05-25 15:12:20', NULL),
(24, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 53, 101, NULL, '2025-05-25 16:56:27', '2025-05-25 16:56:27', NULL),
(25, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 54, 101, NULL, '2025-05-25 17:11:04', '2025-05-25 17:11:04', NULL),
(26, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 55, 101, NULL, '2025-06-06 20:42:32', '2025-06-06 20:42:32', NULL),
(27, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 55, 101, NULL, '2025-06-06 20:42:32', '2025-06-06 20:42:32', NULL),
(28, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 56, 101, NULL, '2025-06-27 15:27:08', '2025-06-27 15:27:08', NULL),
(29, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '25.00', 'null', 57, 101, NULL, '2025-06-27 16:16:41', '2025-06-27 16:16:41', NULL),
(30, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 58, 101, NULL, '2025-06-30 10:45:41', '2025-06-30 10:45:41', NULL),
(31, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 59, 101, NULL, '2025-06-30 12:00:35', '2025-06-30 12:00:35', NULL),
(32, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 60, 101, NULL, '2025-06-30 12:25:02', '2025-06-30 12:25:02', NULL),
(33, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 61, 101, NULL, '2025-06-30 12:28:19', '2025-06-30 12:28:19', NULL),
(34, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.025', '{\"options\":[]}', 62, 101, NULL, '2025-06-30 12:35:46', '2025-06-30 12:35:46', NULL),
(35, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '2.5', '{\"options\":[]}', 63, 101, NULL, '2025-06-30 12:44:32', '2025-06-30 12:44:32', NULL),
(36, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 64, 113, NULL, '2025-06-30 14:14:58', '2025-06-30 14:14:58', NULL),
(37, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 65, 113, NULL, '2025-06-30 14:19:39', '2025-06-30 14:19:39', NULL),
(38, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '2.5', '{\"options\":[]}', 66, 101, NULL, '2025-07-02 21:25:29', '2025-07-02 21:25:29', NULL),
(39, 2, '100.00', '200.00', '0.00', '0.00', '0.00', '0.00', '200.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '2.5', '{\"options\":[]}', 67, 101, NULL, '2025-07-07 22:58:34', '2025-07-07 22:58:34', NULL),
(40, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', '1313186', 'simple', 'DUMMY 6', '0.01', '{\"options\":[]}', 68, 114, NULL, '2025-07-08 14:12:25', '2025-07-08 14:12:25', NULL),
(41, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 68, 113, NULL, '2025-07-08 14:12:25', '2025-07-08 14:12:25', NULL),
(42, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', '1313186', 'simple', 'DUMMY 6', '0.01', '{\"options\":[]}', 69, 114, NULL, '2025-07-08 14:23:01', '2025-07-08 14:23:01', NULL),
(43, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 69, 113, NULL, '2025-07-08 14:23:01', '2025-07-08 14:23:01', NULL),
(46, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 71, 113, NULL, '2025-07-08 14:37:13', '2025-07-08 14:37:13', NULL),
(47, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 72, 113, NULL, '2025-07-08 15:00:33', '2025-07-08 15:00:33', NULL),
(48, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', '1313186', 'simple', 'DUMMY 6', '0.01', '{\"options\":[]}', 72, 114, NULL, '2025-07-08 15:00:33', '2025-07-08 15:00:33', NULL),
(49, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 73, 113, NULL, '2025-07-08 15:05:21', '2025-07-08 15:05:21', NULL),
(50, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', '1313186', 'simple', 'DUMMY 6', '0.01', '{\"options\":[]}', 73, 114, NULL, '2025-07-08 15:05:21', '2025-07-08 15:05:21', NULL),
(51, 1, '15.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', '1', '{\"options\":[]}', 74, 113, NULL, '2025-07-08 15:12:29', '2025-07-08 15:12:29', NULL),
(52, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', '1313186', 'simple', 'DUMMY 6', '0.01', '{\"options\":[]}', 74, 114, NULL, '2025-07-08 15:12:29', '2025-07-08 15:12:29', NULL),
(53, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'barulagi', 'simple', 'Ujicoba', '10.00', 'null', 75, 120, NULL, '2025-07-26 12:24:58', '2025-07-26 12:24:58', NULL),
(54, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '2500.00', '[]', 81, 101, NULL, '2025-08-12 06:50:36', '2025-08-12 06:50:36', NULL),
(55, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '2500.00', '[]', 82, 101, NULL, '2025-08-12 06:55:38', '2025-08-12 06:55:38', NULL),
(56, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '2500.00', '[]', 83, 101, NULL, '2025-08-12 06:59:01', '2025-08-12 06:59:01', NULL),
(57, 1, '30.00', '30.00', '0.00', '0.00', '0.00', '0.00', '30.00', 'DUMMY 2', 'simple', 'DUMMY 2', '0', '[]', 84, 102, NULL, '2025-08-12 07:15:14', '2025-08-12 07:15:14', NULL),
(58, 1, '30.00', '30.00', '0.00', '0.00', '0.00', '0.00', '30.00', 'DUMMY 2', 'simple', 'DUMMY 2', '0', '[]', 85, 102, NULL, '2025-08-12 07:18:50', '2025-08-12 07:18:50', NULL),
(59, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '[]', 85, 111, NULL, '2025-08-12 07:18:50', '2025-08-12 07:18:50', NULL),
(60, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '[]', 86, 111, NULL, '2025-08-12 08:16:54', '2025-08-12 08:16:54', NULL),
(61, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '100.00', '[]', 87, 117, NULL, '2025-08-12 08:26:10', '2025-08-12 08:26:10', NULL),
(62, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '100.00', '[]', 88, 117, NULL, '2025-08-12 10:08:11', '2025-08-12 10:08:11', NULL),
(63, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '100.00', '[]', 89, 117, NULL, '2025-08-12 10:24:45', '2025-08-12 10:24:45', NULL),
(64, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '100.00', '[]', 90, 117, NULL, '2025-08-12 13:05:46', '2025-08-12 13:05:46', NULL),
(65, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.1', '{\"options\":[]}', 91, 101, NULL, '2025-08-13 06:06:44', '2025-08-13 06:06:44', NULL),
(68, 1, '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '100.00', 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', '0.1', '{\"options\":[]}', 94, 101, NULL, '2025-08-13 06:28:19', '2025-08-13 06:28:19', NULL),
(69, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '{\"options\":[]}', 95, 111, NULL, '2025-08-13 06:41:41', '2025-08-13 06:41:41', NULL),
(70, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '{\"options\":[]}', 96, 111, NULL, '2025-08-13 06:46:03', '2025-08-13 06:46:03', NULL),
(71, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '{\"options\":[]}', 97, 111, NULL, '2025-08-13 06:50:02', '2025-08-13 06:50:02', NULL),
(72, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '{\"options\":[]}', 98, 111, NULL, '2025-08-13 06:59:39', '2025-08-13 06:59:39', NULL),
(73, 1, '20.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', '0', '{\"options\":[]}', 99, 111, NULL, '2025-08-13 07:07:49', '2025-08-13 07:07:49', NULL),
(74, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-123', 'simple', 'dummy barcode', '0.01', '{\"options\":[]}', 100, 116, NULL, '2025-08-13 13:14:46', '2025-08-13 13:14:46', NULL),
(75, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '0.1', '{\"options\":[]}', 101, 117, NULL, '2025-08-13 13:33:17', '2025-08-13 13:33:17', NULL),
(76, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '0.1', '{\"options\":[]}', 102, 117, NULL, '2025-08-13 13:45:44', '2025-08-13 13:45:44', NULL),
(77, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-1233', 'simple', 'Dummy Product2', '0.1', '{\"options\":[]}', 103, 117, NULL, '2025-08-13 14:27:43', '2025-08-13 14:27:43', NULL),
(78, 1, '10.00', '10.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'sku-123', 'simple', 'dummy barcode', '10.00', '[]', 104, 116, NULL, '2025-08-13 14:34:33', '2025-08-13 14:34:33', NULL),
(79, 1, '6.00', '6.00', '0.00', '0.00', '0.00', '0.00', '6.00', 'sku-1233', 'configurable', 'Dummy Product2', '1.00', '[{\"attribute\":\"HVS\",\"variant\":\"HVS APP\",\"option\":\"HVS 70Gr\"}]', 105, 117, NULL, '2025-08-14 17:38:52', '2025-08-14 17:38:52', NULL),
(81, 2, '6.00', '12.00', '0.00', '0.00', '0.00', '0.00', '12.00', 'sku-1233-1-null', 'configurable', 'Dummy Product2 - HVS 70Gr', '0', '{\"options\":[]}', 112, 123, NULL, '2025-08-14 18:47:41', '2025-08-14 18:47:41', NULL),
(82, 2, '6.00', '12.00', '0.00', '0.00', '0.00', '0.00', '12.00', 'sku-1233-1-null', 'configurable', 'Dummy Product2 - HVS 70Gr', '0', '{\"options\":[]}', 113, 123, NULL, '2025-08-14 18:48:40', '2025-08-14 18:48:40', NULL),
(83, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 114, 16, NULL, '2025-09-06 11:04:14', '2025-09-06 11:04:14', NULL),
(84, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 116, 16, NULL, '2025-09-06 11:05:59', '2025-09-06 11:05:59', NULL),
(85, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 117, 16, NULL, '2025-09-08 03:22:46', '2025-09-08 03:22:46', NULL),
(86, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 118, 133, 16, '2025-09-08 04:19:42', '2025-09-08 04:19:42', NULL),
(87, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 119, 133, 16, '2025-09-08 04:21:47', '2025-09-08 04:21:47', NULL),
(95, 2, '15000.00', '30000.00', '0.00', '0.00', '0.00', '0.00', '30000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '1.00', '[]', 126, 3, NULL, '2025-09-08 04:30:41', '2025-09-08 04:30:41', NULL),
(105, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 138, 133, 16, '2025-09-08 04:34:45', '2025-09-08 04:34:45', NULL),
(106, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 139, 133, 16, '2025-09-08 04:47:31', '2025-09-08 04:47:31', NULL),
(107, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 140, 16, NULL, '2025-09-09 01:47:47', '2025-09-09 01:47:47', NULL),
(108, 1, '4.00', '4.00', '0.00', '0.00', '0.00', '0.00', '4.00', 'VAR-14', 'configurable', 'astaghfirullah', '0', '{\"product_id\":133,\"variant_id\":14,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"pink\":\"blue\"}}', 140, 14, NULL, '2025-09-09 01:47:47', '2025-09-09 01:47:47', NULL),
(109, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 141, 16, NULL, '2025-09-09 01:54:37', '2025-09-09 01:54:37', NULL),
(110, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 142, 16, NULL, '2025-09-09 02:55:07', '2025-09-09 02:55:07', NULL),
(111, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 143, 133, 16, '2025-09-09 03:53:55', '2025-09-09 03:53:55', NULL),
(112, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 144, 133, 16, '2025-09-09 04:10:37', '2025-09-09 04:10:37', NULL),
(113, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 145, 133, 16, '2025-09-09 04:21:21', '2025-09-09 04:21:21', NULL),
(114, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 146, 133, 16, '2025-09-09 06:59:35', '2025-09-09 06:59:35', NULL),
(115, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '1.00', '[]', 147, 3, NULL, '2025-09-10 02:09:27', '2025-09-10 02:09:27', NULL),
(116, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '1.00', '[]', 152, 3, NULL, '2025-09-10 03:13:36', '2025-09-10 03:13:36', NULL),
(117, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', '8316317', 'configurable', 'ya-allah', '1.00', '{\"blue\":\"panda\"}', 154, 133, 16, '2025-09-10 03:18:13', '2025-09-10 03:18:13', NULL),
(118, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '1.00', '[]', 155, 3, NULL, '2025-09-10 03:20:09', '2025-09-10 03:20:09', NULL),
(119, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 156, 9, NULL, '2025-09-10 03:20:34', '2025-09-10 03:20:34', NULL),
(124, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 171, 3, NULL, '2025-09-10 04:01:33', '2025-09-10 04:01:33', NULL),
(125, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 172, 3, NULL, '2025-09-10 04:04:23', '2025-09-10 04:04:23', NULL),
(126, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 173, 3, NULL, '2025-09-10 04:04:34', '2025-09-10 04:04:34', NULL),
(127, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 174, 3, NULL, '2025-09-10 04:04:35', '2025-09-10 04:04:35', NULL),
(128, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 175, 3, NULL, '2025-09-10 04:04:36', '2025-09-10 04:04:36', NULL),
(129, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 176, 3, NULL, '2025-09-10 04:04:37', '2025-09-10 04:04:37', NULL),
(130, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 177, 3, NULL, '2025-09-10 04:08:48', '2025-09-10 04:08:48', NULL),
(131, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 178, 3, NULL, '2025-09-10 04:08:49', '2025-09-10 04:08:49', NULL),
(132, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 179, 3, NULL, '2025-09-10 04:08:50', '2025-09-10 04:08:50', NULL),
(133, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 180, 3, NULL, '2025-09-10 04:08:51', '2025-09-10 04:08:51', NULL),
(134, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 181, 3, NULL, '2025-09-10 04:08:52', '2025-09-10 04:08:52', NULL),
(135, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 182, 3, NULL, '2025-09-10 04:08:53', '2025-09-10 04:08:53', NULL),
(136, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 183, 3, NULL, '2025-09-10 04:08:54', '2025-09-10 04:08:54', NULL),
(137, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 184, 3, NULL, '2025-09-10 04:08:55', '2025-09-10 04:08:55', NULL),
(138, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 187, 3, NULL, '2025-09-10 04:13:06', '2025-09-10 04:13:06', NULL),
(139, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 188, 3, NULL, '2025-09-10 04:18:11', '2025-09-10 04:18:11', NULL),
(140, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 189, 3, NULL, '2025-09-10 04:18:12', '2025-09-10 04:18:12', NULL),
(141, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 190, 3, NULL, '2025-09-10 04:19:09', '2025-09-10 04:19:09', NULL),
(142, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 191, 3, NULL, '2025-09-10 04:19:10', '2025-09-10 04:19:10', NULL),
(143, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 192, 3, NULL, '2025-09-10 04:20:25', '2025-09-10 04:20:25', NULL),
(144, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 193, 3, NULL, '2025-09-10 04:20:26', '2025-09-10 04:20:26', NULL),
(145, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 194, 3, NULL, '2025-09-10 04:21:42', '2025-09-10 04:21:42', NULL),
(146, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 195, 3, NULL, '2025-09-10 04:21:43', '2025-09-10 04:21:43', NULL),
(147, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 196, 3, NULL, '2025-09-10 04:21:44', '2025-09-10 04:21:44', NULL),
(148, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '[]', 197, 3, NULL, '2025-09-10 04:21:45', '2025-09-10 04:21:45', NULL),
(149, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 201, 16, NULL, '2025-09-10 04:38:45', '2025-09-10 04:38:45', NULL),
(150, 1, '3.00', '3.00', '0.00', '0.00', '0.00', '0.00', '3.00', 'VAR-16', 'configurable', 'ya-allah', '0', '{\"product_id\":133,\"variant_id\":16,\"type\":\"configurable\",\"slug\":\"kertas-baru-lagi\",\"image\":\"\",\"attributes\":{\"blue\":\"panda\"}}', 202, 16, NULL, '2025-09-10 04:41:42', '2025-09-10 04:41:42', NULL),
(152, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 204, 3, NULL, '2025-09-10 04:53:15', '2025-09-10 04:53:15', NULL),
(153, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 205, 3, NULL, '2025-09-10 04:53:16', '2025-09-10 04:53:16', NULL),
(154, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 206, 3, NULL, '2025-09-10 04:53:17', '2025-09-10 04:53:17', NULL),
(155, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 207, 3, NULL, '2025-09-10 05:00:37', '2025-09-10 05:00:37', NULL),
(156, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 208, 3, NULL, '2025-09-10 05:00:38', '2025-09-10 05:00:38', NULL),
(157, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 209, 3, NULL, '2025-09-10 05:00:39', '2025-09-10 05:00:39', NULL),
(158, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\"}', 210, 4, NULL, '2025-09-10 05:01:19', '2025-09-10 05:01:19', NULL),
(159, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\"}', 211, 5, NULL, '2025-09-10 05:01:20', '2025-09-10 05:01:20', NULL),
(160, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 212, 3, NULL, '2025-09-10 05:01:21', '2025-09-10 05:01:21', NULL),
(161, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 213, 3, NULL, '2025-09-10 05:09:39', '2025-09-10 05:09:39', NULL),
(162, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 214, 3, NULL, '2025-09-13 04:47:42', '2025-09-13 04:47:42', NULL),
(163, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\"}', 215, 3, NULL, '2025-09-13 04:49:06', '2025-09-13 04:49:06', NULL),
(170, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 223, 3, NULL, '2025-09-14 07:15:58', '2025-09-14 07:15:58', NULL),
(171, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 224, 3, NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59', NULL),
(172, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 224, 4, NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59', NULL),
(173, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 224, 5, NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59', NULL),
(174, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 225, 3, NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39', NULL),
(175, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 225, 4, NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39', NULL),
(176, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 225, 5, NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39', NULL),
(177, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 226, 3, NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40', NULL),
(178, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 226, 4, NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40', NULL),
(179, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 226, 5, NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40', NULL),
(180, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 227, 3, NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41', NULL),
(181, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 227, 4, NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41', NULL),
(182, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 227, 5, NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41', NULL),
(183, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 228, 3, NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42', NULL),
(184, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 228, 4, NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42', NULL),
(185, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 228, 5, NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42', NULL),
(186, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 229, 3, NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43', NULL),
(187, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 229, 4, NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43', NULL),
(188, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 229, 5, NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43', NULL),
(189, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 230, 3, NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44', NULL),
(190, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 230, 4, NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44', NULL),
(191, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 230, 5, NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44', NULL),
(192, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 231, 3, NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45', NULL),
(193, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 231, 4, NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45', NULL),
(194, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 231, 5, NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45', NULL),
(195, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 232, 3, NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46', NULL),
(196, 1, '2500.00', '2500.00', '0.00', '0.00', '0.00', '0.00', '2500.00', '04fb7d48-e049-4bbe-9f44-707265a75399', 'simple', 'PETA A3', '0.001', '{\"product_id\":4,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"peta-a3\",\"image\":\"product\\/images\\/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png\",\"sku\":\"04fb7d48-e049-4bbe-9f44-707265a75399\"}', 232, 4, NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46', NULL),
(197, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '2c5f9f44-f3be-4e27-8516-a18baca13295', 'simple', 'KUESIONER & PRELIST', '0.001', '{\"product_id\":5,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"kuesioner-prelist\",\"image\":\"product\\/images\\/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png\",\"sku\":\"2c5f9f44-f3be-4e27-8516-a18baca13295\"}', 232, 5, NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46', NULL),
(198, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 233, 3, NULL, '2025-09-14 07:19:08', '2025-09-14 07:19:08', NULL),
(199, 10, '140135.00', '1401350.00', '0.00', '0.00', '0.00', '0.00', '1401350.00', 'VAR-36', 'configurable', 'Baju Pria Lengan Panjang 2 - M Putih', '0', '{\"product_id\":134,\"variant_id\":36,\"type\":\"configurable\",\"slug\":\"baju-pria-lengan-panjang-2\",\"image\":\"product\\/images\\/default-product.jpg\",\"attributes\":{\"color\":\"Putih\",\"size\":\"M\"}}', 234, 36, NULL, '2025-09-14 07:19:46', '2025-09-14 07:19:46', NULL),
(200, 67, '15000.00', '1005000.00', '0.00', '0.00', '0.00', '0.00', '1005000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '0.001', '{\"product_id\":9,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"amplop\",\"image\":\"product\\/images\\/VjoITdPH0wrUKyZ871TTm38E7j6XhJO26Qq56Hsi.png\",\"sku\":\"c831ce28-669e-4fe7-a16c-5ac451c2850c\"}', 235, 9, NULL, '2025-09-14 10:49:26', '2025-09-14 10:49:26', NULL),
(201, 3, '15000.00', '45000.00', '0.00', '0.00', '0.00', '0.00', '45000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 236, 9, NULL, '2025-09-14 11:45:09', '2025-09-14 11:45:09', NULL),
(202, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 237, 9, NULL, '2025-09-14 11:56:03', '2025-09-14 11:56:03', NULL),
(203, 20, '15000.00', '300000.00', '0.00', '0.00', '0.00', '0.00', '300000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 238, 9, NULL, '2025-09-14 12:31:17', '2025-09-14 12:31:17', NULL),
(204, 5, '15000.00', '75000.00', '0.00', '0.00', '0.00', '0.00', '75000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 239, 9, NULL, '2025-09-14 12:44:59', '2025-09-14 12:44:59', NULL),
(206, 5, '15000.00', '75000.00', '0.00', '0.00', '0.00', '0.00', '75000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 243, 9, NULL, '2025-09-14 12:56:40', '2025-09-14 12:56:40', NULL),
(207, 5, '15000.00', '75000.00', '0.00', '0.00', '0.00', '0.00', '75000.00', 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', '1.00', '[]', 244, 9, NULL, '2025-09-14 13:36:43', '2025-09-14 13:36:43', NULL),
(208, 10, '2.00', '20.00', '0.00', '0.00', '0.00', '0.00', '20.00', 'SFWSFRW1313', 'simple', 'RAKET PADEL', '1.00', '[]', 245, 138, NULL, '2025-09-14 14:59:30', '2025-09-14 14:59:30', NULL),
(209, 4, '15000.00', '60000.00', '0.00', '0.00', '0.00', '0.00', '60000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '0.001', '{\"product_id\":3,\"variant_id\":null,\"type\":\"simple\",\"slug\":\"print-on-demand-cetak-kertas-hvs\",\"image\":\"product\\/images\\/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png\",\"sku\":\"0149794284247257524\"}', 246, 3, NULL, '2025-09-15 05:46:15', '2025-09-15 05:46:15', NULL),
(210, 1, '2.00', '2.00', '0.00', '0.00', '0.00', '0.00', '2.00', 'SFWSFRW1313', 'simple', 'RAKET PADEL', '1.00', '[]', 247, 138, NULL, '2025-09-15 15:35:22', '2025-09-15 15:35:22', NULL),
(211, 1, '15000.00', '15000.00', '0.00', '0.00', '0.00', '0.00', '15000.00', '0149794284247257524', 'simple', 'PRINT ON DEMAND | CETAK KERTAS HVS', '1.00', '[]', 248, 3, NULL, '2025-09-15 17:53:52', '2025-09-15 17:53:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payloads` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biller_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `number`, `amount`, `method`, `status`, `token`, `payloads`, `payment_type`, `va_number`, `vendor_name`, `biller_code`, `bill_key`, `order_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '58a82070-ab33-40b6-86a1-c246043eeab5', '720.00', 'qris', 'pending', 'cc889c75-33f0-48c6-8a5c-89dab7b5049a', '{}', 'qris', NULL, NULL, NULL, NULL, 6, '2025-05-11 10:27:13', '2025-05-11 10:27:13', NULL),
(9, '3b9753fb-a8cc-4dd8-bb56-e07e70ca4334', '101.00', 'qris', 'pending', '58a4b120-3e2c-4d81-a4e3-f6ed40d06066', '{}', 'qris', NULL, NULL, NULL, NULL, 8, '2025-05-11 10:38:11', '2025-05-11 10:38:11', NULL),
(10, '3b9753fb-a8cc-4dd8-bb56-e07e70ca4334', '101.00', 'qris', 'settlement', '58a4b120-3e2c-4d81-a4e3-f6ed40d06066', '{}', 'qris', NULL, NULL, NULL, NULL, 8, '2025-05-11 10:38:19', '2025-05-11 10:38:19', NULL),
(11, '60ad02d4-7bd2-4cdf-ad4c-e388850a73bb', '226.00', 'qris', 'pending', 'e9f7043a-d2dc-4b5f-a255-cd32c45abd45', '{}', 'qris', NULL, NULL, NULL, NULL, 9, '2025-05-11 10:41:37', '2025-05-11 10:41:37', NULL),
(12, '60ad02d4-7bd2-4cdf-ad4c-e388850a73bb', '226.00', 'qris', 'settlement', 'e9f7043a-d2dc-4b5f-a255-cd32c45abd45', '{}', 'qris', NULL, NULL, NULL, NULL, 9, '2025-05-11 10:42:26', '2025-05-11 10:42:26', NULL),
(13, '235d1d12-1251-4202-a191-75d06c94bd04', '764.00', 'qris', 'pending', 'cbe62237-b9e5-41fe-98d3-a5a89e7476de', '{}', 'qris', NULL, NULL, NULL, NULL, 50, '2025-05-11 13:45:34', '2025-05-11 13:45:34', NULL),
(14, '235d1d12-1251-4202-a191-75d06c94bd04', '764.00', 'qris', 'settlement', 'cbe62237-b9e5-41fe-98d3-a5a89e7476de', '{}', 'qris', NULL, NULL, NULL, NULL, 50, '2025-05-11 13:45:49', '2025-05-11 13:45:49', NULL),
(15, '13ed72e7-20b5-4a23-a1a6-de8bba92511e', '1072.00', 'qris', 'pending', 'ffb09b98-7174-4d10-b058-d5eab0b4311f', '{}', 'qris', NULL, NULL, NULL, NULL, 52, '2025-05-25 15:13:51', '2025-05-25 15:13:51', NULL),
(16, '13ed72e7-20b5-4a23-a1a6-de8bba92511e', '1072.00', 'qris', 'settlement', 'ffb09b98-7174-4d10-b058-d5eab0b4311f', '{}', 'qris', NULL, NULL, NULL, NULL, 52, '2025-05-25 15:14:09', '2025-05-25 15:14:09', NULL),
(17, 'aac27496-f97b-4b4a-a7fc-e9af48efa8c6', '727.00', 'qris', 'pending', 'a3ffd746-4338-4d0e-81df-6b8dfd645da0', '{}', 'qris', NULL, NULL, NULL, NULL, 53, '2025-05-25 16:56:37', '2025-05-25 16:56:37', NULL),
(18, 'aac27496-f97b-4b4a-a7fc-e9af48efa8c6', '727.00', 'qris', 'settlement', 'a3ffd746-4338-4d0e-81df-6b8dfd645da0', '{}', 'qris', NULL, NULL, NULL, NULL, 53, '2025-05-25 16:57:39', '2025-05-25 16:57:39', NULL),
(19, '8cb06643-5bcf-4179-974c-7f7d2d4cf1d0', '459.00', 'qris', 'pending', '9133054b-2409-45e5-ae80-5cd9a57dfb33', '{}', 'qris', NULL, NULL, NULL, NULL, 60, '2025-06-30 12:25:11', '2025-06-30 12:25:11', NULL),
(20, '8cb06643-5bcf-4179-974c-7f7d2d4cf1d0', '459.00', 'qris', 'settlement', '9133054b-2409-45e5-ae80-5cd9a57dfb33', '{}', 'qris', NULL, NULL, NULL, NULL, 60, '2025-06-30 12:25:23', '2025-06-30 12:25:23', NULL),
(21, '8b6e9e72-9d1e-4286-9cc3-0851598bcc8b', '207.00', 'gopay', 'pending', '40efac5c-bf28-400a-92fa-7138a5113ddc', '{}', 'gopay', NULL, NULL, NULL, NULL, 66, '2025-07-02 21:25:42', '2025-07-02 21:25:42', NULL),
(22, 'e0d4d126-c6a8-4c25-9495-83b0d0a19944', '93.00', 'qris', 'pending', '88603196-c612-4b3c-84bf-f9eafb56358b', '{}', 'qris', NULL, NULL, NULL, NULL, 69, '2025-07-08 14:24:59', '2025-07-08 14:24:59', NULL),
(23, 'e0d4d126-c6a8-4c25-9495-83b0d0a19944', '93.00', 'qris', 'settlement', '88603196-c612-4b3c-84bf-f9eafb56358b', '{}', 'qris', NULL, NULL, NULL, NULL, 69, '2025-07-08 14:25:13', '2025-07-08 14:25:13', NULL),
(24, 'ae7026af-d41e-4ad4-bb3a-971a8ff32caa', '330.00', 'qris', 'pending', '79e063a7-f318-437f-a702-ff69a129e1a5', '{}', 'qris', NULL, NULL, NULL, NULL, 72, '2025-07-08 15:01:38', '2025-07-08 15:01:38', NULL),
(25, 'ae7026af-d41e-4ad4-bb3a-971a8ff32caa', '330.00', 'qris', 'settlement', '79e063a7-f318-437f-a702-ff69a129e1a5', '{}', 'qris', NULL, NULL, NULL, NULL, 72, '2025-07-08 15:02:20', '2025-07-08 15:02:20', NULL),
(26, '8b6e9e72-9d1e-4286-9cc3-0851598bcc8b', '207.00', 'gopay', 'expire', '40efac5c-bf28-400a-92fa-7138a5113ddc', '{}', 'gopay', NULL, NULL, NULL, NULL, 66, '2025-07-09 21:26:32', '2025-07-09 21:26:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembelians`
--

CREATE TABLE `pembelians` (
  `id` bigint UNSIGNED NOT NULL,
  `id_supplier` bigint UNSIGNED NOT NULL,
  `total_item` int NOT NULL,
  `total_harga` int NOT NULL,
  `diskon` tinyint NOT NULL DEFAULT '0',
  `bayar` int NOT NULL DEFAULT '0',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` enum('cash','bank_transfer','credit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `waktu` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembelians`
--

INSERT INTO `pembelians` (`id`, `id_supplier`, `total_item`, `total_harga`, `diskon`, `bayar`, `status`, `payment_method`, `notes`, `waktu`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 52, 0, 52, 'pending', 'cash', NULL, '2025-07-03 00:00:00', '2025-07-03 22:51:48', '2025-07-03 22:54:37'),
(2, 1, 10, 140, 0, 140, 'pending', 'cash', NULL, '2025-07-03 00:00:00', '2025-07-03 22:57:38', '2025-07-03 22:58:05'),
(3, 1, 10, 130, 0, 130, 'pending', 'cash', NULL, '2025-07-04 00:00:00', '2025-07-04 18:24:22', '2025-07-04 18:25:06'),
(4, 1, 10, 170, 0, 170, 'pending', 'cash', NULL, '2025-07-04 00:00:00', '2025-07-04 18:29:17', '2025-07-04 18:29:51'),
(5, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, '2025-07-04 18:29:55', '2025-07-04 18:29:55', '2025-07-04 18:29:55'),
(6, 1, 20, 300, 0, 300, 'pending', 'cash', NULL, '2025-07-08 00:00:00', '2025-07-08 12:32:08', '2025-07-08 13:05:49'),
(7, 1, 20, 320, 0, 320, 'pending', 'cash', NULL, '2025-07-08 00:00:00', '2025-07-08 14:03:05', '2025-07-08 14:06:18'),
(8, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, '2025-08-14 13:54:46', '2025-08-14 13:54:46', '2025-08-14 13:54:46'),
(9, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, '2025-09-14 02:08:10', '2025-09-14 02:08:10', '2025-09-14 02:08:10'),
(10, 1, 100, 500000, 0, 500000, 'completed', 'cash', NULL, '2025-09-14 02:54:57', '2025-09-14 02:54:57', '2025-09-14 02:54:57'),
(11, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, NULL, '2025-09-14 03:51:43', '2025-09-14 03:51:43'),
(12, 1, 11, 220000, 0, 220000, 'completed', 'cash', 'awowkwkwk', '2025-09-13 17:00:00', '2025-09-14 04:01:23', '2025-09-14 05:59:38'),
(17, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, NULL, '2025-09-14 06:24:24', '2025-09-14 06:24:24'),
(18, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, NULL, '2025-09-14 06:25:12', '2025-09-14 06:25:12'),
(19, 1, 0, 0, 0, 0, 'pending', 'cash', NULL, NULL, '2025-09-14 06:26:14', '2025-09-14 06:26:14'),
(20, 1, 200, 13000000, 0, 13000000, 'completed', 'cash', 'awowkwk', '2025-09-13 17:00:00', '2025-09-14 06:34:59', '2025-09-14 06:52:23'),
(21, 1, 73, 730000, 0, 730000, 'completed', 'cash', 'tuku sek', '2025-09-13 17:00:00', '2025-09-14 12:20:12', '2025-09-14 12:29:49'),
(22, 1, 55, 550000, 0, 550000, 'completed', 'cash', NULL, '2025-09-13 17:00:00', '2025-09-14 13:57:14', '2025-09-14 14:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_details`
--

CREATE TABLE `pembelian_details` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pembelian` bigint UNSIGNED NOT NULL,
  `id_produk` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `harga_beli` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembelian_details`
--

INSERT INTO `pembelian_details` (`id`, `id_pembelian`, `id_produk`, `variant_id`, `harga_beli`, `jumlah`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 113, NULL, 13, 4, 52, '2025-07-03 22:53:08', '2025-07-03 22:54:25'),
(2, 2, 113, NULL, 14, 10, 140, '2025-07-03 22:57:50', '2025-07-03 22:57:59'),
(3, 3, 113, NULL, 13, 10, 130, '2025-07-04 18:24:48', '2025-07-04 18:25:00'),
(4, 4, 114, NULL, 17, 10, 170, '2025-07-04 18:29:31', '2025-07-04 18:29:43'),
(5, 6, 114, NULL, 17, 10, 170, '2025-07-08 12:32:17', '2025-07-08 12:32:38'),
(8, 6, 113, NULL, 13, 10, 130, '2025-07-08 13:02:20', '2025-07-08 13:02:28'),
(9, 7, 113, NULL, 14, 10, 140, '2025-07-08 14:05:24', '2025-07-08 14:05:43'),
(10, 7, 114, NULL, 18, 10, 180, '2025-07-08 14:05:27', '2025-07-08 14:05:46'),
(11, 10, 3, 63, 5000, 100, 500000, '2025-09-14 02:54:57', '2025-09-14 02:54:57'),
(21, 12, 9, NULL, 10000, 10, 100000, '2025-09-14 05:58:53', '2025-09-14 05:59:16'),
(22, 12, 134, 36, 120000, 10, 1200000, '2025-09-14 05:59:03', '2025-09-14 05:59:20'),
(24, 20, 9, NULL, 10000, 100, 1000000, '2025-09-14 06:51:55', '2025-09-14 06:51:59'),
(25, 20, 134, 36, 120000, 100, 12000000, '2025-09-14 06:52:10', '2025-09-14 06:52:13'),
(27, 21, 9, NULL, 10000, 73, 730000, '2025-09-14 12:29:31', '2025-09-14 12:29:38'),
(30, 22, 9, NULL, 10000, 55, 550000, '2025-09-14 14:14:16', '2025-09-14 14:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluarans`
--

CREATE TABLE `pengeluarans` (
  `id` bigint UNSIGNED NOT NULL,
  `nominal` int NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `print_files`
--

CREATE TABLE `print_files` (
  `id` bigint UNSIGNED NOT NULL,
  `print_session_id` bigint UNSIGNED DEFAULT NULL,
  `print_order_id` bigint UNSIGNED DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `pages_count` int NOT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `print_files`
--

INSERT INTO `print_files` (`id`, `print_session_id`, `print_order_id`, `file_path`, `file_name`, `file_type`, `file_size`, `pages_count`, `is_processed`, `created_at`, `updated_at`) VALUES
(46, NULL, 10, 'print-files/2025-09-11/hsjZ4RiaBDGKbrPi5vv7tCMn9o2Uc7QS/test_document.pdf', 'test_document.pdf', 'pdf', 164, 5, 0, '2025-09-11 07:15:43', '2025-09-11 07:15:43'),
(47, NULL, 10, 'print-files/2025-09-11/hsjZ4RiaBDGKbrPi5vv7tCMn9o2Uc7QS/presentation.pptx', 'presentation.pptx', 'pptx', 70, 10, 0, '2025-09-11 07:15:43', '2025-09-11 07:15:43'),
(48, NULL, 11, 'print-files/2025-09-11/igHLWFwuouctCLYEb49aBH4cApUWEOUE/test_document.pdf', 'test_document.pdf', 'pdf', 164, 5, 0, '2025-09-11 07:17:14', '2025-09-11 07:17:14'),
(49, NULL, 11, 'print-files/2025-09-11/igHLWFwuouctCLYEb49aBH4cApUWEOUE/presentation.pptx', 'presentation.pptx', 'pptx', 70, 10, 0, '2025-09-11 07:17:14', '2025-09-11 07:17:14'),
(50, NULL, 12, 'print-files/2025-09-11/81J9d4MYJPuWL2GR1NJsZP6Qpt6KH5WK/test_document.pdf', 'test_document.pdf', 'pdf', 164, 5, 0, '2025-09-11 07:17:29', '2025-09-11 07:17:29'),
(51, NULL, 12, 'print-files/2025-09-11/81J9d4MYJPuWL2GR1NJsZP6Qpt6KH5WK/presentation.pptx', 'presentation.pptx', 'pptx', 70, 10, 0, '2025-09-11 07:17:29', '2025-09-11 07:17:29'),
(52, NULL, 13, 'print-files/2025-09-11/UlIWpAP6QDFUleuuFIjAXnTYYd1fLxap/business_proposal.pdf', 'business_proposal.pdf', 'pdf', 278, 8, 0, '2025-09-11 07:19:14', '2025-09-11 07:19:14'),
(53, NULL, 13, 'print-files/2025-09-11/UlIWpAP6QDFUleuuFIjAXnTYYd1fLxap/financial_report.xlsx', 'financial_report.xlsx', 'xlsx', 279, 12, 0, '2025-09-11 07:19:14', '2025-09-11 07:19:14'),
(54, NULL, 14, 'print-files/2025-09-11/wv91lxl7LtfQ2CNY3Ji7Jq2Yxd1n0VP9/1757575259_ASAH ILT SOFT SKILL 2.pdf', 'ASAH ILT SOFT SKILL 2.pdf', 'pdf', 1794261, 15, 0, '2025-09-11 07:20:59', '2025-09-11 07:21:22'),
(55, NULL, 15, 'print-files/2025-09-11/nu7eqnoFlGf2OC75XLFnLOiDQfjoZqSE/final_test.pdf', 'final_test.pdf', 'pdf', 179, 7, 0, '2025-09-11 07:31:29', '2025-09-11 07:31:29'),
(56, NULL, 16, 'print-files/2025-09-11/1FpGVn13CbAVb0hIPpNMBk1mZDDnUj8b/stress_1.pdf', 'stress_1.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:30', '2025-09-11 07:31:30'),
(57, NULL, 17, 'print-files/2025-09-11/z9AbUFcTydBzl26ULrOE9krJRfjr7yAp/stress_2.pdf', 'stress_2.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:31', '2025-09-11 07:31:31'),
(58, NULL, 18, 'print-files/2025-09-11/iXrQEP94whWk6rnY7zNQcJA6d8yENJtk/stress_3.pdf', 'stress_3.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:32', '2025-09-11 07:31:32'),
(59, NULL, 19, 'print-files/2025-09-11/B52B7w9vTHRosy0Ueryvh4otienJy5ot/stress_4.pdf', 'stress_4.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:33', '2025-09-11 07:31:33'),
(60, NULL, 20, 'print-files/2025-09-11/rs3Gks67XXgbX5Yx5vGXlrohudFxxx1k/stress_5.pdf', 'stress_5.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:34', '2025-09-11 07:31:34'),
(61, NULL, 21, 'print-files/2025-09-11/GRvydRH24bKf5giYYJZyba7DVUzKvqU0/stress_6.pdf', 'stress_6.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:35', '2025-09-11 07:31:35'),
(62, NULL, 22, 'print-files/2025-09-11/7ezOTmqR8jPa7ONlHug2yZ1v681ONY7a/stress_7.pdf', 'stress_7.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:36', '2025-09-11 07:31:36'),
(63, NULL, 23, 'print-files/2025-09-11/O8WaXhNWb1FD0zSJG8M7i0nFvT4g0Kyh/stress_8.pdf', 'stress_8.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:37', '2025-09-11 07:31:37'),
(64, NULL, 24, 'print-files/2025-09-11/WS1QdQoaSd7Hh1HIkK1UfJFHtJC3szLg/stress_9.pdf', 'stress_9.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:38', '2025-09-11 07:31:38'),
(65, NULL, 25, 'print-files/2025-09-11/QxdMneppUNcbJt3UjGUwMdXpjfKVMyp7/stress_10.pdf', 'stress_10.pdf', 'pdf', 18, 1, 0, '2025-09-11 07:31:39', '2025-09-11 07:31:39'),
(66, NULL, 26, 'print-files/2025-09-11/jq9sN7jpnMuNZoOmkqKml3Cm33u6WPBO/cleanup_test.pdf', 'cleanup_test.pdf', 'pdf', 114, 3, 0, '2025-09-11 07:33:02', '2025-09-11 07:33:02'),
(67, NULL, 27, 'print-files/2025-09-11/VH9tPhpamN2tVOnRSI2XqUN1wNbkMfVs/1757576086_ASAH ILT SOFT SKILL 2.pdf', 'ASAH ILT SOFT SKILL 2.pdf', 'pdf', 1794261, 15, 0, '2025-09-11 07:34:46', '2025-09-11 07:35:08'),
(68, 69, 28, 'print-files/2025-09-11/PRCwukcqKzMYS0r82gdfgaLm9Pq24Faa/final_fix_test.pdf', 'final_fix_test.pdf', 'pdf', 88, 2, 0, '2025-09-11 07:38:07', '2025-09-11 07:38:07'),
(69, NULL, 29, 'print-files/2025-09-11/jD0N13mU8tHvS7UMEMWAWjFuGT3p4Qkt/1757576599_ASAH ILT SOFT SKILL 2.pdf', 'ASAH ILT SOFT SKILL 2.pdf', 'pdf', 1794261, 15, 0, '2025-09-11 07:43:19', '2025-09-11 07:43:36'),
(70, NULL, 30, 'print-files/2025-09-11/eGQNc83SaDEYL0sPzkkePUam0vCaFOJd/1757576939_ASAH ILT SOFT SKILL 2.pdf', 'ASAH ILT SOFT SKILL 2.pdf', 'pdf', 1794261, 15, 0, '2025-09-11 07:48:59', '2025-09-11 07:49:15'),
(71, NULL, 31, 'print-files/2025-09-11/ppA7mZEV585TBpuCuE3HfuyET0yIePbe/1757577275_Kartu Nama Anjar Setyo Nugroho.pdf', 'Kartu Nama Anjar Setyo Nugroho.pdf', 'pdf', 386772, 1, 0, '2025-09-11 07:54:35', '2025-09-11 07:54:51'),
(72, NULL, 32, 'print-files/2025-09-11/wTxU9ghZ2697v84SSBEOYQN5DVNh8JN2/1757577743_Kartu Nama Anjar Setyo Nugroho.pdf', 'Kartu Nama Anjar Setyo Nugroho.pdf', 'pdf', 386772, 1, 0, '2025-09-11 08:02:23', '2025-09-11 08:02:39'),
(74, NULL, 33, 'print-files/2025-09-11/RyCxE4M2KReK5FlbGlavNaFBSsT41P1y/1757578183_Kartu Nama Anjar Setyo Nugroho.pdf', 'Kartu Nama Anjar Setyo Nugroho.pdf', 'pdf', 386772, 1, 0, '2025-09-11 08:09:43', '2025-09-11 08:09:57'),
(82, NULL, 39, 'print-files/iD0kMwNLEUBQ0IGfigiPzbNwBELvOxbn/test_document.txt', 'test_document.txt', 'txt', 25, 1, 0, '2025-09-11 11:10:35', '2025-09-11 11:10:35'),
(83, NULL, 40, 'test/path.pdf', 'test_doc.pdf', 'pdf', 1024, 5, 0, '2025-09-11 11:14:29', '2025-09-11 11:14:29'),
(84, NULL, 41, 'test/path.pdf', 'test_doc.pdf', 'pdf', 1024, 5, 0, '2025-09-11 11:15:21', '2025-09-11 11:15:21'),
(86, NULL, 42, 'test/frontend_test.pdf', 'frontend_test.pdf', 'pdf', 2048, 3, 0, '2025-09-11 11:17:20', '2025-09-11 11:17:20'),
(87, NULL, 43, 'print-files/2025-09-11/QoLNti91e6pNVWLWOpIkhZwmxvHE2Hhv/1757589883_Kartu Nama Anjar Setyo Nugroho.pdf', 'Kartu Nama Anjar Setyo Nugroho.pdf', 'pdf', 386772, 1, 0, '2025-09-11 11:24:43', '2025-09-11 11:25:05'),
(95, NULL, 56, 'print-files/2025-09-15/iBT2lvobPav5qcsuvaQa7c3rTRBb4tDb/1757951697_Bukti_Kegiatan_Day 13.png', 'Bukti_Kegiatan_Day 13.png', 'png', 309163, 1, 0, '2025-09-15 15:54:58', '2025-09-15 16:10:07'),
(101, NULL, 57, 'print-files/2025-09-15/iBT2lvobPav5qcsuvaQa7c3rTRBb4tDb/1757952880_Bukti_Kegiatan_Day 13.png', 'Bukti_Kegiatan_Day 13.png', 'png', 309163, 1, 0, '2025-09-15 16:14:40', '2025-09-15 16:14:56'),
(110, NULL, 65, 'print-files/2025-09-16/S7xcRUemgKc82LIHLmQm1nlTzHPrBc9r/1757957358_Bukti_Kegiatan_Day 13.png', 'Bukti_Kegiatan_Day 13.png', 'png', 309163, 1, 0, '2025-09-15 17:29:18', '2025-09-15 17:29:37'),
(117, NULL, 70, 'print-files/2025-09-16/bV6bZpsPqGB1bhhLkY2U25yDEaRUt8UL/1757983556_Bukti_Kegiatan_Day 13.png', 'Bukti_Kegiatan_Day 13.png', 'png', 309163, 1, 0, '2025-09-16 00:45:56', '2025-09-16 00:50:11'),
(119, NULL, 71, 'print-files/2025-09-16/bV6bZpsPqGB1bhhLkY2U25yDEaRUt8UL/1757983902_Bukti_Kegiatan_Day 13.png', 'Bukti_Kegiatan_Day 13.png', 'png', 309163, 1, 0, '2025-09-16 00:51:42', '2025-09-16 00:52:04'),
(120, 117, NULL, 'print-files/2025-09-16/fdbsR6w48QOqfBB3n15Vd1JPLCp8NMgO/1757985022_duplicate_test.txt', 'duplicate_test.txt', 'txt', 57, 1, 0, '2025-09-16 01:10:22', '2025-09-16 01:10:22'),
(121, 117, NULL, 'print-files/2025-09-16/fdbsR6w48QOqfBB3n15Vd1JPLCp8NMgO/1757985022_different_test.txt', 'different_test.txt', 'txt', 34, 1, 0, '2025-09-16 01:10:22', '2025-09-16 01:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `print_orders`
--

CREATE TABLE `print_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_data` json NOT NULL,
  `paper_product_id` bigint UNSIGNED NOT NULL,
  `paper_variant_id` bigint UNSIGNED NOT NULL,
  `print_type` enum('bw','color') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `total_pages` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending_upload','uploaded','payment_pending','payment_confirmed','ready_to_print','printing','printed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_upload',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('unpaid','waiting','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` bigint UNSIGNED NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `printed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `print_orders`
--

INSERT INTO `print_orders` (`id`, `order_code`, `customer_phone`, `customer_name`, `file_data`, `paper_product_id`, `paper_variant_id`, `print_type`, `quantity`, `total_pages`, `unit_price`, `total_price`, `status`, `payment_method`, `payment_status`, `payment_proof`, `payment_token`, `payment_url`, `session_id`, `uploaded_at`, `printed_at`, `completed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'PRINT-11-09-2025-13-26-46', '085155228237', 'Raihan', '\"[{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15},{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15},{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15},{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15},{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15}]\"', 137, 46, 'color', 1, 75, '1500.00', '112500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 26, NULL, '2025-09-11 06:59:02', '2025-09-15 15:52:20', '2025-09-11 06:26:46', '2025-09-15 15:52:20', NULL),
(6, 'PRINT-11-09-2025-13-50-56', '0897665432', 'rauhan', '\"[{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15}]\"', 137, 46, 'color', 1, 15, '1500.00', '22500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 46, NULL, '2025-09-11 07:01:31', '2025-09-15 15:53:14', '2025-09-11 06:50:56', '2025-09-15 15:53:14', NULL),
(7, 'PRINT-11-09-2025-14-13-54', '085123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024000,\\\"pages\\\":5},{\\\"name\\\":\\\"presentation.pptx\\\",\\\"type\\\":\\\"pptx\\\",\\\"size\\\":2048000,\\\"pages\\\":10}]\"', 137, 45, 'color', 2, 15, '1500.00', '45000.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 48, NULL, NULL, '2025-09-15 15:53:14', '2025-09-11 07:13:54', '2025-09-15 15:53:14', NULL),
(8, 'PRINT-11-09-2025-14-14-25', '085123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024000,\\\"pages\\\":5},{\\\"name\\\":\\\"presentation.pptx\\\",\\\"type\\\":\\\"pptx\\\",\\\"size\\\":2048000,\\\"pages\\\":10}]\"', 137, 45, 'color', 2, 15, '1500.00', '45000.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 49, NULL, NULL, '2025-09-15 15:53:14', '2025-09-11 07:14:25', '2025-09-15 15:53:14', NULL),
(9, 'PRINT-11-09-2025-14-14-52', '085123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024000,\\\"pages\\\":5},{\\\"name\\\":\\\"presentation.pptx\\\",\\\"type\\\":\\\"pptx\\\",\\\"size\\\":2048000,\\\"pages\\\":10}]\"', 137, 45, 'color', 2, 15, '1500.00', '45000.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 50, NULL, NULL, '2025-09-15 15:54:18', '2025-09-11 07:14:52', '2025-09-15 15:54:18', NULL),
(10, 'PRINT-11-09-2025-14-15-43', '085123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024000,\\\"pages\\\":5},{\\\"name\\\":\\\"presentation.pptx\\\",\\\"type\\\":\\\"pptx\\\",\\\"size\\\":2048000,\\\"pages\\\":10}]\"', 137, 45, 'color', 2, 15, '1500.00', '45000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 51, NULL, NULL, NULL, '2025-09-11 07:15:43', '2025-09-11 11:54:11', NULL),
(11, 'PRINT-11-09-2025-14-17-14', '085123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024000,\\\"pages\\\":5},{\\\"name\\\":\\\"presentation.pptx\\\",\\\"type\\\":\\\"pptx\\\",\\\"size\\\":2048000,\\\"pages\\\":10}]\"', 137, 45, 'color', 2, 15, '1500.00', '45000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 52, NULL, NULL, NULL, '2025-09-11 07:17:14', '2025-09-11 11:54:11', NULL),
(12, 'PRINT-11-09-2025-14-17-29', '085123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024000,\\\"pages\\\":5},{\\\"name\\\":\\\"presentation.pptx\\\",\\\"type\\\":\\\"pptx\\\",\\\"size\\\":2048000,\\\"pages\\\":10}]\"', 137, 45, 'color', 2, 15, '1500.00', '45000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 53, NULL, NULL, NULL, '2025-09-11 07:17:29', '2025-09-11 11:54:11', NULL),
(13, 'PRINT-11-09-2025-14-19-14', '085123456789', 'Demo Customer', '\"[{\\\"name\\\":\\\"business_proposal.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":8},{\\\"name\\\":\\\"financial_report.xlsx\\\",\\\"type\\\":\\\"xlsx\\\",\\\"pages\\\":12}]\"', 137, 45, 'color', 3, 20, '1500.00', '90000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 54, NULL, NULL, NULL, '2025-09-11 07:19:14', '2025-09-11 11:54:11', NULL),
(14, 'PRINT-11-09-2025-14-21-22', '0139137644', 'lobow', '\"[{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15}]\"', 137, 46, 'color', 1, 15, '1500.00', '22500.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 55, NULL, NULL, NULL, '2025-09-11 07:21:22', '2025-09-11 11:54:11', NULL),
(15, 'PRINT-11-09-2025-14-31-29', '085123459777', 'Final Test Customer', '\"[{\\\"name\\\":\\\"final_test.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":7}]\"', 137, 45, 'color', 1, 7, '1500.00', '10500.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 56, NULL, '2025-09-11 07:31:29', '2025-09-11 07:31:29', '2025-09-11 07:31:29', '2025-09-11 11:54:11', NULL),
(16, 'PRINT-11-09-2025-14-31-30', '08599999001', 'Stress Customer 1', '\"[{\\\"name\\\":\\\"stress_1.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 57, NULL, NULL, NULL, '2025-09-11 07:31:30', '2025-09-11 11:54:11', NULL),
(17, 'PRINT-11-09-2025-14-31-31', '08599999002', 'Stress Customer 2', '\"[{\\\"name\\\":\\\"stress_2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 58, NULL, NULL, NULL, '2025-09-11 07:31:31', '2025-09-11 11:54:11', NULL),
(18, 'PRINT-11-09-2025-14-31-32', '08599999003', 'Stress Customer 3', '\"[{\\\"name\\\":\\\"stress_3.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 59, NULL, NULL, NULL, '2025-09-11 07:31:32', '2025-09-11 11:54:11', NULL),
(19, 'PRINT-11-09-2025-14-31-33', '08599999004', 'Stress Customer 4', '\"[{\\\"name\\\":\\\"stress_4.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 60, NULL, NULL, NULL, '2025-09-11 07:31:33', '2025-09-11 11:54:11', NULL),
(20, 'PRINT-11-09-2025-14-31-34', '08599999005', 'Stress Customer 5', '\"[{\\\"name\\\":\\\"stress_5.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 61, NULL, NULL, NULL, '2025-09-11 07:31:34', '2025-09-11 11:54:11', NULL),
(21, 'PRINT-11-09-2025-14-31-35', '08599999006', 'Stress Customer 6', '\"[{\\\"name\\\":\\\"stress_6.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 62, NULL, NULL, NULL, '2025-09-11 07:31:35', '2025-09-11 11:54:11', NULL),
(22, 'PRINT-11-09-2025-14-31-36', '08599999007', 'Stress Customer 7', '\"[{\\\"name\\\":\\\"stress_7.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 63, NULL, NULL, NULL, '2025-09-11 07:31:36', '2025-09-11 11:54:11', NULL),
(23, 'PRINT-11-09-2025-14-31-37', '08599999008', 'Stress Customer 8', '\"[{\\\"name\\\":\\\"stress_8.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 64, NULL, NULL, NULL, '2025-09-11 07:31:37', '2025-09-11 11:54:11', NULL),
(24, 'PRINT-11-09-2025-14-31-38', '08599999009', 'Stress Customer 9', '\"[{\\\"name\\\":\\\"stress_9.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 65, NULL, NULL, NULL, '2025-09-11 07:31:38', '2025-09-11 11:54:11', NULL),
(25, 'PRINT-11-09-2025-14-31-39', '08599999010', 'Stress Customer 10', '\"[{\\\"name\\\":\\\"stress_10.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '1000.00', '1000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 66, NULL, NULL, NULL, '2025-09-11 07:31:39', '2025-09-11 11:54:11', NULL),
(26, 'PRINT-11-09-2025-14-33-02', '08512340000', 'Cleanup Test Customer', '\"[{\\\"name\\\":\\\"cleanup_test.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":3}]\"', 137, 45, 'bw', 1, 3, '1000.00', '3000.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 67, NULL, '2025-09-11 07:33:02', '2025-09-11 07:33:02', '2025-09-11 07:33:02', '2025-09-11 11:54:11', NULL),
(27, 'PRINT-11-09-2025-14-35-08', '085155228237', 'Raihan', '\"[{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15}]\"', 137, 46, 'color', 1, 15, '1500.00', '22500.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 68, NULL, NULL, NULL, '2025-09-11 07:35:08', '2025-09-11 11:54:11', NULL),
(28, 'PRINT-11-09-2025-14-38-07', '08512340001', 'Final Fix Test', '\"[{\\\"name\\\":\\\"final_fix_test.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"pages\\\":2}]\"', 137, 45, 'bw', 1, 2, '1000.00', '2000.00', 'printing', 'toko', 'paid', NULL, NULL, NULL, 69, NULL, NULL, NULL, '2025-09-11 07:38:07', '2025-09-11 11:54:11', NULL),
(29, 'PRINT-11-09-2025-14-43-36', '019336377', 'raihan', '\"[{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15}]\"', 137, 46, 'color', 1, 15, '1500.00', '22500.00', 'printing', 'toko', 'paid', NULL, NULL, NULL, 70, NULL, NULL, NULL, '2025-09-11 07:43:36', '2025-09-11 11:54:11', NULL),
(30, 'PRINT-11-09-2025-14-49-15', '99357547547', 'raihan', '\"[{\\\"name\\\":\\\"ASAH ILT SOFT SKILL 2.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1794261,\\\"pages\\\":15}]\"', 137, 46, 'color', 1, 15, '1500.00', '22500.00', 'printing', 'toko', 'paid', NULL, NULL, NULL, 71, NULL, NULL, NULL, '2025-09-11 07:49:15', '2025-09-11 11:54:11', NULL),
(31, 'PRINT-11-09-2025-14-54-51', '019383272', 'anjar', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 137, 46, 'color', 1, 1, '1500.00', '1500.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 72, NULL, NULL, NULL, '2025-09-11 07:54:51', '2025-09-11 11:54:11', NULL),
(32, 'PRINT-11-09-2025-15-02-39', '0193872442', 'aduh', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 137, 46, 'color', 1, 1, '1500.00', '1500.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 73, NULL, NULL, NULL, '2025-09-11 08:02:39', '2025-09-11 11:54:11', NULL),
(33, 'PRINT-11-09-2025-15-09-57', '103810831', 'test', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 137, 46, 'color', 1, 1, '1500.00', '1500.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 74, NULL, NULL, NULL, '2025-09-11 08:09:57', '2025-09-11 11:54:11', NULL),
(34, 'PRINT-11-09-2025-16-23-50', '823873232', 'tes', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 137, 46, 'color', 1, 1, '1500.00', '1500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 77, NULL, NULL, NULL, '2025-09-11 09:23:50', '2025-09-11 11:54:11', NULL),
(35, 'PRINT-11-09-2025-16-35-45', '833325541', 'info', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 137, 46, 'color', 1, 1, '1500.00', '1500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 78, NULL, NULL, NULL, '2025-09-11 09:35:45', '2025-09-11 11:54:11', NULL),
(38, 'PRINT-11-09-2025-17-28-40', '133243535', 'testing lagi', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 137, 45, 'bw', 1, 1, '500.00', '500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 84, NULL, NULL, NULL, '2025-09-11 10:28:40', '2025-09-11 11:54:11', NULL),
(39, 'PRINT-11-09-2025-18-10-35', '08123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_document.txt\\\",\\\"type\\\":\\\"txt\\\",\\\"size\\\":25,\\\"pages\\\":1}]\"', 135, 45, 'bw', 1, 1, '500.00', '500.00', 'cancelled', 'cash', 'paid', NULL, NULL, NULL, 87, NULL, NULL, NULL, '2025-09-11 11:10:35', '2025-09-11 11:10:35', NULL),
(40, 'PRINT-11-09-2025-18-14-29', '08123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_doc.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024,\\\"pages\\\":5}]\"', 135, 45, 'bw', 2, 5, '500.00', '5000.00', 'ready_to_print', 'cash', 'paid', NULL, NULL, NULL, 88, NULL, NULL, NULL, '2025-09-11 11:14:29', '2025-09-11 11:14:29', NULL),
(41, 'PRINT-11-09-2025-18-15-21', '08123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_doc.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024,\\\"pages\\\":5}]\"', 135, 45, 'bw', 2, 5, '500.00', '5000.00', 'cancelled', 'cash', 'paid', NULL, NULL, NULL, 89, NULL, NULL, NULL, '2025-09-11 11:15:21', '2025-09-11 11:15:21', NULL),
(42, 'PRINT-11-09-2025-18-17-20', '08123456780', 'Frontend Test Customer', '\"[{\\\"name\\\":\\\"frontend_test.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":2048,\\\"pages\\\":3}]\"', 135, 60, 'color', 1, 3, '3000.00', '9000.00', 'ready_to_print', 'cash', 'paid', NULL, NULL, NULL, 91, NULL, NULL, NULL, '2025-09-11 11:17:20', '2025-09-11 11:54:11', NULL),
(43, 'PRINT-11-09-2025-18-25-05', '0193236324', 'ppppp', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 135, 45, 'bw', 1, 1, '500.00', '500.00', 'payment_pending', 'toko', 'waiting', NULL, NULL, NULL, 92, NULL, NULL, NULL, '2025-09-11 11:25:05', '2025-09-11 11:25:05', NULL),
(44, 'PRINT-11-09-2025-18-40-29', '08123456789', 'Test Customer', '\"[{\\\"name\\\":\\\"test_order.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1024,\\\"pages\\\":2}]\"', 135, 45, 'bw', 1, 2, '500.00', '1000.00', 'completed', 'cash', 'paid', NULL, NULL, NULL, 94, NULL, NULL, '2025-09-15 15:54:43', '2025-09-11 11:40:29', '2025-09-15 15:54:43', NULL),
(45, 'PRINT-11-09-2025-18-42-25', '013913813', 'popopp', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1},{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 135, 45, 'bw', 1, 2, '500.00', '1000.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 93, NULL, NULL, NULL, '2025-09-11 11:42:25', '2025-09-11 11:42:50', NULL),
(46, 'PRINT-11-09-2025-18-50-34', '013891389147', 'jfhfjshf', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 135, 45, 'bw', 1, 1, '500.00', '500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 93, NULL, NULL, NULL, '2025-09-11 11:50:34', '2025-09-11 11:50:46', NULL),
(47, 'PRINT-11-09-2025-18-56-54', '08123456789', 'Stress Test Customer', '\"[{\\\"name\\\":\\\"stress_test.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":2048,\\\"pages\\\":3}]\"', 135, 45, 'bw', 1, 3, '500.00', '1500.00', 'completed', 'cash', 'paid', NULL, NULL, NULL, 95, NULL, NULL, NULL, '2025-09-11 11:56:54', '2025-09-15 15:39:46', NULL),
(48, 'PRINT-11-09-2025-19-05-12', '0193863131', 'hahaha', '\"[{\\\"name\\\":\\\"Kartu Nama Anjar Setyo Nugroho.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":386772,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '1500.00', '1500.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 93, NULL, NULL, NULL, '2025-09-11 12:05:12', '2025-09-11 12:05:21', NULL),
(50, 'PRINT-15-09-2025-22-38-48', '031803810381', 'Raihan Rizki', '\"[{\\\"name\\\":\\\"barcode-SFWSFRW1313.pdf\\\",\\\"type\\\":\\\"pdf\\\",\\\"size\\\":1918,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '1500.00', '1500.00', 'completed', 'manual', 'paid', 'print-payments/PRINT-15-09-2025-22-38-48/payment_proof_1757950728.png', NULL, NULL, 96, NULL, NULL, NULL, '2025-09-15 15:38:48', '2025-09-15 15:39:30', NULL),
(56, 'PRINT-15-09-2025-23-10-07', '1301310334', 'rezaaa', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1},{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '1500.00', '3000.00', 'payment_pending', 'automatic', 'unpaid', NULL, 'ab171043-c5aa-4993-b15f-0183e039bcd4', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/ab171043-c5aa-4993-b15f-0183e039bcd4', 96, NULL, NULL, NULL, '2025-09-15 16:10:07', '2025-09-15 16:10:08', NULL),
(57, 'PRINT-15-09-2025-23-14-56', '3103801381', 'rezaaa', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '1500.00', '1500.00', 'payment_pending', 'automatic', 'unpaid', NULL, 'b295f718-4af1-4966-b69f-f928378a87fd', 'https://app.midtrans.com/snap/v2/vtweb/b295f718-4af1-4966-b69f-f928378a87fd', 96, NULL, NULL, NULL, '2025-09-15 16:14:56', '2025-09-15 16:14:56', NULL),
(58, 'PRINT-15-09-2025-23-23-27', '031840124242', 'hiyaaa', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, '7b0d7bb5-a5b8-49b6-8a80-5de0dcd42791', 'https://app.midtrans.com/snap/v2/vtweb/7b0d7bb5-a5b8-49b6-8a80-5de0dcd42791', 104, NULL, NULL, '2025-09-15 16:31:27', '2025-09-15 16:23:27', '2025-09-15 16:31:27', NULL),
(59, 'PRINT-15-09-2025-23-30-55', '13131324', 'aduh', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 7.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":232140,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, '869fe152-0aa9-4e5e-bac7-874d6deae5e2', 'https://app.midtrans.com/snap/v2/vtweb/869fe152-0aa9-4e5e-bac7-874d6deae5e2', 105, NULL, NULL, '2025-09-15 16:31:30', '2025-09-15 16:30:55', '2025-09-15 16:31:30', NULL),
(60, 'PRINT-15-09-2025-23-39-27', '183182642', 'amdnadn', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 5.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":209159,\\\"pages\\\":1},{\\\"name\\\":\\\"Bukti_Kegiatan_Day 3.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":282981,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 2, '2.00', '4.00', 'completed', 'automatic', 'paid', NULL, 'faa0949a-c30d-414f-9c31-dfe1437f4d13', 'https://app.midtrans.com/snap/v2/vtweb/faa0949a-c30d-414f-9c31-dfe1437f4d13', 106, NULL, NULL, '2025-09-15 16:40:21', '2025-09-15 16:39:27', '2025-09-15 16:40:21', NULL),
(61, 'PRINT-16-09-2025-00-01-01', '1301804814', 'hihih1', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 7.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":232140,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, 'a211423a-9bcb-413c-95e7-91eb19ef10a6', 'https://app.midtrans.com/snap/v2/vtweb/a211423a-9bcb-413c-95e7-91eb19ef10a6', 107, NULL, NULL, '2025-09-15 17:01:52', '2025-09-15 17:01:01', '2025-09-15 17:01:52', NULL),
(62, 'PRINT-16-09-2025-00-05-44', '1317482642', 'wfsufs', '\"[{\\\"name\\\":\\\"Answer_1.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":15362,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, '7620d375-788b-4ed0-a3a0-3a18741370b6', 'https://app.midtrans.com/snap/v2/vtweb/7620d375-788b-4ed0-a3a0-3a18741370b6', 108, NULL, NULL, '2025-09-15 17:18:15', '2025-09-15 17:05:44', '2025-09-15 17:18:15', NULL),
(63, 'PRINT-16-09-2025-00-15-30', '3143536474', 'aegeged', '\"[{\\\"name\\\":\\\"Answer_3.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":71261,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, '9de64fbf-a144-47f2-af6f-2fe74ee7fa72', 'https://app.midtrans.com/snap/v2/vtweb/9de64fbf-a144-47f2-af6f-2fe74ee7fa72', 109, NULL, NULL, '2025-09-15 17:16:06', '2025-09-15 17:15:30', '2025-09-15 17:16:06', NULL),
(64, 'PRINT-16-09-2025-00-19-03', '014808401', 'udgdutduts', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'ready_to_print', 'manual', 'paid', 'print-payments/PRINT-16-09-2025-00-19-03/payment_proof_1757956743.png', NULL, NULL, 110, NULL, NULL, '2025-09-15 17:20:36', '2025-09-15 17:19:03', '2025-09-15 17:26:05', NULL),
(65, 'PRINT-16-09-2025-00-29-37', '1314435', '2242425', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'payment_pending', 'manual', 'waiting', 'print-payments/PRINT-16-09-2025-00-29-37/payment_proof_1757957377.png', NULL, NULL, 111, NULL, NULL, NULL, '2025-09-15 17:29:37', '2025-09-15 17:29:37', NULL),
(66, 'PRINT-16-09-2025-00-36-19', '1492593535', 'hidup kita', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'ready_to_print', 'manual', 'paid', 'print-payments/PRINT-16-09-2025-00-36-19/payment_proof_1757957779.png', NULL, NULL, 112, NULL, NULL, '2025-09-15 17:36:39', '2025-09-15 17:36:19', '2025-09-15 17:41:09', NULL),
(67, 'PRINT-16-09-2025-00-43-20', '3647586969', 'sgdhdh', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 135, 46, 'color', 1, 1, '2.00', '2.00', 'completed', 'manual', 'paid', 'print-payments/PRINT-16-09-2025-00-43-20/payment_proof_1757958200.png', NULL, NULL, 113, NULL, NULL, '2025-09-15 17:43:38', '2025-09-15 17:43:20', '2025-09-15 17:43:38', NULL),
(68, 'PRINT-16-09-2025-07-14-51', '19274924', 'adjadjagd', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1},{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 2, '2.00', '4.00', 'completed', 'manual', 'paid', 'print-payments/PRINT-16-09-2025-07-14-51/payment_proof_1757981691.png', NULL, NULL, 115, NULL, NULL, '2025-09-16 00:15:44', '2025-09-16 00:14:51', '2025-09-16 00:15:44', NULL),
(69, 'PRINT-16-09-2025-07-17-02', '194792742', 'oooooo', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, '027bca44-0e47-49e2-9eaa-5e6a13321853', 'https://app.midtrans.com/snap/v2/vtweb/027bca44-0e47-49e2-9eaa-5e6a13321853', 115, NULL, NULL, '2025-09-16 00:17:57', '2025-09-16 00:17:02', '2025-09-16 00:17:57', NULL),
(70, 'PRINT-16-09-2025-07-50-11', '249249724', 'toko', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1},{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 1, '2.00', '4.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 116, NULL, NULL, NULL, '2025-09-16 00:50:11', '2025-09-16 00:50:21', NULL),
(71, 'PRINT-16-09-2025-07-52-04', '92479242', 'toko', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 1, '2.00', '2.00', 'ready_to_print', 'toko', 'paid', NULL, NULL, NULL, 116, NULL, NULL, NULL, '2025-09-16 00:52:04', '2025-09-16 00:52:12', NULL),
(72, 'PRINT-16-09-2025-08-30-43', '394927424', 'toko', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 1, '2.00', '2.00', 'completed', 'toko', 'paid', NULL, NULL, NULL, 116, NULL, NULL, '2025-09-16 01:31:00', '2025-09-16 01:30:43', '2025-09-16 01:31:00', NULL),
(73, 'PRINT-16-09-2025-08-35-32', '391371931', 'bank wak', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 1, '2.00', '2.00', 'completed', 'manual', 'paid', 'print-payments/PRINT-16-09-2025-08-35-32/payment_proof_1757986532.png', NULL, NULL, 116, NULL, NULL, '2025-09-16 01:35:57', '2025-09-16 01:35:32', '2025-09-16 01:35:57', NULL),
(74, 'PRINT-16-09-2025-08-52-08', '139371131', 'qris', '\"[{\\\"name\\\":\\\"Bukti_Kegiatan_Day 13.png\\\",\\\"type\\\":\\\"png\\\",\\\"size\\\":309163,\\\"pages\\\":1}]\"', 167, 107, 'bw', 1, 1, '2.00', '2.00', 'completed', 'automatic', 'paid', NULL, '402cfc16-b2e5-4839-969c-d5fa6c8eb5c8', 'https://app.midtrans.com/snap/v2/vtweb/402cfc16-b2e5-4839-969c-d5fa6c8eb5c8', 116, NULL, NULL, '2025-09-16 01:54:25', '2025-09-16 01:52:08', '2025-09-16 01:54:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `print_sessions`
--

CREATE TABLE `print_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `session_token` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode_token` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `current_step` enum('upload','select','payment','print','complete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upload',
  `started_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `print_sessions`
--

INSERT INTO `print_sessions` (`id`, `session_token`, `barcode_token`, `is_active`, `current_step`, `started_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'djPA7eewfgJekZlKBbZLNvAXzKfPhxPO', 'J0iP01lwEtRynD5wi6ORG4aiywvwZZqo', 0, 'upload', '2025-09-11 04:11:32', '2025-09-12 04:11:32', '2025-09-11 04:11:32', '2025-09-16 01:10:22'),
(13, 'nph9F3P5qMRYK0dEX8YwpPqT7rWj8Jff', 'wknOKAETydNPfD0j0VvWIEnr9Qye9FJB', 0, 'upload', '2025-09-11 05:01:03', '2025-09-12 05:01:03', '2025-09-11 05:01:03', '2025-09-16 01:10:22'),
(14, 'FuucyCWdZVA9KOq1DYkuUXHP5czWS6bV', 'dfC9ENrHx7J7tQcdH27sWVyu3wWxdnaL', 0, 'upload', '2025-09-11 05:01:09', '2025-09-12 05:01:09', '2025-09-11 05:01:09', '2025-09-16 01:10:22'),
(15, 'TpVJJK4cj1TbfQScUB00CSZ5jHsTJScl', 'HvSmCoMMJiNys39x8tjC9LCjreoOK0M9', 0, 'upload', '2025-09-11 05:01:52', '2025-09-12 05:01:52', '2025-09-11 05:01:52', '2025-09-16 01:10:22'),
(16, 'i3mPOmQcUL59Gx9niouaDTGXjCEdc42j', 'GuvpLEmA4CzmzjLT9n6eScFfcMW7vQum', 0, 'upload', '2025-09-11 05:04:32', '2025-09-12 05:04:32', '2025-09-11 05:04:32', '2025-09-16 01:10:22'),
(17, 'ElkqKMYj7vxh5bj7NLdUGEjGAxHmtlwd', 'dqJ9ZHsvqrPYSSwjjYPurruJaHE6PTSG', 0, 'upload', '2025-09-11 05:06:42', '2025-09-12 05:06:42', '2025-09-11 05:06:42', '2025-09-16 01:10:22'),
(18, 'kOEsCY0ZvGkxZ0j8axJtBdmsmG4FLHMZ', 'y5dCogkngWOAd7abtDmlxAK6nOan6evy', 0, 'upload', '2025-09-11 05:06:43', '2025-09-12 05:06:43', '2025-09-11 05:06:43', '2025-09-16 01:10:22'),
(19, 'm1Qzmbd1TelOVwcfMF6VYDPyyHl7Bf9Q', 'pbS3n1ZR8xFa5ehZSVyubhro7VWxLOYm', 0, 'upload', '2025-09-11 05:07:14', '2025-09-12 05:07:14', '2025-09-11 05:07:14', '2025-09-16 01:10:22'),
(20, '5QcVNQKPNrkfo42gcGnSDVsmKgEIXsRP', 'RbkyRuaArvtFEKB89tATqMWzHKWxL0wB', 0, 'upload', '2025-09-11 05:07:25', '2025-09-12 05:07:25', '2025-09-11 05:07:25', '2025-09-16 01:10:22'),
(21, 'AuMMrISJzW4aekba2U6qwYx6yuBzbRtG', 'MGtkAbKqUBit3dGM0JhF7NEbucXXBsOj', 0, 'upload', '2025-09-11 05:08:53', '2025-09-12 05:08:53', '2025-09-11 05:08:53', '2025-09-16 01:10:22'),
(22, 'reZfnlmPTvdJzWN8DlDMHvfp3Vau8ifv', 'n7ypgA8I4i1iIqdfdt06A71jhPs5R7hV', 0, 'upload', '2025-09-11 05:09:57', '2025-09-12 05:09:57', '2025-09-11 05:09:57', '2025-09-16 01:10:22'),
(23, 'Z2Ap0yw6vwhY9q8ZIOlWcHHFwlGHQwVR', 'gbezAEjPw2oukyLyJteBgO6nEgMgbfjC', 0, 'upload', '2025-09-11 05:11:14', '2025-09-12 05:11:14', '2025-09-11 05:11:14', '2025-09-16 01:10:22'),
(24, 'cBgJqpYQWioFTj7Y8M403BUR5nYGDxhk', 'Jsi4vR0r8amhGQ2UDSMddFaHYkfypNiy', 0, 'upload', '2025-09-11 05:11:16', '2025-09-12 05:11:16', '2025-09-11 05:11:16', '2025-09-16 01:10:22'),
(25, '8WRrXLWnNMpRe0bnQ4jD4BSSVygLcLZ7', 'pmp3VnOMEpj6pYm5CQLDoPU5g0mCjxjO', 0, 'upload', '2025-09-11 05:12:26', '2025-09-12 05:12:26', '2025-09-11 05:12:26', '2025-09-16 01:10:22'),
(26, 'QfCOuSQEO4Mew2av0ZcUatZ2EgoNmi5S', 'AwwybF2GN0TFhbyxQlho7DNKtVCUDEBi', 0, 'payment', '2025-09-11 05:13:02', '2025-09-12 05:13:02', '2025-09-11 05:13:02', '2025-09-16 01:10:22'),
(27, 'BEUDyAIAiFEGD4pyfcXPFwvVHjyzoY4m', 'XuoUETlVyNXZACR9viDz4HBW98wFpLJL', 0, 'upload', '2025-09-11 05:18:25', '2025-09-12 05:18:25', '2025-09-11 05:18:25', '2025-09-16 01:10:22'),
(28, 'KvWEtUHAXtV3lCQvAYeGBcom7C11oQa6', '8BzCZx8jR4uEQhw3PjAbyyDL2brfUdHb', 0, 'upload', '2025-09-11 05:19:16', '2025-09-12 05:19:16', '2025-09-11 05:19:16', '2025-09-16 01:10:22'),
(29, '8XxLCQfaPo4HUIyjxYcwPDH2ZB8XpzPB', 'YGZUTeAjSWOQeiK8CIQlGAEbOrgBK212', 0, 'upload', '2025-09-11 05:21:54', '2025-09-12 05:21:54', '2025-09-11 05:21:54', '2025-09-16 01:10:22'),
(30, 'TWucJxdjZJXrNSe61CtT0emr4lH4C463', 'xCV1rOhf61IOYyj61LxwyMZVtqn0PsRP', 0, 'upload', '2025-09-11 05:23:22', '2025-09-12 05:23:22', '2025-09-11 05:23:22', '2025-09-16 01:10:22'),
(31, 'OylNzpsadUgZGfKEXu38OE8SRjjqIlvx', 'mih1v9GkPLGFroSRGhqfn9PTN2NrFKGg', 0, 'upload', '2025-09-11 05:23:23', '2025-09-12 05:23:23', '2025-09-11 05:23:23', '2025-09-16 01:10:22'),
(32, 'SfhrwaaiVV1ZNodTyuUq4m5f8IxYuCJZ', 'z0B0ureir1XMwzmqL1YDAtERTYp1ekC3', 0, 'upload', '2025-09-11 05:24:36', '2025-09-12 05:24:36', '2025-09-11 05:24:36', '2025-09-16 01:10:22'),
(33, 'tPykkkdeFepWhG5sSYsXV8p52kRkiKL5', 'eHcaFV58yv9Jo7z10VPXm30f3xKcmfsI', 0, 'upload', '2025-09-11 05:25:17', '2025-09-12 05:25:17', '2025-09-11 05:25:17', '2025-09-16 01:10:22'),
(34, 'MHjaETL7fZwLSvzodq3mIGFZNTvD8z81', 'TSkyHBuWuK3L6HTbl2PTFH1SNS6TxArp', 0, 'upload', '2025-09-11 05:27:47', '2025-09-12 05:27:47', '2025-09-11 05:27:47', '2025-09-16 01:10:22'),
(35, 'UXn9YFzQMtqwcU5ZgMVdmzvQy9LND6Kb', '3AGNukd7E7syeVmNIts9MhJWLG4wt1v2', 0, 'upload', '2025-09-11 05:38:27', '2025-09-12 05:38:27', '2025-09-11 05:38:27', '2025-09-16 01:10:22'),
(36, 'cNk3OWqkC2mvmeQq9Olm7XxiweDxPVUl', 'o0dnp3wvtjU8QEzljuh530vo2BpPYdDr', 0, 'upload', '2025-09-11 05:40:21', '2025-09-12 05:40:21', '2025-09-11 05:40:21', '2025-09-16 01:10:22'),
(37, 'ceEXxC9SOmO6amE4E3rbFKyrNcN9lKlv', 'YJFQgYKeP0Cf3MHeXcn2nLffjPxnqaGO', 0, 'upload', '2025-09-11 05:42:18', '2025-09-12 05:42:18', '2025-09-11 05:42:18', '2025-09-16 01:10:22'),
(38, 't6fK7GmW7oggOt4EUxfbrGVcxG8ksE0z', '6ZhlQUbihz5xmwSranj6Ku203Ri43g6I', 0, 'upload', '2025-09-11 05:51:42', '2025-09-12 05:51:42', '2025-09-11 05:51:42', '2025-09-16 01:10:22'),
(39, 'o65jSFDLIPzc2IfcXJJonofqa6E6BHlf', 'oyXXeaKADiDg58Ho2WFmLzO2c1GpFtdo', 0, 'upload', '2025-09-11 05:53:36', '2025-09-12 05:53:36', '2025-09-11 05:53:36', '2025-09-16 01:10:22'),
(40, 'j6okIZtkkoGI8a0RNJkfrjKCbmu4FEPc', 'CrsPZObHzHEHh6al15wYFSSKYQTmCCJV', 0, 'upload', '2025-09-11 05:55:08', '2025-09-12 05:55:08', '2025-09-11 05:55:08', '2025-09-16 01:10:22'),
(41, '6DfsixPo11wJsYBNUT0Ctf4E5fDEjuGC', 'CxXSYzKA5dSY2ES9JqrG5j9V7yFWdJv7', 0, 'upload', '2025-09-11 06:05:39', '2025-09-12 06:05:39', '2025-09-11 06:05:39', '2025-09-16 01:10:22'),
(42, 'MrcZW6zGfYyFbV6I3a1vtN1uv7hSFk4n', 'xjfDHbD3EC8xypT7GdnftSDUBVnTOpEt', 0, 'upload', '2025-09-11 06:20:54', '2025-09-12 06:20:54', '2025-09-11 06:20:54', '2025-09-16 01:10:22'),
(43, 'eM5nZtFlhxpanS2Z84AYNMnJEJevBNoY', 'HTjLPlz71OZ1VqfA6XlP9Ky1xvl8FXxx', 0, 'upload', '2025-09-11 06:21:39', '2025-09-12 06:21:39', '2025-09-11 06:21:39', '2025-09-16 01:10:22'),
(46, 'cegryxRqkrXLFqpjEUz3IYbWweX3o5ZU', 'gyeD9BUadwL7UcN6zugcuSCLr901gCzN', 0, 'payment', '2025-09-11 06:50:21', '2025-09-12 06:50:21', '2025-09-11 06:50:21', '2025-09-16 01:10:22'),
(47, 'oyq5DOA1jUpE44Hyzs5ybFHAvMfm1EDv', 'tcY0bL7nFwHcinnhJodLWJ08BWCFYiQT', 0, 'upload', '2025-09-11 07:12:13', '2025-09-12 07:12:13', '2025-09-11 07:12:13', '2025-09-16 01:10:22'),
(48, 'fELZsgHRTxAByhqMoLFFldihIj1SpAWP', 'NKqVvOFeCrD2WNt1xPD9X5FwB1LMl6Df', 0, 'upload', '2025-09-11 07:13:54', '2025-09-12 07:13:54', '2025-09-11 07:13:54', '2025-09-16 01:10:22'),
(49, 'faN7eZGyJXR5pNltWXurEoDryhehZpRI', 'DYJ9zoyrhB6UzZxrwiHzg7Wbey9idllp', 0, 'upload', '2025-09-11 07:14:25', '2025-09-12 07:14:25', '2025-09-11 07:14:25', '2025-09-16 01:10:22'),
(50, 'hmi50oBXSyV79Igqs816QnYulAHnPDRw', 'nYaaNoMFjkb3uiD4NiEA6ndpitZ2e1uR', 0, 'upload', '2025-09-11 07:14:52', '2025-09-12 07:14:52', '2025-09-11 07:14:52', '2025-09-16 01:10:22'),
(51, 'hsjZ4RiaBDGKbrPi5vv7tCMn9o2Uc7QS', 'NQL8ELyz6TNcV18JMM3tLFJ1FWxtit3e', 0, 'upload', '2025-09-11 07:15:43', '2025-09-12 07:15:43', '2025-09-11 07:15:43', '2025-09-16 01:10:22'),
(52, 'igHLWFwuouctCLYEb49aBH4cApUWEOUE', 'lrGmiEMBwpnNPE2mB3yuvzJ2jVsyRn5K', 0, 'upload', '2025-09-11 07:17:14', '2025-09-12 07:17:14', '2025-09-11 07:17:14', '2025-09-16 01:10:22'),
(53, '81J9d4MYJPuWL2GR1NJsZP6Qpt6KH5WK', '2Oy8mKzg0wYqQeDglB2IL4RpO8JjeJU0', 0, 'upload', '2025-09-11 07:17:29', '2025-09-12 07:17:29', '2025-09-11 07:17:29', '2025-09-16 01:10:22'),
(54, 'UlIWpAP6QDFUleuuFIjAXnTYYd1fLxap', '1h5rVi2Lzwezu1MsRQkT55OQ1PhAdJCF', 0, 'upload', '2025-09-11 07:19:14', '2025-09-12 07:19:14', '2025-09-11 07:19:14', '2025-09-16 01:10:22'),
(55, 'wv91lxl7LtfQ2CNY3Ji7Jq2Yxd1n0VP9', 'BU0OfFDvJr6k1JyghB5xoQhUbGppcCww', 0, 'payment', '2025-09-11 07:20:51', '2025-09-12 07:20:51', '2025-09-11 07:20:51', '2025-09-16 01:10:22'),
(56, 'nu7eqnoFlGf2OC75XLFnLOiDQfjoZqSE', '3eUkAHYWY6SYHKQkUVIzG99EO6vo2Yyy', 0, 'complete', '2025-09-11 07:31:29', '2025-09-12 07:31:29', '2025-09-11 07:31:29', '2025-09-16 01:10:22'),
(57, '1FpGVn13CbAVb0hIPpNMBk1mZDDnUj8b', 'fmc7o92vnAjGD3ZHRtptQsZ5ybdZO8PB', 0, 'upload', '2025-09-11 07:31:29', '2025-09-12 07:31:29', '2025-09-11 07:31:29', '2025-09-16 01:10:22'),
(58, 'z9AbUFcTydBzl26ULrOE9krJRfjr7yAp', 'gGbkdyqegMz6Rnn3RGF5rU8OdqTQg5ei', 0, 'upload', '2025-09-11 07:31:30', '2025-09-12 07:31:30', '2025-09-11 07:31:30', '2025-09-16 01:10:22'),
(59, 'iXrQEP94whWk6rnY7zNQcJA6d8yENJtk', '6BbBxBIx2YYjGZfUSkzrjfMag6Y5OGVr', 0, 'upload', '2025-09-11 07:31:31', '2025-09-12 07:31:31', '2025-09-11 07:31:31', '2025-09-16 01:10:22'),
(60, 'B52B7w9vTHRosy0Ueryvh4otienJy5ot', 'xy1pc14X7tunu7IwbbFF3YAvlXgiWUaa', 0, 'upload', '2025-09-11 07:31:32', '2025-09-12 07:31:32', '2025-09-11 07:31:32', '2025-09-16 01:10:22'),
(61, 'rs3Gks67XXgbX5Yx5vGXlrohudFxxx1k', '0675nyRdTeZAfZtmt2HgPNRyYonJokKX', 0, 'upload', '2025-09-11 07:31:33', '2025-09-12 07:31:33', '2025-09-11 07:31:33', '2025-09-16 01:10:22'),
(62, 'GRvydRH24bKf5giYYJZyba7DVUzKvqU0', 'WJytrCQy1maVyplT6SIkHeB3aS20kXle', 0, 'upload', '2025-09-11 07:31:34', '2025-09-12 07:31:34', '2025-09-11 07:31:34', '2025-09-16 01:10:22'),
(63, '7ezOTmqR8jPa7ONlHug2yZ1v681ONY7a', 'Jd19Yhcz0fYjZDZqzEMfBvpfiKu7mAjA', 0, 'upload', '2025-09-11 07:31:35', '2025-09-12 07:31:35', '2025-09-11 07:31:35', '2025-09-16 01:10:22'),
(64, 'O8WaXhNWb1FD0zSJG8M7i0nFvT4g0Kyh', 'Rq9FYqcT0VoRByquCqyB5oLinXb8zAys', 0, 'upload', '2025-09-11 07:31:36', '2025-09-12 07:31:36', '2025-09-11 07:31:36', '2025-09-16 01:10:22'),
(65, 'WS1QdQoaSd7Hh1HIkK1UfJFHtJC3szLg', 'KfvJ9SYFlreuLfkdSIi5V8CfCWG75sNp', 0, 'upload', '2025-09-11 07:31:37', '2025-09-12 07:31:37', '2025-09-11 07:31:37', '2025-09-16 01:10:22'),
(66, 'QxdMneppUNcbJt3UjGUwMdXpjfKVMyp7', 'EK5gFjCFqB56MpDMmDO21iL5NBPHhzoU', 0, 'upload', '2025-09-11 07:31:38', '2025-09-12 07:31:38', '2025-09-11 07:31:38', '2025-09-16 01:10:22'),
(67, 'jq9sN7jpnMuNZoOmkqKml3Cm33u6WPBO', 'rTqgCjM7WHq1svKvZ7EUivc54OpWDGcn', 0, 'complete', '2025-09-11 07:33:02', '2025-09-12 07:33:02', '2025-09-11 07:33:02', '2025-09-16 01:10:22'),
(68, 'VH9tPhpamN2tVOnRSI2XqUN1wNbkMfVs', '5AAlE8Eszili2v84Ba0Ir6UFbMawBvjN', 0, 'payment', '2025-09-11 07:34:39', '2025-09-12 07:34:39', '2025-09-11 07:34:39', '2025-09-16 01:10:22'),
(69, 'PRCwukcqKzMYS0r82gdfgaLm9Pq24Faa', 'DQNxjkpmVLKOYDDPPHf3wUafhtcU2lpl', 0, 'upload', '2025-09-11 07:38:07', '2025-09-12 07:38:07', '2025-09-11 07:38:07', '2025-09-16 01:10:22'),
(70, 'jD0N13mU8tHvS7UMEMWAWjFuGT3p4Qkt', 'hdH4golym1R5mmrJTUfVo4ENaBEKokSN', 0, 'payment', '2025-09-11 07:43:07', '2025-09-12 07:43:07', '2025-09-11 07:43:07', '2025-09-16 01:10:22'),
(71, 'eGQNc83SaDEYL0sPzkkePUam0vCaFOJd', '2LokNnwEm7cH5pkJdL2AUwXqSJpQrBGK', 0, 'payment', '2025-09-11 07:48:44', '2025-09-12 07:48:44', '2025-09-11 07:48:44', '2025-09-16 01:10:22'),
(72, 'ppA7mZEV585TBpuCuE3HfuyET0yIePbe', 'xALipYPAsyqToU5zQxG6AzwJl3RPSaIm', 0, 'payment', '2025-09-11 07:54:27', '2025-09-12 07:54:27', '2025-09-11 07:54:27', '2025-09-16 01:10:22'),
(73, 'wTxU9ghZ2697v84SSBEOYQN5DVNh8JN2', 'JTCIdyms3MnrBuKdQojGhNgjFVrixkJA', 0, 'payment', '2025-09-11 08:02:17', '2025-09-12 08:02:17', '2025-09-11 08:02:17', '2025-09-16 01:10:22'),
(74, 'RyCxE4M2KReK5FlbGlavNaFBSsT41P1y', 'JQdXvHGKiaAmxbcK52YSfs63YOi9bKev', 0, 'payment', '2025-09-11 08:09:35', '2025-09-12 08:09:35', '2025-09-11 08:09:35', '2025-09-16 01:10:22'),
(76, 'DcKtbuVUxv3RHLVEWEsJffcODOuDAxFB', 'W8idBlOKC8yFo4mhewJsjvg9a7fTUbEd', 0, 'upload', '2025-09-11 09:21:56', '2025-09-12 09:21:56', '2025-09-11 09:21:56', '2025-09-16 01:10:22'),
(77, 'duYkOpVq08nYXayEz3GuSUB0BJhe1365', 'Drb4H91CVxvzzQ8u2YeDB4VcXPdt5hxF', 0, 'payment', '2025-09-11 09:23:27', '2025-09-12 09:23:27', '2025-09-11 09:23:27', '2025-09-16 01:10:22'),
(78, 'CglNTpyL9VOoFPwYrNvt2XCyDbsqXKx8', 'DoH3P4UgKzcB31nSYlME1TSACI4Oh7aU', 0, 'payment', '2025-09-11 09:35:16', '2025-09-12 09:35:16', '2025-09-11 09:35:16', '2025-09-16 01:10:22'),
(79, 'TEST-COMPLETE-1757584035', 'BARCODE-1757584035', 0, 'upload', '2025-09-11 09:47:15', '2025-09-11 11:47:15', '2025-09-11 09:47:15', '2025-09-16 01:10:22'),
(80, 'TEST-COMPLETE-1757584077', 'BARCODE-1757584077', 0, 'upload', '2025-09-11 09:47:57', '2025-09-11 11:47:57', '2025-09-11 09:47:57', '2025-09-16 01:10:22'),
(84, 'GJKmtnvvH5gNcvIvoHcEtGYtr5OJapRB', 'vBzItWU0eyUx3hJCqxujIXEaAaGliWW3', 0, 'payment', '2025-09-11 10:28:03', '2025-09-12 10:28:03', '2025-09-11 10:28:03', '2025-09-16 01:10:22'),
(85, 'QGJuCS9UraQtWkvXzT4LCfK7Y7I1eKQk', 'hqkrU9FBir84aPnLYoX2EtFOJ5zKknpP', 0, 'upload', '2025-09-11 11:10:03', '2025-09-12 11:10:03', '2025-09-11 11:10:03', '2025-09-16 01:10:22'),
(86, 'cbkJPAlMJT2Nw26bn1Ebu2ecTwyBuDVk', 'fi6Jci4Hycu7CJrQMwPZFeHJtl26cQTO', 0, 'upload', '2025-09-11 11:10:22', '2025-09-12 11:10:22', '2025-09-11 11:10:22', '2025-09-16 01:10:22'),
(87, 'iD0kMwNLEUBQ0IGfigiPzbNwBELvOxbn', '5zyE6Kw1GKtNNECI0acWFwFC87ReIuds', 0, 'payment', '2025-09-11 11:10:35', '2025-09-12 11:10:35', '2025-09-11 11:10:35', '2025-09-16 01:10:22'),
(88, 'J4LsASn7LlumvVU3jjTp0NVfLtl1Pp3u', 'RFYBuvhtOxdXx6dfHWzUxeIRefeEsss8', 0, 'payment', '2025-09-11 11:14:29', '2025-09-12 11:14:29', '2025-09-11 11:14:29', '2025-09-16 01:10:22'),
(89, 'iSiNF6htIuNSYqqIo4KiDmP59oz8m0e5', 'i7QS0vrPV1ReFDxDf1HgBBFneomx3A0a', 0, 'payment', '2025-09-11 11:15:21', '2025-09-12 11:15:21', '2025-09-11 11:15:21', '2025-09-16 01:10:22'),
(90, 'PEdA5RQ5LabPdi2vc3QagU0uQB1U5R3S', 'CRuw0f3YDOuPzCQK2C9pKl7ec3BXwJLp', 0, 'upload', '2025-09-11 11:16:27', '2025-09-12 11:16:27', '2025-09-11 11:16:27', '2025-09-16 01:10:22'),
(91, '0e62JfQyWddWu5m1Dgdq5UEDFUPNi6PA', 'XSFPfao4OjfXCtK7TqJnDkd34wzVDkWE', 0, 'payment', '2025-09-11 11:17:20', '2025-09-12 11:17:20', '2025-09-11 11:17:20', '2025-09-16 01:10:22'),
(92, 'QoLNti91e6pNVWLWOpIkhZwmxvHE2Hhv', 'AJ6vYTGCgcbDTfkevJZm9fxH23q3JEIc', 0, 'payment', '2025-09-11 11:24:09', '2025-09-12 11:24:09', '2025-09-11 11:24:09', '2025-09-16 01:10:22'),
(93, 'suEAg5DMV4gjPaiHZQUXkomM3ln6O25h', '7OfmNcHZSxLXn5xE7zq9Pxao44kDkn56', 0, 'payment', '2025-09-11 11:26:23', '2025-09-12 11:26:23', '2025-09-11 11:26:23', '2025-09-16 01:10:22'),
(94, 'NjN6U0bAuk6vzxxHIVEoOX7B60AbnCg6', 'TzWrWFUkKZIoTS0zNZkaoY9U68EAHLdv', 0, 'payment', '2025-09-11 11:40:29', '2025-09-12 11:40:29', '2025-09-11 11:40:29', '2025-09-16 01:10:22'),
(95, 'gmWEK8bDkRzCwwqBCcpMmmPLRZk40rGC', 'yvz2HVhmsCupN8DX4Ef0BG3lYwUe0LiW', 0, 'payment', '2025-09-11 11:56:54', '2025-09-12 11:56:54', '2025-09-11 11:56:54', '2025-09-16 01:10:22'),
(96, 'iBT2lvobPav5qcsuvaQa7c3rTRBb4tDb', 'JAe4441eUL3wxFFBLedqSpqauDan3QRf', 1, 'payment', '2025-09-15 15:37:14', '2025-09-16 15:37:14', '2025-09-15 15:37:14', '2025-09-15 15:38:48'),
(102, 'fBt7XKg85XqVrE2iO1SFEvMtu4mBmNkz', 'Da7J326jponsJ5S0Hmwnni1OXCSZHy74', 1, 'upload', '2025-09-15 16:12:33', '2025-09-16 16:12:33', '2025-09-15 16:12:33', '2025-09-15 16:12:33'),
(103, 'FSjz6KZXpIygzPYegsNPaCbSQFXMiapO', 'v8H4wDyWxyiF0jWkxk7UtHRXuPy7LvP4', 1, 'upload', '2025-09-15 16:19:35', '2025-09-16 16:19:35', '2025-09-15 16:19:35', '2025-09-15 16:19:35'),
(104, 'OAjQNAHMbHDCuJdjIaD9rcSgWQdWlbvZ', 'muD7achVIYUx4MzF6ovvZVLQF5y9706K', 1, 'payment', '2025-09-15 16:23:04', '2025-09-16 16:23:04', '2025-09-15 16:23:04', '2025-09-15 16:23:27'),
(105, 'WX8TgrJwOx2fIyLTGmRtbgepNQ185sQE', '7PnFRA9vcVixpVdt7J2PToKMaS86Xaq8', 1, 'payment', '2025-09-15 16:30:32', '2025-09-16 16:30:32', '2025-09-15 16:30:32', '2025-09-15 16:30:55'),
(106, 'DOlYbmw2Vm7P9a6SyLfzvPhr6RbaejLI', 'xIztnh7hDFFlcWo8RGhVi6cg3LQEuPa5', 1, 'payment', '2025-09-15 16:31:43', '2025-09-16 16:31:43', '2025-09-15 16:31:43', '2025-09-15 16:39:27'),
(107, 'eS3mPGqbKNuyvKkuhBqAtuzS8zUDopBw', '2u4Fp9y0BC7NIHjrAX4cbqBezkYi9un5', 1, 'payment', '2025-09-15 17:00:30', '2025-09-16 17:00:30', '2025-09-15 17:00:30', '2025-09-15 17:01:01'),
(108, 'AFOS5q0EI5RUpEqY2v9WUWjQzumIjl5u', 'MU6F9hbdl20uKP2SNeiLazg48ZGzdLux', 1, 'payment', '2025-09-15 17:05:21', '2025-09-16 17:05:21', '2025-09-15 17:05:21', '2025-09-15 17:05:44'),
(109, 'kk7gNxqIzVnYhCoEpt4YOqapNVXcPkKB', 'Rpa0CDXYtbU1JzeZ2Fj3VWMnHZ6OfCXf', 1, 'payment', '2025-09-15 17:15:10', '2025-09-16 17:15:10', '2025-09-15 17:15:10', '2025-09-15 17:15:30'),
(110, 'XWJIOqRM5ZJPsAG0TRiUytP4VXC9zWNj', 'cEqaRUtNgTGrddUYB0g6fVQZ4GElE60Q', 1, 'payment', '2025-09-15 17:18:35', '2025-09-16 17:18:35', '2025-09-15 17:18:35', '2025-09-15 17:19:03'),
(111, 'S7xcRUemgKc82LIHLmQm1nlTzHPrBc9r', 'keI2oO2TgXneWqHitD9LKNL0YKAIQqU0', 1, 'payment', '2025-09-15 17:29:14', '2025-09-16 17:29:14', '2025-09-15 17:29:14', '2025-09-15 17:29:37'),
(112, 'HYiEQUphx1ghegn6KEjzEvQwQaIlxfVs', '0fL4hDgBn4YlnG2vhXLnuHmduirfRfru', 1, 'payment', '2025-09-15 17:35:53', '2025-09-16 17:35:53', '2025-09-15 17:35:53', '2025-09-15 17:36:19'),
(113, 'wAsqUg1codLdSqBAFnm54lhJkZpMohP5', 'JHVXd15uNKRPTV9tuD5GLg9WzU0U0oJp', 1, 'payment', '2025-09-15 17:42:56', '2025-09-16 17:42:56', '2025-09-15 17:42:56', '2025-09-15 17:43:20'),
(114, 'gFQkos5p86oPcw4LStP9JjXS0pJcc0pl', 'uH8KSRPLfRs4YWtx8KmEtzgXAxwxwh4C', 1, 'upload', '2025-09-15 23:51:17', '2025-09-16 23:51:17', '2025-09-15 23:51:17', '2025-09-15 23:51:17'),
(115, 'cbwoaH9sVKdxHIwjsbgSHMI4M2xykcwY', 'Yp5apLbsdlLG8OMTFHaoKvUBdneTMUGi', 1, 'payment', '2025-09-16 00:03:42', '2025-09-17 00:03:42', '2025-09-16 00:03:42', '2025-09-16 00:14:51'),
(116, 'bV6bZpsPqGB1bhhLkY2U25yDEaRUt8UL', 'Hf05Jm3PNhOgXfuB4Dt5584L97qTIgdF', 1, 'payment', '2025-09-16 00:45:53', '2025-09-17 00:45:53', '2025-09-16 00:45:53', '2025-09-16 00:50:11'),
(117, 'fdbsR6w48QOqfBB3n15Vd1JPLCp8NMgO', 'Ixrtzw66qcvNL84KfbBxw9zGNOulsDlY', 1, 'upload', '2025-09-16 01:10:22', '2025-09-17 01:10:22', '2025-09-16 01:10:22', '2025-09-16 01:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `base_price` decimal(15,2) DEFAULT NULL,
  `total_stock` int NOT NULL DEFAULT '0',
  `sold_count` int NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_print_service` tinyint(1) NOT NULL DEFAULT '0',
  `harga_beli` decimal(15,2) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `barcode` bigint DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` int DEFAULT NULL,
  `is_smart_print_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `type`, `name`, `link1`, `link2`, `link3`, `slug`, `price`, `base_price`, `total_stock`, `sold_count`, `rating`, `is_featured`, `is_print_service`, `harga_beli`, `weight`, `length`, `width`, `height`, `barcode`, `short_description`, `description`, `status`, `is_smart_print_enabled`, `user_id`, `brand_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(3, '0149794284247257524', 'configurable', 'PRINT ON DEMAND | CETAK KERTAS HVS', 'https://viviashop.com/', NULL, NULL, 'print-on-demand-cetak-kertas-hvs', '15000.00', '15000.00', 155, 0, '0.00', 0, 1, '10000.00', '1.00', '33.00', '48.00', NULL, 4760867187, 'Print on demand | Spesialis cetak HVS | Up to A3+ | Speed up to 100 PPM Full colour', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>Layanan Print on Demand (Cetak HVS)</p><ul><li><strong>Spesialisasi: </strong>Cetak kertas HVS dengan kualitas tinggi.</li><li><strong>Ukuran Maksimum: </strong>Dapat mencetak hingga ukuran A3+ (33cm x 48cm).</li><li><strong>Kecepatan Cetak: </strong>Capai kecepatan hingga 100 halaman per menit (100ppm).</li><li><strong>Warna: </strong>Mendukung cetak full color untuk hasil yang tajam dan berkualitas.</li><li><strong>Kontak:</strong><ul><li>Nomor telepon: 082145840999</li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><u>sinaragung5758@gmail.com</u></a></li><li>Lokasi: Tebulreng IV No. 38, Cukir, Jombang.</li></ul></li></ul>', 1, 1, 1, NULL, NULL, '2025-05-10 10:23:19', '2025-09-15 22:50:28'),
(4, '04fb7d48-e049-4bbe-9f44-707265a75399', 'configurable', 'PETA A3', 'https://viviashop.com/', 'https://katalog.inaproc.id/sinar-agung-jayaa', NULL, 'peta-a3', '2500.00', '2500.00', 0, 0, '0.00', 0, 1, '1000.00', '1.00', '43.00', '30.00', NULL, 3240868614, 'Kertas HVS 70-80 Gsm A3 | Menggunakan Inkjet Pigment anti luntur apabila terkena air.', '<p style=\"margin-left:0px;\"><strong>Produk: Peta A3</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Peta cetak dengan ukuran <strong>A3 </strong>.</li><li>Digunakan untuk menampilkan informasi geografis atau wilayah tertentu, seperti peta daerah atau area spesifik.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Kertas: </strong>HVS (High Volume Superior) dengan ketebalan <strong>70-80 Gsm </strong>.</li><li><strong>Teknologi Cetak: </strong>Menggunakan <strong>Inkjet Pigment </strong>, yang tahan luntur jika terkena air, menjaga kualitas cetakan tetap baik meskipun terpapar kelembapan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Tampilan detail dan jelas pada peta.</li><li>Kualitas cetak tinggi dengan warna yang tajam dan stabil.</li><li>Cocok untuk kebutuhan profesional seperti studi wilayah, perencanaan urban, atau presentasi.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 1, 1, NULL, NULL, '2025-05-10 11:05:06', '2025-09-15 22:50:46'),
(5, '2c5f9f44-f3be-4e27-8516-a18baca13295', 'configurable', 'KUESIONER & PRELIST', 'https://viviashop.com/', NULL, NULL, 'kuesioner-prelist', '15000.00', '15000.00', 0, 0, '0.00', 0, 1, '10000.00', '1.00', '1.00', '1.00', NULL, 3854342277, 'Kertas HVS 70-80 Gsm \r\nUkuran Double F4 Landscape / A3 \r\nLipat Tengah, Staples', '<p style=\"margin-left:0px;\"><strong>Produk: Kuesioner &amp; Prelist</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Dokumen cetak yang dirancang untuk kebutuhan kuesioner dan prelist.</li><li>Cocok digunakan dalam survei, penelitian, atau pengumpulan data.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Kertas: </strong>HVS (High Volume Superior) dengan ketebalan <strong>70-80 Gsm </strong>, menawarkan kualitas cetak yang baik dan tahan lama.</li><li><strong>Ukuran: </strong>Double F4 Landscape / A3, memberikan ruang cukup untuk desain kompleks dan informasi detail.</li><li><strong>Proses Pemrosesan:</strong><ul><li><strong>Lipat Tengah: </strong>Dilipat di tengah untuk memudahkan penyimpanan dan penggunaan.</li><li><strong>Staples: </strong>Dikaitkan menggunakan staples agar tetap rapi dan terorganisir.</li></ul></li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain profesional dengan elemen seperti tabel, kolom, dan QR Code.</li><li>Format yang mudah digunakan untuk mengisi data secara manual atau digital.</li><li>Cocok untuk kebutuhan akademik, bisnis, atau administratif.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 1, 1, NULL, NULL, '2025-05-10 11:10:02', '2025-09-15 22:51:32'),
(6, '4a821cff-fc09-4373-ae9d-18588ca474ba', 'configurable', 'SPIRAL NOTE BOOK', 'https://viviashop.com/', NULL, NULL, 'spiral-note-book', '15000.00', '15000.00', 0, 0, '0.00', 0, 1, '10000.00', '1.00', '1.00', '1.00', NULL, 3910037612, 'Kertas HVS 70-80 Gsm A4/ A5, Sampul Artpaper\r\n Isi sesuai pesanan', '<p style=\"margin-left:0px;\"><strong>Produk: Spiral Notebook</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Buku catatan spiral dengan desain profesional dan kustomisasi.</li><li>Cocok untuk kebutuhan sekolah, kerja, atau promosi perusahaan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Kertas: </strong>HVS (High Volume Superior) dengan ketebalan <strong>70-80 Gsm </strong>, memberikan kualitas cetak yang baik dan tahan lama.</li><li><strong>Ukuran: </strong>Tersedia dalam ukuran <strong>A4 </strong>dan <strong>A5 </strong>, sesuai dengan kebutuhan pengguna.</li><li><strong>Cover/Sampul: </strong>Terbuat dari <strong>Art Paper </strong>, memberikan kesan elegan dan tahan lama.</li><li><strong>Isi: </strong>Dapat disesuaikan dengan pesanan, seperti jumlah halaman, pola garis, atau format lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi dengan logo atau grafik perusahaan.</li><li>Spiral binding yang kuat, memungkinkan buku tetap rapi dan mudah digunakan.</li><li>Kualitas kertas yang nyaman untuk menulis atau mencetak.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 1, 1, NULL, NULL, '2025-05-10 11:17:12', '2025-09-15 22:52:27'),
(7, '28cc40cd-d45d-4510-bd51-4d879380a2fd', 'simple', 'SPIRAL NOTEPAD', 'https://viviashop.com/', NULL, NULL, 'spiral-notepad', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9849395051, 'Kertas HVS 70-80 Gsm A6, Sampul Artpaper\r\nIsi sesuai pesanan', '<p style=\"margin-left:0px;\"><strong>Produk: Spiral Notepad</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Buku catatan spiral dengan desain profesional dan kustomisasi.</li><li>Cocok untuk kebutuhan sekolah, kerja, atau promosi perusahaan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Kertas: </strong>HVS (High Volume Superior) dengan ketebalan <strong>70-80 Gsm </strong>, memberikan kualitas cetak yang baik dan tahan lama.</li><li><strong>Ukuran: </strong>Tersedia dalam ukuran <strong>A6 </strong>, praktis dan mudah dibawa.</li><li><strong>Cover/Sampul: </strong>Terbuat dari <strong>Art Paper </strong>, memberikan kesan elegan dan tahan lama.</li><li><strong>Isi: </strong>Dapat disesuaikan dengan pesanan, seperti jumlah halaman, pola garis, atau format lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi dengan logo atau grafik perusahaan.</li><li>Spiral binding yang kuat, memungkinkan buku tetap rapi dan mudah digunakan.</li><li>Kualitas kertas yang nyaman untuk menulis atau mencetak.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:21:44', '2025-06-27 13:34:23'),
(8, 'ec2d5037-0c3c-4dd6-9d98-a7efa7247d1f', 'simple', 'BUKU SOFTCOVER', 'https://viviashop.com/', NULL, NULL, 'buku-softcover', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4131018175, 'Kertas HVS 70-80 Gsm A4 / A5 / B5, \r\nFinishing Sampul Softcover  doff/glossy', '<p style=\"margin-left:0px;\"><strong>Produk: Buku Softcover</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Buku cetak dengan desain profesional dan kustomisasi.</li><li>Cocok untuk kebutuhan pendidikan, bisnis, atau publikasi.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Kertas: </strong>HVS (High Volume Superior) dengan ketebalan <strong>70-80 Gsm </strong>, memberikan kualitas cetak yang baik dan tahan lama.</li><li><strong>Ukuran: </strong>Tersedia dalam ukuran <strong>A4 </strong>, <strong>A5 </strong>, atau <strong>B5 </strong>, sesuai dengan kebutuhan pengguna.</li><li><strong>Finishing Sampul: </strong>Softcover dengan pilihan <strong>doff </strong>(matte) atau <strong>glossy </strong>(kilap), menambah kesan elegan pada buku.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi dengan logo atau grafik perusahaan.</li><li>Kualitas cetak yang rapi dan tajam, cocok untuk berbagai jenis konten seperti teks, gambar, atau grafik.</li><li>Finishing softcover yang ringan dan nyaman digunakan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:45:06', '2025-06-27 13:34:23'),
(9, 'c831ce28-669e-4fe7-a16c-5ac451c2850c', 'simple', 'AMPLOP', 'https://viviashop.com/', NULL, NULL, 'amplop', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4995324388, 'Amplop Paperline 80 gsm 23x11 cm dicetak dengan tinta pigment anti luntur, Amplop Coklat Folio dan Airmail', '<p style=\"margin-left:0px;\"><strong>Produk: Amplop</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Amplop cetak dengan desain profesional dan kustomisasi.</li><li>Cocok untuk kebutuhan bisnis, promosi, atau komunikasi formal.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Kertas: </strong>Paperline dengan ketebalan <strong>80 gsm </strong>, memberikan kualitas cetak yang baik dan tahan lama.</li><li><strong>Ukuran: 23 x 11 cm </strong>, ukuran standar yang umum digunakan untuk surat atau dokumen kecil.</li><li><strong>Finishing Cetak: </strong>Menggunakan <strong>tinta pigment anti luntur </strong>, menjaga warna tetap tajam meskipun terkena air atau gesekan.</li><li><strong>Jenis Amplop:</strong><ul><li><strong>Amplop Coklat Folio: </strong>Desain elegan dengan warna coklat klasik.</li><li><strong>Amplop Airmail: </strong>Desain sederhana dengan elemen grafis yang sesuai untuk pengiriman surat.</li></ul></li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi dengan logo atau informasi perusahaan.</li><li>Kualitas cetak yang rapi dan tajam, cocok untuk berbagai jenis konten seperti teks, gambar, atau grafik.</li><li>Tahan lama dan tahan air, memastikan pesan tetap utuh selama pengiriman.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:47:11', '2025-06-27 13:34:23'),
(10, '072f90d0-34c6-485a-bfd6-3cf715a7b3da', 'simple', 'MAP CUSTOM', 'https://viviashop.com/', NULL, NULL, 'map-custom', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1523973315, 'Map Ukuran Folio\r\nDidalam map terdapat penahan kertas', '<p style=\"margin-left:0px;\"><strong>Produk: Map Custom</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Map (folder dokumen) cetak dengan desain profesional dan kustomisasi.</li><li>Cocok untuk kebutuhan sekolah, bisnis, atau penyimpanan dokumen.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Kualitas Cetak: </strong>Menggunakan tinta berkualitas tinggi untuk hasil cetak yang tajam dan awet.</li><li><strong>Material: </strong>Terbuat dari bahan yang tahan lama dan kuat, cocok untuk melindungi dokumen di dalamnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Kapasitas penyimpanan yang cukup untuk menampung berbagai jenis dokumen.</li><li>Tahan lama dan tahan air, menjaga dokumen tetap aman selama penggunaan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:50:43', '2025-06-27 13:34:23'),
(11, 'd857313e-2374-4b90-9b9a-6b8c9e88e4cc', 'simple', 'FLYER / LEAFLET', 'https://viviashop.com/', NULL, NULL, 'flyer-leaflet', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5223267246, 'Kertas ArtPaper 120gsm,150gsm\r\n Cetak Bolak-Balik\r\n Lipat 2/3', '<p style=\"margin-left:0px;\"><strong>Produk: Flyer / Leaflet</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Brosur cetak (flyer/leaflet) dengan desain profesional dan kustomisasi.</li><li>Cocok untuk kebutuhan promosi, edukasi, atau komunikasi.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Kualitas Cetak: </strong>Menggunakan tinta berkualitas tinggi untuk hasil cetak yang tajam dan awet.</li><li><strong>Material: </strong>Terbuat dari bahan yang tahan lama dan kuat, cocok untuk melindungi dokumen di dalamnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Kapasitas penyimpanan yang cukup untuk menampung berbagai jenis dokumen.</li><li>Tahan lama dan tahan air, menjaga dokumen tetap aman selama penggunaan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:53:08', '2025-06-27 13:34:23'),
(12, '23192918-bed6-49d1-a640-c31fc779ac26', 'simple', 'TUMBLER CUSTOM A/B', 'https://viviashop.com/', NULL, NULL, 'tumbler-custom-a-b', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9416698306, 'Tumbler LED, Tumbler Biasa\r\n Branding Sablon UV', '<p style=\"margin-left:0px;\"><strong>Produk: Tumbler Custom A/B</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Botol tumbler dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Jenis Tumbler:</strong><ul><li><strong>Tumbler LED: </strong>Dilengkapi dengan lampu LED yang dapat menyala, menambah nilai estetika dan fungsionalitas.</li><li><strong>Tumbler Biasa: </strong>Desain sederhana tanpa fitur tambahan.</li></ul></li><li><strong>Metode Sablon: </strong>Menggunakan teknik <strong>Sablon UV </strong>, yang memberikan hasil cetak yang tahan lama, tajam, dan tidak mudah luntur.</li><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti stainless steel atau plastik food-grade, aman untuk digunakan sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li>Kualitas cetak yang awet dan tahan lama, cocok untuk penggunaan jangka panjang.</li><li>Pilihan variasi desain (LED vs. biasa) sesuai kebutuhan pelanggan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:56:29', '2025-06-27 13:34:23'),
(13, '8ab481bc-6b19-4946-b678-c2b2c2432eec', 'simple', 'PAYUNG CUSTOM', 'https://viviashop.com/', NULL, NULL, 'payung-custom', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7576881051, 'Payung Lipat, Payung Biasa\r\n Branding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Payung Custom</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Payung dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti poliester atau PVC, yang tahan air dan awet.</li><li><strong>Kualitas Cetak: </strong>Menggunakan teknik cetak profesional untuk hasil yang tajam dan awet.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan tahan air, menjaga payung tetap aman saat digunakan di cuaca ekstrem.</li><li>Fungsionalitas praktis sebagai alat pelindung dari hujan atau sinar matahari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 11:59:25', '2025-06-27 13:34:23'),
(14, '48abf2bf-992d-4414-83f9-880b8e1bc961', 'simple', 'MUG CUSTOM', 'https://viviashop.com/', NULL, NULL, 'mug-custom', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 6636731786, 'Gelas \r\nBranding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Mug Custom</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Mug (cangkir) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti keramik atau plastik food-grade, aman untuk digunakan sehari-hari.</li><li><strong>Kualitas Cetak: </strong>Menggunakan teknik cetak profesional untuk hasil yang tajam dan awet.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Kualitas cetak yang awet dan tahan lama, cocok untuk penggunaan jangka panjang.</li><li>Fungsionalitas praktis sebagai cangkir minuman sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:01:59', '2025-06-27 13:34:23'),
(15, 'ab261cab-f335-474f-bf29-d4d454b2a900', 'simple', 'TOPI CUSTOM', 'https://viviashop.com/', NULL, NULL, 'topi-custom', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 6409412248, 'Topi Kain \r\nBranding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Topi Custom</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Topi dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau acara.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti kanvas atau poliester, yang tahan lama dan nyaman dipakai.</li><li><strong>Kualitas Cetak: </strong>Menggunakan teknik cetak profesional untuk hasil yang tajam dan awet.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai aksesori pelindung kepala dari sinar matahari atau cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:04:59', '2025-06-27 13:34:23'),
(16, '56cfaebd-d17f-4ad4-98ce-d65d00c5a26a', 'simple', 'JAM DINDING CUSTOM', 'https://viviashop.com/', NULL, NULL, 'jam-dinding-custom', '15000.00', NULL, 0, 0, '0.00', 0, 0, '9000.00', '1.00', '1.00', '1.00', NULL, 2803910768, 'Jam Dinding\r\n Branding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Jam Dinding Custom</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Jam dinding dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau dekorasi.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti kayu, plastik, atau metal, yang tahan lama dan awet.</li><li><strong>Kualitas Cetak: </strong>Menggunakan teknik cetak profesional untuk hasil yang tajam dan awet.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan tampilan elegan, cocok untuk berbagai ruangan.</li><li>Fungsionalitas praktis sebagai alat pengukur waktu sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:06:58', '2025-09-09 06:45:24'),
(17, '28f5ed9f-bbc8-4e5c-93bf-a392872a7646', 'simple', 'PIN GANCI CUSTOM', 'https://viviashop.com/', NULL, NULL, 'pin-ganci-custom', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4381212727, 'Pin Plastik\r\nBranding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Pin Ganci Custom</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pin ganci (pin dengan jepit) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Desain Cover: </strong>Dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti logam atau plastik, yang tahan lama dan awet.</li><li><strong>Kualitas Cetak: </strong>Menggunakan teknik cetak profesional untuk hasil yang tajam dan awet.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman dipakai di berbagai jenis pakaian.</li><li>Fungsionalitas praktis sebagai aksesori pelengkap penampilan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:09:21', '2025-06-27 13:34:23'),
(18, '5d29c91e-ba56-48dd-8fcc-ac621e8ec84f', 'simple', 'KALENDER DINDING', NULL, NULL, NULL, 'kalender-dinding', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9039509184, 'Kertas AP A3 120 - 230 Gsm\r\nIsi 6 - 13 Lembar tergantung bulan\r\nJilid Spiral Kawat / Plat', '<p style=\"margin-left:0px;\"><strong>Produk: Kalender Dinding</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Kalender dinding dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau dekorasi.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Ukuran: </strong>A3 (120 x 230 mm).</li><li><strong>Kertas: </strong>AP (Art Paper) dengan ketebalan <strong>120 - 230 Gsm </strong>, memberikan hasil cetak yang tajam dan berkualitas tinggi.</li><li><strong>Isi Halaman: </strong>Tersedia dalam variasi isi halaman, mulai dari <strong>6 hingga 13 lembar </strong>, tergantung pada jumlah bulan yang diinginkan.</li><li><strong>Jilid: </strong>Dilengkapi dengan jilid spiral kawat atau plat, menjaga kalender tetap rapi dan mudah digunakan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Kualitas cetak yang awet dan tahan lama, cocok untuk penggunaan jangka panjang.</li><li>Fungsionalitas praktis sebagai alat pelacak waktu sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:13:14', '2025-06-27 13:34:23'),
(19, '7af448e4-398d-479c-a393-d09ca4a7923e', 'simple', 'KALENDER DUDUK', 'https://viviashop.com/', NULL, NULL, 'kalender-duduk', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5589739119, 'Kertas Ukuran A5 210 - 230 Gsm\r\n Isi 7 - 13 Lembar tergantung bulan\r\n Jilid  Spiral Kawat\r\n Stand Hardcover', '<p style=\"margin-left:0px;\"><strong>Produk: Kalender Meja</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Kalender meja dengan desain menarik dan informatif.</li><li>Cocok untuk kebutuhan sehari-hari, promosi, atau merchandise.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Cover: </strong>Menampilkan gambar lanskap alam yang estetis (misalnya, pemandangan gunung).</li><li><strong>Tampilan Bulanan: </strong>Menunjukkan tanggal-tanggal dalam satu bulan (contohnya, Januari 2024) dengan jelas.</li><li><strong>Praktis: </strong>Dapat digunakan di meja kerja, kantor, atau rumah untuk melacak hari, minggu, dan acara penting.</li><li><strong>Kompak: </strong>Bentuknya ringkas dan mudah dipindahkan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Material: </strong>Terbuat dari bahan berkualitas tinggi, seperti kertas tebal atau plastik transparan, untuk tampilan yang awet dan profesional.</li><li><strong>Binding: </strong>Dilengkapi dengan spiral binding yang kuat, memastikan kalender tetap rapi selama penggunaan.</li><li><strong>Fungsi: </strong>Selain sebagai alat pelacak waktu, kalender ini juga dapat menjadi aksesori dekoratif yang menambah kesan elegan pada ruang kerja atau ruang tamu.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk perusahaan, sekolah, atau individu yang ingin memiliki alat pelacak waktu yang praktis dan menarik.</li><li>Bisa digunakan sebagai hadiah atau merchandise promosi.</li></ul></li><li><strong>Kontak:</strong><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:15:03', '2025-06-27 13:34:23'),
(20, '55120c25-ffad-4ccc-bc2c-aea7f1b194bc', 'simple', 'PIAGAM', 'https://viviashop.com/', NULL, NULL, 'piagam', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2697051538, 'Bahan Akrilik. Model bebas request\r\n sesuai kebutuhan', '<p style=\"margin-left:0px;\"><strong>Produk: Piagam</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Piagam dengan desain kustom dan elegan.</li><li>Cocok untuk kebutuhan penghargaan, sertifikat, atau merchandise perusahaan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>akrilik </strong>, yang memberikan tampilan transparan dan profesional.</li><li><strong>Model: </strong>Desain bebas, dapat disesuaikan sesuai kebutuhan pelanggan (request model tertentu).</li><li><strong>Kualitas: </strong>Tampilan yang rapi dan awet, cocok untuk acara formal seperti penghargaan, seminar, atau event.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan.</li><li>Kualitas cetak yang tajam dan awet, menjaga piagam tetap terlihat elegan selama bertahun-tahun.</li><li>Fungsionalitas praktis sebagai alat penghargaan atau dokumentasi resmi.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk acara-acara formal seperti penghargaan, lomba, seminar, atau event perusahaan.</li><li>Bisa digunakan sebagai hadiah atau merchandise promosi.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:19:51', '2025-06-27 13:34:23'),
(21, '7215f653-8c22-44f5-90e3-3ca649d4cff0', 'simple', 'TOTEBAG KANVAS', 'https://viviashop.com/', NULL, NULL, 'totebag-kanvas', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8709361905, 'Totebag bahan kanvas\r\n Tali dan finishing velcro \r\nUkuran 32 x 38 cm\r\n Branding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Totebag Kanvas</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Totebag (tas selempang) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>kanvas </strong>, yang tahan lama dan memberikan kesan elegan serta ramah lingkungan.</li><li><strong>Tali dan Finishing: </strong>Dilengkapi dengan tali selempang yang kuat dan finishing <strong>velcro </strong>untuk penggunaan praktis.</li><li><strong>Ukuran: 32 x 38 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:21:53', '2025-06-27 13:34:23'),
(22, '9615c899-0879-4ba0-8c7a-c0a544f8bcc4', 'simple', 'TOTEBAG BLACU 1', 'https://viviashop.com/', NULL, NULL, 'totebag-blacu-1', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2974174077, 'Totebag bahan Blacu Broken White \r\nTali dan finishing velcro\r\nUkuran 32 x 38 cm\r\nBranding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Totebag Blacu 1</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Totebag (tas selempang) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Blacu Broken White </strong>, yaitu bahan kanvas putih dengan tekstur yang memberikan kesan elegan dan ramah lingkungan.</li><li><strong>Tali dan Finishing: </strong>Dilengkapi dengan tali selempang yang kuat dan finishing <strong>velcro </strong>untuk penggunaan praktis.</li><li><strong>Ukuran: 32 x 38 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:23:40', '2025-06-27 13:34:23'),
(23, '22c1eab8-acbf-454e-9cb2-ddceb47b08d9', 'simple', 'TOTEBAG BLACU 2', NULL, NULL, NULL, 'totebag-blacu-2', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1483048454, 'Totebag bahan Blacu Broken White\r\n Batik 1sisi di bagian bawah\r\n Tali dan finishing Reseleting\r\n Ukuran 32 x 38 cm\r\n Branding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Totebag Blacu 2</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Totebag (tas selempang) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Blacu Broken White </strong>, yaitu bahan kanvas putih dengan tekstur yang memberikan kesan elegan dan ramah lingkungan.</li><li><strong>Batik di Bagian Bawah: </strong>Dilengkapi dengan motif batik pada satu sisi bagian bawah tas, menambah nilai estetika dan keunikan.</li><li><strong>Tali dan Finishing: </strong>Dilengkapi dengan tali selempang yang kuat dan finishing <strong>reseleting </strong>(zipper), membuatnya lebih praktis dan aman.</li><li><strong>Ukuran: 32 x 38 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari.</li><li>Motif batik pada bagian bawah menambah elemen tradisional dan keunikannya.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:26:04', '2025-06-27 13:34:23'),
(24, 'c08eef4e-0123-4f9e-b5e4-bf51cd01b75b', 'simple', 'TOTEBAG DENIM', 'https://viviashop.com/', NULL, NULL, 'totebag-denim', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2515587214, 'Totebag bahan denim dengan lapisan dalam\r\n Finishing reseleting\r\n Ukuran 32 x 38 cm\r\n Branding Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Totebag Denim</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Totebag (tas selempang) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>denim </strong>, memberikan kesan kasual, tahan lama, dan elegan.</li><li><strong>Lapisan Dalam: </strong>Dilengkapi dengan lapisan dalam yang menambah ketebalan dan daya tahan tas.</li><li><strong>Finishing Resleting: </strong>Tas dilengkapi dengan resleting (zipper) untuk penggunaan praktis dan aman.</li><li><strong>Ukuran: 32 x 38 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari.</li><li>Bahan denim memberikan kesan kasual dan modern.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:29:15', '2025-06-27 13:34:23'),
(25, 'db0eb011-37dc-48dc-afc8-daf13b419963', 'simple', 'TAS BRAND 1', 'https://viviashop.com/', NULL, NULL, 'tas-brand-1', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4328252816, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Biru & Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 1</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Biru &amp; Black </strong>(hitam), memberikan tampilan modern dan elegan.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:30:34', '2025-06-27 13:34:23'),
(26, 'a60f79cf-4308-4840-ba98-7db1d72dd1bd', 'simple', 'TAS BRAND 2', 'https://viviashop.com/', NULL, NULL, 'tas-brand-2', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8401674578, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas Denim\r\n : 31 x 13 x 46 cm\r\n : Navy & Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 2</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas Denim </strong>, yang memberikan kesan kasual, tahan lama, dan elegan.</li><li><strong>Dimensi: 31 x 13 x 46 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Navy &amp; Black </strong>(hitam), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas denim memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:32:27', '2025-06-27 13:34:23'),
(27, 'b896fc73-adf9-4416-a742-015a020c28d8', 'simple', 'TAS BRAND 3', 'https://viviashop.com/', NULL, NULL, 'tas-brand-3', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9334389998, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Blue, Green & Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 3</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Blue, Green &amp; Black </strong>(biru, hijau, dan hitam), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:34:18', '2025-06-27 13:34:23'),
(28, 'c0891399-9c4b-4a83-bf86-64b84733674c', 'simple', 'TAS BRAND 4', NULL, NULL, NULL, 'tas-brand-4', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9769243139, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Navy & Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 4</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Navy &amp; Black </strong>(hitam), memberikan tampilan modern dan elegan.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:36:11', '2025-06-27 13:34:23');
INSERT INTO `products` (`id`, `sku`, `type`, `name`, `link1`, `link2`, `link3`, `slug`, `price`, `base_price`, `total_stock`, `sold_count`, `rating`, `is_featured`, `is_print_service`, `harga_beli`, `weight`, `length`, `width`, `height`, `barcode`, `short_description`, `description`, `status`, `is_smart_print_enabled`, `user_id`, `brand_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(29, 'e12b5d6b-fcb5-496a-8ce2-283c9eb4489f', 'simple', 'TAS BRAND 5', 'https://viviashop.com/', NULL, NULL, 'tas-brand-5', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 6539185470, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas Denim\r\n : 30 x 11 x 41 cm\r\n : Red, Brown, \r\nGray, Blue\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 5</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas selempang (shoulder bag) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas Denim </strong>, yang memberikan kesan kasual, tahan lama, dan elegan.</li><li><strong>Dimensi: 30 x 11 x 41 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti dokumen, laptop, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Red, Brown, Gray, Blue </strong>(merah, cokelat, abu-abu, biru), memberikan pilihan estetika yang beragam.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas denim memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:38:43', '2025-06-27 13:34:23'),
(30, '4a1bbb6a-cefd-4921-9b10-18f49c03b63b', 'simple', 'TAS BRAND 6', 'https://viviashop.com/', NULL, NULL, 'tas-brand-6', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1724525132, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Green + Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 6</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Green + Black </strong>(hijau dan hitam), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:41:06', '2025-06-27 13:34:23'),
(31, '79077173-1652-4a03-9880-36548b8c2104', 'simple', 'TAS BRAND 7', 'https://viviashop.com/', NULL, NULL, 'tas-brand-7', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5615230903, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas Denim\r\n : 31 x 13 x 43 cm\r\n : Brown\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 7</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas Denim </strong>, yang tahan lama, kuat, dan memberikan kesan kasual serta elegan.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam warna <strong>Brown </strong>(cokelat), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas denim memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:42:46', '2025-06-27 13:34:23'),
(32, '457e18a2-9bb1-42c8-b8ae-c3e6b8d3fefc', 'simple', 'TAS BRAND 8', 'https://viviashop.com/', NULL, NULL, 'tas-brand-8', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5985230956, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas Denim\r\n : 31 x 13 x 43 cm\r\n : Navy\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 8</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas Denim </strong>, yang tahan lama, kuat, dan memberikan kesan kasual serta elegan.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam warna <strong>Navy </strong>(biru tua), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas denim memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:44:57', '2025-06-27 13:34:23'),
(33, '4605fd96-9876-43bb-93df-62fe3b491e03', 'simple', 'TAS BRAND 9', 'https://viviashop.com/', NULL, NULL, 'tas-brand-9', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9772274097, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 9</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam warna <strong>Black </strong>(hitam), memberikan tampilan elegan dan modern.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:46:31', '2025-06-27 13:34:23'),
(34, '365fb24a-14a1-4a8f-a155-6ab6444cc6a5', 'simple', 'TAS BRAND 10', 'https://viviashop.com/', NULL, NULL, 'tas-brand-10', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7761185791, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 10</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam warna <strong>Black </strong>(hitam), memberikan tampilan elegan dan modern.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:48:49', '2025-06-27 13:34:23'),
(35, '8715db33-6cbe-4b40-80c9-86717b7d6022', 'simple', 'TAS BRAND 11', 'https://viviashop.com/', NULL, NULL, 'tas-brand-11', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2045205050, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 31 x 13 x 43 cm\r\n : Green + Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Tas Brand 11</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas ransel (backpack) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 31 x 13 x 43 cm </strong>, cukup besar untuk menampung barang-barang sehari-hari seperti laptop, buku, atau perlengkapan lainnya.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Green + Black </strong>(hijau dan hitam), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas ransel yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li><li><i>( </i>) Catatan: Untuk desain lain, pelanggan dapat mengajukan permintaan khusus.*</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:50:41', '2025-06-27 13:34:23'),
(36, 'b449976a-bad4-438b-8b84-f3487d40d7c6', 'simple', 'SLINGBAG 1', 'https://viviashop.com/', NULL, NULL, 'slingbag-1', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8365695822, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 22 x 6 x 25 cm\r\n : Gray + Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Sling Bag 1</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas selempang (sling bag) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 22 x 6 x 25 cm </strong>, ukuran kompak yang nyaman dibawa sehari-hari.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Gray + Black </strong>(abu-abu dan hitam), memberikan tampilan modern dan elegan.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:52:11', '2025-06-27 13:34:23'),
(37, '6f288fe5-7a7e-46fc-b94e-3f61e1abc154', 'simple', 'SLINGBAG 2', 'https://viviashop.com/', NULL, NULL, 'slingbag-2', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9563018763, 'Bahan\r\n Dimensi\r\n Warna\r\n Branding\r\n : Kanvas D300\r\n : 27 x 7 x 17 cm\r\n : Green + Black\r\n : Sablon', '<p style=\"margin-left:0px;\"><strong>Produk: Sling Bag 2</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Tas selempang (sling bag) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>Kanvas D300 </strong>, yang tahan lama, kuat, dan memberikan kesan profesional.</li><li><strong>Dimensi: 27 x 7 x 17 cm </strong>, ukuran kompak yang nyaman dibawa sehari-hari.</li><li><strong>Warna: </strong>Tersedia dalam kombinasi warna <strong>Green + Black </strong>(hijau dan hitam), memberikan tampilan modern dan stylish.</li><li><strong>Branding Sablon: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai tas selempang yang cocok untuk aktivitas sehari-hari, termasuk bekerja, sekolah, atau traveling.</li><li>Bahan kanvas D300 memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:54:55', '2025-06-27 13:34:23'),
(38, '7821d149-0c83-485a-901d-9d54ad8ad86c', 'simple', 'POUCH A', 'https://viviashop.com/', NULL, NULL, 'pouch-a', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1410336804, 'Pouch bahan kulit sintetis cleo/cesar dengan tutup kotak bermagnet, \r\nLapisan dalam berbahan drill, 1 kompartemen utama bereseleting dengan\r\n 3 kantung bagian dalam 1 reseleting dan 2 kantung, dan tali kulit di bagian\r\n samping dan Free Branding', '<p style=\"margin-left:0px;\"><strong>Produk: Pouch A</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Dompet (pouch) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>kulit sintetis cleo/cesar </strong>, memberikan kesan mewah dan tahan lama.</li><li><strong>Tutup Kotak Bermagnet: </strong>Dilengkapi dengan tutup magnetik yang praktis dan aman.</li><li><strong>Lapisan Dalam: </strong>Berbahan <strong>drill </strong>, yang tahan lama dan nyaman.</li><li><strong>Kompartment Utama: </strong>Dilengkapi dengan resleting, memungkinkan penyimpanan yang lebih aman.</li><li><strong>Kantong Tambahan: </strong>Termasuk <strong>3 kompartemen utama berreseleting </strong>dan <strong>2 kantong di samping </strong>, menawarkan ruang penyimpanan yang fleksibel.</li><li><strong>Tali Kulit Samping: </strong>Memperkuat desain dan meningkatkan daya tarik estetika.</li><li><strong>Free Branding: </strong>Desain cover dapat dikustomisasi dengan logo, gambar, atau informasi perusahaan menggunakan teknik sablon profesional.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat disesuaikan sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai dompet yang cocok untuk menyimpan kartu identitas, uang, dan barang-barang kecil lainnya.</li><li>Bahan kulit sintetis memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:57:00', '2025-06-27 13:34:24'),
(39, '02457f0f-4591-43ef-9aa9-fab29457ba28', 'simple', 'POUCH B', 'https://viviashop.com/', NULL, NULL, 'pouch-b', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3695682449, 'Pouch bahan kulit sintetis dengan\r\n 2 kompartemen utama, \r\n1 kompartemen luar, \r\n3 kompartemen kecil bagian dalam\r\n dan tutup magnet.', '<p style=\"margin-left:0px;\"><strong>Produk: Pouch B</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Dompet (pouch) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>kulit sintetis </strong>, memberikan kesan mewah dan tahan lama.</li><li><strong>Kompartment Utama: </strong>Dilengkapi dengan <strong>2 kompartemen utama </strong>, memungkinkan penyimpanan yang lebih terorganisir.</li><li><strong>Kompartment Luar: </strong>Ada <strong>1 kompartemen luar </strong>, menambah ruang penyimpanan tambahan.</li><li><strong>Kompartment Kecil Dalam: </strong>Dilengkapi dengan <strong>3 kompartemen kecil bagian dalam </strong>, ideal untuk menyimpan barang-barang kecil seperti kartu identitas, uang, atau pulpen.</li><li><strong>Tutup Magnet: </strong>Memiliki tutup magnetik yang praktis dan aman.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai dompet yang cocok untuk menyimpan kartu identitas, uang, dan barang-barang kecil lainnya.</li><li>Bahan kulit sintetis memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 12:58:51', '2025-06-27 13:34:24'),
(40, 'ceaee3a6-befe-4bfe-8d92-8d45b16209ac', 'simple', 'POUCH C', 'https://viviashop.com/', NULL, NULL, 'pouch-c', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 6099542122, 'Pouch Kanvas dengan kombinasi kulit\r\n sintetis. Memiliki 2 kompartment\r\n berresleting dengan tambahan handle\r\n kulit sintetis sehingga mudah digenggam.', '<p style=\"margin-left:0px;\"><strong>Produk: Pouch C</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Dompet (pouch) dengan desain kustom dan branding.</li><li>Cocok untuk kebutuhan promosi, merchandise perusahaan, atau hadiah.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Spesifikasi Teknis:</strong></p><ul><li><strong>Bahan: </strong>Terbuat dari <strong>kanvas </strong>dengan kombinasi <strong>kulit sintetis </strong>, memberikan kesan modern dan tahan lama.</li><li><strong>Kompartment: </strong>Dilengkapi dengan <strong>2 kompartemen berreseleting </strong>, memungkinkan penyimpanan yang lebih terorganisir dan aman.</li><li><strong>Handle Kulit Sintetis: </strong>Memiliki tambahan <strong>handle kulit sintetis </strong>, membuat dompet lebih mudah digenggam dan nyaman dibawa.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li>Desain cover yang dapat dikustomisasi sesuai kebutuhan, seperti branding perusahaan atau tema tertentu.</li><li>Tahan lama dan nyaman digunakan dalam berbagai situasi.</li><li>Fungsionalitas praktis sebagai dompet yang cocok untuk menyimpan kartu identitas, uang, dan barang-barang kecil lainnya.</li><li>Bahan kanvas dan kulit sintetis memberikan ketahanan tinggi serta daya tahan terhadap gesekan dan cuaca ekstrem.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:00:25', '2025-06-27 13:34:24'),
(41, '28ca0946-de1f-4f47-b3c8-b31aaa1b089c', 'simple', 'ATK PAKET A', 'https://viviashop.com/', NULL, NULL, 'atk-paket-a', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8422389056, 'Terdiri dari :\r\n 1 Buku Notes Paperline\r\n 1 Ballpoint Snowman V5\r\n 1 Pensil Mekanik Joyko\r\n 1 Isi Pensil Joyko \r\n1 Penghapus Joyko\r\n 1 Nametag B3 + Tali BIG', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Survey ATK (Alat Tulis Kantor) - Paket A</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket alat tulis kantor yang dirancang untuk kebutuhan survey atau aktivitas administratif.</li><li>Cocok untuk distribusi dalam acara promosi, pelatihan, atau kegiatan serupa.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Buku Notes Paperline (A5): </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting.</li><li><strong>Ballpoint Snowman V5: </strong>Pulpen berdesain unik dan nyaman digunakan.</li><li><strong>Pensil Mekanik Joyko: </strong>Pensil mekanik berkualitas tinggi untuk pekerjaan detail.</li><li><strong>Isi Pensil Joyko: </strong>Refill pensil mekanik untuk penggunaan jangka panjang.</li><li><strong>Penghapus Joyko: </strong>Penghapus berkualitas untuk mengoreksi kesalahan.</li><li><strong>Nametag B3 + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Tambahan:</strong></p><ul><li><strong>Bundling Fleksibel: </strong>Paket ini dapat dikombinasikan dengan tas, ransel (backpack), atau sling bag sesuai kebutuhan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:02:36', '2025-06-27 13:34:24'),
(42, '39e484b4-aa39-4d86-8759-675f304bd500', 'simple', 'ATK PAKET B', 'https://viviashop.com/', NULL, NULL, 'atk-paket-b', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7316904000, 'Terdiri dari :\r\n 1 Buku Notes Paperline\r\n 1 Ballpoint Snowman V5\r\n 2 Pensil Staedler\r\n 1 Rautan Greebel \r\n1 Penghapus Joyko\r\n 1 Nametag B3 + Tali BIG', '<p style=\"margin-left:0px;\"><strong>Produk: Paket B</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket alat tulis kantor (ATK) yang dirancang untuk kebutuhan survey atau aktivitas administratif.</li><li>Cocok untuk distribusi dalam acara promosi, pelatihan, atau kegiatan serupa.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Buku Notes Paperline: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting.</li><li><strong>Ballpoint Snowman V5: </strong>Pulpen berdesain unik dan nyaman digunakan.</li><li><strong>2 Pensil Staedler: </strong>Dua pensil berkualitas tinggi untuk pekerjaan detail.</li><li><strong>Rautan Grebeel: </strong>Rautan tajam untuk mengasah pensil.</li><li><strong>Penghapus Joyko: </strong>Penghapus berkualitas untuk mengoreksi kesalahan.</li><li><strong>Nametag B3 + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Alat Tulis Lengkap: </strong>Paket ini menyediakan semua alat tulis dasar yang diperlukan untuk kegiatan survey atau administratif.</li><li><strong>Kualitas Berkualitas: </strong>Setiap item dalam paket dipilih dengan bahan berkualitas tinggi untuk penggunaan jangka panjang.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:04:21', '2025-06-27 13:34:24'),
(43, '008c80fb-59fe-4cb3-ad23-7e16b9900fb3', 'simple', 'ATK  PAKET C', 'https://viviashop.com/', NULL, NULL, 'atk-paket-c', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4335105822, 'Terdiri dari :\r\n 1 Buku Notes Paperline\r\n 1 Ballpoint Standar JR6\r\n 2 Pensil Greebel\r\n 1 Rautan Greebel \r\n1 Penghapus BIG\r\n 1 Nametag B3 + Tali BIG', '<p style=\"margin-left:0px;\"><strong>Produk: Paket C</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket alat tulis kantor (ATK) yang dirancang untuk kebutuhan survey atau aktivitas administratif.</li><li>Cocok untuk distribusi dalam acara promosi, pelatihan, atau kegiatan serupa.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Buku Notes Paperline: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting.</li><li><strong>1 Ballpoint Standar JR6: </strong>Pulpen standar dengan desain sederhana dan nyaman digunakan.</li><li><strong>2 Pensil Greebel: </strong>Dua pensil berkualitas tinggi untuk pekerjaan detail.</li><li><strong>1 Rautan Greebel: </strong>Rautan tajam untuk mengasah pensil.</li><li><strong>1 Penghapus BIG: </strong>Penghapus besar untuk penggunaan lebih efektif dalam mengoreksi kesalahan.</li><li><strong>1 Nametag B3 + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Alat Tulis Lengkap: </strong>Paket ini menyediakan semua alat tulis dasar yang diperlukan untuk kegiatan survey atau administratif.</li><li><strong>Kualitas Berkualitas: </strong>Setiap item dalam paket dipilih dengan bahan berkualitas tinggi untuk penggunaan jangka panjang.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:06:08', '2025-06-27 13:34:24'),
(44, '8ae4ba74-ced0-4122-924b-85812088fdb1', 'simple', 'ATK PAKET D', 'https://viviashop.com/', NULL, NULL, 'atk-paket-d', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4724747179, 'Terdiri dari :\r\n 1 Map Jaring Resleting\r\n 1 Buku Notes Paperline\r\n 1 Ballpoint Snowman V5\r\n 1 Pensil Mekanik Joyko\r\n 1 Isi Pensil Joyko \r\n1 Penghapus Joyko\r\n 1 Nametag B3 + Tali BIG\r\n *) Bisa Costum bundling dengan ATK/ Barang lain', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Survey ATK - Paket D</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket alat tulis kantor (ATK) yang dirancang untuk kebutuhan survey atau aktivitas administratif.</li><li>Cocok untuk distribusi dalam acara promosi, pelatihan, atau kegiatan serupa.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Map Jaring Resleting: </strong>Map jaring dengan resleting praktis untuk menyimpan dokumen dan alat tulis.</li><li><strong>Buku Notes Paperline: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting.</li><li><strong>Ballpoint Snowman V5: </strong>Pulpen berdesain unik dan nyaman digunakan.</li><li><strong>Pensil Mekanik Joyko: </strong>Pensil mekanik berkualitas tinggi untuk pekerjaan detail.</li><li><strong>Isi Pensil Joyko: </strong>Refill pensil mekanik untuk penggunaan jangka panjang.</li><li><strong>Penghapus Joyko: </strong>Penghapus berkualitas untuk mengoreksi kesalahan.</li><li><strong>Nametag B3 + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Tambahan:</strong></p><ul><li><strong>Custom Bundling Fleksibel: </strong>Paket ini dapat disesuaikan dengan menambahkan ATK atau barang lain sesuai kebutuhan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:07:29', '2025-06-27 13:34:24'),
(45, '78cbcd2e-59f1-4a95-aeba-ddac4a71d167', 'simple', 'SEMINAR KIT PAKET A', 'https://viviashop.com/', NULL, NULL, 'seminar-kit-paket-a', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1032778232, 'Terdiri dari :\r\n 1 Tas Pouch\r\n 1 Buku Agenda Hardcover\r\n 1 Ballpoint Gel\r\n 1 Kardus Packing', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Seminar Kit - Paket A</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket seminar kit yang dirancang untuk kebutuhan acara seminar, pelatihan, atau kegiatan serupa.</li><li>Cocok untuk distribusi merchandise kepada peserta sebagai hadiah atau alat pendukung.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Tas Pouch: </strong>Tas selempang (pouch) praktis untuk menyimpan barang-barang penting.</li><li><strong>Buku Agenda Hardcover: </strong>Buku agenda dengan cover keras untuk mencatat jadwal dan catatan penting.</li><li><strong>Ballpoint Gel: </strong>Pulpen gel berkualitas tinggi untuk menulis yang nyaman dan tahan lama.</li><li><strong>Kardus Packing: </strong>Kemasan kardus elegan untuk penyajian produk yang rapi dan profesional.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Praktis: </strong>Semua item dalam paket dipilih untuk memenuhi kebutuhan peserta seminar secara efektif.</li><li><strong>Kualitas Berkualitas: </strong>Setiap komponen paket dibuat dari bahan berkualitas tinggi untuk penggunaan jangka panjang.</li><li><strong>Best Seller: </strong>Terkenal karena popularitasnya di kalangan pelanggan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:09:38', '2025-06-27 13:34:24'),
(46, '2d5743ba-71de-4f28-8d63-c70374fcc315', 'simple', 'SEMINAR KIT PAKET B', 'https://viviashop.com/', NULL, NULL, 'seminar-kit-paket-b', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4571489925, '1 Tas Selempang\r\n 1 Buku Notes Paperline\r\n 1 Ballpoint Gel\r\n 1 Nametag B3 + Tali BIG', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Seminar Kit - Paket B</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket seminar kit yang dirancang untuk kebutuhan acara seminar, pelatihan, atau kegiatan serupa.</li><li>Cocok untuk distribusi merchandise kepada peserta sebagai hadiah atau alat pendukung.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Tas Selempang: </strong>Tas selempang (sling bag) praktis untuk membawa barang-barang penting.</li><li><strong>Buku Notes Paperline: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting.</li><li><strong>Ballpoint Gel: </strong>Pulpen gel berkualitas tinggi untuk menulis yang nyaman dan tahan lama.</li><li><strong>Nametag B3 + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Praktis: </strong>Semua item dalam paket dipilih untuk memenuhi kebutuhan peserta seminar secara efektif.</li><li><strong>Kualitas Berkualitas: </strong>Setiap komponen paket dibuat dari bahan berkualitas tinggi untuk penggunaan jangka panjang.</li><li><strong>Fleksibilitas: </strong>Tas selempang memberikan kenyamanan tambahan saat bergerak di acara.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:11:35', '2025-06-27 13:34:24'),
(47, '5b28e84b-dd36-4a98-bad6-342851b62b14', 'simple', 'SEMINAR KIT PAKET C', 'https://viviashop.com/', NULL, NULL, 'seminar-kit-paket-c', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3533167954, 'Terdiri dari :\r\n 1 Tas Selempang\r\n 1 Buku Notes Paperline\r\n 1 Ballpoint Gel\r\n 1 Nametag B3 + Tali BIG\r\n *) Bisa Costum bundling dengan ATK/ Barang lain', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Seminar Kit - Paket C</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket seminar kit yang dirancang untuk kebutuhan acara seminar, pelatihan, atau kegiatan serupa.</li><li>Cocok untuk distribusi merchandise kepada peserta sebagai hadiah atau alat pendukung.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Tas Selempang: </strong>Tas selempang (sling bag) praktis untuk membawa barang-barang penting.</li><li><strong>Buku Notes Paperline: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting.</li><li><strong>Ballpoint Gel: </strong>Pulpen gel berkualitas tinggi untuk menulis yang nyaman dan tahan lama.</li><li><strong>Nametag B3 + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Tambahan:</strong></p><ul><li><strong>Custom Bundling Fleksibel: </strong>Paket ini dapat dikombinasikan dengan ATK (Alat Tulis Kantor) atau barang lain sesuai kebutuhan.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:13:21', '2025-06-27 13:34:24'),
(48, '6e9c0826-e565-486b-99fa-a043ecfd9b9a', 'simple', 'SURVEY KIT PAKET TAS A', 'https://viviashop.com/', NULL, NULL, 'survey-kit-paket-tas-a', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2710207617, 'Terdiri dari :\r\n 1 Tas Backpack Canvas\r\n 1 Buku Blocknote Paperline 50\r\n 1 Ballpoint Standard AE 7\r\n 2 Pensil Greebel 2B\r\n 1 Penghapus BIG\r\n 1 Rautan Greebel\r\n 1 Name tag + Tali BIG', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Survey Kit - Paket Tas A</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket tas survey yang dirancang untuk kebutuhan acara survei, pelatihan lapangan, atau kegiatan serupa.</li><li>Cocok untuk distribusi merchandise kepada peserta sebagai alat pendukung.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Tas Backpack Canvas: </strong>Tas ransel (backpack) berbahan kanvas kuat dan tahan lama, cocok untuk membawa barang-barang penting.</li><li><strong>Buku Blocknote Paperline 50: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting selama survei.</li><li><strong>Ballpoint Standard AE 7: </strong>Pulpen standar berkualitas tinggi untuk menulis yang nyaman.</li><li><strong>2 Pensil Greebel 2B: </strong>Dua pensil 2B berkualitas tinggi untuk pekerjaan detail.</li><li><strong>Penghapus BIG: </strong>Penghapus besar untuk mengoreksi kesalahan secara efektif.</li><li><strong>Rautan Greebel: </strong>Rautan tajam untuk mengasah pensil.</li><li><strong>Name tag + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Praktis: </strong>Semua item dalam paket dipilih untuk memenuhi kebutuhan peserta survei secara efektif.</li><li><strong>Kualitas Berkualitas: </strong>Setiap komponen paket dibuat dari bahan berkualitas tinggi untuk penggunaan jangka panjang.</li><li><strong>Fleksibilitas: </strong>Tas backpack memberikan ruang penyimpanan yang cukup untuk membawa semua perlengkapan yang diperlukan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kontak:</strong></p><ul><li>Nomor Telepon: <strong>082145840999</strong></li><li>Email: <a href=\"mailto:sinaragung5758@gmail.com\"><strong><u>sinaragung5758@gmail.com</u></strong></a></li><li>Alamat: <strong>Tebulreng IV No. 38, Cukir, Jombang</strong></li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:16:23', '2025-06-27 13:34:24'),
(49, '1e0ed1e9-96e7-487b-b92f-993d382b63f9', 'simple', 'SURVEY KIT PAKET TAS B', 'https://viviashop.com/', NULL, NULL, 'survey-kit-paket-tas-b', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9872554524, 'Terdiri dari :\r\n 1 Tas Backpack Canvas\r\n 1 Buku Blocknote Paperline 50\r\n 1 Ballpoint Standard AE 7\r\n 2 Pensil Greebel 2B\r\n 1 Penghapus BIG\r\n 1 Rautan Greebel\r\n 1 Name tag + Tali BIG', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Tas B</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket tas ransel (backpack) yang dirancang untuk kebutuhan acara survei, pelatihan lapangan, atau kegiatan serupa.</li><li>Cocok untuk distribusi merchandise kepada peserta sebagai alat pendukung.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Tas Backpack Canvas: </strong>Tas ransel berbahan kanvas kuat dan tahan lama, cocok untuk membawa barang-barang penting.</li><li><strong>Buku Blocknote Paperline 50: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting selama survei.</li><li><strong>Ballpoint Standard AE 7: </strong>Pulpen standar berkualitas tinggi untuk menulis yang nyaman.</li><li><strong>2 Pensil Greebel 2B: </strong>Dua pensil 2B berkualitas tinggi untuk pekerjaan detail.</li><li><strong>Penghapus BIG: </strong>Penghapus besar untuk mengoreksi kesalahan secara efektif.</li><li><strong>Rautan Greebel: </strong>Rautan tajam untuk mengasah pensil.</li><li><strong>Name tag + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Praktis: </strong>Semua item dalam paket dipilih untuk memenuhi kebutuhan peserta survei secara efektif.</li><li><strong>Kualitas Berkualitas: </strong>Setiap komponen paket dibuat dari bahan berkualitas tinggi untuk penggunaan jangka panjang.</li><li><strong>Fleksibilitas: </strong>Tas backpack memberikan ruang penyimpanan yang cukup untuk membawa semua perlengkapan yang diperlukan.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:18:50', '2025-06-27 13:34:24'),
(50, 'f6c3a70a-8198-4615-931f-a945277a3bd8', 'simple', 'SURVEY KIT PAKET TAS C', 'https://viviashop.com/', NULL, NULL, 'survey-kit-paket-tas-c', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9960179949, 'Terdiri dari :\r\n 1 Tas Backpack Canvas\r\n 1 Buku Blocknote Paperline 50\r\n 1 Ballpoint Standard AE 7\r\n 2 Pensil Greebel 2B\r\n 1 Penghapus BIG\r\n 1 Rautan Greebel\r\n 1 Name tag + Tali BIG\r\n *) Bisa Costum bundling dengan ATK/ Barang lain', '<p style=\"margin-left:0px;\"><strong>Produk: Paket Tas C</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Paket tas ransel (backpack) yang dirancang untuk kebutuhan acara survei, pelatihan lapangan, atau kegiatan serupa.</li><li>Cocok untuk distribusi merchandise kepada peserta sebagai alat pendukung.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Isi Paket:</strong></p><ol><li><strong>Tas Backpack Canvas: </strong>Tas ransel berbahan kanvas kuat dan tahan lama, cocok untuk membawa barang-barang penting.</li><li><strong>Buku Blocknote Paperline 50: </strong>Buku catatan dengan ukuran praktis untuk mencatat informasi penting selama survei.</li><li><strong>Ballpoint Standard AE 7: </strong>Pulpen standar berkualitas tinggi untuk menulis yang nyaman.</li><li><strong>2 Pensil Greebel 2B: </strong>Dua pensil 2B berkualitas tinggi untuk pekerjaan detail.</li><li><strong>Penghapus BIG: </strong>Penghapus besar untuk mengoreksi kesalahan secara efektif.</li><li><strong>Rautan Greebel: </strong>Rautan tajam untuk mengasah pensil.</li><li><strong>Name tag + Tali BIG: </strong>Nametag besar dengan tali panjang, ideal untuk identifikasi pribadi.</li></ol></li><li><p style=\"margin-left:0px;\"><strong>Fitur Tambahan:</strong></p><ul><li><strong>Custom Bundling Fleksibel: </strong>Paket ini dapat dikombinasikan dengan ATK (Alat Tulis Kantor) atau barang lain sesuai kebutuhan.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:20:55', '2025-06-27 13:34:24'),
(51, 'ae0d4b7b-09f0-4fb1-83ed-c1801d37a805', 'simple', 'PENSIL GREEBEL', 'https://viviashop.com/', NULL, NULL, 'pensil-greebel', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7337167335, 'Pensil berkualitas', '<p style=\"margin-left:0px;\"><strong>Produk: Pensil Greebel</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pensil berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari bahan yang tahan lama dan nyaman digunakan.</li><li><strong>Desain Elegan: </strong>Dilengkapi dengan kotak kemasan elegan yang mencerminkan kesan premium.</li><li><strong>Keseragaman: </strong>Pensil ini dirancang dengan ketebalan seragam, memastikan performa yang konsisten saat digunakan.</li><li><strong>Tajam dan Halus: </strong>Memberikan hasil tulisan yang rapi dan halus, cocok untuk berbagai jenis pekerjaan menulis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti menulis catatan, menggambar, atau mengerjakan tugas sekolah/kantor.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:24:22', '2025-06-27 13:34:24'),
(52, '96acd3f1-1c32-4dc3-be4b-9e36347c7adc', 'simple', 'PENSIL FABER-CASTELL', 'https://viviashop.com/', NULL, NULL, 'pensil-faber-castell', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9234482973, 'PENSIL FABER-CASTELL', '<p style=\"margin-left:0px;\"><strong>Produk: Pensil Faber-Castell</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pensil berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari bahan yang tahan lama dan nyaman digunakan.</li><li><strong>Desain Elegan: </strong>Dilengkapi dengan kotak kemasan elegan yang mencerminkan kesan premium.</li><li><strong>Keseragaman: </strong>Pensil ini dirancang dengan ketebalan seragam, memastikan performa yang konsisten saat digunakan.</li><li><strong>Tajam dan Halus: </strong>Memberikan hasil tulisan yang rapi dan halus, cocok untuk berbagai jenis pekerjaan menulis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti menulis catatan, menggambar, atau mengerjakan tugas sekolah/kantor.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:26:23', '2025-06-27 13:34:24'),
(53, '6282472a-570e-4ac3-a7de-b501fbc43f70', 'simple', 'PENGHAPUS STAEDLER', 'https://viviashop.com/', NULL, NULL, 'penghapus-staedler', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5883814955, 'PENGHAPUS STAEDLER', '<p style=\"margin-left:0px;\"><strong>Produk: Penghapus Staedtler</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Penghapus berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari bahan yang tahan lama dan efektif dalam mengoreksi kesalahan tulisan.</li><li><strong>Desain Elegan: </strong>Dilengkapi dengan kotak kemasan elegan yang mencerminkan kesan premium.</li><li><strong>Efisiensi: </strong>Didesain untuk memberikan hasil penghapusan yang maksimal tanpa merusak permukaan kertas.</li><li><strong>Kenyamanan: </strong>Bentuk ergonomis yang nyaman digunakan saat mengoreksi tulisan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengoreksi catatan, pekerjaan sekolah/kantor, atau aktivitas menulis lainnya.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:28:59', '2025-06-27 13:34:24');
INSERT INTO `products` (`id`, `sku`, `type`, `name`, `link1`, `link2`, `link3`, `slug`, `price`, `base_price`, `total_stock`, `sold_count`, `rating`, `is_featured`, `is_print_service`, `harga_beli`, `weight`, `length`, `width`, `height`, `barcode`, `short_description`, `description`, `status`, `is_smart_print_enabled`, `user_id`, `brand_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(54, 'b096edef-144d-4cd8-b094-f15801c942b6', 'simple', 'BALLPOIN KENKO K-1', 'https://viviashop.com/', NULL, NULL, 'ballpoin-kenko-k-1', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3215745578, 'BALLPOIN KENKO K-1', '<p style=\"margin-left:0px;\"><strong>Produk: Ballpoint Kenko K-1</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pulpen ballpoint berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari bahan yang tahan lama dan nyaman digunakan.</li><li><strong>Desain Ergonomis: </strong>Dilengkapi dengan bentuk ergonomis yang nyaman di tangan saat menulis.</li><li><strong>Tinta Halus: </strong>Memberikan hasil tulisan yang rapi dan halus, cocok untuk berbagai jenis pekerjaan menulis.</li><li><strong>Keseragaman Tinta: </strong>Didesain untuk memberikan aliran tinta yang seragam selama penggunaan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti menulis catatan, mengisi formulir, atau mengerjakan tugas sekolah/kantor.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:31:04', '2025-06-27 13:34:24'),
(55, '6d91c6b1-4ee0-4a05-8898-eb2587702fe9', 'simple', 'BALLPOIN SNOWMAN V3', 'https://viviashop.com/', NULL, NULL, 'ballpoin-snowman-v3', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9551579167, 'BALLPOIN SNOWMAN V3', '<p style=\"margin-left:0px;\"><strong>Produk: Ballpoint Snowman V3</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pulpen gel berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Tinta Gel Berkualitas: </strong>Menggunakan tinta gel yang memberikan hasil tulisan yang halus dan rapi.</li><li><strong>Desain Ergonomis: </strong>Dilengkapi dengan bentuk ergonomis yang nyaman di tangan saat menulis.</li><li><strong>Aliran Tinta Seragam: </strong>Didesain untuk memberikan aliran tinta yang seragam selama penggunaan.</li><li><strong>Keseragaman Warna: </strong>Memberikan warna yang konsisten pada setiap pulpen dalam paket.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti menulis catatan, mengisi formulir, atau mengerjakan tugas sekolah/kantor.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:32:49', '2025-06-27 13:34:24'),
(56, '35b4e19b-7ddd-45fc-b631-cb9885a192ec', 'simple', 'BALLPOIN SNOWMAN V5', 'https://viviashop.com/', NULL, NULL, 'ballpoin-snowman-v5', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2212568815, 'BALLPOIN SNOWMAN V5', '<p style=\"margin-left:0px;\"><strong>Produk: Ballpoint Snowman V5</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pulpen ballpoint berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Ergonomis: </strong>Dilengkapi dengan bentuk ergonomis yang nyaman di tangan saat menulis.</li><li><strong>Tinta Halus: </strong>Memberikan hasil tulisan yang rapi dan halus, cocok untuk berbagai jenis pekerjaan menulis.</li><li><strong>Keseragaman Tinta: </strong>Didesain untuk memberikan aliran tinta yang seragam selama penggunaan.</li><li><strong>Kenyamanan Penggunaan: </strong>Memiliki grip (pegangan) yang nyaman untuk kontrol yang lebih baik saat menulis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti menulis catatan, mengisi formulir, atau mengerjakan tugas sekolah/kantor.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:34:10', '2025-06-27 13:34:24'),
(57, '02521ef9-e49f-4675-9dfe-c4fa9af2fabb', 'simple', 'SPIDOL SNOWMAN', 'https://viviashop.com/', NULL, NULL, 'spidol-snowman', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3052257655, 'SPIDOL SNOWMAN', '<p style=\"margin-left:0px;\"><strong>Produk: Spidol Snowman</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Spidol (marker board) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau presentasi di papan tulis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Tinta Berkualitas: </strong>Menggunakan tinta yang tahan lama dan mudah terlihat pada papan tulis.</li><li><strong>Desain Ergonomis: </strong>Dilengkapi dengan bentuk ergonomis yang nyaman digunakan saat menulis.</li><li><strong>Keseragaman Warna: </strong>Memberikan warna yang konsisten pada setiap spidol dalam paket.</li><li><strong>Praktis: </strong>Mudah digunakan untuk membuat catatan, presentasi, atau menggambar pada papan tulis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti pembuatan catatan, presentasi, atau aktivitas pengajaran.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:36:21', '2025-06-27 13:34:24'),
(58, 'e4ee483e-9213-43f4-b1b2-106f06a6015c', 'simple', 'STABILO BOSS', 'https://viviashop.com/', NULL, NULL, 'stabilo-boss', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3051202529, 'STABILO BOSS', '<p style=\"margin-left:0px;\"><strong>Produk: Stabilo Boss</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pensil warna (highlighter) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Tinta Berkualitas: </strong>Menggunakan tinta yang tahan lama dan mudah terlihat pada berbagai jenis kertas.</li><li><strong>Desain Ergonomis: </strong>Dilengkapi dengan bentuk ergonomis yang nyaman digunakan saat menulis.</li><li><strong>Keseragaman Warna: </strong>Memberikan warna yang konsisten pada setiap pensil dalam paket.</li><li><strong>Praktis: </strong>Mudah digunakan untuk menyoroti teks, membuat catatan, atau menggambar.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti pembuatan catatan, presentasi, atau aktivitas pengajaran.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:37:59', '2025-06-27 13:34:24'),
(59, '7610c01d-fa52-46c5-84d7-bd24fde109d8', 'simple', 'GUNTING JOYKO SC-848', 'https://viviashop.com/', NULL, NULL, 'gunting-joyko-sc-848', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1089936319, 'GUNTING JOYKO SC-848', '<p style=\"margin-left:0px;\"><strong>Produk: Gunting Joyko SC-848</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Gunting berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas rumah tangga.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Stainless Steel: </strong>Dilengkapi dengan bahan stainless steel ( baja tahan karat), yang tahan lama dan tidak mudah berkarat.</li><li><strong>Pisau Tajam: </strong>Memiliki pisau yang sangat tajam, memastikan potongan yang presisi.</li><li><strong>Desain Ergonomis: </strong>Pegangan nyaman digunakan untuk menghindari lelah saat melakukan pemotongan dalam waktu lama.</li><li><strong>Ukuran Praktis: </strong>Panjang gunting sekitar <strong>20.9 cm </strong>, dengan lebar ujung pisau <strong>7.7 cm </strong>, memberikan kontrol yang baik saat dipakai.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti pemotongan kertas, plastik, atau kain.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:39:36', '2025-06-27 13:34:24'),
(60, '2dec9c63-eb8d-44c0-8c14-4b682d384123', 'simple', 'TIPE X KENKO KE-1', 'https://viviashop.com/', NULL, NULL, 'tipe-x-kenko-ke-1', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9129032225, 'TIPE X KENKO KE-1', '<p style=\"margin-left:0px;\"><strong>Produk: Tipe X Kenko KE-1</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Pensil penghapus (correction pen) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan sekolah, kantor, atau aktivitas menulis lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Penghapusan Efektif: </strong>Menggunakan tinta yang dapat menghapus tulisan dengan cepat dan mudah tanpa merusak permukaan kertas.</li><li><strong>Desain Ergonomis: </strong>Dilengkapi dengan bentuk ergonomis yang nyaman digunakan saat mengoreksi tulisan.</li><li><strong>Keseragaman Penggunaan: </strong>Memberikan hasil penghapusan yang seragam pada setiap kali digunakan.</li><li><strong>Praktis: </strong>Mudah digunakan untuk mengoreksi kesalahan tulisan di berbagai jenis kertas.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengoreksi catatan, pekerjaan sekolah/kantor, atau aktivitas menulis lainnya.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:40:54', '2025-06-27 13:34:24'),
(61, '3a825430-0b2e-40cb-aa9f-6ea8c72cad52', 'simple', 'CUTTER KENKO A-300', 'https://viviashop.com/', NULL, NULL, 'cutter-kenko-a-300', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7551428348, 'CUTTER KENKO A-300', '<p style=\"margin-left:0px;\"><strong>Produk: Cutter Kenko A-300</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Cutter (pisau lipat) berkualitas tinggi dengan desain ergonomis dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas pemotongan ringan sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Lipat yang Aman: </strong>Pisau dapat dilipat ke dalam gagang, menjaga keamanan saat tidak digunakan.</li><li><strong>Bahan Pisau Tajam: </strong>Terbuat dari baja tahan karat yang tajam dan tahan lama untuk berbagai keperluan pemotongan.</li><li><strong>Grip Nyaman: </strong>Gagang ergonomis memberikan kenyamanan dan cengkeraman kuat saat digunakan.</li><li><strong>Ukuran Praktis: </strong>Ukuran yang pas di tangan, mudah dibawa dan nyaman digunakan untuk pekerjaan detail.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk memotong kertas, karton tipis, plastik, atau bahan lainnya di lingkungan kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:44:12', '2025-06-27 13:34:24'),
(62, '440ad2db-e132-4da3-bb78-e5b57c8860f8', 'simple', 'CUTTER KENKO L-500', 'https://viviashop.com/', NULL, NULL, 'cutter-kenko-l-500', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9439259396, 'CUTTER KENKO L-500', '<p style=\"margin-left:0px;\"><strong>Produk: Cutter Kenko L-500</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Cutter (pisau lipat) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas pemotongan ringan sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Desain Lipat yang Aman: </strong>Pisau dapat dilipat ke dalam gagang, menjaga keamanan saat tidak digunakan.</li><li><strong>Bahan Pisau Tajam: </strong>Terbuat dari baja tahan karat yang tajam dan tahan lama untuk berbagai keperluan pemotongan.</li><li><strong>Grip Nyaman: </strong>Gagang ergonomis memberikan kenyamanan dan cengkeraman kuat saat digunakan.</li><li><strong>Ukuran Praktis: </strong>Panjang pisau ±15.5 cm dan lebar ±2.5 cm, mudah dibawa dan nyaman digunakan untuk pekerjaan detail.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk memotong kertas, karton tipis, plastik, atau bahan lainnya di lingkungan kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:46:55', '2025-06-27 13:34:24'),
(63, '50788a86-1f38-4d38-a61a-7bd82a1b3e56', 'simple', 'BINDER CLIP NO.107', 'https://viviashop.com/', NULL, NULL, 'binder-clip-no-107', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5282596563, 'BINDER CLIP NO.107', '<p style=\"margin-left:0px;\"><strong>Produk: Binder Clip No. 107</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Klip pengikat dokumen (binder clip) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas penyusunan dokumen sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari logam tahan lama yang kuat dan tahan karat.</li><li><strong>Desain Ergonomis: </strong>Mudah digunakan untuk mengikat dokumen secara rapi dan efisien.</li><li><strong>Kapasitas Penyimpanan: </strong>Dapat menahan berbagai ukuran dokumen dengan aman.</li><li><strong>Praktis: </strong>Ideal untuk menyusun dokumen, presentasi, atau pekerjaan administratif.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengikat catatan, surat, atau dokumen di kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:52:32', '2025-06-27 13:34:24'),
(64, '44ac723f-5ad8-40b1-a196-2507b57a7c11', 'simple', 'BINDER CLIP NO.111', 'https://viviashop.com/', NULL, NULL, 'binder-clip-no-111', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7154579003, 'BINDER CLIP NO.111', '<p style=\"margin-left:0px;\"><strong>Produk: Binder Clip No. 111</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Klip pengikat dokumen (binder clip) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas penyusunan dokumen sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari logam tahan lama yang kuat dan tahan karat.</li><li><strong>Desain Ergonomis: </strong>Mudah digunakan untuk mengikat dokumen secara rapi dan efisien.</li><li><strong>Kapasitas Penyimpanan: </strong>Dapat menahan berbagai ukuran dokumen dengan aman.</li><li><strong>Praktis: </strong>Ideal untuk menyusun dokumen, presentasi, atau pekerjaan administratif.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengikat catatan, surat, atau dokumen di kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:53:57', '2025-06-27 13:34:24'),
(65, '8cf1163c-8edb-4273-ad62-e6d0b38fcd75', 'simple', 'BINDER CLIP NO.155', 'https://viviashop.com/', NULL, NULL, 'binder-clip-no-155', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5212249496, 'BINDER CLIP NO.155', '<p style=\"margin-left:0px;\"><strong>Produk: Binder Clip No. 155</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Klip pengikat dokumen (binder clip) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas penyusunan dokumen sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari logam tahan lama yang kuat dan tahan karat.</li><li><strong>Desain Ergonomis: </strong>Mudah digunakan untuk mengikat dokumen secara rapi dan efisien.</li><li><strong>Kapasitas Penyimpanan: </strong>Dapat menahan berbagai ukuran dokumen dengan aman.</li><li><strong>Praktis: </strong>Ideal untuk menyusun dokumen, presentasi, atau pekerjaan administratif.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengikat catatan, surat, atau dokumen di kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:55:36', '2025-06-27 13:34:24'),
(66, '6367c80f-dbbd-4cf8-93aa-4c47e9e5afe5', 'simple', 'BINDER CLIP NO.260', 'https://viviashop.com/', NULL, NULL, 'binder-clip-no-260', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2513643142, 'BINDER CLIP NO.260', '<p style=\"margin-left:0px;\"><strong>Produk: Binder Clip No. 260</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Klip pengikat dokumen (binder clip) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas penyusunan dokumen sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari logam tahan lama yang kuat dan tahan karat.</li><li><strong>Desain Ergonomis: </strong>Mudah digunakan untuk mengikat dokumen secara rapi dan efisien.</li><li><strong>Kapasitas Penyimpanan: </strong>Dapat menahan berbagai ukuran dokumen dengan aman.</li><li><strong>Praktis: </strong>Ideal untuk menyusun dokumen, presentasi, atau pekerjaan administratif.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengikat catatan, surat, atau dokumen di kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:57:10', '2025-06-27 13:34:24'),
(67, 'f8c2dbdd-a67c-42d0-a976-dae8422b1367', 'simple', 'TRIGONAL CLIP NO.3', 'https://viviashop.com/', NULL, NULL, 'trigonal-clip-no-3', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2329963965, 'TRIGONAL CLIP NO.3', '<p style=\"margin-left:0px;\"><strong>Produk: Trigonal Clip No. 3</strong></p><ul><li><p style=\"margin-left:0px;\"><strong>Deskripsi Produk:</strong></p><ul><li>Klip pengikat dokumen (trigonal clip) berkualitas tinggi dengan desain profesional dan fungsional.</li><li>Cocok untuk kebutuhan kantor, sekolah, atau aktivitas penyusunan dokumen sehari-hari.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Utama:</strong></p><ul><li><strong>Bahan Berkualitas: </strong>Terbuat dari logam tahan lama yang kuat dan tahan karat.</li><li><strong>Desain Ergonomis: </strong>Mudah digunakan untuk mengikat dokumen secara rapi dan efisien.</li><li><strong>Kapasitas Penyimpanan: </strong>Dapat menahan berbagai ukuran dokumen dengan aman.</li><li><strong>Praktis: </strong>Ideal untuk menyusun dokumen, presentasi, atau pekerjaan administratif.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan:</strong></p><ul><li>Ideal untuk kebutuhan sehari-hari seperti mengikat catatan, surat, atau dokumen di kantor, sekolah, atau rumah.</li><li>Dapat digunakan dalam acara promosi, merchandise perusahaan, atau hadiah.</li></ul></li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 13:59:14', '2025-06-27 13:34:24'),
(68, '6b7ca530-a771-4d12-906f-34a2a42ce929', 'simple', 'TRIGONAL CLIP NO.5', 'https://viviashop.com/', NULL, NULL, 'trigonal-clip-no-5', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2312953512, 'TRIGONAL CLIP NO.5', '<ul><li><strong>Nama Produk </strong>: Trigonal Clip No.5</li><li><strong>Merek </strong>: Joyko</li><li><strong>Ukuran </strong>: Jumbo Size (No. 5)</li><li><strong>Fungsi </strong>: Digunakan untuk menggabungkan atau menyatukan lembaran kertas, dokumen, atau bahan lainnya.</li><li><strong>Material </strong>: Terbuat dari logam, tampilan bersih dan kokoh.</li><li><strong>Desain </strong>: Klip berbentuk segitiga (trigonal), memberikan kekuatan ekstra dalam menahan beban.</li><li><strong>Kemasan </strong>: Dalam kotak plastik transparan dengan label mencantumkan spesifikasi produk.</li></ul><p style=\"margin-left:0px;\">Produk ini cocok digunakan di kantor, sekolah, atau tempat kerja yang membutuhkan klip kertas berkualitas tinggi untuk dokumen tebal atau banyak halaman.</p>', 1, 0, 1, NULL, NULL, '2025-05-10 14:02:00', '2025-06-27 13:34:24'),
(69, '60455b16-6f45-448d-8269-52cf019346d1', 'simple', 'BLOCK NOTE A5 50  PAPERLINE', 'https://viviashop.com/', NULL, NULL, 'block-note-a5-50-paperline', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8585057703, 'BLOCK NOTE A5 50 PAPERLINE', '<ul><li><strong>Nama Produk </strong>: Block Note A5 50 Paperline</li><li><strong>Merek </strong>: Paperline</li><li><strong>Ukuran </strong>: A5 (ukuran standar untuk catatan kecil)</li><li><strong>Jumlah Halaman </strong>: 50 halaman</li><li><strong>Fungsi </strong>: Digunakan sebagai buku catatan, jurnal, atau alat tulis untuk mencatat ide, memo, dan informasi penting.</li><li><strong>Desain </strong>: Cover berwarna biru dengan desain minimalis, menampilkan siluet seseorang di bagian depan.</li><li><strong>Kategori </strong>: Alat tulis kantor.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:04:36', '2025-06-27 13:34:24'),
(70, '39a4a17a-2deb-4393-a546-40f8b161ed96', 'simple', 'BUKU AGENDA  SURAT MASUK & KELUAR', 'https://viviashop.com/', NULL, NULL, 'buku-agenda-surat-masuk-keluar', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1948694655, 'BUKU AGENDA SURAT MASUK & KELUAR', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Buku Agenda Surat Masuk &amp; Keluar</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Fitur Utama :</strong></h4><ol><li><strong>Fungsi </strong>: Digunakan untuk mencatat detail surat-surat yang masuk dan keluar, termasuk nomor surat, tanggal, perihal, pengirim/penerima, dan tindak lanjut.</li><li><strong>Desain </strong>: Tersedia dalam berbagai warna cover (biru, hijau, merah), dengan desain profesional dan elegan.</li><li><strong>Isi </strong>: Halaman-halaman didesain khusus untuk memudahkan pencatatan data secara rapi dan sistematis.</li></ol><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk keperluan administrasi di kantor, sekolah, atau instansi lain yang membutuhkan dokumentasi surat secara terstruktur.</li><li>Memudahkan pelacakan dan manajemen arsip surat.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:06:35', '2025-06-27 13:34:24'),
(71, 'f2a7a231-d3ae-488f-b066-02b9b38f6039', 'simple', 'CANON iP2770', 'https://viviashop.com/', NULL, NULL, 'canon-ip2770', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 5220944021, 'Spesifikasi :\r\n Print Only \r\nA4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon iP2770</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print Only </strong>: Printer ini hanya mendukung fungsi mencetak, tidak memiliki fitur scan, copy, atau fax.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain sederhana dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian atas printer.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer sederhana dan efisien untuk mencetak dokumen, foto, atau materi lainnya dalam warna atau hitam-putih.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:22:47', '2025-06-27 13:34:24'),
(72, '329d3f0d-d233-4d53-9cb3-9e017f055e15', 'simple', 'CANON MG2570s', 'https://viviashop.com/', NULL, NULL, 'canon-mg2570s', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 6176185005, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon MG2570s</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian atas printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:24:42', '2025-06-27 13:34:24'),
(73, '96d92b57-3a3a-4575-95aa-b8bb697a69be', 'simple', 'CANON e410', 'https://viviashop.com/', NULL, NULL, 'canon-e410', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2075531340, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon e410</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain sederhana dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian atas printer.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:26:07', '2025-06-27 13:34:24'),
(74, 'ec152357-1a6d-4a8e-9aed-57b08c871c9b', 'simple', 'CANON G4770', 'https://viviashop.com/', NULL, NULL, 'canon-g4770', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8984555014, 'Spesifikasi :\r\n Print , Scan, Copy, ADF\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon G4770</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li><li><strong>ADF (Automatic Document Feeder) </strong>: Memungkinkan pemindai otomatis untuk mengolah beberapa halaman sekaligus tanpa perlu menempatkan setiap halaman secara manual.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian atas printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, copy, dan ADF dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, salinan dokumen, serta pemrosesan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:27:41', '2025-06-27 13:34:24'),
(75, '44034be2-16a6-49ab-a478-0b622eefa01e', 'simple', 'CANON G2770', 'https://viviashop.com/', NULL, NULL, 'canon-g2770', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1987169492, 'Spesifikasi :\r\n Print, Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon G2770</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian atas printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:30:03', '2025-06-27 13:34:24'),
(76, '9e937670-29ce-4999-aed8-4f5cc7269912', 'simple', 'CANON LBP 6030', 'https://viviashop.com/', NULL, NULL, 'canon-lbp-6030', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1209549924, 'Spesifikasi :\r\n Print Only\r\n A4 Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon LBP 6030</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Laser)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print Only </strong>: Printer ini hanya mendukung fungsi mencetak, tidak memiliki fitur scan, copy, atau fax.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>hitam-putih (mono) </strong>saja.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tipe Teknologi </strong>:</p><ul><li>Menggunakan teknologi <strong>laser </strong>, yang dikenal untuk kecepatan cetak tinggi dan hasil cetak berkualitas tinggi.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan minimalis, dengan warna putih yang bersih.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat ramping dan cocok untuk penggunaan di ruang kerja pribadi atau kantor kecil.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer laser sederhana dan efisien untuk mencetak dokumen hitam-putih dengan kecepatan tinggi.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang fokus pada pencetakan dokumen teks tanpa memerlukan fitur tambahan seperti scan atau copy.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:31:28', '2025-06-27 13:34:24'),
(77, '216e13c6-b6cc-4d30-87df-162756aed951', 'simple', 'HP INKTANK 315', 'https://viviashop.com/', NULL, NULL, 'hp-inktank-315', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2327828647, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>HP InkTank 315</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:34:06', '2025-06-27 13:34:24'),
(78, 'a9488f82-962f-4947-bf0c-4030aea2a147', 'simple', 'HP 2335', 'https://viviashop.com/', NULL, NULL, 'hp-2335', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2829980749, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>HP 2335</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:35:35', '2025-06-27 13:34:24'),
(79, 'e5bff255-3b3b-4a86-8954-b968f7e6e9d6', 'simple', 'HP 2775', 'https://viviashop.com/', NULL, NULL, 'hp-2775', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1210503196, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>HP 2775</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak warna, menunjukkan kemampuan printer dalam menghasilkan output berkualitas tinggi.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:37:43', '2025-06-27 13:34:24'),
(80, '6312d9c4-da40-45a3-bc02-ab8fa2b1b13f', 'simple', 'BROTHER T220', 'https://viviashop.com/', NULL, NULL, 'brother-t220', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9147739897, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Brother T220</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak warna, menunjukkan kemampuan printer dalam menghasilkan output berkualitas tinggi.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, dan copy dalam satu perangkat.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:39:07', '2025-06-27 13:34:24'),
(81, '354da0ba-e00b-4af3-89c8-3d3194855284', 'simple', 'BROTHER T420W', 'https://viviashop.com/', NULL, NULL, 'brother-t420w', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 9000312264, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Color/Mono\r\n USB, WIFI Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Brother T420W</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>warna (color) </strong>dan <strong>hitam-putih (mono) </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan dua opsi koneksi:<ul><li><strong>USB input </strong>: Koneksi langsung ke komputer melalui port USB.</li><li><strong>Wi-Fi </strong>: Koneksi nirkabel untuk penggunaan lebih fleksibel.</li></ul></li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak warna, menunjukkan kemampuan printer dalam menghasilkan output berkualitas tinggi.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, copy, serta koneksi Wi-Fi untuk fleksibilitas penggunaan.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien, tanpa perlu kabel fisik.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:40:29', '2025-06-27 13:34:24');
INSERT INTO `products` (`id`, `sku`, `type`, `name`, `link1`, `link2`, `link3`, `slug`, `price`, `base_price`, `total_stock`, `sold_count`, `rating`, `is_featured`, `is_print_service`, `harga_beli`, `weight`, `length`, `width`, `height`, `barcode`, `short_description`, `description`, `status`, `is_smart_print_enabled`, `user_id`, `brand_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(82, '4a0b0fb7-a7ec-4e4f-8e2c-758a3d8ce2e3', 'simple', 'BROTHER B2080DW', 'https://viviashop.com/', NULL, NULL, 'brother-b2080dw', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3383502191, 'Spesifikasi :\r\n Print Only\r\n A4 Mono/ Duplex\r\n USB, WIFI Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Brother B2080DW</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Laser Monokrom)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print Only </strong>: Printer ini hanya mendukung fungsi mencetak, tidak memiliki fitur scan, copy, atau fax.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>hitam-putih (mono) </strong>saja.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Duplex </strong>:</p><ul><li>Dilengkapi dengan <strong>duplex printing </strong>, yaitu kemampuan mencetak di kedua sisi kertas secara otomatis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan dua opsi koneksi:<ul><li><strong>USB input </strong>: Koneksi langsung ke komputer melalui port USB.</li><li><strong>Wi-Fi </strong>: Koneksi nirkabel untuk penggunaan lebih fleksibel.</li></ul></li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas tinggi.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer laser monokromatik sederhana dan efisien untuk mencetak dokumen hitam-putih dengan kecepatan tinggi.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang fokus pada pencetakan dokumen teks tanpa memerlukan fitur tambahan seperti scan atau copy. Fitur duplex printing juga sangat berguna untuk menghemat kertas.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:42:21', '2025-06-27 13:34:24'),
(83, '26f4fd49-dd2a-4637-9b70-e7872d6255c8', 'simple', 'BROTHER  DCP L2540DW', 'https://viviashop.com/', NULL, NULL, 'brother-dcp-l2540dw', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7573841080, 'Spesifikasi :\r\n Print , Scan, Copy\r\n A4 Mono/ Duplex\r\n USB, LAN, WIFI Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Brother DCP-L2540DW</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Multifungsi)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Print </strong>: Mencetak dokumen, foto, atau materi lainnya.</li><li><strong>Scan </strong>: Mengubah dokumen fisik menjadi file digital.</li><li><strong>Copy </strong>: Membuat salinan dari dokumen asli.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Ukuran Kertas </strong>:</p><ul><li>Dapat mencetak kertas ukuran <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Warna Cetak </strong>:</p><ul><li>Mendukung pencetakan dalam <strong>hitam-putih (mono) </strong>saja.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Fitur Duplex </strong>:</p><ul><li>Dilengkapi dengan <strong>duplex printing </strong>, yaitu kemampuan mencetak di kedua sisi kertas secara otomatis.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan tiga opsi koneksi:<ul><li><strong>USB input </strong>: Koneksi langsung ke komputer melalui port USB.</li><li><strong>LAN (Ethernet) </strong>: Koneksi jaringan berbasis kabel.</li><li><strong>Wi-Fi </strong>: Koneksi nirkabel untuk penggunaan lebih fleksibel.</li></ul></li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Tombol kontrol mudah diakses di bagian depan printer.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas tinggi.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan printer multifungsi dengan fitur print, scan, copy, serta dukungan duplex printing untuk hemat kertas.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang memerlukan solusi cetak, pemindai, dan salinan dokumen secara efisien. Fitur Wi-Fi dan LAN memberikan fleksibilitas dalam koneksi.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:44:37', '2025-06-27 13:34:24'),
(84, '46295cec-3019-4851-8d30-51e3794757a8', 'simple', 'BROTHER  SCANNER DS 6402', 'https://viviashop.com/', NULL, NULL, 'brother-scanner-ds-6402', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7463335260, 'Spesifikasi :\r\n Scan only/ Luas scan A4/ \r\nSpeed up to 20 ppm/ USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Brother Scanner DS-6402</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Pemindai Dokumen)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Scan Only </strong>: Perangkat ini hanya mendukung fungsi pemindaian dokumen, tidak memiliki fitur print, copy, atau lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Luas Scan </strong>:</p><ul><li>Dapat memindai dokumen dengan ukuran maksimal <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kecepatan Pemindaian </strong>:</p><ul><li>Kecepatan pemindaian mencapai <strong>hingga 20 halaman per menit (ppm) </strong>untuk dokumen berwarna dan hitam-putih.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain ramping dan modern, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Terlihat sedang melakukan proses pemindaian dokumen, menunjukkan kemampuan pemindaian cepat dan efisien.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan alat pemindai dokumen sederhana namun efisien untuk mengubah dokumen fisik menjadi file digital.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang sering memerlukan solusi pemindaian dokumen dengan kecepatan tinggi.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:47:09', '2025-06-27 13:34:24'),
(85, '6a2eb69a-fc31-4974-bd22-43f1250d7a3b', 'simple', 'HP  SCANJET 2000 S2', 'https://viviashop.com/', NULL, NULL, 'hp-scanjet-2000-s2', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 1708922681, 'Spesifikasi :\r\n Scan only/ Luas scan A4/ \r\nSpeed up to 35 ppm/ USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>HP ScanJet 2000 S2</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Pemindai Dokumen)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Scan Only </strong>: Perangkat ini hanya mendukung fungsi pemindaian dokumen, tidak memiliki fitur print, copy, atau lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Luas Scan </strong>:</p><ul><li>Dapat memindai dokumen dengan ukuran maksimal <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kecepatan Pemindaian </strong>:</p><ul><li>Kecepatan pemindaian mencapai <strong>hingga 35 halaman per menit (ppm) </strong>untuk dokumen berwarna dan hitam-putih.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain modern dan ramping, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Terlihat sedang melakukan proses pemindaian dokumen, menunjukkan kemampuan pemindaian cepat dan efisien.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan alat pemindai dokumen sederhana namun efisien untuk mengubah dokumen fisik menjadi file digital.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang sering memerlukan solusi pemindaian dokumen dengan kecepatan tinggi.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:48:46', '2025-06-27 13:34:24'),
(86, '2425f26e-ca01-46ca-ac59-5cded61a1019', 'simple', 'CANON  SCANNER P208 I', 'https://viviashop.com/', NULL, NULL, 'canon-scanner-p208-i', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2853533163, 'Spesifikasi :\r\n Scan only/ Luas scan A4/ \r\nSpeed up to 10 ppm/ USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Canon Scanner P208 II</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Pemindai Dokumen)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Scan Only </strong>: Perangkat ini hanya mendukung fungsi pemindaian dokumen, tidak memiliki fitur print, copy, atau lainnya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Luas Scan </strong>:</p><ul><li>Dapat memindai dokumen dengan ukuran maksimal <strong>A4 </strong>.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Kecepatan Pemindaian </strong>:</p><ul><li>Kecepatan pemindaian mencapai <strong>hingga 10 halaman per menit (ppm) </strong>untuk dokumen berwarna dan hitam-putih.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain ramping dan modern, cocok untuk penggunaan di rumah atau kantor kecil.</li><li>Terlihat sedang melakukan proses pemindaian dokumen, menunjukkan kemampuan pemindaian yang efisien.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan alat pemindai dokumen sederhana namun efisien untuk mengubah dokumen fisik menjadi file digital.</li><li>Cocok untuk keperluan pribadi, sekolah, atau kantor kecil yang sering memerlukan solusi pemindaian dokumen dengan kecepatan cukup tinggi.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:50:24', '2025-06-27 13:34:24'),
(87, '43d938a3-7ecf-4688-b28c-5e7ac5274b89', 'simple', 'ZEBRA ZD230', 'https://viviashop.com/', NULL, NULL, 'zebra-zd230', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2165704843, 'Spesifikasi :\r\n Thermal Transfer (memakai ribbon) & Direct\r\n Thermal / USB Input', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Zebra ZD230</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Printer Label/Thermal Printer)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Teknologi Cetak </strong>:</p><ul><li>Mendukung dua metode pencetakan:<ul><li><strong>Thermal Transfer </strong>: Menggunakan ribbon panas untuk mencetak pada media khusus.</li><li><strong>Direct Thermal </strong>: Mencetak langsung pada media label tanpa memerlukan ribbon.</li></ul></li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan </strong>:</p><ul><li>Ideal untuk pengguna yang membutuhkan printer label profesional untuk mencetak barcode, stiker, tag, dan aplikasi lainnya di bidang logistik, gudang, manufaktur, atau ritel.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain kompak dan modern, cocok untuk penggunaan di lingkungan industri atau bisnis.</li><li>Terlihat sedang melakukan proses cetak, menunjukkan kemampuan printer dalam menghasilkan output berkualitas tinggi.</li></ul><h4 style=\"margin-left:0px;\"><strong>Fitur Kunci :</strong></h4><ul><li>Fleksibilitas dalam pilihan teknologi cetak (thermal transfer atau direct thermal).</li><li>Konektivitas USB yang mudah digunakan.</li><li>Cocok untuk aplikasi yang memerlukan ketepatan dan efisiensi dalam pencetakan label.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:52:01', '2025-06-27 13:34:24'),
(88, '69714734-d461-4016-93df-25d254951e39', 'simple', 'HONEYWELL HH490', 'https://viviashop.com/', NULL, NULL, 'honeywell-hh490', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4974142505, 'Spesifikasi :\r\n Barcode Scanner / USB', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Honeywell HH490</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Pemindai Barcode)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fungsi </strong>:</p><ul><li><strong>Barcode Scanner </strong>: Perangkat ini dirancang khusus untuk membaca kode barcode, baik itu kode satu dimensi (1D) maupun kode dua dimensi (2D).</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan <strong>USB input </strong>, memungkinkan koneksi langsung ke komputer melalui port USB.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan </strong>:</p><ul><li>Ideal untuk pengguna yang membutuhkan solusi cepat dan akurat dalam membaca kode barcode.</li><li>Cocok untuk aplikasi di bidang ritel, logistik, gudang, atau manajemen inventaris.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain ergonomis dan modern, dengan pegangan yang nyaman untuk digunakan secara berulang.</li><li>Terlihat sedang melakukan proses pembacaan barcode, menunjukkan kemampuan pemindai yang efisien.</li></ul><h4 style=\"margin-left:0px;\"><strong>Fitur Kunci :</strong></h4><ul><li>Kemampuan membaca kode barcode dengan presisi tinggi.</li><li>Konektivitas USB yang mudah digunakan.</li><li>Cocok untuk aplikasi yang memerlukan pencitraan cepat dan akurat dari kode barcode.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:53:36', '2025-06-27 13:34:24'),
(89, '9f9a8629-8736-4eeb-9f75-33ca4075e46f', 'simple', 'WACOM CTL 472', 'https://viviashop.com/', NULL, NULL, 'wacom-ctl-472', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2286998906, 'Spesifikasi :\r\n Tablet with pressure-sensitive,\r\n cordless, battery-free pen', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Wacom CTL-472</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Tablet Grafis)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Fitur Utama </strong>:</p><ul><li><strong>Pressure-Sensitive Pen </strong>: Menggunakan pena yang sensitif terhadap tekanan, memungkinkan pengguna untuk menghasilkan variasi ketebalan garis berdasarkan intensitas tekanan.</li><li><strong>Cordless </strong>: Pena bebas kabel, memberikan kebebasan gerak tanpa batasan kabel.</li><li><strong>Battery-Free Pen </strong>: Pena tidak memerlukan baterai, sehingga lebih praktis dan hemat biaya.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Tujuan Penggunaan </strong>:</p><ul><li>Ideal untuk desainer, ilustrator, atau siapa saja yang membutuhkan alat untuk menciptakan konten digital dengan presisi tinggi.</li><li>Cocok untuk aplikasi seperti menggambar, melukis, mengedit foto, membuat animasi, dan lainnya.</li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain minimalis dan modern, dengan layar tablet yang ramping dan pena yang ergonomis.</li><li>Terlihat sedang digunakan untuk menggambar, menunjukkan kemampuan tablet dalam mendeteksi tekanan pena secara akurat.</li></ul><h4 style=\"margin-left:0px;\"><strong>Fitur Kunci :</strong></h4><ul><li>Teknologi pena pressure-sensitive untuk hasil gambar yang lebih natural.</li><li>Kepraktisan karena pena bebas kabel dan tidak memerlukan baterai.</li><li>Cocok untuk pemula hingga profesional dalam bidang desain digital.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:54:59', '2025-06-27 13:34:24'),
(90, '25f895a9-355f-417f-99c9-f7d9b05c3803', 'simple', 'PROJECTOR  OPTOMA X400LVE', 'https://viviashop.com/', NULL, NULL, 'projector-optoma-x400lve', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3722907274, 'Spesifikasi :\r\n Brightness\r\n Resolution\r\n Aspect Ratio\r\n Input\r\n : 4,000 ANSI LUMENS\r\n : 1024×768\r\n : 4:3 (XGA)\r\n : HDMI, VGA, USB', '<h4 style=\"margin-left:0px;\"><strong>Nama Produk :</strong></h4><ul><li><strong>Optoma X400LVE</strong></li></ul><h4 style=\"margin-left:0px;\"><strong>Kategori :</strong></h4><ul><li>Elektronik (Proyektor)</li></ul><h4 style=\"margin-left:0px;\"><strong>Spesifikasi Utama :</strong></h4><ol><li><p style=\"margin-left:0px;\"><strong>Kecerahan (Brightness) </strong>:</p><ul><li><strong>4,000 ANSI Lumens </strong>: Memberikan tampilan yang cerah dan jelas, bahkan di ruangan dengan cahaya cukup terang.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Resolusi </strong>:</p><ul><li><strong>1024×768 (XGA) </strong>: Menawarkan kualitas gambar yang tajam dan detail dalam presentasi atau penggunaan multimedia.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Rasio Aspek </strong>:</p><ul><li><strong>4:3 </strong>: Ideal untuk konten tradisional seperti presentasi bisnis atau pendidikan.</li></ul></li><li><p style=\"margin-left:0px;\"><strong>Port Input </strong>:</p><ul><li>Dilengkapi dengan berbagai opsi koneksi:<ul><li><strong>HDMI </strong>: Untuk kualitas video HD.</li><li><strong>VGA </strong>: Untuk kompatibilitas dengan perangkat lama.</li><li><strong>USB </strong>: Untuk menghubungkan ke perangkat penyimpanan eksternal atau komputer.</li></ul></li></ul></li></ol><h4 style=\"margin-left:0px;\"><strong>Tampilan Fisik :</strong></h4><ul><li>Desain profesional dan ramping, cocok untuk penggunaan di ruang pertemuan, kantor, atau kelas.</li><li>Terlihat sedang menampilkan gambar dengan cahaya proyeksi yang kuat, menunjukkan kemampuan proyektor dalam memberikan visual yang jelas dan tajam.</li></ul><h4 style=\"margin-left:0px;\"><strong>Tujuan Penggunaan :</strong></h4><ul><li>Ideal untuk pengguna yang membutuhkan solusi proyeksi untuk presentasi bisnis, pelajaran, atau hiburan sederhana.</li><li>Cocok untuk ruangan kecil hingga sedang</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:57:10', '2025-06-27 13:34:24'),
(91, 'd0a81043-8e34-4347-8542-76a0164c13c6', 'simple', 'HP PC 22-DD2009D  AIO CEL J4025', 'https://viviashop.com/', NULL, NULL, 'hp-pc-22-dd2009d-aio-cel-j4025', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 3374656739, 'Spesifikasi :\r\n RAM 4GB / 256 GB SSD / 21.5 FHD / \r\nWindows 11 + OHS', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP PC 22-DD2009D adalah komputer All-in-One (AIO) yang menawarkan desain kompak dan fungsionalitas lengkap dalam satu unit.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Celeron J4025, prosesor berperforma efisien untuk tugas-tugas sehari-hari seperti browsing internet, pengetikan, dan multimedia.</li><li><strong>RAM: </strong>4 GB DDR4, cukup untuk menjalankan aplikasi dasar dengan lancar.</li><li><strong>Penyimpanan: </strong>SSD 256 GB, memberikan kecepatan akses data yang lebih cepat dibandingkan HDD tradisional.</li><li><strong>Layar: </strong>Layar 21.5 inci Full HD (1920 x 1080 piksel), cocok untuk pengalaman visual yang jernih saat bekerja atau hiburan.</li><li><strong>Sistem Operasi: </strong>Windows 11 + OHS (OneHomeService), menyediakan pengalaman pengguna yang modern dan dukungan layanan tambahan dari HP.</li><li><strong>Desain: </strong>Desain minimalis dan ringkas, ideal untuk ruang kerja atau rumah yang terbatas.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Solusi komputasi all-in-one yang praktis.</li><li>Performa yang cukup tangguh untuk aktivitas harian.</li><li>Penyimpanan SSD yang cepat dan responsif.</li><li>Layar FHD untuk kenyamanan penglihatan.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer untuk tugas-tugas umum seperti belajar, bekerja, atau hiburan.</li><li>Orang-orang yang menginginkan solusi komputasi yang hemat tempat tanpa mengorbankan performa.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 14:59:00', '2025-06-27 13:34:24'),
(92, '08d207df-240b-4e9e-b1cf-bc203bdaad35', 'simple', 'HP PC 22-DD2010D  AIO I3 - 1215U', 'https://viviashop.com/', NULL, NULL, 'hp-pc-22-dd2010d-aio-i3-1215u', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2810910066, 'Spesifikasi :\r\n RAM 4G / 512GB SSD / 21,5 FHD /\r\n Windows 11 + OHS', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP PC 22-DD2010D adalah komputer All-in-One (AIO) yang dirancang untuk memberikan performa tangguh dalam desain kompak dan modern.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i3-1215U, prosesor generasi ke-12 dengan arsitektur terbaru yang menawarkan kinerja efisien untuk berbagai tugas.</li><li><strong>RAM: </strong>4 GB DDR4, cukup untuk menjalankan aplikasi dasar hingga beberapa program multitasking secara lancar.</li><li><strong>Penyimpanan: </strong>SSD 512 GB, memberikan kecepatan akses data yang sangat cepat serta kapasitas penyimpanan yang luas untuk file, aplikasi, dan media.</li><li><strong>Layar: </strong>Layar 21.5 inci Full HD (1920 x 1080 piksel), menawarkan resolusi jernih dan sudut pandang lebar untuk pengalaman visual yang optimal.</li><li><strong>Sistem Operasi: </strong>Windows 11 + OHS (OneHomeService), menyediakan platform modern dengan fitur canggih dan dukungan layanan tambahan dari HP.</li><li><strong>Desain: </strong>Desain minimalis dan elegan, ideal untuk ruang kerja atau rumah yang membutuhkan solusi komputasi tanpa mengorbankan estetika.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i3-1215U yang efisien untuk multitasking dan produktivitas harian.</li><li>Penyimpanan SSD 512 GB yang besar dan responsif.</li><li>Layar FHD untuk pengalaman visual yang jernih.</li><li>Sistem operasi Windows 11 dengan dukungan OHS untuk pengalaman pengguna yang lebih baik.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer untuk aktivitas seperti belajar, bekerja, multimedia, dan hiburan.</li><li>Orang-orang yang mencari komputer all-in-one dengan performa yang lebih tinggi dibandingkan model entry-level namun tetap hemat tempat.</li><li>Pemilik bisnis kecil atau profesional yang memerlukan komputer yang praktis dan fungsional.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:01:18', '2025-06-27 13:34:24'),
(93, 'a974a7ca-1d8a-4fa3-94ae-5004143ce9fd', 'simple', 'HP PRO 200 G4  I3-10110U', 'https://viviashop.com/', NULL, NULL, 'hp-pro-200-g4-i3-10110u', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8223478980, 'Spesifikasi :\r\n RAM 4GB/ HDD 1TB/ LCD 21.5 FHD /\r\n Windows 10', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP Pro 200 G4 adalah komputer All-in-One (AIO) yang dirancang untuk kebutuhan bisnis dan produktivitas harian dengan performa tangguh dan desain profesional.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i3-10110U, prosesor generasi ke-10 yang menawarkan kinerja efisien untuk multitasking dan tugas-tugas berat.</li><li><strong>RAM: </strong>4 GB DDR4, cukup untuk menjalankan aplikasi bisnis dasar hingga beberapa program secara bersamaan.</li><li><strong>Penyimpanan: </strong>HDD 1 TB, menyediakan kapasitas penyimpanan besar untuk file, data, dan aplikasi.</li><li><strong>Layar: </strong>Layar 21.5 inci Full HD (1920 x 1080 piksel), memberikan resolusi jernih dan sudut pandang lebar untuk pengalaman kerja yang nyaman.</li><li><strong>Sistem Operasi: </strong>Windows 10, platform stabil dan teruji untuk produktivitas bisnis.</li><li><strong>Desain: </strong>Desain minimalis dan profesional, ideal untuk lingkungan kerja modern.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i3-10110U yang handal untuk multitasking dan tugas-tugas produktif.</li><li>Kapasitas penyimpanan HDD 1 TB yang luas untuk mengakomodasi banyak file dan data.</li><li>Layar FHD untuk pengalaman visual yang jernih saat bekerja atau presentasi.</li><li>Sistem operasi Windows 10 yang stabil dan mendukung banyak aplikasi bisnis.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna bisnis atau profesional yang membutuhkan komputer all-in-one untuk aktivitas seperti dokumen, spreadsheet, presentasi, dan aplikasi bisnis lainnya.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat tempat namun tetap fungsional.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:03:09', '2025-06-27 13:34:24'),
(94, '4a7db14d-7f91-4ae9-82b7-e8690b363d07', 'simple', 'HP PRO 200 G4  I5-10210U', 'https://viviashop.com/', NULL, NULL, 'hp-pro-200-g4-i5-10210u', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4023191979, 'Spesifikasi :\r\n RAM 8GB / HDD 1 TB / LCD 21.5 FHD /\r\n Windows 10 Pro', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP Pro 200 G4 adalah komputer All-in-One (AIO) yang dirancang untuk kebutuhan bisnis dan produktivitas tinggi dengan performa tangguh dan desain profesional.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i5-10210U, prosesor generasi ke-10 yang menawarkan kinerja canggih untuk multitasking dan tugas-tugas berat.</li><li><strong>RAM: </strong>8 GB DDR4, kapasitas memori yang cukup besar untuk menjalankan aplikasi bisnis secara lancar bahkan saat multitasking.</li><li><strong>Penyimpanan: </strong>HDD 1 TB, menyediakan ruang penyimpanan luas untuk file, data, dan aplikasi.</li><li><strong>Layar: </strong>Layar 21.5 inci Full HD (1920 x 1080 piksel), memberikan resolusi jernih dan sudut pandang lebar untuk pengalaman kerja yang nyaman.</li><li><strong>Sistem Operasi: </strong>Windows 10 Pro, sistem operasi yang stabil, aman, dan mendukung banyak fitur bisnis.</li><li><strong>Desain: </strong>Desain minimalis dan profesional, ideal untuk lingkungan kerja modern.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i5-10210U yang handal untuk multitasking, pemrosesan data, dan tugas-tugas produktif.</li><li>Kapasitas RAM 8 GB yang besar untuk menjalankan aplikasi berat dan multitasking dengan lancar.</li><li>Penyimpanan HDD 1 TB yang luas untuk mengakomodasi banyak file dan data.</li><li>Layar FHD untuk pengalaman visual yang jernih saat bekerja atau presentasi.</li><li>Sistem operasi Windows 10 Pro yang aman dan dilengkapi dengan fitur administratif untuk pengguna bisnis.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna bisnis atau profesional yang membutuhkan komputer all-in-one untuk aktivitas seperti dokumen, spreadsheet, presentasi, dan aplikasi bisnis lainnya.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat tempat namun tetap fungsional dan aman.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari dengan performa lebih tinggi dibandingkan model entry-level.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:05:08', '2025-06-27 13:34:24'),
(95, 'a500651c-1cc0-4739-8b27-771a1302851f', 'simple', 'HP PRO 205 G4  ATHLON 3050U', 'https://viviashop.com/', NULL, NULL, 'hp-pro-205-g4-athlon-3050u', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 8477372432, 'Spesifikasi :\r\n RAM 4GB / HDD 1TB / LCD 21.5 FHD\r\n Windows 10', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP Pro 205 G4 adalah komputer All-in-One (AIO) yang dirancang untuk kebutuhan bisnis dan produktivitas harian dengan performa efisien dan desain sederhana.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>AMD Athlon 3050U, prosesor entry-level yang menawarkan kinerja cukup untuk tugas-tugas sehari-hari.</li><li><strong>RAM: </strong>4 GB DDR4, kapasitas memori yang cukup untuk menjalankan aplikasi dasar hingga beberapa program secara bersamaan.</li><li><strong>Penyimpanan: </strong>HDD 1 TB, menyediakan ruang penyimpanan luas untuk file, data, dan aplikasi.</li><li><strong>Layar: </strong>Layar 21.5 inci Full HD (1920 x 1080 piksel), memberikan resolusi jernih dan sudut pandang lebar untuk pengalaman kerja yang nyaman.</li><li><strong>Sistem Operasi: </strong>Windows 10, platform stabil dan teruji untuk produktivitas bisnis.</li><li><strong>Desain: </strong>Desain minimalis dan fungsional, ideal untuk lingkungan kerja sederhana atau rumah.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor AMD Athlon 3050U yang efisien untuk tugas-tugas umum seperti pengetikan, browsing internet, dan aplikasi ringan.</li><li>Kapasitas penyimpanan HDD 1 TB yang luas untuk mengakomodasi banyak file dan data.</li><li>Layar FHD untuk pengalaman visual yang jernih saat bekerja atau presentasi.</li><li>Sistem operasi Windows 10 yang stabil dan mendukung banyak aplikasi bisnis.</li><li>Harga terjangkau dibandingkan dengan komputer all-in-one dengan spesifikasi lebih tinggi.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer all-in-one untuk aktivitas seperti belajar, bekerja, dan hiburan.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat biaya namun tetap fungsional.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari tanpa memerlukan performa tinggi.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:07:53', '2025-06-27 13:34:24'),
(96, 'da27c97f-a7f8-4b5c-b374-3f833846182d', 'simple', 'HP PC 24 AIO  I5-1135G7', 'https://viviashop.com/', NULL, NULL, 'hp-pc-24-aio-i5-1135g7', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 2465178763, 'Spesifikasi :\r\n HP PRO 205 G4\r\n ATHLON 3050U\r\n Spesifikasi :\r\n RAM 4GB / HDD 1TB / LCD 21.5 FHD\r\n Windows 10\r\n RAM 8GB/ 512GB SSD Nvme/\r\n LCD 23,8 IPS FHD/ Windows 11 Home+OHS', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP PC 24 AIO adalah komputer All-in-One (AIO) yang dirancang untuk memberikan performa tangguh dan pengalaman visual yang luar biasa dalam desain modern dan fungsional.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i5-1135G7, prosesor generasi ke-11 dengan arsitektur Intel Tiger Lake yang menawarkan kinerja efisien untuk multitasking dan tugas-tugas berat.</li><li><strong>RAM: </strong>8 GB DDR4, kapasitas memori yang cukup besar untuk menjalankan aplikasi secara lancar bahkan saat multitasking.</li><li><strong>Penyimpanan: </strong>SSD NVMe 512 GB, penyimpanan cepat yang memberikan akses data instan dan responsif.</li><li><strong>Layar: </strong>Layar 23.8 inci IPS Full HD (1920 x 1080 piksel), dengan teknologi IPS yang menyediakan sudut pandang lebar dan warna akurat untuk pengalaman visual yang optimal.</li><li><strong>Sistem Operasi: </strong>Windows 11 Home + OHS (OneHomeService), platform modern dengan fitur canggih dan dukungan layanan tambahan dari HP.</li><li><strong>Desain: </strong>Desain minimalis dan elegan, ideal untuk ruang kerja atau rumah yang mengutamakan estetika serta fungsionalitas.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i5-1135G7 yang handal untuk multitasking, pemrosesan data, dan tugas produktivitas tinggi.</li><li>Penyimpanan SSD NVMe 512 GB yang sangat cepat untuk meningkatkan waktu booting, loading aplikasi, dan transfer file.</li><li>Layar IPS FHD dengan ukuran 23.8 inci, memberikan ruang kerja yang luas dan pengalaman visual yang jernih.</li><li>Sistem operasi Windows 11 dengan dukungan OHS untuk pengalaman pengguna yang lebih baik.</li><li>Harga terjangkau dibandingkan dengan komputer all-in-one dengan spesifikasi serupa.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer all-in-one untuk aktivitas seperti belajar, bekerja, multimedia, dan hiburan.</li><li>Orang-orang yang mencari solusi komputasi hemat tempat namun tetap memiliki performa yang kuat.</li><li>Pemilik bisnis kecil atau profesional yang memerlukan komputer yang praktis dan fungsional untuk kebutuhan sehari-hari.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:10:26', '2025-06-27 13:34:24'),
(97, '95bfd006-539c-4198-89d9-0e56e731dd13', 'simple', 'PC HP 280 PRO G5 SFF  I3-10100', 'https://viviashop.com/', NULL, NULL, 'pc-hp-280-pro-g5-sff-i3-10100', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7447624685, 'Spesifikasi :\r\n RAM 4GB/ HDD 1TB/LCD HP P204V 19.5\"\r\n FHD/ Windows 10 Home', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP 280 Pro G5 SFF adalah komputer desktop berukuran kecil (Small Form Factor, SFF) yang dirancang untuk memberikan performa efisien dalam desain kompak dan fungsional.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i3-10100, prosesor generasi ke-10 dengan arsitektur Comet Lake yang menawarkan kinerja tangguh untuk multitasking dan tugas-tugas produktif.</li><li><strong>RAM: </strong>4 GB DDR4, kapasitas memori yang cukup besar untuk menjalankan aplikasi dasar hingga beberapa program secara bersamaan.</li><li><strong>Penyimpanan: </strong>HDD 1 TB, menyediakan ruang penyimpanan luas untuk file, data, dan aplikasi.</li><li><strong>Layar: </strong>Monitor HP P204V 19.5 inci Full HD (1920 x 1080 piksel), layar yang memberikan resolusi jernih dan sudut pandang lebar untuk pengalaman kerja yang nyaman.</li><li><strong>Sistem Operasi: </strong>Windows 10 Home, platform stabil dan teruji untuk produktivitas sehari-hari.</li><li><strong>Desain: </strong>Desain SFF (Small Form Factor) yang kompak, ideal untuk ruang kerja atau rumah yang terbatas.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i3-10100 yang handal untuk multitasking, pemrosesan data, dan tugas-tugas produktif.</li><li>Kapasitas RAM 4 GB yang cukup untuk menjalankan aplikasi bisnis dasar hingga beberapa program secara bersamaan.</li><li>Penyimpanan HDD 1 TB yang luas untuk mengakomodasi banyak file dan data.</li><li>Layar monitor HP P204V 19.5 inci FHD untuk pengalaman visual yang jernih saat bekerja atau presentasi.</li><li>Desain SFF hemat tempat tanpa mengorbankan performa.</li><li>Sistem operasi Windows 10 Home yang stabil dan mendukung banyak aplikasi umum.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer desktop untuk aktivitas seperti belajar, bekerja, multimedia, dan hiburan.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat tempat namun tetap fungsional.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:12:29', '2025-06-27 13:34:24'),
(98, 'd1260a45-d91e-42d5-8c1a-a6a3c65094f4', 'simple', 'PC HP PRODESK 400  G7 SFF', 'https://viviashop.com/', NULL, NULL, 'pc-hp-prodesk-400-g7-sff', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10500.00', '1.00', '1.00', '1.00', NULL, 5337951187, 'Spesifikasi :\r\n Processor i3-10100T/ RAM 4GB/\r\n HDD 1TB/ Windows 10 Home', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP ProDesk 400 G7 SFF adalah komputer desktop berukuran kecil (Small Form Factor, SFF) yang dirancang untuk memberikan performa efisien dalam desain kompak dan profesional.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i3-10100T, prosesor generasi ke-10 dengan arsitektur Comet Lake yang menawarkan kinerja tangguh namun lebih hemat daya dibandingkan versi standar.</li><li><strong>RAM: </strong>4 GB DDR4, kapasitas memori yang cukup besar untuk menjalankan aplikasi dasar hingga beberapa program secara bersamaan.</li><li><strong>Penyimpanan: </strong>HDD 1 TB, menyediakan ruang penyimpanan luas untuk file, data, dan aplikasi.</li><li><strong>Sistem Operasi: </strong>Windows 10 Home, platform stabil dan teruji untuk produktivitas sehari-hari.</li><li><strong>Desain: </strong>Desain SFF (Small Form Factor) yang kompak, ideal untuk ruang kerja atau rumah yang terbatas.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i3-10100T yang handal untuk multitasking, pemrosesan data, dan tugas-tugas produktif, serta lebih hemat daya.</li><li>Kapasitas RAM 4 GB yang cukup untuk menjalankan aplikasi bisnis dasar hingga beberapa program secara bersamaan.</li><li>Penyimpanan HDD 1 TB yang luas untuk mengakomodasi banyak file dan data.</li><li>Sistem operasi Windows 10 Home yang stabil dan mendukung banyak aplikasi umum.</li><li>Desain SFF hemat tempat tanpa mengorbankan performa.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer desktop untuk aktivitas seperti belajar, bekerja, multimedia, dan hiburan.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat tempat namun tetap fungsional.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:14:29', '2025-09-09 06:35:43'),
(99, '32123c5b-5a59-458f-8d93-b68a63857d3b', 'simple', 'HP 280 G6 PRO', 'https://viviashop.com/', NULL, NULL, 'hp-280-g6-pro', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 7988058892, 'Spesifikasi :\r\n Processor i3-10100/ RAM 4GB/ HDD 1 TB/\r\n WIN 10 HOME 64Bit', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP 280 G6 Pro adalah komputer desktop yang dirancang untuk memberikan performa efisien dalam desain sederhana dan fungsional.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i3-10100, prosesor generasi ke-10 dengan arsitektur Comet Lake yang menawarkan kinerja tangguh untuk multitasking dan tugas-tugas produktif.</li><li><strong>RAM: </strong>4 GB DDR4, kapasitas memori yang cukup besar untuk menjalankan aplikasi dasar hingga beberapa program secara bersamaan.</li><li><strong>Penyimpanan: </strong>HDD 1 TB, menyediakan ruang penyimpanan luas untuk file, data, dan aplikasi.</li><li><strong>Sistem Operasi: </strong>Windows 10 Home (64-bit), platform stabil dan teruji untuk produktivitas sehari-hari.</li><li><strong>Desain: </strong>Desain desktop standar yang sederhana, ideal untuk pengguna yang mengutamakan fungsionalitas tanpa memerlukan desain mewah.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i3-10100 yang handal untuk multitasking, pemrosesan data, dan tugas-tugas produktif.</li><li>Kapasitas RAM 4 GB yang cukup untuk menjalankan aplikasi bisnis dasar hingga beberapa program secara bersamaan.</li><li>Penyimpanan HDD 1 TB yang luas untuk mengakomodasi banyak file dan data.</li><li>Sistem operasi Windows 10 Home (64-bit) yang stabil dan mendukung banyak aplikasi umum.</li><li>Harga terjangkau dibandingkan dengan komputer desktop dengan spesifikasi serupa.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer desktop untuk aktivitas seperti belajar, bekerja, multimedia, dan hiburan.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat biaya namun tetap fungsional.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:16:48', '2025-06-27 13:34:24'),
(100, '8011c9d8-3da0-435b-a248-66afe8db9297', 'simple', 'HP 280 G6 PRO (Intel Core i5-10400)', 'https://viviashop.com/', NULL, NULL, 'hp-280-g6-pro-intel-core-i5-10400', '15000.00', NULL, 0, 0, '0.00', 0, 0, '10000.00', '1.00', '1.00', '1.00', NULL, 4522786548, 'Spesifikasi :\r\n Processor i5-10400/ RAM 8GB/ HDD 1 TB + 256 SSD/\r\n WIN 10 PRO 64Bit', '<p style=\"margin-left:0px;\"><strong>Produk: </strong>HP 280 G6 Pro adalah komputer desktop yang dirancang untuk memberikan performa tangguh dalam desain sederhana dan fungsional, dengan kombinasi penyimpanan HDD dan SSD.</p><p style=\"margin-left:0px;\"><strong>Spesifikasi Utama:</strong></p><ol><li><strong>Prosesor: </strong>Intel Core i5-10400, prosesor generasi ke-10 dengan arsitektur Comet Lake yang menawarkan kinerja canggih untuk multitasking dan tugas-tugas produktif.</li><li><strong>RAM: </strong>8 GB DDR4, kapasitas memori yang besar untuk menjalankan aplikasi secara lancar bahkan saat multitasking.</li><li><strong>Penyimpanan: </strong>Kombinasi HDD 1 TB dan SSD 256 GB, menyediakan ruang penyimpanan luas serta akses data yang cepat.<ul><li><strong>HDD 1 TB: </strong>Ruang penyimpanan besar untuk file, data, dan aplikasi.</li><li><strong>SSD 256 GB: </strong>Penyimpanan cepat untuk sistem operasi, aplikasi utama, dan file yang sering digunakan.</li></ul></li><li><strong>Sistem Operasi: </strong>Windows 10 Pro (64-bit), platform stabil dan teruji untuk produktivitas bisnis dengan fitur administratif tambahan.</li><li><strong>Desain: </strong>Desain desktop standar yang sederhana, ideal untuk pengguna yang mengutamakan fungsionalitas tanpa memerlukan desain mewah.</li></ol><p style=\"margin-left:0px;\"><strong>Keunggulan:</strong></p><ul><li>Prosesor Intel Core i5-10400 yang handal untuk multitasking, pemrosesan data, dan tugas-tugas produktif.</li><li>Kapasitas RAM 8 GB yang besar untuk menjalankan aplikasi berat secara lancar.</li><li>Kombinasi penyimpanan HDD 1 TB dan SSD 256 GB, memberikan keseimbangan antara kapasitas penyimpanan luas dan kecepatan akses data.</li><li>Sistem operasi Windows 10 Pro (64-bit) yang stabil dan mendukung banyak aplikasi bisnis.</li><li>Harga terjangkau dibandingkan dengan komputer desktop dengan spesifikasi serupa.</li></ul><p style=\"margin-left:0px;\"><strong>Target Pengguna:</strong></p><ul><li>Pengguna pribadi atau keluarga yang membutuhkan komputer desktop untuk aktivitas seperti belajar, bekerja, multimedia, dan hiburan.</li><li>Perusahaan atau institusi yang mencari solusi komputasi hemat biaya namun tetap fungsional.</li><li>Pemilik usaha kecil yang memerlukan komputer yang praktis untuk manajemen operasional sehari-hari dengan performa lebih tinggi dibandingkan model entry-level.</li></ul>', 1, 0, 1, NULL, NULL, '2025-05-10 15:18:49', '2025-06-27 13:34:24'),
(101, 'b603e6da-2d9b-41f9-88f3-98129a4e9eea', 'simple', 'DUMMY PRODUCT', 'https://viviashop.com/', NULL, NULL, 'dummy-product', '100.00', NULL, 0, 0, '0.00', 0, 0, '100.00', '100.00', '10.00', '20.00', NULL, 9659871106, 'Buat Dummy', '<p>Buat Dummy</p>', 1, 0, 1, NULL, NULL, '2025-05-10 18:06:46', '2025-08-12 14:10:25'),
(102, 'DUMMY 2', 'simple', 'DUMMY 2', NULL, NULL, NULL, 'dummy-2', '30.00', NULL, 0, 0, '0.00', 0, 0, '20.00', NULL, NULL, NULL, NULL, 2175871393, 'BUAT DUMMY 2', 'BUAT DUMYY 2', 1, 0, 1, NULL, NULL, '2025-06-30 13:03:18', '2025-07-26 12:15:05'),
(111, 'fjfhjhfjshf6888686', 'simple', 'DUMMY 3', NULL, NULL, NULL, 'dummy-3', '20.00', NULL, 0, 0, '0.00', 0, 0, '10.00', NULL, NULL, NULL, NULL, 7599518708, 'DUMMY', 'DUMMY 3', 1, 0, 1, NULL, NULL, '2025-06-30 14:07:09', '2025-06-30 14:07:09'),
(112, 'kfshfkshfks7254725', 'simple', 'DUMMY 4', NULL, NULL, NULL, 'dummy-4', '35.00', NULL, 0, 0, '0.00', 0, 0, '10.00', NULL, NULL, NULL, NULL, 4862391258, 'DUMMY', 'DUMMY 4', 1, 0, 1, NULL, NULL, '2025-06-30 14:09:11', '2025-06-30 14:09:11'),
(113, 'fsfshfsjhfjsh92749274', 'simple', 'DUMMY 5', NULL, NULL, NULL, 'dummy-5', '15.00', NULL, 0, 0, '0.00', 0, 0, '14.00', '1000.00', '10.00', '10.00', '10.00', 4527472899, 'DUMMY 5', 'DUMMY 5', 1, 0, 1, NULL, NULL, '2025-06-30 14:14:19', '2025-07-08 14:05:43'),
(114, '1313186', 'simple', 'DUMMY 6', NULL, NULL, NULL, 'dummy-6', '20.00', NULL, 0, 0, '0.00', 0, 0, '18.00', '10.00', '10.00', '10.00', '10.00', 6035007415, 'DUMMY 6', 'DUMMY 6', 1, 0, 1, NULL, NULL, '2025-07-04 18:28:02', '2025-07-08 14:05:46'),
(115, '163516351', 'simple', 'Dummy New', 'viviashop.com', NULL, NULL, 'dummy-new', '10.00', NULL, 0, 0, '0.00', 0, 0, '8.00', '1.00', '1.00', '1.00', '1.00', 2177115087, 'Deskripsi', '<p>Deskripsi</p>', 1, 0, 1, NULL, NULL, '2025-07-26 11:54:40', '2025-07-26 12:15:13'),
(116, 'sku-123', 'simple', 'dummy barcode', 'viviashop.com', 'viviashop.com', 'viviashop.com', 'dummy-barcode', '10.00', NULL, 0, 0, '0.00', 0, 0, '8.00', '10.00', '10.00', '10.00', '10.00', 3467504648, 'ajsifoiafnao', '<p>deskripsi</p>', 1, 0, 1, NULL, NULL, '2025-07-26 11:59:10', '2025-07-26 12:15:10'),
(117, 'sku-1233', 'configurable', 'Dummy Product2', 'viviashop.com', 'viviashop.com', 'viviashop.com', 'dummy-product2', '6.00', NULL, 0, 0, '0.00', 0, 0, '5.00', '1.00', '100.00', '100.00', '100.00', 5904345369, 'deskripsi ocb', '<p>cobadeskr</p>', 1, 0, 1, NULL, NULL, '2025-07-26 12:01:04', '2025-08-14 14:56:48'),
(118, 'simple', 'simple', 'simple', 'viviashop.com', 'viviashop.com', 'viviashop.com', 'simple', '10.00', NULL, 0, 0, '0.00', 0, 0, '8.00', '10.00', '10.00', '10.00', '10.00', 6035499505, 'iso', '<p>iso</p>', 1, 0, 1, NULL, NULL, '2025-07-26 12:10:17', '2025-07-26 12:10:40'),
(119, 'cobagawe', 'simple', 'Baru Lagi', 'viviashop.com', 'viviashop.com', 'viviashop.com', 'baru-lagi', '20.00', NULL, 0, 0, '0.00', 0, 0, '8.00', '10.00', '10.00', '10.00', '10.00', 7644930288, 'viviashop.com', '<p>viviashop.com</p>', 1, 0, 1, NULL, NULL, '2025-07-26 12:15:57', '2025-07-26 12:16:17'),
(120, 'barulagi', 'simple', 'Ujicoba', 'viviashop.com/', NULL, NULL, 'ujicoba', '10.00', NULL, 0, 0, '0.00', 0, 0, '8.00', '10.00', '10.00', '10.00', '10.00', 4453754967, 'deskripsi', '<p>deskripsi</p>', 1, 0, 1, NULL, NULL, '2025-07-26 12:23:13', '2025-07-26 12:23:55'),
(121, '93797131', 'simple', 'DUMMY LAGE', NULL, NULL, NULL, 'dummy-lage', NULL, NULL, 0, 0, '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, 9452739849, NULL, NULL, NULL, 0, 1, NULL, NULL, '2025-08-06 13:11:23', '2025-08-06 13:11:23'),
(123, 'sku-1233-1-null', 'simple', 'Dummy Product2 - HVS 70Gr', 'viviashop.com', 'viviashop.com', 'viviashop.com', 'dummy-product2-hvs-70gr', '6.00', NULL, 0, 0, '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, 7920825782, NULL, NULL, NULL, 0, 1, NULL, 117, '2025-08-14 15:59:50', '2025-09-08 05:02:39'),
(124, '731757551', 'configurable', 'Kertas Dummy', 'https://viviashop.com/', NULL, NULL, 'kertas-dummy', NULL, NULL, 0, 0, '0.00', 0, 0, NULL, '1.00', NULL, NULL, NULL, 9682765838, 'coba-coba multi kategori', NULL, 1, 0, 1, NULL, NULL, '2025-09-06 03:03:35', '2025-09-06 03:11:07'),
(128, '731757551-2-null', 'simple', 'Kertas Dummy - HVS 80Gr', 'https://viviashop.com/', NULL, NULL, 'kertas-dummy-hvs-80gr', '3.00', NULL, 0, 0, '0.00', 0, 0, '1.00', '1.00', '1.00', '1.00', '1.00', 8417618644, 'coba-coba multiple', NULL, 1, 0, 1, NULL, 124, '2025-09-06 03:19:56', '2025-09-08 05:02:39');
INSERT INTO `products` (`id`, `sku`, `type`, `name`, `link1`, `link2`, `link3`, `slug`, `price`, `base_price`, `total_stock`, `sold_count`, `rating`, `is_featured`, `is_print_service`, `harga_beli`, `weight`, `length`, `width`, `height`, `barcode`, `short_description`, `description`, `status`, `is_smart_print_enabled`, `user_id`, `brand_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(129, 'HVS-001', 'configurable', 'Kertas HVS', NULL, NULL, NULL, 'kertas-hvs', NULL, '42000.00', 435, 0, '0.00', 1, 0, NULL, '0.50', NULL, NULL, NULL, 3944670008, 'Kertas HVS berkualitas tinggi untuk kebutuhan kantor dan sekolah.', 'Kertas HVS dengan berbagai varian brand, ukuran, dan gramatur. Tersedia brand APP, Sinar Dunia dengan ukuran A4, F4 dan gramatur 70gr, 80gr.', 1, 0, 1, 1, NULL, '2025-09-06 03:57:31', '2025-09-08 05:02:39'),
(130, 'PILOT-001', 'simple', 'Pulpen Pilot', NULL, NULL, NULL, 'pulpen-pilot', '5000.00', '5000.00', 0, 0, '0.00', 0, 0, NULL, '0.02', NULL, NULL, NULL, 3979872271, 'Pulpen Pilot warna biru, tinta lancar.', 'Pulpen berkualitas dari Pilot dengan tinta yang lancar dan awet.', 1, 0, 1, 6, NULL, '2025-09-06 03:57:31', '2025-09-08 05:02:39'),
(131, '242553', 'configurable', 'Kertas Baru', NULL, NULL, NULL, 'kertas-baru', NULL, '0.00', 0, 0, '0.00', 0, 0, NULL, '1.00', NULL, NULL, NULL, 3946774839, NULL, NULL, 1, 0, 1, NULL, NULL, '2025-09-06 04:10:13', '2025-09-08 05:02:39'),
(132, '8578758798', 'configurable', 'Kertas Baru', NULL, NULL, NULL, 'kertas-baru-2', NULL, '0.00', 0, 0, '0.00', 0, 0, NULL, '1.00', NULL, NULL, NULL, 1244499694, NULL, NULL, 1, 0, 1, NULL, NULL, '2025-09-06 04:14:25', '2025-09-08 05:02:39'),
(133, '9787080', 'configurable', 'Kertas Baru Lagi', 'https://viviashop.com/', NULL, NULL, 'kertas-baru-lagi', '2.00', '2.00', 101, 0, '0.00', 0, 0, '1.00', '1.00', '1.00', '1.00', '1.00', 3172558906, 'b aja', NULL, 1, 0, 1, NULL, NULL, '2025-09-06 04:25:31', '2025-09-08 04:51:58'),
(134, 'BAJUPRIA9115', 'configurable', 'Baju Pria Lengan Panjang 2', NULL, NULL, NULL, 'baju-pria-lengan-panjang-2', '150000.00', '140135.00', 713, 0, '0.00', 0, 0, NULL, '500.00', NULL, NULL, NULL, 8601571766, NULL, 'Baju pria lengan panjang berkualitas tinggi dengan berbagai variasi warna dan ukuran', 1, 0, 1, NULL, NULL, '2025-09-06 10:59:10', '2025-09-14 07:19:46'),
(135, 'PRINT-HVS-001', 'configurable', 'Kertas HVS - Layanan Cetak', NULL, NULL, NULL, 'kertas-hvs-layanan-cetak', NULL, '2.00', 71972, 0, '0.00', 1, 1, NULL, '0.01', NULL, NULL, NULL, 9806845264, 'Layanan cetak dokumen pada kertas HVS berkualitas tinggi.', 'Layanan cetak profesional dengan pilihan kertas HVS berbagai ukuran dan jenis cetak (hitam putih atau berwarna). Cocok untuk dokumen kantor, tugas sekolah, presentasi, dan kebutuhan cetak lainnya.', 1, 1, 1, 8, NULL, '2025-09-11 04:02:27', '2025-09-16 02:11:48'),
(136, 'PRINT-HVS-001', 'configurable', 'Kertas HVS - Layanan Cetak', NULL, NULL, NULL, 'kertas-hvs-layanan-cetak-2', NULL, '500.00', 26500, 0, '0.00', 1, 0, NULL, '0.01', NULL, NULL, NULL, 9197167766, 'Layanan cetak dokumen pada kertas HVS berkualitas tinggi.', 'Layanan cetak profesional dengan pilihan kertas HVS berbagai ukuran dan jenis cetak (hitam putih atau berwarna). Cocok untuk dokumen kantor, tugas sekolah, presentasi, dan kebutuhan cetak lainnya.', 1, 0, 1, 8, NULL, '2025-09-11 04:09:59', '2025-09-16 02:11:48'),
(137, 'PRINT-HVS-001', 'configurable', 'Kertas HVS - Layanan Cetak', NULL, NULL, NULL, 'kertas-hvs-layanan-cetak-3', NULL, '500.00', 26500, 0, '0.00', 1, 1, NULL, '0.01', NULL, NULL, NULL, 3459184217, 'Layanan cetak dokumen pada kertas HVS berkualitas tinggi.', 'Layanan cetak profesional dengan pilihan kertas HVS berbagai ukuran dan jenis cetak (hitam putih atau berwarna). Cocok untuk dokumen kantor, tugas sekolah, presentasi, dan kebutuhan cetak lainnya.', 0, 1, 1, 8, NULL, '2025-09-11 04:10:55', '2025-09-16 02:11:48'),
(138, 'SFWSFRW1313', 'simple', 'RAKET PADEL', NULL, NULL, NULL, 'raket-padel', '2.00', '0.00', 100, 0, '0.00', 0, 0, '1.00', '1.00', '10.00', '10.00', '10.00', 2524460405, 'Ini raket padel', NULL, 1, 0, 190, NULL, NULL, '2025-09-14 14:16:16', '2025-09-15 13:27:36'),
(139, 'SP-A4-1757958820', 'configurable', 'Smart Print Paper A4', NULL, NULL, NULL, 'smart-print-paper-a4', NULL, NULL, 0, 0, '0.00', 0, 1, NULL, '0.10', NULL, NULL, NULL, 9014513488, 'High quality A4 paper for smart printing', NULL, 1, 1, 1, NULL, NULL, '2025-09-15 17:53:40', '2025-09-16 02:11:48'),
(140, 'REG-1757958820', 'simple', 'Regular Product', NULL, NULL, NULL, 'regular-product', NULL, NULL, 0, 0, '0.00', 0, 0, NULL, '0.50', NULL, NULL, NULL, 5113353783, 'Regular product not for smart print', NULL, 1, 0, 1, NULL, NULL, '2025-09-15 17:53:40', '2025-09-16 02:11:48'),
(141, 'TSP-1757958888', 'simple', 'Test Smart Print Product', NULL, NULL, NULL, 'test-smart-print-product', '5000.00', NULL, 0, 0, '0.00', 0, 1, '4000.00', '0.20', NULL, NULL, NULL, 3314326367, 'Test product for smart print validation', NULL, 1, 1, 1, NULL, NULL, '2025-09-15 17:54:48', '2025-09-16 02:11:48'),
(142, 'TRP-1757958888', 'simple', 'Test Regular Product', NULL, NULL, NULL, 'test-regular-product', '3000.00', NULL, 0, 0, '0.00', 0, 1, '2500.00', '0.30', NULL, NULL, NULL, 3239409704, 'Regular product without smart print', NULL, 1, 1, 1, NULL, NULL, '2025-09-15 17:54:48', '2025-09-16 02:11:48'),
(143, 'SPCP-1757958888', 'configurable', 'Smart Print Configurable Product', NULL, NULL, NULL, 'smart-print-configurable-product', NULL, NULL, 0, 0, '0.00', 0, 1, NULL, '0.10', NULL, NULL, NULL, 1492065044, 'Configurable product with smart print', NULL, 1, 1, 1, NULL, NULL, '2025-09-15 17:54:48', '2025-09-16 02:11:48'),
(144, 'sgdhdhfh', 'simple', 'KERTAS A10', NULL, NULL, NULL, 'kertas-a10', '2.00', '0.00', 0, 0, '0.00', 0, 1, '1.00', '1.00', '1.00', '1.00', '1.00', 2911595975, 'ini buat tes aja yaa', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 17:56:05', '2025-09-16 02:11:48'),
(145, 'afhjsfhsjffs', 'configurable', 'Kertas A9', NULL, NULL, NULL, 'kertas-a9', NULL, '0.00', 0, 0, '0.00', 0, 0, NULL, '1.00', NULL, NULL, NULL, 5954142228, NULL, NULL, 1, 0, 190, NULL, NULL, '2025-09-15 18:09:10', '2025-09-16 02:11:48'),
(149, '87853jhjhjfd', 'simple', 'KERTAS DINO', NULL, NULL, NULL, 'kertas-dino', '2.00', '0.00', 0, 0, '0.00', 0, 1, '1.00', '1.00', '1.00', '1.00', '1.00', 5176672164, 'aowkwkwkwk', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 22:00:22', '2025-09-16 02:11:48'),
(151, 'dsfsfrete586879', 'configurable', 'Kertas Buffalo', NULL, NULL, NULL, 'kertas-buffalo', '2.00', '0.00', 0, 0, '0.00', 0, 1, '1.00', '1.00', '1.00', '1.00', '1.00', 1550586117, 'awowkwkwkk', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 22:11:18', '2025-09-16 02:11:48'),
(153, 'TEST-CHECKBOX-1757974852', 'simple', 'Test Kertas Checkbox Fix', NULL, NULL, NULL, 'test-kertas-checkbox-fix', '5000.00', NULL, 0, 0, '0.00', 0, 1, NULL, NULL, NULL, NULL, NULL, 6508042044, NULL, 'Produk test untuk verifikasi fix checkbox', 1, 1, 1, 1, NULL, '2025-09-15 22:20:52', '2025-09-16 02:11:48'),
(154, 'test-kertas-checkbox-fix-2-bw', 'variant', 'Test Kertas Checkbox Fix - BW', NULL, NULL, NULL, 'test-kertas-checkbox-fix-bw', '5000.00', NULL, 0, 0, '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, 1895074409, NULL, NULL, 1, 0, 1, 1, 153, '2025-09-15 22:20:52', '2025-09-16 02:11:48'),
(155, 'test-kertas-checkbox-fix-2-color', 'variant', 'Test Kertas Checkbox Fix - Color', NULL, NULL, NULL, 'test-kertas-checkbox-fix-color', '7500.00', NULL, 0, 0, '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, 5009232720, NULL, NULL, 1, 0, 1, 1, 153, '2025-09-15 22:20:52', '2025-09-16 02:11:48'),
(158, 'adafs6465757', 'simple', 'Kertas Glossy', NULL, NULL, NULL, 'kertas-glossy', NULL, '0.00', 0, 0, '0.00', 0, 1, NULL, '1.00', NULL, NULL, NULL, 9462577497, NULL, NULL, 1, 1, 190, NULL, NULL, '2025-09-15 22:29:11', '2025-09-16 02:11:48'),
(160, 'jhdjhsajd868632', 'simple', 'Kertas Foto', NULL, NULL, NULL, 'kertas-foto', '2.00', '0.00', 0, 0, '0.00', 0, 1, '1.00', '1.00', '1.00', '1.00', '1.00', 9059260274, 'aowkwkwk', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 22:36:20', '2025-09-16 02:11:48'),
(165, 'kadhkah838163', 'simple', 'Kertas Ajaib', NULL, NULL, NULL, 'kertas-ajaib', '2.00', '0.00', 0, 0, '0.00', 0, 1, '1.00', '1.00', '1.00', '1.00', '1.00', 6630743162, 'aowkwkwkwkw', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 22:43:19', '2025-09-16 02:11:48'),
(166, 'fskfsfshkh', 'simple', 'Kertas Minyak', NULL, NULL, NULL, 'kertas-minyak', '2.00', '0.00', 0, 0, '0.00', 0, 1, '1.00', '1.00', '1.00', '1.00', '1.00', 4598204722, 'oeioreowruowr', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 22:54:15', '2025-09-16 02:11:48'),
(167, 'dakhdakh7070698', 'configurable', 'Kertas Padang', NULL, NULL, NULL, 'kertas-padang', NULL, '2.00', 200, 0, '0.00', 0, 1, NULL, '1.00', NULL, NULL, NULL, 9189165328, 'aowkwkwkw', NULL, 1, 1, 190, NULL, NULL, '2025-09-15 23:02:03', '2025-09-16 02:11:48'),
(168, '87959rufnc', 'configurable', 'Kertas Ya Allah', NULL, NULL, NULL, 'kertas-ya-allah', NULL, '2.00', 200, 0, '0.00', 0, 1, NULL, '1.00', NULL, NULL, NULL, 6134969452, NULL, NULL, 1, 1, 190, NULL, NULL, '2025-09-15 23:46:27', '2025-09-16 02:11:48'),
(169, 'TEST-BARCODE-1757988727', 'simple', 'Test Product for Barcode Generation', NULL, NULL, NULL, 'test-product-for-barcode-generation', '10000.00', NULL, 0, 0, '0.00', 0, 0, '8000.00', NULL, NULL, NULL, NULL, 8250374074, NULL, 'Test product to verify barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:12:07', '2025-09-16 02:12:07'),
(170, 'FINAL-TEST-1757988810', 'simple', 'Final Test Product', NULL, NULL, NULL, 'final-test-product', '15000.00', NULL, 0, 0, '0.00', 0, 0, '12000.00', NULL, NULL, NULL, NULL, 6816884498, NULL, 'Final test product for barcode testing', 1, 0, 1, NULL, NULL, '2025-09-16 02:13:30', '2025-09-16 02:13:30'),
(171, 'BTN-TEST-1757988871-1', 'simple', 'Button Test Product #1', NULL, NULL, NULL, 'button-test-product-1', '11000.00', NULL, 0, 0, '0.00', 0, 0, '8800.00', NULL, NULL, NULL, NULL, 7167078981, NULL, 'Test product for button click simulation', 1, 0, 1, NULL, NULL, '2025-09-16 02:14:31', '2025-09-16 02:14:31'),
(172, 'BTN-TEST-1757988871-2', 'simple', 'Button Test Product #2', NULL, NULL, NULL, 'button-test-product-2', '12000.00', NULL, 0, 0, '0.00', 0, 0, '9600.00', NULL, NULL, NULL, NULL, 1632777892, NULL, 'Test product for button click simulation', 1, 0, 1, NULL, NULL, '2025-09-16 02:14:31', '2025-09-16 02:14:31'),
(173, 'BTN-TEST-1757988871-3', 'simple', 'Button Test Product #3', NULL, NULL, NULL, 'button-test-product-3', '13000.00', NULL, 0, 0, '0.00', 0, 0, '10400.00', NULL, NULL, NULL, NULL, 7742209469, NULL, 'Test product for button click simulation', 1, 0, 1, NULL, NULL, '2025-09-16 02:14:31', '2025-09-16 02:14:31'),
(174, 'BTN-TEST-1757988871-4', 'simple', 'Button Test Product #4', NULL, NULL, NULL, 'button-test-product-4', '14000.00', NULL, 0, 0, '0.00', 0, 0, '11200.00', NULL, NULL, NULL, NULL, 9797875285, NULL, 'Test product for button click simulation', 1, 0, 1, NULL, NULL, '2025-09-16 02:14:31', '2025-09-16 02:14:31'),
(175, 'BTN-TEST-1757988871-5', 'simple', 'Button Test Product #5', NULL, NULL, NULL, 'button-test-product-5', '15000.00', NULL, 0, 0, '0.00', 0, 0, '12000.00', NULL, NULL, NULL, NULL, 4645919047, NULL, 'Test product for button click simulation', 1, 0, 1, NULL, NULL, '2025-09-16 02:14:31', '2025-09-16 02:14:31'),
(176, 'REDIRECT-TEST-1757989056', 'simple', 'Redirect Test Product', NULL, NULL, NULL, 'redirect-test-product', '25000.00', NULL, 0, 0, '0.00', 0, 0, '20000.00', NULL, NULL, NULL, NULL, 3533531292, NULL, 'Test product for redirect functionality', 1, 0, 1, NULL, NULL, '2025-09-16 02:17:36', '2025-09-16 02:17:36'),
(177, 'LIVE-TEST-1757989098', 'simple', 'Live Test Product', NULL, NULL, NULL, 'live-test-product', '30000.00', NULL, 0, 0, '0.00', 0, 0, '25000.00', NULL, NULL, NULL, NULL, 3577771926, NULL, 'Product for live route testing', 1, 0, 1, NULL, NULL, '2025-09-16 02:18:18', '2025-09-16 02:19:21'),
(178, 'FINAL-1757989229-1', 'simple', 'Final Test Product #1', NULL, NULL, NULL, 'final-test-product-1', '11000.00', NULL, 0, 0, '0.00', 0, 0, '8800.00', NULL, NULL, NULL, NULL, 4916042813, NULL, 'Final test product #1', 1, 0, 1, NULL, NULL, '2025-09-16 02:20:29', '2025-09-16 02:25:02'),
(179, 'FINAL-1757989229-2', 'simple', 'Final Test Product #2', NULL, NULL, NULL, 'final-test-product-2', '12000.00', NULL, 0, 0, '0.00', 0, 0, '9600.00', NULL, NULL, NULL, NULL, 3077987080, NULL, 'Final test product #2', 1, 0, 1, NULL, NULL, '2025-09-16 02:20:29', '2025-09-16 02:25:02'),
(180, 'FINAL-1757989229-3', 'simple', 'Final Test Product #3', NULL, NULL, NULL, 'final-test-product-3', '13000.00', NULL, 0, 0, '0.00', 0, 0, '10400.00', NULL, NULL, NULL, NULL, 8449644326, NULL, 'Final test product #3', 1, 0, 1, NULL, NULL, '2025-09-16 02:20:29', '2025-09-16 02:25:02'),
(181, 'DKFSJFHS72424', 'simple', 'BAMBU RUNCING', NULL, NULL, NULL, 'bambu-runcing', '2.00', '0.00', 0, 0, '0.00', 0, 0, '1.00', '1.00', '1.00', '1.00', '1.00', 1386981998, 'ADUDUDUDU', NULL, 1, 0, 190, NULL, NULL, '2025-09-16 02:21:57', '2025-09-16 02:25:02'),
(182, 'ADMIN-TEST-1757989638-1', 'simple', 'Admin Test Product #1', NULL, NULL, NULL, 'admin-test-product-1', '16000.00', NULL, 0, 0, '0.00', 0, 0, '12800.00', NULL, NULL, NULL, NULL, 6459839143, NULL, 'Test product for admin barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:27:18', '2025-09-16 02:27:47'),
(183, 'ADMIN-TEST-1757989638-2', 'simple', 'Admin Test Product #2', NULL, NULL, NULL, 'admin-test-product-2', '17000.00', NULL, 0, 0, '0.00', 0, 0, '13600.00', NULL, NULL, NULL, NULL, 1397044237, NULL, 'Test product for admin barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:27:18', '2025-09-16 02:27:47'),
(184, 'ADMIN-TEST-1757989638-3', 'simple', 'Admin Test Product #3', NULL, NULL, NULL, 'admin-test-product-3', '18000.00', NULL, 0, 0, '0.00', 0, 0, '14400.00', NULL, NULL, NULL, NULL, 4489163024, NULL, 'Test product for admin barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:27:18', '2025-09-16 02:27:47'),
(185, 'FINAL-BARCODE-1757989755-1', 'simple', 'Final Test Product #1', NULL, NULL, NULL, 'final-test-product-1-2', '22000.00', NULL, 0, 0, '0.00', 0, 0, '16500.00', NULL, NULL, NULL, NULL, 7938883283, NULL, 'Final test product #1 for barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:29:15', '2025-09-16 02:32:22'),
(186, 'FINAL-BARCODE-1757989755-2', 'simple', 'Final Test Product #2', NULL, NULL, NULL, 'final-test-product-2-2', '24000.00', NULL, 0, 0, '0.00', 0, 0, '18000.00', NULL, NULL, NULL, NULL, 2598996456, NULL, 'Final test product #2 for barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:29:15', '2025-09-16 02:32:22'),
(187, 'FINAL-BARCODE-1757989755-3', 'simple', 'Final Test Product #3', NULL, NULL, NULL, 'final-test-product-3-2', '26000.00', NULL, 0, 0, '0.00', 0, 0, '19500.00', NULL, NULL, NULL, NULL, 6620057062, NULL, 'Final test product #3 for barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:29:15', '2025-09-16 02:32:22'),
(188, 'FINAL-BARCODE-1757989755-4', 'simple', 'Final Test Product #4', NULL, NULL, NULL, 'final-test-product-4', '28000.00', NULL, 0, 0, '0.00', 0, 0, '21000.00', NULL, NULL, NULL, NULL, 9809782075, NULL, 'Final test product #4 for barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:29:15', '2025-09-16 02:32:22'),
(189, 'FINAL-BARCODE-1757989755-5', 'simple', 'Final Test Product #5', NULL, NULL, NULL, 'final-test-product-5', '30000.00', NULL, 0, 0, '0.00', 0, 0, '22500.00', NULL, NULL, NULL, NULL, 6054584913, NULL, 'Final test product #5 for barcode generation', 1, 0, 1, NULL, NULL, '2025-09-16 02:29:15', '2025-09-16 02:32:22'),
(190, 'ADAD654432', 'simple', 'BOLA BASKET', NULL, NULL, NULL, 'bola-basket', '2.00', '0.00', 0, 0, '0.00', 0, 0, '1.00', '1.00', '1.00', '1.00', '1.00', 8285064160, 'WADUH', NULL, 1, 0, 190, NULL, NULL, '2025-09-16 02:31:59', '2025-09-16 02:32:22'),
(192, 'ASFSJFSJ4254242', 'simple', 'SEPATU BRODO', NULL, NULL, NULL, 'sepatu-brodo', '2.00', '0.00', 0, 0, '0.00', 0, 0, '1.00', '1.00', '1.00', '1.00', '1.00', 5108608734, 'sepatu brodo', '<ol><li>Sepatu brodo berkualitas</li><li>nyaman</li><li>enak</li><li>MANTAP</li></ol>', 1, 0, 190, NULL, NULL, '2025-09-16 02:46:12', '2025-09-16 02:46:50'),
(193, 'DAKHDAIDH63186414', 'configurable', 'Kucing', NULL, NULL, NULL, 'kucing', NULL, '2.00', 200, 0, '0.00', 0, 0, NULL, '2.00', NULL, NULL, NULL, NULL, 'awas kucing garong', '<p>ya macem-macem lah.</p><ol><li>kucing garong</li><li>anggora</li><li>oren</li><li>persia</li></ol>', 1, 0, 190, NULL, NULL, '2025-09-16 03:00:41', '2025-09-16 03:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint UNSIGNED NOT NULL,
  `text_value` text COLLATE utf8mb4_unicode_ci,
  `boolean_value` tinyint(1) DEFAULT NULL,
  `integer_value` int DEFAULT NULL,
  `float_value` decimal(8,2) DEFAULT NULL,
  `datetime_value` datetime DEFAULT NULL,
  `date_value` date DEFAULT NULL,
  `json_value` text COLLATE utf8mb4_unicode_ci,
  `parent_product_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `text_value`, `boolean_value`, `integer_value`, `float_value`, `datetime_value`, `date_value`, `json_value`, `parent_product_id`, `product_id`, `attribute_id`, `created_at`, `updated_at`) VALUES
(2, 'HVS 70Gr', NULL, NULL, NULL, NULL, NULL, NULL, 117, 123, 1, '2025-08-14 15:59:50', '2025-08-14 15:59:50'),
(6, 'HVS 80Gr', NULL, NULL, NULL, NULL, NULL, NULL, 124, 128, 1, '2025-09-06 03:19:56', '2025-09-06 03:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 8),
(7, 7, 8),
(8, 8, 8),
(9, 9, 3),
(10, 10, 9),
(11, 11, 9),
(12, 12, 3),
(13, 13, 3),
(14, 14, 3),
(15, 15, 3),
(16, 16, 3),
(17, 17, 9),
(18, 18, 3),
(19, 19, 3),
(20, 20, 3),
(21, 21, 3),
(22, 22, 3),
(23, 23, 3),
(24, 24, 3),
(25, 25, 3),
(26, 26, 3),
(27, 27, 3),
(28, 28, 3),
(29, 29, 3),
(30, 30, 3),
(31, 31, 3),
(32, 32, 3),
(33, 33, 3),
(34, 34, 3),
(35, 35, 3),
(36, 36, 3),
(37, 37, 3),
(38, 38, 3),
(39, 39, 3),
(40, 40, 3),
(41, 41, 3),
(42, 42, 3),
(43, 43, 3),
(44, 44, 3),
(45, 45, 2),
(46, 46, 2),
(47, 47, 2),
(48, 48, 20),
(49, 49, 20),
(50, 50, 20),
(51, 51, 3),
(52, 52, 3),
(53, 53, 3),
(54, 54, 3),
(55, 55, 3),
(56, 56, 3),
(57, 57, 3),
(58, 58, 3),
(59, 59, 3),
(60, 60, 3),
(61, 61, 3),
(62, 62, 3),
(63, 63, 3),
(64, 64, 3),
(65, 65, 3),
(66, 66, 3),
(67, 67, 3),
(68, 68, 3),
(69, 69, 3),
(70, 70, 3),
(71, 71, 21),
(72, 72, 21),
(73, 73, 21),
(74, 74, 21),
(75, 75, 21),
(76, 76, 21),
(77, 77, 21),
(78, 78, 21),
(79, 79, 21),
(80, 80, 21),
(81, 81, 21),
(82, 82, 21),
(83, 83, 21),
(84, 84, 21),
(85, 85, 21),
(86, 86, 21),
(87, 87, 21),
(88, 88, 21),
(89, 89, 21),
(90, 90, 21),
(91, 91, 21),
(92, 92, 21),
(93, 93, 21),
(94, 94, 21),
(95, 95, 21),
(96, 96, 21),
(97, 97, 21),
(98, 98, 21),
(99, 99, 21),
(100, 100, 21),
(101, 101, 22),
(102, 102, 22),
(111, 111, 22),
(112, 112, 22),
(113, 113, 22),
(114, 114, 22),
(115, 115, 22),
(116, 116, 22),
(117, 117, 22),
(118, 118, 22),
(119, 119, 22),
(120, 120, 22),
(121, 121, 22),
(123, 123, 22),
(124, 124, 22),
(128, 128, 22),
(129, 129, 1),
(130, 130, 1),
(131, 131, 22),
(132, 132, 22),
(133, 133, 22),
(134, 134, 1),
(135, 135, 1),
(136, 136, 1),
(137, 137, 1),
(138, 138, 22),
(139, 139, 1),
(140, 141, 1),
(141, 142, 1),
(142, 143, 1),
(143, 144, 22),
(144, 145, 1),
(145, 149, 1),
(146, 151, 22),
(148, 153, 3),
(151, 158, 4),
(153, 160, 1),
(157, 165, 4),
(158, 166, 4),
(159, 167, 4),
(160, 168, 4),
(161, 181, 3),
(162, 190, 3),
(163, 192, 3),
(164, 193, 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_large` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `large` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medium` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `small` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `path`, `extra_large`, `large`, `medium`, `small`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 'product/images/BvQDkRDNDLHzK5zdc9ScDhM7Pkki3SNlZ8Bk4Ihp.png', NULL, NULL, NULL, NULL, 3, '2025-05-10 10:56:43', '2025-05-10 10:56:43'),
(2, 'product/images/ge5EMZlQftSjKKJAa4PF5ypcj7FRNxidziKvttXz.png', NULL, NULL, NULL, NULL, 4, '2025-05-10 11:07:58', '2025-05-10 11:07:58'),
(3, 'product/images/qffpunfDOYE2omaVqVGNQGDk156QM94EEAE1I469.png', NULL, NULL, NULL, NULL, 5, '2025-05-10 11:10:15', '2025-05-10 11:10:15'),
(4, 'product/images/E3KnwrGPTFlBL0z5yGisKhelcUbak42dPVh9sGFH.png', NULL, NULL, NULL, NULL, 6, '2025-05-10 11:17:20', '2025-05-10 11:17:20'),
(5, 'product/images/gKQi4FdzFnhi4V5WrwhLk6uBEqwePIgPFz60sXZX.png', NULL, NULL, NULL, NULL, 7, '2025-05-10 11:23:56', '2025-05-10 11:23:56'),
(6, 'product/images/1nxnbbt0dkYdsxTqzaX1F4Ft437lOaFnHm678M7O.png', NULL, NULL, NULL, NULL, 8, '2025-05-10 11:45:13', '2025-05-10 11:45:13'),
(7, 'product/images/VjoITdPH0wrUKyZ871TTm38E7j6XhJO26Qq56Hsi.png', NULL, NULL, NULL, NULL, 9, '2025-05-10 11:47:19', '2025-05-10 11:47:19'),
(8, 'product/images/xCyQHonMnNvRma8fytAn498hzebY8PpbNXXfPhor.png', NULL, NULL, NULL, NULL, 10, '2025-05-10 11:50:52', '2025-05-10 11:50:52'),
(9, 'product/images/jWbHSC7e7pS5ppMdMu8Eg3K0SC1RcvC4UVA0c9mq.png', NULL, NULL, NULL, NULL, 11, '2025-05-10 11:53:18', '2025-05-10 11:53:18'),
(10, 'product/images/5MHMX89eAff9UDeAUYRspsPsaABKZkN1H5mb4fp2.png', NULL, NULL, NULL, NULL, 12, '2025-05-10 11:56:37', '2025-05-10 11:56:37'),
(11, 'product/images/cIonkPIz4Uixa7GObP16YFpJqWL33AiPW5P1EBAT.png', NULL, NULL, NULL, NULL, 13, '2025-05-10 11:59:33', '2025-05-10 11:59:33'),
(12, 'product/images/HhNcw4Mjet2fjJAaOGJvnSveGcvHYe5MyuYxseNn.png', NULL, NULL, NULL, NULL, 14, '2025-05-10 12:02:06', '2025-05-10 12:02:06'),
(13, 'product/images/lzlI8kb82wLzGNq5f9Nw7mEW7R9zBJEdx5DiNGkd.png', NULL, NULL, NULL, NULL, 15, '2025-05-10 12:05:10', '2025-05-10 12:05:10'),
(14, 'product/images/bHAxjoEnLHMNEtukLX4o8wPjtgTtKP7HPCyzctUB.png', NULL, NULL, NULL, NULL, 16, '2025-05-10 12:07:05', '2025-05-10 12:07:05'),
(15, 'product/images/JtFURy9y49EvX4pNUNRJF0BLkiGbHH5EZf4fUZSd.png', NULL, NULL, NULL, NULL, 17, '2025-05-10 12:09:28', '2025-05-10 12:09:28'),
(16, 'product/images/4ZkGRXZgQ3EkECWmV3JyJRfGacp3fJJcBMXO2ut0.png', NULL, NULL, NULL, NULL, 18, '2025-05-10 12:13:22', '2025-05-10 12:13:22'),
(17, 'product/images/xD5NXvJvIl8p5g4GOJbCpCWrG6UTXdxnF1wfHWue.png', NULL, NULL, NULL, NULL, 19, '2025-05-10 12:15:35', '2025-05-10 12:15:35'),
(18, 'product/images/j6EDkGj5r7W4uDqAGts1aYwtjbUqQh9KRrlEN1GC.png', NULL, NULL, NULL, NULL, 20, '2025-05-10 12:20:04', '2025-05-10 12:20:04'),
(19, 'product/images/Uqke55Rmn8ed9NRaHkHg6d3AUzmMTKHi6wxK70wX.png', NULL, NULL, NULL, NULL, 21, '2025-05-10 12:22:05', '2025-05-10 12:22:05'),
(20, 'product/images/osHJjnDpXg7D7HdaldPSMpFiIrm2942KtMYcYQJX.png', NULL, NULL, NULL, NULL, 22, '2025-05-10 12:23:48', '2025-05-10 12:23:48'),
(21, 'product/images/rRswMPioJDra9t0uGBaNjMUCO7rWmFnGuBTZgY77.png', NULL, NULL, NULL, NULL, 23, '2025-05-10 12:27:13', '2025-05-10 12:27:13'),
(23, 'product/images/iow01vYk4YZeA6FywH9KcXp7E2DLEmw6xgfROewo.png', NULL, NULL, NULL, NULL, 24, '2025-05-10 12:29:28', '2025-05-10 12:29:28'),
(24, 'product/images/s8O1aHPbMzk7aTqtikmW8AjAHksLRJKsfi620BYK.png', NULL, NULL, NULL, NULL, 27, '2025-05-10 12:34:33', '2025-05-10 12:34:33'),
(25, 'product/images/g5qm374NeuZQGrtDje8hmmCIfUtX3xYRwPBrNp6q.png', NULL, NULL, NULL, NULL, 28, '2025-05-10 12:36:52', '2025-05-10 12:36:52'),
(26, 'product/images/HXgcAIBvZE4W9qrsmglutlDOSXdesu3iZ8l8Hrk3.png', NULL, NULL, NULL, NULL, 29, '2025-05-10 12:38:59', '2025-05-10 12:38:59'),
(27, 'product/images/7YGhq3qSO6zI8U28Ei6h6jmRodS9NYoelosl37eS.png', NULL, NULL, NULL, NULL, 30, '2025-05-10 12:41:49', '2025-05-10 12:41:49'),
(28, 'product/images/wt1EOZoYFlezicHISGQDloWo4AZvWuV1nHsp7xuG.png', NULL, NULL, NULL, NULL, 31, '2025-05-10 12:44:11', '2025-05-10 12:44:11'),
(29, 'product/images/BaKkm6W2MxDvooEd45GRZev5qoR7mTFMfRPIE6I0.png', NULL, NULL, NULL, NULL, 32, '2025-05-10 12:45:39', '2025-05-10 12:45:39'),
(30, 'product/images/M51d2ErM9dQ6FmJ5hOKWqBZrDSoy8U9Da7zQXYyt.png', NULL, NULL, NULL, NULL, 33, '2025-05-10 12:47:11', '2025-05-10 12:47:11'),
(31, 'product/images/ch36sgoAJ6IdKk9Uj28aE7gwyXGt868BiAdjpLKb.png', NULL, NULL, NULL, NULL, 34, '2025-05-10 12:49:29', '2025-05-10 12:49:29'),
(32, 'product/images/oi1SWfhZVuSdkgriGUR926Yi1yDg90t2wNXPxhbc.png', NULL, NULL, NULL, NULL, 35, '2025-05-10 12:51:17', '2025-05-10 12:51:17'),
(33, 'product/images/bX8HD3tHrDnhKHcFHyqgn0McYpowamj2EPuoUV6G.png', NULL, NULL, NULL, NULL, 36, '2025-05-10 12:53:17', '2025-05-10 12:53:17'),
(34, 'product/images/0XIw2TCDJ2N81V2CpmnFsfvVBMwvazsf1YejUjC0.png', NULL, NULL, NULL, NULL, 37, '2025-05-10 12:55:51', '2025-05-10 12:55:51'),
(35, 'product/images/4k0nFwyv2qWttXIcKrCTxtJqzefcA9RFU1ACRV0A.png', NULL, NULL, NULL, NULL, 38, '2025-05-10 12:57:46', '2025-05-10 12:57:46'),
(36, 'product/images/HL0Zw4aaUs9XYktb0o0z2PUupPHkpfnIhGqQbbqq.png', NULL, NULL, NULL, NULL, 39, '2025-05-10 12:59:24', '2025-05-10 12:59:24'),
(37, 'product/images/BgobE6aeiMgzk256K1L1G8CyCo0x6BXFdvU9Qj3q.png', NULL, NULL, NULL, NULL, 40, '2025-05-10 13:01:03', '2025-05-10 13:01:03'),
(38, 'product/images/cigmJzqPrjTo6pDCgEXZ4nqNQbOjIzOgKEWl8r6U.png', NULL, NULL, NULL, NULL, 41, '2025-05-10 13:03:21', '2025-05-10 13:03:21'),
(39, 'product/images/Dtra03VnJ2laZsuIDXELu8NgOCXBBJ2yxjEbBtpb.png', NULL, NULL, NULL, NULL, 42, '2025-05-10 13:05:22', '2025-05-10 13:05:22'),
(40, 'product/images/u1aPZpBGXHmNEiCYM9aWfFICZLyncMe0uAE4LOet.png', NULL, NULL, NULL, NULL, 43, '2025-05-10 13:06:43', '2025-05-10 13:06:43'),
(41, 'product/images/nzUV2nUmyBetkBcZQqWF6lRPwXmX8N6TEowxjbPI.png', NULL, NULL, NULL, NULL, 44, '2025-05-10 13:08:05', '2025-05-10 13:08:05'),
(42, 'product/images/ZlQFswXjdkVo1Ikqjzmk6oFDBeiBbPYkHLLagpQh.png', NULL, NULL, NULL, NULL, 45, '2025-05-10 13:10:30', '2025-05-10 13:10:30'),
(43, 'product/images/RgHUl4gyvXBNtYc45Pz3hiriY2mNMXNMZ3zCBQKr.png', NULL, NULL, NULL, NULL, 46, '2025-05-10 13:12:15', '2025-05-10 13:12:15'),
(44, 'product/images/o4uaKfpKZbXmUn2zLUi5ErMmmWcy7UmsCD8gwShH.png', NULL, NULL, NULL, NULL, 47, '2025-05-10 13:13:59', '2025-05-10 13:13:59'),
(45, 'product/images/WMLKeRF8K7ZKCkTF5rCctBVVmSP6HtxUT0G57Dfo.png', NULL, NULL, NULL, NULL, 48, '2025-05-10 13:17:31', '2025-05-10 13:17:31'),
(46, 'product/images/m2hz2d4vIkXVVKoWQBHwGQnxGRyMF0zb1c0TNbX8.png', NULL, NULL, NULL, NULL, 49, '2025-05-10 13:19:32', '2025-05-10 13:19:32'),
(47, 'product/images/QqOAxiGPVHTK9VejZneNGPQ3RdwKno1FVxRhS2pf.png', NULL, NULL, NULL, NULL, 50, '2025-05-10 13:22:00', '2025-05-10 13:22:00'),
(48, 'product/images/kFhbZDYZIGwdCXyAgk3GUD4CGHY5Tu2JRNvH0qmn.png', NULL, NULL, NULL, NULL, 51, '2025-05-10 13:24:34', '2025-05-10 13:24:34'),
(49, 'product/images/KnDcx3t8k3e9QSxLjjhBXvSP1gjkbbqZhZnRm3eb.png', NULL, NULL, NULL, NULL, 52, '2025-05-10 13:27:16', '2025-05-10 13:27:16'),
(50, 'product/images/34fpwexk7Iref1Dfdf96fH2hz039TFvQui6Te4wa.png', NULL, NULL, NULL, NULL, 53, '2025-05-10 13:29:50', '2025-05-10 13:29:50'),
(51, 'product/images/tti97EY4axxKFFKhfjbrdMExX462LjBHn5a8gfLz.png', NULL, NULL, NULL, NULL, 54, '2025-05-10 13:32:00', '2025-05-10 13:32:00'),
(52, 'product/images/XoPPLEkFRtsoOS7ahnYEgkM1i5SPacroAgQQPMPR.png', NULL, NULL, NULL, NULL, 55, '2025-05-10 13:33:28', '2025-05-10 13:33:28'),
(53, 'product/images/0XmCWLVLLPNVH99cayFrrQpP9A89v5IHsVQ94quj.png', NULL, NULL, NULL, NULL, 56, '2025-05-10 13:35:07', '2025-05-10 13:35:07'),
(54, 'product/images/qLlAHydFS7HJ7XbQrMsLDf93ryhYoy2U2PC5P5W1.png', NULL, NULL, NULL, NULL, 57, '2025-05-10 13:36:58', '2025-05-10 13:36:58'),
(55, 'product/images/0gSa5W52b8BoctKFroz0rSLi5nkqE5gRcvyL1XSF.png', NULL, NULL, NULL, NULL, 58, '2025-05-10 13:38:56', '2025-05-10 13:38:56'),
(56, 'product/images/aVpYiZpHRqVnmLjSDyDHyO4Fbp4DAoGOnGRxIzY1.png', NULL, NULL, NULL, NULL, 59, '2025-05-10 13:40:12', '2025-05-10 13:40:12'),
(57, 'product/images/vnhfLXCdMZdqTuUBgL4BD0iWdROP1hzegvRx5fGB.png', NULL, NULL, NULL, NULL, 60, '2025-05-10 13:41:35', '2025-05-10 13:41:35'),
(58, 'product/images/9tQyP6RUHd7NO4kQDTnGNAdZsZuFUb7KDSR0yoIN.png', NULL, NULL, NULL, NULL, 61, '2025-05-10 13:45:34', '2025-05-10 13:45:34'),
(59, 'product/images/Ht63z11NQaCXoqqI6Er9dlfU9M0jnnh2y6gCJiZW.png', NULL, NULL, NULL, NULL, 62, '2025-05-10 13:47:57', '2025-05-10 13:47:57'),
(60, 'product/images/9E7dqHOc9vL2lRXpqhgtIu43VheoDILciR6FOp8x.png', NULL, NULL, NULL, NULL, 63, '2025-05-10 13:53:13', '2025-05-10 13:53:13'),
(61, 'product/images/mTeXoVhgWJpA191tg4uMUKIsSj6hlQqlzXzhYgoC.png', NULL, NULL, NULL, NULL, 64, '2025-05-10 13:54:29', '2025-05-10 13:54:29'),
(62, 'product/images/7kVYkprAqnruwqpoFOBR3PbCyTPSmhZKUzETrHpc.png', NULL, NULL, NULL, NULL, 65, '2025-05-10 13:56:25', '2025-05-10 13:56:25'),
(63, 'product/images/RNlAJO8RV00I3EnNcieNekGrNQoqJLUCeIpiRl5N.png', NULL, NULL, NULL, NULL, 66, '2025-05-10 13:57:45', '2025-05-10 13:57:45'),
(64, 'product/images/trBX2yKKYwVPdnrx8h2vVou6kpSakAPcuJb4ClJQ.png', NULL, NULL, NULL, NULL, 67, '2025-05-10 14:00:55', '2025-05-10 14:00:55'),
(65, 'product/images/VyO8WT4nDaR0X0W7H1acwWgcW5AN99W5fyP1BZkK.png', NULL, NULL, NULL, NULL, 68, '2025-05-10 14:02:36', '2025-05-10 14:02:36'),
(66, 'product/images/6QuCeffJWXcHZ2GA0qNhs89lNgr6Rx02YDdPAMee.png', NULL, NULL, NULL, NULL, 69, '2025-05-10 14:05:40', '2025-05-10 14:05:40'),
(67, 'product/images/dGZAYfzcZxH6kaljDOJbru1exh7od0BRxRk7Qtyp.png', NULL, NULL, NULL, NULL, 70, '2025-05-10 14:07:15', '2025-05-10 14:07:15'),
(68, 'product/images/QGspx3zrUFH6h83UIJpkDgHqt6zNYsKOK0KIeZUJ.png', NULL, NULL, NULL, NULL, 71, '2025-05-10 14:23:40', '2025-05-10 14:23:40'),
(69, 'product/images/EpjO0S67kb63utzAT7nJSRFGgQIvKgCsU2MsyEWI.png', NULL, NULL, NULL, NULL, 72, '2025-05-10 14:25:16', '2025-05-10 14:25:16'),
(70, 'product/images/CUGNT02idAGr7AQYo5I4WMNCVhACQEJKSTv7kB61.png', NULL, NULL, NULL, NULL, 73, '2025-05-10 14:26:46', '2025-05-10 14:26:46'),
(71, 'product/images/5SFk0zm1wPJkdtEWVovTo0A6U0pBl41Ec1vHoQg3.png', NULL, NULL, NULL, NULL, 74, '2025-05-10 14:28:33', '2025-05-10 14:28:33'),
(72, 'product/images/MIwetzwzEZVaOV4g2Za0xX8tPL2MIJKvCf23O3TB.png', NULL, NULL, NULL, NULL, 75, '2025-05-10 14:30:40', '2025-05-10 14:30:40'),
(73, 'product/images/rlDe5LEGcHXKHW5VyiNUO76XVcX9mrDXA6px9Dgh.png', NULL, NULL, NULL, NULL, 76, '2025-05-10 14:33:17', '2025-05-10 14:33:17'),
(74, 'product/images/RK0tVpeSgNSzTFf1UDfsSozZmUCSTtvvKub6HDGq.png', NULL, NULL, NULL, NULL, 77, '2025-05-10 14:34:45', '2025-05-10 14:34:45'),
(75, 'product/images/3IeKxA6aSDgqDe9660sE6cHUm7lafqLw5i3alUDx.png', NULL, NULL, NULL, NULL, 78, '2025-05-10 14:36:13', '2025-05-10 14:36:13'),
(76, 'product/images/1qYOgpfCyxVgwUQyKyh9rSdhpB7pempj3o0CQoKO.png', NULL, NULL, NULL, NULL, 79, '2025-05-10 14:38:21', '2025-05-10 14:38:21'),
(77, 'product/images/rZlbnKXgbDCHA03kJW3669Ozw6jyvUrDNIGYdN0a.png', NULL, NULL, NULL, NULL, 80, '2025-05-10 14:39:40', '2025-05-10 14:39:40'),
(78, 'product/images/zIQ9fSsxEk8D8lyaWg2fojvbhk65lbUOhseEGSkk.png', NULL, NULL, NULL, NULL, 81, '2025-05-10 14:41:21', '2025-05-10 14:41:21'),
(79, 'product/images/kLYTlgnSfu9kGg8milld4XFdmDZXo3GJiasIMVd6.png', NULL, NULL, NULL, NULL, 82, '2025-05-10 14:42:59', '2025-05-10 14:42:59'),
(80, 'product/images/33mOi7qPNNpApCw8CCDc86i0h2Mdr5bzQL2OThMQ.png', NULL, NULL, NULL, NULL, 83, '2025-05-10 14:45:33', '2025-05-10 14:45:33'),
(81, 'product/images/Au3UuVzpzI6JGdIwmC5u2tfAREw81etqVWYn6GGD.png', NULL, NULL, NULL, NULL, 84, '2025-05-10 14:47:56', '2025-05-10 14:47:56'),
(82, 'product/images/Lsx2obcQiWbgBiMiZAuvQ5VzYqWtQMZtQJa4s2J4.png', NULL, NULL, NULL, NULL, 85, '2025-05-10 14:49:37', '2025-05-10 14:49:37'),
(83, 'product/images/kPj7A7LNlCUb6vlMRCCxxJ9T3C4xl02zjCQmLRco.png', NULL, NULL, NULL, NULL, 86, '2025-05-10 14:51:08', '2025-05-10 14:51:08'),
(84, 'product/images/RB0cUfSdxfLUx5WhEkn9RYDzrvbpgMMwiq1R8hRM.png', NULL, NULL, NULL, NULL, 87, '2025-05-10 14:52:43', '2025-05-10 14:52:43'),
(85, 'product/images/hFhVIi9Rl8GKAheD7NdRA2zdlUm6twzD4vJdETok.png', NULL, NULL, NULL, NULL, 88, '2025-05-10 14:54:08', '2025-05-10 14:54:08'),
(86, 'product/images/FiCAUleDpDsbyCQPMt0qXC5BU9P3vxlYGRBZjcq0.png', NULL, NULL, NULL, NULL, 89, '2025-05-10 14:55:41', '2025-05-10 14:55:41'),
(87, 'product/images/mcSSgRDZVe0O1CteSdyNemP75akWmE5BsNqpRVdz.png', NULL, NULL, NULL, NULL, 90, '2025-05-10 14:57:53', '2025-05-10 14:57:53'),
(88, 'product/images/M5RXcig7WqwcKGgT2SmTbqiuiVWCaQ2o1ok2HNN2.png', NULL, NULL, NULL, NULL, 91, '2025-05-10 15:00:22', '2025-05-10 15:00:22'),
(89, 'product/images/ToWf25OUAAQaQ4an05i6orgMiFYsuLJ8X9fXJU3M.png', NULL, NULL, NULL, NULL, 92, '2025-05-10 15:02:08', '2025-05-10 15:02:08'),
(90, 'product/images/gyydAPsdQ4G6DM8sdXRsuC2BWCu9WWSD6SF5Y6rO.png', NULL, NULL, NULL, NULL, 93, '2025-05-10 15:03:58', '2025-05-10 15:03:58'),
(91, 'product/images/lmugc5210SqT6432mOkbviZtKvGZYmjf3HwMvaUG.png', NULL, NULL, NULL, NULL, 94, '2025-05-10 15:06:03', '2025-05-10 15:06:03'),
(92, 'product/images/4USf4J0JMLeFWnjOKqh7KYcG8ry46k5CHsVChYmd.png', NULL, NULL, NULL, NULL, 95, '2025-05-10 15:08:33', '2025-05-10 15:08:33'),
(93, 'product/images/fFeEdITWkZiYZF65gQ13cxYF9WIZ8jmoxupMgdFa.png', NULL, NULL, NULL, NULL, 96, '2025-05-10 15:11:29', '2025-05-10 15:11:29'),
(94, 'product/images/htctfHsv5EKO3cg6Cd4cSDEA2cvjgDJzFmr40xnl.png', NULL, NULL, NULL, NULL, 97, '2025-05-10 15:13:21', '2025-05-10 15:13:21'),
(95, 'product/images/ktDKCbz0n8pShXiBLBCjMf4j1Rn0gxzeET34zCNL.png', NULL, NULL, NULL, NULL, 98, '2025-05-10 15:15:49', '2025-05-10 15:15:49'),
(96, 'product/images/v3sPxjk0vGgs0dvhPHluDx8sHEwEJe3rPAZ8Yg67.png', NULL, NULL, NULL, NULL, 99, '2025-05-10 15:17:46', '2025-05-10 15:17:46'),
(97, 'product/images/jzU772jMlBnAaoaokXlnkwVjwcZIunfPjGEBTPKx.png', NULL, NULL, NULL, NULL, 100, '2025-05-10 15:19:49', '2025-05-10 15:19:49'),
(98, 'product/images/Ry4EYxOMcO7HfI30B3bUHoQtcEq3daQIU1xkxhEx.jpg', NULL, NULL, NULL, NULL, 101, '2025-06-29 17:46:01', '2025-06-29 17:46:01'),
(99, 'product/images/dAEMyRnLgSopyymc61BlVMJS2k2dgUGMXc4UcVyr.jpg', NULL, NULL, NULL, NULL, 101, '2025-06-29 17:46:11', '2025-06-29 17:46:11'),
(100, 'product/images/default-product.jpg', NULL, NULL, NULL, NULL, 134, '2025-09-06 11:01:00', '2025-09-06 11:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventories`
--

CREATE TABLE `product_inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_inventories`
--

INSERT INTO `product_inventories` (`id`, `qty`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 155, 3, '2025-05-10 10:54:42', '2025-09-15 17:54:06'),
(2, 0, 4, '2025-05-10 11:07:42', '2025-09-14 07:17:46'),
(3, 0, 5, '2025-05-10 11:12:23', '2025-09-14 07:17:46'),
(4, 10, 6, '2025-05-10 11:19:03', '2025-05-10 11:19:03'),
(5, 10, 7, '2025-05-10 11:43:57', '2025-05-10 11:43:57'),
(6, 10, 8, '2025-05-10 11:46:08', '2025-05-10 11:46:08'),
(7, 100, 9, '2025-05-10 11:48:35', '2025-09-14 14:15:20'),
(8, 10, 10, '2025-05-10 11:51:43', '2025-05-10 11:51:43'),
(9, 10, 11, '2025-05-10 11:54:28', '2025-05-10 11:54:28'),
(10, 10, 12, '2025-05-10 11:57:35', '2025-05-10 11:57:35'),
(11, 10, 13, '2025-05-10 12:00:10', '2025-05-10 12:00:10'),
(12, 10, 14, '2025-05-10 12:02:48', '2025-05-10 12:02:48'),
(13, 10, 15, '2025-05-10 12:05:46', '2025-05-10 12:05:46'),
(14, 10, 16, '2025-05-10 12:08:07', '2025-05-10 12:08:07'),
(15, 10, 17, '2025-05-10 12:10:44', '2025-05-10 12:10:44'),
(16, 10, 18, '2025-05-10 12:14:06', '2025-05-10 12:14:06'),
(17, 10, 19, '2025-05-10 12:16:57', '2025-05-10 12:16:57'),
(18, 10, 20, '2025-05-10 12:20:41', '2025-05-10 12:20:41'),
(19, 10, 21, '2025-05-10 12:22:42', '2025-05-10 12:22:42'),
(20, 10, 22, '2025-05-10 12:24:37', '2025-05-10 12:24:37'),
(21, 10, 23, '2025-05-10 12:28:03', '2025-05-10 12:28:03'),
(22, 10, 24, '2025-05-10 12:30:00', '2025-05-10 12:30:00'),
(23, 10, 25, '2025-05-10 12:32:00', '2025-05-10 12:32:00'),
(24, 10, 26, '2025-05-10 12:33:33', '2025-05-10 12:33:33'),
(25, 10, 27, '2025-05-10 12:35:10', '2025-05-10 12:35:10'),
(26, 10, 28, '2025-05-10 12:37:31', '2025-05-10 12:37:31'),
(27, 10, 29, '2025-05-10 12:40:18', '2025-05-10 12:40:18'),
(28, 10, 30, '2025-05-10 12:42:26', '2025-05-10 12:42:26'),
(29, 10, 31, '2025-05-10 12:44:40', '2025-05-10 12:44:40'),
(30, 10, 32, '2025-05-10 12:46:17', '2025-05-10 12:46:17'),
(31, 10, 33, '2025-05-10 12:48:33', '2025-05-10 12:48:33'),
(32, 10, 34, '2025-05-10 12:50:06', '2025-05-10 12:50:06'),
(33, 10, 35, '2025-05-10 12:51:52', '2025-05-10 12:51:52'),
(34, 10, 36, '2025-05-10 12:54:40', '2025-05-10 12:54:40'),
(35, 10, 37, '2025-05-10 12:56:27', '2025-05-10 12:56:27'),
(36, 10, 38, '2025-05-10 12:58:35', '2025-05-10 12:58:35'),
(37, 10, 39, '2025-05-10 13:00:08', '2025-05-10 13:00:08'),
(38, 10, 40, '2025-05-10 13:01:35', '2025-05-10 13:01:35'),
(39, 10, 41, '2025-05-10 13:04:02', '2025-05-10 13:04:02'),
(40, 10, 42, '2025-05-10 13:05:51', '2025-05-10 13:05:51'),
(41, 10, 43, '2025-05-10 13:07:12', '2025-05-10 13:07:12'),
(42, 10, 44, '2025-05-10 13:08:55', '2025-05-10 13:08:55'),
(43, 10, 45, '2025-05-10 13:11:06', '2025-05-10 13:11:06'),
(44, 10, 46, '2025-05-10 13:12:55', '2025-05-10 13:12:55'),
(45, 10, 47, '2025-05-10 13:14:28', '2025-05-10 13:14:28'),
(46, 10, 48, '2025-05-10 13:18:25', '2025-05-10 13:18:25'),
(47, 10, 49, '2025-05-10 13:20:16', '2025-05-10 13:20:16'),
(48, 10, 50, '2025-05-10 13:22:32', '2025-05-10 13:22:32'),
(49, 10, 51, '2025-05-10 13:26:06', '2025-05-10 13:26:06'),
(50, 10, 52, '2025-05-10 13:27:55', '2025-05-10 13:27:55'),
(51, 10, 53, '2025-05-10 13:30:39', '2025-05-10 13:30:39'),
(52, 10, 54, '2025-05-10 13:32:32', '2025-05-10 13:32:32'),
(53, 10, 55, '2025-05-10 13:33:57', '2025-05-10 13:33:57'),
(54, 10, 56, '2025-05-10 13:35:34', '2025-05-10 13:35:34'),
(55, 10, 57, '2025-05-10 13:37:35', '2025-05-10 13:37:35'),
(56, 10, 58, '2025-05-10 13:39:23', '2025-05-10 13:39:23'),
(57, 10, 59, '2025-05-10 13:40:41', '2025-05-10 13:40:41'),
(58, 10, 60, '2025-05-10 13:42:32', '2025-05-10 13:42:32'),
(59, 10, 61, '2025-05-10 13:46:18', '2025-05-10 13:46:18'),
(60, 10, 62, '2025-05-10 13:49:04', '2025-05-10 13:49:04'),
(61, 10, 63, '2025-05-10 13:53:43', '2025-05-10 13:53:43'),
(62, 10, 64, '2025-05-10 13:55:11', '2025-05-10 13:55:11'),
(63, 10, 65, '2025-05-10 13:56:53', '2025-05-10 13:56:53'),
(64, 10, 66, '2025-05-10 13:58:52', '2025-05-10 13:58:52'),
(65, 10, 67, '2025-05-10 14:01:31', '2025-05-10 14:01:31'),
(66, 10, 68, '2025-05-10 14:03:59', '2025-05-10 14:03:59'),
(67, 10, 69, '2025-05-10 14:06:16', '2025-05-10 14:06:16'),
(68, 10, 70, '2025-05-10 14:08:07', '2025-05-10 14:08:07'),
(69, 10, 71, '2025-05-10 14:24:27', '2025-05-10 14:24:27'),
(70, 10, 72, '2025-05-10 14:25:47', '2025-05-10 14:25:47'),
(71, 10, 73, '2025-05-10 14:27:23', '2025-05-10 14:27:23'),
(72, 10, 74, '2025-05-10 14:29:14', '2025-05-10 14:29:14'),
(73, 10, 75, '2025-05-10 14:31:13', '2025-05-10 14:31:13'),
(74, 10, 76, '2025-05-10 14:33:52', '2025-05-10 14:33:52'),
(75, 10, 77, '2025-05-10 14:35:17', '2025-05-10 14:35:17'),
(76, 10, 78, '2025-05-10 14:37:15', '2025-05-10 14:37:15'),
(77, 10, 79, '2025-05-10 14:38:48', '2025-05-10 14:38:48'),
(78, 10, 80, '2025-05-10 14:40:17', '2025-05-10 14:40:17'),
(79, 10, 81, '2025-05-10 14:41:59', '2025-05-10 14:41:59'),
(80, 10, 82, '2025-05-10 14:44:15', '2025-05-10 14:44:15'),
(81, 10, 83, '2025-05-10 14:46:52', '2025-05-10 14:46:52'),
(82, 10, 84, '2025-05-10 14:48:30', '2025-05-10 14:48:30'),
(83, 10, 85, '2025-05-10 14:50:10', '2025-05-10 14:50:10'),
(84, 10, 86, '2025-05-10 14:51:44', '2025-05-10 14:51:44'),
(85, 10, 87, '2025-05-10 14:53:18', '2025-05-10 14:53:18'),
(86, 10, 88, '2025-05-10 14:54:45', '2025-05-10 14:54:45'),
(87, 10, 89, '2025-05-10 14:56:52', '2025-05-10 14:56:52'),
(88, 10, 90, '2025-05-10 14:58:45', '2025-05-10 14:58:45'),
(89, 10, 91, '2025-05-10 15:01:02', '2025-05-10 15:01:02'),
(90, 10, 92, '2025-05-10 15:02:53', '2025-05-10 15:02:53'),
(91, 10, 93, '2025-05-10 15:04:54', '2025-05-10 15:04:54'),
(92, 10, 94, '2025-05-10 15:07:37', '2025-05-10 15:07:37'),
(93, 10, 95, '2025-05-10 15:09:57', '2025-05-10 15:09:57'),
(94, 10, 96, '2025-05-10 15:12:13', '2025-05-10 15:12:13'),
(95, 10, 97, '2025-05-10 15:14:14', '2025-05-10 15:14:14'),
(96, 10, 98, '2025-05-10 15:16:32', '2025-05-10 15:16:32'),
(97, 10, 99, '2025-05-10 15:18:30', '2025-05-10 15:18:30'),
(98, 10, 100, '2025-05-10 15:21:16', '2025-05-10 15:21:16'),
(99, 61, 101, '2025-05-10 18:07:29', '2025-08-13 06:28:19'),
(100, 98, 102, '2025-06-30 13:03:18', '2025-08-12 07:18:50'),
(109, 93, 111, '2025-06-30 14:07:09', '2025-08-13 07:07:49'),
(110, 100, 112, '2025-06-30 14:09:11', '2025-06-30 14:09:11'),
(111, 142, 113, '2025-06-30 14:14:19', '2025-07-08 15:12:59'),
(112, 50, 114, '2025-07-04 18:28:02', '2025-07-08 15:12:59'),
(113, 1, 115, '2025-07-26 11:55:48', '2025-07-26 11:55:48'),
(114, 8, 116, '2025-07-26 11:59:35', '2025-08-13 14:34:33'),
(115, 92, 117, '2025-07-26 12:01:40', '2025-08-14 17:38:52'),
(116, 10, 118, '2025-07-26 12:10:40', '2025-07-26 12:10:40'),
(117, 10, 119, '2025-07-26 12:16:17', '2025-07-26 12:16:17'),
(118, 9, 120, '2025-07-26 12:23:55', '2025-07-26 12:24:58'),
(120, 96, 123, '2025-08-14 18:35:56', '2025-08-14 18:48:41'),
(124, 1, 128, '2025-09-06 03:23:42', '2025-09-06 03:23:42'),
(125, 500, 130, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(126, 89, 138, '2025-09-14 14:16:58', '2025-09-15 15:36:17'),
(127, 100, 141, '2025-09-15 17:54:48', '2025-09-15 17:54:48'),
(128, 50, 142, '2025-09-15 17:54:48', '2025-09-15 17:54:48'),
(129, 100, 144, '2025-09-15 17:56:35', '2025-09-15 17:56:35'),
(130, 100, 149, '2025-09-15 22:00:37', '2025-09-15 22:00:37'),
(131, 100, 151, '2025-09-15 22:11:32', '2025-09-15 22:11:32'),
(132, 100, 160, '2025-09-15 22:36:37', '2025-09-15 22:36:37'),
(133, 100, 165, '2025-09-15 22:43:32', '2025-09-15 22:43:32'),
(134, 100, 166, '2025-09-15 22:54:27', '2025-09-15 22:54:27'),
(135, 100, 181, '2025-09-16 02:22:15', '2025-09-16 02:22:15'),
(136, 100, 190, '2025-09-16 02:32:13', '2025-09-16 02:32:13'),
(137, 100, 192, '2025-09-16 02:46:28', '2025-09-16 02:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `harga_beli` decimal(15,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `weight` decimal(10,2) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `print_type` enum('bw','color') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paper_size` enum('A4','A3','F4') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `min_stock_threshold` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `name`, `price`, `harga_beli`, `stock`, `weight`, `length`, `width`, `height`, `barcode`, `print_type`, `paper_size`, `is_active`, `min_stock_threshold`, `created_at`, `updated_at`) VALUES
(1, 129, 'KER-AP-A4-70', 'Kertas HVS - APP A4 70gr', '45000.00', '35000.00', 100, '0.50', NULL, NULL, NULL, '5429885686', NULL, NULL, 1, 20, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(2, 129, 'KER-AP-A4-80', 'Kertas HVS - APP A4 80gr', '50000.00', '40000.00', 75, '0.50', NULL, NULL, NULL, '2779539872', NULL, NULL, 1, 20, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(3, 129, 'KER-AP-F4-70', 'Kertas HVS - APP F4 70gr', '48000.00', '38000.00', 50, '0.50', NULL, NULL, NULL, '3103660033', NULL, NULL, 1, 15, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(4, 129, 'KER-SI-A4-70', 'Kertas HVS - Sinar Dunia A4 70gr', '42000.00', '32000.00', 120, '0.50', NULL, NULL, NULL, '7748231376', NULL, NULL, 1, 25, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(5, 129, 'KER-SI-A4-80', 'Kertas HVS - Sinar Dunia A4 80gr', '47000.00', '37000.00', 90, '0.50', NULL, NULL, NULL, '7695160431', NULL, NULL, 1, 20, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(10, 133, '9787080', 'Kertas Baru Lagi', '2.00', '1.00', 10, '1.00', '1.00', '1.00', '1.00', NULL, NULL, NULL, 1, 10, '2025-09-06 04:43:37', '2025-09-06 06:10:47'),
(11, 133, 'FIX-TEST-1757134428', 'Test Fix Variant - 11:53:48', '250000.00', NULL, 25, '400.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 04:53:48', '2025-09-06 04:53:48'),
(12, 133, 'FIX-TEST-1757134440', 'Test Fix Variant - 11:54:00', '250000.00', NULL, 25, '400.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 04:54:00', '2025-09-06 04:54:00'),
(14, 133, '31316313', 'astaghfirullah', '4.00', NULL, 134, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 04:59:41', '2025-09-11 10:23:40'),
(15, 133, '9787080-V1', 'astaghfirullah', '4.00', '1.00', 10, '10.00', '1.00', '1.00', '1.00', NULL, NULL, NULL, 1, 10, '2025-09-06 05:00:01', '2025-09-06 06:11:05'),
(16, 133, '8316317', 'ya-allah', '3.00', '1.00', 131, '10.00', '1.00', '1.00', '1.00', NULL, NULL, NULL, 1, 10, '2025-09-06 05:04:16', '2025-09-11 10:23:03'),
(17, 117, 'TEST-RED-L-1757137084', 'Test Variant - Red Large', '150000.00', '120000.00', 92, '0.50', '25.00', '15.00', '5.00', NULL, NULL, NULL, 1, 10, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(18, 117, 'TEST-BLUE-M-1757137084', 'Test Variant - Blue Medium', '135000.00', '110000.00', 92, '0.45', '23.00', '14.00', '4.50', NULL, NULL, NULL, 1, 10, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(19, 117, 'TEST-GREEN-S-1757137084', 'Test Variant - Green Small', '125000.00', '100000.00', 92, '0.40', '20.00', '12.00', '4.00', NULL, NULL, NULL, 1, 10, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(20, 117, 'sku-1233-V1', 'Conflict Test Variant', '100000.00', '80000.00', 92, '0.30', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 05:38:04', '2025-09-08 04:32:41'),
(21, 117, 'STRESS-A-1757138147-1', 'Stress Test Variant A', '100000.00', '80000.00', 92, '0.50', '20.00', '15.00', '5.00', NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(22, 117, 'STRESS-B-1757138147-2', 'Stress Test Variant B', '120000.00', '95000.00', 92, '0.60', '22.00', '16.00', '6.00', NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(23, 117, 'STRESS-C-1757138147-3', 'Stress Test Variant C', '110000.00', '88000.00', 92, '0.55', '21.00', '15.50', '5.50', NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(25, 117, 'sku-1233-V2', 'Conflict Test 2', '100000.00', '78000.00', 92, '0.50', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(26, 117, 'sku-1233-V3', 'Conflict Test 3', '105000.00', '82000.00', 92, '0.60', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(27, 117, 'sku-1233-V4', 'Conflict Test 4', '110000.00', '86000.00', 92, '0.70', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(28, 117, 'sku-1233-V5', 'Conflict Test 5', '115000.00', '90000.00', 92, '0.80', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(29, 134, 'BAJU-PL2-S-MER', 'Baju Pria Lengan Panjang 2 - S Merah', '153882.00', '120000.00', 38, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(30, 134, 'BAJU-PL2-S-BIR', 'Baju Pria Lengan Panjang 2 - S Biru', '147083.00', '120000.00', 44, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(31, 134, 'BAJU-PL2-S-HIT', 'Baju Pria Lengan Panjang 2 - S Hitam', '158215.00', '120000.00', 39, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(32, 134, 'BAJU-PL2-S-PUT', 'Baju Pria Lengan Panjang 2 - S Putih', '148640.00', '120000.00', 133, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-11 10:23:24'),
(33, 134, 'BAJU-PL2-M-MER', 'Baju Pria Lengan Panjang 2 - M Merah', '150841.00', '120000.00', 27, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(34, 134, 'BAJU-PL2-M-BIR', 'Baju Pria Lengan Panjang 2 - M Biru', '143764.00', '120000.00', 33, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(35, 134, 'BAJU-PL2-M-HIT', 'Baju Pria Lengan Panjang 2 - M Hitam', '140318.00', '120000.00', 34, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(36, 134, 'BAJU-PL2-M-PUT', 'Baju Pria Lengan Panjang 2 - M Putih', '140135.00', '120000.00', 130, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-14 07:19:46'),
(37, 134, 'BAJU-PL2-L-MER', 'Baju Pria Lengan Panjang 2 - L Merah', '155753.00', '120000.00', 50, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(38, 134, 'BAJU-PL2-L-BIR', 'Baju Pria Lengan Panjang 2 - L Biru', '148740.00', '120000.00', 30, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(39, 134, 'BAJU-PL2-L-HIT', 'Baju Pria Lengan Panjang 2 - L Hitam', '151373.00', '120000.00', 32, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(40, 134, 'BAJU-PL2-L-PUT', 'Baju Pria Lengan Panjang 2 - L Putih', '142290.00', '120000.00', 30, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(41, 134, 'BAJU-PL2-XL-MER', 'Baju Pria Lengan Panjang 2 - XL Merah', '147137.00', '120000.00', 23, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(42, 134, 'BAJU-PL2-XL-BIR', 'Baju Pria Lengan Panjang 2 - XL Biru', '154659.00', '120000.00', 16, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(43, 134, 'BAJU-PL2-XL-HIT', 'Baju Pria Lengan Panjang 2 - XL Hitam', '151449.00', '120000.00', 13, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(44, 134, 'BAJU-PL2-XL-PUT', 'Baju Pria Lengan Panjang 2 - XL Putih', '143966.00', '120000.00', 41, '500.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(45, 135, 'KER-A4-HI', 'Kertas HVS - Layanan Cetak - A4 Hitam Putih', '500.00', '300.00', 11982, '0.01', NULL, NULL, NULL, '6414338284', 'bw', 'A4', 1, 1000, '2025-09-11 04:02:27', '2025-09-15 16:45:07'),
(46, 135, 'KER-A4-BE', 'Kertas HVS - Layanan Cetak - A4 Berwarna', '2.00', '1.00', 9833, '0.01', '10.00', '10.00', '10.00', '6411927437', 'color', 'A4', 1, 500, '2025-09-11 04:02:27', '2025-09-15 17:43:30'),
(47, 135, 'KER-A3-HI', 'Kertas HVS - Layanan Cetak - A3 Hitam Putih', '1000.00', '600.00', 10000, '0.01', NULL, NULL, NULL, '1820603793', 'bw', 'A3', 1, 300, '2025-09-11 04:02:27', '2025-09-15 16:45:07'),
(48, 135, 'KER-A3-BE', 'Kertas HVS - Layanan Cetak - A3 Berwarna', '3000.00', '1800.00', 9997, '0.01', NULL, NULL, NULL, '3361036221', 'color', 'A3', 0, 200, '2025-09-11 04:02:27', '2025-09-15 16:45:07'),
(49, 135, 'KER-F4-HI', 'Kertas HVS - Layanan Cetak - F4 Hitam Putih', '750.00', '450.00', 10000, '0.01', NULL, NULL, NULL, '8200323552', 'bw', 'F4', 1, 400, '2025-09-11 04:02:27', '2025-09-15 16:45:07'),
(50, 135, 'KER-F4-BE', 'Kertas HVS - Layanan Cetak - F4 Berwarna', '2000.00', '1200.00', 10000, '0.01', NULL, NULL, NULL, '2934790815', 'color', 'F4', 1, 250, '2025-09-11 04:02:27', '2025-09-15 16:45:07'),
(51, 136, 'KER-A4-HI-01', 'Kertas HVS - Layanan Cetak - A4 Hitam Putih', '500.00', '300.00', 10000, '0.01', NULL, NULL, NULL, '4383702921', 'bw', 'A4', 1, 1000, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(52, 136, 'KER-A4-BE-01', 'Kertas HVS - Layanan Cetak - A4 Berwarna', '1500.00', '900.00', 5000, '0.01', NULL, NULL, NULL, '6777309359', 'color', 'A4', 1, 500, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(53, 136, 'KER-A3-HI-01', 'Kertas HVS - Layanan Cetak - A3 Hitam Putih', '1000.00', '600.00', 3000, '0.01', NULL, NULL, NULL, '8194900548', 'bw', 'A3', 1, 300, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(54, 136, 'KER-A3-BE-01', 'Kertas HVS - Layanan Cetak - A3 Berwarna', '3000.00', '1800.00', 2000, '0.01', NULL, NULL, NULL, '7101522202', 'color', 'A3', 1, 200, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(55, 136, 'KER-F4-HI-01', 'Kertas HVS - Layanan Cetak - F4 Hitam Putih', '750.00', '450.00', 4000, '0.01', NULL, NULL, NULL, '2472378728', 'bw', 'F4', 1, 400, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(56, 136, 'KER-F4-BE-01', 'Kertas HVS - Layanan Cetak - F4 Berwarna', '2000.00', '1200.00', 2500, '0.01', NULL, NULL, NULL, '3849979704', 'color', 'F4', 1, 250, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(57, 137, 'KER-A4-HI-02', 'Kertas HVS - Layanan Cetak - A4 Hitam Putih', '500.00', '300.00', 9999, '0.01', NULL, NULL, NULL, '2319116029', 'bw', 'A4', 0, 1000, '2025-09-11 04:10:55', '2025-09-11 11:54:11'),
(58, 137, 'KER-A4-BE-02', 'Kertas HVS - Layanan Cetak - A4 Berwarna', '1500.00', '900.00', 10000, '0.01', NULL, NULL, NULL, '9436546167', 'color', 'A4', 0, 500, '2025-09-11 04:10:55', '2025-09-15 16:45:07'),
(59, 137, 'KER-A3-HI-02', 'Kertas HVS - Layanan Cetak - A3 Hitam Putih', '1000.00', '600.00', 10000, '0.01', NULL, NULL, NULL, '2537695702', 'bw', 'A3', 0, 300, '2025-09-11 04:10:55', '2025-09-15 16:45:07'),
(60, 135, 'KER-A3-BE-02', 'Kertas HVS - Layanan Cetak - A3 Berwarna', '3000.00', '1800.00', 10000, '0.01', NULL, NULL, NULL, '1896205953', 'color', 'A3', 1, 200, '2025-09-11 04:10:55', '2025-09-15 16:45:07'),
(61, 137, 'KER-F4-HI-02', 'Kertas HVS - Layanan Cetak - F4 Hitam Putih', '750.00', '450.00', 10000, '0.01', NULL, NULL, NULL, '1345817753', 'bw', 'F4', 0, 400, '2025-09-11 04:10:55', '2025-09-15 16:45:07'),
(62, 137, 'KER-F4-BE-02', 'Kertas HVS - Layanan Cetak - F4 Berwarna', '2000.00', '1200.00', 10000, '0.01', NULL, NULL, NULL, '7050053831', 'color', 'F4', 0, 250, '2025-09-11 04:10:55', '2025-09-15 16:45:07'),
(63, 3, '3-DEFAULT', 'Default', '15000.00', '10000.00', 155, NULL, NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-14 02:53:45', '2025-09-15 23:30:47'),
(64, 9, 'AMPLOP-DEFAULT', 'AMPLOP (Default)', '15000.00', '10000.00', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-14 06:15:10', '2025-09-14 14:15:20'),
(65, 4, '04fb7d48-e049-4bbe-9f44-707265a75399', 'PETA A3 (Default)', '2500.00', '1000.00', 0, NULL, NULL, NULL, NULL, NULL, 'bw', 'A3', 1, 10, '2025-09-14 07:16:59', '2025-09-15 23:30:47'),
(66, 5, '2c5f9f44-f3be-4e27-8516-a18baca13295', 'KUESIONER & PRELIST (Default)', '15000.00', '10000.00', 0, NULL, NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-14 07:16:59', '2025-09-15 23:30:47'),
(67, 138, 'SFWSFRW1313', 'RAKET PADEL (Default)', '2.00', '1.00', 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2025-09-14 14:59:55', '2025-09-15 15:36:17'),
(68, 139, 'SP-A4-80-1757958820', 'A4 80gsm White', '1000.00', '800.00', 100, NULL, NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 17:53:40', '2025-09-15 17:53:40'),
(69, 139, 'SP-A4-COLOR-1757958820', 'A4 80gsm Color', '2000.00', '1600.00', 50, NULL, NULL, NULL, NULL, NULL, 'color', 'A4', 1, 10, '2025-09-15 17:53:40', '2025-09-15 17:53:40'),
(70, 143, 'SPCP-V1-1757958888', 'Variant 1', '2000.00', '1500.00', 75, NULL, NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 17:54:48', '2025-09-15 17:54:48'),
(71, 143, 'SPCP-V2-1757958888', 'Variant 2', '3000.00', '2500.00', 25, NULL, NULL, NULL, NULL, NULL, 'color', 'A3', 1, 10, '2025-09-15 17:54:48', '2025-09-15 17:54:48'),
(72, 144, 'A10-STD-001', 'A10 Standard', '2.00', '1.00', 100, '1.00', '1.00', '1.00', '1.00', NULL, 'bw', 'A4', 1, 10, '2025-09-15 21:49:01', '2025-09-15 21:49:01'),
(73, 144, 'A10-CLR-001', 'A10 Color', '3.00', '1.00', 50, '1.00', '1.00', '1.00', '1.00', NULL, 'color', 'A4', 1, 5, '2025-09-15 21:49:01', '2025-09-15 21:49:01'),
(76, 149, '87853jhjhjfd-BW', 'KERTAS DINO - Black & White', '1000.00', '500.00', 100, '1.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:00:22', '2025-09-15 22:00:22'),
(77, 149, '87853jhjhjfd-CLR', 'KERTAS DINO - Color', '1500.00', '500.00', 50, '1.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 5, '2025-09-15 22:00:22', '2025-09-15 22:00:22'),
(80, 151, 'dsfsfrete586879-BW', 'Kertas Buffalo - Black & White', '1000.00', '500.00', 100, '1.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:11:18', '2025-09-15 22:11:18'),
(81, 151, 'dsfsfrete586879-CLR', 'Kertas Buffalo - Color', '1500.00', '500.00', 50, '1.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 5, '2025-09-15 22:11:18', '2025-09-15 22:11:18'),
(84, 158, 'adafs6465757-BW', 'Kertas Glossy - Black & White', '1000.00', '500.00', 100, '1.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:29:11', '2025-09-15 22:29:11'),
(85, 158, 'adafs6465757-CLR', 'Kertas Glossy - Color', '1500.00', '500.00', 50, '1.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 5, '2025-09-15 22:29:11', '2025-09-15 22:29:11'),
(88, 160, 'jhdjhsajd868632-BW', 'Kertas Foto - Black & White', '1000.00', '500.00', 100, '1.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:36:21', '2025-09-15 22:36:21'),
(89, 160, 'jhdjhsajd868632-CLR', 'Kertas Foto - Color', '1500.00', '500.00', 50, '1.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 5, '2025-09-15 22:36:21', '2025-09-15 22:36:21'),
(94, 165, 'kadhkah838163-BW', 'Kertas Ajaib - Black & White', '1000.00', '500.00', 100, '1.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:43:19', '2025-09-15 22:43:19'),
(95, 165, 'kadhkah838163-CLR', 'Kertas Ajaib - Color', '1500.00', '500.00', 50, '1.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 5, '2025-09-15 22:43:19', '2025-09-15 22:43:19'),
(96, 3, '0149794284247257524-BW', 'BW', '15000.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:50:28', '2025-09-15 23:30:47'),
(97, 3, '0149794284247257524-Color', 'Color', '22500.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 10, '2025-09-15 22:50:28', '2025-09-15 23:30:47'),
(98, 4, '04fb7d48-e049-4bbe-9f44-707265a75399-BW', 'BW', '2500.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:50:46', '2025-09-15 23:30:47'),
(99, 4, '04fb7d48-e049-4bbe-9f44-707265a75399-Color', 'Color', '3750.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 10, '2025-09-15 22:50:46', '2025-09-15 23:30:47'),
(100, 5, '2c5f9f44-f3be-4e27-8516-a18baca13295-BW', 'BW', '15000.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:51:32', '2025-09-15 23:30:47'),
(101, 5, '2c5f9f44-f3be-4e27-8516-a18baca13295-Color', 'Color', '22500.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 10, '2025-09-15 22:51:32', '2025-09-15 23:30:47'),
(102, 6, '4a821cff-fc09-4373-ae9d-18588ca474ba-BW', 'BW', '15000.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:52:27', '2025-09-15 23:30:47'),
(103, 6, '4a821cff-fc09-4373-ae9d-18588ca474ba-Color', 'Color', '22500.00', NULL, 0, '0.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 10, '2025-09-15 22:52:27', '2025-09-15 23:30:47'),
(104, 166, 'fskfsfshkh-BW', 'Kertas Minyak - Black & White', '1000.00', '500.00', 100, '1.00', NULL, NULL, NULL, NULL, 'bw', 'A4', 1, 10, '2025-09-15 22:54:15', '2025-09-15 22:54:15'),
(105, 166, 'fskfsfshkh-CLR', 'Kertas Minyak - Color', '1500.00', '500.00', 50, '1.00', NULL, NULL, NULL, NULL, 'color', 'A4', 1, 5, '2025-09-15 22:54:15', '2025-09-15 22:54:15'),
(107, 167, 'kfhks2424725', 'Padang Black & White', '2.00', '1.00', 91, '1.00', '0.00', '0.00', '0.00', NULL, 'bw', 'A4', 1, 10, '2025-09-15 23:10:24', '2025-09-16 01:52:53'),
(108, 167, 'duiwru9479759', 'Padang Colorful', '2.00', '1.00', 100, '1.00', '1.00', '1.00', '1.00', NULL, 'color', 'A4', 1, 10, '2025-09-15 23:11:00', '2025-09-15 23:24:34'),
(109, 3, 'PRINTONDEMAND|C-B5F15F', 'PRINT ON DEMAND | CETAK KERTAS HVS - Black & White', '2000.00', NULL, 100, NULL, NULL, NULL, NULL, '1B39D8C2A1C0', 'bw', 'A4', 1, 10, '2025-09-15 23:32:54', '2025-09-15 23:32:54'),
(110, 3, 'PRINTONDEMAND|C-586227', 'PRINT ON DEMAND | CETAK KERTAS HVS - Color', '5000.00', NULL, 50, NULL, NULL, NULL, NULL, '0D25E437BAB0', 'color', 'A4', 1, 10, '2025-09-15 23:32:54', '2025-09-15 23:32:54'),
(111, 168, '84864667sbb', 'KERTAS YA ALLAH', '2.00', '1.00', 100, '1.00', '1.00', '1.00', '1.00', NULL, 'bw', 'A4', 1, 10, '2025-09-15 23:48:01', '2025-09-15 23:50:30'),
(112, 168, '7815514ERWY', 'KERTAS YA ALLAH', '2.00', '1.00', 100, '1.00', '1.00', '1.00', '1.00', NULL, 'bw', 'A4', 1, 10, '2025-09-15 23:49:08', '2025-09-15 23:50:30'),
(115, 193, '391391IKHKH', 'KUCING GARONG', '2.00', '1.00', 100, '2.00', '1.00', '1.00', '1.00', NULL, NULL, NULL, 1, 10, '2025-09-16 03:01:08', '2025-09-16 03:09:52'),
(116, 193, 'UHSHS72725', 'KUCING ANGGORA', '2.00', '1.00', 100, '2.00', '1.00', '1.00', '1.00', NULL, NULL, NULL, 1, 10, '2025-09-16 03:10:15', '2025-09-16 03:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `rekaman_stoks`
--

CREATE TABLE `rekaman_stoks` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `stok_masuk` int DEFAULT NULL,
  `stok_keluar` int DEFAULT NULL,
  `id_penjualan` int DEFAULT NULL,
  `id_pembelian` int DEFAULT NULL,
  `stok_awal` int DEFAULT NULL,
  `stok_sisa` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekaman_stoks`
--

INSERT INTO `rekaman_stoks` (`id`, `product_id`, `waktu`, `stok_masuk`, `stok_keluar`, `id_penjualan`, `id_pembelian`, `stok_awal`, `stok_sisa`, `created_at`, `updated_at`) VALUES
(1, 113, '2025-07-03 22:53:16', 1, NULL, NULL, NULL, 98, 99, '2025-07-03 22:53:16', '2025-07-03 22:53:16'),
(2, 113, '2025-07-03 22:53:34', 2, NULL, NULL, NULL, 98, 100, '2025-07-03 22:53:34', '2025-07-03 22:53:34'),
(3, 113, '2025-07-03 22:53:56', 3, NULL, NULL, NULL, 98, 101, '2025-07-03 22:53:56', '2025-07-03 22:53:56'),
(4, 113, '2025-07-03 22:53:56', 4, NULL, NULL, NULL, 98, 102, '2025-07-03 22:53:56', '2025-07-03 22:53:56'),
(5, 113, '2025-07-03 22:54:37', 4, NULL, NULL, 1, 98, 102, '2025-07-03 22:54:37', '2025-07-03 22:54:37'),
(6, 113, '2025-07-03 22:57:59', 1, NULL, NULL, NULL, 102, 103, '2025-07-03 22:57:59', '2025-07-03 22:57:59'),
(7, 113, '2025-07-03 22:57:59', 10, NULL, NULL, NULL, 102, 112, '2025-07-03 22:57:59', '2025-07-03 22:57:59'),
(8, 113, '2025-07-03 22:58:05', 10, NULL, NULL, 2, 102, 112, '2025-07-03 22:58:05', '2025-07-03 22:58:05'),
(9, 113, '2025-07-04 18:25:00', 10, NULL, NULL, NULL, 112, 122, '2025-07-04 18:25:00', '2025-07-04 18:25:00'),
(10, 113, '2025-07-04 18:25:06', 10, NULL, NULL, 3, 112, 122, '2025-07-04 18:25:06', '2025-07-04 18:25:06'),
(11, 114, '2025-07-04 18:29:43', 10, NULL, NULL, NULL, 100, 110, '2025-07-04 18:29:43', '2025-07-04 18:29:43'),
(12, 114, '2025-07-04 18:29:51', 10, NULL, NULL, 4, 100, 110, '2025-07-04 18:29:51', '2025-07-04 18:29:51'),
(13, 114, '2025-07-08 12:32:38', 10, NULL, NULL, NULL, 110, 120, '2025-07-08 12:32:38', '2025-07-08 12:32:38'),
(14, 113, '2025-07-08 12:32:39', 10, NULL, NULL, NULL, 122, 132, '2025-07-08 12:32:39', '2025-07-08 12:32:39'),
(15, 113, '2025-07-08 12:35:00', 10, NULL, NULL, NULL, 122, 132, '2025-07-08 12:35:00', '2025-07-08 12:35:00'),
(16, 113, '2025-07-08 13:02:28', 10, NULL, NULL, NULL, 122, 132, '2025-07-08 13:02:28', '2025-07-08 13:02:28'),
(17, 114, '2025-07-08 13:05:50', 10, NULL, NULL, 6, 110, 120, '2025-07-08 13:05:50', '2025-07-08 13:05:50'),
(18, 113, '2025-07-08 13:05:50', 10, NULL, NULL, 6, 122, 132, '2025-07-08 13:05:50', '2025-07-08 13:05:50'),
(19, 113, '2025-07-08 14:05:30', 10, NULL, NULL, NULL, 132, 142, '2025-07-08 14:05:30', '2025-07-08 14:05:30'),
(20, 114, '2025-07-08 14:05:31', 10, NULL, NULL, NULL, 120, 130, '2025-07-08 14:05:31', '2025-07-08 14:05:31'),
(21, 113, '2025-07-08 14:06:18', 10, NULL, NULL, 7, 132, 142, '2025-07-08 14:06:18', '2025-07-08 14:06:18'),
(22, 114, '2025-07-08 14:06:18', 10, NULL, NULL, 7, 120, 130, '2025-07-08 14:06:18', '2025-07-08 14:06:18'),
(24, 9, '2025-09-14 05:59:38', 10, NULL, NULL, 12, 20, 30, '2025-09-14 05:59:38', '2025-09-14 05:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_toko` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_toko`, `alamat`, `telepon`, `path_logo`, `created_at`, `updated_at`) VALUES
(1, 'ViviaShop', 'Tebuireng', '081411111769', NULL, '2024-11-10 01:50:57', '2025-07-26 12:58:37'),
(2, 'ViviaShop', 'Jalan Mojolangu', '0182190410', 'Jalan Mojolangu', '2025-05-25 22:33:01', '2025-05-25 22:33:01'),
(3, 'ViviaShop', 'Jalan Mojolangu', '0182190410', 'Jalan Mojolangu', '2025-05-25 22:35:23', '2025-05-25 22:35:23');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `track_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` int NOT NULL,
  `total_weight` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` int DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `shipped_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `track_number`, `status`, `total_qty`, `total_weight`, `name`, `address1`, `address2`, `phone`, `email`, `city_id`, `province_id`, `postcode`, `shipped_at`, `user_id`, `order_id`, `shipped_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(5, NULL, 'pending', 1, 25, 'admin', 'haujfgua9ho', 'gauhjsbugaosfnbaugosn', '80298498231489', 'admin@admin.com', '177', '10', 412980, NULL, 1, 6, NULL, NULL, '2025-05-11 10:26:42', '2025-05-11 10:26:42'),
(7, NULL, 'pending', 1, 25, 'admin', 'haujfgua9ho', 'gauhjsbugaosfnbaugosn', '80298498231489', 'admin@admin.com', '28', '2', 412980, NULL, 1, 8, NULL, NULL, '2025-05-11 10:37:58', '2025-05-11 10:37:58'),
(8, NULL, 'pending', 1, 25, 'admin', 'haujfgua9ho', 'gauhjsbugaosfnbaugosn', '80298498231489', 'admin@admin.com', '403', '3', 412980, NULL, 1, 9, NULL, NULL, '2025-05-11 10:41:20', '2025-05-11 10:41:20'),
(9, NULL, 'pending', 1, 25, 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'araihanrizki@gmail.com', '17', '1', 17517, NULL, 1, 50, NULL, NULL, '2025-05-11 13:44:17', '2025-05-11 13:44:17'),
(10, 'sadf', 'shipped', 1, 25, 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'araihanrizki@gmail.com', '27', '2', 17517, '2025-05-11 13:48:45', 1, 51, 1, NULL, '2025-05-11 13:47:09', '2025-05-11 13:48:45'),
(11, NULL, 'pending', 1, 25, 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'admin@admin.com', '290', '11', 17517, NULL, 1, 52, NULL, NULL, '2025-05-25 15:12:20', '2025-05-25 15:12:20'),
(12, NULL, 'pending', 1, 25, 'admin', 'akhfkahf', 'gauhjsbugaosfnbaugosn', '17351745174', 'admin@admin.com', '290', '11', 17517, NULL, 1, 53, NULL, NULL, '2025-05-25 16:56:27', '2025-05-25 16:56:27'),
(13, NULL, 'pending', 1, 25, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 58, NULL, NULL, '2025-06-30 10:45:41', '2025-06-30 10:45:41'),
(14, NULL, 'pending', 1, 25, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 59, NULL, NULL, '2025-06-30 12:00:35', '2025-06-30 12:00:35'),
(15, NULL, 'pending', 1, 25, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 60, NULL, NULL, '2025-06-30 12:25:02', '2025-06-30 12:25:02'),
(16, NULL, 'pending', 1, 25, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 61, NULL, NULL, '2025-06-30 12:28:19', '2025-06-30 12:28:19'),
(17, '4827482582758275', 'shipped', 1, 25, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, '2025-06-30 12:37:42', 1, 62, 1, NULL, '2025-06-30 12:35:46', '2025-06-30 12:37:42'),
(18, '9147284782748274', 'shipped', 1, 2500, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, '2025-06-30 12:45:59', 1, 63, 1, NULL, '2025-06-30 12:44:32', '2025-06-30 12:45:59'),
(19, 'fkshfksh816481648', 'shipped', 1, 1000, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, '2025-06-30 14:16:38', 1, 64, 1, NULL, '2025-06-30 14:14:58', '2025-06-30 14:16:38'),
(20, 'kshfkshfk2864826428', 'shipped', 1, 1000, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, '2025-06-30 14:20:23', 1, 65, 1, NULL, '2025-06-30 14:19:39', '2025-06-30 14:20:23'),
(21, NULL, 'pending', 1, 2500, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 66, NULL, NULL, '2025-07-02 21:25:29', '2025-07-02 21:25:29'),
(22, NULL, 'pending', 2, 5000, 'Fanani Agung', 'Tebuireng IV Cukir', 'Jombang', '08113476769', 'fanani5758@gmail.com', '164', '11', 61471, NULL, 12, 67, NULL, NULL, '2025-07-07 22:58:34', '2025-07-07 22:58:34'),
(23, NULL, 'pending', 2, 1010, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 68, NULL, NULL, '2025-07-08 14:12:25', '2025-07-08 14:12:25'),
(24, NULL, 'pending', 2, 1010, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 69, NULL, NULL, '2025-07-08 14:23:01', '2025-07-08 14:23:01'),
(26, NULL, 'pending', 1, 1000, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 71, NULL, NULL, '2025-07-08 14:37:13', '2025-07-08 14:37:13'),
(27, NULL, 'pending', 2, 1010, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 72, NULL, NULL, '2025-07-08 15:00:33', '2025-07-08 15:00:33'),
(28, NULL, 'pending', 2, 1010, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 73, NULL, NULL, '2025-07-08 15:05:21', '2025-07-08 15:05:21'),
(29, NULL, 'pending', 2, 1010, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '290', '11', 61471, NULL, 1, 74, NULL, NULL, '2025-07-08 15:12:29', '2025-07-08 15:12:29'),
(30, '2425353', 'shipped', 1, 100, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-09-06 09:53:32', 1, 91, 1, NULL, '2025-08-13 06:06:44', '2025-09-06 02:53:32'),
(33, NULL, 'pending', 1, 100, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 94, NULL, NULL, '2025-08-13 06:28:19', '2025-08-13 06:28:19'),
(34, NULL, 'pending', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 95, NULL, NULL, '2025-08-13 06:41:41', '2025-08-13 06:41:41'),
(35, NULL, 'pending', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 96, NULL, NULL, '2025-08-13 06:46:03', '2025-08-13 06:46:03'),
(36, NULL, 'pending', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 97, NULL, NULL, '2025-08-13 06:50:02', '2025-08-13 06:50:02'),
(37, NULL, 'pending', 1, 0, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 98, NULL, NULL, '2025-08-13 06:59:39', '2025-08-13 06:59:39'),
(38, NULL, 'pending', 1, 0, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 99, NULL, NULL, '2025-08-13 07:07:49', '2025-08-13 07:07:49'),
(39, NULL, 'pending', 1, 10, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 100, NULL, NULL, '2025-08-13 13:14:46', '2025-08-13 13:14:46'),
(40, NULL, 'shipped', 1, 100, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 101, NULL, NULL, '2025-08-13 13:33:17', '2025-08-13 13:33:17'),
(41, NULL, 'shipped', 1, 100, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-08-13 21:26:38', 1, 102, 1, NULL, '2025-08-13 13:45:44', '2025-08-13 14:26:38'),
(42, NULL, 'shipped', 1, 100, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-08-13 21:29:16', 1, 103, 1, NULL, '2025-08-13 14:27:43', '2025-08-13 14:29:16'),
(43, NULL, 'pending', 2, 0, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 112, NULL, NULL, '2025-08-14 18:47:41', '2025-08-14 18:47:41'),
(44, NULL, 'shipped', 2, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-08-15 01:50:02', 1, 113, 1, NULL, '2025-08-14 18:48:41', '2025-08-14 18:50:02'),
(45, NULL, 'pending', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 114, NULL, NULL, '2025-09-06 11:04:14', '2025-09-06 11:04:14'),
(46, NULL, 'pending', 0, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, NULL, 1, 115, NULL, NULL, '2025-09-06 11:05:03', '2025-09-06 11:05:03'),
(47, NULL, 'shipped', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-09-08 10:01:20', 1, 116, 1, NULL, '2025-09-06 11:05:59', '2025-09-08 03:01:20'),
(48, '66151442', 'shipped', 1, 0, 'admin', 'ajdgajdgajgd', 'gauhjsbugaosfnbaugosn', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-09-08 10:24:14', 1, 117, 1, NULL, '2025-09-08 03:22:46', '2025-09-08 03:24:14'),
(49, NULL, 'shipped', 2, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-09-09 08:49:58', 1, 140, 1, NULL, '2025-09-09 01:47:47', '2025-09-09 01:49:58'),
(50, NULL, 'shipped', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-09-09 09:11:45', 1, 141, 1, NULL, '2025-09-09 01:54:37', '2025-09-09 02:11:45'),
(51, NULL, 'shipped', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '19319461964914', 'admin@admin.com', '388', '18', 61471, '2025-09-09 09:57:07', 1, 142, 1, NULL, '2025-09-09 02:55:07', '2025-09-09 02:57:07'),
(52, NULL, 'pending', 0, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test@example.com', '1', '1', 12345, NULL, 188, 165, NULL, NULL, '2025-09-10 03:40:19', '2025-09-10 03:40:19'),
(54, NULL, 'pending', 0, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test@example.com', '1', '1', 12345, NULL, 188, 167, NULL, NULL, '2025-09-10 03:40:23', '2025-09-10 03:40:23'),
(55, NULL, 'pending', 0, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test@example.com', '1', '1', 12345, NULL, 188, 168, NULL, NULL, '2025-09-10 03:40:25', '2025-09-10 03:40:25'),
(56, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test1757476892@example.com', '388', '18', 12345, NULL, 1, 171, NULL, NULL, '2025-09-10 04:01:33', '2025-09-10 04:01:33'),
(57, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'debug1757477063@example.com', '388', '18', 12345, NULL, 1, 172, NULL, NULL, '2025-09-10 04:04:23', '2025-09-10 04:04:23'),
(58, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test17574770743138@example.com', '388', '18', 12345, NULL, 1, 173, NULL, NULL, '2025-09-10 04:04:34', '2025-09-10 04:04:34'),
(59, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test17574770743899@example.com', '388', '18', 12345, NULL, 1, 174, NULL, NULL, '2025-09-10 04:04:35', '2025-09-10 04:04:35'),
(60, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test17574770757582@example.com', '388', '18', 12345, NULL, 1, 175, NULL, NULL, '2025-09-10 04:04:37', '2025-09-10 04:04:37'),
(61, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test17574770774809@example.com', '388', '18', 12345, NULL, 1, 176, NULL, NULL, '2025-09-10 04:04:37', '2025-09-10 04:04:37'),
(62, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test_manual_self_17574773283950@example.com', '388', '18', 12345, NULL, 1, 177, NULL, NULL, '2025-09-10 04:08:48', '2025-09-10 04:08:48'),
(63, NULL, 'pending', 1, 1, 'Test manual_courier 17574773289726', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_manual_courier_17574773289726@example.com', '388', '18', 12345, NULL, 1, 178, NULL, NULL, '2025-09-10 04:08:49', '2025-09-10 04:08:49'),
(64, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test_automatic_self_17574773292092@example.com', '388', '18', 12345, NULL, 1, 179, NULL, NULL, '2025-09-10 04:08:50', '2025-09-10 04:08:50'),
(65, NULL, 'pending', 1, 1, 'Test automatic_courier 17574773303840', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_automatic_courier_17574773303840@example.com', '388', '18', 12345, NULL, 1, 180, NULL, NULL, '2025-09-10 04:08:51', '2025-09-10 04:08:51'),
(66, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test_cod_self_17574773323157@example.com', '388', '18', 12345, NULL, 1, 181, NULL, NULL, '2025-09-10 04:08:52', '2025-09-10 04:08:52'),
(67, NULL, 'pending', 1, 1, 'Test cod_courier 17574773324633', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_cod_courier_17574773324633@example.com', '388', '18', 12345, NULL, 1, 182, NULL, NULL, '2025-09-10 04:08:53', '2025-09-10 04:08:53'),
(68, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'test_toko_self_17574773339025@example.com', '388', '18', 12345, NULL, 1, 183, NULL, NULL, '2025-09-10 04:08:54', '2025-09-10 04:08:54'),
(69, NULL, 'pending', 1, 1, 'Test toko_courier 17574773345951', 'Jl. Test Stress No. 123', 'Unit Test Suite', '081234567890', 'test_toko_courier_17574773345951@example.com', '388', '18', 12345, NULL, 1, 184, NULL, NULL, '2025-09-10 04:08:55', '2025-09-10 04:08:55'),
(70, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'frontend_test_1757477586@example.com', '388', '18', 12345, NULL, 1, 187, NULL, NULL, '2025-09-10 04:13:06', '2025-09-10 04:13:06'),
(71, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'john_1757477891@example.com', '388', '18', 12345, NULL, 1, 188, NULL, NULL, '2025-09-10 04:18:11', '2025-09-10 04:18:11'),
(72, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081987654321', 'jane_1757477891@example.com', '388', '18', 54321, NULL, 1, 189, NULL, NULL, '2025-09-10 04:18:12', '2025-09-10 04:18:12'),
(73, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'john_1757477949@example.com', '388', '18', 12345, NULL, 1, 190, NULL, NULL, '2025-09-10 04:19:09', '2025-09-10 04:19:09'),
(74, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081987654321', 'jane_1757477949@example.com', '388', '18', 54321, NULL, 1, 191, NULL, NULL, '2025-09-10 04:19:10', '2025-09-10 04:19:10'),
(75, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'john_1757478025@example.com', '388', '18', 12345, NULL, 1, 192, NULL, NULL, '2025-09-10 04:20:25', '2025-09-10 04:20:25'),
(76, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081987654321', 'jane_1757478025@example.com', '388', '18', 54321, NULL, 1, 193, NULL, NULL, '2025-09-10 04:20:26', '2025-09-10 04:20:26'),
(77, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'manual_1757478102@example.com', '388', '18', 12345, NULL, 1, 194, NULL, NULL, '2025-09-10 04:21:42', '2025-09-10 04:21:42'),
(78, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081987654321', 'midtrans_1757478102@example.com', '388', '18', 54321, NULL, 1, 195, NULL, NULL, '2025-09-10 04:21:43', '2025-09-10 04:21:43'),
(79, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081555444333', 'cod_1757478102@example.com', '388', '18', 67890, NULL, 1, 196, NULL, NULL, '2025-09-10 04:21:44', '2025-09-10 04:21:44'),
(80, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081666777888', 'toko_1757478102@example.com', '388', '18', 99999, NULL, 1, 197, NULL, NULL, '2025-09-10 04:21:45', '2025-09-10 04:21:45'),
(81, NULL, 'pending', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '085155228237', 'reza@gmail.com', '388', '18', 61319, NULL, 189, 201, NULL, NULL, '2025-09-10 04:38:45', '2025-09-10 04:38:45'),
(82, NULL, 'pending', 1, 0, 'Ambil di Toko', 'Toko ViVia Shop', '', '085155228237', 'reza@gmail.com', '388', '18', 61319, NULL, 189, 202, NULL, NULL, '2025-09-10 04:41:42', '2025-09-10 04:41:42'),
(84, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 204, NULL, NULL, '2025-09-10 04:53:15', '2025-09-10 04:53:15'),
(85, NULL, 'pending', 1, 1, 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 205, NULL, NULL, '2025-09-10 04:53:16', '2025-09-10 04:53:16'),
(86, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 206, NULL, NULL, '2025-09-10 04:53:17', '2025-09-10 04:53:17'),
(87, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 207, NULL, NULL, '2025-09-10 05:00:37', '2025-09-10 05:00:37'),
(88, NULL, 'pending', 1, 1, 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 208, NULL, NULL, '2025-09-10 05:00:38', '2025-09-10 05:00:38'),
(89, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 209, NULL, NULL, '2025-09-10 05:00:39', '2025-09-10 05:00:39'),
(90, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 210, NULL, NULL, '2025-09-10 05:01:19', '2025-09-10 05:01:19'),
(91, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 211, NULL, NULL, '2025-09-10 05:01:20', '2025-09-10 05:01:20'),
(92, NULL, 'pending', 1, 1, 'Test User Toko', 'Test Address 1', 'Test Address 2', '081234567890', 'toko_1757478102@example.com', '388', '18', 12345, NULL, 1, 212, NULL, NULL, '2025-09-10 05:01:21', '2025-09-10 05:01:21'),
(93, NULL, 'shipped', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '085155228237', 'reza@gmail.com', '388', '18', 61319, '2025-09-10 12:16:27', 189, 213, 190, NULL, '2025-09-10 05:09:39', '2025-09-10 05:16:27'),
(94, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, NULL, 190, 214, NULL, NULL, '2025-09-13 04:47:42', '2025-09-13 04:47:42'),
(95, '74726472642', 'shipped', 1, 1, 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, '2025-09-13 12:40:45', 190, 215, 190, NULL, '2025-09-13 04:49:06', '2025-09-13 05:40:45'),
(96, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 223, NULL, NULL, '2025-09-14 07:15:58', '2025-09-14 07:15:58'),
(97, NULL, 'pending', 3, 3, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 224, NULL, NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59'),
(98, NULL, 'pending', 3, 3, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 225, NULL, NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39'),
(99, NULL, 'pending', 3, 3, 'Test User 1757834259', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 226, NULL, NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40'),
(100, NULL, 'pending', 3, 3, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 227, NULL, NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41'),
(101, NULL, 'pending', 3, 3, 'Test User 1757834261', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 228, NULL, NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42'),
(102, NULL, 'pending', 3, 3, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 229, NULL, NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43'),
(103, NULL, 'pending', 3, 3, 'Test User 1757834263', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 230, NULL, NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44'),
(104, NULL, 'pending', 3, 3, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 231, NULL, NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45'),
(105, NULL, 'pending', 3, 3, 'Test User 1757834266', 'Test Address 1', 'Test Address 2', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 232, NULL, NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46'),
(106, NULL, 'pending', 1, 1, 'Ambil di Toko', 'Toko ViVia Shop', '', '08123456789', 'test@example.com', '1', '1', 12345, NULL, 188, 233, NULL, NULL, '2025-09-14 07:19:08', '2025-09-14 07:19:08'),
(107, NULL, 'pending', 10, 1000, 'Ambil di Toko', 'Toko ViVia Shop', '', '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, NULL, 190, 234, NULL, NULL, '2025-09-14 07:19:47', '2025-09-14 07:19:47'),
(108, '842482642', 'shipped', 67, 67, 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, '2025-09-14 17:52:21', 190, 235, 190, NULL, '2025-09-14 10:49:26', '2025-09-14 10:52:21'),
(109, '1635175347143', 'shipped', 4, 4, 'Raihan Rizki Alfareza', 'Jalan Gedongan VII/12 A', NULL, '082131831262', 'araihanrizki@gmail.com', '388', '18', 61319, '2025-09-15 13:48:38', 190, 246, 190, NULL, '2025-09-15 05:46:15', '2025-09-15 06:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcart`
--

CREATE TABLE `shoppingcart` (
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `movement_type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `old_stock` int NOT NULL,
  `new_stock` int NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `variant_id`, `movement_type`, `quantity`, `old_stock`, `new_stock`, `reference_type`, `reference_id`, `reason`, `notes`, `created_at`, `updated_at`) VALUES
(1, 16, 'in', 25, 6, 31, 'print_order', 999, 'test_cancel', 'Stock restored from cancelled print order', '2025-09-11 10:23:03', '2025-09-11 10:23:03'),
(2, 16, 'in', 100, 31, 131, 'manual', NULL, 'test_adjustment', 'Test stock adjustment', '2025-09-11 10:23:03', '2025-09-11 10:23:03'),
(3, 32, 'in', 25, 8, 33, 'print_order', 999, 'test_cancel', 'Stock restored from cancelled print order', '2025-09-11 10:23:24', '2025-09-11 10:23:24'),
(4, 32, 'in', 100, 33, 133, 'manual', NULL, 'test_adjustment', 'Test stock adjustment', '2025-09-11 10:23:24', '2025-09-11 10:23:24'),
(5, 14, 'in', 25, 9, 34, 'print_order', 999, 'test_cancel', 'Stock restored from cancelled print order', '2025-09-11 10:23:40', '2025-09-11 10:23:40'),
(6, 14, 'in', 100, 34, 134, 'manual', NULL, 'test_adjustment', 'Test stock adjustment', '2025-09-11 10:23:40', '2025-09-11 10:23:40'),
(7, 57, 'out', 10, 10000, 9990, 'print_order', 37, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 10:23:41', '2025-09-11 10:23:41'),
(8, 57, 'in', 10, 9990, 10000, 'print_order', 37, 'order_cancelled', 'Stock restored from cancelled print order', '2025-09-11 10:23:41', '2025-09-11 10:23:41'),
(9, 57, 'out', 1, 10000, 9999, 'print_order', 38, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 10:28:57', '2025-09-11 10:28:57'),
(10, 45, 'out', 1, 10000, 9999, 'print_order', 39, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:10:35', '2025-09-11 11:10:35'),
(11, 45, 'in', 1, 9999, 10000, 'print_order', 39, 'order_cancelled', 'Stock restored from cancelled print order', '2025-09-11 11:10:35', '2025-09-11 11:10:35'),
(12, 45, 'out', 10, 10000, 9990, 'print_order', 40, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:14:29', '2025-09-11 11:14:29'),
(13, 45, 'in', 1000, 9990, 10990, 'manual', NULL, 'restock', 'Test restock adjustment', '2025-09-11 11:14:29', '2025-09-11 11:14:29'),
(14, 45, 'out', 10, 10990, 10980, 'print_order', 41, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:15:21', '2025-09-11 11:15:21'),
(15, 45, 'in', 1000, 10980, 11980, 'manual', NULL, 'restock', 'Test restock adjustment', '2025-09-11 11:15:21', '2025-09-11 11:15:21'),
(16, 45, 'in', 10, 11980, 11990, 'print_order', 41, 'order_cancelled', 'Stock restored from cancelled print order', '2025-09-11 11:15:21', '2025-09-11 11:15:21'),
(17, 48, 'out', 3, 2000, 1997, 'print_order', 42, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:17:20', '2025-09-11 11:17:20'),
(18, 45, 'out', 2, 11990, 11988, 'print_order', 44, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:40:29', '2025-09-11 11:40:29'),
(19, 45, 'out', 2, 11988, 11986, 'print_order', 45, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:42:46', '2025-09-11 11:42:46'),
(20, 45, 'out', 1, 11986, 11985, 'print_order', 46, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:50:42', '2025-09-11 11:50:42'),
(21, 45, 'out', 3, 21984, 21981, 'print_order', 47, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 11:56:54', '2025-09-11 11:56:54'),
(22, 46, 'out', 1, 10000, 9999, 'print_order', 48, 'order_confirmed', 'Stock reduced for print order', '2025-09-11 12:05:17', '2025-09-11 12:05:17'),
(23, 63, 'in', 100, 65, 165, 'purchase', 10, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 02:54:57', '2025-09-14 02:54:57'),
(24, 63, 'in', 5, 165, 170, 'test', 999, 'Test Purchase', NULL, '2025-09-14 03:27:11', '2025-09-14 03:27:11'),
(25, 63, 'in', 5, 170, 175, 'test', 999, 'Test Purchase', NULL, '2025-09-14 03:27:59', '2025-09-14 03:27:59'),
(26, 36, 'in', 10, 30, 40, 'purchase', 12, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 05:59:38', '2025-09-14 05:59:38'),
(27, 64, 'in', 5, 25, 30, 'test', 999, 'manual_adjustment', 'Testing simple product stock movement', '2025-09-14 06:15:53', '2025-09-14 06:15:53'),
(28, 64, 'out', 5, 20, 15, 'test', 999, 'manual_adjustment', 'Reversing test movement', '2025-09-14 06:15:53', '2025-09-14 06:15:53'),
(29, 64, 'in', 15, 35, 50, 'purchase', 16, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 06:16:48', '2025-09-14 06:16:48'),
(30, 17, 'in', 82, 10, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(10) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(31, 17, 'in', 80, 12, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(12) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(32, 17, 'in', 79, 13, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(13) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(33, 17, 'in', 78, 14, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(14) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(34, 17, 'in', 77, 15, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(15) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(35, 17, 'in', 72, 20, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(20) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(36, 17, 'in', 67, 25, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(25) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(37, 17, 'in', 62, 30, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(30) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(38, 17, 'in', 62, 30, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(30) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(39, 17, 'in', 52, 40, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(40) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(40, 17, 'in', 42, 50, 92, 'system', NULL, 'stock_synchronization', 'Automatic sync: Inventory(92) -> Variant(50) -> Correct(92)', '2025-09-14 06:42:19', '2025-09-14 06:42:19'),
(41, 64, 'in', 10, 60, 70, 'test', 999, 'manual_adjustment', 'Test stock movement', '2025-09-14 06:47:58', '2025-09-14 06:47:58'),
(42, 64, 'out', 3, 70, 67, 'test', 999, 'manual_adjustment', 'Ultimate validation test', '2025-09-14 06:50:49', '2025-09-14 06:50:49'),
(43, 64, 'in', 100, 67, 167, 'purchase', 20, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 06:52:23', '2025-09-14 06:52:23'),
(44, 36, 'in', 100, 40, 140, 'purchase', 20, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 06:52:23', '2025-09-14 06:52:23'),
(45, 63, 'out', 1, 175, 174, 'Frontend Sale', 223, 'Order #INV-14-09-2025-14-15-58', NULL, '2025-09-14 07:15:58', '2025-09-14 07:15:58'),
(46, 63, 'out', 1, 174, 173, 'Frontend Sale', 224, 'Order #INV-14-09-2025-14-16-59', NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59'),
(47, 65, 'out', 1, 9, 8, 'Frontend Sale', 224, 'Order #INV-14-09-2025-14-16-59', NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59'),
(48, 66, 'out', 1, 9, 8, 'Frontend Sale', 224, 'Order #INV-14-09-2025-14-16-59', NULL, '2025-09-14 07:16:59', '2025-09-14 07:16:59'),
(49, 63, 'out', 1, 173, 172, 'Frontend Sale', 225, 'Order #INV-14-09-2025-14-17-39', NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39'),
(50, 65, 'out', 1, 8, 7, 'Frontend Sale', 225, 'Order #INV-14-09-2025-14-17-39', NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39'),
(51, 66, 'out', 1, 8, 7, 'Frontend Sale', 225, 'Order #INV-14-09-2025-14-17-39', NULL, '2025-09-14 07:17:39', '2025-09-14 07:17:39'),
(52, 63, 'out', 1, 172, 171, 'Frontend Sale', 226, 'Order #INV-14-09-2025-14-17-40', NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40'),
(53, 65, 'out', 1, 7, 6, 'Frontend Sale', 226, 'Order #INV-14-09-2025-14-17-40', NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40'),
(54, 66, 'out', 1, 7, 6, 'Frontend Sale', 226, 'Order #INV-14-09-2025-14-17-40', NULL, '2025-09-14 07:17:40', '2025-09-14 07:17:40'),
(55, 63, 'out', 1, 171, 170, 'Frontend Sale', 227, 'Order #INV-14-09-2025-14-17-41', NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41'),
(56, 65, 'out', 1, 6, 5, 'Frontend Sale', 227, 'Order #INV-14-09-2025-14-17-41', NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41'),
(57, 66, 'out', 1, 6, 5, 'Frontend Sale', 227, 'Order #INV-14-09-2025-14-17-41', NULL, '2025-09-14 07:17:41', '2025-09-14 07:17:41'),
(58, 63, 'out', 1, 170, 169, 'Frontend Sale', 228, 'Order #INV-14-09-2025-14-17-42', NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42'),
(59, 65, 'out', 1, 5, 4, 'Frontend Sale', 228, 'Order #INV-14-09-2025-14-17-42', NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42'),
(60, 66, 'out', 1, 5, 4, 'Frontend Sale', 228, 'Order #INV-14-09-2025-14-17-42', NULL, '2025-09-14 07:17:42', '2025-09-14 07:17:42'),
(61, 63, 'out', 1, 169, 168, 'Frontend Sale', 229, 'Order #INV-14-09-2025-14-17-43', NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43'),
(62, 65, 'out', 1, 4, 3, 'Frontend Sale', 229, 'Order #INV-14-09-2025-14-17-43', NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43'),
(63, 66, 'out', 1, 4, 3, 'Frontend Sale', 229, 'Order #INV-14-09-2025-14-17-43', NULL, '2025-09-14 07:17:43', '2025-09-14 07:17:43'),
(64, 63, 'out', 1, 168, 167, 'Frontend Sale', 230, 'Order #INV-14-09-2025-14-17-44', NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44'),
(65, 65, 'out', 1, 3, 2, 'Frontend Sale', 230, 'Order #INV-14-09-2025-14-17-44', NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44'),
(66, 66, 'out', 1, 3, 2, 'Frontend Sale', 230, 'Order #INV-14-09-2025-14-17-44', NULL, '2025-09-14 07:17:44', '2025-09-14 07:17:44'),
(67, 63, 'out', 1, 167, 166, 'Frontend Sale', 231, 'Order #INV-14-09-2025-14-17-45', NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45'),
(68, 65, 'out', 1, 2, 1, 'Frontend Sale', 231, 'Order #INV-14-09-2025-14-17-45', NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45'),
(69, 66, 'out', 1, 2, 1, 'Frontend Sale', 231, 'Order #INV-14-09-2025-14-17-45', NULL, '2025-09-14 07:17:45', '2025-09-14 07:17:45'),
(70, 63, 'out', 1, 166, 165, 'Frontend Sale', 232, 'Order #INV-14-09-2025-14-17-46', NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46'),
(71, 65, 'out', 1, 1, 0, 'Frontend Sale', 232, 'Order #INV-14-09-2025-14-17-46', NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46'),
(72, 66, 'out', 1, 1, 0, 'Frontend Sale', 232, 'Order #INV-14-09-2025-14-17-46', NULL, '2025-09-14 07:17:46', '2025-09-14 07:17:46'),
(73, 63, 'out', 1, 165, 164, 'Frontend Sale', 233, 'Order #INV-14-09-2025-14-19-08', NULL, '2025-09-14 07:19:08', '2025-09-14 07:19:08'),
(74, 36, 'out', 10, 140, 130, 'Frontend Sale', 234, 'Order #INV-14-09-2025-14-19-46', NULL, '2025-09-14 07:19:46', '2025-09-14 07:19:46'),
(75, 64, 'out', 67, 167, 100, 'Frontend Sale', 235, 'Order #INV-14-09-2025-17-49-26', NULL, '2025-09-14 10:49:26', '2025-09-14 10:49:26'),
(77, 64, 'out', 3, 30, 27, 'order', 236, 'Admin Offline Sale', 'Order #INV-14-09-2025-18-45-09', '2025-09-14 11:55:13', '2025-09-14 11:55:13'),
(78, 64, 'in', 73, 27, 100, 'purchase', 21, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 12:29:49', '2025-09-14 12:29:49'),
(79, 64, 'out', 20, 80, 60, 'order', 238, 'Admin Offline Sale', 'Order #INV-14-09-2025-19-31-17', '2025-09-14 12:31:32', '2025-09-14 12:31:32'),
(81, 64, 'out', 5, 60, 55, 'order', 239, 'Admin Offline Sale', 'Order #239 (corrected)', '2025-09-14 12:50:27', '2025-09-14 12:50:27'),
(84, 64, 'out', 5, 55, 50, 'order', 243, 'Admin Offline Sale', 'Order #INV-14-09-2025-19-56-40', '2025-09-14 12:56:45', '2025-09-14 12:56:45'),
(85, 64, 'out', 5, 50, 45, 'order', 244, 'Admin Offline Sale', 'Order #INV-14-09-2025-20-36-43', '2025-09-14 13:36:49', '2025-09-14 13:36:49'),
(86, 64, 'in', 55, 45, 100, 'purchase', 22, 'purchase_confirmed', 'Purchase from supplier: Raihan Rizki Alfareza', '2025-09-14 14:15:20', '2025-09-14 14:15:20'),
(87, 67, 'out', 10, 100, 90, 'order', 245, 'Admin Offline Sale', 'Order #INV-14-09-2025-21-59-30', '2025-09-14 14:59:55', '2025-09-14 14:59:55'),
(88, 63, 'out', 4, 164, 160, 'Frontend Sale', 246, 'Order #INV-15-09-2025-12-46-14', NULL, '2025-09-15 05:46:15', '2025-09-15 05:46:15'),
(89, 63, 'out', 4, 160, 156, 'order', 246, 'Admin Sale', 'Order #INV-15-09-2025-12-46-14', '2025-09-15 06:48:43', '2025-09-15 06:48:43'),
(90, 67, 'out', 1, 90, 89, 'order', 247, 'Admin Offline Sale', 'Order #INV-15-09-2025-22-35-22', '2025-09-15 15:36:17', '2025-09-15 15:36:17'),
(91, 46, 'out', 1, 9999, 9998, 'print_order', 50, 'order_confirmed', 'Stock reduced for print order', '2025-09-15 15:39:20', '2025-09-15 15:39:20'),
(94, 46, 'out', 1, 9998, 9997, 'print_order', 999999, 'test_reduction', NULL, '2025-09-15 16:48:11', '2025-09-15 16:48:11'),
(95, 46, 'in', 1, 9997, 9998, 'print_order', 999999, 'test_restore', NULL, '2025-09-15 16:48:11', '2025-09-15 16:48:11'),
(96, 46, 'out', 1, 9998, 9997, 'print_order', 61, 'order_confirmed', NULL, '2025-09-15 17:04:29', '2025-09-15 17:04:29'),
(97, 46, 'out', 2, 9997, 9995, 'print_order', 60, 'order_confirmed', NULL, '2025-09-15 17:04:29', '2025-09-15 17:04:29'),
(98, 46, 'out', 1, 9995, 9994, 'print_order', 59, 'order_confirmed', NULL, '2025-09-15 17:04:29', '2025-09-15 17:04:29'),
(99, 46, 'out', 1, 9994, 9993, 'print_order', 58, 'order_confirmed', NULL, '2025-09-15 17:04:29', '2025-09-15 17:04:29'),
(100, 46, 'out', 1, 9993, 9992, 'print_order', 62, 'order_confirmed', 'Stock reduction for completed order (manual fix)', '2025-09-15 17:12:32', '2025-09-15 17:12:32'),
(102, 46, 'out', 1, 9992, 9991, 'print_order', 63, 'order_confirmed', 'Stock reduced for print order', '2025-09-15 17:15:53', '2025-09-15 17:15:53'),
(103, 46, 'out', 1, 9991, 9990, 'print_order', 64, 'order_confirmed', 'Missing stock movement for paid order (auto-fix)', '2025-09-15 17:25:08', '2025-09-15 17:25:08'),
(104, 46, 'out', 75, 9990, 9915, 'print_order', 5, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(105, 46, 'out', 15, 9915, 9900, 'print_order', 6, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(106, 46, 'out', 15, 9900, 9885, 'print_order', 14, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(107, 46, 'out', 15, 9885, 9870, 'print_order', 27, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(108, 46, 'out', 15, 9870, 9855, 'print_order', 29, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(109, 46, 'out', 15, 9855, 9840, 'print_order', 30, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(110, 46, 'out', 1, 9840, 9839, 'print_order', 31, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(111, 46, 'out', 1, 9839, 9838, 'print_order', 32, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(112, 46, 'out', 1, 9838, 9837, 'print_order', 33, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(113, 46, 'out', 1, 9837, 9836, 'print_order', 34, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(114, 46, 'out', 1, 9836, 9835, 'print_order', 35, 'order_confirmed', 'Historical stock movement correction (auto-fix)', '2025-09-15 17:26:05', '2025-09-15 17:26:05'),
(115, 46, 'out', 1, 9835, 9834, 'print_order', 66, 'order_confirmed', 'Stock reduced for print order', '2025-09-15 17:38:31', '2025-09-15 17:38:31'),
(116, 46, 'out', 1, 9834, 9833, 'print_order', 67, 'order_confirmed', 'Stock reduced for print order', '2025-09-15 17:43:30', '2025-09-15 17:43:30'),
(117, 63, 'out', 1, 156, 155, 'order', 248, 'Admin Offline Sale', 'Order #INV-16-09-2025-00-53-52', '2025-09-15 17:54:06', '2025-09-15 17:54:06'),
(118, 107, 'out', 2, 100, 98, 'print_order', 68, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 00:15:15', '2025-09-16 00:15:15'),
(119, 107, 'out', 1, 98, 97, 'print_order', 69, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 00:17:45', '2025-09-16 00:17:45'),
(120, 107, 'out', 2, 97, 95, 'print_order', 70, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 00:50:21', '2025-09-16 00:50:21'),
(121, 107, 'out', 1, 95, 94, 'print_order', 71, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 00:52:12', '2025-09-16 00:52:12'),
(122, 107, 'out', 1, 94, 93, 'print_order', 72, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 01:30:51', '2025-09-16 01:30:51'),
(123, 107, 'out', 1, 93, 92, 'print_order', 73, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 01:35:45', '2025-09-16 01:35:45'),
(124, 107, 'out', 1, 92, 91, 'print_order', 74, 'order_confirmed', 'Stock reduced for print order', '2025-09-16 01:52:53', '2025-09-16 01:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `nama`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
(1, 'Raihan Rizki Alfareza', 'Jalan Gedongan Mojokerto', '082131831262', '2025-07-03 22:51:38', '2025-07-03 22:51:38');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `testimonial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `district_id` int DEFAULT NULL,
  `postcode` int DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `instagram_access_token`, `phone`, `address1`, `address2`, `province_id`, `city_id`, `district_id`, `postcode`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'Test User Toko', 'toko_1757478102@example.com', NULL, '$2y$10$aisPfdfLUZcZfi3tRzUHF.EvopNHWUeaGfkCeHgjyE4BVCDaP4GIC', 'zkCC4oWDh5k6Ect3aWOxIxjXUhmyqcVcjoQbRd0VD5XIGxFUpPL4oRN8g0R5', 'IGAAT6fCIMniNBZAE5TZAm13ZAkNGTTFPbGh4eHNtbnF6RjZAzQ3BydTdVeWw5LVJ0OVFHZAXBuREx2UldyRFE0YXkwNzN3MmNpcE1SRFU0QV9jbEVyRjVPanBnVmdYMHR6Y0J6aXJoOHVjUUpjN3lxZAmVlUlpR', '081234567890', 'Test Address 1', 'Test Address 2', 18, 388, 3850, 12345, 1, '2024-11-10 01:50:58', '2025-08-13 05:29:51'),
(7, 'admin', 'admin@gmail.com', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-25 22:35:23', '2025-05-25 22:35:23'),
(8, 'ECLWVBZA', 'pvelazquezt52@gmail.com', NULL, '$2y$10$dBUJLVm.8LIVfkt0R8NaNO6Uk6tzQmE3w4zunvZLwEH7vgDdgKNI.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-26 01:54:54', '2025-05-26 01:54:54'),
(10, 'uYHnWknUAzMdvdl', 'fergusonevak48@gmail.com', NULL, '$2y$10$CRAnBV87N4ckfB0R7KFYr.Nq0YO14ViHdozV3/Wfulcy6Wh7Nl.1G', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-26 16:25:31', '2025-05-26 16:25:31'),
(11, 'FdpGHFBxyDNR', 'idgarmathews1@gmail.com', NULL, '$2y$10$3qOP4ZJFBnnyN29bVH1IEeg7pguJlxhYjBvmIHtQp2WvUJ44hGOE2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-27 06:55:43', '2025-05-27 06:55:43'),
(12, 'Fanani Agung', 'fanani5758@gmail.com', NULL, '$2y$10$94zrPcGXicXxAmxQtRjfbes3DE7eWEcX8aPwPtQoxBc/7P1LxR2mS', '6MdPgxgupUfeBVPFCWinXKxf3uYjgl2Wxi41NTUB8ZoFoMFq4yx2AcsxKaXV', NULL, '08113476769', 'Tebuireng IV Cukir', 'Jombang', 11, 164, NULL, 61471, 0, '2025-05-27 13:13:52', '2025-05-28 10:29:18'),
(13, 'TPhWLViRuCIYIsq', 'rollinseponawl71@gmail.com', NULL, '$2y$10$YkUq1vEVJqfpoTk8PsXfLuik/zTyEAAcS9cYJlZeWogU.M828DAzm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-27 16:19:18', '2025-07-03 11:28:36'),
(14, 'kcCggyzDhAEw', 'mirabeldickersonde1@gmail.com', NULL, '$2y$10$MPuTwwdk.mWpHsYxZv6gqeqdQhEniPMBYp1ia4lZXRMt1XDJr5xqa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-27 18:44:01', '2025-05-27 18:44:01'),
(15, 'rxOAgqRKWZXpb', 'tiffanyjohnson501199@yahoo.com', NULL, '$2y$10$07AmRMz3q3iG/2.XHuNsb.4dYsZxqErcBTNvQSSJi9HWC/Tmvdx02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-28 00:48:03', '2025-05-28 00:48:03'),
(16, 'pikxZTgfluT', 'darrilnortonq@gmail.com', NULL, '$2y$10$GEo/HvpDgLKQQuBMskUr.OzwagmnqqdYu7NhoV1MY9bf.lwJRsWQq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-28 22:49:34', '2025-05-28 22:49:34'),
(17, 'hxFzTUkzoGC', 'hklodet6@gmail.com', NULL, '$2y$10$mhr4gtB96aY1xehP//WEteyv7KzjLkNfAZWoC555nO4ReE4mwXiJi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-31 00:10:17', '2025-05-31 00:10:17'),
(18, 'isjQYPHKSG', 'mleitoniq1986@gmail.com', NULL, '$2y$10$kwdvLUPAcY8pTD8YsQJbROb15WsaW8UDUbb5b0Zp9KIUNA/LiysJ6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-31 12:35:50', '2025-05-31 12:35:50'),
(19, 'tKWPqiOGhtJaC', 'mortimerweaver1@gmail.com', NULL, '$2y$10$PFae8m9KZg5Jo0a0VEzp6OUWkEzOATtNU7FrhnTre1L6rTvgi8auG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-31 18:32:52', '2025-05-31 18:32:52'),
(20, 'CndGmzIUyTEPbYc', 'romeorojas568820@yahoo.com', NULL, '$2y$10$9yJZpWc3sMVgrNkBpAlrieLgSJ5XYoWiSkquCXJ.9s4cwz9JJA8QW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-01 08:58:39', '2025-06-01 08:58:39'),
(21, 'FZVzJhdjHMaKueD', 'milfordhooper32@gmail.com', NULL, '$2y$10$Htz9XU6zlf/qCWIY1CFZGOvvu07LYkcU4mGYquJPTV43W631zDUWW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-01 09:09:28', '2025-06-01 09:09:28'),
(22, 'PtApALeEg', 'littlelauren687025@yahoo.com', NULL, '$2y$10$TG3POSX2rt7n0vXrQxqYhOSwFQe.Gbk6xnGCDP3DiHgGPydAqOtk2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-02 23:37:10', '2025-06-02 23:37:10'),
(23, 'hwBWQHrfYcgkh', 'silvestrgev@gmail.com', NULL, '$2y$10$Myo2LcnUPgQfdosF5yXxLOg0fU6ttLlLkx95XvG/OVeIk/MMaaMp.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-03 11:09:23', '2025-06-03 11:09:23'),
(24, 'gAZhVOgORtBJmZj', 'morenosaffronu26@gmail.com', NULL, '$2y$10$sh/6cxaI4vIFvjWenQ6yVO.NU1v.SSJLxipNdWBuDeMYsAFJYoKnG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-03 13:02:02', '2025-06-03 13:02:02'),
(25, 'EnhndGKdGqrRv', 'matthewneedham590595@yahoo.com', NULL, '$2y$10$VMP8e9NaIx60.ehnNbbRqur0l2b1N0DwmtHz9bzSgr2Xwn2VFbsqK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-04 06:07:32', '2025-06-04 06:07:32'),
(26, 'bgTmtQgzURLDU', 'rosenbaumtodd2005@yahoo.com', NULL, '$2y$10$tWHxMMXMc9tlPDhtI3Pbg.GbGKJTg7nor4YF5mxFcpxU50tfj01r2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-04 13:59:44', '2025-06-04 13:59:44'),
(27, 'rVJIWfkXQrVoD', 'aguillea60@gmail.com', NULL, '$2y$10$YZz8sVnxjQTn3iOzvP235OPz6AE6TbpJSJWjsYjyoB7j6gePUekpi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-06 02:44:39', '2025-06-06 02:44:39'),
(28, 'POQBiKdBWhJC', 'drakemeidleinfq@gmail.com', NULL, '$2y$10$0nQJl9XHRUsMLi0Ay5d1d.Gpz7gigccTNoAvnYiwubsmOeAjX7TKm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:49:17', '2025-06-07 05:49:17'),
(29, 'zbRSkLglhpLxsf', 'walterozk29@gmail.com', NULL, '$2y$10$bIhmaXAyQsWRdAih/2hTw.q7owaS44RLvkq7Lgg7lRjPlG7wGnJLe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 07:55:59', '2025-06-07 07:55:59'),
(30, 'yJxxUnCBanODd', 'septmontoa2003@gmail.com', NULL, '$2y$10$nFMG8hkmcTuhlavTYmGAOOD/PktvdiE6S1DftHZZDT4FN1afFw37m', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 13:05:10', '2025-06-07 13:05:10'),
(31, 'OUYAZzuFOxtGIcf', 'wellscorey1985@yahoo.com', NULL, '$2y$10$p.ueGE1hEqZ91nWmEt7QW.bVmH88Er9p8nimC8UWz3E.BBhQpnxFW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 14:19:29', '2025-06-07 14:19:29'),
(32, 'qSiILmdlGkNTF', 'hyntleibrockhp1991@gmail.com', NULL, '$2y$10$27Vch3FsD7z6e.4W6ch.Iuj46DAAyLRzGuOW.GEz0MKOuX4mGHOxG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 22:08:27', '2025-06-07 22:08:27'),
(33, 'AchRIgqC', 'santanabrokzo@gmail.com', NULL, '$2y$10$nAm2wSGPc35LaGHAN1O.i.knh.pe3BikW0qUO7r1kq7Xe2OxNzN5K', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-08 12:51:14', '2025-06-08 12:51:14'),
(34, 'VDNHihuFFNAI', 'kiaraneal1996@yahoo.com', NULL, '$2y$10$vqUwxkzYdxw9dhCliTMpKe6kQvwT6h507OM.sNISGyA5DvANuR0K.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-08 21:04:19', '2025-06-08 21:04:19'),
(35, 'UhOIrPnd', 'hamiltonkleitoniw2002@gmail.com', NULL, '$2y$10$OyXEz0B3YkSCSsFt7fWWv.rHxoVOhFvYbvV8qficldUpziiGp.Fv2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-09 13:54:18', '2025-06-09 13:54:18'),
(36, 'eBYtrvJYjnoO', 'rshannonbq1989@gmail.com', NULL, '$2y$10$HLQkPho14dH6oIEej0se3e1uxXUCpMmNeyA3/YLtM5S7c32mFKhla', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-09 14:52:44', '2025-06-09 14:52:44'),
(37, 'hXUtRBLwe', 'ellisryan35254@yahoo.com', NULL, '$2y$10$8NLAtq3l/gjdN4soOCRADeY9U07/C46ZxW6H/nTtFL0dZUgI0WqjO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-09 15:52:32', '2025-06-09 15:52:32'),
(38, 'wRknVkKncnmxv', 'ashledeivirk1989@gmail.com', NULL, '$2y$10$z.MSRMhmQDXt.smXZTmJnOC/IQ8.znfvVtr3wB476N4jossPnuYr.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-10 07:57:39', '2025-06-10 07:57:39'),
(39, 'JWycpPeb', 'chowdhuryperis276667@yahoo.com', NULL, '$2y$10$uSz8oV9qUn0kAxoB7ha5hu0EunLmAg5VWcO2ww2ltlywlYuEWEmLi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-10 10:49:09', '2025-06-10 10:49:09'),
(40, 'SxOeUHXwWWG', 'polobooth747677@yahoo.com', NULL, '$2y$10$dKT9Qcz4QjpvoU9AF4oFYeaWYfAd0bUAsFWbKf3oHd0lepepFRjne', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-10 16:50:58', '2025-06-10 16:50:58'),
(41, 'afAdODmuXEcxFfi', 'zbaibr12@gmail.com', NULL, '$2y$10$fQhSVlMLcTkzNY7dcVOmzeYlYGff4Ik8qmaK2x5Nzm6VwILfmyQDy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-11 16:47:06', '2025-06-11 16:47:06'),
(42, 'WJSIzlbevTKwqBW', 'lucasnannayab@gmail.com', NULL, '$2y$10$bI4uMvStedAZtqTiZdlOA.mMPB3nDDSC8uw1QmbQjjEK68dubiy0G', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-12 05:52:38', '2025-06-12 05:52:38'),
(43, 'lOCGQLRFsYRA', 'yilfredrusselltl@gmail.com', NULL, '$2y$10$vzZ1Q13meVTAUEWutV8j0OlPiQb7zdN06Y9wujbzSratRFhkvH5e6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-12 06:09:29', '2025-06-12 06:09:29'),
(44, 'ADSMmXGRo', 'sidnimathiso@gmail.com', NULL, '$2y$10$QxWefuZNnCJQbqI5mzlUtOxZ3KrgAYMePNpozE7MlfE0riOJXHxfS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-12 08:34:44', '2025-06-12 08:34:44'),
(45, 'biSyXtsVKBJv', 'kazarinh5@gmail.com', NULL, '$2y$10$x2fdKHyt1a/ANd8hNg1Amu3HTY4j8K0TzkCobRkL.fkC8RK6gPIri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-12 14:38:51', '2025-06-12 14:38:51'),
(46, 'IkHzFvyiTTetWAJ', 'sdjeilinz4@gmail.com', NULL, '$2y$10$AZCdRvHvr7uny.cB94c8fuE/iX1/AbnyCXnt3p7bzUx0RiPwmYUMG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-13 02:26:13', '2025-06-13 02:26:13'),
(47, 'asdMZsuUDRCQ', 'williamolatunji665170@yahoo.com', NULL, '$2y$10$Y36QrW0IskpXOOYTvLtsZ.l9QFG1FGUa0rssrJ3VfSp64tE/wozZa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-13 03:39:31', '2025-06-13 03:39:31'),
(48, 'GAkiwELKgMlWO', 'heberteibep93@gmail.com', NULL, '$2y$10$hpxSGTBv6jmGq3.7ik1HOesyWeAarU3nuAdnKfXFTFslyCk2s/fFy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-13 06:46:02', '2025-06-13 06:46:02'),
(49, 'TmCnhAZweTxXJnE', 'wtalallati@gmail.com', NULL, '$2y$10$L1MAt0PqSLh2G2FrYJOaI.kqI.0yc7K1D6.hblG14bK/eGsljl0Zu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-13 17:35:02', '2025-06-13 17:35:02'),
(50, 'wWsTKBjkQdhp', 'willisveleb@gmail.com', NULL, '$2y$10$XgSdpMNwNZvIeMAkNL/z1el4Geln23aFNHluU4qZwVYzR2kC590IW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-16 05:17:53', '2025-06-16 05:17:53'),
(51, 'zsePUuDwdgaSg', 'macdonalddjeims1@gmail.com', NULL, '$2y$10$OdJe4RA00.al8oUkdDLkg.IpuErUwdUDxrfMwF.l07i8wBxKbwm4u', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-16 11:46:55', '2025-06-16 11:46:55'),
(52, 'NGTZkPQEs', 'kfigueroav5@gmail.com', NULL, '$2y$10$Cv0FnQiAA5pl/8SqZW8jaOomAyMgx3rCvuvx1zVwhfwJer39Fahhq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-17 07:12:10', '2025-06-17 07:12:10'),
(53, 'BhfOwYwVzaXogld', 'deifordn9@gmail.com', NULL, '$2y$10$.C2MJYkNN98FtmVJZlfwH.hUT0d2J0C5hHUyT8Q9k.Ky29oc8RyzG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-17 20:53:12', '2025-06-17 20:53:12'),
(54, 'NklILVEtFIy', 'darinnorthy47@gmail.com', NULL, '$2y$10$f9UdqrdOlhSNafUeccOaFeYAOP3CGA9IRmYldoJUTZ1qM78mnYEO6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-18 01:57:55', '2025-06-18 01:57:55'),
(55, 'wgPULHnwrZJfmKt', 'stokesmetu6@gmail.com', NULL, '$2y$10$JMBH4t1kfYt4Iz32YLZlp.ZygDCKXba5Hg66nHUZ4omPip1IB6GlO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-18 02:39:57', '2025-06-18 02:39:57'),
(56, 'gRFUsMwRuZteHMJ', 'sandersbethany728179@yahoo.com', NULL, '$2y$10$buw2CU0U0xw5p9pM44ei3udO.28KWTW/FjtPLAJn..Kex74iOt872', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-18 04:21:14', '2025-06-18 04:21:14'),
(57, 'GUUQLyFYfkK', 'maddoterloml37@gmail.com', NULL, '$2y$10$0QC.Shp9ZZ10IeOulsotT.nmUlZCQy6GQP91XxgDzNgZtM0jUjH/C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-18 08:35:51', '2025-06-18 08:35:51'),
(58, 'jKcomPtMeYPZG', 'beckerstiv2@gmail.com', NULL, '$2y$10$Gbxjro5xWnpvpLhPiWynWOrIyL2f9xx/LX8oCSG1pCMJ1iCFQ7CP6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-18 21:52:01', '2025-06-18 21:52:01'),
(59, 'bKeMgsUUtV', 'leksysb29@gmail.com', NULL, '$2y$10$vFjFSZ6WAAFZHSAW1HEFfO92oqyfritb/DXl38so2wLaKFBV7S0pW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-18 23:18:44', '2025-06-18 23:18:44'),
(60, 'azYrMeAzyFbKr', 'mayadelaxf@gmail.com', NULL, '$2y$10$HUkprkIUy2THIqooLHepTe0S32Q1wkH2JDdlyqOI7SxrK2Vfx1VOa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-19 01:10:55', '2025-06-19 01:10:55'),
(61, 'hgeWJFRZULN', 'hiscn7@gmail.com', NULL, '$2y$10$Fy.S0YX7jz5mwLHkruIuyepDAuk5g9FFrKQaew2QV4/770NL8jSvu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-19 13:02:11', '2025-06-19 13:02:11'),
(62, 'NxlhwTybMU', 'oliverrodgersxi@gmail.com', NULL, '$2y$10$wlylCdmhe4Dz0OhCqEklReWtFJy.Lp84iSBbqKCUyKkBE.jjxlG2i', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-19 23:44:43', '2025-06-19 23:44:43'),
(63, 'obORgoZukjvZjy', 'dizonharrisl50@gmail.com', NULL, '$2y$10$XVnCVqS/GuFkINsmtggNPuYr8nZdCnRkLHwmzQxXcSmczOzDqemee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-20 01:57:17', '2025-06-20 01:57:17'),
(64, 'VqVJhNEPfpvXH', 'ricblubqs28@gmail.com', NULL, '$2y$10$6LvvqfcMqa1CS9ijhXDrTu.JC6CA0S.ib63K8qyrrZu1nwxaTu.c6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-20 07:47:08', '2025-06-20 07:47:08'),
(65, 'DfzosCtrsrYsVU', 'amilimcco@gmail.com', NULL, '$2y$10$Y6/ENz49RhOLQbBuq3YzmukbbVFjY5wcG0HVP46d.1Dccv3gC61k2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-20 10:04:05', '2025-06-20 10:04:05'),
(66, 'qBTpZDzb', 'sapfircd34@gmail.com', NULL, '$2y$10$UFgTbuqeyfUIUeK1IxOhaOhLLmcTVLH1DcZfbjUA02jU.oQel4.dS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-21 07:08:07', '2025-06-21 07:08:07'),
(67, 'zihvCaHEVZQwuH', 'burcdimeri93@gmail.com', NULL, '$2y$10$e2hC5LoFiQAkDMs0lRs2sOxT.oH4Ws0UiWgbEICnI.06L0pUeDtdq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-21 11:34:55', '2025-06-21 11:34:55'),
(68, 'KyuErHmjxB', 'cuevastamikakx@gmail.com', NULL, '$2y$10$6H8Pn0h5PqIV9wQkcNW3xOiUpf83PDz69CXZfLfnmjQdbEwa1A0Q2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-21 21:12:13', '2025-06-21 21:12:13'),
(69, 'oxGVfDNVwVZmjn', 'gaymartinx69@gmail.com', NULL, '$2y$10$rj8ALA1UhlYVDf6LKvAz7.KCzk/v8yaCQKBET.u/eFmWLn8iQrAyi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-22 00:34:35', '2025-06-22 00:34:35'),
(70, 'xomCUmzvO', 'contchatira1974@yahoo.com', NULL, '$2y$10$6me2hfeMo8vJIL7l3Cw/kedFv90jFBVVZ8132CK.noQFObzwXw/9S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-22 14:00:56', '2025-06-22 14:00:56'),
(71, 'plLxabPTi', 'abnarchurch6@gmail.com', NULL, '$2y$10$JxL0kF0EcAUfujJWqo/CE.LONhub6ur67j7unKnkC4/c9BKVrYqTS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-22 16:06:41', '2025-06-22 16:06:41'),
(72, 'GNUrOfaDyG', 'deicholawra1985@yahoo.com', NULL, '$2y$10$t.ClMFVvvkIM1L.Y3XDeYOxOed6.C.DCcljDafwGSWcxP/wr/Q5ui', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-22 22:11:04', '2025-06-22 22:11:04'),
(73, 'pvbemoXOUWuSlO', 'ginnlawanvi1983@yahoo.com', NULL, '$2y$10$.wffPXfTr4Rv/67APKgV4.1U1oDIlnMG05oD1V06Jf8cqZ9MsqCeS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-22 22:41:38', '2025-06-22 22:41:38'),
(74, 'wJmCdsmxKYkut', 'herveigonzalezwu23@gmail.com', NULL, '$2y$10$OYdEvDdfQlA72uXQW40LG.7Uib0vHYMA.oyy8zQdF.ML227w48Xp.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-23 06:18:39', '2025-06-23 06:18:39'),
(75, 'jzrNDyJfzvNQ', 'dbanksrq6@gmail.com', NULL, '$2y$10$zwpmuI.M35CgUcQj4Cah4.qvICxTjjSD5OjO2drvhjV98y2YjnJIi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-23 11:56:45', '2025-06-23 11:56:45'),
(76, 'PuKEwULJuRdde', 'cochrannimbysoj2004@gmail.com', NULL, '$2y$10$m1kZQOxXo8RyKBTvpzdo..GKjGa2SDFPnNeKEnNjpdu9Jn.gLG8OO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-24 01:50:10', '2025-06-24 01:50:10'),
(77, 'mvalVmVWGw', 'cheretbte18@gmail.com', NULL, '$2y$10$G.vLdXninFyc97VtvBw27.i36MMY9tCXMXtSO4/1k2zzAxzDLzOE6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-24 03:52:26', '2025-06-24 03:52:26'),
(78, 'GMtDBcynjDoaq', 'bethmikkelsen969447@yahoo.com', NULL, '$2y$10$shBHx0vKHJCxUDMnKDpJMun8icKZAABbMkVQYBzxmEQhMPHlJQZ9i', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-24 06:09:03', '2025-06-24 06:09:03'),
(79, 'jGxgexuIiGq', 'afrikahha1991@gmail.com', NULL, '$2y$10$KZkaSIj0X6dBzYntYxdjI.HyX8Ab52jN0kEoEYnpa/Is4OqUbG2pu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-25 09:42:45', '2025-06-25 09:42:45'),
(80, 'xMyAWXwX', 'sapfirthorntonc@gmail.com', NULL, '$2y$10$XSb4U7CLAnRlNFfAUqwfMOX2lgrg3KkNwXJhEYfojzGLwhouYWweS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-25 14:06:17', '2025-06-25 14:06:17'),
(81, 'aOcBUwviS', 'fordalissiya1987@gmail.com', NULL, '$2y$10$1a9dZZLSQqiQIiaCm9H5RObEtR1wpaYOD53WDMv/Y920zJb5OdKcu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-26 07:14:09', '2025-06-26 07:14:09'),
(82, 'jvINgFmIcTCtLCE', 'efsekarscast1979@yahoo.com', NULL, '$2y$10$fQkkiBSnVc03cRdvct4Phu31IrC4fi9vipo611GRhX4ejlBpb1aji', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-27 08:06:50', '2025-06-27 08:06:50'),
(83, 'CRlMXChu', 'eqifixit855@gmail.com', NULL, '$2y$10$lQVKeFTQOvcd6J.q9N6IKustJ01g6P0SybJAn1/Ztx6OuRsQorvfW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-27 09:05:29', '2025-06-27 09:05:29'),
(84, 'mAeQZZcHPofG', 'evonotuy595@gmail.com', NULL, '$2y$10$rfXl2jywceMp3uHYMyls5OlC3.1X9IJ.intdzF7Dm02ukpSDFn2um', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-27 19:55:01', '2025-06-27 19:55:01'),
(85, 'FemvGbfEMSz', 'omehoya937@gmail.com', NULL, '$2y$10$LPeVDiU2vexA06iyfzJUGuLlNsEV7S1EnOrYZM.UbutI/.kG7Couu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-28 11:09:38', '2025-06-28 11:09:38'),
(86, 'gVmAoYlPZCE', 'jill_watson4177@yahoo.com', NULL, '$2y$10$YsspxW4EIcJPb4mQEfoNHuc5mxnYfYLOH/HFqu5K5cgS974XA/zFi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-29 01:34:04', '2025-06-29 01:34:04'),
(87, 'bzDbRkjHOFc', 'mdoreta15@gmail.com', NULL, '$2y$10$5siNFGL8D0kjpM3YNrje.ec3hIkOkOonLhcmGC0XK/mUQDe7GnQsG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-29 15:53:22', '2025-06-29 15:53:22'),
(88, 'UYfWhDxQCR', 'helviesean623408@yahoo.com', NULL, '$2y$10$p8FE5p4TW4TFtGBb6kJQSuQ5u1hqBmCw8COtwjxGBSeTrhTWFWMXq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-30 14:30:15', '2025-06-30 14:30:15'),
(89, 'zOfICodlxx', 'kadivasquezt@gmail.com', NULL, '$2y$10$/i/M0x7Ayo.D/V7nCYj7q.3.x7h2/5Cyfvay2f5eXh0clfyn2OdDi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-30 17:40:20', '2025-06-30 17:40:20'),
(90, 'fDsjhNtzwmhzApe', 'elroissc@gmail.com', NULL, '$2y$10$MuzjAyndSgNYkllkYxsQou9FD8zUWa8LhQPNx7pPiUFisOMBx6k3m', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-30 19:25:36', '2025-06-30 19:25:36'),
(91, 'XczEMeAq', 'churksaq25@gmail.com', NULL, '$2y$10$hZt4YQYz0SFEhWMmbkmZS.iOrdQk95gRRx3bwfm5cPqApf/7sryPa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 01:12:30', '2025-07-01 01:12:30'),
(92, 'uujAplFDKOduh', 'reesepettiqs71@gmail.com', NULL, '$2y$10$VXgTckYgFWZnU9Rfwdy8yOEMWhNNUzQ5HjuoK/OfZbfDhrz2OIVUq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 15:49:12', '2025-07-01 15:49:12'),
(93, 'Kytxwvlnqs', 'sibilloyo8@gmail.com', NULL, '$2y$10$s8O2q7XwO/Ou2Dqg2DqUW.z1Hixzwss0fk0trRhSdnbv8uAw5CM2S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 21:47:23', '2025-07-01 21:47:23'),
(94, 'WUWRuYXHjDnSjdQ', 'kyinnmhk@gmail.com', NULL, '$2y$10$KiJypPvqDSLykZgUSmgbyut8oOv4q.1S96iHUce6Yt1in8HDO18qu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-02 07:28:35', '2025-07-02 07:28:35'),
(95, 'MpBgyMEpyctYX', 'balloweangel2002@yahoo.com', NULL, '$2y$10$VyKqVI2A5jznNLkFGY6WW.Fu8cfIV9/4a326WTRhZVVwSu/R6kZ9O', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-02 10:28:59', '2025-07-02 10:28:59'),
(96, 'rltiJVVAANkgcI', 'emilincx5@gmail.com', NULL, '$2y$10$202lcc90eCij73lkptPo3.KIBaww.dEKQuYvOt2RtgzAYjaSMXCw2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-02 18:43:46', '2025-07-02 18:43:46'),
(97, 'NkztTHiyju', 'skaranmanish998195@yahoo.com', NULL, '$2y$10$Fgp.4E5rP8Fly.g37YTL0uwewHRq5O1K0ANR62Vacbx6QLM3W.r9a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-02 20:45:01', '2025-07-02 20:45:01'),
(98, 'sai', 'aiaso@gmail.com', NULL, '$2y$10$FBL2RE/uGl9Nsp5c2jXxr.irK9Q0ybPX3DctKHWDDZCDpQN4J6raK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-03 09:48:32', '2025-07-03 09:48:32'),
(99, 'qrCnptFFEa', 'byarvydb31@gmail.com', NULL, '$2y$10$GFwQ3ebzhF2Cv/N.fHa5SOGZeBmO0gprUg5My7toxLz.ZpNeyzY9W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-03 18:31:07', '2025-07-03 18:31:07'),
(100, 'ZeoQbvco', 'anthonycowan133303@yahoo.com', NULL, '$2y$10$NztsZiAsM9Sn021w.QeS6.ccfSZ227Q9XShFZK10F.xtiyNodKOme', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-04 02:00:01', '2025-07-04 02:00:01'),
(101, 'ONkzlNHNizG', 'ecimahelivas50@gmail.com', NULL, '$2y$10$qmm7trt1BSN9as/aR6PfwOByVXBd60a08IT..U9OXQU8pEI2UuJ.O', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-04 22:38:49', '2025-07-04 22:38:49'),
(102, 'eMKOLPMCLLfEPz', 'ocibekivatot81@gmail.com', NULL, '$2y$10$nPOb/Pbj8b8jv4ERXWULiOHs4IUMNnDUVI4VH3CHxumddHeZTM2oq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-05 04:50:13', '2025-07-05 04:50:13'),
(103, 'OEEyPzIO', 'uyidalefos705@gmail.com', NULL, '$2y$10$MFj1FhKpRL.iwDXn0dEDcukxbvJaVLYwGyDaMuCBIm6wljVlEw9ne', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-05 06:38:14', '2025-07-05 06:38:14'),
(104, 'JiSgmGnHhnb', 'farrebekn1981@gmail.com', NULL, '$2y$10$gK7OScRRk3c6tS0cSne2P.cvIya2J3VGFD9q/BV/rxTcJHOQsnlii', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-05 21:14:06', '2025-07-05 21:14:06'),
(105, 'UlwqBTYlq', 'weaverreksanaq38@gmail.com', NULL, '$2y$10$alK8eH02vz0PsCmLN0uwR.KXLuvstfgnLyaye11uXKTqykTBMJIaq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-06 13:26:56', '2025-07-06 13:26:56'),
(106, 'pxvGRIAydL', 'ceciliagomez677785@yahoo.com', NULL, '$2y$10$..IhAsJEz9s0sI9rJEJ0Z.UIdVMESugTLBv8a2VIqZCwwyb.yyY9q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-06 15:05:15', '2025-07-06 15:05:15'),
(107, 'YNEXnGKehXLvK', 'ronaldrandolph1986@gmail.com', NULL, '$2y$10$0oa3wrGCsyalLYxDCZ6O4uxxhjo8kqiNug6nbtNOIwUDqDfeYsZxa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-07 09:31:06', '2025-07-07 09:31:06'),
(108, 'ZZGcSoCvihL', 'bridgerandolrw43@gmail.com', NULL, '$2y$10$XtlNYG/LU0wWgoI.ZW0QDOe68il0d2MVaDdXOtla.502JvtBl0jU6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-07 11:56:19', '2025-07-07 11:56:19'),
(109, 'Dinda', 'uadnid@gmail.com', NULL, '$2y$10$hXlqArad8Cn6COCPTb9RaOhRw8/EvDRc5EXibn2TT/k/PhMCuhULO', NULL, NULL, '082257173363', 'RT 001 RW 007 Dusun Jeruk Kidul', 'Desa Banjarsari', 11, 289, NULL, 61352, 0, '2025-07-07 12:20:14', '2025-07-07 17:57:25'),
(110, 'Agung Jaya', '769agungjaya@gmail.com', NULL, '$2y$10$5LTpCWEzSfaNWrZDBLSGPegiFur8zQ4lN9eEphnoo0707GJvm.ztC', NULL, NULL, '08113476769', 'Tebuireng Gang 4 Cukir', 'Diwek', 11, 164, NULL, 691471, 0, '2025-07-07 17:30:55', '2025-07-07 17:32:13'),
(111, 'LJMQlmgnhzdtkXo', 'anastasyakgz21@gmail.com', NULL, '$2y$10$ATv.IhImD20TJKPpDeKJCutpWOay8gHJM0iQ7Ya3Qzofjb2l8EMwy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-07 20:18:12', '2025-07-07 20:18:12'),
(112, 'BvdfKPtfAJUEguN', 'enolap42@gmail.com', NULL, '$2y$10$NnKp5LuJsKGl/vaTKejBt.7MNT6r6.N2VH4I47zH7TKk6bXRKqHvm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-08 13:27:53', '2025-07-08 13:27:53'),
(113, 'JgjxOllhxG', 'djaneiowemu@gmail.com', NULL, '$2y$10$FRcHyEXz94SY8kXngh/GwemrSrWgbFAKQvtkELXss0SqiJ9oK5xs2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-08 13:57:13', '2025-07-08 13:57:13'),
(114, 'XsNqVOvzrs', 'rangelmelloni7@gmail.com', NULL, '$2y$10$jEfYhYyQgZEwLAZytbx8mOj0mibBJQUhr59EKy/7CHuxHo6fw2xjq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-08 14:32:58', '2025-07-08 14:32:58'),
(115, 'nLxhGxEKL', 'lopez_paris7383@yahoo.com', NULL, '$2y$10$ap1679Ic03MCXqlYB0U4.OpEJQmvkwYD2fzXie/X7AHp8.7zlC0hq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-08 20:11:33', '2025-07-08 20:11:33'),
(116, 'kQjOVTSypAQhln', 'schabriannu@gmail.com', NULL, '$2y$10$BgAbN09dHkVDl78fbnk57ucQRjYzuLIWjRaqAa9566Wcujpn.JOaG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-08 22:52:47', '2025-07-08 22:52:47'),
(117, 'XAhywZSsLuvlSEr', 'bentlcalla@gmail.com', NULL, '$2y$10$CUWKw/0vc86MdOR6wnlRpe35ahLAPSi04HR0BZqpKRyNtG5P0AFUW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-09 15:08:53', '2025-07-09 15:08:53'),
(118, 'qmwzTmBjaqyghEF', 'kanesammere9@gmail.com', NULL, '$2y$10$DIRbi9Zgh9D.HvUmwcJY6eG2vblhYtIzAc4GczQzObwFQIr85f9US', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-09 16:21:58', '2025-07-09 16:21:58'),
(119, 'oHNajFOupoqoXJC', 'tgrosst1992@gmail.com', NULL, '$2y$10$CHVC9On0zR9wS914QgtDK.XRBfNLPjfP6p/TbheuKDul5qcShzfOC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-10 04:15:28', '2025-07-10 04:15:28'),
(120, 'EmncrAgMs', 'opamequdica654@gmail.com', NULL, '$2y$10$H/uZ.W8TvbnTsXCnY5/W9.pXwZftjlAgvmSNT/aT22s9uu5SbxBGK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-10 09:31:57', '2025-07-10 09:31:57'),
(121, 'gpEZOYyUe', 'tujeyoteg309@gmail.com', NULL, '$2y$10$8xLlZniiQpC4wocJSrtaIe/ubgENf/NXizSNioPlPhoJs0M1rUxVe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-10 11:12:37', '2025-07-10 11:12:37'),
(122, 'MthCqznbeI', 'linnscottvz1989@gmail.com', NULL, '$2y$10$xXxtHFr01OuwL2aDtHYjaON41AaMjrZ28WULhlCXUpgBZoztigHjC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-10 15:10:44', '2025-07-10 15:10:44'),
(123, 'AAjLtiCnTKtlIFq', 'tozeguyima55@gmail.com', NULL, '$2y$10$85UW9.mJih22xjbNFVplteMmhijuE06MCFQ9jFS3itGroJXC.Nqmu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-11 00:41:17', '2025-07-11 00:41:17'),
(124, 'bIPXdsXfKUZ', 'ciniheceg127@gmail.com', NULL, '$2y$10$czuqEFEVuOGqbhM9b1j/.OmPNT3scIbfN8RD/TWwjVV7AZDJ/qrCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-11 01:35:33', '2025-07-11 01:35:33'),
(125, 'lIZzgSDOk', 'niduguboj599@gmail.com', NULL, '$2y$10$39RomjmN.nvM6HtiIwYAe.vEI41QHzbFJE65EJLAwc/KLGW2a0mxO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-11 10:41:38', '2025-07-11 10:41:38'),
(126, 'jMPJCWwNTRdGQL', 'qayehulu52@gmail.com', NULL, '$2y$10$tXls9uhgRnS.WS26du60Gegwo5usLQCP9a7xhyAUTkLga2/c.zFqW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-11 18:37:45', '2025-07-11 18:37:45'),
(127, 'hxoDRDqyR', 'qukemajeqo00@gmail.com', NULL, '$2y$10$znPCooYb0VEvWm1TNyChU.RKwdIw93l98hmM.zdYG8UZHyd42/lA.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-12 10:02:54', '2025-07-12 10:02:54'),
(128, 'noIfHihjxAsas', 'lisblag36@gmail.com', NULL, '$2y$10$/E4pSnhLQ7ODquWPQA6eZuJheo/VdJM6NEt0DqWlm097WF/6mk8a.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-12 14:04:13', '2025-07-12 14:04:13'),
(129, 'FwXfentI', 'landrykasperr3@gmail.com', NULL, '$2y$10$o4PgwvfJ0zUPf8j6BbhvjeQPwNsjClSuLIziQpslkB9CWlj2LY1Ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-13 20:03:03', '2025-07-13 20:03:03'),
(130, 'jfSiFKmk', 'lensvf8@gmail.com', NULL, '$2y$10$KGIt9eb96EZdR0jQP4y3c.zmghpvxvj6igGeTgtN.T125oWYOMhPW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-14 00:27:50', '2025-07-14 00:27:50'),
(131, 'bGVgmmSe', 'ballakli1997@gmail.com', NULL, '$2y$10$OFhkOiP3YiCHHADiDa/RV.TLxyhcCylgmDJI.wQbLwDSDLxUkEztC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-14 06:44:35', '2025-07-14 06:44:35'),
(132, 'KpJHgBhqbCZJsG', 'djylijordanr@gmail.com', NULL, '$2y$10$ED257ODAVLHn13L/Npe41esjeU87OaQ.VZ7IwoXC76MKahz09xjuK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-14 12:32:15', '2025-07-14 12:32:15'),
(133, 'rojXycTirtiJZQ', 'andriconwaymh9@gmail.com', NULL, '$2y$10$LL1y0wVJTrSkcaVv7nLSEu/5hhqjYuD5HKlVBr2TEIw/WkLPZpGb2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-14 21:24:05', '2025-07-14 21:24:05'),
(134, 'zBqCSCnl', 'wpetronelmb@gmail.com', NULL, '$2y$10$PRzhL6NdUaA3CoZmfgOKF.gZ3rU7ejoBx5oo4ABk5RCvcikZgiN16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-15 11:51:58', '2025-07-15 11:51:58'),
(135, 'uprolCRb', 'verhnt31@gmail.com', NULL, '$2y$10$6XVE0EmFo1kPl/J5Oro1LO6vCrgRoqa755nXIvDsLB7BSYIpq0z/G', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-15 21:24:33', '2025-07-15 21:24:33'),
(136, 'mYWAAGffp', 'heflincalvin994626@yahoo.com', NULL, '$2y$10$uhLXYyTOSQNuPi9A6lYzYu5j47U0Uj6GYz0x.oMG8Kx4.UdAOco5y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-15 21:49:22', '2025-07-15 21:49:22'),
(137, 'ffHovmJtWBInKUO', 'marrihowellhe2004@gmail.com', NULL, '$2y$10$mYYpOc0arfQWtnwtVrvW0esEEQg6n5VmODIvRV3r4yil3q2u46JbS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-17 08:46:28', '2025-07-17 08:46:28'),
(138, 'VhoCVjuEKweXcm', 'eoforhildhorton@gmail.com', NULL, '$2y$10$gNt9ZNamYLwHka8o1/5OkOJF9rqhPX2cnQNAhf7.sAiKDIenVvOou', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-17 19:23:15', '2025-07-17 19:23:15'),
(139, 'MnvYAGaJm', 'hedleisweeneya28@gmail.com', NULL, '$2y$10$lnYHJMRZyV3i8U4Z6SO92.Vz.FHqUnPlTYteUq8vUwSnvLEMbh5Y.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-19 06:11:05', '2025-07-19 06:11:05'),
(140, 'OKtsXKOnnyj', 'tedwitzigreuter16143@yahoo.com', NULL, '$2y$10$/trSosjFkfSX59I63zfPk.dTP6TTyLgPGHO3p1XsqOTlMfioUxqN2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-19 14:52:53', '2025-07-19 14:52:53'),
(141, 'XENxJqQK', 'arroydjeinltu30@gmail.com', NULL, '$2y$10$7ASbf1YBOAmfHUt0DnRzfulaDXp6bqJEZbl6JGcgwb2SEUJhtz07W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-19 15:15:12', '2025-07-19 15:15:12'),
(142, 'JtLBHvBVGEuVsrT', 'fabexozo210@gmail.com', NULL, '$2y$10$u8/FKM4BH33NXviB4tP2F.HzZFJ.U/PxeY.mCvctdgFybIEFq4QuO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-20 07:17:27', '2025-07-20 07:17:27'),
(143, 'WVEeTDFBBTF', 'carsoredjintr@gmail.com', NULL, '$2y$10$A8diNsyFUH2v3Te5C.9XHu.w0Jekc2PE92GtA9coaKq1PLfTh7BjS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-21 04:24:13', '2025-07-21 04:24:13'),
(144, 'VTZXcgdFtBrEV', 'mccardarta65@gmail.com', NULL, '$2y$10$/KRNlFEU5KwX5mWYhqpSi.xxg4P3PsiTwJIg9RU.Vs2/TLjxlrTYa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-21 08:31:03', '2025-07-21 08:31:03'),
(145, 'UlGmJEQjR', 'ohogenaledu62@gmail.com', NULL, '$2y$10$ZJdk77toFNDt0tWQKgsHku809FZ7CKZ9HRNTy3/d3AwQyUTm8/U9q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 03:58:30', '2025-07-23 03:58:30'),
(146, 'pxegmajuca', 'kirbytoperm@gmail.com', NULL, '$2y$10$BroY9oYbMJ4ndLl7qLx0beuEJWzxjJHfS6FzQpEmZKArtGGY7JIF6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 04:16:12', '2025-07-23 04:16:12'),
(147, 'hIWOvgYGzZwekDC', 'pachecodikvd@gmail.com', NULL, '$2y$10$1odVDxdEC.SNyoePaG3iBesIO650hcuFYBzy2kQnj/Iqr.WcPlTIS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-24 08:58:18', '2025-07-24 08:58:18'),
(148, 'GyltaQNZj', 'hardigy67@gmail.com', NULL, '$2y$10$gEW0nZInylb7..wWNl5AneZmXSBFUI0dhHltgiBS1Pf4lsZwydhLm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-24 09:29:09', '2025-07-24 09:29:09'),
(149, 'jKfwYRRq', 'ujupuqabilor14@gmail.com', NULL, '$2y$10$QE67Mi8qfQgSn2ifU.KxPOloR0ScBZj7.DasYB9nDMrR/8WLoxgAe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-24 14:25:12', '2025-07-24 14:25:12'),
(150, 'SvVFfZAYcm', 'guerraflayerdw2001@gmail.com', NULL, '$2y$10$5V/2pAII/I8HfeLT01YunOM6lYuuneGLxJUUt2G0f4.72HyW8nLUy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-24 21:36:27', '2025-07-24 21:36:27'),
(151, 'wRBnrbKY', 'spoolef2003@gmail.com', NULL, '$2y$10$Kj4j2YmFv3T9kXDezNXLN.Qd7ALOD6M7w3PY0IRBt41JC9n8uBQIW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-25 09:05:44', '2025-07-25 09:05:44'),
(152, 'hfTHwHYWe', 'equburoxo129@gmail.com', NULL, '$2y$10$J4oNuJiZ9YSaZTBMeMoUTeGQ3b27Ld/CWXD1LQNbo8QSDF9nJmEMm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-25 15:15:52', '2025-07-25 15:15:52'),
(153, 'GSLNfyAtUKqbmy', 'nejakuvoc366@gmail.com', NULL, '$2y$10$duqEIyeOaw2vEie1G3bmbeJts6LiUjTbS9t7Rww3d8yyosPk92vpu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-26 06:26:53', '2025-07-26 06:26:53'),
(154, 'yeJqOiHtT', 'foxleksnq5@gmail.com', NULL, '$2y$10$AwQgw7sWfBX1AdT6D8/YXeXwIOEiUvPwuYiKOTARgM/w7tC5yELEC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-26 19:52:38', '2025-07-26 19:52:38'),
(155, 'GcCAPmGpcq', 'moxapowati025@gmail.com', NULL, '$2y$10$yAhRbAVRr64TDzWKyYaspOZSJ/I8OGNJV6Gsc8QfaI32n8P.NzKL6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-27 04:10:14', '2025-07-27 04:10:14'),
(156, 'JeHSyHJGHybxVtu', 'yuhevoyi16@gmail.com', NULL, '$2y$10$lcblVN/XchWF0NeWYVDpluIoATpXtg4K4CRlPZ3WlN2QBRcAXrR7q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-27 21:21:23', '2025-07-27 21:21:23'),
(157, 'fMVuQUGerMQA', 'linseilqt75@gmail.com', NULL, '$2y$10$q4H3YaUm2vXMQU.AvJFBn.cYCowZ7ARWuBY.SgKt1.Uz2XEwz7tdO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-28 03:53:31', '2025-07-28 03:53:31'),
(158, 'SFXkYwddXEWF', 'gawoqomeg067@gmail.com', NULL, '$2y$10$m5QHa/rtWslBPQ67ZV5WPed5ERBYYDNTauYUOYYbTg1yFoO4cQfEW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-28 09:48:26', '2025-07-28 09:48:26'),
(159, 'KmeuJdCPlD', 'erindavis611500@yahoo.com', NULL, '$2y$10$zTm9cKpFaawTK3YBp.YgDOWseESONyv23DPF8DY8iO8CyMImWGoN2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-29 09:35:08', '2025-07-29 09:35:08'),
(160, 'URrzKEiuzhC', 'mariopfingston404461@yahoo.com', NULL, '$2y$10$piE30nV7ziUFSObZkl64w.OJsYByq91YlFfRkuEEWXSDa.fA/1HqG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-29 15:25:20', '2025-07-29 15:25:20'),
(161, 'xTPGaPhu', 'madisondebbie578349@yahoo.com', NULL, '$2y$10$edPGKvPBgNsaKdiheopQauEClqvbKZGDuvrBGU5TvLHkkSTDJMnyW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-30 05:26:47', '2025-07-30 05:26:47'),
(162, 'OPTdFnjnYqDM', 'donnawedge404018@yahoo.com', NULL, '$2y$10$yNXM5hyCKysuEsVBmdRRfOEgTsIoozLzFRZbhl2pZSN6kQ.s14Byi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-30 20:44:06', '2025-07-30 20:44:06'),
(163, 'OzstuGaPSSmI', 'akavurogo229@gmail.com', NULL, '$2y$10$HUoM8yYbuuKrE83hJRARduMll9ugktpY8bVRaC5me0cWMI5EIoGe6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-31 19:23:57', '2025-07-31 19:23:57'),
(164, 'zERYrOomq', 'vavidagapu351@gmail.com', NULL, '$2y$10$5szHDuxuTOWEUEk.K6blcOIhMSfxFpG4YYO.jXBBN4vUfPtptyq2O', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-01 09:20:29', '2025-08-01 09:20:29'),
(165, 'ijEVgvIPOmDQpF', 'ozobonaxa881@gmail.com', NULL, '$2y$10$kak370pNne5DFNa0GhVkMeYGaKraTjVimnFYreMXINad5C.HqCcMy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-01 11:53:00', '2025-08-01 11:53:00'),
(166, 'WGMQrFSAjLYuaBE', 'wurimaqunag974@gmail.com', NULL, '$2y$10$ndjmgemel/2MYJHYKjQVj.gxw/z5AL1EQdipctJCntU11lJKpBbzW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-01 18:02:30', '2025-08-01 18:02:30'),
(167, 'zSXtCeXUMVS', 'foyudirep83@gmail.com', NULL, '$2y$10$20OJah.J8lhQjpHX8qRZYu5sD551d24RH1usmSjZATBMmtvLaepQy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-02 10:54:17', '2025-08-02 10:54:17'),
(168, 'tWjoUMWzHnh', 'ohicayota75@gmail.com', NULL, '$2y$10$uzPJeZFa59Nf/ZdNMzEv4OCwsKZACT816Cpg5ggbRFCU3PM1DDIg2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-02 18:06:27', '2025-08-02 18:06:27'),
(169, 'rjFZLcjP', 'kamaraheather209722@yahoo.com', NULL, '$2y$10$1GsGtOrdfDcfAXtCK.HH/O4vJuE3Kc9ZI8GCInr/0QMBIt4J1q/9C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-03 00:38:28', '2025-08-03 00:38:28'),
(170, 'wewANnRgs', 'vargaskeidens@gmail.com', NULL, '$2y$10$vKoA.ScNq7HKabFLr5G70eMfjGYrig0PzYT8.bRG/6FF27Z0suo0C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-03 16:54:09', '2025-08-03 16:54:09'),
(171, 'ZaCHSNelsEHWzc', 'uqujayid33@gmail.com', NULL, '$2y$10$7kmUCbV87umpo3FEZ3JQx.c.EOVuvE788lKXsNHRVwIOBQwLrxnS.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-03 23:37:54', '2025-08-03 23:37:54'),
(172, 'meUWvOvyiJxl', 'oletetopo539@gmail.com', NULL, '$2y$10$oZ2bjzJKb8rjMjIoyE8qFuOUKmCD/SB8Z4OUmbFEUrgdiC9werI6m', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-04 21:46:08', '2025-08-04 21:46:08'),
(173, 'SVcKtxsRTqp', 'medisonje85@gmail.com', NULL, '$2y$10$i8Oud/JdUQLE5TG40t7J6..ictjAe42IQEBmSRBREwnt4VMcSCc16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-05 08:41:09', '2025-08-05 08:41:09'),
(174, 'eadxkhKntVXBL', 'harrisemiti@gmail.com', NULL, '$2y$10$/A/vJRNf7mu9R1LGNI5eQ.ewM.PK0ckgZBwJUi4zU/pI0z8MFeza.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-05 15:42:24', '2025-08-05 15:42:24'),
(175, 'KnRvOaplWJ', 'berrivalentinege48@gmail.com', NULL, '$2y$10$tHw8TETVWsStRFc0y8rkOOM7wWGAb.xf63Q6VkHjxTWx7RuFGZKn6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-05 15:52:45', '2025-08-05 15:52:45'),
(176, 'QYnufExGrypLLh', 'geielomaldoh39@gmail.com', NULL, '$2y$10$vjA3KtRawCuRjm6rNphlMOSvXtxh8BLPs13lptQVsSRfYSAv1Czbu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-05 20:17:03', '2025-08-05 20:17:03'),
(177, 'mnlEesWZOPI', 'uvoyufuziq67@gmail.com', NULL, '$2y$10$Myv9cBZSIVVPCYaF.arNV.8gHfLp5eNXeQiIxw1TEGUFk2mtNeDOm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-06 23:11:27', '2025-08-06 23:11:27'),
(178, 'kajcbjUCPsomO', 'haralisyuq4@gmail.com', NULL, '$2y$10$g3N4MQGG6mLr4S6RjP9ue.rJ3b1FTDvL5pXyBQWMyVWqFu7pjXO.G', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-07 17:07:07', '2025-08-07 17:07:07'),
(179, 'dYNjZiNTbyk', 'hoffmankoreiur@gmail.com', NULL, '$2y$10$m6M7C1bwWcqFQInC4YZOJ.qdCp6gWeIfJHMPMVyqo4QT4Mo.bVrl2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-07 23:22:08', '2025-08-07 23:22:08'),
(180, 'YIWajYESmDAGKy', 'dianamitchell597311@yahoo.com', NULL, '$2y$10$7NxUopxsvocV.h/j4/f9N.rwR9uz9DZim5WgDkLUYNDd6fRjSm7wm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-08 22:29:17', '2025-08-08 22:29:17'),
(181, 'wIqBwskrcEHN', 'bizudazosu050@gmail.com', NULL, '$2y$10$xLx133YNFfLdMR6IROWPY.oJ1q50LLk79KMkdyu7bi2cVSOQh2eOa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-09 08:44:12', '2025-08-09 08:44:12'),
(182, 'bIDsnhcUzvkpji', 'sijihoketib32@gmail.com', NULL, '$2y$10$wkrbcx7NCDVK/Sts24zVm.2MF3mA5hJ3L2M84BU0bgBJM7EWTzAQy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-09 15:35:39', '2025-08-09 15:35:39'),
(183, 'plxqhtSJGi', 'feqekajem20@gmail.com', NULL, '$2y$10$/JWgnaIGrN0S/McfSBy7le956i1DUDuOkRvJD/3CD3lvXGSgHc58W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-09 17:43:16', '2025-08-09 17:43:16'),
(184, 'zHhGPlLqeeAgWj', 'etebabosoxu57@gmail.com', NULL, '$2y$10$z4ETyeYG2SSlzTkDPKs12u6.xAOHGXTofHEmlXbhnaKP8n6T6rLy.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-10 10:58:27', '2025-08-10 10:58:27'),
(185, 'fKlKvuBs', 'zoyecexebu79@gmail.com', NULL, '$2y$10$u4tCNlJWKtGSqWC2Vq7.ie0UH8GfSpDqmTd7kcNxIccJwiGRC9Zze', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-10 11:10:32', '2025-08-10 11:10:32'),
(186, 'sBYEyOjMmgTlb', 'pagenoya1983@gmail.com', NULL, '$2y$10$hVoslsgS9Ekcoh01v5xH4Od2MC.TYnIjvNLgL.0Jpu1ORMkyqfkR6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-10 12:42:39', '2025-08-10 12:42:39'),
(187, 'jWYNLqgFYMmAT', 'shortgregory125722@yahoo.com', NULL, '$2y$10$sMrIOwWX3GZwn4I4Rjsr2ux4f3IL9V7yVZmR62zYtZo87OYRI5muW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-08-10 17:33:12', '2025-08-10 17:33:12'),
(188, 'Test Payment Flow User', 'test@example.com', '2025-09-10 03:34:51', '$2y$10$QI2awdDQxAKhbPM/AsvmP.W5bv8g5PqSMvgYT91PeTivM9ZRr1nb2', NULL, NULL, '08123456789', 'Test Address 1', 'Test Address 2', 1, 1, NULL, 12345, 0, '2025-09-10 03:34:51', '2025-09-10 03:34:51'),
(189, 'reza', 'reza@gmail.com', NULL, '$2y$10$0Uo.FFnagSYZ9OuIlE..oOEZ.j.XimmPcWiYnoirwZkQCZrp766pK', NULL, NULL, '085155228237', 'Jalan Gedongan VII/12 A', 'Magersari', 18, 388, 3850, 61319, 0, '2025-09-10 04:27:04', '2025-09-10 04:29:46'),
(190, 'Raihan Rizki Alfareza', 'araihanrizki@gmail.com', NULL, '$2y$10$5gqAJQOK8sO7CpR53UvfLuRA564XAFT5fuRTAgb6rQszZGOMab9M6', 'dR7TNvOh3apa482VBIIzxF1TwfAr4ycNxfevaryUyBGF7TX6LVLXGMOhGAQL', NULL, '082131831262', 'Jalan Gedongan VII/12 A', NULL, 18, 388, 3850, 61319, 1, '2025-09-10 05:11:41', '2025-09-10 05:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `variant_attributes`
--

CREATE TABLE `variant_attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `attribute_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_attributes`
--

INSERT INTO `variant_attributes` (`id`, `variant_id`, `attribute_name`, `attribute_value`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'brand', 'APP', 0, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(2, 1, 'size', 'A4', 1, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(3, 1, 'weight', '70gr', 2, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(4, 2, 'brand', 'APP', 0, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(5, 2, 'size', 'A4', 1, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(6, 2, 'weight', '80gr', 2, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(7, 3, 'brand', 'APP', 0, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(8, 3, 'size', 'F4', 1, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(9, 3, 'weight', '70gr', 2, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(10, 4, 'brand', 'Sinar Dunia', 0, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(11, 4, 'size', 'A4', 1, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(12, 4, 'weight', '70gr', 2, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(13, 5, 'brand', 'Sinar Dunia', 0, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(14, 5, 'size', 'A4', 1, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(15, 5, 'weight', '80gr', 2, '2025-09-06 03:57:31', '2025-09-06 03:57:31'),
(21, 14, 'pink', 'blue', 0, '2025-09-06 04:59:41', '2025-09-06 04:59:41'),
(24, 17, 'Color', 'Red', 0, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(25, 17, 'Size', 'Large', 0, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(26, 18, 'Color', 'Blue', 0, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(27, 18, 'Size', 'Medium', 0, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(28, 19, 'Color', 'Green', 0, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(29, 19, 'Size', 'Small', 0, '2025-09-06 05:38:04', '2025-09-06 05:38:04'),
(31, 21, 'Color', 'Red', 0, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(32, 21, 'Size', 'XS', 0, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(33, 23, 'Material', 'Cotton', 0, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(34, 23, 'Brand', 'Premium', 0, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(35, 23, 'Pattern', 'Solid', 0, '2025-09-06 05:55:47', '2025-09-06 05:55:47'),
(38, 10, 'Putih', 'XL', 0, '2025-09-06 06:10:47', '2025-09-06 06:10:47'),
(39, 16, 'blue', 'panda', 0, '2025-09-06 06:10:57', '2025-09-06 06:10:57'),
(40, 15, 'pink', 'blue', 0, '2025-09-06 06:11:05', '2025-09-06 06:11:05'),
(41, 29, 'size', 'S', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(42, 29, 'color', 'Merah', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(43, 30, 'size', 'S', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(44, 30, 'color', 'Biru', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(45, 31, 'size', 'S', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(46, 31, 'color', 'Hitam', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(47, 32, 'size', 'S', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(48, 32, 'color', 'Putih', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(49, 33, 'size', 'M', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(50, 33, 'color', 'Merah', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(51, 34, 'size', 'M', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(52, 34, 'color', 'Biru', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(53, 35, 'size', 'M', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(54, 35, 'color', 'Hitam', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(55, 36, 'size', 'M', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(56, 36, 'color', 'Putih', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(57, 37, 'size', 'L', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(58, 37, 'color', 'Merah', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(59, 38, 'size', 'L', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(60, 38, 'color', 'Biru', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(61, 39, 'size', 'L', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(62, 39, 'color', 'Hitam', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(63, 40, 'size', 'L', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(64, 40, 'color', 'Putih', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(65, 41, 'size', 'XL', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(66, 41, 'color', 'Merah', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(67, 42, 'size', 'XL', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(68, 42, 'color', 'Biru', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(69, 43, 'size', 'XL', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(70, 43, 'color', 'Hitam', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(71, 44, 'size', 'XL', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(72, 44, 'color', 'Putih', 0, '2025-09-06 11:01:00', '2025-09-06 11:01:00'),
(73, 45, 'paper_size', 'A4', 0, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(74, 45, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(77, 47, 'paper_size', 'A3', 0, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(78, 47, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(79, 48, 'paper_size', 'A3', 0, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(80, 48, 'print_type', 'Berwarna', 1, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(81, 49, 'paper_size', 'F4', 0, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(82, 49, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(83, 50, 'paper_size', 'F4', 0, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(84, 50, 'print_type', 'Berwarna', 1, '2025-09-11 04:02:27', '2025-09-11 04:02:27'),
(85, 51, 'paper_size', 'A4', 0, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(86, 51, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(87, 52, 'paper_size', 'A4', 0, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(88, 52, 'print_type', 'Berwarna', 1, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(89, 53, 'paper_size', 'A3', 0, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(90, 53, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(91, 54, 'paper_size', 'A3', 0, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(92, 54, 'print_type', 'Berwarna', 1, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(93, 55, 'paper_size', 'F4', 0, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(94, 55, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(95, 56, 'paper_size', 'F4', 0, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(96, 56, 'print_type', 'Berwarna', 1, '2025-09-11 04:09:59', '2025-09-11 04:09:59'),
(97, 57, 'paper_size', 'A4', 0, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(98, 57, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(99, 58, 'paper_size', 'A4', 0, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(100, 58, 'print_type', 'Berwarna', 1, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(101, 59, 'paper_size', 'A3', 0, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(102, 59, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(103, 60, 'paper_size', 'A3', 0, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(104, 60, 'print_type', 'Berwarna', 1, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(105, 61, 'paper_size', 'F4', 0, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(106, 61, 'print_type', 'Hitam Putih', 1, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(107, 62, 'paper_size', 'F4', 0, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(108, 62, 'print_type', 'Berwarna', 1, '2025-09-11 04:10:55', '2025-09-11 04:10:55'),
(109, 46, 'paper_size', 'A4', 0, '2025-09-15 16:22:36', '2025-09-15 16:22:36'),
(110, 46, 'print_type', 'Berwarna', 1, '2025-09-15 16:22:36', '2025-09-15 16:22:36'),
(113, 107, 'Padang', 'Hitam Putih', 0, '2025-09-15 23:11:18', '2025-09-15 23:11:18'),
(114, 108, 'Padang', 'Color', 0, '2025-09-15 23:11:30', '2025-09-15 23:11:30'),
(116, 111, 'A4', 'Color', 0, '2025-09-15 23:48:24', '2025-09-15 23:48:24'),
(118, 112, 'A4', 'Black & White', 0, '2025-09-15 23:49:18', '2025-09-15 23:49:18'),
(123, 115, 'KUCING GARONG', 'KUNING', 0, '2025-09-16 03:09:52', '2025-09-16 03:09:52'),
(125, 116, 'ANGGORA', 'HITAM', 0, '2025-09-16 03:21:51', '2025-09-16 03:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `wish_lists`
--

CREATE TABLE `wish_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_options`
--
ALTER TABLE `attribute_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_options_attribute_variant_id_foreign` (`attribute_variant_id`);

--
-- Indexes for table `attribute_variants`
--
ALTER TABLE `attribute_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_variants_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`),
  ADD KEY `brands_is_active_slug_index` (`is_active`,`slug`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `dymantic_instagram_basic_profiles`
--
ALTER TABLE `dymantic_instagram_basic_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dymantic_instagram_basic_profiles_username_unique` (`username`);

--
-- Indexes for table `dymantic_instagram_feed_tokens`
--
ALTER TABLE `dymantic_instagram_feed_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_bonuses`
--
ALTER TABLE `employee_bonuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_bonuses_employee_name_period_start_period_end_index` (`employee_name`,`period_start`,`period_end`),
  ADD KEY `employee_bonuses_given_by_given_at_index` (`given_by`,`given_at`);

--
-- Indexes for table `employee_performances`
--
ALTER TABLE `employee_performances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_performances_order_id_foreign` (`order_id`),
  ADD KEY `employee_performances_employee_name_completed_at_index` (`employee_name`,`completed_at`),
  ADD KEY `employee_performances_transaction_value_completed_at_index` (`transaction_value`,`completed_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_code_unique` (`code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_approved_by_foreign` (`approved_by`),
  ADD KEY `orders_cancelled_by_foreign` (`cancelled_by`),
  ADD KEY `orders_code_index` (`code`),
  ADD KEY `orders_code_order_date_index` (`code`,`order_date`),
  ADD KEY `orders_payment_token_index` (`payment_token`),
  ADD KEY `orders_shipping_adjusted_by_foreign` (`shipping_adjusted_by`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_sku_index` (`sku`),
  ADD KEY `order_items_variant_id_index` (`variant_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_number_index` (`number`),
  ADD KEY `payments_method_index` (`method`),
  ADD KEY `payments_token_index` (`token`),
  ADD KEY `payments_payment_type_index` (`payment_type`);

--
-- Indexes for table `pembelians`
--
ALTER TABLE `pembelians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembelians_id_supplier_foreign` (`id_supplier`),
  ADD KEY `pembelians_status_index` (`status`),
  ADD KEY `pembelians_id_supplier_status_index` (`id_supplier`,`status`);

--
-- Indexes for table `pembelian_details`
--
ALTER TABLE `pembelian_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembelian_details_id_pembelian_foreign` (`id_pembelian`),
  ADD KEY `pembelian_details_id_produk_foreign` (`id_produk`),
  ADD KEY `pembelian_details_variant_id_foreign` (`variant_id`),
  ADD KEY `pembelian_details_id_pembelian_id_produk_index` (`id_pembelian`,`id_produk`),
  ADD KEY `pembelian_details_id_pembelian_variant_id_index` (`id_pembelian`,`variant_id`);

--
-- Indexes for table `pengeluarans`
--
ALTER TABLE `pengeluarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `print_files`
--
ALTER TABLE `print_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `print_files_print_order_id_is_processed_index` (`print_order_id`,`is_processed`),
  ADD KEY `print_files_print_session_id_is_processed_index` (`print_session_id`,`is_processed`);

--
-- Indexes for table `print_orders`
--
ALTER TABLE `print_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `print_orders_order_code_unique` (`order_code`),
  ADD KEY `print_orders_paper_product_id_foreign` (`paper_product_id`),
  ADD KEY `print_orders_paper_variant_id_foreign` (`paper_variant_id`),
  ADD KEY `print_orders_status_payment_status_index` (`status`,`payment_status`),
  ADD KEY `print_orders_session_id_status_index` (`session_id`,`status`);

--
-- Indexes for table `print_sessions`
--
ALTER TABLE `print_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `print_sessions_session_token_unique` (`session_token`),
  ADD UNIQUE KEY `print_sessions_barcode_token_unique` (`barcode_token`),
  ADD KEY `print_sessions_is_active_expires_at_index` (`is_active`,`expires_at`),
  ADD KEY `print_sessions_session_token_index` (`session_token`),
  ADD KEY `print_sessions_barcode_token_index` (`barcode_token`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_user_id_foreign` (`user_id`),
  ADD KEY `products_parent_id_foreign` (`parent_id`),
  ADD KEY `products_brand_id_status_index` (`brand_id`,`status`),
  ADD KEY `products_is_featured_status_index` (`is_featured`,`status`),
  ADD KEY `products_sold_count_rating_index` (`sold_count`,`rating`);
ALTER TABLE `products` ADD FULLTEXT KEY `search` (`name`,`slug`,`short_description`,`description`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_parent_product_id_foreign` (`parent_product_id`),
  ADD KEY `product_attribute_values_product_id_foreign` (`product_id`),
  ADD KEY `product_attribute_values_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_product_id_foreign` (`product_id`),
  ADD KEY `product_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_inventories_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_is_active_index` (`product_id`,`is_active`),
  ADD KEY `product_variants_sku_is_active_index` (`sku`,`is_active`),
  ADD KEY `product_variants_stock_min_stock_threshold_index` (`stock`,`min_stock_threshold`);

--
-- Indexes for table `rekaman_stoks`
--
ALTER TABLE `rekaman_stoks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekaman_stoks_product_id_foreign` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipments_user_id_foreign` (`user_id`),
  ADD KEY `shipments_order_id_foreign` (`order_id`),
  ADD KEY `shipments_shipped_by_foreign` (`shipped_by`),
  ADD KEY `shipments_track_number_index` (`track_number`);

--
-- Indexes for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
  ADD PRIMARY KEY (`identifier`,`instance`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slides_user_id_index` (`user_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_variant_id_created_at_index` (`variant_id`,`created_at`),
  ADD KEY `stock_movements_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  ADD KEY `stock_movements_reason_index` (`reason`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_attributes_variant_id_attribute_name_index` (`variant_id`,`attribute_name`),
  ADD KEY `variant_attributes_attribute_name_attribute_value_index` (`attribute_name`,`attribute_value`);

--
-- Indexes for table `wish_lists`
--
ALTER TABLE `wish_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wish_lists_product_id_foreign` (`product_id`),
  ADD KEY `wish_lists_user_id_product_id_index` (`user_id`,`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attribute_options`
--
ALTER TABLE `attribute_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attribute_variants`
--
ALTER TABLE `attribute_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `dymantic_instagram_basic_profiles`
--
ALTER TABLE `dymantic_instagram_basic_profiles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dymantic_instagram_feed_tokens`
--
ALTER TABLE `dymantic_instagram_feed_tokens`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_bonuses`
--
ALTER TABLE `employee_bonuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employee_performances`
--
ALTER TABLE `employee_performances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pembelian_details`
--
ALTER TABLE `pembelian_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pengeluarans`
--
ALTER TABLE `pengeluarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `print_files`
--
ALTER TABLE `print_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `print_orders`
--
ALTER TABLE `print_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `print_sessions`
--
ALTER TABLE `print_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `product_inventories`
--
ALTER TABLE `product_inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `rekaman_stoks`
--
ALTER TABLE `rekaman_stoks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `wish_lists`
--
ALTER TABLE `wish_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_options`
--
ALTER TABLE `attribute_options`
  ADD CONSTRAINT `attribute_options_attribute_variant_id_foreign` FOREIGN KEY (`attribute_variant_id`) REFERENCES `attribute_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_variants`
--
ALTER TABLE `attribute_variants`
  ADD CONSTRAINT `attribute_variants_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_bonuses`
--
ALTER TABLE `employee_bonuses`
  ADD CONSTRAINT `employee_bonuses_given_by_foreign` FOREIGN KEY (`given_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `employee_performances`
--
ALTER TABLE `employee_performances`
  ADD CONSTRAINT `employee_performances_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_shipping_adjusted_by_foreign` FOREIGN KEY (`shipping_adjusted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `pembelians`
--
ALTER TABLE `pembelians`
  ADD CONSTRAINT `pembelians_id_supplier_foreign` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `pembelian_details`
--
ALTER TABLE `pembelian_details`
  ADD CONSTRAINT `pembelian_details_id_pembelian_foreign` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelians` (`id`),
  ADD CONSTRAINT `pembelian_details_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `pembelian_details_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `print_files`
--
ALTER TABLE `print_files`
  ADD CONSTRAINT `print_files_print_order_id_foreign` FOREIGN KEY (`print_order_id`) REFERENCES `print_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `print_files_print_session_id_foreign` FOREIGN KEY (`print_session_id`) REFERENCES `print_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `print_orders`
--
ALTER TABLE `print_orders`
  ADD CONSTRAINT `print_orders_paper_product_id_foreign` FOREIGN KEY (`paper_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `print_orders_paper_variant_id_foreign` FOREIGN KEY (`paper_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `print_orders_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `print_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`),
  ADD CONSTRAINT `product_attribute_values_parent_product_id_foreign` FOREIGN KEY (`parent_product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD CONSTRAINT `product_inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rekaman_stoks`
--
ALTER TABLE `rekaman_stoks`
  ADD CONSTRAINT `rekaman_stoks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `shipments_shipped_by_foreign` FOREIGN KEY (`shipped_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shipments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `slides`
--
ALTER TABLE `slides`
  ADD CONSTRAINT `slides_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD CONSTRAINT `variant_attributes_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wish_lists`
--
ALTER TABLE `wish_lists`
  ADD CONSTRAINT `wish_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `wish_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
