<?php ob_start(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $title ?? 'Dashboard'; ?></h3>

                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                Selamat Datang, <b><?php echo $_SESSION['username'] ?? 'Guest'; ?></b>! <br>
                Start creating your amazing application!
            </div>
            <div class="card-footer">Footer</div>
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();
    // Pastikan path ke layout main benar
require_once __DIR__ . '/layouts/main.php';
?>