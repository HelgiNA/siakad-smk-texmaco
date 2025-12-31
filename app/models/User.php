<?php
// app/models/User.php

namespace App\Models;

use PDO;
use PDOException;

require_once __DIR__ . '/../Models/Model.php';

class User extends Model
{
    protected $table      = "users";
    protected $primaryKey = "user_id";

    // Fungsi 1: Mengambil data user untuk Login
    // Sesuai Sequence Diagram SIA-001
    public static function getCredential($username)
    {
        $instance = new static();
        try {
            $query = "SELECT * FROM " . $instance->table . " WHERE username = :username LIMIT 1";
            $stmt  = $instance->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            // Mengembalikan array data user (id, username, password hash, role)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
