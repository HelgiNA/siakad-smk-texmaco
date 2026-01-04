-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2026 at 03:28 PM
-- Server version: 12.1.2-MariaDB
-- PHP Version: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_siakad_texmaco`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `absensi_id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_validasi` enum('Draft','Valid','Rejected') DEFAULT 'Draft',
  `catatan_harian` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`absensi_id`, `jadwal_id`, `tanggal`, `status_validasi`, `catatan_harian`) VALUES
(1, 1, '2024-07-15', 'Valid', 'Materi: Aljabar Linear. Siswa aktif.');

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
) ;

--
-- Dumping data for table `bobot_nilai`
--

INSERT INTO `bobot_nilai` (`bobot_id`, `persen_tugas`, `persen_uts`, `persen_uas`, `created_at`) VALUES
(1, 30, 30, 40, '2025-12-29 06:06:59');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `catatan_rapor`
--

INSERT INTO `catatan_rapor` (`catatan_id`, `siswa_id`, `tahun_id`, `guru_wali_id`, `catatan_sikap`, `catatan_akademik`, `tgl_input`) VALUES
(1, 1, 2, 1, 'Sangat Disiplin', 'Pertahankan prestasi akademikmu.', '2024-12-20'),
(2, 3, 2, 1, 'Kurang Disiplin', 'Perlu peningkatan kehadiran dan belajar lebih giat.', '2024-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `detail_absensi`
--

CREATE TABLE `detail_absensi` (
  `detail_id` int(11) NOT NULL,
  `absensi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status_kehadiran` enum('Hadir','Sakit','Izin','Alpa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `detail_absensi`
--

INSERT INTO `detail_absensi` (`detail_id`, `absensi_id`, `siswa_id`, `status_kehadiran`) VALUES
(1, 1, 1, 'Hadir'),
(2, 1, 2, 'Sakit'),
(3, 1, 3, 'Alpa');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`guru_id`, `user_id`, `nip`, `nama_lengkap`) VALUES
(1, 3, '19800101', 'Budi Santoso, S.Pd'),
(2, 4, '19850202', 'Sari Indah, M.Si'),
(3, 5, '19900303', 'Anton Wijaya, S.Si'),
(4, 19, 'guru', 'guru');

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
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`jadwal_id`, `tahun_id`, `kelas_id`, `mapel_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(1, 2, 1, 1, 1, 'Senin', '07:00:00', '08:30:00'),
(2, 2, 1, 3, 3, 'Senin', '08:45:00', '10:15:00'),
(3, 2, 2, 4, 2, 'Senin', '07:00:00', '08:30:00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `guru_wali_id`, `tahun_id`, `nama_kelas`, `tingkat`, `jurusan`) VALUES
(1, 4, 2, 'X-IPA-1', '10', 'IPA'),
(2, 1, 2, 'X-IPS-1', '10', 'IPS');

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
(1, 'MTK-W', 'Matematika Wajib', 'A', 75),
(2, 'FIS-P', 'Fisika Peminatan', 'C1', 78),
(3, 'BIO-P', 'Biologi Peminatan', 'C1', 78),
(4, 'SOS-W', 'Sosiologi', 'A', 75);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `nilai_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT 0.00,
  `nilai_uts` decimal(5,2) DEFAULT 0.00,
  `nilai_uas` decimal(5,2) DEFAULT 0.00,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00 COMMENT 'Hasil kalkulasi otomatis',
  `status_validasi` enum('Draft','Submitted','Revisi','Final') DEFAULT 'Draft',
  `catatan_revisi` TEXT NULL DEFAULT NULL COMMENT 'Feedback dari Wali Kelas saat revisi',
  PRIMARY KEY (`nilai_id`),
  UNIQUE KEY `unique_nilai` (`siswa_id`, `mapel_id`, `tahun_id`),
  FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE,
  FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`) ON DELETE CASCADE,
  FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`nilai_id`, `siswa_id`, `mapel_id`, `tahun_id`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `status_validasi`) VALUES
(1, 1, 1, 2, 90.00, 88.00, 95.00, 91.40, 'Draft'),
(2, 2, 1, 2, 75.00, 70.00, 75.00, 73.50, 'Draft'),
(3, 3, 1, 2, 50.00, 40.00, 45.00, 45.00, 'Draft');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `riwayat_cetak`
--

INSERT INTO `riwayat_cetak` (`log_id`, `user_id`, `siswa_id`, `tgl_cetak`, `jenis_dokumen`) VALUES
(1, 1, 1, '2025-12-29 13:06:59', 'Rapor'),
(2, 3, 3, '2025-12-29 13:06:59', 'RekapAbsen');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`siswa_id`, `user_id`, `kelas_id`, `nis`, `nisn`, `nama_lengkap`, `tanggal_lahir`, `alamat`) VALUES
(1, 6, 1, '24001', '0081234561', 'Ani Lestari', '2008-01-01', 'Jl. Merdeka No. 1'),
(2, 7, 1, '24002', '0081234562', 'Budi Hartono', '2008-02-15', 'Jl. Sudirman No. 5'),
(3, 8, 2, '24003', '0081234563', 'Citra Kirana', '2008-03-20', 'Jl. Diponegoro No. 10'),
(4, 9, 2, '24004', '0081234564', 'Doni Pratama', '2008-04-10', 'Jl. Mawar No. 3'),
(5, 10, 2, '24005', '0081234565', 'Eka Saputra', '2008-05-05', 'Jl. Melati No. 7'),
(6, 18, 2, '12345', '12345', 'Helgi Nur A', '2026-01-11', 'Jajja');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `tahun_id` int(11) NOT NULL,
  `tahun` varchar(20) NOT NULL COMMENT 'Contoh: 2024/2025',
  `semester` enum('Ganjil','Genap') NOT NULL,
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Hanya satu baris yang boleh 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`tahun_id`, `tahun`, `semester`, `is_active`) VALUES
(1, '2023/2024', 'Genap', 0),
(2, '2024/2025', 'Ganjil', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `status_aktif`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$lpfkOAyqD1fqLYWbs8tjsOUh0qWkTG.Ssq0qOBXqcIuc4xVClZGKK', 'Admin', 1, NULL, '2025-12-29 06:06:59'),
(2, 'kepsek', 'hash_kepsek', 'Kepsek', 1, NULL, '2025-12-29 06:06:59'),
(3, 'guru_budi', 'hash_guru1', 'Guru', 1, NULL, '2025-12-29 06:06:59'),
(4, 'guru_sari', 'hash_guru2', 'Guru', 1, NULL, '2025-12-29 06:06:59'),
(5, 'guru_anton', 'hash_guru3', 'Guru', 1, NULL, '2025-12-29 06:06:59'),
(6, 'siswa_ani', 'hash_siswa1', 'Siswa', 1, NULL, '2025-12-29 06:06:59'),
(7, 'siswa_budi', 'hash_siswa2', 'Siswa', 1, NULL, '2025-12-29 06:06:59'),
(8, 'siswa_citra', 'hash_siswa3', 'Siswa', 1, NULL, '2025-12-29 06:06:59'),
(9, 'siswa_doni', 'hash_siswa4', 'Siswa', 1, NULL, '2025-12-29 06:06:59'),
(10, 'siswa_eka', 'hash_siswa5', 'Siswa', 1, NULL, '2025-12-29 06:06:59'),
(11, 'siswa_hans', '$2y$12$LrJc4rDIJIpNefgbi36vau3Sm0JW2VBz52ElccPomcw0CMYCnqgqS', 'Siswa', 1, NULL, '2025-12-29 08:38:08'),
(18, '12345', '$2y$12$BWUoR0WLCAGRxL/0OD3bh.WFPq3Q4ZdJ6QTPrp3D1t8buV3pKknUK', 'Siswa', 1, NULL, '2026-01-02 05:14:27'),
(19, 'guru', '$2y$12$3Ddu9CDaCof4Y3hyRARsw.vMSyayzX0CM.js3FQBQORI2yz0.c6NS', 'Guru', 1, NULL, '2026-01-02 06:24:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`absensi_id`) USING BTREE,
  ADD KEY `jadwal_id` (`jadwal_id`) USING BTREE;
  ALTER TABLE `absensi`

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
  ADD KEY `absensi_id` (`absensi_id`) USING BTREE,
  ADD KEY `siswa_id` (`siswa_id`) USING BTREE;

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
  ADD PRIMARY KEY (`nilai_id`) USING BTREE,
  ADD KEY `siswa_id` (`siswa_id`) USING BTREE,
  ADD KEY `mapel_id` (`mapel_id`) USING BTREE,
  ADD KEY `tahun_id` (`tahun_id`) USING BTREE;

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
  MODIFY `absensi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bobot_nilai`
--
ALTER TABLE `bobot_nilai`
  MODIFY `bobot_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catatan_rapor`
--
ALTER TABLE `catatan_rapor`
  MODIFY `catatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_absensi`
--
ALTER TABLE `detail_absensi`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `jadwal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `nilai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat_cetak`
--
ALTER TABLE `riwayat_cetak`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `tahun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--ALTER TABLE `absensi`
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