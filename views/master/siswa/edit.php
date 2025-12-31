<?php ob_start(); ?>

<section class="content">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Edit Siswa</h3>
        </div>

        <form action="<?php echo BASE_URL ?>/siswa/update" method="post">
            <input type="hidden" name="id" value="<?php echo $student['siswa_id'] ?>">
            <input type="hidden" name="user_id" value="<?php echo $student['user_id'] ?>">
            <div class="card-body">

                <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['flash']['error'];unset($_SESSION['flash']['error']); ?>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" name="nis" class="form-control bg-light" value="<?php echo $student['nis'] ?>"
                        readonly>
                    <small class="text-danger">NIS tidak dapat diubah karena terhubung dengan Akun Login.</small>
                </div>

                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control bg-light" value="<?php echo $student['nisn'] ?>"
                        readonly>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap"
                        value="<?php echo $student['nama_lengkap'] ?>">
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        value="<?php echo $student['tanggal_lahir'] ?>">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" rows="3" placeholder="Enter ..."
                        name="alamat"><?php echo $student['alamat'] ?></textarea>
                </div>

            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL ?>/siswa" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-primary float-right">Simpan Siswa</button>
            </div>
        </form>
    </div>
</section>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>