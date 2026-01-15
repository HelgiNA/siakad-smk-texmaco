<?php ob_start(); ?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL ?>/users" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
    </a>
    <h1 class="page-title" style="margin:0;">Registrasi Pengguna Baru</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
            <i class="bi bi-person-plus-fill" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Informasi Akun</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Lengkapi data di bawah ini untuk membuat akses baru.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL ?>/users/store" method="post">
        <div class="form-body">
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person"></i> Username
                    </label>
                    <input type="text" name="username" class="form-input" placeholder="Contoh: 10293847" required autofocus>
                    <div class="form-text">Gunakan NIS/NIP agar mudah diingat, atau username unik lainnya.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-shield-check"></i> Peran (Role)
                    </label>
                    <select name="role" class="form-select">
                        <option value="Siswa">Siswa</option>
                        <option value="Guru">Guru</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <div class="form-text">Role menentukan hak akses menu pengguna.</div>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="passwordInput" class="form-input" placeholder="Masukkan password minimal 6 karakter" required minlength="6">
                        <i class="bi bi-eye-slash password-toggle" id="togglePassword" title="Lihat Password"></i>
                    </div>
                    <div class="form-text">Disarankan menggunakan kombinasi huruf dan angka.</div>
                </div>
            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL ?>/users" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#passwordInput');

    togglePassword.addEventListener('click', function (e) {
        // Toggle tipe input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle icon
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
        
        // Toggle warna agar user tahu sedang aktif
        if(type === 'text') {
            this.style.color = 'var(--primary)';
        } else {
            this.style.color = '#94a3b8';
        }
    });
</script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../layouts/main.php';
?>
