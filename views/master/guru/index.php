<?php ob_start(); ?>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['success']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['error']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Guru</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/guru/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Guru
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>NIP / Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role (User)</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['gurus'])): ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data guru.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($data['gurus'] as $index => $guru): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($guru['nip']); ?></td>
                    <td><?php echo htmlspecialchars($guru['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($guru['role'] ?? 'Guru'); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL ?>/guru/edit?id=<?php echo $guru['guru_id'] ?>"
                            class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="<?php echo BASE_URL ?>/guru/delete?id=<?php echo $guru['guru_id'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->


<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>