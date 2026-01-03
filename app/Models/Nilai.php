<?php

namespace App\Models;

use App\Core\Database;
use PDOException;

class Nilai extends Model
{
    protected static $table = 'nilai';

    public static function saveBatch($dataNilai)
    {
        $instance = new static();
        
        // Gunakan Transaction biar aman (semua tersimpan atau batal semua)
        $instance->conn->beginTransaction();

        try {
            // Query UPSERT (MySQL Syntax: ON DUPLICATE KEY UPDATE)
            // Ini cara paling efisien/pro untuk menangani Simpan/Edit sekaligus
            $query = "INSERT INTO nilai (siswa_id, mapel_id, tahun_id, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, status_validasi)\n                      VALUES (:siswa_id, :mapel_id, :tahun_id, :tugas, :uts, :uas, :akhir, 'Draft')\n                      ON DUPLICATE KEY UPDATE \n                      nilai_tugas = VALUES(nilai_tugas),\n                      nilai_uts = VALUES(nilai_uts),\n                      nilai_uas = VALUES(nilai_uas),\n                      nilai_akhir = VALUES(nilai_akhir)";
            
            $stmt = $instance->conn->prepare($query);

            foreach ($dataNilai as $row) {
                // Hitung di Server Side (SIA-007)
                $akhir = ($row['tugas'] * 0.2) + ($row['uts'] * 0.3) + ($row['uas'] * 0.5);

                $stmt->execute([
                    'siswa_id' => $row['siswa_id'],
                    'mapel_id' => $row['mapel_id'],
                    'tahun_id' => $row['tahun_id'], // Ambil dari session tahun aktif
                    'tugas'    => $row['tugas'],
                    'uts'      => $row['uts'],
                    'uas'      => $row['uas'],
                    'akhir'    => $akhir
                ]);
            }

            $instance->conn->commit();
            return true;

        } catch (PDOException $e) {
            $instance->conn->rollBack();
            return false;
        }
    }

    public static function getByKelasMapel($kelas_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        // Query ini mengambil nilai siswa untuk kelas, mapel, dan tahun tertentu.
        // Hasilnya di-index berdasarkan siswa_id untuk kemudahan lookup di view.
        $query = "SELECT
                        n.*
                    FROM
                        " . static::$table . " n
                        JOIN siswa s ON n.siswa_id = s.id
                    WHERE
                        s.kelas_id = :kelas_id
                        AND n.mapel_id = :mapel_id
                        AND n.tahun_id = :tahun_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([
            ':kelas_id' => $kelas_id,
            ':mapel_id' => $mapel_id,
            ':tahun_id' => $tahun_id
        ]);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Re-index array by siswa_id for easier lookup
        $nilaiBySiswa = [];
        foreach ($result as $row) {
            $nilaiBySiswa[$row['siswa_id']] = $row;
        }

        return $nilaiBySiswa;
    }
}
