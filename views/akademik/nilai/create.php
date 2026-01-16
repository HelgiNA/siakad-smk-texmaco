<?php ob_start(); ?>

<style type="text/css">
    :root {
        --c-primary: #2563eb;
        --c-border: #e2e8f0;
        --c-text-main: #334155;
        --c-bg-header: #f8fafc;
        --radius: 12px;
    }

    /* Layout Utama */
    .att-card { 
        background: white; 
        width: 100%; 
        border-radius: var(--radius); 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); 
        padding: 24px; 
        display: flex; flex-direction: column; gap: 20px; 
        border: 1px solid rgba(0,0,0,0.02);
    }

    /* 1. Header Statistik */
    .att-header { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
    .att-stat { 
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 10px; border: 1px solid var(--c-border); 
        border-radius: 8px; min-width: 80px; text-align: center;
        background: #fdfdfd; transition: 0.2s;
    }
    .att-stat__count { font-weight: 800; font-size: 1.2rem; color: var(--c-primary); margin-bottom: 2px; } 
    .att-stat__label { font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; }

    /* 2. Container Table (Scrollable X) */
    .att-table-wrapper { 
        border: 1px solid var(--c-border); 
        border-radius: 10px; 
        overflow-x: auto; 
        position: relative;
        background: white;
    }
    
    .att-table { 
        width: 100%; 
        border-collapse: separate; /* Penting untuk Sticky */
        border-spacing: 0;
        min-width: 600px; /* Paksa scroll di HP */
    }

    .att-table th, .att-table td {
        padding: 12px 10px;
        border-bottom: 1px solid var(--c-border);
        vertical-align: middle;
    }

    .att-table th {
        background: var(--c-bg-header);
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #475569;
        text-align: center;
        position: sticky; top: 0; z-index: 20;
    }

    /* --- STICKY NAME COLUMN (The Magic) --- */
    .col-sticky {
        position: sticky; 
        left: 0; 
        background: white; 
        z-index: 10;
        border-right: 2px solid #f1f5f9; /* Shadow border */
    }
    .att-table th.col-sticky {
        background: var(--c-bg-header);
        z-index: 30; /* Header nama harus paling atas */
    }

    /* Input Styles */
    .inp-grade {
        width: 100%; max-width: 70px;
        text-align: center; padding: 8px 4px;
        border: 1px solid #cbd5e1; border-radius: 6px;
        font-weight: 600; font-size: 0.95rem; color: #334155;
        transition: all 0.15s;
    }
    .inp-grade:focus {
        border-color: var(--c-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none; background: #fff;
    }
    /* Chrome remove spin buttons */
    .inp-grade::-webkit-outer-spin-button, .inp-grade::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* Final Score Column */
    .score-final { font-weight: 800; font-size: 1rem; color: #334155; }
    .score-final.remedial { color: #ef4444; } /* Merah jika < 75 */
    .score-final.lulus { color: #10b981; }    /* Hijau jika >= 75 */

    /* Footer */
    .att-footer { margin-top: 5px; }
    .btn-save-grade { 
        width: 100%; padding: 14px; background-color: var(--c-primary); 
        color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; 
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2); transition: 0.2s;
    }
    .btn-save-grade:hover { background-color: #1d4ed8; transform: translateY(-1px); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold" style="color: #1e293b;">Input Nilai Akademik</h4>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">
            <?php echo htmlspecialchars($kelas["nama_kelas"]); ?> &bull; 
            <?php echo htmlspecialchars($mapel["nama_mapel"]); ?>
        </p>
    </div>
    <a href="<?php echo BASE_URL; ?>/nilai" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="<?php echo BASE_URL; ?>/nilai/store" method="POST" id="formNilai">
    <input type="hidden" name="kelas_id" value="<?php echo $kelas["kelas_id"]; ?>">
    <input type="hidden" name="mapel_id" value="<?php echo $mapel["mapel_id"]; ?>">
    
    <div class="att-card">
        
        <header class="att-header">
            <div class="att-stat">
                <span class="att-stat__count" id="stat-avg">0</span>
                <span class="att-stat__label">Rata-rata</span>
            </div>
            <div class="att-stat">
                <span class="att-stat__count" style="color: #10b981;" id="stat-max">0</span>
                <span class="att-stat__label">Tertinggi</span>
            </div>
            <div class="att-stat">
                <span class="att-stat__count" style="color: #ef4444;" id="stat-min">0</span>
                <span class="att-stat__label">Terendah</span>
            </div>
            <div class="att-stat" style="background: #eff6ff; border-color: #bfdbfe;">
                <span class="att-stat__count" style="color: #2563eb;" id="stat-pass">0</span>
                <span class="att-stat__label">Tuntas (KKM 75)</span>
            </div>
        </header>

        <div class="att-table-wrapper">
            <table class="att-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th class="col-sticky" style="width: 200px; text-align: left;">Nama Siswa</th>
                        <th style="width: 100px;">Tugas <br><span style="font-weight:400; font-size:0.7rem;">(30%)</span></th>
                        <th style="width: 100px;">UTS <br><span style="font-weight:400; font-size:0.7rem;">(30%)</span></th>
                        <th style="width: 100px;">UAS <br><span style="font-weight:400; font-size:0.7rem;">(40%)</span></th>
                        <th style="width: 80px;">Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($siswa as $idx => $s):
                        // Ambil nilai tersimpan atau 0
                        $tugas = $savedDetails[$s["siswa_id"]]["tugas"] ?? "";
                        $uts   = $savedDetails[$s["siswa_id"]]["uts"] ?? "";
                        $uas   = $savedDetails[$s["siswa_id"]]["uas"] ?? "";
                        
                        // Hitung nilai awal jika ada
                        $t = floatval($tugas); $u = floatval($uts); $a = floatval($uas);
                        $akhir = ($t * 0.3) + ($u * 0.3) + ($a * 0.4);
                        
                        $readonly = ($existing && $existing["status_validasi"] === "Final") ? "readonly disabled" : "";
                    ?>
                    <tr class="row-student">
                        <td class="text-center text-muted"><?php echo $idx + 1; ?></td>
                        
                        <td class="col-sticky">
                            <div style="font-weight: 600; color: #1e293b; line-height: 1.2;">
                                <?php echo htmlspecialchars($s["nama_lengkap"]); ?>
                            </div>
                            <small class="text-muted" style="font-family: monospace;">
                                <?php echo htmlspecialchars($s["nis"]); ?>
                            </small>
                        </td>

                        <td class="text-center">
                            <input type="number" name="nilai[<?php echo $s["siswa_id"]; ?>][tugas]" 
                                   class="inp-grade inp-tugas" value="<?php echo $tugas; ?>" 
                                   min="0" max="100" step="0.01" placeholder="0"
                                   <?php echo $readonly; ?>>
                        </td>
                        <td class="text-center">
                            <input type="number" name="nilai[<?php echo $s["siswa_id"]; ?>][uts]" 
                                   class="inp-grade inp-uts" value="<?php echo $uts; ?>" 
                                   min="0" max="100" step="0.01" placeholder="0"
                                   <?php echo $readonly; ?>>
                        </td>
                        <td class="text-center">
                            <input type="number" name="nilai[<?php echo $s["siswa_id"]; ?>][uas]" 
                                   class="inp-grade inp-uas" value="<?php echo $uas; ?>" 
                                   min="0" max="100" step="0.01" placeholder="0"
                                   <?php echo $readonly; ?>>
                        </td>

                        <td class="text-center">
                            <span class="score-final" id="final-<?php echo $s["siswa_id"]; ?>">
                                <?php echo ($tugas === "" && $uts === "" && $uas === "") ? "0" : number_format($akhir, 2); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!$existing || $existing["status_validasi"] !== "Final"): ?>
        <div class="att-footer">
            <button type="submit" class="btn-save-grade">
                <i class="bi bi-save2-fill me-2"></i> Simpan Data Nilai
            </button>
        </div>
        <?php endif; ?>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    const rows = document.querySelectorAll('.row-student');
    const KKM = 75;

    // --- 1. Fungsi Kalkulasi Per Baris ---
    function calculateRow(row) {
        const iTugas = row.querySelector('.inp-tugas');
        const iUts   = row.querySelector('.inp-uts');
        const iUas   = row.querySelector('.inp-uas');
        const elFinal = row.querySelector('.score-final');

        // Ambil value, default 0 jika kosong/invalid
        const valTugas = parseFloat(iTugas.value) || 0;
        const valUts   = parseFloat(iUts.value) || 0;
        const valUas   = parseFloat(iUas.value) || 0;

        // Rumus Bobot
        const finalScore = (valTugas * 0.30) + (valUts * 0.30) + (valUas * 0.40);
        
        // Update Tampilan
        elFinal.textContent = finalScore.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });

        // Update Warna (Visual Feedback)
        if(finalScore < KKM) {
            elFinal.classList.add('remedial');
            elFinal.classList.remove('lulus');
        } else {
            elFinal.classList.add('lulus');
            elFinal.classList.remove('remedial');
        }

        return finalScore;
    }

    // --- 2. Fungsi Statistik Global ---
    function updateStats() {
        let total = 0;
        let count = 0;
        let max = -1;
        let min = 101;
        let passCount = 0;

        rows.forEach(row => {
            const score = calculateRow(row);
            total += score;
            count++;

            if (score > max) max = score;
            if (score < min) min = score;
            if (score >= KKM) passCount++;
        });

        if(count > 0) {
            document.getElementById('stat-avg').textContent = (total / count).toLocaleString('id-ID', { maximumFractionDigits: 1 });
            document.getElementById('stat-max').textContent = max.toLocaleString('id-ID', { maximumFractionDigits: 0 });
            document.getElementById('stat-min').textContent = min.toLocaleString('id-ID', { maximumFractionDigits: 0 });
            document.getElementById('stat-pass').textContent = passCount + "/" + count;
        }
    }

    // --- 3. Event Listeners ---
    const inputs = document.querySelectorAll('.inp-grade');
    
    inputs.forEach(input => {
        // Recalculate on input
        input.addEventListener('input', () => {
            // Validasi Max 100
            if(parseFloat(input.value) > 100) input.value = 100;
            if(parseFloat(input.value) < 0) input.value = 0;
            
            updateStats();
        });

        // UX: Auto Select content on focus (biar cepat editnya)
        input.addEventListener('focus', function() {
            this.select();
        });
    });

    // Run once on load
    updateStats();
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";
?>
