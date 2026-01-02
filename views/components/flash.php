<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["flash"])):

    $flash = $_SESSION["flash"];
    $type = $flash["type"] ?? "info";

    // Mapping SVG Icon berdasarkan Tipe
    $icons = [
        "success" =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
        "info" =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
        "warning" =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        "error" =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
        "question" =>
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
    ];

    $icon = $icons[$type] ?? $icons["info"];
    ?>
<style>
/* Container di pojok kanan atas */
.flash-container {
    position: fixed;
    top: 20px;
    right: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    z-index: 9999;
    font-family: system-ui, -apple-system, sans-serif;
}

/* Style Dasar Card */
.flash {
    display: flex;
    align-items: flex-start;
    background: #fff;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    min-width: 320px;
    max-width: 360px;
    position: relative;
    overflow: hidden;
    border-left: 6px solid #ccc;

    /* Animasi Masuk */
    animation: slideInRight 0.5s ease forwards;

    /* Transisi untuk animasi keluar */
    transition: all 0.3s ease;
    opacity: 1;
    transform: translateX(0);
}

/* Animasi Masuk dari Kanan */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Kelas saat ditutup (Fade Out ke kanan) */
.flash.closing {
    opacity: 0;
    transform: translateX(100%);
    margin-top: -100px;
    /* Supaya elemen bawah naik ke atas */
}

/* Bagian Icon SVG */
.flash-icon {
    margin-right: 12px;
    margin-top: 2px;
    width: 24px;
    height: 24px;
}

.flash-icon svg {
    width: 100%;
    height: 100%;
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* Bagian Text */
.flash-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.flash-title {
    font-weight: 700;
    font-size: 16px;
    color: #333;
    margin-bottom: 4px;
}

.flash-message {
    font-size: 14px;
    color: #666;
    line-height: 1.4;
}

/* Tombol X Close */
.flash-close {
    position: absolute;
    top: 10px;
    right: 12px;
    font-size: 22px;
    color: #999;
    cursor: pointer;
    line-height: 1;
}

.flash-close:hover {
    color: #333;
}

/* --- WARNA --- */
.flash-success {
    border-left-color: #2ecc71;
}

.flash-success .flash-icon {
    color: #2ecc71;
}

.flash-info {
    border-left-color: #3498db;
}

.flash-info .flash-icon {
    color: #3498db;
}

.flash-warning {
    border-left-color: #f1c40f;
}

.flash-warning .flash-icon {
    color: #f1c40f;
}

.flash-error {
    border-left-color: #e74c3c;
}

.flash-error .flash-icon {
    color: #e74c3c;
}

.flash-question {
    border-left-color: #8e44ad;
}

.flash-question .flash-icon {
    color: #8e44ad;
}

/* --- TOMBOL AKSI (Question) --- */
.flash-actions {
    margin-top: 12px;
    display: flex;
    gap: 10px;
}

.flash-actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    transition: 0.2s;
}

.btn-confirm {
    background: #8e44ad;
    color: #fff;
}

.btn-confirm:hover {
    background: #732d91;
}

.btn-cancel {
    background: #f0f0f0;
    color: #333;
}

.btn-cancel:hover {
    background: #e0e0e0;
}
</style>

<div class="flash-container">
    <div class="flash flash-<?php echo $type; ?>">
        <div class="flash-icon">
            <?php echo $icon; ?>
        </div>
        <div class="flash-content">
            <span class="flash-title"><?php echo htmlspecialchars(
                $flash["title"] ?? ""
            ); ?></span>
            <span class="flash-message"><?php echo htmlspecialchars(
                $flash["message"]
            ); ?></span>
            <?php if ($type == "question"): ?>
                <!-- code... -->
            <div class="flash-actions">
                <button class="btn-confirm" onclick="closeFlash(this)">Ya</button>
                <button class="btn-cancel" onclick="closeFlash(this)">Tidak</button>
            </div>
            <?php endif; ?>
        </div>
        <div class="flash-close" onclick="closeFlash(this)">&times;</div>
    </div>
</div>

<script>
function closeFlash(element) {
    // Mencari elemen induk dengan class 'flash'
    const flash = element.closest('.flash');

    // Menambahkan class 'closing' untuk memicu animasi CSS
    flash.classList.add('closing');

    // Menunggu animasi selesai (300ms) baru menghapus elemen dari DOM
    setTimeout(() => {
        flash.remove();
    }, 300);
}
</script>

<?php // Hapus session agar flash tidak muncul terus menerus saat refresh

    unset($_SESSION["flash"]);
endif;
?>
