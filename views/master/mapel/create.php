<?php
    // views/master/mapel/create.php
    ob_start();
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Mata Pelajaran</h3>
                    </div>

                    <form action="<?php echo BASE_URL;?>/mapel/store" method="POST">
                        <div class="card-body">

                            <?php if (isset($_SESSION['flash']['error'])): ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['flash']['error'];unset($_SESSION['flash']['error']);?></div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="kode_mapel">Kode Mapel</label>
                                <input type="text" class="form-control" id="kode_mapel" name="kode_mapel"
                                       placeholder="Contoh: MTK, WEB-1"
                                       style="text-transform: uppercase;" required>
                                <small class="text-muted">Kode mapel harus unik dan huruf besar.</small>
                            </div>

                            <div class="form-group">
                                <label for="nama_mapel">Nama Mapel</label>
                                <input type="text" class="form-control" id="nama_mapel" name="nama_mapel"
                                       placeholder="Contoh: Matematika Wajib" required>
                            </div>

                            <div class="form-group">
                                <label for="kkm">KKM</label>
                                <input type="number" class="form-control" id="kkm" name="kkm"
                                       value="75" min="0" max="100" required>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="<?php echo BASE_URL;?>/mapel" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
