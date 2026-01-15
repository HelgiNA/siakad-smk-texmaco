<?php ob_start(); ?>

<style>
    /* 1. Wrapper untuk Input Tahun Split */
    .year-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .year-field {
        flex: 1;
        position: relative;
    }
    
    .year-separator {
        font-size: 1.5rem;
        color: #94a3b8;
        font-weight: 300;
    }

    /* Input Readonly (Tahun Akhir) */
    .input-readonly {
        background-color: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
        font-weight: 600;
    }

    /* Info Box */
    .info-box {
        background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;
        padding: 12px 15px; border-radius: 8px; font-size: 0.85rem;
        display: flex; gap: 10px; align-items: flex-start;
        margin-top: 20px;
    }

    /* Style dasar form (Copy dari style global agar mandiri atau ikut global) */
    .form-card { background: white; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid rgba(0,0,0,0.02); }
    .form-header { padding: 20px 30px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 15px; background-color: #f8fafc; }
    .form-title { font-size: 1.1rem; font-weight: 700; color: #334155; margin: 0; }
    .form-body { padding: 30px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-label { font-size: 0.9rem; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 8px; }
    .form-input, .form-select { padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; font-size: 0.95rem; transition: 0.2s; }
    .form-input:focus, .form-select:focus { border-color: #0d6efd; outline: none; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }
    .form-text { font-size: 0.8rem; color: #94a3b8; margin-top: 5px; }
    .form-footer { padding: 20px 30px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 15px; }
    
    /* Buttons */
    .btn-cancel { padding: 10px 20px; border-radius: 8px; text-decoration: none; color: #334155; background: white; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.9rem; }
    .btn-save { padding: 10px 25px; border-radius: 8px; background: #0d6efd; color: white; border: none; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .btn-save:hover { background: #0b5ed7; }
</style>

<div style="margin-bottom: 24px;">
    <a href="<?php echo BASE_URL; ?>/tahun-ajaran" style="text-decoration: none; color: #94a3b8; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Tahun Ajaran
    </a>
    <h1 class="page-title" style="margin:0; font-size: 1.5rem; font-weight: 700; color: #334155;">Buat Periode Baru</h1>
</div>

<div class="form-card">
    <div class="form-header">
        <div style="width: 40px; height: 40px; background: #e0e7ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #4338ca;">
            <i class="bi bi-calendar-range-fill" style="font-size: 1.2rem;"></i>
        </div>
        <div>
            <h3 class="form-title">Seting Tahun Akademik</h3>
            <span style="font-size: 0.85rem; color: #64748b;">Tentukan tahun dan semester yang akan berjalan.</span>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>/tahun-ajaran/store" method="POST" id="formTahun">
        <div class="form-body">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label class="form-label"><i class="bi bi-calendar-event"></i> Tahun Ajaran</label>
                    
                    <div class="year-input-group">
                        <div class="year-field">
                            <input type="number" id="tahunAwal" class="form-input" placeholder="2024" min="2000" max="2099" required autofocus>
                            <div class="form-text">Mulai</div>
                        </div>
                        
                        <div class="year-separator">/</div>
                        
                        <div class="year-field">
                            <input type="text" id="tahunAkhir" class="form-input input-readonly" placeholder="2025" readonly tabindex="-1">
                            <div class="form-text">Selesai</div>
                        </div>
                    </div>

                    <input type="hidden" name="tahun" id="tahunGabungan">
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-pie-chart"></i> Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                    <div class="form-text">Pilih semester yang berlaku.</div>
                </div>

            </div>

            <div class="info-box">
                <i class="bi bi-info-circle-fill" style="font-size: 1.1rem; margin-top: 2px;"></i>
                <div>
                    <strong>Catatan Sistem:</strong><br>
                    Status default periode baru adalah <strong>Non-Aktif</strong>. Anda dapat mengaktifkannya melalui halaman utama setelah data disimpan.
                </div>
            </div>

        </div>

        <div class="form-footer">
            <a href="<?php echo BASE_URL; ?>/tahun-ajaran" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-check-lg"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tahunAwal = document.getElementById('tahunAwal');
        const tahunAkhir = document.getElementById('tahunAkhir');
        const tahunGabungan = document.getElementById('tahunGabungan');
        const form = document.getElementById('formTahun');

        // Saat user mengetik tahun awal
        tahunAwal.addEventListener('input', function() {
            const val = parseInt(this.value);
            
            if (!isNaN(val) && this.value.length === 4) {
                // Kalkulasi Tahun Akhir
                tahunAkhir.value = val + 1;
                // Update Hidden Input untuk Database
                tahunGabungan.value = val + '/' + (val + 1);
            } else {
                tahunAkhir.value = '';
                tahunGabungan.value = '';
            }
        });

        // Validasi sebelum submit
        form.addEventListener('submit', function(e) {
            if(!tahunGabungan.value) {
                e.preventDefault();
                alert("Mohon isi tahun awal dengan benar (4 digit angka).");
                tahunAwal.focus();
            }
        });
    });
</script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
