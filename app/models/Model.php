<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $conn;

    public $wheres   = [];
    public $bindings = [];
    public $query;

    // Siapkan format response default
    public $response = [
        'status' => false,
        'data'   => null,
        'error'  => null,
    ];

    public function __construct()
    {
        $database   = new Database();
        $this->conn = $database->getConnection();
    }

    public static function getAll()
    {
        $instance = new static();
        $stmt     = $instance->conn->prepare("SELECT * FROM " . $instance->table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $instance = new static();
        $stmt     = $instance->conn->prepare("SELECT * FROM " . $instance->table . " WHERE " . $instance->primaryKey . " = :_primary_id");
        $stmt->execute(['_primary_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function where($column, $operator, $value = null)
    {
        $instance = new static();

        if ($value === null) {
            $value    = $operator;
            $operator = '=';
        }

        // GANTI INI: Jangan pakai :_primary_id, pakai ? saja
        $instance->wheres[]   = "$column $operator ?";
        $instance->bindings[] = $value;

        return $instance;
    }

    public function get()
    {
        try {
            $query = "SELECT * FROM " . $this->table;

            // Jika ada where, kita tempelkan pakai 'AND'
            if (! empty($this->wheres)) {
                $query .= " WHERE " . implode(" AND ", $this->wheres);
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($this->bindings);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->error($e);
            return [];
        }
    }

    public function first()
    {
        return $this->get()[0] ?? null;
    }

    private function error($exception)
    {
        var_dump('Gagal menyimpan data!');
        var_dump($exception->getMessage());
        die();
    }

    // Fungsi CRUD (Create, Read, Update, Delete)
    // Untuk Memenuhi Sequence Diagram SIA-003 dan SIA-011

    // Fungsi 1: Create($data)
// Fungsi 1: Create($data)
    public static function create($data)
    {
        $instance = new static();

        try {
            $instance->conn->beginTransaction();

            $columns      = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));

            $query = "INSERT INTO " . $instance->table . " ($columns) VALUES ($placeholders)";

            $stmt = $instance->conn->prepare($query);
            $stmt->execute($data);

            // Ambil ID terakhir
            $data = $instance->find($instance->conn->lastInsertId());
            $instance->conn->commit();

            // Isi response sukses
            $instance->response['status'] = true;
            $instance->response['data']   = $data;

            return $instance->response;

        } catch (PDOException $e) {
            $instance->conn->rollBack();

            // Isi response gagal
            $instance->response['status'] = false;
            $instance->response['error']  = $e->getMessage(); // Pesan error asli dari SQL

            return $instance->response;
        }
    }

    // Fungsi 2: Update($id, $data)
    public static function update($id, $data)
    {
        $instance = new static();

        try {
            $instance->conn->beginTransaction();

            $sets = [];
            foreach (array_keys($data) as $key) {
                $sets[] = "$key = :$key";
            }
            $setString = implode(", ", $sets);

            $query               = "UPDATE " . $instance->table . " SET $setString WHERE " . $instance->primaryKey . " = :_primary_id";
            $data['_primary_id'] = $id;

            $stmt = $instance->conn->prepare($query);
            $stmt->execute($data);

            $instance->conn->commit();
            $dataNew = $instance->find($id);

            $instance->response['status'] = true;
            $instance->response['data']   = $dataNew;

            return $instance->response;
        } catch (PDOException $e) {
            $instance->conn->rollBack();

            // Isi response gagal
            $instance->response['status'] = false;
            $instance->response['error']  = $e->getMessage(); // Pesan error asli dari SQL

            return $instance->response;
        }
    }

    // Fungsi 3: Delete($id)
    public static function delete($id)
    {
        $instance = new static();
        try {
            $instance->conn->beginTransaction();

            $query = "DELETE FROM " . $instance->table . " WHERE " . $instance->primaryKey . " = :_primary_id";
            $stmt  = $instance->conn->prepare($query);

            $stmt->bindParam(":_primary_id", $id);
            $stmt->execute();

            $instance->conn->commit();
            return true;
        } catch (PDOException $e) {
            $instance->conn->rollBack();
            $instance->error($e);
            return false;
        }
    }
}