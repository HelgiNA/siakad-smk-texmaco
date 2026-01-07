<?php ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Siswa - Cetak Rapor</h3>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>Status Nilai</th>
                    <th style="width: 140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($list)): ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada siswa untuk Anda sebagai Wali Kelas.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($list as $i => $row): ?>
                <?php $s = $row['siswa']; ?>
                <?php $k = $row['kelas']; ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($s['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($s['nisn'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($k['nama_kelas']); ?></td>
                    <td>
                        <?php if ($row['status'] === 'Lengkap'): ?>
                            <span class="badge bg-success">Lengkap</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Belum</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'Lengkap'): ?>
                        <a href="<?php echo BASE_URL; ?>/rapor/print?id=<?php echo $s['siswa_id']; ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="bi bi-printer"></i> Cetak
                        </a>
                        <?php else: ?>
                        <button class="btn btn-secondary btn-sm" disabled><i class="bi bi-printer"></i> Cetak</button>
                        <?php endif; ?>
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
