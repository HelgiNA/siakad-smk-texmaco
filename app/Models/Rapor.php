<?php
namespace App\Models;

require_once __DIR__ . '/Model.php';

use PDO;
use PDOException;

class Rapor extends Model
{
    protected $table = '';

    /**
     * Ambil biodata siswa beserta info kelas/jurusan
     */
    public static function getBiodata($siswa_id)
    {
        $instance = new static();
        $query = "SELECT s.*, k.nama_kelas, k.tingkat, k.jurusan, k.guru_wali_id
                  FROM siswa s
                  LEFT JOIN kelas k ON s.kelas_id = k.kelas_id
                  WHERE s.siswa_id = :siswa_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil semua nilai akademik per kelompok (A, B, C)
     */
    public static function getNilaiAkademik($siswa_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT n.*, m.nama_mapel, m.kelompok, m.kkm
                  FROM nilai n
                  JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                  WHERE n.siswa_id = :siswa_id
                  AND n.tahun_id = :tahun_id
                  ORDER BY m.kelompok ASC, m.nama_mapel ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(':tahun_id', $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groups = ['A' => [], 'B' => [], 'C' => []];
        foreach ($rows as $r) {
            $kel = strtoupper($r['kelompok']);
            if (strpos($kel, 'C') === 0) {
                $groups['C'][] = $r;
            } elseif ($kel === 'A') {
                $groups['A'][] = $r;
            } else {
                $groups['B'][] = $r;
            }
        }

        return $groups;
    }

    /**
     * Hitung rekap absensi (Hadir/Sakit/Izin/Alpa) untuk siswa di tahun tertentu
     */
    public static function getAbsensi($siswa_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT d.status_kehadiran, COUNT(*) as cnt
                  FROM detail_absensi d
                  JOIN absensi a ON d.absensi_id = a.absensi_id
                  JOIN jadwal_pelajaran j ON a.jadwal_id = j.jadwal_id
                  WHERE d.siswa_id = :siswa_id
                  AND j.tahun_id = :tahun_id
                  GROUP BY d.status_kehadiran";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(':tahun_id', $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [
            'Hadir' => 0,
            'Sakit' => 0,
            'Izin'  => 0,
            'Alpa'  => 0,
        ];

        foreach ($rows as $r) {
            if (isset($result[$r['status_kehadiran']])) {
                $result[$r['status_kehadiran']] = (int) $r['cnt'];
            }
        }

        return $result;
    }

    /**
     * Periksa apakah semua nilai siswa sudah berstatus 'Final' untuk tahun ajaran aktif
     */
    public static function checkStatusValidasi($siswa_id)
    {
        $instance = new static();

        // Ambil tahun aktif jika ada
        require_once __DIR__ . '/TahunAjaran.php';
        $active = TahunAjaran::getActive();
        if (! $active) {
            return false;
        }

        $query = "SELECT COUNT(*) as cnt
                  FROM nilai n
                  WHERE n.siswa_id = :siswa_id
                  AND n.tahun_id = :tahun_id
                  AND n.status_validasi != 'Final'";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(':tahun_id', $active['tahun_id'], PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($res && (int) $res['cnt'] === 0);
    }
}
