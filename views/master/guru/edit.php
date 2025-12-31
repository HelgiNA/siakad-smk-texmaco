<?php
    // views/master/guru/edit.php
    ob_start();
?>

<div class="content">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Data Guru</h3>
        </div>

        <form action="<?php echo BASE_URL; ?>/guru/update" method="POST">
            <input type="hidden" name="guru_id" value="<?php echo $data['guru']['guru_id']; ?>">

            <div class="card-body">
                <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['flash']['error'];unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip"
                        value="<?php echo htmlspecialchars($guru['nip']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                        value="<?php echo htmlspecialchars($guru['nama_lengkap']); ?>" required>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Update</button>
                <a href="<?php echo BASE_URL; ?>/guru" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>