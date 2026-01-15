<?php ob_start(); ?>

<style>
    /* Tombol Update berwarna Oranye/Kuning Gelap */
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

    /* Input Readonly Style */
    .input-locked {
        background-color: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }
    
    /* Alert Soft Warning */
    .alert-warning-soft {
        background-color: #fffbeb;
        border: 1px solid #fcd34d;
        color: #92400e;
        padding: 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
    }
</style>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/siswa" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
    </a>
    <h1 class="page-title" style="margin:0;">Edit Data Siswa</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
            <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Perbarui Informasi Akademik</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Edit detail siswa <strong><?php echo htmlspecialchars(
                $student["nama_lengkap"]
            ); ?></strong></span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/siswa/update" method="post">
        <input type="hidden" name="id" value="<?php echo $student[
            "siswa_id"
        ]; ?>">
        <input type="hidden" name="user_id" value="<?php echo $student[
            "user_id"
        ]; ?>">

        <div class="form-body">
            
            <div class="alert-warning-soft">
                <i class="bi bi-exclamation-triangle-fill" style="margin-top: 2px;"></i>
                <span><strong>Perhatian:</strong> NIS tidak dapat diubah karena terhubung langsung dengan Akun Login siswa. Hubungi Admin Database jika terjadi kesalahan input fatal.</span>
            </div>

            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-card-heading"></i> NIS
                    </label>
                    <input type="text" name="nis" class="form-input input-locked" value="<?php echo htmlspecialchars(
                        $student["nis"]
                    ); ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-globe"></i> NISN
                    </label>
                    <input type="text" name="nisn" class="form-input input-locked" value="<?php echo htmlspecialchars(
                        $student["nisn"]
                    ); ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person-lines-fill"></i> Nama Lengkap
                    </label>
                    <input type="text" name="nama_lengkap" class="form-input" value="<?php echo htmlspecialchars(
                        $student["nama_lengkap"]
                    ); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-calendar-date"></i> Tanggal Lahir
                    </label>
                    <input type="date" name="tanggal_lahir" class="form-input" value="<?php echo $student[
                        "tanggal_lahir"
                    ]; ?>" required>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">
                        <i class="bi bi-geo-alt"></i> Alamat Lengkap
                    </label>
                    <textarea name="alamat" class="form-textarea" rows="3"><?php echo htmlspecialchars(
                        $student["alamat"]
                    ); ?></textarea>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/siswa" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-update">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";

?>
