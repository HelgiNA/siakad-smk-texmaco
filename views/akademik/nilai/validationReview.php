<?php
ob_start();

// 1. Pre-calculate Stats untuk Header
$totalNilai = 0;
$maxNilai = 0;
$minNilai = 100;
$countSiswa = count($details);
$countRemed = 0;
$countIncomplete = 0; // [BARU] Hitung siswa yang belum dinilai
$KKM = 75; 

foreach ($details as $d) {
    $val = floatval($d['nilai_akhir']);
    
    // [BARU] Logika Cek Kelengkapan
    // Jika nilai akhir 0, kita asumsikan guru belum input nilai
    // Atau bisa dicek spesifik: if ($d['nilai_tugas'] == 0 && $d['nilai_uts'] == 0 ...)
    if ($val == 0) {
        $countIncomplete++;
    }

    $totalNilai += $val;
    if ($val > $maxNilai) $maxNilai = $val;
    if ($val < $minNilai) $minNilai = $val;
    if ($val < $KKM && $val > 0) $countRemed++; // Hanya hitung remed jika nilainya bukan 0
}

$avgNilai = $countSiswa > 0 ? $totalNilai / $countSiswa : 0;
$isDataComplete = ($countIncomplete === 0 && $countSiswa > 0); // [BARU] Flag kelengkapan
?>

<style>
    :root {
        --c-bg-page: #f8fafc;
        --c-text-main: #334155;
        --c-text-muted: #64748b;
        --radius: 12px;
    }

    /* Sticky Sidebar */
    .sticky-sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    /* Card Styles */
    .review-card {
        background: white; border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
        margin-bottom: 20px;
    }
    
    .card-header-clean {
        padding: 15px 20px; border-bottom: 1px solid #f1f5f9;
        font-weight: 700; color: var(--c-text-main); font-size: 1rem;
        background: #fff; display: flex; justify-content: space-between; align-items: center;
    }

    /* Info List Side */
    .info-group { padding: 20px; display: flex; flex-direction: column; gap: 15px; }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--c-text-muted); font-weight: 600; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--c-text-main); }
    .info-sub { font-size: 0.85rem; color: #64748b; }

    /* Stats Cards (Top of Table) */
    .stats-container {
        display: flex; gap: 15px; padding: 20px; background: #f8fafc; border-bottom: 1px solid #f1f5f9;
    }
    .stat-box {
        flex: 1; background: white; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 10px; text-align: center;
    }
    .stat-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block; }
    .stat-value { font-size: 1.1rem; font-weight: 800; color: #334155; }
    
    /* Table */
    .table-wrapper { overflow-x: auto; max-height: 600px; }
    .review-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .review-table th { 
        background: #fff; color: #64748b; font-weight: 700; padding: 12px 15px; 
        text-align: left; border-bottom: 2px solid #f1f5f9; position: sticky; top: 0; z-index: 10;
    }
    .review-table td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .review-table tr:hover td { background: #f8fafc; }

    /* Score Highlighting */
    .score-remed { color: #ef4444; font-weight: 700; }
    .score-pass  { color: #10b981; font-weight: 700; }
    .col-score { text-align: center; font-family: 'Consolas', monospace; }

    /* Modals */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); z-index: 999;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(3px); opacity: 0; pointer-events: none; transition: opacity 0.2s;
    }
    .modal-overlay.active { opacity: 1; pointer-events: auto; }
    
    .modal-content {
        background: white; width: 90%; max-width: 450px;
        padding: 25px; border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        transform: translateY(10px); transition: transform 0.2s;
    }
    .modal-overlay.active .modal-content { transform: translateY(0); }

    /* Buttons */
    .btn-action { width: 100%; padding: 12px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-approve { background: #10b981; color: white; margin-bottom: 10px; }
    .btn-approve:hover { background: #059669; }
    .btn-reject { background: white; color: #ef4444; border: 1px solid #ef4444; }
    .btn-reject:hover { background: #fef2f2; }

    .textarea-custom {
        width: 100%; border: 1px solid #cbd5e1; border-radius: 8px;
        padding: 12px; margin: 15px 0; min-height: 100px;
        font-family: inherit; font-size: 0.9rem; resize: vertical;
    }
    .textarea-custom:focus { outline: none; border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title" style="margin:0; font-size: 1.5rem; font-weight: 700;">Review Nilai Siswa</h1>
        <div style="display: flex; gap: 10px; align-items: center; margin-top: 5px;">
            <span style="color: #64748b; font-size: 0.9rem;">ID Pengajuan: <strong>#<?php echo $header['nilai_id']; ?></strong></span>
            <span class="badge bg-warning text-dark border border-warning" style="font-size: 0.75rem;"><?php echo $header['status_validasi']; ?></span>
        </div>
    </div>
    <a href="<?php echo BASE_URL; ?>/nilai/validasi" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<form id="mainForm" action="<?php echo BASE_URL; ?>/nilai/validasi/process" method="POST">
    <input type="hidden" name="nilai_id" value="<?php echo $header["nilai_id"]; ?>">
    <input type="hidden" name="action" id="actionInput" value="">

    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="sticky-sidebar">
                <div class="review-card">
                    <div class="card-header-clean">
                        <span><i class="bi bi-info-circle me-2 text-primary"></i> Detail Pengajuan</span>
                    </div>
                    <div class="info-group">
                        <div class="info-item">
                            <span class="info-label">Periode Akademik</span>
                            <span class="info-value"><?php echo htmlspecialchars($header["tahun"]); ?></span>
                            <span class="info-sub">Semester <?php echo htmlspecialchars($header["semester"]); ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Kelas & Mapel</span>
                            <span class="info-value text-primary"><?php echo htmlspecialchars($header["nama_mapel"]); ?></span>
                            <span class="info-sub">Kelas: <?php echo htmlspecialchars($header["nama_kelas"]); ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Guru Pengampu</span>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                <div style="width: 30px; height: 30px; background: #e0f2fe; color: #0284c7; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="info-value"><?php echo htmlspecialchars($header["nama_guru"]); ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Waktu Input</span>
                            <span class="info-sub">
                                <?php echo date("d F Y, H:i", strtotime($header["updated_at"])); ?> WIB
                            </span>
                        </div>
                    </div>
                </div>

                <div class="review-card" style="padding: 20px;">
                    <div style="margin-bottom: 10px; font-weight: 600; color: #334155;">Tindakan Validasi:</div>

                    <?php if ($isDataComplete): ?>
                        <button type="button" class="btn-action btn-approve" id="btnApproveTrigger">
                            <i class="bi bi-check-circle-fill"></i> Validasi Final
                        </button>
                    <?php else: ?>
                        <div class="alert alert-warning p-2 mb-2" style="font-size: 0.85rem; border-left: 3px solid #f59e0b; background: #fffbeb;">
                            <i class="bi bi-exclamation-circle-fill text-warning"></i> 
                            <b>Belum Bisa Validasi!</b><br>
                            Terdapat <strong><?php echo $countIncomplete; ?></strong> siswa belum memiliki nilai (Nilai 0).
                        </div>
                        <button type="button" class="btn-action btn-approve" disabled style="opacity: 0.6; cursor: not-allowed; background: #94a3b8;">
                            <i class="bi bi-lock-fill"></i> Validasi Final
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn-action btn-reject" id="btnRejectTrigger">
                        <i class="bi bi-x-circle"></i> Minta Revisi
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="review-card">
                <div class="card-header-clean">
                    <span><i class="bi bi-calculator me-2 text-primary"></i> Rekapitulasi Nilai</span>
                    <span class="badge bg-light text-dark border">Total: <?php echo $countSiswa; ?> Siswa</span>
                </div>

                <div class="stats-container">
                    <div class="stat-box">
                        <span class="stat-label">Rata-Rata</span>
                        <span class="stat-value"><?php echo number_format($avgNilai, 1); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Tertinggi</span>
                        <span class="stat-value text-success"><?php echo number_format($maxNilai, 0); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Terendah</span>
                        <span class="stat-value text-danger"><?php echo number_format($minNilai, 0); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Remedial (< 75)</span>
                        <span class="stat-value text-danger"><?php echo $countRemed; ?></span>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="review-table">
                        <thead>
                            <tr>
                                <th width="40" class="text-center">No</th>
                                <th width="100">NIS</th>
                                <th>Nama Siswa</th>
                                <th class="col-score">Tugas</th>
                                <th class="col-score">UTS</th>
                                <th class="col-score">UAS</th>
                                <th class="col-score text-center">Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $idx => $row): 
                                $akhir = floatval($row['nilai_akhir']);
                                $isRemed = $akhir < $KKM && $akhir > 0;
                                $isEmpty = $akhir == 0; // [BARU] Cek kosong
                            ?>
                            <tr class="<?php echo $isEmpty ? 'bg-danger-subtle' : ''; ?>" <?php echo $isEmpty ? 'style="background-color: #fef2f2;"' : ''; ?>>
                                <td class="text-center text-muted"><?php echo $idx + 1; ?></td>
                                <td style="font-family: monospace; color: #64748b;"><?php echo htmlspecialchars($row["nis"]); ?></td>
                                <td style="font-weight: 600;">
                                    <?php echo htmlspecialchars($row["nama_lengkap"]); ?>
                                    <?php if($isEmpty): ?>
                                        <span class="badge bg-danger ms-1" style="font-size: 0.65rem;">Kosong</span>
                                    <?php endif; ?>
                                </td>

                                <td class="col-score text-muted"><?php echo number_format($row['nilai_tugas'], 0); ?></td>
                                <td class="col-score text-muted"><?php echo number_format($row['nilai_uts'], 0); ?></td>
                                <td class="col-score text-muted"><?php echo number_format($row['nilai_uas'], 0); ?></td>

                                <td class="col-score">
                                    <?php if ($isEmpty): ?>
                                        <span class="text-danger fw-bold">-</span>
                                    <?php else: ?>
                                        <span class="<?php echo $isRemed ? 'score-remed' : 'score-pass'; ?>">
                                            <?php echo number_format($akhir, 2); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalReject" class="modal-overlay">
        <div class="modal-content">
            <h4 style="margin: 0 0 10px; color: #ef4444; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-exclamation-triangle-fill"></i> Tolak Pengajuan?
            </h4>
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0;">
                Nilai akan dikembalikan ke status DRAFT. Berikan catatan revisi untuk guru mapel.
            </p>
            
            <textarea name="catatan_revisi" id="rejectReason" class="textarea-custom" placeholder="Contoh: Ada nilai yang tertukar antara Budi dan Andi..."></textarea>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-action btn-modal-cancel" id="btnCancelReject" style="width: auto; background: #f1f5f9; color: #64748b;">Batal</button>
                <button type="button" class="btn-action btn-modal-confirm" id="btnConfirmReject" style="width: auto; background: #ef4444; color: white;">Minta Revisi</button>
            </div>
        </div>
    </div>

    <div id="modalApprove" class="modal-overlay">
        <div class="modal-content">
            <h4 style="margin: 0 0 10px; color: #10b981; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-check-circle-fill"></i> Konfirmasi Finalisasi
            </h4>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 20px;">
                Apakah Anda yakin nilai ini sudah valid? <br>
                Status akan berubah menjadi <strong>FINAL</strong> dan nilai akan dipublikasikan ke Rapor.
            </p>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-action btn-modal-cancel" id="btnCancelApprove" style="width: auto; background: #f1f5f9; color: #64748b;">Batal</button>
                <button type="button" class="btn-action btn-modal-confirm" id="btnConfirmApprove" style="width: auto; background: #10b981; color: white;">Ya, Validasi Final</button>
            </div>
        </div>
    </div>

</form>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainForm = document.getElementById('mainForm');
    const actionInput = document.getElementById('actionInput');
    const rejectReason = document.getElementById('rejectReason');

    // Modals
    const modalReject = document.getElementById('modalReject');
    const modalApprove = document.getElementById('modalApprove');

    // Buttons (Ambil elemen berdasarkan ID)
    const btnApprove = document.getElementById('btnApproveTrigger');
    const btnReject = document.getElementById('btnRejectTrigger');
    
    // Tombol di dalam Modal
    const btnCancelApprove = document.getElementById('btnCancelApprove');
    const btnConfirmApprove = document.getElementById('btnConfirmApprove');
    const btnCancelReject = document.getElementById('btnCancelReject');
    const btnConfirmReject = document.getElementById('btnConfirmReject');

    // --- APPROVE FLOW ---
    // PENTING: Cek dulu apakah tombol Approve ada (karena bisa saja hidden/disabled)
    if (btnApprove) {
        btnApprove.addEventListener('click', () => {
            modalApprove.classList.add('active');
            rejectReason.removeAttribute('required');
        });
    }

    if (btnCancelApprove) {
        btnCancelApprove.addEventListener('click', () => {
            modalApprove.classList.remove('active');
        });
    }

    if (btnConfirmApprove) {
        btnConfirmApprove.addEventListener('click', () => {
            actionInput.value = 'approve';
            mainForm.submit();
        });
    }

    // --- REJECT FLOW ---
    if (btnReject) {
        btnReject.addEventListener('click', () => {
            modalReject.classList.add('active');
            rejectReason.setAttribute('required', 'true');
            setTimeout(() => rejectReason.focus(), 100); 
        });
    }

    if (btnCancelReject) {
        btnCancelReject.addEventListener('click', () => {
            modalReject.classList.remove('active');
        });
    }

    if (btnConfirmReject) {
        btnConfirmReject.addEventListener('click', () => {
            if (!rejectReason.value.trim()) {
                alert("Harap isi catatan revisi.");
                rejectReason.focus();
                return;
            }
            actionInput.value = 'reject';
            mainForm.submit();
        });
    }

    // Close on click outside
    window.addEventListener('click', (e) => {
        if(e.target === modalReject) modalReject.classList.remove('active');
        if(e.target === modalApprove) modalApprove.classList.remove('active');
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";
?>
