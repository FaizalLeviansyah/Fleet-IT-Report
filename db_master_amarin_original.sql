-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_master_amarin_original
CREATE DATABASE IF NOT EXISTS `db_master_amarin_original` /*!40100 DEFAULT CHARACTER SET armscii8 COLLATE armscii8_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_master_amarin_original`;

-- Dumping structure for table db_master_amarin_original.tbl_company
CREATE TABLE IF NOT EXISTS `tbl_company` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `company_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `logo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `company_code` (`company_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_master_amarin_original.tbl_company: ~3 rows (approximately)
INSERT INTO `tbl_company` (`company_id`, `company_code`, `company_name`, `address`, `phone`, `created_at`, `updated_at`, `logo_path`, `is_deleted`) VALUES
	(1, 'ASM', 'Amarin Ship Management', 'Citra Tower jl Benyamin Sueb kav 6a, lt 08 Unit K-L, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta', NULL, '2025-12-15 02:33:14', '2026-01-05 05:39:02', 'Logo_PT_ASM.jpg', 0),
	(2, 'CTP', 'Caraka Tirta Pratama', 'Jalan Mangga Dua Raya Blok JJ/KK No.39, Mangga Dua Selatan, Sawah Besar, RT.4/RW.4, Pinangsia, Kec. Taman Sari, Kota Jakarta Pusat', '(021) 6922523', '2025-12-22 10:06:15', '2025-12-22 10:06:15', NULL, 0),
	(3, 'ACS', 'Amarin Crewing Services', 'Rukan Mangga Dua Square Blok H No. 23, Daerah Khusus Ibukota Jakarta ', '(021) 38269211', '2025-12-22 10:07:24', '2025-12-22 10:07:24', NULL, 0);

-- Dumping structure for table db_master_amarin_original.tbl_department
CREATE TABLE IF NOT EXISTS `tbl_department` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `department_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`department_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `tbl_department_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_master_amarin_original.tbl_department: ~10 rows (approximately)
INSERT INTO `tbl_department` (`department_id`, `company_id`, `department_name`, `description`, `created_at`, `updated_at`, `is_deleted`) VALUES
	(1, 1, 'BOD', NULL, '2025-12-15 02:33:15', '2025-12-22 10:11:50', 0),
	(2, 1, 'IT', NULL, '2025-12-22 10:17:20', '2025-12-22 10:18:12', 0),
	(3, 1, 'Technical', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(4, 1, 'Operation', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(5, 1, 'Finance', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(6, 1, 'Marine, Ops', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(7, 1, 'HSSEQ', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(8, 1, 'Crewing', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(9, 1, 'DPA', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0),
	(10, 1, 'HRD', NULL, '2025-12-22 10:17:20', '2025-12-22 10:17:20', 0);

-- Dumping structure for table db_master_amarin_original.tbl_department_position
CREATE TABLE IF NOT EXISTS `tbl_department_position` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `department_id` int NOT NULL,
  `position_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_master_amarin_original.tbl_department_position: ~25 rows (approximately)
INSERT INTO `tbl_department_position` (`id`, `company_id`, `department_id`, `position_id`) VALUES
	(1, 1, 2, 1),
	(2, 1, 2, 22),
	(3, 1, 2, 23),
	(4, 1, 2, 25),
	(5, 1, 2, 26),
	(8, 1, 10, 16),
	(9, 1, 10, 19),
	(10, 1, 10, 20),
	(11, 1, 10, 21),
	(12, 1, 10, 29),
	(15, 1, 5, 2),
	(16, 1, 5, 6),
	(17, 1, 5, 9),
	(18, 1, 5, 14),
	(22, 1, 8, 11),
	(23, 1, 8, 24),
	(25, 1, 3, 7),
	(26, 1, 3, 8),
	(27, 1, 3, 13),
	(28, 1, 3, 15),
	(29, 1, 3, 27),
	(32, 1, 7, 10),
	(33, 1, 7, 12),
	(35, 1, 4, 5),
	(36, 1, 4, 18);

-- Dumping structure for table db_master_amarin_original.tbl_employee
CREATE TABLE IF NOT EXISTS `tbl_employee` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `is_it_team` tinyint(1) NOT NULL DEFAULT '0',
  `employee_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `employee_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email_work` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `signature_path` varchar(2048) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expired_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'staff',
  `company_id` int NOT NULL,
  `department_id` int DEFAULT NULL,
  `position_id` int DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `employment_status` enum('Active','Inactive') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `is_active` tinyint(1) DEFAULT '1',
  `access_app_IT_Management_System` tinyint(1) DEFAULT '0' COMMENT 'Akses IT Management (1=Ya, 0=Tidak)',
  `access_app_rl` tinyint(1) DEFAULT '0' COMMENT 'Akses ke RL Monitoring (1=Ya, 0=Tidak)',
  `access_app_audit` tinyint(1) DEFAULT '0' COMMENT 'Akses ke E-Audit System (1=Ya, 0=Tidak)',
  `access_amarinform` tinyint(1) DEFAULT '0' COMMENT 'Akses ke AmarinForm (1=Ya, 0=Tidak)',
  `employment_type` enum('Permanent','Contract','Intern','Freelance') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `employee_code` (`employee_code`),
  UNIQUE KEY `email_work` (`email_work`),
  UNIQUE KEY `employee_number` (`employee_number`),
  KEY `company_id` (`company_id`),
  KEY `department_id` (`department_id`),
  KEY `position_id` (`position_id`),
  CONSTRAINT `tbl_employee_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`company_id`),
  CONSTRAINT `tbl_employee_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `tbl_department` (`department_id`),
  CONSTRAINT `tbl_employee_ibfk_3` FOREIGN KEY (`position_id`) REFERENCES `tbl_position` (`position_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_master_amarin_original.tbl_employee: ~37 rows (approximately)
INSERT INTO `tbl_employee` (`employee_id`, `is_it_team`, `employee_code`, `employee_number`, `full_name`, `avatar_url`, `jabatan`, `gender`, `date_of_birth`, `email_work`, `profile_photo_path`, `signature_path`, `phone`, `address`, `password`, `remember_token`, `reset_token`, `reset_token_expired_at`, `last_login_at`, `role`, `company_id`, `department_id`, `position_id`, `join_date`, `employment_status`, `is_active`, `access_app_IT_Management_System`, `access_app_rl`, `access_app_audit`, `access_amarinform`, `employment_type`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`, `created_by`, `updated_by`) VALUES
	(1, 0, 'ADM001', NULL, 'Super Admin', NULL, NULL, NULL, NULL, 'admin@kantor.com', NULL, NULL, NULL, NULL, '$2y$12$HyAYmUpCiMvDRz7PVGHgnevsqXEmqxDnIh5Ea4XXzrj/QigOT719W', NULL, NULL, NULL, NULL, 'admin', 1, 1, 28, '2025-12-15', 'Active', 1, 0, 1, 1, 1, NULL, '2025-12-15 02:33:15', '2026-05-26 00:56:02', NULL, 0, NULL, NULL),
	(2, 0, 'HR01', NULL, 'Felix', NULL, NULL, 'Male', NULL, 'felix@asm.com', NULL, NULL, NULL, NULL, '$2y$12$vuH8kY7bQHpVvtOB7X3gyOfw/Jzj0HvwUNB2PtCHlOvaZHRClb3TC', NULL, NULL, NULL, NULL, 'staff', 1, 10, 29, '2025-12-15', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-15 07:09:09', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(3, 0, 'EMP001', NULL, 'Agung Bagja Budiman', NULL, NULL, 'Male', NULL, 'agung.bagja@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 1, 2, '2025-12-22', 'Active', 1, 0, 1, 1, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(4, 0, 'EMP002', NULL, 'Dinesh Rampal', NULL, NULL, 'Male', NULL, 'dinesh.rampal@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 3, 3, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(5, 0, 'EMP003', NULL, 'Jitendar Malhotra', NULL, NULL, 'Male', NULL, 'jitendar.malhotra@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 4, 4, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(6, 0, 'EMP004', NULL, 'Rusmiyati (Nila)', NULL, NULL, 'Female', NULL, 'rusmiyati@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 4, 5, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(7, 0, 'EMP005', NULL, 'Vonny Chandra Dewi', NULL, NULL, 'Female', NULL, 'vonny.chandra@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 5, 6, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(8, 0, 'EMP006', NULL, 'Ahmad Suwandi', NULL, NULL, 'Male', NULL, 'ahmad.suwandi@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 3, 7, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(9, 0, 'EMP007', NULL, 'Ruly Samratulangi', NULL, NULL, 'Male', NULL, 'ruly.samratulangi@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 6, 8, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(10, 0, 'EMP008', NULL, 'Aulia Dewi Fortuna', NULL, NULL, 'Female', NULL, 'aulia.dewi@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 5, 9, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(11, 0, 'EMP009', NULL, 'Lucas Timbul Parasian Simanjorang', NULL, NULL, 'Male', NULL, 'lucas.timbul@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 3, 7, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-05-17 23:52:04', NULL, 0, NULL, NULL),
	(12, 0, 'EMP010', NULL, 'Jesica Mutiara', NULL, NULL, 'Female', NULL, 'jesica.mutiara@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 7, 10, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(13, 0, 'EMP011', NULL, 'Sindang Sari Sihite', NULL, NULL, 'Female', NULL, 'sindang.sari@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 8, 11, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(14, 0, 'EMP012', NULL, 'Haryanto', NULL, NULL, 'Male', NULL, 'haryanto@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 9, 12, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(15, 0, 'EMP013', NULL, 'Jumadi Nurdin', NULL, NULL, 'Male', NULL, 'jumadi.nurdin@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 6, 13, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(16, 0, 'EMP014', NULL, 'Ari Budi Utomo', NULL, NULL, 'Male', NULL, 'ari.budi@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 5, 14, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(17, 0, 'EMP015', NULL, 'Rifqi Farhan Ramadhan', NULL, NULL, 'Male', NULL, 'rifqi.farhan@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 3, 15, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(18, 0, 'EMP016', NULL, 'Muhamad Aris', NULL, NULL, 'Male', NULL, 'muhamad.aris@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 10, 16, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(19, 0, 'EMP017', NULL, 'Muhamad Firman', NULL, NULL, 'Male', NULL, 'muhamad.firman@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 6, 17, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(20, 0, 'EMP018', NULL, 'Amanda Putri M', NULL, NULL, 'Female', NULL, 'amanda.putri@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 8, 17, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(21, 0, 'EMP019', NULL, 'Juanes Midya Ayu Mintari', NULL, NULL, 'Female', NULL, 'juanes.midya@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 4, 18, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(22, 0, 'EMP020', NULL, 'Theodorus Felix', NULL, NULL, 'Male', NULL, 'theodorus.felix@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 10, 19, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(23, 0, 'EMP021', NULL, 'Aulia El Fadhillah', NULL, NULL, 'Male', NULL, 'aulia.el@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 10, 20, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(24, 0, 'EMP022', NULL, 'Estanti Damaryanti', NULL, NULL, 'Male', NULL, 'estanti.damaryanti@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 10, 21, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(25, 0, 'EMP023', NULL, 'Hendry Setio Prakoso', NULL, NULL, 'Male', NULL, 'hendry.setio@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 2, 22, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(26, 0, 'EMP024', NULL, 'Ridho Septadi Mirandita', NULL, NULL, 'Male', NULL, 'ridho.septadi@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 2, 23, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(27, 0, 'EMP025', NULL, 'Rinus Indra Komara', NULL, NULL, 'Male', NULL, 'rinus.indra@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 8, 24, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(28, 0, 'EMP026', NULL, 'Rachim Chaidir Yazid', NULL, NULL, 'Male', NULL, 'rachim.chaidir@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 7, 25, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(29, 1, 'EMP027', NULL, 'Farhan Arif Indiarto', NULL, NULL, 'Male', NULL, 'farhan.arif@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 2, 26, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-06-04 04:05:10', NULL, 0, NULL, NULL),
	(30, 1, 'EMP028', NULL, 'Faizal Leviansyah', NULL, 'IT Staff', 'Male', NULL, 'faizal.leviansyah@company.com', NULL, NULL, NULL, NULL, '$2y$12$YtakU.HiaHbXn39u6RPOq.YqYZ97OTjuBD6284ma.aUzGYa1.BhF.', 'y1BSybJ98C2N2NyCBMeORBQU2No2X9bofD06MVcbWOxBEPPZKSBwg5KSf3rU', NULL, NULL, NULL, 'admin', 1, 2, 26, '2025-12-22', 'Active', 1, 1, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-06-04 04:05:10', NULL, 0, NULL, NULL),
	(31, 0, 'EMP029', NULL, 'Tengku Imam Munandar', NULL, NULL, 'Male', NULL, 'tengku.imam@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 3, 27, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(32, 0, 'EMP030', NULL, 'Hadiyanto Basri', NULL, NULL, 'Male', NULL, 'hadiyanto.basri@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 3, 27, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(33, 0, 'EMP031', NULL, 'Ryeska Awalia Saputri', NULL, NULL, 'Female', NULL, 'ryeska.awalia@company.com', NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, 'staff', 1, 8, 11, '2025-12-22', 'Active', 1, 0, 0, 0, 1, NULL, '2025-12-22 10:27:21', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(34, 0, 'ADM-001', NULL, 'Super Administrator', NULL, NULL, NULL, NULL, 'admin@amarin.com', NULL, NULL, NULL, NULL, '$2y$12$IBUzLCbl9MI9tKLankKmyewGZp8763GhX2D5FBF3Xc5/PnkxljSa6', NULL, NULL, NULL, NULL, 'admin', 1, NULL, NULL, NULL, 'Active', 1, 0, 1, 1, 1, NULL, '2026-01-11 20:49:51', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(35, 0, 'AUD-001', NULL, 'Auditor Lapangan', NULL, NULL, NULL, NULL, 'auditor@amarin.com', NULL, NULL, NULL, NULL, '$2y$12$IBUzLCbl9MI9tKLankKmyewGZp8763GhX2D5FBF3Xc5/PnkxljSa6', NULL, NULL, NULL, NULL, 'staff', 1, NULL, NULL, NULL, 'Active', 1, 0, 0, 1, 1, NULL, '2026-01-11 20:49:51', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(36, 0, 'NO-ACCESS', NULL, 'Karyawan Tanpa Akses', NULL, NULL, NULL, NULL, 'noaccess@amarin.com', NULL, NULL, NULL, NULL, '$2y$12$IBUzLCbl9MI9tKLankKmyewGZp8763GhX2D5FBF3Xc5/PnkxljSa6', NULL, NULL, NULL, NULL, 'staff', 1, NULL, NULL, NULL, 'Active', 1, 0, 0, 0, 1, NULL, '2026-01-11 20:49:51', '2026-01-26 10:03:21', NULL, 0, NULL, NULL),
	(37, 0, 'EMP-3534', NULL, 'test', NULL, NULL, NULL, NULL, 'test@company.com', NULL, NULL, NULL, NULL, '$2y$12$rhyaGGy6lgTGXMepduwDn.6b/5qw.9iW1HRqiHPEQJAwpbJ4DgdHG', NULL, NULL, NULL, NULL, 'staff', 1, 1, 1, NULL, 'Active', 1, 0, 0, 0, 1, NULL, '2026-01-12 20:10:28', '2026-01-26 10:03:21', NULL, 0, NULL, NULL);

-- Dumping structure for table db_master_amarin_original.tbl_position
CREATE TABLE IF NOT EXISTS `tbl_position` (
  `position_id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `position_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`position_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `tbl_position_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_master_amarin_original.tbl_position: ~28 rows (approximately)
INSERT INTO `tbl_position` (`position_id`, `company_id`, `position_name`, `description`, `created_at`, `updated_at`, `is_deleted`) VALUES
	(1, 1, 'IT Staff', NULL, '2025-12-15 02:33:15', '2025-12-15 02:33:15', 0),
	(2, 1, 'CFO', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(3, 1, 'Managing Director', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(4, 1, 'Deputy Managing Director', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(5, 1, 'Admin Operation', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(6, 1, 'Finance Supervisor', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(7, 1, 'Assistant Technical Superintendent', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(8, 1, 'Marine Gas Superintendent', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(9, 1, 'Vessel Accounting', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(10, 1, 'HSSEQ Officer', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(11, 1, 'Crewing Officer', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(12, 1, 'DPA', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(13, 1, 'Marine Oil Superintendent', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(14, 1, 'Finance Staff', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(15, 1, 'Technical Assistant', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(16, 1, 'Office Boy', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(17, 1, 'Cadet', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(18, 1, 'Assistant Ops', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(19, 1, 'Assist. Manager HRGA', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(20, 1, 'HRGA Manager', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(21, 1, 'HRBP Manager', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(22, 1, 'IT Manager', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(23, 1, 'ERP Specialist', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(24, 1, 'Crewing Manager', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(25, 1, 'Assist. Manager Environment & Quality Assurance', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(26, 1, 'IT Support Staff', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(27, 1, 'Technical Superintendent', NULL, '2025-12-22 10:22:58', '2025-12-22 10:22:58', 0),
	(28, 1, 'Super Admin', NULL, '2026-01-05 08:49:48', '2026-01-05 08:49:48', 0),
	(29, 1, 'HR Staff', NULL, '2026-01-05 11:03:18', '2026-01-05 11:03:18', 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
