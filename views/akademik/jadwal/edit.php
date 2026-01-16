<?php
// Shortcut variable agar kode lebih bersih
$jadwal = $data['jadwal'];
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
    <a href="<?php echo BASE_URL; ?>/jadwal" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Jadwal
    </a>
    <h1 class="page-title" style="margin:0;">Edit Jadwal Pelajaran</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ea580c;">
            <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Perbarui Jadwal KBM</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Edit detail waktu dan guru pengampu.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/jadwal/update" method="POST" id="formJadwal">
        <input type="hidden" name="jadwal_id" value="<?php echo $jadwal['jadwal_id']; ?>">

        <div class="form-body">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label" for="kelas_id">
                        <i class="bi bi-door-open"></i> Kelas
                    </label>
                    <select class="form-select" id="kelas_id" name="kelas_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($data['kelas'] as $kls): ?>
                            <option value="<?php echo $kls['kelas_id']; ?>" 
                                <?php echo ($kls['kelas_id'] == $jadwal['kelas_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kls['nama_kelas']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="hari">
                        <i class="bi bi-calendar-day"></i> Hari
                    </label>
                    <select class="form-select" id="hari" name="hari" required>
                        <option value="">-- Pilih Hari --</option>
                        <?php foreach ($data['hari'] as $hr): ?>
                            <option value="<?php echo $hr; ?>" 
                                <?php echo ($hr == $jadwal['hari']) ? 'selected' : ''; ?>>
                                <?php echo $hr; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="mapel_id">
                        <i class="bi bi-journal-bookmark"></i> Mata Pelajaran
                    </label>
                    <select class="form-select" id="mapel_id" name="mapel_id" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($data['mapel'] as $mp): ?>
                            <option value="<?php echo $mp['mapel_id']; ?>" 
                                <?php echo ($mp['mapel_id'] == $jadwal['mapel_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mp['nama_mapel']); ?> (<?php echo htmlspecialchars($mp['kode_mapel']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="guru_id">
                        <i class="bi bi-person-video3"></i> Guru Pengampu
                    </label>
                    <select class="form-select" id="guru_id" name="guru_id" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($data['gurus'] as $gr): ?>
                            <option value="<?php echo $gr['guru_id']; ?>" 
                                <?php echo ($gr['guru_id'] == $jadwal['guru_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($gr['nama_lengkap']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jam_mulai">
                        <i class="bi bi-clock"></i> Jam Mulai
                    </label>
                    <input type="time" class="form-input" id="jam_mulai" name="jam_mulai" 
                           value="<?php echo htmlspecialchars($jadwal['jam_mulai']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jam_selesai">
                        <i class="bi bi-clock-history"></i> Jam Selesai
                    </label>
                    <input type="time" class="form-input" id="jam_selesai" name="jam_selesai" 
                           value="<?php echo htmlspecialchars($jadwal['jam_selesai']); ?>" required>
                </div>

            </div>

            <div id="timeError" style="display: none; color: #dc2626; font-size: 0.85rem; margin-top: 10px; font-weight: 600;">
                <i class="bi bi-exclamation-circle"></i> Jam selesai harus lebih besar dari jam mulai.
            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/jadwal" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-update">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jamMulai = document.getElementById('jam_mulai');
        const jamSelesai = document.getElementById('jam_selesai');
        const form = document.getElementById('formJadwal');
        const errorMsg = document.getElementById('timeError');

        function validateTime() {
            if (jamMulai.value && jamSelesai.value) {
                if (jamSelesai.value <= jamMulai.value) {
                    errorMsg.style.display = 'block';
                    jamSelesai.style.borderColor = '#dc2626';
                    return false;
                } else {
                    errorMsg.style.display = 'none';
                    jamSelesai.style.borderColor = '#e2e8f0'; 
                    return true;
                }
            }
            return true;
        }

        jamMulai.addEventListener('change', validateTime);
        jamSelesai.addEventListener('change', validateTime);

        form.addEventListener('submit', function(e) {
            if (!validateTime()) {
                e.preventDefault();
                alert('Mohon periksa kembali input waktu (Jam Selesai harus lebih akhir dari Jam Mulai).');
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>
