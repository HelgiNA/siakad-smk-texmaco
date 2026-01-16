<?php
// Helper variable agar penulisan lebih pendek
$mapel = $data["mapel"];
ob_start();
?>

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
    <a href="<?php echo BASE_URL; ?>/mapel" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Mapel
    </a>
    <h1 class="page-title" style="margin:0;">Edit Mata Pelajaran</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ea580c;">
            <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Perbarui Kurikulum</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Edit detail mata pelajaran <strong><?php echo htmlspecialchars(
                $mapel["nama_mapel"]
            ); ?></strong></span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/mapel/update" method="POST">
        <input type="hidden" name="mapel_id" value="<?php echo $mapel[
            "mapel_id"
        ]; ?>">

        <div class="form-body">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label" for="kode_mapel">
                        <i class="bi bi-upc-scan"></i> Kode Mapel
                    </label>
                    <input type="text" class="form-input" id="kode_mapel" name="kode_mapel" 
                           value="<?php echo htmlspecialchars(
                               $mapel["kode_mapel"]
                           ); ?>" 
                           placeholder="Contoh: MTK" required>
                    <div class="form-text">Pastikan kode tetap unik jika diubah.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_mapel">
                        <i class="bi bi-journal-text"></i> Nama Mata Pelajaran
                    </label>
                    <input type="text" class="form-input" id="nama_mapel" name="nama_mapel" 
                           value="<?php echo htmlspecialchars(
                               $mapel["nama_mapel"]
                           ); ?>" 
                           placeholder="Contoh: Matematika Wajib" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kelompok">
                        <i class="bi bi-collection"></i> Kelompok Mapel
                    </label>
                    <select class="form-select" id="kelompok" name="kelompok" required>
                        <option value="" selected disabled>-- Pilih Kelompok --</option>
                        <option value="A" <?php echo $mapel["kelompok"] == "A"
                            ? "selected"
                            : ""; ?>>Kelompok A (Muatan Nasional)</option>
                        <option value="B" <?php echo $mapel["kelompok"] == "B"
                            ? "selected"
                            : ""; ?>>Kelompok B (Muatan Kewilayahan)</option>
                        <option value="C1" <?php echo $mapel["kelompok"] == "C1"
                            ? "selected"
                            : ""; ?>>Kelompok C1 (Dasar Bidang Keahlian)</option>
                        <option value="C2" <?php echo $mapel["kelompok"] == "C2"
                            ? "selected"
                            : ""; ?>>Kelompok C2 (Dasar Program Keahlian)</option>
                        <option value="C3" <?php echo $mapel["kelompok"] == "C3"
                            ? "selected"
                            : ""; ?>>Kelompok C3 (Kompetensi Keahlian)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kkm">
                        <i class="bi bi-bar-chart-steps"></i> KKM
                    </label>
                    <input type="number" class="form-input" id="kkm" name="kkm" 
                           value="<?php echo htmlspecialchars(
                               $mapel["kkm"]
                           ); ?>" 
                           min="0" max="100" required>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/mapel" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-update">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
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
require_once __DIR__ . "/../../layouts/main.php";


?>
