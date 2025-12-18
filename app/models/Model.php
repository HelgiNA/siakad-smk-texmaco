<?php
namespace App\Models; // Ini Namespace

require_once __DIR__ . '/../../config/database.php';
use Database; // Kita 'use' class Database dari file config
use PDO;

class Model
{
    protected $table;
    public $conn;

    public function __construct()
    {
        $database   = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}