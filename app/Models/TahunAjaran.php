<?php
// app/Models/TahunAjaran.php

namespace App\Models;

use PDOException;

require_once __DIR__ . '/Model.php';

class TahunAjaran extends Model
{
    protected $table      = 'tahun_ajaran';
    protected $primaryKey = 'tahun_id';

    public static function getActive()
    {
        $instance = new static();
        return $instance::where('is_active', 1)->first();
    }

    public static function activateSemester($id)
    {
        $instance = new static();
        try {
            $instance->conn->beginTransaction();

            // 1. Nonaktifkan semua
            $queryReset = "UPDATE " . $instance->table . " SET is_active = 0";
            $stmtReset  = $instance->conn->prepare($queryReset);
            $stmtReset->execute();

            // 2. Aktifkan yang dipilih
            $querySet = "UPDATE " . $instance->table . " SET is_active = 1 WHERE " . $instance->primaryKey . " = :id";
            $stmtSet  = $instance->conn->prepare($querySet);
            $stmtSet->execute(['id' => $id]);

            $instance->conn->commit();
            return true;
        } catch (PDOException $e) {
            $instance->conn->rollBack();
            return false;
        }
    }
}