<?php
// app/Models/Kelas.php

namespace App\Models;

require_once __DIR__ . '/Model.php';

class Kelas extends Model
{
    protected $table      = 'kelas';
    protected $primaryKey = 'kelas_id';

    public static function getAllWithDetails()
    {
        $instance = new static();
        $query    = "SELECT k.*,
                         g.nama_lengkap as nama_wali_kelas,
                         t.tahun, t.semester
                  FROM " . $instance->table . " k
                  JOIN guru g ON k.guru_wali_id = g.guru_id
                  JOIN tahun_ajaran t ON k.tahun_id = t.tahun_id
                  ORDER BY k.tingkat ASC, k.nama_kelas ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getByWaliKelas($guru_id)
    {
        $instance = new static();
        $query    = "SELECT * FROM " . $instance->table . " WHERE guru_wali_id = :guru_id LIMIT 1";
        $stmt     = $instance->conn->prepare($query);
        $stmt->execute([':guru_id' => $guru_id]);
        return $stmt->fetch();
    }
}
