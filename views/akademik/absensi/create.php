<?php ob_start(); ?>

<style type="text/css">
    :root {
        --color-bg-card: #ffffff;
        --color-border: #e2e8f0;
        --color-text-main: #334155;
        --color-text-muted: #94a3b8;
        
        /* Status Colors (Konsisten dengan Global) */
        --status-hadir: #10b981; 
        --status-sakit: #f59e0b; 
        --status-izin: #3b82f6;  
        --status-alpa: #ef4444;  
        
        --radius-lg: 12px;
        --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    /* Layout Utama */
    .att-card { 
        background: var(--color-bg-card); 
        width: 100%; 
        border-radius: var(--radius-lg); 
        box-shadow: var(--shadow-soft); 
        padding: 24px; 
        display: flex; flex-direction: column; gap: 24px; 
        border: 1px solid rgba(0,0,0,0.02);
    }

    /* 1. Header Statistik */
    .att-header { display: flex; gap: 12px; padding-bottom: 5px; overflow-x: auto; }
    .att-stat { 
        flex: 1; 
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 15px 10px; 
        background: #f8fafc;
        border: 1px solid var(--color-border); 
        border-radius: 10px; 
        min-width: 80px; 
        text-align: center;
        transition: 0.2s;
    }
    
    /* Warna Aktif Stat (Biar lebih pop) */
    .att-stat[data-status="hadir"] { border-bottom: 3px solid var(--status-hadir); }
    .att-stat[data-status="sakit"] { border-bottom: 3px solid var(--status-sakit); }
    .att-stat[data-status="izin"]  { border-bottom: 3px solid var(--status-izin); }
    .att-stat[data-status="alpa"]  { border-bottom: 3px solid var(--status-alpa); }

    .att-stat__icon svg { width: 24px; height: 24px; margin-bottom: 5px; }
    .att-stat__count { display: block; font-weight: 800; font-size: 1.4rem; line-height: 1.2; color: var(--color-text-main); }
    .att-stat__label { font-size: 0.75rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; }

    /* Icon Colors */
    .att-stat[data-status="hadir"] .att-stat__icon { color: var(--status-hadir); }
    .att-stat[data-status="sakit"] .att-stat__icon { color: var(--status-sakit); }
    .att-stat[data-status="izin"]  .att-stat__icon { color: var(--status-izin); }
    .att-stat[data-status="alpa"]  .att-stat__icon { color: var(--status-alpa); }

    /* 2. Input Catatan */
    .att-notes__label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--color-text-main); font-size: 0.95rem; }
    .att-notes__input { 
        width: 100%; padding: 12px 15px; 
        border: 1px solid var(--color-border); 
        border-radius: 8px; outline: none; 
        background-color: #ffffff; color: var(--color-text-main);
        font-family: inherit; font-size: 0.95rem;
        transition: 0.2s;
    }
    .att-notes__input:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }

    /* 3. Tabel List Siswa */
    .att-list-container { border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden; background: white; }
    
    .att-row { 
        display: flex; align-items: center; padding: 12px 16px; gap: 15px; 
    }
    .att-list-header { 
        background-color: #f8fafc; 
        border-bottom: 1px solid var(--color-border); 
        font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #64748b; 
    }
    .att-item { border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .att-item:last-child { border-bottom: none; }
    .att-item:hover { background-color: #f8fafc; }

    /* Kolom */
    .col-no { width: 35px; text-align: center; color: #94a3b8; font-size: 0.85rem; }
    .col-nis { width: 100px; color: #64748b; font-family: monospace; font-size: 0.9rem; }
    .col-name { flex: 1; font-weight: 600; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .col-action { width: 50px; display: flex; justify-content: flex-end; position: relative; }

    /* Action Trigger */
    .att-trigger { 
        background: white; border: 1px solid var(--color-border); 
        cursor: pointer; width: 36px; height: 36px; border-radius: 50%; 
        color: var(--color-text-muted); display: flex; align-items: center; justify-content: center; 
        transition: 0.2s; 
    }
    .att-trigger:hover { transform: scale(1.05); }
    .att-trigger svg { width: 20px; height: 20px; }
    
    /* State Trigger (Selected) */
    .att-trigger.is-hadir { color: var(--status-hadir); background: #ecfdf5; border-color: #6ee7b7; }
    .att-trigger.is-sakit { color: var(--status-sakit); background: #fffbeb; border-color: #fcd34d; }
    .att-trigger.is-izin  { color: var(--status-izin);  background: #eff6ff; border-color: #93c5fd; }
    .att-trigger.is-alpa  { color: var(--status-alpa);  background: #fef2f2; border-color: #fca5a5; }

    /* Dropdown Menu */
    .att-menu { 
        position: absolute; right: 0; top: -8px; 
        background: white; padding: 5px; 
        border-radius: 40px; 
        display: flex; gap: 6px; 
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); 
        border: 1px solid #e2e8f0; z-index: 50; 
        animation: fadeIn 0.2s ease;
    }
    .att-menu.hidden { display: none; }
    
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }

    .att-menu__opt { 
        border: none; cursor: pointer; width: 34px; height: 34px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        transition: transform 0.1s; 
    }
    .att-menu__opt:hover { transform: scale(1.15); }
    .att-menu__opt svg { width: 18px; height: 18px; }
    
    .att-menu__opt[data-value="Hadir"] { background: #d1fae5; color: var(--status-hadir); }
    .att-menu__opt[data-value="Sakit"] { background: #fef3c7; color: var(--status-sakit); }
    .att-menu__opt[data-value="Izin"]  { background: #dbeafe; color: var(--status-izin); }
    .att-menu__opt[data-value="Alpa"]  { background: #fee2e2; color: var(--status-alpa); }

    /* Footer Button */
    .att-recap { display: flex; justify-content: space-between; font-size: 0.9rem; color: #64748b; margin-bottom: 10px; padding: 0 5px; }
    .att-btn-primary { 
        width: 100%; padding: 14px; 
        background-color: #0d6efd; color: white; 
        border: none; border-radius: 8px; 
        font-weight: 700; cursor: pointer; font-size: 1rem;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
        transition: 0.2s;
    }
    .att-btn-primary:hover { background-color: #0b5ed7; transform: translateY(-1px); }
    .att-btn-primary:disabled { background-color: #cbd5e1; cursor: not-allowed; box-shadow: none; transform: none; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold" style="color: #1e293b;">Input Presensi Siswa</h4>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">
            <?php echo htmlspecialchars($jadwal["nama_kelas"]); ?> &bull; <?php echo htmlspecialchars($jadwal["nama_mapel"]); ?>
        </p>
    </div>
    <a href="<?php echo BASE_URL; ?>/absensi" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<?php if (isset($absensi) && $absensi["status_validasi"] === "Rejected"): ?>
<div class="alert alert-danger mb-4" style="border-radius: 10px; border-left: 5px solid #dc2626;">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-octagon-fill me-3 fs-3"></i>
        <div>
            <h6 class="fw-bold mb-1">Presensi Ditolak</h6>
            <span class="fs-6">Alasan: <?php echo htmlspecialchars($absensi["alasan_penolakan"]); ?></span>
        </div>
    </div>
</div>
<?php endif; ?>

<svg style="display: none;">
    <symbol id="icon-ellipsis" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></symbol>
    <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></symbol>
    <symbol id="icon-sick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></symbol>
    <symbol id="icon-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></symbol>
    <symbol id="icon-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></symbol>
</svg>

<form action="<?php echo BASE_URL; ?>/absensi/store" method="POST">
    <input type="hidden" name="jadwal_id" value="<?php echo $jadwal["jadwal_id"]; ?>">
    <input type="hidden" name="tanggal" value="<?php echo $tanggal; ?>">

    <div class="att-card">
        
        <header class="att-header">
            <div class="att-stat" data-status="hadir">
                <div class="att-stat__icon"><svg><use href="#icon-check"></use></svg></div>
                <div class="att-stat__info"><span class="att-stat__count" id="count-Hadir">0</span><span class="att-stat__label">Hadir</span></div>
            </div>
            <div class="att-stat" data-status="sakit">
                <div class="att-stat__icon"><svg><use href="#icon-sick"></use></svg></div>
                <div class="att-stat__info"><span class="att-stat__count" id="count-Sakit">0</span><span class="att-stat__label">Sakit</span></div>
            </div>
            <div class="att-stat" data-status="izin">
                <div class="att-stat__icon"><svg><use href="#icon-info"></use></svg></div>
                <div class="att-stat__info"><span class="att-stat__count" id="count-Izin">0</span><span class="att-stat__label">Izin</span></div>
            </div>
            <div class="att-stat" data-status="alpa">
                <div class="att-stat__icon"><svg><use href="#icon-x"></use></svg></div>
                <div class="att-stat__info"><span class="att-stat__count" id="count-Alpa">0</span><span class="att-stat__label">Alpa</span></div>
            </div>
        </header>

        <section class="att-notes">
            <label for="catatan" class="att-notes__label">Catatan Jurnal / Materi Pembelajaran</label>
            <textarea name="catatan_harian" id="catatan" class="att-notes__input" 
                placeholder="Tuliskan materi yang diajarkan, kendala, atau catatan penting hari ini..." 
                rows="3" required><?php echo htmlspecialchars($jadwal["catatan_harian_value"] ?? ""); ?></textarea>
        </section>

        <section class="att-list-container">
            <div class="att-row att-list-header">
                <span class="col-no">No</span>
                <span class="col-nis">NIS</span>
                <span class="col-name">Nama Siswa</span>
                <span class="col-action text-end">Status</span>
            </div>
            
            <ul class="att-list" style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($siswa as $idx => $s):
                    // Logic: Jika sudah ada data tersimpan, pakai itu. Jika belum, DEFAULT "Hadir".
                    $defaultStatus = "Hadir";
                    $savedStatus = $savedDetails[$s["siswa_id"]] ?? $defaultStatus; 
                ?>
                <li class="att-item att-row" data-student-id="<?php echo $s["siswa_id"]; ?>">
                    <span class="col-no"><?php echo $idx + 1; ?></span>
                    <span class="col-nis"><?php echo htmlspecialchars($s["nis"]); ?></span>
                    <span class="col-name"><?php echo htmlspecialchars($s["nama_lengkap"]); ?></span>
                    
                    <div class="col-action att-action">
                        <input type="hidden" name="status_kehadiran[<?php echo $s["siswa_id"]; ?>]" value="<?php echo $savedStatus; ?>">
            
                        <button type="button" class="att-trigger" onclick="toggleMenu(this)">
                            </button>
                        
                        <div class="att-menu hidden">
                            <button type="button" class="att-menu__opt" data-value="Hadir" onclick="setStatus(this, 'Hadir')" title="Hadir"><svg><use href="#icon-check"></use></svg></button>
                            <button type="button" class="att-menu__opt" data-value="Sakit" onclick="setStatus(this, 'Sakit')" title="Sakit"><svg><use href="#icon-sick"></use></svg></button>
                            <button type="button" class="att-menu__opt" data-value="Izin" onclick="setStatus(this, 'Izin')" title="Izin"><svg><use href="#icon-info"></use></svg></button>
                            <button type="button" class="att-menu__opt" data-value="Alpa" onclick="setStatus(this, 'Alpa')" title="Alpa"><svg><use href="#icon-x"></use></svg></button>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <div class="att-recap">
            <span>Total: <b><?php echo count($siswa); ?> Siswa</b></span>
            </div>

        <div class="att-footer">
            <button type="submit" class="att-btn-primary" id="btn-submit">
                <i class="bi bi-check-circle-fill me-2"></i> Simpan Data Presensi
            </button>
        </div>

    </div>
</form>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        // --- DATA STATE ---
        let counts = { Hadir: 0, Sakit: 0, Izin: 0, Alpa: 0 };
        
        // --- INITIALIZATION ---
        function init() {
            // Reset counts
            counts = { Hadir: 0, Sakit: 0, Izin: 0, Alpa: 0 };

            document.querySelectorAll('.att-item').forEach(item => {
                const input = item.querySelector('input[type="hidden"]');
                const trigger = item.querySelector('.att-trigger');
                
                // Ambil value. Jika kosong, default 'Hadir' (untuk create baru)
                let status = input.value;
                if(!status) status = 'Hadir'; 
                input.value = status; // Pastikan input value terisi
                
                // Update UI Per Baris
                updateRowUI(trigger, status);

                // Update Count
                if (counts.hasOwnProperty(status)) counts[status]++;
                else if (status === 'Alpa') counts.Alpa++;
            });

            updateHeaderStats();
        }

        // --- UI UPDATERS ---
        function updateRowUI(triggerBtn, status) {
            const cssStatus = status.toLowerCase(); // hadir, sakit, izin, alpa
            
            // Pilih icon ID
            let iconId = '#icon-check';
            if(cssStatus === 'sakit') iconId = '#icon-sick';
            else if(cssStatus === 'izin') iconId = '#icon-info';
            else if(cssStatus === 'alpa') iconId = '#icon-x';
            
            triggerBtn.innerHTML = `<svg><use href="${iconId}"></use></svg>`;
            
            // Update Class Warna
            triggerBtn.classList.remove('is-hadir', 'is-sakit', 'is-izin', 'is-alpa');
            triggerBtn.classList.add(`is-${cssStatus}`);
        }

        function updateHeaderStats() {
            document.getElementById('count-Hadir').textContent = counts.Hadir;
            document.getElementById('count-Sakit').textContent = counts.Sakit;
            document.getElementById('count-Izin').textContent = counts.Izin;
            document.getElementById('count-Alpa').textContent = counts.Alpa;
        }

        // --- EVENT HANDLERS (Global Functions) ---
        
        // 1. Toggle Menu
        window.toggleMenu = (triggerBtn) => {
            // Tutup semua menu lain dulu
            document.querySelectorAll('.att-menu').forEach(menu => {
                if(menu !== triggerBtn.nextElementSibling) menu.classList.add('hidden');
            });
            
            const menu = triggerBtn.nextElementSibling;
            menu.classList.toggle('hidden');
        };

        // 2. Set Status (Klik Opsi)
        window.setStatus = (optionBtn, newStatus) => {
            const menu = optionBtn.parentElement;
            const actionWrapper = menu.closest('.att-action');
            const triggerBtn = menu.previousElementSibling;
            const inputHidden = actionWrapper.querySelector('input[type="hidden"]');
            
            const oldStatus = inputHidden.value;

            // Update Logic Count
            if(oldStatus !== newStatus) {
                // Kurangi lama
                if(counts.hasOwnProperty(oldStatus)) counts[oldStatus]--;
                else if(oldStatus === 'Alpa') counts.Alpa--;

                // Tambah baru
                if(counts.hasOwnProperty(newStatus)) counts[newStatus]++;
                else if(newStatus === 'Alpa') counts.Alpa++;

                // Update Value & UI
                inputHidden.value = newStatus;
                updateRowUI(triggerBtn, newStatus);
                updateHeaderStats();
            }

            // Tutup menu
            menu.classList.add('hidden');
        };

        // 3. Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.att-action')) {
                document.querySelectorAll('.att-menu').forEach(menu => menu.classList.add('hidden'));
            }
        });

        // --- RUN INIT ---
        init();
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";
?>
