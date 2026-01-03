<?php
/**
 * PHASE 4: Validasi Nilai - Rekap Wali Kelas
 * 
 * Wali Kelas melihat rekap nilai siswa di kelas ampuannya
 * Bisa approve (Final) atau tolak (Revisi)
 * Pre-check: Nilai tidak boleh 0 saat finalisasi
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle"></i> Validasi Nilai - Wali Kelas
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Info Header -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Kelas Ampuan:</strong> <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($tahunAjaran['tahun'] . ' - ' . $tahunAjaran['semester']); ?>
                        </div>
                    </div>

                    <!-- Alert Info -->
                    <div class="alert alert-info" role="alert">
                        <strong>Catatan:</strong> 
                        <ul>
                            <li>Review nilai yang diinput guru mapel</li>
                            <li>Jika ada nilai kosong (0), Anda tidak bisa finalisasi. Hubungi guru mapel.</li>
                            <li>Status <strong>Final</strong>: Data terkunci, guru tidak bisa edit lagi</li>
                            <li>Status <strong>Revisi</strong>: Guru akan melakukan perbaikan</li>
                        </ul>
                    </div>

                    <!-- Tabel Rekap Nilai -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelValidasi">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Nama Siswa</th>
                                    <th width="15%">Mata Pelajaran</th>
                                    <th width="10%">Guru Mapel</th>
                                    <th width="8%">Tugas</th>
                                    <th width="8%">UTS</th>
                                    <th width="8%">UAS</th>
                                    <th width="10%">Nilai Akhir</th>
                                    <th width="12%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($nilai)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            <em>Belum ada data nilai untuk divalidasi</em>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($nilai as $n): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($n['nama_lengkap']); ?></strong><br>
                                                <small class="text-muted">NISN: <?php echo htmlspecialchars($n['nisn']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($n['nama_mapel']); ?></td>
                                            <td><?php echo htmlspecialchars($n['nama_guru']); ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-info"><?php echo number_format($n['nilai_tugas'], 2); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info"><?php echo number_format($n['nilai_uts'], 2); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info"><?php echo number_format($n['nilai_uas'], 2); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <strong><?php echo number_format($n['nilai_akhir'], 2); ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $badgeClass = match($n['status_validasi']) {
                                                    'Draft' => 'badge-warning',
                                                    'Revisi' => 'badge-danger',
                                                    'Final' => 'badge-success',
                                                    default => 'badge-secondary'
                                                };
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo htmlspecialchars($n['status_validasi']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($n['status_validasi'] !== 'Final'): ?>
                                                    <form method="POST" action="<?php echo BASE_URL; ?>/validasi-nilai/proses" style="display:inline;">
                                                        <input type="hidden" name="nilai_id" value="<?php echo htmlspecialchars($n['nilai_id']); ?>">
                                                        <input type="hidden" name="keputusan" value="Final">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui Nilai">
                                                            <i class="fas fa-check"></i> Setuju
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="<?php echo BASE_URL; ?>/validasi-nilai/proses" style="display:inline;">
                                                        <input type="hidden" name="nilai_id" value="<?php echo htmlspecialchars($n['nilai_id']); ?>">
                                                        <input type="hidden" name="keputusan" value="Revisi">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak dan minta revisi">
                                                            <i class="fas fa-times"></i> Revisi
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Terkunci</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk enhance UX -->
<script>
    $(function() {
        $('#tabelValidasi').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });
    });
</script>
