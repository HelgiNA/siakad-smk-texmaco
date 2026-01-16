<?php
ob_start();

// 1. Hitung Statistik Ringkas untuk Header
$stats = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alpa' => 0];
foreach ($details as $d) {
    $s = $d['status_kehadiran'];
    if (isset($stats[$s])) $stats[$s]++;
    else $stats['Alpa']++; // Default fallback
}

// 2. Helper Class Badge
function getBadgeStyle($status) {
    switch ($status) {
        case 'Hadir': return 'bg-green';
        case 'Sakit': return 'bg-yellow';
        case 'Izin':  return 'bg-blue';
        case 'Alpa':  return 'bg-red';
        default:      return 'bg-red';
    }
}
?>

<style>
    /* Layout Variables */
    :root {
        --c-bg-page: #f8fafc;
        --c-text-main: #334155;
        --c-text-muted: #64748b;
        --radius: 12px;
    }

    /* Badge Colors (Soft UI) */
    .bg-green { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .bg-yellow { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
    .bg-blue   { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .bg-red    { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Sticky Sidebar (Kiri) */
    .sticky-sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: 20px; /* Jarak dari atas saat scroll */
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
        background: #fff;
    }

    /* Info Lists */
    .info-group { padding: 20px; display: flex; flex-direction: column; gap: 15px; }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--c-text-muted); font-weight: 600; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--c-text-main); }
    
    /* Teacher Note Box */
    .note-box {
        background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px;
        padding: 12px; font-size: 0.9rem; color: #475569; line-height: 1.5;
    }

    /* Stats Bar (Kanan Atas) */
    .stats-bar {
        display: flex; gap: 10px; padding: 15px 20px;
        background: #f8fafc; border-bottom: 1px solid #f1f5f9;
        overflow-x: auto;
    }
    .stat-pill {
        display: flex; align-items: center; gap: 6px;
        padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
        white-space: nowrap;
    }

    /* Table */
    .table-wrapper { overflow-x: auto; }
    .review-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .review-table th { background: #fff; color: #64748b; font-weight: 700; padding: 12px 20px; text-align: left; border-bottom: 2px solid #f1f5f9; }
    .review-table td { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    .review-table tr:hover td { background: #f8fafc; }

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
    
    .btn-modal-cancel { background: #f1f5f9; color: #64748b; padding: 10px 20px; border-radius: 6px; border:none; cursor: pointer; font-weight: 600; }
    .btn-modal-confirm { background: #ef4444; color: white; padding: 10px 20px; border-radius: 6px; border:none; cursor: pointer; font-weight: 600; }
    
    /* Textarea Custom */
    .textarea-custom {
        width: 100%; border: 1px solid #cbd5e1; border-radius: 8px;
        padding: 12px; margin: 15px 0; min-height: 100px;
        font-family: inherit; font-size: 0.9rem; resize: vertical;
    }
    .textarea-custom:focus { outline: none; border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title" style="margin:0; font-size: 1.5rem; font-weight: 700;">Review Validasi</h1>
        <p style="color: #64748b; margin:0;">
            ID Laporan: <strong>#<?php echo $absensi['absensi_id']; ?></strong>
        </p>
    </div>
    <a href="<?php echo BASE_URL; ?>/absensi/validasi" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<form id="mainForm" action="<?php echo BASE_URL; ?>/absensi/validasi/process" method="POST">
    <input type="hidden" name="absensi_id" value="<?php echo $absensi['absensi_id']; ?>">
    <input type="hidden" name="action" id="actionInput" value="">
    
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="sticky-sidebar">
                <div class="review-card">
                    <div class="card-header-clean">
                        <i class="bi bi-info-circle me-2 text-primary"></i> Detail Laporan
                    </div>
                    <div class="info-group">
                        <div class="info-item">
                            <span class="info-label">Waktu Pelaksanaan</span>
                            <span class="info-value">
                                <?php echo date('l, d F Y', strtotime($absensi['tanggal'])); ?>
                            </span>
                            <span style="font-size: 0.85rem; color: #64748b;">
                                Pukul: <?php echo date('H:i', strtotime($absensi['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($absensi['jam_selesai'])); ?>
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Kelas & Mapel</span>
                            <span class="info-value text-primary"><?php echo htmlspecialchars($absensi['nama_kelas']); ?></span>
                            <span style="font-size: 0.9rem; font-weight: 600;"><?php echo htmlspecialchars($absensi['nama_mapel']); ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Guru Pengampu</span>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                <div style="width: 30px; height: 30px; background: #e0f2fe; color: #0284c7; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="info-value"><?php echo htmlspecialchars($absensi['nama_guru']); ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Catatan Jurnal Guru</span>
                            <div class="note-box">
                                <?php echo nl2br(htmlspecialchars($absensi['catatan_harian'] ?? 'Tidak ada catatan khusus.')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="review-card" style="padding: 20px;">
                    <div style="margin-bottom: 10px; font-weight: 600; color: #334155;">Tindakan:</div>
                    <button type="button" class="btn-action btn-approve" id="btnApproveTrigger">
                        <i class="bi bi-check-circle-fill"></i> Validasi (Setujui)
                    </button>
                    <button type="button" class="btn-action btn-reject" id="btnRejectTrigger">
                        <i class="bi bi-x-circle"></i> Tolak Laporan
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="review-card">
                <div class="card-header-clean d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people-fill me-2 text-primary"></i> Daftar Kehadiran Siswa</span>
                    <span class="badge bg-light text-dark border">Total: <?php echo count($details); ?></span>
                </div>
                
                <div class="stats-bar">
                    <div class="stat-pill bg-green">
                        <i class="bi bi-check-circle-fill"></i> Hadir: <?php echo $stats['Hadir']; ?>
                    </div>
                    <div class="stat-pill bg-yellow">
                        <i class="bi bi-emoji-neutral-fill"></i> Sakit: <?php echo $stats['Sakit']; ?>
                    </div>
                    <div class="stat-pill bg-blue">
                        <i class="bi bi-info-circle-fill"></i> Izin: <?php echo $stats['Izin']; ?>
                    </div>
                    <div class="stat-pill bg-red">
                        <i class="bi bi-x-circle-fill"></i> Alpa: <?php echo $stats['Alpa']; ?>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="review-table">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th width="120">NIS</th>
                                <th>Nama Siswa</th>
                                <th width="100" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $idx => $row): 
                                $statusStyle = getBadgeStyle($row['status_kehadiran']);
                            ?>
                            <tr>
                                <td><?php echo $idx + 1; ?></td>
                                <td style="font-family: monospace; color: #64748b;"><?php echo htmlspecialchars($row['nis']); ?></td>
                                <td style="font-weight: 600;"><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td class="text-center">
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; <?php echo str_replace('background:', 'background:', $statusStyle); // Quick hack to re-use string class ?>">
                                        <span class="<?php echo $statusStyle; ?>" style="padding: 4px 10px; border-radius: 20px;">
                                            <?php echo $row['status_kehadiran']; ?>
                                        </span>
                                    </span>
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
                <i class="bi bi-exclamation-triangle-fill"></i> Tolak Laporan?
            </h4>
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0;">
                Guru harus memperbaiki data ini. Silakan berikan alasan penolakan.
            </p>
            
            <textarea name="alasan_penolakan" id="rejectReason" class="textarea-custom" placeholder="Contoh: Ada siswa yang hadir tapi tercatat alpa..."></textarea>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-modal-cancel" id="btnCancelReject">Batal</button>
                <button type="button" class="btn-modal-confirm" id="btnConfirmReject">Tolak Laporan</button>
            </div>
        </div>
    </div>

    <div id="modalApprove" class="modal-overlay">
        <div class="modal-content">
            <h4 style="margin: 0 0 10px; color: #10b981; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-check-circle-fill"></i> Konfirmasi Validasi
            </h4>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 20px;">
                Apakah Anda yakin data presensi ini sudah benar? Data yang sudah divalidasi akan masuk ke rekapitulasi.
            </p>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-modal-cancel" id="btnCancelApprove">Batal</button>
                <button type="button" class="btn-modal-confirm" id="btnConfirmApprove" style="background: #10b981;">Ya, Validasi</button>
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

    // --- APPROVE FLOW ---
    document.getElementById('btnApproveTrigger').addEventListener('click', () => {
        modalApprove.classList.add('active');
        // Reset reject reason requirement
        rejectReason.removeAttribute('required');
    });

    document.getElementById('btnCancelApprove').addEventListener('click', () => {
        modalApprove.classList.remove('active');
    });

    document.getElementById('btnConfirmApprove').addEventListener('click', () => {
        actionInput.value = 'approve';
        mainForm.submit();
    });

    // --- REJECT FLOW ---
    document.getElementById('btnRejectTrigger').addEventListener('click', () => {
        modalReject.classList.add('active');
        rejectReason.setAttribute('required', 'true');
        setTimeout(() => rejectReason.focus(), 100);
    });

    document.getElementById('btnCancelReject').addEventListener('click', () => {
        modalReject.classList.remove('active');
    });

    document.getElementById('btnConfirmReject').addEventListener('click', () => {
        if (!rejectReason.value.trim()) {
            alert("Harap isi alasan penolakan.");
            rejectReason.focus();
            return;
        }
        actionInput.value = 'reject';
        mainForm.submit();
    });

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
