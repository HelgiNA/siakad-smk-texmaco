<?php
// app/Models/Jadwal.php
namespace App\Models;

require_once __DIR__ . '/Model.php';

use PDO;

class Jadwal extends Model
{
    protected $table      = 'jadwal_pelajaran';
    protected $primaryKey = 'jadwal_id';

    public static function getAllByTahun($tahun_id)
    {
        $instance = new static();
        $query    = "SELECT j.*,\n                            k.nama_kelas,\n                            m.nama_mapel, m.kode_mapel, m.kelompok,\n                            g.nama_lengkap as nama_guru,\n                            t.tahun, t.semester\n                        FROM $instance->table j\n                        JOIN kelas k ON j.kelas_id = k.kelas_id\n                        JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id\n                        JOIN guru g ON j.guru_id = g.guru_id\n                        JOIN tahun_ajaran t ON j.tahun_id = t.tahun_id\n                        WHERE j.tahun_id = :tahun_id\n                  ORDER BY k.tingkat ASC, k.nama_kelas ASC, FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':tahun_id', $tahun_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get schedules for a specific teacher on a specific day and year
    public static function getByGuru($guru_id, $hari, $tahun_id)
    {
        $instance = new static();
        $query    = "SELECT\n                            j.*,\n                            k.nama_kelas,\n                            m.nama_mapel,\n                            m.kode_mapel\n                        FROM\n                            $instance->table j\n                            JOIN kelas k ON j.kelas_id = k.kelas_id\n                            JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id\n                        WHERE\n                            j.guru_id = :guru_id\n                            AND j.hari = :hari\n                            AND j.tahun_id = :tahun_id\n                        ORDER BY\n                            j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([
            ':guru_id'  => $guru_id,
            ':hari'     => $hari,
            ':tahun_id' => $tahun_id,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findWithDetails($id)
    {
        $instance = new static();
        $query    = "SELECT j.*,\n                         k.nama_kelas,\n                         m.nama_mapel,\n                         g.nama_lengkap as nama_guru,\n                         t.tahun, t.semester\n                  FROM " . $instance->table . " j\n                  JOIN kelas k ON j.kelas_id = k.kelas_id\n                  JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id\n                  JOIN guru g ON j.guru_id = g.guru_id\n                  JOIN tahun_ajaran t ON j.tahun_id = t.tahun_id\n                  WHERE j.jadwal_id = :id";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getKelasMapelByGuru($guru_id, $tahun_id)
    {
        $instance = new static();
        // Query ini mengambil daftar kelas & mapel yang diajar oleh guru tertentu
        // pada tahun ajaran aktif, dan memastikan tidak ada duplikat.
        $query = "SELECT DISTINCT
                        j.kelas_id,
                        k.nama_kelas,
                        j.mapel_id,
                        m.nama_mapel
                    FROM
                        " . $instance->table . " j
                        JOIN kelas k ON j.kelas_id = k.kelas_id
                        JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                    WHERE
                        j.guru_id = :guru_id
                        AND j.tahun_id = :tahun_id
                    ORDER BY
                        k.nama_kelas, m.nama_mapel";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([
            ':guru_id'  => $guru_id,
            ':tahun_id' => $tahun_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}