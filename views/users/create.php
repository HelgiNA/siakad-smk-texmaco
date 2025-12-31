<?php ob_start(); ?>

<section class="content">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Registrasi</h3>
        </div>

        <form action="<?php echo BASE_URL ?>/users/store" method="post">
            <div class="card-body">

                <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['flash']['error'];unset($_SESSION['flash']['error']); ?>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username unik"
                        required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Password minimal 6 karakter" required>
                    <small class="text-muted">Password akan otomatis dienkripsi sistem.</small>
                </div>

                <div class="form-group">
                    <label>Role (Peran)</label>
                    <select name="role" class="form-control">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL ?>/users" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-primary float-right">Simpan User</button>
            </div>
        </form>
    </div>
</section>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>