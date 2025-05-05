-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2025 at 09:38 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `bisnis_terkait`
--

CREATE TABLE `bisnis_terkait` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bisnis_terkait`
--

INSERT INTO `bisnis_terkait` (`id`, `name`) VALUES
(1, 'Pengawasan'),
(2, 'Operasional'),
(3, 'Perencanaan'),
(4, 'Perumahan'),
(5, 'Perizinan');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` bigint UNSIGNED NOT NULL,
  `department_name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `department_username`) VALUES
(1, 'Admin', 'Admin'),
(2, 'IT & Management System Department', 'ITMS'),
(3, 'Finance Department', 'Finance'),
(4, 'Human Capital Department', 'HC'),
(6, 'Project Control Department', 'Project Control'),
(7, 'Real Estate Department', 'Real Estate'),
(12, 'Legal Department', 'Legal');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_iku`
--

CREATE TABLE `form_iku` (
  `id` bigint UNSIGNED NOT NULL,
  `iku_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sasaran_id` int NOT NULL,
  `version` int NOT NULL,
  `iku_atasan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_iku_id` bigint UNSIGNED NOT NULL,
  `target` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_multi_point` tinyint(1) NOT NULL DEFAULT '0',
  `base` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stretch` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `satuan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `polaritas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bobot` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_iku`
--

INSERT INTO `form_iku` (`id`, `iku_id`, `sasaran_id`, `version`, `iku_atasan`, `isi_iku_id`, `target`, `is_multi_point`, `base`, `stretch`, `satuan`, `polaritas`, `bobot`) VALUES
(34, 'IKUITMS_2025', 1, 1, 'EBITDA', 40, '83,464 Milyar', 1, '10', '12', '%', 'maximize', 2.00),
(35, 'IKUITMS_2025', 1, 1, 'Biaya CSR', 41, '2,273 Milyar', 0, '6', NULL, 'Jam/kary', 'maximize', 2.00),
(36, 'IKUITMS_2025', 2, 1, NULL, 42, NULL, 0, '100', NULL, '%', 'maximize', 10.00),
(37, 'IKUITMS_2025', 2, 1, NULL, 43, NULL, 1, NULL, NULL, NULL, 'maximize', NULL),
(38, 'IKUITMS_2025', 2, 1, NULL, 45, NULL, 1, NULL, NULL, NULL, 'maximize', NULL),
(39, 'IKUITMS_2025', 2, 1, NULL, 46, NULL, 0, 'Okt 24', NULL, 'bln', 'minimize', 9.00),
(40, 'IKUITMS_2025', 2, 1, NULL, 47, NULL, 1, NULL, NULL, NULL, 'maximize', NULL),
(41, 'IKUITMS_2025', 2, 1, NULL, 48, NULL, 0, '1', NULL, 'Tema', 'maximize', 3.00),
(42, 'IKUITMS_2025', 3, 1, 'Implementasi Sistem Keamanan Kawasan Terintegrasi', 49, '31-des-2024', 0, '100', NULL, '%', 'maximize', 8.00),
(43, 'IKUITMS_2025', 3, 1, 'Pembangunana IPAL Terintegrasi untuk Mendukung Kelestarian Lingkungan', 51, '31-des-2024', 0, '10', NULL, '%', 'maximize', 8.00),
(44, 'IKUITMS_2025', 3, 1, 'Pembangunana IPAL Terintegrasi untuk Mendukung Kelestarian Lingkungan', 52, '31-des-2024', 0, '', NULL, '%', 'maximize', 9.00),
(45, 'IKUITMS_2025', 4, 1, NULL, 53, NULL, 0, 'Des', NULL, 'Waktu', 'minimize', 10.00),
(46, 'IKUITMS_2025', 4, 1, NULL, 54, NULL, 0, '', NULL, 'Aplikasi', 'maximize', 5.00),
(47, 'IKUITMS_2025', 5, 1, NULL, 55, NULL, 0, '100', NULL, '%', 'maximize', 2.00),
(231, 'IKUITMS_2025', 5, 1, NULL, 135, NULL, 0, '0', NULL, 'Kejadian', 'minimize', 2.00),
(232, 'IKUHC_2025', 1, 1, 'Test 1', 136, '1', 0, '1', '2', '1', 'minimize', 3.00),
(248, 'IKUITMS_2025', 1, 2, 'EBITDA', 152, '83,464 Milyar', 1, '10', '12', '%', 'maximize', 2.00),
(249, 'IKUITMS_2025', 1, 2, 'Biaya CSR', 153, '2,273 Milyar', 0, '6', NULL, 'Jam/kary', 'maximize', 2.00),
(250, 'IKUITMS_2025', 2, 2, NULL, 154, NULL, 0, '100', NULL, '%', 'maximize', 10.00),
(251, 'IKUITMS_2025', 2, 2, NULL, 155, NULL, 1, NULL, NULL, NULL, 'maximize', NULL),
(252, 'IKUITMS_2025', 2, 2, NULL, 156, NULL, 1, NULL, NULL, NULL, 'maximize', NULL),
(253, 'IKUITMS_2025', 2, 2, NULL, 157, NULL, 0, 'Okt 24', NULL, 'bln', 'minimize', 9.00),
(254, 'IKUITMS_2025', 2, 2, NULL, 158, NULL, 1, NULL, NULL, NULL, 'maximize', NULL),
(255, 'IKUITMS_2025', 2, 2, NULL, 159, NULL, 0, '1', NULL, 'Tema', 'maximize', 3.00),
(256, 'IKUITMS_2025', 3, 2, 'Implementasi Sistem Keamanan Kawasan Terintegrasi', 160, '31-des-2024', 0, '100', NULL, '%', 'maximize', 8.00),
(257, 'IKUITMS_2025', 3, 2, 'Pembangunana IPAL Terintegrasi untuk Mendukung Kelestarian Lingkungan', 161, '31-des-2024', 0, '10', NULL, '%', 'maximize', 8.00),
(258, 'IKUITMS_2025', 3, 2, 'Pembangunana IPAL Terintegrasi untuk Mendukung Kelestarian Lingkungan', 162, '31-des-2024', 0, '', NULL, '%', 'maximize', 9.00),
(259, 'IKUITMS_2025', 4, 2, NULL, 163, NULL, 0, 'Des', NULL, 'Waktu', 'minimize', 10.00),
(260, 'IKUITMS_2025', 4, 2, NULL, 164, NULL, 0, '', NULL, 'Aplikasi', 'maximize', 5.00),
(261, 'IKUITMS_2025', 5, 2, NULL, 165, NULL, 0, '100', NULL, '%', 'maximize', 2.00),
(262, 'IKUITMS_2025', 5, 2, NULL, 166, NULL, 0, '0', NULL, 'Kejadian', 'minimize', 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `form_kontrak_manajemen`
--

CREATE TABLE `form_kontrak_manajemen` (
  `id` bigint UNSIGNED NOT NULL,
  `kontrak_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sasaran_id` int NOT NULL,
  `kpi_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `milestone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `esgc` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `polaritas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bobot` decimal(5,2) DEFAULT NULL,
  `du` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dk` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `do` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_kontrak_manajemen`
--

INSERT INTO `form_kontrak_manajemen` (`id`, `kontrak_id`, `sasaran_id`, `kpi_name`, `target`, `satuan`, `milestone`, `esgc`, `polaritas`, `bobot`, `du`, `dk`, `do`) VALUES
(1, 'KM_2025', 1, 'EBITDA', '83.464', 'Rp Juta', '-', 'C', 'maximize', 9.00, 'O', 'R', 'R'),
(2, 'KM_2025', 1, 'Laba/(Rugi) Tahun Berjalan', '55.591', 'Rp Juta', '-', 'C', 'maximize', 9.00, 'O', 'R', 'R'),
(3, 'KM_2025', 1, 'Penyelesaian Laporan Audit Tahun 2023', 'Apr-24', 'Waktu', '-', 'G', 'minimize', 5.00, 'O', 'R', 'S'),
(4, 'KM_2025', 2, 'Revenue', '415.176', 'Rp juta', '-', 'C', 'maximize', 5.00, 'O', 'S', 'R'),
(5, 'KM_2025', 2, 'Sinergi Bisnis KS Group', '1', 'Transaksi', '-', 'C', 'maximize', 5.00, 'O', 'R', 'R'),
(6, 'KM_2025', 2, 'Transformasi Bisnis KSP', 'Dec-24', 'Waktu', 'Inbreng Aset tahap 1', 'C', 'minimize', 5.00, 'O', 'R', 'S'),
(7, 'KM_2025', 3, 'Implementasi Sistem Keamanan Kawasan Terintegrasi', 'Dec-24', 'Waktu', 'Dec-24', 'C', 'minimize', 5.00, 'O', 'R', 'R'),
(8, 'KM_2025', 3, 'Penyiapan dan Penerapan Master Plan Smart Eco Industrial Estate', 'Dec-24', 'Waktu', 'Complete penyiapan dan 60% penerapan', 'E', 'minimize', 5.00, 'O', 'R', 'R'),
(9, 'KM_2025', 4, 'Pembangunan Gudang Kawasan Industri Krakatau 2 Tahap 1', 'Mar-24', 'Waktu', 'Selesai Project', 'C', 'minimize', 5.00, 'O', 'S', 'R'),
(10, 'KM_2025', 4, 'Pembangunan Gudang Kawasan Industri Krakatau 1 Tahap 1', 'Dec-24', 'Waktu', 'Selesai Project', 'C', 'minimize', 5.00, 'O', 'S', 'R'),
(11, 'KM_2025', 5, 'Milenial (<=42 Tahun) dalam Nominated Talent', '30', '%', '-', 'G', 'maximize', 5.00, 'O', 'R', 'S'),
(12, 'KM_2025', 5, 'Perempuan dalam Nominated Talent', '5', '%', '-', '', 'maximize', 5.00, 'O', 'R', 'S'),
(13, 'KM_2025', 5, 'Peningkatan Kompetensi Pegawai Melalui Sertifikasi untuk core business skill', '5', 'Jumlah Karyawan', '-', 'G', 'maximize', 5.00, 'O', 'R', 'S'),
(14, 'KM_2025', 1, 'Biaya CSR', '2.273', 'Rp Juta', '-', 'S', 'maximize', 7.00, 'O', 'R', 'S'),
(15, 'KM_2025', 2, 'Pembukaan Cluster Baru Perumahan Bumi Rawa Arum', 'Sep-24', 'Waktu', 'Selesai Project', 'C', 'maximize', 5.00, 'O', 'S', 'R'),
(16, 'KM_2025', 4, 'Perluasan Unit Bisnis luar Cilegon', '1', 'Unit', '-', 'C', 'minimize', 5.00, 'O', 'S', 'R'),
(17, 'KM_2025', 4, 'Krakatau Epicentrum Tahap-1', 'Dec-24', 'Waktu', 'Penyelesaian Tahap Satu', 'C', 'minimize', 5.00, 'O', 'S', 'R'),
(18, 'KM_2025', 3, 'Pembangunan IPAL Terintegrasi untuk Mendukung Kelestarian Lingkungan', 'Dec-24', 'Waktu', 'Selesai Project', 'E', 'minimize', 5.00, 'O', 'S', 'R');

-- --------------------------------------------------------

--
-- Table structure for table `iku`
--

CREATE TABLE `iku` (
  `iku_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department_name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` bigint NOT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iku`
--

INSERT INTO `iku` (`iku_id`, `department_name`, `tahun`, `created_by`) VALUES
('IKUAdmin_2025', 'Admin', 2025, 'Admin'),
('IKUHC_2025', 'HC', 2025, 'HC User 1'),
('IKUITMS_2025', 'ITMS', 2025, 'ITMS User 1');

-- --------------------------------------------------------

--
-- Table structure for table `iku_evaluations`
--

CREATE TABLE `iku_evaluations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `iku_id` bigint UNSIGNED NOT NULL,
  `point_id` bigint UNSIGNED DEFAULT NULL,
  `year` int NOT NULL,
  `month` int NOT NULL,
  `polaritas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bobot` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `base` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `target_bulan_ini` decimal(10,2) DEFAULT NULL,
  `target_sdbulan_ini` decimal(10,2) DEFAULT NULL,
  `realisasi_bulan_ini` decimal(10,2) DEFAULT NULL,
  `realisasi_sdbulan_ini` decimal(10,2) DEFAULT NULL,
  `percent_target` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `percent_year` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ttl` decimal(5,2) DEFAULT NULL,
  `adj` decimal(5,2) DEFAULT NULL,
  `penyebab_tidak_tercapai` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `program_kerja` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iku_evaluations`
--

INSERT INTO `iku_evaluations` (`id`, `user_id`, `iku_id`, `point_id`, `year`, `month`, `polaritas`, `bobot`, `satuan`, `base`, `target_bulan_ini`, `target_sdbulan_ini`, `realisasi_bulan_ini`, `realisasi_sdbulan_ini`, `percent_target`, `percent_year`, `ttl`, `adj`, `penyebab_tidak_tercapai`, `program_kerja`, `created_at`, `updated_at`) VALUES
(7, 2, 34, NULL, 2025, 1, 'maximize', '2.00', '%', '10', 10.00, 10.00, -130.00, -130.00, '-1300%', '-1300%', 0.00, 0.00, NULL, NULL, '2025-02-28 01:29:31', '2025-02-28 01:29:31'),
(8, 2, 35, NULL, 2025, 1, 'maximize', '2.00', 'Jam/kary', '6', 0.50, 0.50, 0.33, 0.33, '66%', '6%', 0.11, 0.11, NULL, NULL, '2025-02-28 01:30:03', '2025-02-28 01:30:03'),
(9, 2, 36, NULL, 2025, 1, 'maximize', '10.00', '%', '100', 8.00, 8.00, 14.00, 14.00, '175%', '14%', 1.40, 1.40, NULL, NULL, '2025-02-28 01:30:27', '2025-02-28 01:30:27'),
(10, 2, 37, 36, 2025, 1, 'minimize', '9.00', 'bulan', 'Juli & Des', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 01:31:16', '2025-02-28 01:31:16'),
(11, 2, 37, 37, 2025, 1, 'maximize', '2.00', '%', '100', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 01:31:48', '2025-02-28 01:31:48'),
(12, 2, 38, 38, 2025, 1, 'maximize', '10.00', '%', '100', 30.50, 30.50, 20.98, 20.98, '69%', '21%', 2.10, 2.10, NULL, NULL, '2025-02-28 01:32:47', '2025-02-28 01:32:47'),
(13, 2, 38, 39, 2025, 1, 'minimize', '2', 'Tanggal', '15', 15.00, 15.00, 19.00, 19.00, '79%', '79%', 1.58, 1.58, NULL, NULL, '2025-02-28 02:39:19', '2025-02-28 02:39:19'),
(14, 2, 39, NULL, 2025, 1, 'minimize', '9.00', 'Bulan', 'Okt', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 02:40:11', '2025-02-28 02:40:11'),
(15, 2, 40, 40, 2025, 1, 'minimize', '5.00', 'Bln', 'Dec', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 02:41:04', '2025-02-28 02:41:04'),
(16, 2, 40, 41, 2025, 1, 'maximize', '2.00', 'Skor', '7.800 Office, 7.500 Non Office', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 02:44:05', '2025-02-28 02:44:05'),
(17, 2, 41, NULL, 2025, 1, 'maximize', '3.00', 'Tema', '1', 1.00, 1.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 02:44:41', '2025-02-28 02:44:41'),
(18, 2, 42, NULL, 2025, 1, 'maximize', '8.00', '%', '100', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 02:45:22', '2025-02-28 02:45:22'),
(19, 2, 43, NULL, 2025, 1, 'maximize', '8.00', '%', '100', 5.00, 5.00, 5.00, 5.00, '100%', '5%', 0.40, 0.40, NULL, NULL, '2025-02-28 02:46:21', '2025-02-28 02:46:21'),
(25, 2, 44, NULL, 2025, 1, 'maximize', '9.00', '%', '100', 30.00, 30.00, 30.00, 30.00, '100%', '30%', 2.70, 2.70, NULL, NULL, '2025-02-28 03:48:51', '2025-02-28 03:48:51'),
(26, 2, 45, NULL, 2025, 1, 'minimize', '10.00', 'Waktu', 'Des', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 03:49:46', '2025-02-28 03:49:46'),
(27, 2, 46, NULL, 2025, 1, 'maximize', '5.00', 'Aplikasi', '6', 1.00, 1.00, 1.00, 1.00, '100%', '17%', 0.83, 0.83, NULL, NULL, '2025-02-28 03:51:09', '2025-02-28 03:51:09'),
(28, 2, 47, NULL, 2025, 1, 'maximize', '2.00', '%', '100', 0.00, 0.00, 0.00, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 03:51:29', '2025-02-28 03:51:29'),
(30, 2, 34, NULL, 2025, 2, 'maximize', '2.00', '%', '10', NULL, 10.00, 97.00, -16.00, '-160%', '-160%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:02:20', '2025-02-28 04:02:20'),
(31, 2, 35, NULL, 2025, 2, 'maximize', '2.00', 'Jam/kary', '6', 0.50, 1.00, 0.00, 0.33, '33%', '6%', 0.11, 0.11, NULL, NULL, '2025-02-28 04:03:08', '2025-02-28 04:03:08'),
(32, 2, 36, NULL, 2025, 2, 'maximize', '10.00', '%', '100', NULL, 16.00, NULL, 19.33, '121%', '19%', 1.93, 1.93, NULL, NULL, '2025-02-28 04:03:52', '2025-02-28 04:03:52'),
(33, 2, 37, 36, 2025, 2, 'minimize', '9.00', 'bulan', 'Juli & Des', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:05:00', '2025-02-28 04:05:00'),
(34, 2, 37, 37, 2025, 2, 'maximize', '2.00', '%', '100', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:05:17', '2025-02-28 04:05:17'),
(35, 2, 38, 38, 2025, 2, 'maximize', '10.00', '%', '100', NULL, 37.68, NULL, 26.71, '71%', '27%', 2.67, 2.67, NULL, NULL, '2025-02-28 04:05:58', '2025-02-28 04:05:58'),
(36, 2, 38, 39, 2025, 2, 'minimize', '2', 'Tanggal', '15', NULL, 15.00, NULL, 19.00, '79%', '79%', 1.58, 1.58, NULL, NULL, '2025-02-28 04:07:01', '2025-02-28 04:07:01'),
(37, 2, 39, NULL, 2025, 2, 'minimize', '9.00', 'Bulan', 'Okt', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:08:01', '2025-02-28 04:08:01'),
(38, 2, 40, 40, 2025, 2, 'minimize', '5.00', 'Bulan', 'Dec 24', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:08:42', '2025-02-28 04:08:42'),
(39, 2, 40, 41, 2025, 2, 'maximize', '2.00', 'Skor', '7.800 Office, 7.500 Non Office', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:09:12', '2025-02-28 04:09:12'),
(40, 2, 41, NULL, 2025, 2, 'maximize', '3.00', 'Tema', '1', NULL, 1.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:09:46', '2025-02-28 04:09:46'),
(41, 2, 42, NULL, 2025, 2, 'maximize', '8.00', '%', '100', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:10:39', '2025-02-28 04:10:39'),
(42, 2, 43, NULL, 2025, 2, 'maximize', '8.00', '%', '100', NULL, 10.00, NULL, 10.00, '100%', '10%', 0.80, 0.80, NULL, NULL, '2025-02-28 04:11:44', '2025-02-28 04:11:44'),
(43, 2, 44, NULL, 2025, 2, 'maximize', '9.00', '%', '100', NULL, 40.00, NULL, 40.00, '100%', '40%', 3.60, 3.60, NULL, NULL, '2025-02-28 04:12:44', '2025-02-28 04:12:44'),
(44, 2, 45, NULL, 2025, 2, 'minimize', '10.00', 'Waktu', 'Des', NULL, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:13:08', '2025-02-28 04:13:08'),
(45, 2, 46, NULL, 2025, 2, 'maximize', '5.00', 'Aplikasi', '6', NULL, 1.00, NULL, 1.00, '100%', '17%', 0.83, 0.83, NULL, NULL, '2025-02-28 04:13:43', '2025-02-28 04:13:43'),
(46, 2, 47, NULL, 2025, 2, 'maximize', '2.00', '%', '100', 0.00, 0.00, NULL, 0.00, '0%', '0%', 0.00, 0.00, NULL, NULL, '2025-02-28 04:14:09', '2025-02-28 04:14:09'),
(49, 5, 34, NULL, 2025, 3, 'maximize', '2.00', '%', '10', NULL, 10.00, 42.00, 22.00, '220%', '220%', 4.40, 2.40, NULL, NULL, '2025-03-06 03:39:55', '2025-03-06 03:39:55'),
(50, 2, 231, NULL, 2025, 1, 'minimize', '2.00', 'Kejadian', '0', 0.00, 0.00, 0.00, 0.00, '100%', '100%', 2.00, 2.00, NULL, NULL, '2025-04-22 03:00:13', '2025-04-22 03:00:13'),
(51, 2, 231, NULL, 2025, 2, 'minimize', '2.00', 'Kejadian', '0', 0.00, 0.00, 0.00, 0.00, '100%', '100%', 2.00, 2.00, NULL, NULL, '2025-04-22 03:07:22', '2025-04-22 03:07:22'),
(52, 2, 34, NULL, 2025, 4, 'maximize', '2.00', '%', '10', 10.00, 10.00, -52.00, -11.00, '-110%', '-110%', 0.00, 0.00, NULL, NULL, '2025-04-22 04:32:55', '2025-04-22 04:32:55'),
(53, 2, 35, NULL, 2025, 4, 'maximize', '2.00', 'Jam/kary', '6', 0.50, 2.00, 0.30, 1.30, '65%', '22%', 0.43, 0.43, NULL, NULL, '2025-04-22 04:33:41', '2025-04-22 04:33:41'),
(54, 4, 232, NULL, 2025, 4, 'minimize', '3.00', '1', '1', 2.00, 2.00, 6.83, 0.33, '250%', '250%', 7.50, 3.60, NULL, NULL, '2025-04-23 01:23:22', '2025-04-23 01:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `iku_point`
--

CREATE TABLE `iku_point` (
  `id` bigint UNSIGNED NOT NULL,
  `form_iku_id` bigint UNSIGNED NOT NULL,
  `point_name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `base` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stretch` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `satuan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `polaritas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bobot` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iku_point`
--

INSERT INTO `iku_point` (`id`, `form_iku_id`, `point_name`, `base`, `stretch`, `satuan`, `polaritas`, `bobot`) VALUES
(36, 37, 'a. Corporate. - laporan profil risiko inheren dan residual', 'Juli & Des', NULL, 'bulan', 'minimize', 9.00),
(37, 37, 'b. Unit Kerja (identifikasi & mitigasi risisiko)', '100', NULL, '%', 'maximize', 2.00),
(38, 38, 'a. Kontrak Manajemen, Evaluasi Iku dan Monitoring', '100', NULL, '%', 'maximize', 10.00),
(39, 38, 'b. Pengumpulan Evaluasi IKU Bulanan (Mandatory)', '15', NULL, 'Tanggal', 'minimize', 2.00),
(40, 40, 'a. Asessment 5R unit kerja', 'Dec 24', NULL, 'Bln', 'minimize', 5.00),
(41, 40, 'b. Skor 5r', '7.800 Office, 7.500 Non Office', NULL, 'Skor', 'maximize', 2.00),
(102, 251, 'a. Corporate. - laporan profil risiko inheren dan residual', 'Juli & Des', NULL, 'bulan', 'minimize', 9.00),
(103, 251, 'b. Unit Kerja (identifikasi & mitigasi risisiko)', '100', NULL, '%', 'maximize', 2.00),
(104, 252, 'a. Kontrak Manajemen, Evaluasi Iku dan Monitoring', '100', NULL, '%', 'maximize', 10.00),
(105, 252, 'b. Pengumpulan Evaluasi IKU Bulanan (Mandatory)', '15', NULL, 'Tanggal', 'minimize', 2.00),
(106, 254, 'a. Asessment 5R unit kerja', 'Dec 24', NULL, 'Bln', 'minimize', 5.00),
(107, 254, 'b. Skor 5r', '7.800 Office, 7.500 Non Office', NULL, 'Skor', 'maximize', 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `isi_iku`
--

CREATE TABLE `isi_iku` (
  `id` bigint UNSIGNED NOT NULL,
  `iku` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proker` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pj` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `isi_iku`
--

INSERT INTO `isi_iku` (`id`, `iku`, `proker`, `pj`) VALUES
(40, '1. Pengendalian Biaya Cost Center, Konsultan, Rapat, Consumable', 'Mengendalikan Biaya Cost Center, Biaya Konsultan, Biaya Rapat dan lain lain', 'Manager'),
(41, '2. CSR', 'Keterlibatan dalam kegiatan CSR', 'Manager & All Staff'),
(42, '3. Improvement Prosedur untuk perbaikan proses (150 dokumen)', '1. Develop/ IK /SOP /SD lanjutan terkait Perubahan Skema Bisnis\r\n2. Develop /Revisi Prosedur / IK / SOP / SD rutin berdasarkan permintaan User & Evaluasi Kesesuain Proses', 'Manager, Management System Group'),
(43, '4. Pengelolaan Risiko:', '1. Koordinasi identifakasi, Mitigasi Risiko Corporate dan Unit Kerja.\r\n2. Monitoring pelaksanaan Mitigasi Risiko Corporate & Unit Kerja\r\n3. Menyiapkan dan Melaporka Profil Risiko Inheren dan Residual Corporate & Unit Kerja', 'Manager, Management System Group'),
(45, '5. Pengelolaan Performance Management', '1. Koordinasi Penyusuanan dan pengumpulan IKU Unit Bisnis dan Pendukung.\r\n2. Monitoring Pencapaian IKU Unit Bisnis dan Pendukung', 'Manager, Management System Group'),
(46, '6. Assessment improvement dan Pelaksanaan konvensi mutu internal', '1. Pelaksanaan Gemba Improvement di Unit Bisnis dan Pendukung\r\n2. Koordinasi pengumpulan makalah gugus, asessment PDCA\r\n3. Pelaksanaan konvensi mutu', 'Manager, Management System Group'),
(47, '7. Implementasi 5R', '1. Pembentukan Assessor, Awareness, Pelaksanan Asessment, Laporan Hasil Assessment\r\n3. Mengimplementasikan Program dan Standar 5R', 'Manager, Management System Group'),
(48, '8. Improvement', '1. Menyelesaikan Tema Gugus\r\n2. Asessment dan Konvensi Mutu', 'Manager & All Staff'),
(49, '9. Implementasi Sistem Keamanan Kawasan Terintegarasi', '1. All Vehicle Detection & Traffic Management\r\n2. Gate System', 'Manager, IT Group'),
(51, '10. Penyiapan Masterplan Smart Eco Industrial Estate', 'Penyiapan Master Plan Smart Eco Industrial Estate', 'Manager, IT Group'),
(52, '11. Implementasi Digitalisasi Smart Eco Industrial Estate (System Monitoring dan Pengendalian Kawasan Industri)', '1. Krakatau Information Tenant Service\r\n2. Sistem Informasi Manajemen Gudang\r\n3. Middleware', 'Manager, IT Group'),
(53, '12. Peningkatan Infrastruktur Jaringan, Data dan Keamanan Data', '1. Penambahan Fiber Optic Kawasan\r\n2. Security Server dan Website (SSL)', 'Manager, IT Group'),
(54, '13. Digitalisasi sistem internal', 'Implementasi software internal', 'Manager, IT Group'),
(55, '14. Pemenuhan Dokumen ESG', 'Menyiapkan Dokumen Pendukung ESG', 'Manager'),
(135, '15. Zero Accident', '1. Identifikasi dan Tindakan Preventif\r\n2. Awareness terhadap K3', 'Manager'),
(136, '1. Test', '1. Test\r\n2. Test', 'Manager'),
(152, '1. Pengendalian Biaya Cost Center, Konsultan, Rapat, Consumable', 'Mengendalikan Biaya Cost Center, Biaya Konsultan, Biaya Rapat dan lain lain', 'Manager'),
(153, '2. CSR', 'Keterlibatan dalam kegiatan CSR', 'Manager & All Staff'),
(154, '3. Improvement Prosedur untuk perbaikan proses (150 dokumen)', '1. Develop/ IK /SOP /SD lanjutan terkait Perubahan Skema Bisnis\r\n2. Develop /Revisi Prosedur / IK / SOP / SD rutin berdasarkan permintaan User & Evaluasi Kesesuain Proses', 'Manager, Management System Group'),
(155, '4. Pengelolaan Risiko:', '1. Koordinasi identifakasi, Mitigasi Risiko Corporate dan Unit Kerja.\r\n2. Monitoring pelaksanaan Mitigasi Risiko Corporate & Unit Kerja\r\n3. Menyiapkan dan Melaporka Profil Risiko Inheren dan Residual Corporate & Unit Kerja', 'Manager, Management System Group'),
(156, '5. Pengelolaan Performance Management', '1. Koordinasi Penyusuanan dan pengumpulan IKU Unit Bisnis dan Pendukung.\r\n2. Monitoring Pencapaian IKU Unit Bisnis dan Pendukung', 'Manager, Management System Group'),
(157, '6. Assessment improvement dan Pelaksanaan konvensi mutu internal', '1. Pelaksanaan Gemba Improvement di Unit Bisnis dan Pendukung\r\n2. Koordinasi pengumpulan makalah gugus, asessment PDCA\r\n3. Pelaksanaan konvensi mutu', 'Manager, Management System Group'),
(158, '7. Implementasi 5R', '1. Pembentukan Assessor, Awareness, Pelaksanan Asessment, Laporan Hasil Assessment\r\n3. Mengimplementasikan Program dan Standar 5R', 'Manager, Management System Group'),
(159, '8. Improvement', '1. Menyelesaikan Tema Gugus\r\n2. Asessment dan Konvensi Mutu', 'Manager & All Staff'),
(160, '9. Implementasi Sistem Keamanan Kawasan Terintegarasi', '1. All Vehicle Detection & Traffic Management\r\n2. Gate System', 'Manager, IT Group'),
(161, '10. Penyiapan Masterplan Smart Eco Industrial Estate', 'Penyiapan Master Plan Smart Eco Industrial Estate', 'Manager, IT Group'),
(162, '11. Implementasi Digitalisasi Smart Eco Industrial Estate (System Monitoring dan Pengendalian Kawasan Industri)', '1. Krakatau Information Tenant Service\r\n2. Sistem Informasi Manajemen Gudang\r\n3. Middleware', 'Manager, IT Group'),
(163, '12. Peningkatan Infrastruktur Jaringan, Data dan Keamanan Data', '1. Penambahan Fiber Optic Kawasan\r\n2. Security Server dan Website (SSL)', 'Manager, IT Group'),
(164, '13. Digitalisasi sistem internal', 'Implementasi software internal', 'Manager, IT Group'),
(165, '14. Pemenuhan Dokumen ESG', 'Menyiapkan Dokumen Pendukung ESG', 'Manager'),
(166, '15. Zero Accident2', '1. Identifikasi dan Tindakan Preventif\r\n2. Awareness terhadap K3', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kontrak_manajemen`
--

CREATE TABLE `kontrak_manajemen` (
  `kontrak_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `year` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontrak_manajemen`
--

INSERT INTO `kontrak_manajemen` (`kontrak_id`, `year`) VALUES
('KM_2024', 2024),
('KM_2025', 2025);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_25_025248_create_iku_evaluations_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjabaran_strategis`
--

CREATE TABLE `penjabaran_strategis` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` bigint UNSIGNED NOT NULL,
  `proses_bisnis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `strategis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pic` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjabaran_strategis`
--

INSERT INTO `penjabaran_strategis` (`id`, `form_id`, `proses_bisnis`, `strategis`, `pic`) VALUES
(7, 1, 'Operasional, Penjualan', '- Peningkatan Penjualan di Unit Bisnis\r\n- Efesiensi biaya masin2 cc', 'GM, Mgr unit bisnis dan pendukung'),
(8, 2, '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `progres`
--

CREATE TABLE `progres` (
  `id` bigint NOT NULL,
  `iku_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','accept','reject') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `need_discussion` tinyint(1) DEFAULT '0',
  `meeting_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progres`
--

INSERT INTO `progres` (`id`, `iku_id`, `user_id`, `status`, `need_discussion`, `meeting_date`, `notes`, `created_at`) VALUES
(11, 'IKUITMS_2025', 2, 'accept', 0, '2025-02-21', NULL, '2025-02-19 07:22:48'),
(16, 'IKUHC_2025', 4, 'pending', NULL, '2025-03-05', NULL, '2025-03-05 07:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `re_bisnis_department`
--

CREATE TABLE `re_bisnis_department` (
  `department_id` bigint UNSIGNED NOT NULL,
  `bisnis_terkait_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `re_user_department`
--

CREATE TABLE `re_user_department` (
  `user_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `department_role` enum('Admin','User') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `re_user_department`
--

INSERT INTO `re_user_department` (`user_id`, `department_id`, `department_role`) VALUES
(1, 1, 'Admin'),
(2, 2, 'Admin'),
(3, 3, 'Admin'),
(4, 4, 'Admin'),
(5, 2, 'User'),
(6, 2, 'User'),
(7, 4, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `sasaran_strategis`
--

CREATE TABLE `sasaran_strategis` (
  `id` int NOT NULL,
  `kontrak_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `position` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sasaran_strategis`
--

INSERT INTO `sasaran_strategis` (`id`, `kontrak_id`, `name`, `position`) VALUES
(1, 'KM_2025', 'Nilai Ekonomi dan Sosial Untuk Indonesia', 0),
(2, 'KM_2025', 'Inovasi Model Bisnis', 0),
(3, 'KM_2025', 'Kepemimpinan Teknologi', 0),
(4, 'KM_2025', 'Peningkatan Investasi', 0),
(5, 'KM_2025', 'Pengembangan Talenta', 0),
(6, 'KM_2024', 'Tes 1', 0),
(7, 'KM_2024', 'Test 1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1KRnPoqohfPNeq52HDAJaHR5zz90XFsSjJrGyNma', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZUVERWoyNDI2dFFmbnRpNHI2Z1d1ZXhpdlRDdTlBeENyS1pIWTk0RCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQtYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1746436822),
('XrMMVcqE9DFbnrASjacUhFIthdvxX3xWyed9eRzs', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidWZEQm5sMTBkMnZBYkU1QjJ2VUk1YUViTWlPNGtwejBBelgzaE5CWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1746436082);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `department_id`, `password`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'admin', 1, '$2y$12$DCGZEXK/TseJDQSfnJan.OVTT5Votu17jKSyaIOOVWrZly6GTTNGu', '2025-02-03 12:54:14', '2025-02-03 12:54:14', 'admin'),
(2, 'ITMS User 1', 'art', 2, '$2y$12$WvmBiQXpNCRS.oSwpIcdnuvgwhkL1/2X07pdOfUqCdpNAqlZbVvSS', '2025-02-05 13:37:16', '2025-03-17 18:33:11', 'user'),
(3, 'Finance User 1\r\n', 'patrick', 3, '$2y$12$oQk4e93Fs.FH3v/2lDPqtuYKu472fHFs1IYTMMk5tMdTDWxnVbIsG', '2025-02-10 14:28:52', '2025-02-10 14:28:52', 'user'),
(4, 'HC User 1', 'tashi', 4, '$2y$12$BNOF2qCmM1lBBJax7L3X9u9mro3h2CQWLAVRT/Rb4mnIkFnj0JxXe', '2025-02-27 01:23:35', '2025-02-27 01:23:35', 'user'),
(5, 'ITMS User 2', 'joe', 2, '$2y$12$tHCECVWPRXus5iKePGSYOOE3Kk9macrllAcdvotycnUHkgiw.vWFe', '2025-03-05 00:16:58', '2025-03-05 00:44:08', 'user'),
(6, 'ITMS User 3', 'paul', 2, '$2y$12$ntmo.xX1sN8LwtMNkL1DJ.zVCBN9kkWS4CrcjQtTNxyfeaNIchvnq', '2025-04-21 18:39:04', '2025-04-21 18:39:04', 'user'),
(7, 'HC User 2', 'mickey', 4, '$2y$12$IRY6s/Seq0TN2yzQgx8NnOVKKGF1f7yveK7euYN3OBHVF/qM8/yLa', '2025-04-23 01:23:08', '2025-04-23 01:23:08', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bisnis_terkait`
--
ALTER TABLE `bisnis_terkait`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `form_iku`
--
ALTER TABLE `form_iku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_iku_id` (`iku_id`),
  ADD KEY `fk_iku_sasaran_id` (`sasaran_id`),
  ADD KEY `form_iku_ibfk_1` (`isi_iku_id`);

--
-- Indexes for table `form_kontrak_manajemen`
--
ALTER TABLE `form_kontrak_manajemen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sasaran_id` (`sasaran_id`),
  ADD KEY `form_kontrak_ibfk_2` (`kontrak_id`);

--
-- Indexes for table `iku`
--
ALTER TABLE `iku`
  ADD PRIMARY KEY (`iku_id`),
  ADD KEY `iku_ibfk_1` (`created_by`);

--
-- Indexes for table `iku_evaluations`
--
ALTER TABLE `iku_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iku_evaluations_user_id_foreign` (`user_id`),
  ADD KEY `iku_evaluasi_ibfk_1` (`iku_id`),
  ADD KEY `iku_evaluation_ibfk_2` (`point_id`);

--
-- Indexes for table `iku_point`
--
ALTER TABLE `iku_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_iku_id` (`form_iku_id`);

--
-- Indexes for table `isi_iku`
--
ALTER TABLE `isi_iku`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `kontrak_manajemen`
--
ALTER TABLE `kontrak_manajemen`
  ADD PRIMARY KEY (`kontrak_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penjabaran_strategis`
--
ALTER TABLE `penjabaran_strategis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strategis_ibfk_1` (`form_id`);

--
-- Indexes for table `progres`
--
ALTER TABLE `progres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progres_ibfk_1` (`iku_id`),
  ADD KEY `progres_ibfk_2` (`user_id`);

--
-- Indexes for table `re_bisnis_department`
--
ALTER TABLE `re_bisnis_department`
  ADD PRIMARY KEY (`department_id`,`bisnis_terkait_id`),
  ADD KEY `fk_bisnis_terkait` (`bisnis_terkait_id`);

--
-- Indexes for table `re_user_department`
--
ALTER TABLE `re_user_department`
  ADD PRIMARY KEY (`user_id`,`department_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `sasaran_strategis`
--
ALTER TABLE `sasaran_strategis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kontrak_id` (`kontrak_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_name_unique` (`nama`),
  ADD KEY `user_department_ibfk_1` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bisnis_terkait`
--
ALTER TABLE `bisnis_terkait`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_iku`
--
ALTER TABLE `form_iku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `form_kontrak_manajemen`
--
ALTER TABLE `form_kontrak_manajemen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `iku_evaluations`
--
ALTER TABLE `iku_evaluations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `iku_point`
--
ALTER TABLE `iku_point`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `isi_iku`
--
ALTER TABLE `isi_iku`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `penjabaran_strategis`
--
ALTER TABLE `penjabaran_strategis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `progres`
--
ALTER TABLE `progres`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sasaran_strategis`
--
ALTER TABLE `sasaran_strategis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_iku`
--
ALTER TABLE `form_iku`
  ADD CONSTRAINT `fk_iku_id` FOREIGN KEY (`iku_id`) REFERENCES `iku` (`iku_id`),
  ADD CONSTRAINT `fk_iku_sasaran_id` FOREIGN KEY (`sasaran_id`) REFERENCES `sasaran_strategis` (`id`),
  ADD CONSTRAINT `form_iku_ibfk_1` FOREIGN KEY (`isi_iku_id`) REFERENCES `isi_iku` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `form_kontrak_manajemen`
--
ALTER TABLE `form_kontrak_manajemen`
  ADD CONSTRAINT `form_kontrak_ibfk_2` FOREIGN KEY (`kontrak_id`) REFERENCES `kontrak_manajemen` (`kontrak_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `form_kontrak_manajemen_ibfk_1` FOREIGN KEY (`sasaran_id`) REFERENCES `sasaran_strategis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `iku`
--
ALTER TABLE `iku`
  ADD CONSTRAINT `iku_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`nama`) ON DELETE CASCADE;

--
-- Constraints for table `iku_evaluations`
--
ALTER TABLE `iku_evaluations`
  ADD CONSTRAINT `iku_evaluasi_ibfk_1` FOREIGN KEY (`iku_id`) REFERENCES `form_iku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `iku_evaluation_ibfk_2` FOREIGN KEY (`point_id`) REFERENCES `iku_point` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `iku_evaluations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `iku_point`
--
ALTER TABLE `iku_point`
  ADD CONSTRAINT `iku_point_ibfk_1` FOREIGN KEY (`form_iku_id`) REFERENCES `form_iku` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penjabaran_strategis`
--
ALTER TABLE `penjabaran_strategis`
  ADD CONSTRAINT `strategis_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `form_kontrak_manajemen` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progres`
--
ALTER TABLE `progres`
  ADD CONSTRAINT `progres_ibfk_1` FOREIGN KEY (`iku_id`) REFERENCES `iku` (`iku_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progres_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `re_bisnis_department`
--
ALTER TABLE `re_bisnis_department`
  ADD CONSTRAINT `fk_bisnis_terkait` FOREIGN KEY (`bisnis_terkait_id`) REFERENCES `bisnis_terkait` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `re_user_department`
--
ALTER TABLE `re_user_department`
  ADD CONSTRAINT `re_user_department_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `re_user_department_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `sasaran_strategis`
--
ALTER TABLE `sasaran_strategis`
  ADD CONSTRAINT `sasaran_strategis_ibfk_1` FOREIGN KEY (`kontrak_id`) REFERENCES `kontrak_manajemen` (`kontrak_id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_department_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
