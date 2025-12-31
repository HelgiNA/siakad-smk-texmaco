<?php ob_start(); ?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>SIAKAD</b> TEXMACO</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan login untuk masuk sistem</p>

            <form action="/login" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username">
                    <div class="input-group-text">
                        <span class="bi bi-person"></span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-text">
                        <span class="bi bi-lock"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();
    // Pastikan path ke layout main benar
require_once __DIR__ . '/../layouts/auth.php';
?>