<?php ob_start(); ?>

<div class="card">
    <div class="card-header">Edit Produk</div>
    <div class="card-body">
        <form action="index.php?page=product_update&id=<?php echo $product['id'];?>" method="POST">
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="name" class="form-control" value="<?php echo $product['name'];?>" required>
            </div>
            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="price" class="form-control" value="<?php echo $product['price'];?>" required>
            </div>
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stock" class="form-control" value="<?php echo $product['stock'];?>" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control"><?php echo $product['description'];?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="?page=product" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>