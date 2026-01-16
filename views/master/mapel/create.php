<?php ob_start(); ?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/mapel" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Mapel
    </a>
    <h1 class="page-title" style="margin:0;">Tambah Mata Pelajaran</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #ecfeff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #06b6d4;">
            <i class="bi bi-journal-bookmark-fill" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Data Kurikulum</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Tambahkan mata pelajaran baru ke dalam sistem.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/mapel/store" method="POST">
        <div class="form-body">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label" for="kode_mapel">
                        <i class="bi bi-upc-scan"></i> Kode Mapel
                    </label>
                    <input type="text" class="form-input" id="kode_mapel" name="kode_mapel" 
                           placeholder="Contoh: MTK, IND, WEB-1" required autofocus>
                    <div class="form-text">Kode harus unik (Maks 10 karakter). Otomatis huruf besar.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_mapel">
                        <i class="bi bi-journal-text"></i> Nama Mata Pelajaran
                    </label>
                    <input type="text" class="form-input" id="nama_mapel" name="nama_mapel" 
                           placeholder="Contoh: Matematika Wajib" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kelompok">
                        <i class="bi bi-collection"></i> Kelompok Mapel
                    </label>
                    <select class="form-select" id="kelompok" name="kelompok" required>
                        <option value="" disabled selected>-- Pilih Kelompok --</option>
                        <option value="A">Kelompok A (Muatan Nasional)</option>
                        <option value="B">Kelompok B (Muatan Kewilayahan)</option>
                        <option value="C1">Kelompok C1 (Dasar Bidang Keahlian)</option>
                        <option value="C2">Kelompok C2 (Dasar Program Keahlian)</option>
                        <option value="C3">Kelompok C3 (Kompetensi Keahlian)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kkm">
                        <i class="bi bi-bar-chart-steps"></i> KKM (Kriteria Ketuntasan)
                    </label>
                    <input type="number" class="form-input" id="kkm" name="kkm" 
                           value="75" min="0" max="100" required>
                    <div class="form-text">Nilai minimal kelulusan (0-100).</div>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/mapel" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kodeInput = document.getElementById('kode_mapel');
        
        kodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
</script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
