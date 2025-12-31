<?php ob_start(); ?>

<?php if (isset($_SESSION['flash']['success'])): ?>
<div class="alert alert-success"><?php echo $_SESSION['flash']['success'];unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>
<section class="content">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Data</h3>
        </div>

        <form action="<?php echo BASE_URL ?>/users/update" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id'] ?>">
            <div class="card-body">
                <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['flash']['error'];unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Kosongkan jika tidak ingin mengganti password">
                    <small class="text-muted">Hanya isi jika ingin mereset password user ini.</small>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="Siswa" <?php echo $user['role'] == 'Siswa' ? 'selected' : '' ?>>Siswa</option>
                        <option value="Guru" <?php echo $user['role'] == 'Guru' ? 'selected' : '' ?>>Guru</option>
                        <option value="Admin" <?php echo $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>

            <div class="card-footer">
                <a href="<?php echo BASE_URL ?>/users" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-warning float-right">Update User</button>
            </div>
        </form>
    </div>
</section>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>