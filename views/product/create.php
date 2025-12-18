<?php ob_start(); ?>

<div class="card">
    <div class="card-header">Tambah Produk Baru</div>
    <div class="card-body">
        <form action="index.php?page=product_store" method="POST">
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="?page=product" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>