<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private $host = DB_HOST;
    private $port = DB_PORT; // 1. Tambahkan properti Port
    private $db_name = DB_NAME;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;

    private static $instance = null;

    public function getConnection()
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        try {
            // 2. Tambahkan ";port=" . $this->port ke dalam string koneksi
            self::$instance = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false,
                ]
            );
        } catch (PDOException $exception) {
            // Matikan redirect, paksa error muncul di layar
            die("<h1>GAGAL KONEKSI DATABASE:</h1><br>" . $exception->getMessage());
        }

        return self::$instance;
    }
}
