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
    public static function getAllWithRelasi()
    {
        $instance = new static();
        $query    = "SELECT
                            $instance->table.*,
                            u.status_aktif,
                            k.nama_kelas
                        FROM
                            $instance->table
                            JOIN users u ON $instance->table.user_id = u.user_id
                            LEFT JOIN kelas k ON $instance->table.kelas_id = k.kelas_id
                        ORDER BY
                            $instance->table.nis ASC;";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getUnassigned()
    {
        $instance = new static();
        // Assuming status_aktif from users table or similar logic.
        // For now, let's join with users to ensure we only get active students.
        // And check if kelas_id is NULL or 0
        $query = "SELECT
                        s.*,
                        u.status_aktif
                    FROM
                        $instance->table s
                        JOIN users u ON s.user_id = u.user_id
                    WHERE
                        (s.kelas_id IS NULL OR s.kelas_id = 0)
                        AND u.status_aktif = 1
                    ORDER BY
                        s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByKelas($kelas_id)
    {
        $instance = new static();
        $query    = "SELECT
                            s.*,
                            u.status_aktif
                        FROM
                            $instance->table s
                            JOIN users u ON s.user_id = u.user_id
                        WHERE
                            s.kelas_id = :kelas_id
                            AND u.status_aktif = 1
                        ORDER BY
                            s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':kelas_id', $kelas_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}