<?php
ob_start();

// Helper untuk Badge Status sesuai CSS baru
function getStatusClass($status)
{
    switch ($status) {
        case "Hadir":
            return "status-hadir";
        case "Sakit":
            return "status-sakit";
        case "Izin":
            return "status-izin";
        case "Alpa":
            return "status-alfa";
        default:
            return "status-alfa";
    }
}
?>

<style>
    :root {
        --c-primary: #10b981; --c-danger: #ef4444; --c-text-main: #1f2937;
        --c-text-muted: #6b7280; --c-bg-page: #f3f4f6; --c-bg-card: #ffffff;
        --c-border: #e5e7eb; --radius-md: 8px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        --status-hadir: #10b981; --status-sakit: #f59e0b;
        --status-izin: #3b82f6; --status-alfa: #ef4444;
    }
    
    /* Header & Cards */
    .page-header { margin-bottom: 8px; }
    .page-title { font-size: 24px; font-weight: 700; color: var(--c-text-main); margin-bottom: 4px; }
    .meta-info { display: flex; gap: 12px; font-size: 14px; color: var(--c-text-muted); align-items: center; }
    .meta-status { background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
    
    .card { background: var(--c-bg-card); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1px solid var(--c-border); overflow: hidden; }
    .card-title { padding: 16px 20px; font-size: 16px; font-weight: 600; border-bottom: 1px solid var(--c-border); margin: 0; }
    .validation-card { padding: 20px; display: flex; flex-direction: column; gap: 24px; }
    
    /* Info List */
    .info-list { display: flex; flex-direction: column; gap: 16px; }
    .info-row { display: flex; flex-direction: column; gap: 4px; }
    .info-label { color: var(--c-text-muted); font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-weight: 600; font-size: 15px; color: var(--c-text-main); }
    
    /* Buttons */
    .action-group { display: flex; flex-direction: column; gap: 10px; margin-top: 8px; }
    .btn { width: 100%; padding: 12px; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; font-size: 14px; border: 1px solid transparent; transition: 0.2s; }
    .btn-solid-success { background: var(--c-primary) !important; color: white !important; } .btn-solid-success:hover { background: #059669 !important; }
    .btn-outline-danger { background: white !important; border-color: var(--c-danger) !important; color: var(--c-danger) !important; } .btn-outline-danger:hover { background: #fef2f2 !important; }
    .btn-solid-danger { background: var(--c-danger) !important; color: white !important; }
    .btn-ghost { background: transparent; color: var(--c-text-muted); }
    
    /* Table */
    .table-wrapper { overflow-x: auto; }
    .att-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .att-table th, .att-table td { padding: 12px 16px; border-bottom: 1px solid var(--c-border); }
    .att-table th { background: #f9fafb; color: var(--c-text-muted); font-weight: 600; text-align: left; }
    .badge { display: inline-block; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; }
    .status-hadir { background: #d1fae5; color: #065f46; }
    .status-sakit { background: #fef3c7; color: #92400e; }
    .status-izin  { background: #dbeafe; color: #1e40af; }
    .status-alfa  { background: #fee2e2; color: #991b1b; }

    /* Utilities */
    .hidden { display: none !important; }
    .visually-hidden { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0); }
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
    .modal-box { background: white; padding: 24px; border-radius: 12px; width: 90%; max-width: 450px; box-shadow: var(--shadow-lg); animation: slideUp 0.3s ease-out; }
/* Update pada class input-textarea */
.input-textarea { 
    width: 100%; 
    padding: 12px; 
    border: 1px solid var(--c-border); 
    border-radius: var(--radius-md); 
    min-height: 80px; 
    margin-top: 8px; 
    margin-bottom: 20px;
    
    /* TAMBAHAN: Paksa warna background putih & teks gelap */
    background-color: #ffffff !important; 
    color: #1f2937 !important; 
}

/* Tambahan: Ubah warna border saat diklik (fokus) agar lebih jelas */
.input-textarea:focus {
    outline: none;
    border-color: var(--c-primary);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); /* Efek glow hijau tipis */
}

    .modal-actions { display: flex; justify-content: flex-end; gap: 10px; }
    .modal-actions .btn { width: auto; padding: 10px 20px; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<div>

    <header class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Detail Validasi Absensi</h1>
            <div class="meta-info">
                <span>ID: #<?php echo htmlspecialchars(
                    $absensi["absensi_id"]
                ); ?></span>
                <span class="meta-status"><?php echo htmlspecialchars(
                    $absensi["status_validasi"]
                ); ?></span>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>/absensi/validasi" class="btn btn-ghost" style="width: auto;">
            &larr; Kembali
        </a>
    </header>

    <div class="row">
        
        <div class="col-sm-4 col-12 mb-3">
            <form id="validationForm" action="<?php echo BASE_URL; ?>/absensi/validasi/process" method="POST">
                
                <input type="hidden" name="absensi_id" value="<?php echo $absensi[
                    "absensi_id"
                ]; ?>">
                
                <input type="hidden" name="action" id="actionInput" value="">

                <section class="card validation-card">
                    <h2 class="card-title visually-hidden">Informasi Jadwal</h2>
                    
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-label">Waktu</span>
                            <span class="info-value">
                                <?php echo htmlspecialchars(
                                    $absensi["hari"]
                                ); ?>, 
                                <?php echo date(
                                    "d M Y",
                                    strtotime($absensi["tanggal"])
                                ); ?> <br>
                                <small class="text-muted">
                                    <?php echo date(
                                        "H:i",
                                        strtotime($absensi["jam_mulai"])
                                    ); ?> - 
                                    <?php echo date(
                                        "H:i",
                                        strtotime($absensi["jam_selesai"])
                                    ); ?>
                                </small>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kelas / Mapel</span>
                            <span class="info-value">
                                <?php echo htmlspecialchars(
                                    $absensi["nama_kelas"]
                                ); ?> &bull; 
                                <?php echo htmlspecialchars(
                                    $absensi["nama_mapel"]
                                ); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Guru Pengajar</span>
                            <span class="info-value"><?php echo htmlspecialchars(
                                $absensi["nama_guru"]
                            ); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Catatan Guru</span>
                            <p class="info-value text-wrap" style="font-weight: 400; font-size: 14px;">
                                <?php echo nl2br(
                                    htmlspecialchars(
                                        $absensi["catatan_harian"] ?? "-"
                                    )
                                ); ?>
                            </p>
                        </div>
                    </div>

                    <div class="action-group">
                        <button type="button" class="btn btn-solid-success" id="btnTriggerApprove">
                            Validasi (Setujui)
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="btnTriggerReject">
                            Tolak (Revisi)
                        </button>
                    </div>
                </section>

                <div id="modalReject" class="modal-overlay hidden">
                    <div class="modal-box">
                        <h3 style="margin-bottom: 8px;">Tolak Pengajuan?</h3>
                        <p style="color: var(--c-text-muted); font-size: 14px;">
                            Guru akan diminta untuk merevisi data absensi ini.
                        </p>
                        
                        <textarea name="alasan_penolakan" id="rejectReason" class="input-textarea" 
                            placeholder="Tulis alasan penolakan di sini (Wajib)..."></textarea>
                        
                        <div class="modal-actions">
                            <button type="button" class="btn btn-ghost" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-solid-danger" id="submitReject">
                                Ya, Tolak Absensi
                            </button>
                        </div>
                    </div>
                </div>

                <div id="modalApprove" class="modal-overlay hidden">
                    <div class="modal-box">
                        <h3 style="margin-bottom: 8px;">Konfirmasi Validasi</h3>
                        <p style="color: var(--c-text-muted); font-size: 14px; margin-bottom: 20px;">
                            Apakah Anda yakin data absensi ini sudah benar dan siap disimpan?
                        </p>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-ghost" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-solid-success" id="submitApprove">
                                Ya, Validasi
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-sm-8 col-12">
            <section class="card table-card">
                <h2 class="card-title">Detail Kehadiran Siswa</h2>
                <div class="table-wrapper" style="max-height: 600px; overflow-y: auto;">
                    <table class="att-table">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">No</th>
                                <th style="width: 100px;">NIS</th>
                                <th>Nama Siswa</th>
                                <th style="width: 120px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $idx => $row): ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $idx +
                                    1; ?></td>
                                <td><?php echo htmlspecialchars(
                                    $row["nis"]
                                ); ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars(
                                    $row["nama_lengkap"]
                                ); ?></td>
                                <td style="text-align: center;">
                                    <span class="badge <?php echo getStatusClass(
                                        $row["status_kehadiran"]
                                    ); ?>">
                                        <?php echo $row["status_kehadiran"]; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Referensi Input Hidden untuk Backend PHP
    // Backend lama mengharapkan $_POST['action'] = 'approve' atau 'reject'
    const actionInput = document.getElementById('actionInput');
    const rejectReasonInput = document.getElementById('rejectReason');

    // Modals & Buttons
    const modalReject = document.getElementById('modalReject');
    const modalApprove = document.getElementById('modalApprove');
    const btnTriggerReject = document.getElementById('btnTriggerReject');
    const btnTriggerApprove = document.getElementById('btnTriggerApprove');
    const btnSubmitReject = document.getElementById('submitReject');
    const btnSubmitApprove = document.getElementById('submitApprove');
    const closeButtons = document.querySelectorAll('[data-close-modal]');

    // Fungsi Helper Modal
    const openModal = (modal) => {
        modal.classList.remove('hidden');
        const focusable = modal.querySelector('textarea, button:not([data-close-modal])');
        if(focusable) focusable.focus();
    };
    const closeAllModals = () => {
        modalReject.classList.add('hidden');
        modalApprove.classList.add('hidden');
    };

    // --- TRIGGERS ---
    
    // Klik Tombol "Tolak" (Sidebar)
    btnTriggerReject.addEventListener('click', () => {
        rejectReasonInput.value = '';
        rejectReasonInput.setAttribute('required', 'true'); 
        openModal(modalReject);
    });

    // Klik Tombol "Validasi" (Sidebar)
    btnTriggerApprove.addEventListener('click', () => {
        rejectReasonInput.removeAttribute('required');
        openModal(modalApprove);
    });

    // --- ACTIONS (Saat tombol di dalam modal diklik) ---

    // Konfirmasi Tolak
    btnSubmitReject.addEventListener('click', () => {
        if(!rejectReasonInput.value.trim()) return; // Validasi HTML5 handle sisanya
        actionInput.value = 'reject'; // Kirim value 'reject' ke PHP
    });

    // Konfirmasi Approve
    btnSubmitApprove.addEventListener('click', () => {
        actionInput.value = 'approve'; // Kirim value 'approve' ke PHP
    });

    // --- CLOSING ---
    closeButtons.forEach(btn => btn.addEventListener('click', closeAllModals));
    window.addEventListener('click', (e) => {
        if (e.target === modalReject || e.target === modalApprove) closeAllModals();
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
