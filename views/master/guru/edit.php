<?php ob_start(); ?>

<style>
    .btn-update {
        padding: 10px 25px;
        border-radius: 8px;
        background: #f59e0b; /* Amber 500 */
        color: white;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: 0.2s;
    }
    .btn-update:hover { background: #d97706; transform: translateY(-1px); }
</style>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/guru" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Guru
    </a>
    <h1 class="page-title" style="margin:0;">Edit Data Guru</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ea580c;">
            <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Perbarui Data Pengajar</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Edit informasi untuk <strong><?php echo htmlspecialchars($guru['nama_lengkap']); ?></strong></span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/guru/update" method="POST">
        <input type="hidden" name="guru_id" value="<?php echo $guru['guru_id']; ?>">

        <div class="form-body">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label" for="nip">
                        <i class="bi bi-card-text"></i> NIP
                    </label>
                    <input type="text" class="form-input" id="nip" name="nip" 
                           value="<?php echo htmlspecialchars($guru['nip']); ?>" required>
                    <div class="form-text">Perubahan NIP juga akan mengubah Username login guru ini.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">
                        <i class="bi bi-person-vcard"></i> Nama Lengkap
                    </label>
                    <input type="text" class="form-input" id="nama_lengkap" name="nama_lengkap" 
                           value="<?php echo htmlspecialchars($guru['nama_lengkap']); ?>" required>
                    <div class="form-text">Pastikan gelar akademik tertulis dengan benar.</div>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/guru" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-update">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
