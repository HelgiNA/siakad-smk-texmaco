<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["alert"])):

    $alert = $_SESSION["alert"];
    $type = $alert["type"]; // success, info, warning, error

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
    /* Container pembungkus (Opsional, hanya untuk demo ini) */
    .alert-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 600px;
        margin: 20px auto;
        font-family:
            system-ui,
            -apple-system,
            sans-serif;
    }

    /* Base Alert Style */
    .alert {
        position: relative;
        padding: 16px 20px;
        border-radius: 8px;
        border-left: 5px solid;
        display: flex;
        align-items: flex-start;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Icon Style */
    .alert-icon {
        width: 24px;
        height: 24px;
        margin-right: 15px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .alert-icon svg {
        width: 100%;
        height: 100%;
    }

    /* Content Text */
    .alert-content {
        flex: 1;
    }
    .alert-content strong {
        display: block;
        font-size: 16px;
        margin-bottom: 4px;
    }
    .alert-content p {
        font-size: 14px;
        margin: 0;
        line-height: 1.5;
        opacity: 0.9;
    }

    /* Close Button (X) */
    .alert-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.6;
        padding: 0;
        margin-left: 10px;
        line-height: 1;
    }
    .alert-close:hover {
        opacity: 1;
    }

    /* --- TEMA WARNA (Background Tint + Border) --- */

    /* 1. Success (Green) */
    .alert-success {
        background-color: #e8f5e9; /* Hijau muda sekali */
        border-left-color: #2e7d32;
        color: #1b5e20;
    }

    /* 2. Info (Blue) */
    .alert-info {
        background-color: #e3f2fd; /* Biru muda sekali */
        border-left-color: #1976d2;
        color: #0d47a1;
    }

    /* 3. Warning (Orange/Amber) */
    .alert-warning {
        background-color: #fff8e1; /* Kuning muda sekali */
        border-left-color: #ffa000;
        color: #e65100;
    }

    /* 4. Error (Red) */
    .alert-error {
        background-color: #ffebee; /* Merah muda sekali */
        border-left-color: #d32f2f;
        color: #b71c1c;
    }

    /* 5. Question (Purple) */
    .alert-question {
        background-color: #f3e5f5; /* Ungu muda sekali */
        border-left-color: #7b1fa2;
        color: #4a148c;
    }

    /* Tombol Khusus di dalam Alert Question */
    .alert-buttons {
        margin-top: 12px;
        display: flex;
        gap: 10px;
    }
    .alert-buttons button {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
    }

    /* Tombol Primary (Solid) */
    .btn-alert-primary {
        background-color: #7b1fa2;
        color: white;
    }
    .btn-alert-primary:hover {
        background-color: #6a1b9a;
    }

    /* Tombol Secondary (Outline/Soft) */
    .btn-alert-secondary {
        background-color: rgba(123, 31, 162, 0.1);
        color: #7b1fa2;
    }
    .btn-alert-secondary:hover {
        background-color: rgba(123, 31, 162, 0.2);
    }
</style>

<div class="alert-wrapper" style="margin-bottom: 20px;">
    <div class="alert alert-<?= $type ?>">
        <div class="alert-icon">
            <?= $icon ?>
        </div>
        <div class="alert-content">
            <strong><?php echo htmlspecialchars(
                $alert["title"] ?? ""
            ); ?></strong>
            <p><? echo htmlspecialchars($alert["message"]) ?></p>
            <?php if ($type == "question"): ?>
                <!-- html... -->
            <div class="alert-buttons">
                <button class="btn-alert-primary">Mulai Ujian</button>
                <button class="btn-alert-secondary">Nanti Saja</button>
            </div>
            <?php endif; ?>
            
        </div>
        <button class="alert-close" onclick="this.parentElement.parentElement.style.display='none';">&times;</button>
    </div>
</div>

<?php // Hapus session agar alert tidak muncul terus menerus saat refresh

    unset($_SESSION["alert"]);
endif;
?>
