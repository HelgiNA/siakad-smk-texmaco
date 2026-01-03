<?php
// Database credentials from config/config.php
define("DB_HOST", "127.0.0.1");
define("DB_NAME", "db_siakad_texmaco");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

try {
    $pdoconn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdoconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sql = "
DROP TABLE IF EXISTS `nilai`;
CREATE TABLE `nilai` (
  `nilai_id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT 0.00,
  `nilai_uts` decimal(5,2) DEFAULT 0.00,
  `nilai_uas` decimal(5,2) DEFAULT 0.00,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00 COMMENT 'Hasil (Tugas*20%)+(UTS*30%)+(UAS*50%)',
  `status_validasi` enum('Draft','Final') DEFAULT 'Draft',
  PRIMARY KEY (`nilai_id`),
  UNIQUE KEY `unique_nilai` (`siswa_id`, `mapel_id`, `tahun_id`),
  FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $pdoconn->exec($sql);
    echo "Table 'nilai' created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
