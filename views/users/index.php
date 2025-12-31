<?php ob_start(); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Akun Pengguna</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL ?>/users/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah User Baru
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>

                    <th>Username</th>
                    <th>Role (Peran)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo htmlspecialchars($user['username']) ?></td>
                    <td>
                        <?php if ($user['role'] == 'Admin'): ?>
                        <span class="badge bg-primary">Admin</span>
                        <?php elseif ($user['role'] == 'Guru'): ?>
                        <span class="badge bg-warning">Guru</span>
                        <?php elseif ($user['role'] == 'Siswa'): ?>
                        <span class="badge bg-info">Siswa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo BASE_URL ?>/users/edit?id=<?php echo $user['user_id'] ?>"
                            class="btn btn-xs btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="<?php echo BASE_URL ?>/users/delete?id=<?php echo $user['user_id'] ?>"
                            class="btn btn-xs btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>