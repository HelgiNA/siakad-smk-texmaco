<?php
// views/master/tahun_ajaran/index.php
ob_start(); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Tahun Ajaran</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/tahun-ajaran/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Tahun Ajaran
            </a>
        </div>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th style="width: 200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["tahun_ajaran"])): ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($data["tahun_ajaran"] as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row["tahun"]); ?></td>
                    <td><?php echo htmlspecialchars($row["semester"]); ?></td>
                    <td>
                        <?php if ($row["is_active"]): ?>
                        <span class="badge bg-success">AKTIF</span>
                        <?php else: ?>
                        <span class="badge bg-secondary">Non-Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$row["is_active"]): ?>
                        <a href="<?php echo BASE_URL; ?>/tahun-ajaran/activate?id=<?php echo $row[
    "tahun_id"
]; ?>"
                            class="btn btn-sm btn-success" title="Aktifkan ini">
                            <i class="bi bi-checks"></i> Aktifkan
                        </a>
                        <?php endif; ?>

                        <a href="<?php echo BASE_URL; ?>/tahun-ajaran/delete?id=<?php echo $row[
    "tahun_id"
]; ?>"
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
require_once __DIR__ . "/../../layouts/main.php";

?>
