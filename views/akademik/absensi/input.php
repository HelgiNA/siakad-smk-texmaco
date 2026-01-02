<?php
ob_start(); ?>

<style type="text/css">
    :root {
        --color-bg-card: #ffffff;
        --color-bg-body: #f3f4f6;
        --color-text-main: #1f2937;
        --color-text-muted: #6b7280;
        --color-border: #e5e7eb;
        --color-primary: #2563eb;
        
        /* Status Colors */
        --status-hadir: #10b981; 
        --status-sakit: #f59e0b; 
        --status-izin: #3b82f6;  
        --status-alfa: #ef4444;  
        
        --radius-lg: 12px;
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Layout Utama */
    .att-card { 
        background: var(--color-bg-card); 
        width: 100%; 
        border-radius: var(--radius-lg); 
        box-shadow: var(--shadow-md); 
        padding: 24px; 
        display: flex; 
        flex-direction: column; 
        gap: 24px; /* Jarak antar section diperlebar */
    }

    /* 1. Header Statistik (Kotak-kotak atas) */
    .att-header { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 4px; }
    .att-stat { 
        flex: 1; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        padding: 12px 10px; /* Padding ditambah */
        border: 1px solid var(--color-border); 
        border-radius: 8px; 
        min-width: 70px; 
        text-align: center;
    }
    /* Warna Ikon Stat */
    .att-stat[data-status="hadir"] .att-stat__icon { color: var(--status-hadir); }
    .att-stat[data-status="sakit"] .att-stat__icon { color: var(--status-sakit); }
    .att-stat[data-status="izin"] .att-stat__icon { color: var(--status-izin); }
    .att-stat[data-status="alfa"] .att-stat__icon { color: var(--status-alfa); }
    
    .att-stat__icon svg { width: 24px; height: 24px; margin-bottom: 8px; }
    .att-stat__count { display: block; font-weight: 700; font-size: 1.25rem; margin-bottom: 2px; } /* Jarak angka ke text */
    .att-stat__label { font-size: 0.7rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

    /* 2. Input Catatan (Fix Background Hitam) */
    .att-notes__label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--color-text-main); }
    .att-notes__input { 
        width: 100%; 
        padding: 12px; 
        border: 1px solid var(--color-border); 
        border-radius: 8px; 
        outline: none; 
        background-color: #ffffff !important; /* Paksa Putih */
        color: #1f2937 !important; /* Paksa Teks Gelap */
    }
    .att-notes__input:focus { border-color: var(--color-primary); ring: 2px solid rgba(37, 99, 235, 0.1); }

    /* 3. Tabel List Siswa (Grid System) */
    .att-list-container { border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden; }
    
    /* Baris Header & Item menggunakan Flexbox yang sama */
    .att-row { 
        display: flex; 
        align-items: center; 
        padding: 12px 16px; 
        gap: 10px; /* Jarak antar kolom */
    }
    .att-list-header { 
        background-color: #f9fafb; 
        border-bottom: 1px solid var(--color-border); 
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: var(--color-text-muted); 
    }
    .att-item { border-bottom: 1px solid var(--color-border); transition: background 0.1s; }
    .att-item:last-child { border-bottom: none; }
    .att-item:hover { background-color: #f8fafc; }

    /* Pengaturan Lebar Kolom (PENTING AGAR RAPI) */
    .col-no { width: 30px; text-align: center; flex-shrink: 0; }
    .col-nis { flex: 20%; flex-shrink: 0; }
    .col-name { flex: 80%; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; }
    .col-action { width: 50px; display: flex; justify-content: flex-end; flex-shrink: 0; }

    /* Komponen Tombol & Menu */
    .att-action { position: relative; }
    .att-trigger { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 50%; color: var(--color-text-muted); display: flex; align-items: center; justify-content: center; }
    .att-trigger:hover { background-color: #e5e7eb; }
    .att-trigger svg { width: 22px; height: 22px; }
    
    /* Warna Trigger saat terpilih */
    .att-trigger.is-hadir { color: var(--status-hadir); background: rgba(16, 185, 129, 0.1); }
    .att-trigger.is-sakit { color: var(--status-sakit); background: rgba(245, 158, 11, 0.1); }
    .att-trigger.is-izin  { color: var(--status-izin);  background: rgba(59, 130, 246, 0.1); }
    .att-trigger.is-alfa  { color: var(--status-alfa);  background: rgba(239, 68, 68, 0.1); }

    /* Menu Dropdown */
    .att-menu { position: absolute; right: 0; top: -5px; background: white; border: 1px solid var(--color-border); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-radius: 50px; display: flex; padding: 4px; gap: 4px; z-index: 50; }
    .att-menu.hidden { display: none; }
    .att-menu__opt { border: none; cursor: pointer; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: transform 0.1s; }
    .att-menu__opt:hover { transform: scale(1.15); }
    .att-menu__opt svg { width: 16px; height: 16px; }
    
    /* Warna Opsi Menu */
    .att-menu__opt[data-value="Hadir"] { background: #d1fae5; color: var(--status-hadir); }
    .att-menu__opt[data-value="Sakit"] { background: #fef3c7; color: var(--status-sakit); }
    .att-menu__opt[data-value="Izin"]  { background: #dbeafe; color: var(--status-izin); }
    .att-menu__opt[data-value="Alfa"]  { background: #fee2e2; color: var(--status-alfa); }

    /* Footer */
    .att-recap { display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--color-text-muted); padding: 0 4px; }
    .att-btn-primary { width: 100%; padding: 14px; background-color: var(--color-primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .att-btn-primary:disabled { background-color: #9ca3af; cursor: not-allowed; }
</style>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 text-primary fw-bold">Input Absensi</h4>
        <p class="text-muted mb-0">
            <?php echo htmlspecialchars(
                $jadwal["nama_kelas"]
            ); ?> | <?php echo htmlspecialchars($jadwal["nama_mapel"]); ?>
        </p>
    </div>
    <a href="<?php echo BASE_URL; ?>/absensi" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<?php if (isset($absensi) && $absensi["status_validasi"] === "Rejected"): ?>
<div class="alert alert-danger mb-4">
    <strong><i class="bi bi-exclamation-octagon-fill me-2"></i> PERHATIAN!</strong> Data ini DITOLAK oleh Wali Kelas.<br>
    Alasan: <?php echo htmlspecialchars($absensi["alasan_penolakan"]); ?>
</div>
<?php endif; ?>

<svg style="display: none;">
    <symbol id="icon-ellipsis" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></symbol>
    <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></symbol>
    <symbol id="icon-sick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></symbol>
    <symbol id="icon-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></symbol>
    <symbol id="icon-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></symbol>
</svg>

<form action="<?php echo BASE_URL; ?>/absensi/submit" method="POST">
    <input type="hidden" name="jadwal_id" value="<?php echo $jadwal[
        "jadwal_id"
    ]; ?>">
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
            <div class="att-stat" data-status="alfa">
                <div class="att-stat__icon"><svg><use href="#icon-x"></use></svg></div>
                <div class="att-stat__info"><span class="att-stat__count" id="count-Alfa">0</span><span class="att-stat__label">Alfa</span></div>
            </div>
        </header>

        <section class="att-notes">
            <label for="catatan" class="att-notes__label">Catatan Harian (Jurnal)</label>
            <textarea name="catatan_harian" id="catatan" class="att-notes__input" 
                placeholder="Materi yang diajarkan, kejadian khusus, dll..." 
                rows="2" required><?php echo htmlspecialchars(
                    $jadwal["catatan_harian_value"] ?? ""
                ); ?></textarea>
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
                    $savedStatus = $savedDetails[$s["siswa_id"]] ?? "Hadir"; ?>
                <li class="att-item att-row" data-student-id="<?php echo $idx +
                    1; ?>">
                    <span class="col-no text-muted"><?php echo $idx +
                        1; ?></span>
                    
                    <span class="col-nis text-muted"><?php echo htmlspecialchars(
                        $s["nis"]
                    ); ?></span>
                    
                    <span class="col-name text-dark fw-bold"><?php echo htmlspecialchars(
                        $s["nama_lengkap"]
                    ); ?></span>
                    
                    <div class="col-action att-action">
                        <input type="hidden" 
                               name="status_keHadiran[<?php echo $s[
                                   "siswa_id"
                               ]; ?>]" 
                               value="<?php echo $savedStatus; ?>">
            
                        <button type="button" class="att-trigger" onclick="toggleMenu(this)">
                            <svg class="icon-default"><use href="#icon-ellipsis"></use></svg>
                        </button>
                        
                        <div class="att-menu hidden">
                            <button type="button" class="att-menu__opt" data-value="Hadir" onclick="setStatus(this, 'Hadir')"><svg><use href="#icon-check"></use></svg></button>
                            <button type="button" class="att-menu__opt" data-value="Sakit" onclick="setStatus(this, 'Sakit')"><svg><use href="#icon-sick"></use></svg></button>
                            <button type="button" class="att-menu__opt" data-value="Izin" onclick="setStatus(this, 'Izin')"><svg><use href="#icon-info"></use></svg></button>
                            <button type="button" class="att-menu__opt" data-value="Alfa" onclick="setStatus(this, 'Alfa')"><svg><use href="#icon-x"></use></svg></button>
                        </div>
                    </div>
                </li>
                <?php
                endforeach; ?>
            </ul>
        </section>


        <div class="att-recap">
            <span class="att-recap__text">Total Siswa: <b><?php echo count(
                $siswa
            ); ?></b></span>
            <span class="att-recap__text">Belum diabsen: <b id="count-unmarked"><?php echo count(
                $siswa
            ); ?></b></span>
        </div>

        <div class="att-footer">
            <button type="submit" class="att-btn-primary" id="btn-submit" disabled>
                <i class="bi bi-save me-1"></i> Simpan Absensi
            </button>
        </div>

    </div>
</form>

<script type="text/javascript" charset="utf-8">
    document.addEventListener('DOMContentLoaded', () => {
        // State
        let counts = { Hadir: 0, Sakit: 0, Izin: 0, Alfa: 0 };
        const totalStudents = document.querySelectorAll('.att-item').length;
        let markedCount = 0;
    
        const btnSubmit = document.getElementById('btn-submit');
        const elUnmarked = document.getElementById('count-unmarked');
    
        // === INISIALISASI: Membaca Value dari PHP ===
        document.querySelectorAll('.att-item').forEach(item => {
            const input = item.querySelector('input[type="hidden"]');
            const trigger = item.querySelector('.att-trigger');
            
            // Ambil value dari HTML (generated by PHP), default Hadir jika kosong
            let status = input && input.value.trim() !== "" ? input.value : 'Hadir';
            
            // Pastikan input value konsisten
            if(input) input.value = status;
            
            // Tentukan Icon & Class (Convert status ke lowercase untuk Class CSS: .is-hadir, .is-sakit)
            const cssStatus = status.toLowerCase(); // hadir, sakit, izin, alfa
            const iconId = `#icon-${cssStatus === 'hadir' ? 'check' : cssStatus === 'alfa' ? 'x' : cssStatus === 'sakit' ? 'sick' : 'info'}`;
            
            trigger.innerHTML = `<svg><use href="${iconId}"></use></svg>`;
            trigger.classList.remove('is-hadir', 'is-sakit', 'is-izin', 'is-alfa');
            trigger.classList.add(`is-${cssStatus}`);
    
            item.dataset.status = status;
            
            // Safety check key exists
            if (counts.hasOwnProperty(status)) {
                counts[status]++;
            } else if (status === 'Alpa' || status === 'Alfa') {
                counts.Alfa++; // Handle variasi penulisan Alpa/Alfa
            }
        });
    
        markedCount = totalStudents;
        updateSummaryUI();
        
        // Klik di luar menutup menu
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.att-action')) {
                document.querySelectorAll('.att-menu').forEach(menu => menu.classList.add('hidden'));
            }
        });
    
        // Fungsi Global
        window.toggleMenu = (triggerBtn) => {
            document.querySelectorAll('.att-menu').forEach(menu => menu.classList.add('hidden'));
            const menu = triggerBtn.nextElementSibling;
            menu.classList.toggle('hidden');
        };
    
        window.setStatus = (optionBtn, status) => {
            const menu = optionBtn.parentElement;
            const actionWrapper = menu.closest('.att-action');
            const triggerBtn = menu.previousElementSibling;
            const listItem = menu.closest('.att-item');
            
            // 1. Update Input Hidden Value (Tetap Title Case sesuai backend: Hadir, Sakit...)
            const inputHidden = actionWrapper.querySelector('input[type="hidden"]');
            if (inputHidden) inputHidden.value = status;
    
            // 2. Update Visual (Gunakan Lowercase untuk CSS)
            const cssStatus = status.toLowerCase();
            const iconId = `#icon-${cssStatus === 'hadir' ? 'check' : cssStatus === 'alfa' ? 'x' : cssStatus === 'sakit' ? 'sick' : 'info'}`;
            
            triggerBtn.innerHTML = `<svg><use href="${iconId}"></use></svg>`;
            triggerBtn.classList.remove('is-hadir', 'is-sakit', 'is-izin', 'is-alfa');
            triggerBtn.classList.add(`is-${cssStatus}`);
    
            // 3. Update Hitungan
            const prevStatus = listItem.dataset.status;
            
            // Kurangi count lama
            if (prevStatus) {
                if(counts.hasOwnProperty(prevStatus)) counts[prevStatus]--;
                else if(prevStatus === 'Alpa') counts.Alfa--; // Handle Alpa vs Alfa
            } else {
                markedCount++;
            }
            
            // Tambah count baru
            if(counts.hasOwnProperty(status)) counts[status]++;
            else if(status === 'Alpa') counts.Alfa++;
            
            listItem.dataset.status = status;
    
            updateSummaryUI();
            menu.classList.add('hidden');
        };
    
        function updateSummaryUI() {
            document.getElementById('count-Hadir').textContent = counts.Hadir;
            document.getElementById('count-Sakit').textContent = counts.Sakit;
            document.getElementById('count-Izin').textContent = counts.Izin;
            document.getElementById('count-Alfa').textContent = counts.Alfa; // Pastikan ID di HTML juga Alfa
            elUnmarked.textContent = totalStudents - markedCount;
    
            if (markedCount > 0) btnSubmit.disabled = false;
        }
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
