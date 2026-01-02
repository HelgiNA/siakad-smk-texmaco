<?php ob_start(); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Siswa</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/siswa/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Siswa Baru
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Identitas (NIS/NISN)</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Status Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $student["nis"] .
                        " | " .
                        $student["nisn"]; ?></td>
                    <td><?php echo $student["nama_lengkap"]; ?></td>
                    <td>
                        <span class="badge bg-info">
                            <?php echo $student["nama_kelas"] ??
                                "Tanpa Kelas"; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($student["status_aktif"] === 1): ?>
                        <span class="badge bg-success">Aktif</span>
                        <?php elseif ($student["status_aktif"] === 0): ?>
                        <span class="badge bg-danger">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/siswa/edit?id=<?php echo $student[
    "siswa_id"
]; ?>"
                            class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="<?php echo BASE_URL; ?>/siswa/delete?id=<?php echo $student[
    "siswa_id"
]; ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
