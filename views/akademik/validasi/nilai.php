<?php
/**
 * SIA-008 V2: Validasi Nilai - Rekap Wali Kelas
 * 
 * Tampilkan HANYA nilai yang status = 'Submitted'
 * Wali Kelas bisa approve (Final) atau reject (Revisi + Catatan Mandatory)
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
                        <strong>SIA-008 V2 - Alur Validasi:</strong> 
                        <ul class="mb-0">
                            <li><strong>Data Ditampilkan:</strong> Hanya nilai dengan status "Submitted" (Draft tidak perlu validasi)</li>
                            <li><strong>Tombol Setuju:</strong> Ubah status jadi "Final" (terkunci, siap rapor)</li>
                            <li><strong>Tombol Revisi:</strong> Ubah status jadi "Revisi" + <strong>WAJIB</strong> isi catatan feedback</li>
                            <li>Catatan akan dilihat Guru Mapel - mereka perlu tahu apa yang harus diperbaiki</li>
                        </ul>
                    </div>

                    <!-- Tabel Rekap Nilai -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelValidasi">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Nama Siswa</th>
                                    <th width="12%">Mata Pelajaran</th>
                                    <th width="10%">Guru Mapel</th>
                                    <th width="8%">Tugas</th>
                                    <th width="8%">UTS</th>
                                    <th width="8%">UAS</th>
                                    <th width="10%">Nilai Akhir</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($nilai)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            <em>✓ Tidak ada data nilai yang perlu divalidasi (Semua sudah disetujui atau belum ada pengajuan)</em>
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
                                            <td>
                                                <!-- SIA-008 V2: Form Inline untuk Final -->
                                                <form method="POST" action="<?php echo BASE_URL; ?>/validasi-nilai/proses" style="display:inline;" onsubmit="return confirm('Setujui nilai ini?');">
                                                    <input type="hidden" name="nilai_id" value="<?php echo htmlspecialchars($n['nilai_id']); ?>">
                                                    <input type="hidden" name="keputusan" value="Final">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui & Finalisasi">
                                                        <i class="fas fa-check"></i> Setuju
                                                    </button>
                                                </form>
                                                
                                                <!-- SIA-008 V2: Modal trigger untuk Revisi (dengan catatan) -->
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalRevisi<?php echo $n['nilai_id']; ?>" title="Tolak & minta revisi">
                                                    <i class="fas fa-edit"></i> Revisi
                                                </button>

                                                <!-- Modal: Form Revisi dengan Catatan Mandatory -->
                                                <div class="modal fade" id="modalRevisi<?php echo $n['nilai_id']; ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning text-white">
                                                                <h5 class="modal-title">Catatan Revisi Nilai</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="<?php echo BASE_URL; ?>/validasi-nilai/proses">
                                                                <div class="modal-body">
                                                                    <p><strong>Siswa:</strong> <?php echo htmlspecialchars($n['nama_lengkap']); ?></p>
                                                                    <p><strong>Mapel:</strong> <?php echo htmlspecialchars($n['nama_mapel']); ?></p>
                                                                    <p><strong>Guru:</strong> <?php echo htmlspecialchars($n['nama_guru']); ?></p>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="catatan<?php echo $n['nilai_id']; ?>">
                                                                            <strong>Catatan Revisi <span class="text-danger">*</span></strong>
                                                                            <small class="d-block text-muted mt-1">
                                                                                Berikan feedback spesifik untuk Guru Mapel. 
                                                                                Contoh: "Nilai Si Budi masih 0, lengkapi terlebih dahulu"
                                                                            </small>
                                                                        </label>
                                                                        <textarea 
                                                                            class="form-control" 
                                                                            id="catatan<?php echo $n['nilai_id']; ?>" 
                                                                            name="catatan_revisi" 
                                                                            rows="4" 
                                                                            placeholder="Jelaskan apa yang perlu diperbaiki..."
                                                                            required>
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="nilai_id" value="<?php echo htmlspecialchars($n['nilai_id']); ?>">
                                                                    <input type="hidden" name="keputusan" value="Revisi">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-warning">
                                                                        <i class="fas fa-times"></i> Tolak & Beri Catatan
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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

