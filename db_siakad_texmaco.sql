-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: Jan 25, 2026 at 01:07 PM
-- Server version: 11.4.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40927436_db_siakad_texmaco`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `absensi_id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_validasi` enum('Pending','Valid','Rejected') DEFAULT 'Pending',
  `alasan_penolakan` text DEFAULT NULL,
  `catatan_harian` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`absensi_id`, `jadwal_id`, `tanggal`, `status_validasi`, `alasan_penolakan`, `catatan_harian`) VALUES
(1, 1, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(2, 2, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(3, 3, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(4, 4, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(5, 5, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(6, 6, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(7, 7, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(8, 8, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(9, 9, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(10, 10, '2026-01-12', 'Valid', NULL, 'Pembahasan Materi Bab 1: Pengenalan Dasar'),
(16, 11, '2026-01-13', 'Valid', NULL, 'Praktikum di Lab Komputer / Bengkel'),
(17, 12, '2026-01-13', 'Valid', NULL, 'Praktikum di Lab Komputer / Bengkel'),
(18, 13, '2026-01-13', 'Valid', NULL, 'Praktikum di Lab Komputer / Bengkel'),
(19, 14, '2026-01-13', 'Valid', NULL, 'Praktikum di Lab Komputer / Bengkel'),
(20, 15, '2026-01-13', 'Valid', NULL, 'Praktikum di Lab Komputer / Bengkel'),
(21, 16, '2026-01-13', 'Valid', NULL, 'Praktikum di Lab Komputer / Bengkel'),
(23, 17, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(24, 18, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(25, 19, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(26, 20, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(27, 21, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(28, 22, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(29, 23, '2026-01-14', 'Valid', NULL, 'Latihan Soal dan Diskusi Kelompok'),
(31, 25, '2026-01-15', 'Pending', NULL, 'Presentasi Project Siswa'),
(32, 26, '2026-01-15', 'Valid', NULL, 'Presentasi Project Siswa'),
(33, 27, '2026-01-15', 'Pending', NULL, 'Presentasi Project Siswa'),
(34, 28, '2026-01-15', 'Pending', NULL, 'Presentasi Project Siswa'),
(35, 7, '2026-01-19', 'Pending', NULL, 'Hhhh'),
(37, 35, '2026-01-22', 'Valid', NULL, 'pengantar basis data'),
(38, 26, '2026-01-22', 'Rejected', 'Mada', 'Pengantar');

-- --------------------------------------------------------

--
-- Table structure for table `bobot_nilai`
--

CREATE TABLE `bobot_nilai` (
  `bobot_id` int(11) NOT NULL,
  `persen_tugas` int(11) NOT NULL,
  `persen_uts` int(11) NOT NULL,
  `persen_uas` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bobot_nilai`
--

INSERT INTO `bobot_nilai` (`bobot_id`, `persen_tugas`, `persen_uts`, `persen_uas`, `created_at`) VALUES
(1, 40, 30, 30, '2026-01-17 05:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `catatan_rapor`
--

CREATE TABLE `catatan_rapor` (
  `catatan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `guru_wali_id` int(11) NOT NULL,
  `catatan_sikap` text DEFAULT NULL,
  `catatan_akademik` text DEFAULT NULL,
  `tgl_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `catatan_rapor`
--

INSERT INTO `catatan_rapor` (`catatan_id`, `siswa_id`, `tahun_id`, `guru_wali_id`, `catatan_sikap`, `catatan_akademik`, `tgl_input`) VALUES
(61, 21, 2, 5, 'AAS', 'SADASDAS', '2026-01-22'),
(62, 61, 2, 12, 'ok', 'ok', '2026-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `detail_absensi`
--

CREATE TABLE `detail_absensi` (
  `detail_id` int(11) NOT NULL,
  `absensi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status_kehadiran` enum('Hadir','Sakit','Izin','Alpa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `detail_absensi`
--

INSERT INTO `detail_absensi` (`detail_id`, `absensi_id`, `siswa_id`, `status_kehadiran`) VALUES
(1, 1, 1, 'Sakit'),
(2, 1, 2, 'Hadir'),
(3, 1, 3, 'Hadir'),
(4, 1, 4, 'Hadir'),
(5, 1, 5, 'Hadir'),
(6, 2, 1, 'Hadir'),
(7, 2, 2, 'Hadir'),
(8, 2, 3, 'Hadir'),
(9, 2, 4, 'Hadir'),
(10, 2, 5, 'Hadir'),
(11, 3, 1, 'Hadir'),
(12, 3, 2, 'Hadir'),
(13, 3, 3, 'Hadir'),
(14, 3, 4, 'Hadir'),
(15, 3, 5, 'Hadir'),
(16, 4, 11, 'Hadir'),
(17, 4, 12, 'Sakit'),
(18, 4, 13, 'Sakit'),
(19, 4, 14, 'Hadir'),
(20, 4, 15, 'Sakit'),
(21, 5, 11, 'Sakit'),
(22, 5, 12, 'Hadir'),
(23, 5, 13, 'Hadir'),
(24, 5, 14, 'Hadir'),
(25, 5, 15, 'Hadir'),
(26, 6, 11, 'Hadir'),
(27, 6, 12, 'Hadir'),
(28, 6, 13, 'Sakit'),
(29, 6, 14, 'Hadir'),
(30, 6, 15, 'Hadir'),
(31, 7, 21, 'Hadir'),
(32, 7, 22, 'Hadir'),
(33, 7, 23, 'Hadir'),
(34, 7, 24, 'Hadir'),
(35, 7, 25, 'Hadir'),
(36, 8, 21, 'Hadir'),
(37, 8, 22, 'Hadir'),
(38, 8, 23, 'Hadir'),
(39, 8, 24, 'Hadir'),
(40, 8, 25, 'Hadir'),
(41, 9, 51, 'Hadir'),
(42, 9, 52, 'Hadir'),
(43, 9, 53, 'Hadir'),
(44, 9, 54, 'Hadir'),
(45, 9, 55, 'Hadir'),
(46, 10, 51, 'Hadir'),
(47, 10, 52, 'Hadir'),
(48, 10, 53, 'Hadir'),
(49, 10, 54, 'Hadir'),
(50, 10, 55, 'Hadir'),
(51, 16, 1, 'Hadir'),
(52, 16, 2, 'Hadir'),
(53, 16, 3, 'Hadir'),
(54, 16, 4, 'Hadir'),
(55, 16, 5, 'Hadir'),
(56, 17, 6, 'Sakit'),
(57, 17, 7, 'Hadir'),
(58, 17, 8, 'Hadir'),
(59, 17, 9, 'Hadir'),
(60, 17, 10, 'Hadir'),
(61, 18, 21, 'Hadir'),
(62, 18, 22, 'Hadir'),
(63, 18, 23, 'Hadir'),
(64, 18, 24, 'Sakit'),
(65, 18, 25, 'Hadir'),
(66, 19, 11, 'Hadir'),
(67, 19, 12, 'Sakit'),
(68, 19, 13, 'Hadir'),
(69, 19, 14, 'Hadir'),
(70, 19, 15, 'Hadir'),
(71, 20, 16, 'Hadir'),
(72, 20, 17, 'Hadir'),
(73, 20, 18, 'Sakit'),
(74, 20, 19, 'Hadir'),
(75, 20, 20, 'Hadir'),
(76, 21, 31, 'Hadir'),
(77, 21, 32, 'Hadir'),
(78, 21, 33, 'Hadir'),
(79, 21, 34, 'Sakit'),
(80, 21, 35, 'Sakit'),
(81, 23, 1, 'Hadir'),
(82, 23, 2, 'Hadir'),
(83, 23, 3, 'Hadir'),
(84, 23, 4, 'Hadir'),
(85, 23, 5, 'Hadir'),
(86, 24, 6, 'Hadir'),
(87, 24, 7, 'Hadir'),
(88, 24, 8, 'Hadir'),
(89, 24, 9, 'Hadir'),
(90, 24, 10, 'Hadir'),
(91, 25, 11, 'Sakit'),
(92, 25, 12, 'Hadir'),
(93, 25, 13, 'Hadir'),
(94, 25, 14, 'Hadir'),
(95, 25, 15, 'Hadir'),
(96, 26, 11, 'Hadir'),
(97, 26, 12, 'Hadir'),
(98, 26, 13, 'Sakit'),
(99, 26, 14, 'Hadir'),
(100, 26, 15, 'Hadir'),
(101, 27, 41, 'Hadir'),
(102, 27, 42, 'Hadir'),
(103, 27, 43, 'Hadir'),
(104, 27, 44, 'Hadir'),
(105, 27, 45, 'Hadir'),
(106, 28, 41, 'Hadir'),
(107, 28, 42, 'Sakit'),
(108, 28, 43, 'Hadir'),
(109, 28, 44, 'Hadir'),
(110, 28, 45, 'Hadir'),
(111, 29, 41, 'Hadir'),
(112, 29, 42, 'Hadir'),
(113, 29, 43, 'Hadir'),
(114, 29, 44, 'Hadir'),
(115, 29, 45, 'Hadir'),
(121, 31, 1, 'Hadir'),
(122, 31, 2, 'Hadir'),
(123, 31, 3, 'Hadir'),
(124, 31, 4, 'Sakit'),
(125, 31, 5, 'Hadir'),
(126, 32, 21, 'Hadir'),
(127, 32, 22, 'Hadir'),
(128, 32, 23, 'Hadir'),
(129, 32, 24, 'Hadir'),
(130, 32, 25, 'Hadir'),
(131, 33, 31, 'Hadir'),
(132, 33, 32, 'Hadir'),
(133, 33, 33, 'Hadir'),
(134, 33, 34, 'Hadir'),
(135, 33, 35, 'Hadir'),
(136, 34, 41, 'Hadir'),
(137, 34, 42, 'Hadir'),
(138, 34, 43, 'Sakit'),
(139, 34, 44, 'Hadir'),
(140, 34, 45, 'Hadir'),
(141, 35, 21, 'Sakit'),
(142, 35, 22, 'Alpa'),
(143, 35, 23, 'Alpa'),
(144, 35, 24, 'Hadir'),
(145, 35, 25, 'Hadir'),
(156, 37, 61, 'Hadir'),
(157, 38, 21, 'Sakit'),
(158, 38, 22, 'Izin'),
(159, 38, 23, 'Hadir'),
(160, 38, 24, 'Hadir'),
(161, 38, 25, 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `detail_nilai`
--

CREATE TABLE `detail_nilai` (
  `detail_id` int(11) NOT NULL,
  `nilai_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT 0.00,
  `nilai_uts` decimal(5,2) DEFAULT 0.00,
  `nilai_uas` decimal(5,2) DEFAULT 0.00,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00 COMMENT '(Tugas*30%)+(UTS*30%)+(UAS*40%)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_nilai`
--

INSERT INTO `detail_nilai` (`detail_id`, `nilai_id`, `siswa_id`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`) VALUES
(1, 1, 1, '82.00', '98.00', '97.00', '91.30'),
(2, 1, 2, '94.00', '78.00', '83.00', '85.90'),
(3, 1, 3, '80.00', '80.00', '84.00', '81.20'),
(4, 1, 4, '81.00', '78.00', '98.00', '85.20'),
(5, 1, 5, '84.00', '77.00', '81.00', '81.00'),
(6, 2, 1, '75.00', '83.00', '91.00', '82.20'),
(7, 2, 2, '85.00', '77.00', '78.00', '80.50'),
(8, 2, 3, '86.00', '95.00', '98.00', '92.30'),
(9, 2, 4, '81.00', '83.00', '98.00', '86.70'),
(10, 2, 5, '94.00', '77.00', '75.00', '83.20'),
(11, 3, 1, '97.00', '87.00', '95.00', '93.40'),
(12, 3, 2, '92.00', '75.00', '95.00', '87.80'),
(13, 3, 3, '82.00', '97.00', '95.00', '90.40'),
(14, 3, 4, '84.00', '84.00', '94.00', '87.00'),
(15, 3, 5, '94.00', '89.00', '89.00', '91.00'),
(16, 11, 1, '81.00', '85.00', '85.00', '83.40'),
(17, 11, 2, '97.00', '83.00', '96.00', '92.50'),
(18, 11, 3, '87.00', '94.00', '86.00', '88.80'),
(19, 11, 4, '98.00', '88.00', '93.00', '93.50'),
(20, 11, 5, '81.00', '97.00', '97.00', '90.60'),
(21, 17, 1, '94.00', '82.00', '77.00', '85.30'),
(22, 17, 2, '91.00', '98.00', '95.00', '94.30'),
(23, 17, 3, '81.00', '96.00', '92.00', '88.80'),
(24, 17, 4, '94.00', '75.00', '88.00', '86.50'),
(25, 17, 5, '95.00', '88.00', '82.00', '89.00'),
(26, 24, 1, '93.00', '75.00', '93.00', '87.60'),
(27, 24, 2, '94.00', '90.00', '95.00', '93.10'),
(28, 24, 3, '81.00', '96.00', '89.00', '87.90'),
(29, 24, 4, '82.00', '94.00', '76.00', '83.80'),
(30, 24, 5, '97.00', '85.00', '85.00', '89.80'),
(31, 25, 1, '94.00', '92.00', '81.00', '89.50'),
(32, 25, 2, '79.00', '78.00', '76.00', '77.80'),
(33, 25, 3, '75.00', '93.00', '96.00', '86.70'),
(34, 25, 4, '77.00', '96.00', '79.00', '83.30'),
(35, 25, 5, '79.00', '82.00', '77.00', '79.30'),
(36, 29, 1, '88.00', '84.00', '85.00', '85.90'),
(37, 29, 2, '97.00', '83.00', '75.00', '86.20'),
(38, 29, 3, '75.00', '97.00', '92.00', '86.70'),
(39, 29, 4, '95.00', '78.00', '76.00', '84.20'),
(40, 29, 5, '94.00', '98.00', '87.00', '93.10'),
(41, 30, 1, '87.00', '78.00', '76.00', '81.00'),
(42, 30, 2, '97.00', '84.00', '81.00', '88.30'),
(43, 30, 3, '79.00', '75.00', '90.00', '81.10'),
(44, 30, 4, '79.00', '97.00', '77.00', '83.80'),
(45, 30, 5, '93.00', '87.00', '83.00', '88.20'),
(46, 31, 1, '77.00', '87.00', '79.00', '80.60'),
(47, 31, 2, '85.00', '91.00', '75.00', '83.80'),
(48, 31, 3, '78.00', '90.00', '93.00', '86.10'),
(49, 31, 4, '98.00', '87.00', '92.00', '92.90'),
(50, 31, 5, '77.00', '81.00', '76.00', '77.90'),
(51, 12, 6, '86.00', '82.00', '75.00', '81.50'),
(52, 12, 7, '81.00', '79.00', '79.00', '79.80'),
(53, 12, 8, '85.00', '87.00', '82.00', '84.70'),
(54, 12, 9, '75.00', '80.00', '76.00', '76.80'),
(55, 12, 10, '89.00', '95.00', '86.00', '89.90'),
(56, 18, 6, '92.00', '81.00', '77.00', '84.20'),
(57, 18, 7, '91.00', '78.00', '92.00', '87.40'),
(58, 18, 8, '78.00', '89.00', '89.00', '84.60'),
(59, 18, 9, '78.00', '96.00', '75.00', '82.50'),
(60, 18, 10, '84.00', '75.00', '97.00', '85.20'),
(61, 4, 11, '86.00', '91.00', '75.00', '84.20'),
(62, 4, 12, '75.00', '77.00', '82.00', '77.70'),
(63, 4, 13, '83.00', '94.00', '98.00', '90.80'),
(64, 4, 14, '86.00', '86.00', '98.00', '89.60'),
(65, 4, 15, '86.00', '83.00', '85.00', '84.80'),
(66, 5, 11, '77.00', '80.00', '92.00', '82.40'),
(67, 5, 12, '76.00', '78.00', '86.00', '79.60'),
(68, 5, 13, '97.00', '81.00', '87.00', '89.20'),
(69, 5, 14, '96.00', '94.00', '85.00', '92.10'),
(70, 5, 15, '92.00', '82.00', '82.00', '86.00'),
(71, 6, 11, '92.00', '93.00', '87.00', '90.80'),
(72, 6, 12, '82.00', '77.00', '88.00', '82.30'),
(73, 6, 13, '86.00', '90.00', '95.00', '89.90'),
(74, 6, 14, '80.00', '91.00', '93.00', '87.20'),
(75, 6, 15, '95.00', '95.00', '92.00', '94.10'),
(76, 14, 11, '77.00', '82.00', '80.00', '79.40'),
(77, 14, 12, '79.00', '83.00', '78.00', '79.90'),
(78, 14, 13, '91.00', '98.00', '94.00', '94.00'),
(79, 14, 14, '80.00', '88.00', '80.00', '82.40'),
(80, 14, 15, '83.00', '77.00', '86.00', '82.10'),
(81, 19, 11, '76.00', '96.00', '81.00', '83.50'),
(82, 19, 12, '89.00', '83.00', '95.00', '89.00'),
(83, 19, 13, '81.00', '92.00', '96.00', '88.80'),
(84, 19, 14, '80.00', '88.00', '75.00', '80.90'),
(85, 19, 15, '86.00', '83.00', '81.00', '83.60'),
(86, 20, 11, '81.00', '90.00', '85.00', '84.90'),
(87, 20, 12, '78.00', '85.00', '91.00', '84.00'),
(88, 20, 13, '79.00', '95.00', '93.00', '88.00'),
(89, 20, 14, '80.00', '95.00', '90.00', '87.50'),
(90, 20, 15, '88.00', '95.00', '90.00', '90.70'),
(91, 15, 16, '91.00', '86.00', '85.00', '87.70'),
(92, 15, 17, '89.00', '93.00', '98.00', '92.90'),
(93, 15, 18, '89.00', '75.00', '84.00', '83.30'),
(94, 15, 19, '98.00', '88.00', '98.00', '95.00'),
(95, 15, 20, '77.00', '88.00', '87.00', '83.30'),
(131, 16, 31, '78.00', '81.00', '75.00', '78.00'),
(132, 16, 32, '79.00', '95.00', '94.00', '88.30'),
(133, 16, 33, '85.00', '94.00', '90.00', '89.20'),
(134, 16, 34, '96.00', '87.00', '94.00', '92.70'),
(135, 16, 35, '89.00', '87.00', '93.00', '89.60'),
(136, 27, 31, '82.00', '81.00', '84.00', '82.30'),
(137, 27, 32, '79.00', '94.00', '87.00', '85.90'),
(138, 27, 33, '77.00', '97.00', '82.00', '84.50'),
(139, 27, 34, '94.00', '76.00', '98.00', '89.80'),
(140, 27, 35, '92.00', '91.00', '81.00', '88.40'),
(141, 21, 41, '82.00', '94.00', '75.00', '83.50'),
(142, 21, 42, '89.00', '76.00', '85.00', '83.90'),
(143, 21, 43, '77.00', '78.00', '83.00', '79.10'),
(144, 21, 44, '83.00', '93.00', '94.00', '89.30'),
(145, 21, 45, '94.00', '86.00', '75.00', '85.90'),
(146, 22, 41, '89.00', '75.00', '80.00', '82.10'),
(147, 22, 42, '78.00', '75.00', '92.00', '81.30'),
(148, 22, 43, '89.00', '95.00', '85.00', '89.60'),
(149, 22, 44, '91.00', '77.00', '84.00', '84.70'),
(150, 22, 45, '89.00', '96.00', '90.00', '91.40'),
(151, 23, 41, '89.00', '75.00', '82.00', '82.70'),
(152, 23, 42, '88.00', '97.00', '95.00', '92.80'),
(153, 23, 43, '86.00', '94.00', '91.00', '89.90'),
(154, 23, 44, '98.00', '95.00', '83.00', '92.60'),
(155, 23, 45, '78.00', '90.00', '96.00', '87.00'),
(156, 28, 41, '86.00', '92.00', '81.00', '86.30'),
(157, 28, 42, '78.00', '96.00', '77.00', '83.10'),
(158, 28, 43, '94.00', '92.00', '81.00', '89.50'),
(159, 28, 44, '77.00', '90.00', '75.00', '80.30'),
(160, 28, 45, '76.00', '84.00', '90.00', '82.60'),
(161, 9, 51, '78.00', '92.00', '81.00', '83.10'),
(162, 9, 52, '78.00', '97.00', '79.00', '84.00'),
(163, 9, 53, '76.00', '95.00', '77.00', '82.00'),
(164, 9, 54, '98.00', '88.00', '96.00', '94.40'),
(165, 9, 55, '93.00', '77.00', '82.00', '84.90'),
(166, 10, 51, '80.00', '80.00', '86.00', '81.80'),
(167, 10, 52, '92.00', '79.00', '91.00', '87.80'),
(168, 10, 53, '97.00', '88.00', '98.00', '94.60'),
(169, 10, 54, '80.00', '80.00', '84.00', '81.20'),
(170, 10, 55, '81.00', '78.00', '96.00', '84.60'),
(186, 37, 61, '70.00', '80.00', '87.00', '79.80'),
(187, 38, 21, '100.00', '50.00', '100.00', '85.00'),
(188, 38, 22, '100.00', '0.00', '0.00', '30.00'),
(189, 38, 23, '0.00', '0.00', '0.00', '0.00'),
(190, 38, 24, '0.00', '0.00', '0.00', '0.00'),
(191, 38, 25, '0.00', '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`guru_id`, `user_id`, `nip`, `nama_lengkap`) VALUES
(1, 10, '198501012010011001', 'Sandi Pratama, S.Kom'),
(2, 14, '199002022015022002', 'Siska Wulandari, S.T'),
(3, 12, '198203032009031003', 'Joko Susilo, S.T'),
(4, 15, '199104042016042004', 'Tina Melati, S.Pd.T'),
(5, 11, '198805052012052005', 'Rina Agustina, S.Pd'),
(6, 13, '197506062000061006', 'Drs. Ahmad Dahlan'),
(7, 16, '198907072013071007', 'Budi Santoso, S.Or'),
(8, 17, '199208082017082008', 'Sari Indah, S.Pd'),
(9, 18, '198709092011091009', 'Dedi Kurniawan, S.Si'),
(10, 19, '199310102018101010', 'Andi Wijaya, S.S'),
(11, 2, '197008171995011001', 'Drs. H. Budi Santoso, M.Pd'),
(12, 164, '20240101', 'halimil'),
(13, 165, '123456', 'guru baru');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `jadwal_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`jadwal_id`, `tahun_id`, `kelas_id`, `mapel_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(1, 2, 1, 4, 5, 'Senin', '07:00:00', '09:30:00'),
(2, 2, 1, 1, 6, 'Senin', '09:45:00', '11:45:00'),
(3, 2, 1, 3, 10, 'Senin', '13:30:00', '15:30:00'),
(4, 2, 3, 4, 5, 'Senin', '07:00:00', '09:30:00'),
(5, 2, 3, 1, 6, 'Senin', '09:45:00', '11:45:00'),
(6, 2, 3, 3, 10, 'Senin', '13:30:00', '15:30:00'),
(7, 2, 5, 18, 1, 'Senin', '07:00:00', '11:45:00'),
(8, 2, 5, 2, 8, 'Senin', '13:30:00', '15:30:00'),
(9, 2, 11, 23, 3, 'Senin', '07:00:00', '11:45:00'),
(10, 2, 11, 2, 8, 'Senin', '13:30:00', '15:30:00'),
(11, 2, 1, 14, 1, 'Selasa', '07:00:00', '11:45:00'),
(12, 2, 2, 14, 2, 'Selasa', '07:00:00', '11:45:00'),
(13, 2, 5, 17, 2, 'Selasa', '07:00:00', '15:30:00'),
(14, 2, 3, 19, 3, 'Selasa', '07:00:00', '11:45:00'),
(15, 2, 4, 19, 4, 'Selasa', '07:00:00', '11:45:00'),
(16, 2, 7, 21, 4, 'Selasa', '07:00:00', '15:30:00'),
(17, 2, 1, 8, 7, 'Rabu', '07:00:00', '09:30:00'),
(18, 2, 2, 8, 7, 'Rabu', '09:45:00', '11:45:00'),
(19, 2, 3, 10, 9, 'Rabu', '07:00:00', '09:30:00'),
(20, 2, 3, 11, 9, 'Rabu', '09:45:00', '11:45:00'),
(21, 2, 9, 6, 10, 'Rabu', '07:00:00', '09:30:00'),
(22, 2, 9, 4, 5, 'Rabu', '09:45:00', '11:45:00'),
(23, 2, 9, 3, 10, 'Rabu', '13:30:00', '15:30:00'),
(24, 2, 1, 15, 2, 'Kamis', '07:00:00', '09:30:00'),
(25, 2, 1, 5, 8, 'Kamis', '09:45:00', '11:45:00'),
(26, 2, 5, 16, 1, 'Kamis', '07:00:00', '11:45:00'),
(27, 2, 7, 22, 3, 'Kamis', '07:00:00', '11:45:00'),
(28, 2, 9, 17, 2, 'Kamis', '07:00:00', '15:30:00'),
(29, 2, 1, 7, 10, 'Jumat', '07:00:00', '09:30:00'),
(30, 2, 1, 2, 8, 'Jumat', '09:45:00', '11:45:00'),
(31, 2, 1, 12, 1, 'Jumat', '13:30:00', '15:30:00'),
(32, 2, 5, 1, 6, 'Jumat', '07:00:00', '09:30:00'),
(33, 2, 5, 6, 10, 'Jumat', '09:45:00', '11:45:00'),
(34, 2, 5, 15, 2, 'Jumat', '13:30:00', '15:30:00'),
(35, 2, 13, 18, 12, 'Kamis', '16:18:00', '20:18:00'),
(36, 2, 13, 18, 13, 'Kamis', '01:00:00', '03:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `guru_wali_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL COMMENT 'Contoh: 2023/2024 Ganjil',
  `nama_kelas` varchar(50) NOT NULL COMMENT 'Contoh: X-IPA-1',
  `tingkat` varchar(10) NOT NULL COMMENT 'Contoh: 10, 11, 12',
  `jurusan` varchar(50) NOT NULL COMMENT 'Contoh: IPA, IPS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `guru_wali_id`, `tahun_id`, `nama_kelas`, `tingkat`, `jurusan`) VALUES
(1, 1, 2, 'X-RPL-1', '10', 'RPL'),
(2, 2, 2, 'X-RPL-2', '10', 'RPL'),
(3, 3, 2, 'X-TEI-1', '10', 'TEI'),
(4, 4, 2, 'X-TEI-2', '10', 'TEI'),
(5, 5, 2, 'XI-RPL-1', '11', 'RPL'),
(6, 6, 2, 'XI-RPL-2', '11', 'RPL'),
(7, 7, 2, 'XI-TEI-1', '11', 'TEI'),
(8, 8, 2, 'XI-TEI-2', '11', 'TEI'),
(9, 9, 2, 'XII-RPL-1', '12', 'RPL'),
(10, 10, 2, 'XII-RPL-2', '12', 'RPL'),
(11, 1, 2, 'XII-TEI-1', '12', 'TEI'),
(12, 2, 2, 'XII-TEI-2', '12', 'TEI'),
(13, 12, 2, 'TATA BOGA - 1 ', '12', 'TATA BOGA');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `mapel_id` int(11) NOT NULL,
  `kode_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kelompok` enum('A','B','C1','C2','C3') NOT NULL COMMENT 'A:Nasional, B:Wilayah, C:Kejuruan',
  `kkm` int(11) NOT NULL DEFAULT 75
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`mapel_id`, `kode_mapel`, `nama_mapel`, `kelompok`, `kkm`) VALUES
(1, 'AGM', 'Pendidikan Agama dan Budi Pekerti', 'A', 75),
(2, 'PKN', 'Pendidikan Pancasila dan Kewarganegaraan', 'A', 75),
(3, 'IND', 'Bahasa Indonesia', 'A', 75),
(4, 'MTK', 'Matematika', 'A', 75),
(5, 'SEJ', 'Sejarah Indonesia', 'A', 75),
(6, 'ING', 'Bahasa Inggris', 'A', 75),
(7, 'SBD', 'Seni Budaya', 'B', 75),
(8, 'PJK', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'B', 78),
(9, 'SIM', 'Simulasi dan Komunikasi Digital', 'C1', 75),
(10, 'FIS', 'Fisika Terapan', 'C1', 70),
(11, 'KIM', 'Kimia Kejuruan', 'C1', 70),
(12, 'SKD', 'Sistem Komputer (RPL)', 'C2', 75),
(13, 'KJD', 'Komputer dan Jaringan Dasar (RPL)', 'C2', 75),
(14, 'PRO', 'Pemrograman Dasar (RPL)', 'C2', 78),
(15, 'DDG', 'Dasar Desain Grafis (RPL)', 'C2', 78),
(16, 'PBO', 'Pemrograman Berorientasi Objek (RPL)', 'C3', 80),
(17, 'WEB', 'Pemrograman Web dan Perangkat Bergerak (RPL)', 'C3', 80),
(18, 'BAS', 'Basis Data (RPL)', 'C3', 80),
(19, 'KBK', 'Kerja Bengkel dan Gambar Teknik (TEI)', 'C2', 75),
(20, 'DLE', 'Dasar Listrik dan Elektronika (TEI)', 'C2', 75),
(21, 'MPR', 'Teknik Pemrograman, Mikroprosesor (TEI)', 'C2', 78),
(22, 'PRE', 'Penerapan Rangkaian Elektronika (TEI)', 'C3', 80),
(23, 'PSK', 'Perekayasaan Sistem Kontrol (TEI)', 'C3', 80);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `nilai_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `status_validasi` enum('Draft','Submitted','Final') DEFAULT 'Draft',
  `catatan_revisi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`nilai_id`, `tahun_id`, `kelas_id`, `mapel_id`, `guru_id`, `status_validasi`, `catatan_revisi`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 4, 5, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(2, 2, 1, 1, 6, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(3, 2, 1, 3, 10, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(4, 2, 3, 4, 5, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(5, 2, 3, 1, 6, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(6, 2, 3, 3, 10, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(9, 2, 11, 23, 3, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(10, 2, 11, 2, 8, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(11, 2, 1, 14, 1, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(12, 2, 2, 14, 2, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(14, 2, 3, 19, 3, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(15, 2, 4, 19, 4, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(16, 2, 7, 21, 4, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(17, 2, 1, 8, 7, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(18, 2, 2, 8, 7, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(19, 2, 3, 10, 9, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(20, 2, 3, 11, 9, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(21, 2, 9, 6, 10, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(22, 2, 9, 4, 5, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(23, 2, 9, 3, 10, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(24, 2, 1, 15, 2, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(25, 2, 1, 5, 8, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(27, 2, 7, 22, 3, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(28, 2, 9, 17, 2, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(29, 2, 1, 7, 10, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(30, 2, 1, 2, 8, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(31, 2, 1, 12, 1, 'Final', NULL, '2026-01-17 13:56:34', '2026-01-17 13:56:34'),
(37, 2, 13, 18, 12, 'Final', NULL, '2026-01-22 12:24:13', '2026-01-22 12:24:30'),
(38, 2, 5, 16, 1, 'Draft', NULL, '2026-01-22 12:26:13', '2026-01-22 12:26:13');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_cetak`
--

CREATE TABLE `riwayat_cetak` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tgl_cetak` datetime DEFAULT current_timestamp(),
  `jenis_dokumen` enum('Rapor','RekapAbsen') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `riwayat_cetak`
--

INSERT INTO `riwayat_cetak` (`log_id`, `user_id`, `siswa_id`, `tgl_cetak`, `jenis_dokumen`) VALUES
(23, 11, 21, '2026-01-21 22:45:10', 'Rapor'),
(24, 11, 21, '2026-01-22 01:28:19', 'Rapor'),
(25, 11, 21, '2026-01-22 01:45:14', 'Rapor'),
(26, 11, 21, '2026-01-22 01:45:22', 'Rapor'),
(27, 164, 61, '2026-01-22 04:24:59', 'Rapor'),
(28, 164, 61, '2026-01-22 04:33:36', 'Rapor');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`siswa_id`, `user_id`, `kelas_id`, `nis`, `nisn`, `nama_lengkap`, `tanggal_lahir`, `alamat`) VALUES
(1, 101, 1, '24001', '0081234001', 'Aditya Pratama', '2008-01-01', 'Jl. Merdeka No. 1'),
(2, 102, 1, '24002', '0081234002', 'Bayu Saputra', '2008-02-05', 'Jl. Sudirman No. 10'),
(3, 103, 1, '24003', '0081234003', 'Citra Lestari', '2008-03-10', 'Jl. A. Yani No. 5'),
(4, 104, 1, '24004', '0081234004', 'Dewi Sartika', '2008-04-15', 'Jl. Gatot Subroto No. 3'),
(5, 105, 2, '24005', '0081234005', 'Eko Purnomo', '2008-05-20', 'Jl. Diponegoro No. 8'),
(6, 106, 2, '24006', '0081234006', 'Fajar Nugraha', '2008-06-01', 'Jl. Imam Bonjol No. 2'),
(7, 107, 2, '24007', '0081234007', 'Gita Gutawa', '2008-07-05', 'Jl. Pemuda No. 12'),
(8, 108, 2, '24008', '0081234008', 'Hadi Wijaya', '2008-08-10', 'Jl. Pahlawan No. 4'),
(9, 109, 2, '24009', '0081234009', 'Indah Permata', '2008-09-15', 'Jl. Kartini No. 6'),
(10, 110, 1, '24010', '0081234010', 'Joko Anwar', '2008-10-20', 'Jl. Melati No. 9'),
(11, 111, 3, '24011', '0081234011', 'Kevin Sanjaya', '2008-11-01', 'Jl. Mawar No. 1'),
(12, 112, 3, '24012', '0081234012', 'Lesti Kejora', '2008-12-05', 'Jl. Anggrek No. 2'),
(13, 113, 3, '24013', '0081234013', 'Maman Suherman', '2009-01-10', 'Jl. Kamboja No. 3'),
(14, 114, 3, '24014', '0081234014', 'Nina Zatulini', '2009-02-15', 'Jl. Kenanga No. 4'),
(15, 115, 3, '24015', '0081234015', 'Opick Tomboati', '2009-03-20', 'Jl. Flamboyan No. 5'),
(16, 116, 4, '24016', '0081234016', 'Putri Titian', '2009-04-01', 'Jl. Cempaka No. 6'),
(17, 117, 4, '24017', '0081234017', 'Qory Sandioriva', '2009-05-05', 'Jl. Dahlia No. 7'),
(18, 118, 4, '24018', '0081234018', 'Raffi Ahmad', '2009-06-10', 'Jl. Teratai No. 8'),
(19, 119, 4, '24019', '0081234019', 'Sule Prikitiw', '2009-07-15', 'Jl. Seroja No. 9'),
(20, 120, 4, '24020', '0081234020', 'Tukul Arwana', '2009-08-20', 'Jl. Bougenville No. 10'),
(21, 121, 5, '23001', '0071234001', 'Ucok Baba', '2007-01-01', 'Jl. Mangga No. 1'),
(22, 122, 5, '23002', '0071234002', 'Vicky Prasetyo', '2007-02-05', 'Jl. Jeruk No. 2'),
(23, 123, 5, '23003', '0071234003', 'Wulan Guritno', '2007-03-10', 'Jl. Nanas No. 3'),
(24, 124, 5, '23004', '0071234004', 'Xavier Hernandes', '2007-04-15', 'Jl. Durian No. 4'),
(25, 125, 5, '23005', '0071234005', 'Yuni Shara', '2007-05-20', 'Jl. Apel No. 5'),
(26, 126, 6, '23006', '0071234006', 'Zaskia Gotik', '2007-06-01', 'Jl. Anggur No. 6'),
(27, 127, 6, '23007', '0071234007', 'Andre Taulany', '2007-07-05', 'Jl. Melon No. 7'),
(28, 128, 6, '23008', '0071234008', 'Baim Wong', '2007-08-10', 'Jl. Semangka No. 8'),
(29, 129, 6, '23009', '0071234009', 'Cinta Laura', '2007-09-15', 'Jl. Salak No. 9'),
(30, 130, 6, '23010', '0071234010', 'Desta Mahendra', '2007-10-20', 'Jl. Rambutan No. 10'),
(31, 131, 7, '23011', '0071234011', 'Enzy Storia', '2007-11-01', 'Jl. Pisang No. 11'),
(32, 132, 7, '23012', '0071234012', 'Ferry Maryadi', '2007-12-05', 'Jl. Pepaya No. 12'),
(33, 133, 7, '23013', '0071234013', 'Gading Marten', '2008-01-10', 'Jl. Jambu No. 13'),
(34, 134, 7, '23014', '0071234014', 'Hesti Purwadinata', '2008-02-15', 'Jl. Manggis No. 14'),
(35, 135, 7, '23015', '0071234015', 'Indra Bekti', '2008-03-20', 'Jl. Dukuh No. 15'),
(36, 136, 8, '23016', '0071234016', 'Judika Sihotang', '2008-04-01', 'Jl. Sawo No. 16'),
(37, 137, 8, '23017', '0071234017', 'Kaka Slank', '2008-05-05', 'Jl. Nangka No. 17'),
(38, 138, 8, '23018', '0071234018', 'Luna Maya', '2008-06-10', 'Jl. Kedondong No. 18'),
(39, 139, 8, '23019', '0071234019', 'Melly Goeslaw', '2008-07-15', 'Jl. Cokelat No. 19'),
(40, 140, 8, '23020', '0071234020', 'Nike Ardilla', '2008-08-20', 'Jl. Kopi No. 20'),
(41, 141, 9, '22001', '0061234001', 'Olga Syahputra', '2006-01-01', 'Jl. Teh No. 21'),
(42, 142, 9, '22002', '0061234002', 'Parto Patrio', '2006-02-05', 'Jl. Susu No. 22'),
(43, 143, 9, '22003', '0061234003', 'Reza Rahadian', '2006-03-10', 'Jl. Gula No. 23'),
(44, 144, 9, '22004', '0061234004', 'Sandra Dewi', '2006-04-15', 'Jl. Garam No. 24'),
(45, 145, 9, '22005', '0061234005', 'Titi Kamal', '2006-05-20', 'Jl. Lada No. 25'),
(46, 146, 10, '22006', '0061234006', 'Uya Kuya', '2006-06-01', 'Jl. Cabe No. 26'),
(47, 147, 10, '22007', '0061234007', 'Vino G Bastian', '2006-07-05', 'Jl. Bawang No. 27'),
(48, 148, 10, '22008', '0061234008', 'Wendy Cagur', '2006-08-10', 'Jl. Jahe No. 28'),
(49, 149, 10, '22009', '0061234009', 'Yuki Kato', '2006-09-15', 'Jl. Kunyit No. 29'),
(50, 150, 10, '22010', '0061234010', 'Zayn Malik', '2006-10-20', 'Jl. Lengkuas No. 30'),
(51, 151, 11, '22011', '0061234011', 'Agnez Mo', '2006-11-01', 'Jl. Merpati No. 31'),
(52, 152, 11, '22012', '0061234012', 'Bunga Citra L', '2006-12-05', 'Jl. Gelatik No. 32'),
(53, 153, 11, '22013', '0061234013', 'Cakra Khan', '2007-01-10', 'Jl. Beo No. 33'),
(54, 154, 11, '22014', '0061234014', 'Denny Sumargo', '2007-02-15', 'Jl. Nuri No. 34'),
(55, 155, 11, '22015', '0061234015', 'Ernest Prakasa', '2007-03-20', 'Jl. Cendrawasih No. 35'),
(56, 156, 12, '22016', '0061234016', 'Fiersa Besari', '2007-04-01', 'Jl. Rajawali No. 36'),
(57, 157, 12, '22017', '0061234017', 'Gilang Dirga', '2007-05-05', 'Jl. Garuda No. 37'),
(58, 158, 12, '22018', '0061234018', 'Hamish Daud', '2007-06-10', 'Jl. Elang No. 38'),
(59, 159, 12, '22019', '0061234019', 'Iko Uwais', '2007-07-15', 'Jl. Kasuari No. 39'),
(60, 160, 12, '22020', '0061234020', 'Joe Taslim', '2007-08-20', 'Jl. Pipit No. 40'),
(61, 163, 13, '24100', '2312312312', 'salman', '2026-01-28', 'Jln, subang');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `tahun_id` int(11) NOT NULL,
  `tahun` varchar(20) NOT NULL COMMENT 'Contoh: 2024/2025',
  `semester` enum('Ganjil','Genap') NOT NULL,
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Hanya satu baris yang boleh 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`tahun_id`, `tahun`, `semester`, `is_active`) VALUES
(1, '2024/2025', 'Ganjil', 0),
(2, '2024/2025', 'Genap', 1),
(3, '2028/2029', 'Genap', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Gunakan hash (e.g., BCrypt)',
  `role` enum('Admin','Guru','Siswa','Kepsek') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `status_aktif`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$eTZNkEsDRdgTpKZfDdFpSOEimSYIUyO9yrVUTURceusFPzr5AZvR.', 'Admin', 1, NULL, '2025-12-29 06:06:59'),
(2, 'kepsek', '$2y$12$6Q7z.x4lr9skKjSQKbczeeerTO4kOv5LEtd9lMTnBO0kOsF5n0Ja6', 'Kepsek', 1, NULL, '2025-12-29 06:06:59'),
(10, 'guru_sandi', '$2y$12$tb3IELxJqQQr6ajOBa4HBeB7rhEY23plmi6U44dPt471aL0vq/KD.', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(11, 'guru_rina', '$2y$12$KlwULWiU7cXtgrDJcWWtie9m7N11YZRQpM8ZlYCQbbQPMNWau0p9C', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(12, 'guru_joko', '$2y$12$E10sDI7KT.X1t4r97Z/p4uuxircWPxqRi2VMUPSY/wDXWpyk/RVQu', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(13, 'guru_ahmad', '$2y$12$sSY3vMypryRDfCxqt7PFTe7e.iDjVhOOSlclDLwQs76pGrqeIMgk2', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(14, 'guru_siska', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(15, 'guru_tina', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(16, 'guru_budi', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(17, 'guru_sari', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(18, 'guru_dedi', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(19, 'guru_andi', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Guru', 1, NULL, '2026-01-17 05:22:39'),
(101, '24001', '$2y$12$QaxHqDf5IJvSE8h9SXnvNe66Dk21zQvzzGan9Yo997yh6KUNV4yP6', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(102, '24002', '$2y$10$LdRR3H4Svwc4Gvl/SPltUOJXARikA0Amw66eizaBqyNCzaUSPQ6yS', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(103, '24003', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(104, '24004', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(105, '24005', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(106, '24006', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(107, '24007', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(108, '24008', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(109, '24009', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(110, '24010', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(111, '24011', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(112, '24012', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(113, '24013', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(114, '24014', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(115, '24015', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(116, '24016', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(117, '24017', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(118, '24018', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(119, '24019', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(120, '24020', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(121, '23001', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(122, '23002', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(123, '23003', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(124, '23004', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(125, '23005', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(126, '23006', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(127, '23007', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(128, '23008', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(129, '23009', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(130, '23010', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(131, '23011', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(132, '23012', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(133, '23013', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(134, '23014', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(135, '23015', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(136, '23016', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(137, '23017', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(138, '23018', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(139, '23019', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(140, '23020', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(141, '22001', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(142, '22002', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(143, '22003', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(144, '22004', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(145, '22005', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(146, '22006', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(147, '22007', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(148, '22008', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(149, '22009', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(150, '22010', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(151, '22011', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(152, '22012', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(153, '22013', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(154, '22014', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(155, '22015', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(156, '22016', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(157, '22017', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(158, '22018', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(159, '22019', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(160, '22020', '$2y$10$uZ.7A1J4.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1.1u1', 'Siswa', 1, NULL, '2026-01-17 05:29:37'),
(161, 'admin1', '$2y$10$ftByhRfpekwnm/TXQVRXf./Ms2FbiYIs/fbtVcX1aE1giKg5rFEeq', 'Admin', 1, NULL, '2026-01-19 04:24:03'),
(163, '24100', '$2y$10$WWtpVp24pXQwAZSviVDkcue0At/CQWEAy19DNgT8.e3AL5upo/zd6', 'Siswa', 1, NULL, '2026-01-22 12:12:27'),
(164, '20240101', '$2y$10$IJzThLVPwPFFsrqUX2l.dOXF7nzsO7Y5EZ95WZheNAJEG9gwLHTgG', 'Guru', 1, NULL, '2026-01-22 12:13:37'),
(165, '123456', '$2y$10$zhU0AkEJIT.qbR/GFax69u1IxJ24KopFOM4L5Yy.kqqjrApHeaLYK', 'Guru', 1, NULL, '2026-01-22 12:28:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`absensi_id`) USING BTREE,
  ADD KEY `jadwal_id` (`jadwal_id`) USING BTREE;

--
-- Indexes for table `bobot_nilai`
--
ALTER TABLE `bobot_nilai`
  ADD PRIMARY KEY (`bobot_id`) USING BTREE;

--
-- Indexes for table `catatan_rapor`
--
ALTER TABLE `catatan_rapor`
  ADD PRIMARY KEY (`catatan_id`) USING BTREE,
  ADD KEY `siswa_id` (`siswa_id`) USING BTREE,
  ADD KEY `tahun_id` (`tahun_id`) USING BTREE,
  ADD KEY `guru_wali_id` (`guru_wali_id`) USING BTREE;

--
-- Indexes for table `detail_absensi`
--
ALTER TABLE `detail_absensi`
  ADD PRIMARY KEY (`detail_id`) USING BTREE,
  ADD UNIQUE KEY `unique_absensi_siswa` (`absensi_id`,`siswa_id`),
  ADD KEY `absensi_id` (`absensi_id`) USING BTREE,
  ADD KEY `siswa_id` (`siswa_id`) USING BTREE;

--
-- Indexes for table `detail_nilai`
--
ALTER TABLE `detail_nilai`
  ADD PRIMARY KEY (`detail_id`),
  ADD UNIQUE KEY `unique_detail_nilai` (`nilai_id`,`siswa_id`),
  ADD KEY `fk_detail_nilai_siswa` (`siswa_id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`guru_id`) USING BTREE,
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE,
  ADD UNIQUE KEY `nip` (`nip`) USING BTREE;

--
-- Indexes for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD PRIMARY KEY (`jadwal_id`),
  ADD KEY `idx_tahun` (`tahun_id`) USING BTREE,
  ADD KEY `idx_kelas` (`kelas_id`) USING BTREE,
  ADD KEY `idx_mapel` (`mapel_id`) USING BTREE,
  ADD KEY `idx_guru` (`guru_id`) USING BTREE;

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas_id`) USING BTREE,
  ADD KEY `guru_wali_id` (`guru_wali_id`) USING BTREE,
  ADD KEY `tahun_id` (`tahun_id`) USING BTREE;

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`mapel_id`),
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`nilai_id`),
  ADD UNIQUE KEY `unique_nilai_header` (`tahun_id`,`kelas_id`,`mapel_id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `fk_nilai_kelas` (`kelas_id`),
  ADD KEY `fk_nilai_mapel` (`mapel_id`);

--
-- Indexes for table `riwayat_cetak`
--
ALTER TABLE `riwayat_cetak`
  ADD PRIMARY KEY (`log_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `siswa_id` (`siswa_id`) USING BTREE;

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`siswa_id`) USING BTREE,
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE,
  ADD UNIQUE KEY `nis` (`nis`) USING BTREE,
  ADD KEY `kelas_id` (`kelas_id`) USING BTREE;

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`tahun_id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `absensi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `bobot_nilai`
--
ALTER TABLE `bobot_nilai`
  MODIFY `bobot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `catatan_rapor`
--
ALTER TABLE `catatan_rapor`
  MODIFY `catatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `detail_absensi`
--
ALTER TABLE `detail_absensi`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `detail_nilai`
--
ALTER TABLE `detail_nilai`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `jadwal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `nilai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `riwayat_cetak`
--
ALTER TABLE `riwayat_cetak`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `tahun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`jadwal_id`) ON DELETE CASCADE;

--
-- Constraints for table `catatan_rapor`
--
ALTER TABLE `catatan_rapor`
  ADD CONSTRAINT `catatan_rapor_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`),
  ADD CONSTRAINT `catatan_rapor_ibfk_2` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`),
  ADD CONSTRAINT `catatan_rapor_ibfk_3` FOREIGN KEY (`guru_wali_id`) REFERENCES `guru` (`guru_id`);

--
-- Constraints for table `detail_absensi`
--
ALTER TABLE `detail_absensi`
  ADD CONSTRAINT `detail_absensi_ibfk_1` FOREIGN KEY (`absensi_id`) REFERENCES `absensi` (`absensi_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_absensi_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`);

--
-- Constraints for table `detail_nilai`
--
ALTER TABLE `detail_nilai`
  ADD CONSTRAINT `fk_detail_nilai_header` FOREIGN KEY (`nilai_id`) REFERENCES `nilai` (`nilai_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_nilai_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`);

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD CONSTRAINT `fk_jadwal_pelajaran_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`),
  ADD CONSTRAINT `fk_jadwal_pelajaran_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jadwal_pelajaran_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`),
  ADD CONSTRAINT `fk_jadwal_pelajaran_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`);

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`guru_wali_id`) REFERENCES `guru` (`guru_id`),
  ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`);

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `fk_nilai_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`),
  ADD CONSTRAINT `fk_nilai_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`),
  ADD CONSTRAINT `fk_nilai_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`),
  ADD CONSTRAINT `fk_nilai_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`);

--
-- Constraints for table `riwayat_cetak`
--
ALTER TABLE `riwayat_cetak`
  ADD CONSTRAINT `riwayat_cetak_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `riwayat_cetak_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
