<?php
ob_start(); ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2"></i> Jadwal Mengajar Hari Ini</h5>
                <small
                    class="text-white-50"><?php echo $hari; ?>, <?php echo date(
    "d F Y",
    strtotime($tanggal)
); ?></small>
            </div>
            <div class="card-body">
                <?php showAlert(); ?>
                <?php if ($jadwal): ?>
                <!-- html... -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Jam</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Status Absensi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwal as $j): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo date(
                                            "H:i",
                                            strtotime($j["jam_mulai"])
                                        ); ?>
                                        -<?php echo date(
                                            "H:i",
                                            strtotime($j["jam_selesai"])
                                        ); ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars(
                                    $j["nama_kelas"]
                                ); ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars(
                                        $j["nama_mapel"]
                                    ); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars(
                                        $j["kode_mapel"]
                                    ); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = "bg-secondary";
                                    if ($j["status_absensi"] === "Draft") {
                                        $badgeClass = "bg-warning text-dark";
                                    } elseif (
                                        $j["status_absensi"] === "Valid"
                                    ) {
                                        $badgeClass = "bg-success";
                                    } elseif (
                                        $j["status_absensi"] === "Rejected"
                                    ) {
                                        $badgeClass = "bg-danger";
                                    }
                                    ?>
                                    <span
                                        class="badge<?php echo $badgeClass; ?>"><?php echo $j[
    "status_absensi"
]; ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if (
                                        $j["status_absensi"] ===
                                            "Belum Input" ||
                                        $j["status_absensi"] === "Rejected"
                                    ): ?>
                                    <a href="<?php echo BASE_URL; ?>/absensi/create?jadwal_id=<?php echo $j[
    "jadwal_id"
]; ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square me-1"></i> Input Absensi
                                    </a>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="bi bi-check-circle me-1"></i> Sudah Input
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
