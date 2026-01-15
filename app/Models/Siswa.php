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

    /**
     * Hitung total siswa yang aktif (status_aktif = 1 di tabel users)
     * Digunakan untuk Dashboard Admin/Kepsek
     */
    public static function countActive()
    {
        $instance = new static();
        $query = "SELECT COUNT(s.siswa_id) as total
                  FROM " . $instance->table . " s
                  JOIN users u ON s.user_id = u.user_id
                  WHERE u.status_aktif = 1";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }

    /**
     * Ambil statistik kehadiran siswa
     * Digunakan untuk Dashboard Siswa
     * 
     * @param int $siswa_id
     * @return array dengan keys: nama_siswa, nama_kelas, wali_kelas, hadir, sakit, izin, alpa, total_pertemuan, persentase_hadir
     */
    public static function getStatistikAbsensi($siswa_id)
    {
        $instance = new static();
        
        $query = "SELECT
                    s.nama_lengkap as nama_siswa,
                    k.nama_kelas,
                    g.nama_lengkap as nama_wali_kelas,
                    COUNT(CASE WHEN da.status_kehadiran = 'Hadir' THEN 1 END) as hadir,
                    COUNT(CASE WHEN da.status_kehadiran = 'Sakit' THEN 1 END) as sakit,
                    COUNT(CASE WHEN da.status_kehadiran = 'Izin' THEN 1 END) as izin,
                    COUNT(CASE WHEN da.status_kehadiran = 'Alpa' THEN 1 END) as alpa,
                    COUNT(da.detail_id) as total_pertemuan
                  FROM " . $instance->table . " s
                  LEFT JOIN kelas k ON s.kelas_id = k.kelas_id
                  LEFT JOIN guru g ON k.guru_wali_id = g.guru_id
                  LEFT JOIN detail_absensi da ON s.siswa_id = da.siswa_id
                  WHERE s.siswa_id = :siswa_id
                  GROUP BY s.siswa_id";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Hitung persentase kehadiran
            if ($result['total_pertemuan'] > 0) {
                $result['persentase_hadir'] = round(($result['hadir'] / $result['total_pertemuan']) * 100, 2);
            } else {
                $result['persentase_hadir'] = 0;
            }
        }
        
        return $result ?? [];
    }

    /**
     * Ambil data siswa berdasarkan user_id
     * Digunakan untuk Dashboard Siswa
     * 
     * @param int $user_id
     * @return array
     */
    public static function getByUserId($user_id)
    {
        $instance = new static();
        $query = "SELECT * FROM " . $instance->table . " WHERE user_id = :user_id LIMIT 1";
        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
    }
}