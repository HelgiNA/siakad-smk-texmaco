<?php
    // views/master/mapel/index.php
    ob_start();
?>

<?php if (isset($_SESSION['flash']['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['flash']['success']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php unset($_SESSION['flash']['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['flash']['error']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php unset($_SESSION['flash']['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Mata Pelajaran</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/mapel/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Mapel
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Kode Mapel</th>
                    <th>Nama Mapel</th>
                    <th>KKM</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['mapel'])): ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($data['mapel'] as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['kode_mapel']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($row['nama_mapel']); ?></td>
                    <td><?php echo htmlspecialchars($row['kkm']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL ?>/mapel/edit?id=<?php echo $row['mapel_id'] ?>"
                            class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="<?php echo BASE_URL ?>/mapel/delete?id=<?php echo $row['mapel_id'] ?>"
                            class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>