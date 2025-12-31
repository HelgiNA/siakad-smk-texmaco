<?php
// setup_jadwal.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db   = new Database();
    $conn = $db->getConnection();

    // Disable foreign key checks to avoid issues during drop
    $conn->exec("SET foreign_key_checks = 0");

    $query = "
    DROP TABLE IF EXISTS `jadwal_pelajaran`;
    CREATE TABLE `jadwal_pelajaran` (
      `jadwal_id` int(11) NOT NULL AUTO_INCREMENT,
      `tahun_id` int(11) NOT NULL,
      `kelas_id` int(11) NOT NULL,
      `mapel_id` int(11) NOT NULL,
      `guru_id` int(11) NOT NULL,
      `hari` ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
      `jam_mulai` TIME NOT NULL,
      `jam_selesai` TIME NOT NULL,
      PRIMARY KEY (`jadwal_id`),
      INDEX `idx_tahun` (`tahun_id`),
      INDEX `idx_kelas` (`kelas_id`),
      INDEX `idx_mapel` (`mapel_id`),
      INDEX `idx_guru` (`guru_id`),
      CONSTRAINT `fk_jadwal_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_ajaran` (`tahun_id`) ON DELETE RESTRICT,
      CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE,
      CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`mapel_id`) ON DELETE RESTRICT,
      CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB;
    ";

    $conn->exec($query);

    // Re-enable foreign key checks
    $conn->exec("SET foreign_key_checks = 1");

    echo "Tabel 'jadwal_pelajaran' berhasil dibuat.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
