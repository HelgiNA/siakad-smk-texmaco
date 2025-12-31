<?php
// setup_guru.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db   = new Database();
    $conn = $db->getConnection();

    $query = "
    CREATE TABLE IF NOT EXISTS guru (
        guru_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        nip VARCHAR(20) NOT NULL UNIQUE,
        nama_lengkap VARCHAR(100) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";

    $conn->exec($query);
    echo "Tabel 'guru' berhasil dibuat atau sudah ada.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
