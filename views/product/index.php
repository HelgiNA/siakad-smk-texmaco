<?php ob_start(); ?>

<div class="d-flex justify-content-between mb-3">
    <h2><?php echo $title; ?></h2>
    <a href="?page=product_create" class="btn btn-primary">Tambah Produk</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $index => $p): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td>Rp <?php echo number_format($p['price']); ?></td>
            <td><?php echo $p['stock']; ?></td>
            <td>
                <a href="?page=product_edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?page=product_delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>