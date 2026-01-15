<?php ob_start(); ?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/users" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
    </a>
    <h1 class="page-title" style="margin:0;">Edit Data Pengguna</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
            <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Perbarui Informasi</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Edit detail akun <strong><?php echo htmlspecialchars(
                $user["username"]
            ); ?></strong></span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/users/update" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user[
            "user_id"
        ]; ?>">

        <div class="form-body">
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person"></i> Username
                    </label>
                    <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars(
                        $user["username"]
                    ); ?>" required>
                    <div class="form-text" style="font-size:0.8rem; color:#94a3b8;">Pastikan username tetap unik.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-shield-check"></i> Peran (Role)
                    </label>
                    <select name="role" class="form-select">
                        <option value="Siswa" <?php echo $user["role"] ==
                        "Siswa"
                            ? "selected"
                            : ""; ?>>Siswa</option>
                        <option value="Guru" <?php echo $user["role"] == "Guru"
                            ? "selected"
                            : ""; ?>>Guru</option>
                        <option value="Admin" <?php echo $user["role"] ==
                        "Admin"
                            ? "selected"
                            : ""; ?>>Admin</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: 1 / -1; margin-top: 10px;">
                    <label class="form-label">
                        <i class="bi bi-key"></i> Password Baru
                    </label>
                    
                    <div class="alert-info-soft">
                        <i class="bi bi-info-circle-fill" style="margin-top: 2px;"></i>
                        <span>Biarkan kolom ini <strong>kosong</strong> jika Anda tidak ingin mengubah password pengguna.</span>
                    </div>

                    <div class="password-wrapper">
                        <input type="password" name="password" id="passwordInput" class="form-input" placeholder="Masukkan password baru (Opsional)">
                        <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/users" class="btn-cancel">Batal</a>
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#passwordInput');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
        
        if(type === 'text') {
            this.style.color = 'var(--primary)';
        } else {
            this.style.color = '#94a3b8';
        }
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../layouts/main.php";


?>
