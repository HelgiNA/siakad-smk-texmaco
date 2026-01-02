<?php
    // views/master/kelas/index.php
    ob_start();
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Kelas</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/kelas/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Tingkat</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['kelas'])): ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada data.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($data['kelas'] as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['tingkat']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
                    <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_wali_kelas']); ?></td>
                    <td><?php echo htmlspecialchars($row['tahun'] . ' - ' . $row['semester']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL ?>/kelas/edit?id=<?php echo $row['kelas_id'] ?>"
                            class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="<?php echo BASE_URL ?>/kelas/delete?id=<?php echo $row['kelas_id'] ?>"
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