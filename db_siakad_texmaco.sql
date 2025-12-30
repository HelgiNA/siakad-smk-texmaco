/*
 Navicat Premium Dump SQL

 Source Server         : coba
 Source Server Type    : MariaDB
 Source Server Version : 110802 (11.8.2-MariaDB-log)
 Source Host           : localhost:3306
 Source Schema         : db_siakad_texmaco

 Target Server Type    : MariaDB
 Target Server Version : 110802 (11.8.2-MariaDB-log)
 File Encoding         : 65001

 Date: 30/12/2025 10:59:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for absensi
-- ----------------------------
DROP TABLE IF EXISTS `absensi`;
CREATE TABLE `absensi`  (
  `absensi_id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_validasi` enum('Draft','Valid','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT 'Draft',
  `catatan_harian` text CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`absensi_id`) USING BTREE,
  INDEX `jadwal_id`(`jadwal_id` ASC) USING BTREE,
  CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of absensi
-- ----------------------------
INSERT INTO `absensi` VALUES (1, 1, '2024-07-15', 'Valid', 'Materi: Aljabar Linear. Siswa aktif.');

-- ----------------------------
-- Table structure for bobot_nilai
-- ----------------------------
DROP TABLE IF EXISTS `bobot_nilai`;
CREATE TABLE `bobot_nilai`  (
  `bobot_id` int(11) NOT NULL AUTO_INCREMENT,
  `persen_tugas` int(11) NOT NULL,
  `persen_uts` int(11) NOT NULL,
  `persen_uas` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`bobot_id`) USING BTREE,
  CONSTRAINT `chk_total_persen` CHECK (`persen_tugas` + `persen_uts` + `persen_uas` = 100)
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bobot_nilai
-- ----------------------------
INSERT INTO `bobot_nilai` VALUES (1, 30, 30, 40, '2025-12-29 13:06:59');

-- ----------------------------
-- Table structure for catatan_rapor
-- ----------------------------
DROP TABLE IF EXISTS `catatan_rapor`;
CREATE TABLE `catatan_rapor`  (
  `catatan_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `guru_wali_id` int(11) NOT NULL,
  `catatan_sikap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `catatan_akademik` text CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `tgl_input` date NOT NULL,
  PRIMARY KEY (`catatan_id`) USING BTREE,
  INDEX `siswa_id`(`siswa_id` ASC) USING BTREE,
  INDEX `tahun_id`(`tahun_id` ASC) USING BTREE,
  INDEX `guru_wali_id`(`guru_wali_id` ASC) USING BTREE,
  CONSTRAINT `catatan_rapor_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `catatan_rapor_ibfk_2` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `catatan_rapor_ibfk_3` FOREIGN KEY (`guru_wali_id`) REFERENCES `guru` (`guru_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of catatan_rapor
-- ----------------------------
INSERT INTO `catatan_rapor` VALUES (1, 1, 2, 1, 'Sangat Disiplin', 'Pertahankan prestasi akademikmu.', '2024-12-20');
INSERT INTO `catatan_rapor` VALUES (2, 3, 2, 1, 'Kurang Disiplin', 'Perlu peningkatan kehadiran dan belajar lebih giat.', '2024-12-20');

-- ----------------------------
-- Table structure for detail_absensi
-- ----------------------------
DROP TABLE IF EXISTS `detail_absensi`;
CREATE TABLE `detail_absensi`  (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `absensi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status_kehadiran` enum('Hadir','Sakit','Izin','Alpa') CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  PRIMARY KEY (`detail_id`) USING BTREE,
  INDEX `absensi_id`(`absensi_id` ASC) USING BTREE,
  INDEX `siswa_id`(`siswa_id` ASC) USING BTREE,
  CONSTRAINT `detail_absensi_ibfk_1` FOREIGN KEY (`absensi_id`) REFERENCES `absensi` (`absensi_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `detail_absensi_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of detail_absensi
-- ----------------------------
INSERT INTO `detail_absensi` VALUES (1, 1, 1, 'Hadir');
INSERT INTO `detail_absensi` VALUES (2, 1, 2, 'Sakit');
INSERT INTO `detail_absensi` VALUES (3, 1, 3, 'Alpa');

-- ----------------------------
-- Table structure for guru
-- ----------------------------
DROP TABLE IF EXISTS `guru`;
CREATE TABLE `guru`  (
  `guru_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  PRIMARY KEY (`guru_id`) USING BTREE,
  UNIQUE INDEX `user_id`(`user_id` ASC) USING BTREE,
  UNIQUE INDEX `nip`(`nip` ASC) USING BTREE,
  CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of guru
-- ----------------------------
INSERT INTO `guru` VALUES (1, 3, '19800101', 'Budi Santoso, S.Pd');
INSERT INTO `guru` VALUES (2, 4, '19850202', 'Sari Indah, M.Si');
INSERT INTO `guru` VALUES (3, 5, '19900303', 'Anton Wijaya, S.Si');

-- ----------------------------
-- Table structure for jadwal
-- ----------------------------
DROP TABLE IF EXISTS `jadwal`;
CREATE TABLE `jadwal`  (
  `jadwal_id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL COMMENT 'Senin, Selasa, dst',
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  PRIMARY KEY (`jadwal_id`) USING BTREE,
  INDEX `tahun_id`(`tahun_id` ASC) USING BTREE,
  INDEX `kelas_id`(`kelas_id` ASC) USING BTREE,
  INDEX `mapel_id`(`mapel_id` ASC) USING BTREE,
  INDEX `guru_id`(`guru_id` ASC) USING BTREE,
  CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `jadwal_ibfk_4` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jadwal
-- ----------------------------
INSERT INTO `jadwal` VALUES (1, 2, 1, 1, 1, 'Senin', '07:00:00', '08:30:00');
INSERT INTO `jadwal` VALUES (2, 2, 1, 3, 3, 'Senin', '08:45:00', '10:15:00');
INSERT INTO `jadwal` VALUES (3, 2, 2, 4, 2, 'Senin', '07:00:00', '08:30:00');

-- ----------------------------
-- Table structure for kelas
-- ----------------------------
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas`  (
  `kelas_id` int(11) NOT NULL AUTO_INCREMENT,
  `guru_wali_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `nama_kelas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL COMMENT 'Contoh: X-IPA-1',
  `tingkat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL COMMENT 'Contoh: 10, 11, 12',
  `jurusan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL COMMENT 'Contoh: IPA, IPS',
  PRIMARY KEY (`kelas_id`) USING BTREE,
  INDEX `guru_wali_id`(`guru_wali_id` ASC) USING BTREE,
  INDEX `tahun_id`(`tahun_id` ASC) USING BTREE,
  CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`guru_wali_id`) REFERENCES `guru` (`guru_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kelas
-- ----------------------------
INSERT INTO `kelas` VALUES (1, 1, 2, 'X-IPA-1', '10', 'IPA');
INSERT INTO `kelas` VALUES (2, 2, 2, 'X-IPS-1', '10', 'IPS');

-- ----------------------------
-- Table structure for mata_pelajaran
-- ----------------------------
DROP TABLE IF EXISTS `mata_pelajaran`;
CREATE TABLE `mata_pelajaran`  (
  `mapel_id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `nama_mapel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `kkm` int(11) NOT NULL DEFAULT 75 COMMENT 'Kriteria Ketuntasan Minimal',
  PRIMARY KEY (`mapel_id`) USING BTREE,
  UNIQUE INDEX `kode_mapel`(`kode_mapel` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mata_pelajaran
-- ----------------------------
INSERT INTO `mata_pelajaran` VALUES (1, 'MTK-W', 'Matematika Wajib', 75);
INSERT INTO `mata_pelajaran` VALUES (2, 'FIS-P', 'Fisika Peminatan', 78);
INSERT INTO `mata_pelajaran` VALUES (3, 'BIO-P', 'Biologi Peminatan', 78);
INSERT INTO `mata_pelajaran` VALUES (4, 'SOS-W', 'Sosiologi', 75);

-- ----------------------------
-- Table structure for nilai
-- ----------------------------
DROP TABLE IF EXISTS `nilai`;
CREATE TABLE `nilai`  (
  `nilai_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `nilai_tugas` decimal(5, 2) NULL DEFAULT 0.00,
  `nilai_uts` decimal(5, 2) NULL DEFAULT 0.00,
  `nilai_uas` decimal(5, 2) NULL DEFAULT 0.00,
  `nilai_akhir` decimal(5, 2) NULL DEFAULT 0.00 COMMENT 'Hasil kalkulasi otomatis',
  `status_validasi` enum('Draft','Final') CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT 'Draft',
  PRIMARY KEY (`nilai_id`) USING BTREE,
  INDEX `siswa_id`(`siswa_id` ASC) USING BTREE,
  INDEX `mapel_id`(`mapel_id` ASC) USING BTREE,
  INDEX `tahun_id`(`tahun_id` ASC) USING BTREE,
  CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `nilai_ibfk_3` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai
-- ----------------------------
INSERT INTO `nilai` VALUES (1, 1, 1, 2, 90.00, 88.00, 95.00, 91.40, 'Draft');
INSERT INTO `nilai` VALUES (2, 2, 1, 2, 75.00, 70.00, 75.00, 73.50, 'Draft');
INSERT INTO `nilai` VALUES (3, 3, 1, 2, 50.00, 40.00, 45.00, 45.00, 'Draft');

-- ----------------------------
-- Table structure for riwayat_cetak
-- ----------------------------
DROP TABLE IF EXISTS `riwayat_cetak`;
CREATE TABLE `riwayat_cetak`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tgl_cetak` datetime NULL DEFAULT current_timestamp(),
  `jenis_dokumen` enum('Rapor','RekapAbsen') CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `siswa_id`(`siswa_id` ASC) USING BTREE,
  CONSTRAINT `riwayat_cetak_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `riwayat_cetak_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of riwayat_cetak
-- ----------------------------
INSERT INTO `riwayat_cetak` VALUES (1, 1, 1, '2025-12-29 13:06:59', 'Rapor');
INSERT INTO `riwayat_cetak` VALUES (2, 3, 3, '2025-12-29 13:06:59', 'RekapAbsen');

-- ----------------------------
-- Table structure for siswa
-- ----------------------------
DROP TABLE IF EXISTS `siswa`;
CREATE TABLE `siswa`  (
  `siswa_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `nis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`siswa_id`) USING BTREE,
  UNIQUE INDEX `user_id`(`user_id` ASC) USING BTREE,
  UNIQUE INDEX `nis`(`nis` ASC) USING BTREE,
  INDEX `kelas_id`(`kelas_id` ASC) USING BTREE,
  CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of siswa
-- ----------------------------
INSERT INTO `siswa` VALUES (1, 6, 1, '24001', 'Ani Lestari', '2008-01-01', 'Jl. Merdeka No. 1');
INSERT INTO `siswa` VALUES (2, 7, 1, '24002', 'Budi Hartono', '2008-02-15', 'Jl. Sudirman No. 5');
INSERT INTO `siswa` VALUES (3, 8, 1, '24003', 'Citra Kirana', '2008-03-20', 'Jl. Diponegoro No. 10');
INSERT INTO `siswa` VALUES (4, 9, 2, '24004', 'Doni Pratama', '2008-04-10', 'Jl. Mawar No. 3');
INSERT INTO `siswa` VALUES (5, 10, 2, '24005', 'Eka Saputra', '2008-05-05', 'Jl. Melati No. 7');

-- ----------------------------
-- Table structure for tahun_ajaran
-- ----------------------------
DROP TABLE IF EXISTS `tahun_ajaran`;
CREATE TABLE `tahun_ajaran`  (
  `tahun_id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL COMMENT 'Contoh: 2024/2025',
  `semester` enum('Ganjil','Genap') CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 0 COMMENT 'Hanya satu baris yang boleh 1',
  PRIMARY KEY (`tahun_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tahun_ajaran
-- ----------------------------
INSERT INTO `tahun_ajaran` VALUES (1, '2023/2024', 'Genap', 0);
INSERT INTO `tahun_ajaran` VALUES (2, '2024/2025', 'Ganjil', 1);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL COMMENT 'Gunakan hash (e.g., BCrypt)',
  `role` enum('Admin','Guru','Siswa','Kepsek') CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NOT NULL,
  `status_aktif` tinyint(1) NULL DEFAULT 1,
  `last_login` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', '$2y$10$lpfkOAyqD1fqLYWbs8tjsOUh0qWkTG.Ssq0qOBXqcIuc4xVClZGKK', 'Admin', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (2, 'kepsek', 'hash_kepsek', 'Kepsek', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (3, 'guru_budi', 'hash_guru1', 'Guru', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (4, 'guru_sari', 'hash_guru2', 'Guru', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (5, 'guru_anton', 'hash_guru3', 'Guru', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (6, 'siswa_ani', 'hash_siswa1', 'Siswa', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (7, 'siswa_budi', 'hash_siswa2', 'Siswa', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (8, 'siswa_citra', 'hash_siswa3', 'Siswa', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (9, 'siswa_doni', 'hash_siswa4', 'Siswa', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (10, 'siswa_eka', 'hash_siswa5', 'Siswa', 1, NULL, '2025-12-29 13:06:59');
INSERT INTO `users` VALUES (11, 'siswa_hans', '$2y$12$LrJc4rDIJIpNefgbi36vau3Sm0JW2VBz52ElccPomcw0CMYCnqgqS', 'Siswa', 1, NULL, '2025-12-29 15:38:08');

SET FOREIGN_KEY_CHECKS = 1;
