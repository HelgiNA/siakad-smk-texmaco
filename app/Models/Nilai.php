<?php

namespace App\Models;

class Nilai extends Model
{
    protected static $table = 'nilai';

    public static function getByKelasMapel($kelas_id, $mapel_id)
    {
        $instance = new static();
        $query = "SELECT * FROM " . static::$table . " WHERE siswa_id IN (SELECT id FROM siswa WHERE kelas_id = ?) AND mapel_id = ?";
        $stmt = $instance->conn->prepare($query);
        $stmt->execute([$kelas_id, $mapel_id]);
        $result = $stmt->fetchAll();
        return array_column($result, null, 'siswa_id');
    }

    public static function saveBatch($dataNilai)
    {
        $instance = new static();
        $instance->conn->beginTransaction();

        try {
            $query = "INSERT INTO nilai (siswa_id, mapel_id, tahun_id, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, status_validasi)
                      VALUES (:siswa_id, :mapel_id, :tahun_id, :tugas, :uts, :uas, :akhir, 'Draft')
                      ON DUPLICATE KEY UPDATE 
                      nilai_tugas = VALUES(nilai_tugas),
                      nilai_uts = VALUES(nilai_uts),
                      nilai_uas = VALUES(nilai_uas),
                      nilai_akhir = VALUES(nilai_akhir)";
            
            $stmt = $instance->conn->prepare($query);

            foreach ($dataNilai as $row) {
                $akhir = ($row['tugas'] * 0.2) + ($row['uts'] * 0.3) + ($row['uas'] * 0.5);

                $stmt->execute([
                    'siswa_id' => $row['siswa_id'],
                    'mapel_id' => $row['mapel_id'],
                    'tahun_id' => $row['tahun_id'],
                    'tugas'    => $row['tugas'],
                    'uts'      => $row['uts'],
                    'uas'      => $row['uas'],
                    'akhir'    => $akhir
                ]);
            }

            $instance->conn->commit();
            return true;

        } catch (\PDOException $e) {
            $instance->conn->rollBack();
            return false;
        }
    }
}
