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
        $query    = "SELECT j.*,
                         k.nama_kelas,
                         m.nama_mapel, m.kode_mapel, m.kelompok,
                         g.nama_lengkap as nama_guru,
                         t.tahun, t.semester
                  FROM " . $instance->table . " j
                  JOIN kelas k ON j.kelas_id = k.kelas_id
                  JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                  JOIN guru g ON j.guru_id = g.guru_id
                  JOIN tahun_ajaran t ON j.tahun_id = t.tahun_id
                  WHERE j.tahun_id = :tahun_id
                  ORDER BY k.tingkat ASC, k.nama_kelas ASC, FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':tahun_id', $tahun_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
