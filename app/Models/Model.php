<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Model
{
    protected $table;
    protected $primaryKey = "id";
    protected $conn;

    protected $wheres = [];
    protected $bindings = [];
    protected $query;
    protected static $instances = [];

    // Siapkan format response default
    public $response = [
        "status" => false,
        "lastInsertId" => null,
        "error" => null,
    ];

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public static function getAll()
    {
        $instance = new static();
        $stmt = $instance->conn->prepare("SELECT * FROM " . $instance->table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $instance = new static();
        $stmt = $instance->conn->prepare(
            "SELECT * FROM " .
                $instance->table .
                " WHERE " .
                $instance->primaryKey .
                " = :_primary_id"
        );
        $stmt->bindParam(":_primary_id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function where($column, $operator, $value = null)
    {
        $instance = new static();

        if ($value === null) {
            $value = $operator;
            $operator = "=";
        }

        // GANTI INI: Jangan pakai :_primary_id, pakai ? saja
        $instance->wheres[] = "$column $operator ?";
        $instance->bindings[] = $value;

        return $instance;
    }

    public function get()
    {
        try {
            $query = "SELECT * FROM " . $this->table;

            // Jika ada where, kita tempelkan pakai 'AND'
            if (!empty($this->wheres)) {
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
        var_dump("Gagal menyimpan data!");
        var_dump($exception->getMessage());
        die();
    }

    // Fungsi CRUD (Create, Read, Update, Delete)
    // Untuk Memenuhi Sequence Diagram SIA-003 dan SIA-011

    // Fungsi 1: Create($data)
    // 1. Tambahkan Method Helper Transaksi Global

    public static function getInstance()
    {
        $className = static::class; // Mendapatkan nama kelas yang memanggil (misal: 'User')

        if (!isset(self::$instances[$className])) {
            // Membuat instance baru spesifik untuk kelas tersebut
            self::$instances[$className] = new static();
        }

        return self::$instances[$className];
    }

    public static function beginTransaction()
    {
        // Menggunakan instance yang sudah terdaftar di static::$instances
        $instance = static::getInstance();
        if (!$instance->conn->inTransaction()) {
            return $instance->conn->beginTransaction();
        }
        return false;
    }

    public static function commit()
    {
        $instance = static::getInstance();
        if ($instance->conn->inTransaction()) {
            return $instance->conn->commit();
        }
        return false;
    }

    public static function rollBack()
    {
        $instance = static::getInstance();
        if ($instance->conn->inTransaction()) {
            return $instance->conn->rollBack();
        }
        return false;
    }

    public static function isInsideTransaction()
    {
        return self::getInstance()->conn->inTransaction();
    }

    // 2. Update Method create() agar "Sadar Transaksi"
    public static function create($data)
    {
        // 1. Ambil instance tunggal (Singleton), jangan 'new' terus menerus
        $instance = static::getInstance();

        // 2. Cek status transaksi pada koneksi yang sama
        $isInsideTransaction = $instance->conn->inTransaction();

        try {
            // Hanya mulai transaksi jika BELUM ada transaksi berjalan
            if (!$isInsideTransaction) {
                $instance->conn->beginTransaction();
            }

            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $query =
                "INSERT INTO " .
                $instance->table .
                " ($columns) VALUES ($placeholders)";

            $stmt = $instance->conn->prepare($query);
            $stmt->execute($data);
            $lastInsertId = $instance->conn->lastInsertId();

            // Hanya commit jika transaksi ini milik method ini sendiri
            if (!$isInsideTransaction) {
                $instance->conn->commit();
            }

            $instance->response["status"] = true;
            $instance->response["lastInsertId"] = $lastInsertId;
            return $instance->response;
        } catch (PDOException $e) {
            // Rollback hanya jika transaksi dimulai di sini
            if (!$isInsideTransaction && $instance->conn->inTransaction()) {
                $instance->conn->rollBack();
            }

            $instance->response["status"] = false;
            $instance->response["error"] = $e->getMessage();
            return $instance->response;
        }
    }

    /**
     * Membuat banyak data sekaligus (Bulk Insert)
     * Menggunakan satu transaksi untuk semua data demi performa dan integritas.
     * * @param array $dataArray Array of associative arrays
     * @return array Response standard
     */
    public static function createMany(array $dataArray)
    {
        // 1. Ambil instance tunggal (Singleton), jangan 'new' terus menerus
        $instance = static::getInstance();

        // 2. Cek status transaksi pada koneksi yang sama
        $isInsideTransaction = $instance->conn->inTransaction();

        $insertedIds = [];

        // Cek jika data kosong
        if (empty($dataArray)) {
            return [
                "status" => false,
                "error" => "Data kosong, tidak ada yang diproses.",
            ];
        }

        try {
            // Mulai transaksi di awal loop
            // Hanya mulai transaksi jika BELUM ada transaksi berjalan
            if (!$isInsideTransaction) {
                $instance->conn->beginTransaction();
            }

            foreach ($dataArray as $data) {
                $columns = implode(", ", array_keys($data));
                $placeholders = ":" . implode(", :", array_keys($data));

                $query = "INSERT INTO $instance->table ($columns) VALUES ($placeholders)";

                $stmt = $instance->conn->prepare($query);
                $stmt->execute($data);

                // Simpan ID dari setiap row yang berhasil masuk
                $insertedIds[] = $instance->conn->lastInsertId();
            }

            // Jika loop selesai tanpa error, commit semua perubahan
            // Hanya commit jika transaksi ini milik method ini sendiri
            if (!$isInsideTransaction) {
                $instance->conn->commit();
            }

            $instance->response["status"] = true;
            $instance->response["message"] =
                count($insertedIds) . " data berhasil disimpan.";
            $instance->response["ids"] = $insertedIds; // Opsional: mengembalikan ID yang baru dibuat

            return $instance->response;
        } catch (PDOException $e) {
            // Rollback hanya jika transaksi dimulai di sini
            if (!$isInsideTransaction && $instance->conn->inTransaction()) {
                $instance->conn->rollBack();
            }

            $instance->response["status"] = false;
            $instance->response["error"] = $e->getMessage();

            return $instance->response;
        }
    }

    // Lakukan hal yang sama (cek $isInsideTransaction) untuk createMany() juga!

    // Fungsi 2: Update($id, $data)
    public static function update($id, $data)
    {
        // 1. Ambil instance tunggal (Singleton), jangan 'new' terus menerus
        $instance = static::getInstance();

        // 2. Cek status transaksi pada koneksi yang sama
        $isInsideTransaction = $instance->conn->inTransaction();

        try {
            if (!$isInsideTransaction) {
                $instance->conn->beginTransaction();
            }

            $sets = [];
            foreach (array_keys($data) as $key) {
                $sets[] = "$key = :$key";
            }
            $setString = implode(", ", $sets);

            $query = "UPDATE $instance->table SET $setString WHERE $instance->primaryKey = $id";

            $stmt = $instance->conn->prepare($query);
            $stmt->execute($data);

            // Hanya commit jika transaksi ini milik method ini sendiri
            if (!$isInsideTransaction) {
                $instance->conn->commit();
            }

            $instance->response["status"] = true;

            return $instance->response;
        } catch (PDOException $e) {
            if (!$isInsideTransaction && $instance->conn->inTransaction()) {
                $instance->conn->rollBack();
            }

            // Isi response gagal
            $instance->response["status"] = false;
            $instance->response["error"] = $e->getMessage(); // Pesan error asli dari SQL

            return $instance->response;
        }
    }

    // Fungsi 3: Delete($id)
    public static function delete($id)
    {
        // 1. Ambil instance tunggal (Singleton), jangan 'new' terus menerus
        $instance = static::getInstance();

        // 2. Cek status transaksi pada koneksi yang sama
        $isInsideTransaction = $instance->conn->inTransaction();
        try {
            if (!$isInsideTransaction) {
                $instance->conn->beginTransaction();
            }

            $query = "DELETE FROM $instance->table WHERE $instance->primaryKey = :_primary_id";
            $stmt = $instance->conn->prepare($query);

            $stmt->bindParam(":_primary_id", $id);
            $stmt->execute();

            if (!$isInsideTransaction) {
                $instance->conn->commit();
            }

            $instance->response["status"] = true;
            return $instance->response;
        } catch (PDOException $e) {
            if (!$isInsideTransaction && $instance->conn->inTransaction()) {
                $instance->conn->rollBack();
            }

            $instance->response["status"] = false;
            $instance->response["error"] = $e->getMessage(); // Pesan error asli dari SQL
            return $instance->response;
        }
    }
}
