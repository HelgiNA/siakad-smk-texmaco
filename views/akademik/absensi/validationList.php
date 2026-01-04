<?php
ob_start(); ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle-fill me-2"></i> Validasi Absensi</h5>
                <small class="text-white-50">
                    <?php if ($isWaliKelas): ?>
                    Wali Kelas:<?php echo htmlspecialchars(
                        $kelas["nama_kelas"]
                    ); ?>
                    <?php endif; ?>
                </small>
            </div>
            <div class="card-body">
                <?php
                showAlert();
                if (!empty($pending)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru Pengampu</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending as $row): ?>
                            <tr>
                                <td><?php echo date(
                                    "d M Y",
                                    strtotime($row["tanggal"])
                                ); ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo date(
                                            "H:i",
                                            strtotime($row["jam_mulai"])
                                        ); ?>
                                        -<?php echo date(
                                            "H:i",
                                            strtotime($row["jam_selesai"])
                                        ); ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars(
                                    $row["nama_mapel"]
                                ); ?></td>
                                <td><?php echo htmlspecialchars(
                                    $row["nama_guru"]
                                ); ?></td>
                                <td><span class="badge bg-warning text-dark">Draft</span></td>
                                <td class="text-center">
                                    <a href="<?php echo BASE_URL; ?>/absensi/validasi/review?absensi_id=<?php echo $row[
    "absensi_id"
]; ?>"
                                        class="btn btn-sm btn-success">
                                        <i class="bi bi-eye-fill me-1"></i> Periksa & Validasi
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
