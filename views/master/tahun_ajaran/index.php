<?php ob_start(); ?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/datatable.css">

<style>
    /* --- CUSTOM MODAL STYLE --- */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); z-index: 9999;
        display: none; align-items: center; justify-content: center;
        backdrop-filter: blur(2px);
        opacity: 0; transition: opacity 0.3s ease;
    }
    .modal-overlay.show { display: flex; opacity: 1; }

    .modal-box {
        background: white; width: 90%; max-width: 400px;
        border-radius: 15px; padding: 30px; text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: scale(0.9); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .modal-overlay.show .modal-box { transform: scale(1); }

    .modal-icon {
        width: 70px; height: 70px; margin: 0 auto 20px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 32px;
    }
    /* Warna Icon Dinamis */
    .icon-activate { background: #d1fae5; color: #059669; } /* Hijau */
    .icon-delete { background: #fee2e2; color: #dc2626; }   /* Merah */

    .modal-title { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px; }
    .modal-text { color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px; }

    .modal-actions { display: flex; gap: 10px; justify-content: center; }
    .btn-modal { padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; font-size: 0.95rem; }
    .btn-modal-cancel { background: #f1f5f9; color: #475569; }
    .btn-modal-cancel:hover { background: #e2e8f0; }
    
    /* Tombol Konfirmasi Dinamis */
    .btn-confirm-activate { background: #10b981; color: white; }
    .btn-confirm-activate:hover { background: #059669; }
    
    .btn-confirm-delete { background: #ef4444; color: white; }
    .btn-confirm-delete:hover { background: #dc2626; }

    /* --- STYLE TABEL SEPERTI SEBELUMNYA --- */
    tr.row-active td { background-color: #f0fdf4 !important; border-bottom: 1px solid #bbf7d0; }
    .btn-activate {
        padding: 5px 10px; border: 1px solid #10b981; color: #10b981;
        background: white; border-radius: 6px; font-size: 0.8rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; transition: 0.2s;
    }
    .btn-activate:hover { background: #10b981; color: white; }
    .badge-ganjil { background: #e0e7ff; color: #4338ca; }
    .badge-genap  { background: #fef3c7; color: #b45309; }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Tahun Ajaran</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Atur periode akademik aktif sistem</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div class="table-controls">
        <div class="filter-group">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari Tahun (misal: 2024)...">
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>/tahun-ajaran/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Periode Baru
        </a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Tahun Akademik</th>
                    <th>Semester</th>
                    <th>Status Sistem</th>
                    <th width="150" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($data["tahun_ajaran"])): ?>
                    <tr id="noDataRow">
                        <td colspan="5" style="text-align: center; padding: 50px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <i class="bi bi-calendar-x" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                <span style="font-size: 1rem; font-weight: 500;">Belum ada data Tahun Ajaran</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["tahun_ajaran"] as $index => $row):

                        $isActive = $row["is_active"] == 1;
                        $rowClass = $isActive ? "row-active" : "";
                        $semesterClass =
                            $row["semester"] == "Ganjil"
                                ? "badge-ganjil"
                                : "badge-genap";
                        ?>
                    <tr class="data-row <?php echo $rowClass; ?>">
                        <td><?php echo $index + 1; ?></td>
                        <td class="searchable-tahun" style="font-weight: 600;">
                            <?php echo htmlspecialchars($row["tahun"]); ?>
                        </td>
                        <td>
                            <span class="badge-custom <?php echo $semesterClass; ?>">
                                <?php echo htmlspecialchars(
                                    $row["semester"]
                                ); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($isActive): ?>
                                <span class="badge-custom badge-active"><i class="bi bi-check-circle-fill"></i> SEDANG AKTIF</span>
                            <?php else: ?>
                                <span class="badge-custom badge-guest" style="opacity: 0.7;">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if (!$isActive): ?>
                                <button type="button" class="btn-activate" 
                                    onclick="openModal('activate', '<?php echo BASE_URL; ?>/tahun-ajaran/activate?id=<?php echo $row[
    "tahun_id"
]; ?>', '<?php echo $row["tahun"] . " - " . $row["semester"]; ?>')">
                                    <i class="bi bi-power"></i> Set Aktif
                                </button>

                                <button type="button" class="btn-action btn-delete" 
                                    onclick="openModal('delete', '<?php echo BASE_URL; ?>/tahun-ajaran/delete?id=<?php echo $row[
    "tahun_id"
]; ?>', '<?php echo $row["tahun"] . " - " . $row["semester"]; ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            <?php else: ?>
                                <span style="font-size: 0.8rem; color: #15803d; font-weight: 600;"><i class="bi bi-lock-fill"></i> Terkunci</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                    endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="confirmModal" class="modal-overlay">
    <div class="modal-box">
        <div id="modalIcon" class="modal-icon">
            </div>
        <h3 id="modalTitle" class="modal-title">Konfirmasi</h3>
        <p id="modalText" class="modal-text">Deskripsi aksi...</p>
        
        <div class="modal-actions">
            <button class="btn-modal btn-modal-cancel" onclick="closeModal()">Batal</button>
            <a id="btnConfirm" href="#" class="btn-modal">Ya, Lanjutkan</a>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/public/js/datatable.js"></script>

<script>
    const modal = document.getElementById('confirmModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const btnConfirm = document.getElementById('btnConfirm');

    function openModal(type, url, itemName) {
        // Reset Class
        modalIcon.className = 'modal-icon';
        btnConfirm.className = 'btn-modal';

        if (type === 'activate') {
            // Setup Tampilan untuk Aktivasi (HIJAU)
            modalIcon.classList.add('icon-activate');
            modalIcon.innerHTML = '<i class="bi bi-check-lg"></i>';
            
            modalTitle.innerText = "Aktifkan Periode?";
            modalText.innerHTML = `Anda akan mengaktifkan tahun ajaran <strong>${itemName}</strong>.<br>Periode aktif sebelumnya akan otomatis dinonaktifkan.`;
            
            btnConfirm.classList.add('btn-confirm-activate');
            btnConfirm.innerText = "Ya, Aktifkan";
        } 
        else if (type === 'delete') {
            // Setup Tampilan untuk Hapus (MERAH)
            modalIcon.classList.add('icon-delete');
            modalIcon.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
            
            modalTitle.innerText = "Hapus Data?";
            modalText.innerHTML = `Apakah Anda yakin ingin menghapus data <strong>${itemName}</strong>?<br>Tindakan ini tidak dapat dibatalkan.`;
            
            btnConfirm.classList.add('btn-confirm-delete');
            btnConfirm.innerText = "Ya, Hapus";
        }

        // Set Link Tujuan
        btnConfirm.href = url;

        // Tampilkan Modal
        modal.classList.add('show');
    }

    function closeModal() {
        modal.classList.remove('show');
    }

    // Tutup jika klik di luar box
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
