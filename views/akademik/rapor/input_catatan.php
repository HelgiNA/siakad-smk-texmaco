<?php ob_start(); ?>

<style>
    :root {
        --c-primary: #0f172a;
        --c-accent: #3b82f6;
        --c-bg-page: #f8fafc;
        --c-border: #e2e8f0;
        --radius: 12px;
    }

    /* Student Info Card */
    .student-header {
        background: white; border-radius: var(--radius);
        padding: 20px; display: flex; align-items: center; gap: 15px;
        border: 1px solid var(--c-border);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .student-avatar {
        width: 50px; height: 50px; background: #eff6ff; color: var(--c-accent);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; border: 1px solid #bfdbfe;
    }
    .student-info h4 { margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; }
    .student-info p { margin: 2px 0 0; font-size: 0.9rem; color: #64748b; font-family: monospace; }

    /* Form Card */
    .form-card {
        background: white; border-radius: var(--radius);
        padding: 30px; border: 1px solid var(--c-border);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }

    /* Input Sections */
    .input-section { margin-bottom: 25px; }
    .label-custom {
        display: flex; align-items: center; gap: 8px;
        font-weight: 700; color: #334155; margin-bottom: 10px; font-size: 1rem;
    }
    .textarea-custom {
        width: 100%; border: 1px solid #cbd5e1; border-radius: 8px;
        padding: 15px; font-size: 0.95rem; line-height: 1.6; color: #1e293b;
        background: #fff; transition: 0.2s; min-height: 120px; resize: vertical;
    }
    .textarea-custom:focus {
        outline: none; border-color: var(--c-accent);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .helper-text {
        margin-top: 6px; font-size: 0.85rem; color: #64748b; font-style: italic;
    }

    /* Save Button */
    .btn-save {
        width: 100%; padding: 14px; background: var(--c-primary); color: white;
        border: none; border-radius: 8px; font-weight: 700; font-size: 1rem;
        cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-save:hover { background: #1e293b; transform: translateY(-1px); }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold" style="color: #1e293b;">Input Catatan Wali Kelas</h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Narasi perkembangan siswa di rapor.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/rapor" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
        
        <div class="student-header">
            <div class="student-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="student-info">
                <h4><?php echo htmlspecialchars($biodata['nama_lengkap']); ?></h4>
                <p>NIS: <?php echo htmlspecialchars($biodata['nis']); ?></p>
            </div>
        </div>

        <div class="form-card">
            <?php if (function_exists('showAlert')) showAlert(); ?>

            <form action="<?php echo BASE_URL; ?>/rapor/catatan/store" method="POST">
                <input type="hidden" name="siswa_id" value="<?php echo $biodata['siswa_id']; ?>">

                <div class="input-section">
                    <label for="sikap" class="label-custom text-primary">
                        <i class="bi bi-person-heart"></i> 1. Catatan Sikap & Karakter
                    </label>
                    <textarea name="catatan_sikap" id="sikap" class="textarea-custom" 
                        placeholder="Contoh: Ananda menunjukkan sikap sopan santun yang sangat baik, disiplin, dan memiliki kepedulian sosial yang tinggi..." 
                        required><?php echo htmlspecialchars($catatan['catatan_sikap'] ?? ''); ?></textarea>
                    <div class="helper-text">
                        <i class="bi bi-info-circle me-1"></i> Deskripsikan perkembangan perilaku, kedisiplinan, dan interaksi sosial siswa.
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e2e8f0; margin: 30px 0;">

                <div class="input-section">
                    <label for="akademik" class="label-custom text-success">
                        <i class="bi bi-journal-check"></i> 2. Catatan Akademik
                    </label>
                    <textarea name="catatan_akademik" id="akademik" class="textarea-custom" 
                        placeholder="Contoh: Prestasi akademik meningkat secara signifikan. Perlu mempertahankan konsistensi belajar, terutama pada mata pelajaran Eksakta..." 
                        required><?php echo htmlspecialchars($catatan['catatan_akademik'] ?? ''); ?></textarea>
                    <div class="helper-text">
                        <i class="bi bi-info-circle me-1"></i> Berikan motivasi, saran perbaikan, atau apresiasi atas capaian pembelajaran.
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-save2-fill"></i> Simpan Catatan Rapor
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";
?>
