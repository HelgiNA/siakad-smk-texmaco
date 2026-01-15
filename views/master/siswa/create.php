<?php ob_start(); ?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/siswa" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
    </a>
    <h1 class="page-title" style="margin:0;">Tambah Siswa Baru</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
            <i class="bi bi-mortarboard-fill" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Data Akademik & Pribadi</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Pastikan NIS dan NISN unik dan belum terdaftar.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/siswa/store" method="post">
        <div class="form-body">
            
            <div class="info-box">
                <i class="bi bi-info-circle-fill" style="margin-top:2px;"></i>
                <div>
                    <strong>Informasi Login Otomatis:</strong><br>
                    NIS yang Anda masukkan akan otomatis digunakan sebagai <strong>Username</strong> dan <strong>Password</strong> default untuk siswa tersebut login.
                </div>
            </div>
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label"><i class="bi bi-card-heading"></i> NIS (Nomor Induk Siswa)</label>
                    <input type="number" name="nis" class="form-input" placeholder="Contoh: 21221005" required>
                    <div class="form-text">Nomor identitas lokal sekolah.</div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-globe"></i> NISN</label>
                    <input type="number" name="nisn" class="form-input" placeholder="Contoh: 0051234567" required>
                    <div class="form-text">Nomor Induk Siswa Nasional (10 Digit).</div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-person-lines-fill"></i> Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-input" placeholder="Masukkan nama lengkap siswa" required>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-calendar-date"></i> Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-input" required>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label"><i class="bi bi-geo-alt"></i> Alamat Lengkap</label>
                    <textarea name="alamat" class="form-textarea" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/siswa" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Simpan Siswa
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
