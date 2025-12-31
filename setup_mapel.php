<?php
// setup_mapel.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db   = new Database();
    $conn = $db->getConnection();

    $query = "
    DROP TABLE IF EXISTS `mata_pelajaran`;
    CREATE TABLE `mata_pelajaran` (
      `mapel_id` int(11) NOT NULL AUTO_INCREMENT,
      `kode_mapel` varchar(20) NOT NULL COMMENT 'Unik, Ex: MTK, WEB-1',
      `nama_mapel` varchar(100) NOT NULL,
      `kkm` int(11) NOT NULL DEFAULT 75,
      PRIMARY KEY (`mapel_id`),
      UNIQUE KEY `kode_mapel` (`kode_mapel`)
    ) ENGINE=InnoDB;
    ";

    $conn->exec($query);
    echo "Tabel 'mata_pelajaran' berhasil dibuat.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}