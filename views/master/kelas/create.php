<?php ob_start(); ?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/kelas" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Kelas
    </a>
    <h1 class="page-title" style="margin:0;">Tambah Kelas Baru</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #e0f2fe; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0284c7;">
            <i class="bi bi-door-open-fill" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Informasi Rombel</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Buat rombongan belajar baru untuk tahun ajaran aktif.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/kelas/store" method="POST">
        <div class="form-body">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label" for="tingkat">
                        <i class="bi bi-layers"></i> Tingkat
                    </label>
                    <select class="form-select" id="tingkat" name="tingkat" required autofocus>
                        <option value="" disabled selected>-- Pilih Tingkat --</option>
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jurusan">
                        <i class="bi bi-cpu"></i> Jurusan / Kompetensi
                    </label>
                    <input type="text" class="form-input" id="jurusan" name="jurusan" 
                           placeholder="Contoh: TKJ, RPL, AKL" required>
                    <div class="form-text">Gunakan singkatan jurusan (Huruf Kapital).</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_kelas">
                        <i class="bi bi-tag"></i> Nama Kelas
                    </label>
                    <input type="text" class="form-input" id="nama_kelas" name="nama_kelas" 
                           placeholder="Contoh: X TKJ 1" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tahun_id">
                        <i class="bi bi-calendar-event"></i> Tahun Ajaran
                    </label>
                    <select class="form-select" id="tahun_id" name="tahun_id" required>
                        <option value="" disabled>-- Pilih Periode --</option>
                        <?php foreach ($data['tahuns'] as $tahun): ?>
                            <option value="<?php echo $tahun['tahun_id']; ?>" 
                                <?php echo $tahun['is_active'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tahun['tahun'] . ' - ' . $tahun['semester']); ?>
                                <?php echo $tahun['is_active'] ? '(Aktif Saat Ini)' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="guru_wali_id">
                        <i class="bi bi-person-badge"></i> Wali Kelas
                    </label>
                    <select class="form-select" id="guru_wali_id" name="guru_wali_id" required>
                        <option value="" disabled selected>-- Pilih Wali Kelas --</option>
                        <?php foreach ($data['gurus'] as $guru): ?>
                            <option value="<?php echo $guru['guru_id']; ?>">
                                <?php echo htmlspecialchars($guru['nama_lengkap']); ?> 
                                (NIP: <?php echo htmlspecialchars($guru['nip']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/kelas" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Simpan Kelas
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = [document.getElementById('jurusan'), document.getElementById('nama_kelas')];
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
    });
</script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
