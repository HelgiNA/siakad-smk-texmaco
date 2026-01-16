<?php ob_start(); ?>

<style>
    /* Reset & Base */
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh; /* Agar vertikal center */
        padding: 20px;
    }

    /* Card Design */
    .login-card {
        background: white;
        width: 100%;
        max-width: 400px;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        padding: 40px;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    
    /* Aksen Garis Atas */
    .login-card::before {
        content: '';
        position: absolute; top: 0; left: 0; width: 100%; height: 5px;
        background: linear-gradient(90deg, #2563eb, #0ea5e9);
    }

    /* Header Section */
    .login-header { text-align: center; margin-bottom: 30px; }
    .logo-icon {
        width: 60px; height: 60px;
        background: #eff6ff; color: #2563eb;
        border-radius: 12px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 32px; margin-bottom: 15px;
    }
    .app-name { font-size: 24px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; margin: 0; }
    .app-desc { color: #64748b; font-size: 14px; margin-top: 5px; }

    /* Form Elements */
    .form-group { margin-bottom: 20px; position: relative; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    
    .input-group { position: relative; }
    .input-icon {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 18px; transition: 0.2s;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 15px 12px 45px; /* Padding kiri buat icon */
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        color: #1e293b;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    
    /* State Focus */
    .form-input:focus {
        background: white;
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .form-input:focus + .input-icon { color: #2563eb; } /* Icon berubah warna saat fokus */

    /* Button */
    .btn-login {
        width: 100%;
        padding: 14px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        margin-top: 10px;
    }
    .btn-login:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* Footer */
    .login-footer {
        text-align: center; margin-top: 25px;
        font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        
        <div class="login-header">
            <div class="logo-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1 class="app-name">SIAKAD TEXMACO</h1>
            <p class="app-desc">Sistem Informasi Akademik Sekolah</p>
        </div>

        <?php if (function_exists('showFlash')) showFlash(); ?>

        <form action="/login" method="post">
            
            <div class="form-group">
                <label class="form-label" for="username">Username / NIS</label>
                <div class="input-group">
                    <input type="text" name="username" id="username" class="form-input" 
                           placeholder="Masukkan ID Pengguna" required autofocus autocomplete="off">
                    <i class="bi bi-person input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-input" 
                           placeholder="Masukkan Kata Sandi" required>
                    <i class="bi bi-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Masuk Sistem <i class="bi bi-arrow-right"></i>
            </button>

        </form>

        <div class="login-footer">
            &copy; <?php echo date('Y'); ?> SMK Texmaco Subang.<br>
            All rights reserved. v1.0
        </div>

    </div>
</div>

<?php
    $content = ob_get_clean();
    // Pastikan layout auth.php hanya berisi struktur HTML dasar (head, body, script) 
    // tanpa container bootstrap tambahan agar style di atas bekerja optimal.
    require_once __DIR__ . '/../layouts/auth.php';
?>
