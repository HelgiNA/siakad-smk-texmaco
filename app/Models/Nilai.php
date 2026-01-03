<?php
namespace App\Models;

require_once __DIR__ . '/../Models/Model.php';

use PDO;
use PDOException;

class Nilai extends Model
{
    protected $table      = "nilai";
    protected $primaryKey = "nilai_id";

    /**
     * Ambil nilai yang sudah disimpan berdasarkan Kelas & Mapel & Tahun
     */
    public static function getByKelasMapel($kelas_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query    = "SELECT
                        n.*,
                        s.nama_lengkap,
                        s.siswa_id
                    FROM
                        $instance->table n
                        JOIN siswa s ON n.siswa_id = s.siswa_id
                    WHERE
                        s.kelas_id = :kelas_id
                        AND n.mapel_id = :mapel_id
                        AND n.tahun_id = :tahun_id
                    ORDER BY
                        s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':kelas_id', $kelas_id, PDO::PARAM_INT);
        $stmt->bindParam(':mapel_id', $mapel_id, PDO::PARAM_INT);
        $stmt->bindParam(':tahun_id', $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cek apakah siswa sudah memiliki nilai untuk mapel & tahun tertentu
     */
    public static function checkExisting($siswa_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query    = "SELECT * FROM $instance->table 
                    WHERE siswa_id = :siswa_id 
                    AND mapel_id = :mapel_id 
                    AND tahun_id = :tahun_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(':mapel_id', $mapel_id, PDO::PARAM_INT);
        $stmt->bindParam(':tahun_id', $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * UPSERT Batch - Insert atau Update nilai untuk banyak siswa sekaligus
     * Menggunakan MySQL ON DUPLICATE KEY UPDATE untuk efisiensi
     */
    public static function saveBatch($dataNilai)
    {
        $instance = new static();

        // Mulai transaction untuk keamanan data
        try {
            $instance->conn->beginTransaction();

            // Query UPSERT (MySQL Syntax: ON DUPLICATE KEY UPDATE)
            // UNIQUE KEY `unique_nilai` (`siswa_id`, `mapel_id`, `tahun_id`)
            // memastikan hanya ada 1 record per siswa-mapel-tahun
            $query = "INSERT INTO $instance->table 
                        (siswa_id, mapel_id, tahun_id, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, status_validasi)
                    VALUES 
                        (:siswa_id, :mapel_id, :tahun_id, :tugas, :uts, :uas, :akhir, 'Draft')
                    ON DUPLICATE KEY UPDATE 
                        nilai_tugas = VALUES(nilai_tugas),
                        nilai_uts = VALUES(nilai_uts),
                        nilai_uas = VALUES(nilai_uas),
                        nilai_akhir = VALUES(nilai_akhir),
                        status_validasi = 'Draft'";

            $stmt = $instance->conn->prepare($query);

            foreach ($dataNilai as $row) {
                // Validasi data
                $tugas = (float) $row['tugas'];
                $uts   = (float) $row['uts'];
                $uas   = (float) $row['uas'];

                // Cek range nilai (0-100)
                if ($tugas < 0 || $tugas > 100 || $uts < 0 || $uts > 100 || $uas < 0 || $uas > 100) {
                    $instance->conn->rollBack();
                    return [
                        'status'  => false,
                        'message' => 'Nilai harus berada dalam range 0-100'
                    ];
                }

                // Hitung Nilai Akhir di Server Side (PENTING: Jangan dari client)
                // Rumus: (Tugas*20%) + (UTS*30%) + (UAS*50%)
                $akhir = ($tugas * 0.20) + ($uts * 0.30) + ($uas * 0.50);

                // Execute untuk setiap siswa
                $stmt->execute([
                    ':siswa_id' => (int) $row['siswa_id'],
                    ':mapel_id' => (int) $row['mapel_id'],
                    ':tahun_id' => (int) $row['tahun_id'],
                    ':tugas'    => $tugas,
                    ':uts'      => $uts,
                    ':uas'      => $uas,
                    ':akhir'    => $akhir
                ]);
            }

            $instance->conn->commit();
            return [
                'status'  => true,
                'message' => 'Nilai berhasil disimpan'
            ];

        } catch (PDOException $e) {
            $instance->conn->rollBack();
            return [
                'status'  => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Ambil nilai final (untuk laporan/rapor)
     */
    public static function getByTahun($tahun_id)
    {
        $instance = new static();
        $query    = "SELECT
                        n.*,
                        s.nama_lengkap,
                        s.nisn,
                        m.nama_mapel,
                        k.nama_kelas
                    FROM
                        $instance->table n
                        JOIN siswa s ON n.siswa_id = s.siswa_id
                        JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                        JOIN kelas k ON s.kelas_id = k.kelas_id
                    WHERE
                        n.tahun_id = :tahun_id
                    ORDER BY
                        k.nama_kelas ASC,
                        s.nama_lengkap ASC,
                        m.nama_mapel ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':tahun_id', $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
