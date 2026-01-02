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
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $exception) {
            redirect('/login')->with([
                "error",
                "Connection error: " . $exception->getMessage(),
            ]);
        }
        return $this->conn;
    }
}
