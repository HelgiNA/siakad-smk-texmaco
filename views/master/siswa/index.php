<?php ob_start(); ?>

<?php if (isset($_SESSION['flash']['success'])): ?>
<div class="alert alert-success"><?php echo $_SESSION['flash']['success'];unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
<div class="alert alert-danger"><?php echo $_SESSION['flash']['error'];unset($_SESSION['flash']['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Siswa</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL ?>/siswa/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Siswa Baru
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>id</th>
                    <th>User Id</th>
                    <th>Kelas Id</th>
                    <th>NIS</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $student['siswa_id'] ?></td>
                    <td><?php echo $student['user_id'] ?></td>
                    <td><?php echo $student['kelas_id'] ?></td>
                    <td><?php echo $student['nis'] ?></td>
                    <td><?php echo $student['nisn'] ?></td>
                    <td><?php echo $student['nama_lengkap'] ?></td>
                    <td><?php echo $student['tanggal_lahir'] ?></td>
                    <td><?php echo $student['alamat'] ?></td>
                    <td><?php echo htmlspecialchars($student['username']) ?></td>
                    <td>
                        <?php if ($student['role'] == 'Admin'): ?>
                        <span class="badge bg-primary">Admin</span>
                        <?php elseif ($student['role'] == 'Guru'): ?>
                        <span class="badge bg-warning">Guru</span>
                        <?php elseif ($student['role'] == 'Siswa'): ?>
                        <span class="badge bg-info">Siswa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo BASE_URL ?>/siswa/edit?id=<?php echo $student['siswa_id'] ?>"
                            class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <a href="<?php echo BASE_URL ?>/siswa/delete?id=<?php echo $student['siswa_id'] ?>"
                            class="btn btn-xs btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                            <i class="fas fa-trash"></i> Hapus
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
require_once __DIR__ . '/../../layouts/main.php';
?>