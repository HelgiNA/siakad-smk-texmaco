<?php
// app/Models/Guru.php

namespace App\Models;

use PDO;
use PDOException;

require_once __DIR__ . '/Model.php';

class Guru extends Model
{
    protected $table      = 'guru';
    protected $primaryKey = 'guru_id';

    public static function getAllWithUser()
    {
        $instance = new static();
        try {
            // Join dengan tabel users untuk mengambil data login (username/email)
            // Namun di tabel user hanya ada username.
            $query = "SELECT g.*, u.username, u.role
                      FROM " . $instance->table . " g
                      JOIN users u ON g.user_id = u.user_id
                      ORDER BY g.nama_lengkap ASC";

            $stmt = $instance->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function findByUserId($user_id)
    {
        $instance = new static();
        $query    = "SELECT * FROM " . $instance->table . " WHERE user_id = :user_id LIMIT 1";
        $stmt     = $instance->conn->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Hitung total guru yang aktif (status_aktif = 1 di tabel users)
     * Digunakan untuk Dashboard Admin/Kepsek
     */
    public static function countActive()
    {
        $instance = new static();
        $query = "SELECT COUNT(g.guru_id) as total
                  FROM " . $instance->table . " g
                  JOIN users u ON g.user_id = u.user_id
                  WHERE u.status_aktif = 1";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
}
