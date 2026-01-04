<?php ob_start(); ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Plotting Siswa (Rombongan
                    Belajar)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($kelas)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>Belum ada data kelas. Silakan tambahkan data kelas terlebih
                    dahulu di Master Data.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th class="text-center">Jumlah Siswa</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kelas as $index => $row): ?>
                            <tr>
                                <td><?php echo $index + 1 ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['nama_kelas']) ?></td>
                                <td><?php echo htmlspecialchars($row['tingkat']) ?></td>
                                <td><?php echo htmlspecialchars($row['jurusan']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-info rounded-pill fs-6 px-3">
                                        <?php echo $row['jumlah_siswa'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo BASE_URL ?>/plotting/manage?id=<?php echo $row['kelas_id'] ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-gear-fill me-1"></i> Atur Anggota
                                    </a>
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
require_once __DIR__ . '/../../layouts/main.php';
?>