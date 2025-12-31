<?php
namespace App\Models;

require_once __DIR__ . '/../Models/Model.php';

use PDO;

class Siswa extends Model
{
    protected $table      = "siswa";
    protected $primaryKey = "siswa_id";

    // Kita override getAll untuk join dengan tabel users
    // Agar saat menampilkan daftar siswa, kita bisa lihat usernamenya juga (opsional)
    public static function getAllWithUser()
    {
        $instance = new static();
        $query    = "SELECT siswa.*, users.username, users.role
                        FROM " . $instance->table . "
                        JOIN users ON siswa.user_id = users.user_id
                        ORDER BY siswa.siswa_id ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}