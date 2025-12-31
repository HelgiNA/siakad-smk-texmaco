<?php ob_start(); ?>

<div class="content">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Guru</h3>
        </div>

        <form action="<?php echo BASE_URL; ?>/guru/store" method="POST">
            <div class="card-body">

                <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['flash']['error']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>

                <div class="form-group">
                    <label for="nip">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP">
                    <small class="form-text text-muted">NIP akan digunakan sebagai Username dan Password
                        default.</small>
                </div>
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                        placeholder="Nama Lengkap + Gelar">
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo BASE_URL; ?>/guru" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>