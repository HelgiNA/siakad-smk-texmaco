<?php if (isset($_SESSION['flash'])): ?>
<style>
/* Container di pojok kanan atas */
.myToast-container {
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
.myToast {
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
.myToast.closing {
    opacity: 0;
    transform: translateX(100%);
    margin-top: -100px;
    /* Supaya elemen bawah naik ke atas */
}

/* Bagian Icon SVG */
.myToast-icon {
    margin-right: 12px;
    margin-top: 2px;
    width: 24px;
    height: 24px;
}

.myToast-icon svg {
    width: 100%;
    height: 100%;
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* Bagian Text */
.myToast-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.myToast-title {
    font-weight: 700;
    font-size: 16px;
    color: #333;
    margin-bottom: 4px;
}

.myToast-message {
    font-size: 14px;
    color: #666;
    line-height: 1.4;
}

/* Tombol X Close */
.myToast-close {
    position: absolute;
    top: 10px;
    right: 12px;
    font-size: 22px;
    color: #999;
    cursor: pointer;
    line-height: 1;
}

.myToast-close:hover {
    color: #333;
}

/* --- WARNA --- */
.myToast-success {
    border-left-color: #2ecc71;
}

.myToast-success .myToast-icon {
    color: #2ecc71;
}

.myToast-info {
    border-left-color: #3498db;
}

.myToast-info .myToast-icon {
    color: #3498db;
}

.myToast-warning {
    border-left-color: #f1c40f;
}

.myToast-warning .myToast-icon {
    color: #f1c40f;
}

.myToast-error {
    border-left-color: #e74c3c;
}

.myToast-error .myToast-icon {
    color: #e74c3c;
}

.myToast-question {
    border-left-color: #8e44ad;
}

.myToast-question .myToast-icon {
    color: #8e44ad;
}

/* --- TOMBOL AKSI (Question) --- */
.myToast-actions {
    margin-top: 12px;
    display: flex;
    gap: 10px;
}

.myToast-actions button {
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

<div class="myToast-container">
    <?php if (isset($_SESSION['flash']['type']) && $_SESSION['flash']['type'] === 'success'): ?>
    <div class="myToast myToast-success">
        <div class="myToast-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="myToast-content">
            <span class="myToast-title">Success</span>
            <span class="myToast-message"><?php echo $_SESSION['flash']['message']; ?></span>
        </div>
        <div class="myToast-close" onclick="closeMyToast(this)">&times;</div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash']['type']) && $_SESSION['flash']['type'] === 'info'): ?>
    <div class="myToast myToast-info">
        <div class="myToast-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
        </div>
        <div class="myToast-content">
            <span class="myToast-title">Info</span>
            <span class="myToast-message"><?php echo $_SESSION['flash']['message']; ?></span>
        </div>
        <div class="myToast-close" onclick="closeMyToast(this)">&times;</div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash']['type']) && $_SESSION['flash']['type'] === 'warning'): ?>
    <div class="myToast myToast-warning">
        <div class="myToast-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                </path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <div class="myToast-content">
            <span class="myToast-title">Warning</span>
            <span class="myToast-message"><?php echo $_SESSION['flash']['message']; ?></span>
        </div>
        <div class="myToast-close" onclick="closeMyToast(this)">&times;</div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash']['type']) && $_SESSION['flash']['type'] === 'error'): ?>
    <div class="myToast myToast-error">
        <div class="myToast-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="myToast-content">
            <span class="myToast-title">Error</span>
            <span class="myToast-message"><?php echo $_SESSION['flash']['message']; ?></span>
        </div>
        <div class="myToast-close" onclick="closeMyToast(this)">&times;</div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash']['type']) && $_SESSION['flash']['type'] === 'question'): ?>
    <div class="myToast myToast-question">
        <div class="myToast-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <div class="myToast-content">
            <span class="myToast-title">Konfirmasi</span>
            <span class="myToast-message"><?php echo $_SESSION['flash']['message']; ?></span>
            <div class="myToast-actions">
                <button class="btn-confirm" onclick="closeMyToast(this)">Ya</button>
                <button class="btn-cancel" onclick="closeMyToast(this)">Tidak</button>
            </div>
        </div>
        <div class="myToast-close" onclick="closeMyToast(this)">&times;</div>
    </div>
    <?php endif; ?>

</div>

<script>
function closeMyToast(element) {
    // Mencari elemen induk dengan class 'myToast'
    const myToast = element.closest('.myToast');

    // Menambahkan class 'closing' untuk memicu animasi CSS
    myToast.classList.add('closing');

    // Menunggu animasi selesai (300ms) baru menghapus elemen dari DOM
    setTimeout(() => {
        myToast.remove();
    }, 300);
}
</script>
<?php endif; ?>
<?php unset($_SESSION['flash']); ?>