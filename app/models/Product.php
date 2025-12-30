<?php
namespace App\Models;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/Model.php';

class Product extends Model
{
    protected $table = 'products';

    // CREATE (Tambah data)
    public function create($data)
    {
        $sql  = "INSERT INTO products (name, price, stock, description) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['stock'],
            $data['description'],
        ]);
    }

    // UPDATE (Edit data)
    public function update($id, $data)
    {
        $sql  = "UPDATE products SET name = ?, price = ?, stock = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['stock'],
            $data['description'],
            $id,
        ]);
    }

    // DELETE (Hapus data)
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
