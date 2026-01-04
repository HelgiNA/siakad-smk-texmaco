<?php
/**
 * SIA-006 V2: Input Nilai - Form Matriks
 * 
 * Guru memilih kelas & mapel → tampilkan tabel siswa dengan input nilai
 * Guru isi nilai tugas, UTS, UAS → sistem hitung otomatis Nilai Akhir
 * 
 * Alur:
 * 1. "Simpan Draft" → Status='Draft', Form tetap editable
 * 2. "Ajukan Validasi" → Check kelengkapan → Status='Submitted', Form read-only
 * 3. Jika ditolak Wali → Status='Revisi', Form editable kembali + tampil catatan
 * 
 * Logic View:
 * - Baris = Nama Siswa
 * - Kolom = Input Tugas, Input UTS, Input UAS, Nilai Akhir (readonly, dihitung saat submit)
 * - Jika data nilai sudah ada, isi input dengan data yang ada
 * - Jika belum ada, isi 0
 * - Jika status='Submitted' atau 'Final': Form READ-ONLY
 * - Jika status='Revisi': Form editable + tampil catatan feedback
 */
?>
<?php ob_start(); ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $title; ?></h3>
                    <div class="card-tools pull-right">
                        <a href="<?php echo BASE_URL; ?>/nilai/create" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali Pilih Kelas
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- SIA-006 V2: Alert Catatan Revisi (jika ada feedback dari Wali) -->
                    <?php
                    // Cek apakah ada nilai dengan status 'Revisi' dan catatan revisi
                    showAlert();
                    $hasRevisi = false;
                    $catatanRevisi = [];
                    if (!empty($nilaiMap)) {
                        foreach ($nilaiMap as $n) {
                            if ($n['status_validasi'] === 'Revisi' && !empty($n['catatan_revisi'])) {
                                $hasRevisi = true;
                                $catatanRevisi[] = $n;
                            }
                        }
                    }
                    ?>
                    
                    <?php if ($hasRevisi): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-exclamation-circle"></i> Catatan dari Wali Kelas</h5>
                            <p>Nilai Anda ditolak dan perlu diperbaiki. Berikut adalah feedback dari Wali Kelas:</p>
                            <ul>
                                <?php foreach ($catatanRevisi as $c): ?>
                                    <li><strong><?php echo htmlspecialchars($c['nama_lengkap']); ?>:</strong> 
                                        <?php echo nl2br(htmlspecialchars($c['catatan_revisi'])); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="mb-0"><em>Silakan perbaiki nilai dan ajukan kembali.</em></p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST" action="<?php echo BASE_URL; ?>/nilai/store" id="formNilai">
                        <!-- Hidden fields -->
                        <input type="hidden" name="kelas_id" value="<?php echo htmlspecialchars($kelas_id); ?>">
                        <input type="hidden" name="mapel_id" value="<?php echo htmlspecialchars($mapel_id); ?>">
                        <input type="hidden" name="tahun_id" value="<?php echo htmlspecialchars($tahun_id); ?>">

                        <!-- Info Header -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Kelas:</strong> <?php echo htmlspecialchars($nama_kelas); ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Mapel:</strong> <?php echo htmlspecialchars($nama_mapel); ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($activeTahun['tahun'] . ' - ' . $activeTahun['semester']); ?>
                            </div>
                        </div>

                        <!-- Tabel Matriks Nilai -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Nama Siswa</th>
                                        <th width="15%">
                                            <small>Nilai Tugas (0-100)</small>
                                        </th>
                                        <th width="15%">
                                            <small>Nilai UTS (0-100)</small>
                                        </th>
                                        <th width="15%">
                                            <small>Nilai UAS (0-100)</small>
                                        </th>
                                        <th width="15%">
                                            <small>Preview Nilai Akhir*</small>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="4" class="text-center">
                                            <small class="text-muted">* Dihitung: (Tugas×20%) + (UTS×30%) + (UAS×50%)</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($siswa)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-danger">
                                                <strong>Tidak ada siswa di kelas ini.</strong>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach ($siswa as $s):
                                            // Cek apakah siswa ini sudah punya nilai
                                            $existing = isset($nilaiMap[$s['siswa_id']]) ? $nilaiMap[$s['siswa_id']] : null;
                                            $tugas = $existing ? $existing['nilai_tugas'] : 0;
                                            $uts = $existing ? $existing['nilai_uts'] : 0;
                                            $uas = $existing ? $existing['nilai_uas'] : 0;
                                        ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($s['nama_lengkap']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">NISN: <?php echo htmlspecialchars($s['nisn'] ?? '-'); ?></small>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="number" 
                                                        name="nilai[<?php echo $s['siswa_id']; ?>][tugas]"
                                                        class="form-control nilai-input"
                                                        min="0" 
                                                        max="100" 
                                                        step="0.01"
                                                        value="<?php echo $tugas; ?>"
                                                        data-siswa="<?php echo $s['siswa_id']; ?>"
                                                        required
                                                    >
                                                </td>
                                                <td>
                                                    <input 
                                                        type="number" 
                                                        name="nilai[<?php echo $s['siswa_id']; ?>][uts]"
                                                        class="form-control nilai-input"
                                                        min="0" 
                                                        max="100" 
                                                        step="0.01"
                                                        value="<?php echo $uts; ?>"
                                                        data-siswa="<?php echo $s['siswa_id']; ?>"
                                                        required
                                                    >
                                                </td>
                                                <td>
                                                    <input 
                                                        type="number" 
                                                        name="nilai[<?php echo $s['siswa_id']; ?>][uas]"
                                                        class="form-control nilai-input"
                                                        min="0" 
                                                        max="100" 
                                                        step="0.01"
                                                        value="<?php echo $uas; ?>"
                                                        data-siswa="<?php echo $s['siswa_id']; ?>"
                                                        required
                                                    >
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" 
                                                        class="form-control bg-light"
                                                        id="preview_<?php echo $s['siswa_id']; ?>"
                                                        value="<?php echo number_format($tugas * 0.20 + $uts * 0.30 + $uas * 0.50, 2); ?>"
                                                        readonly
                                                    >
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Info & Buttons -->
                        <div class="alert alert-info mt-3">
                            <strong>Catatan:</strong>
                            <ul class="mb-0">
                                <li>Preview Nilai Akhir dihitung secara realtime (Client-side preview saja)</li>
                                <li>Nilai final akan dihitung ulang di Server sebelum disimpan (untuk keamanan)</li>
                                <li><strong>Simpan Draft:</strong> Form tetap editable. Nilai masih privat (Wali Kelas tidak lihat)</li>
                                <li><strong>Ajukan Validasi:</strong> Lock form. Wali Kelas bisa review + approve/reject</li>
                                <li>Jika status ada nilai masih 0, sistem akan menolak pengajuan</li>
                            </ul>
                        </div>

                    </form>
                </div>

                <div class="card-footer">
                    <?php
                    // SIA-006 V2: Tentukan status form (Draft vs Submitted)
                    $formLocked = false;
                    if (!empty($nilaiMap)) {
                        $statusCheck = array_unique(array_column($nilaiMap, 'status_validasi'));
                        if (count($statusCheck) === 1 && in_array($statusCheck[0], ['Submitted', 'Final'])) {
                            $formLocked = true;
                        }
                    }
                    ?>

                    <?php if (!$formLocked): ?>
                        <!-- SIA-006 V2: Mode Draft - Dua tombol -->
                        <button type="submit" form="formNilai" name="action" value="draft" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Draft
                        </button>
                        <button type="submit" form="formNilai" name="action" value="submit" class="btn btn-warning" onclick="return confirm('Setelah diajukan, form akan terkunci. Pastikan semua nilai lengkap. Lanjutkan?')">
                            <i class="fas fa-check-square"></i> Ajukan ke Wali Kelas
                        </button>
                    <?php else: ?>
                        <!-- SIA-006 V2: Mode Submitted/Final - Form read-only -->
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-lock"></i> <strong>Form Terkunci</strong> - Status nilai sudah Submitted. Menunggu validasi Wali Kelas.
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>/nilai/create" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batalkan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk preview nilai akhir realtime -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua input nilai
    const nilaiInputs = document.querySelectorAll('.nilai-input');
    
    nilaiInputs.forEach(input => {
        input.addEventListener('change', hitungPreviewAkhir);
        input.addEventListener('keyup', hitungPreviewAkhir);
    });

    function hitungPreviewAkhir(event) {
        // Ambil siswa_id dari data attribute
        const siswaId = event.target.dataset.siswa;
        
        // Ambil row siswa yang sesuai
        const row = event.target.closest('tr');
        
        // Cari input tugas, uts, uas di row yang sama
        const tugas = parseFloat(row.querySelector(`input[name="nilai[${siswaId}][tugas]"]`).value) || 0;
        const uts = parseFloat(row.querySelector(`input[name="nilai[${siswaId}][uts]"]`).value) || 0;
        const uas = parseFloat(row.querySelector(`input[name="nilai[${siswaId}][uas]"]`).value) || 0;
        
        // Hitung Nilai Akhir (preview)
        const nilaiAkhir = (tugas * 0.20) + (uts * 0.30) + (uas * 0.50);
        
        // Update preview
        const previewInput = document.getElementById(`preview_${siswaId}`);
        previewInput.value = nilaiAkhir.toFixed(2);
    }
});
</script>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>