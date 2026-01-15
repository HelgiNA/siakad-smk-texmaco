<?php ob_start(); ?>

<div class="login-box">

    <?php if (function_exists('showFlash')) showFlash(); ?>

    <div class="card">
        <div class="text-center mb-4">
            <i class="bi bi-mortarboard-fill" style="font-size: 40px; color: var(--primary);"></i>
            
            <a href="#" class="brand-logo mt-2">
                <b>SIAKAD</b> TEXMACO
            </a>
            <p class="login-msg">Masuk untuk mengakses sistem akademik</p>
        </div>

        <form action="/login" method="post">
            
            <div class="input-wrapper">
                <i class="bi bi-person icon"></i>
                <input type="text" name="username" class="form-control" placeholder="Username / NIS" required autofocus>
            </div>

            <div class="input-wrapper">
                <i class="bi bi-lock icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sistem
            </button>

        </form>

        <div class="auth-footer">
            &copy; <?php echo date('Y'); ?> SMK Texmaco.<br>All rights reserved.
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../layouts/auth.php';
?>
