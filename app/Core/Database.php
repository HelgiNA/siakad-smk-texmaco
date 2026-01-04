<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;

    // Simpan koneksi secara statis agar tidak terbuat berulang kali
    private static $instance = null;

    public function getConnection()
    {
        // Jika koneksi sudah ada, langsung kembalikan yang sudah ada
        if (self::$instance !== null) {
            return self::$instance;
        }

        try {
            self::$instance = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [
                    // Tambahkan opsi agar koneksi stabil
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false, // Opsional, tergantung kebutuhan server
                ]
            );
        } catch (PDOException $exception) {
            // Pastikan fungsi redirect() tersedia di helper Anda
            redirect("/login")->with([
                "error",
                "Connection error: " . $exception->getMessage(),
            ]);
            exit();
        }

        return self::$instance;
    }
}
