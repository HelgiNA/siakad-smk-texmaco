<?php ob_start(); ?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/guru" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Guru
    </a>
    <h1 class="page-title" style="margin:0;">Tambah Guru Baru</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ea580c;">
            <i class="bi bi-briefcase-fill" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Data Kepegawaian</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Lengkapi informasi tenaga pengajar di bawah ini.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/guru/store" method="POST">
        <div class="form-body">

            <div class="info-box">
                <i class="bi bi-info-circle-fill" style="margin-top:2px;"></i>
                <div>
                    <strong>Informasi Login Otomatis:</strong><br>
                    NIP yang Anda masukkan akan otomatis digunakan sebagai <strong>Username</strong> dan <strong>Password</strong> default untuk guru tersebut login.
                </div>
            </div>
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label" for="nip">
                        <i class="bi bi-card-text"></i> NIP (Nomor Induk Pegawai)
                    </label>
                    <input type="text" class="form-input" id="nip" name="nip" placeholder="Contoh: 19800101 200501 1 001" required autofocus>
                    <div class="form-text">Pastikan NIP unik dan belum terdaftar.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">
                        <i class="bi bi-person-vcard"></i> Nama Lengkap
                    </label>
                    <input type="text" class="form-input" id="nama_lengkap" name="nama_lengkap" placeholder="Contoh: Budi Santoso, S.Pd., M.Pd." required>
                    <div class="form-text">Sertakan gelar akademik lengkap.</div>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/guru" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
