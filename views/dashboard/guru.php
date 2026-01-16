<?php
// --- DATA DUMMY / HELPER (Agar tidak error saat preview) ---
$hariList = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu",
];
$hariIni = $hariList[date("l")];
$tanggal = date("d F Y");

// Pastikan variabel ada (fallback)
$username = $username ?? "Bapak/Ibu Guru";
$is_wali_kelas = $is_wali_kelas ?? false;
$validasi_pending = $validasi_pending ?? 0;
$jadwal_hari_ini = $jadwal_hari_ini ?? [];
?>

<style>
    /* --- 1. RESET & VARIABLES --- */
    :root {
        --c-primary: #0f172a;
        --c-accent: #2563eb;
        --c-bg-card: #ffffff;
        --radius: 12px;
        --gap: 24px;
    }
    * { box-sizing: border-box; }

    /* --- 2. GRID SYSTEM MANUAL (Solusi Col-LG-8 Berdempetan) --- */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -12px;
        margin-left: -12px;
    }
    
    .col-lg-8, .col-lg-4, .col-12 {
        position: relative;
        width: 100%;
        padding-right: 12px;
        padding-left: 12px;
    }

    .mb-4 { margin-bottom: 24px !important; }

    /* Media Query untuk Layar Besar (Laptop/PC) */
    @media (min-width: 992px) {
        .col-lg-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .col-lg-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    /* --- 3. KOMPONEN DASHBOARD --- */
    
    /* Welcome Banner */
    .welcome-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white; border-radius: var(--radius);
        padding: 30px 25px; margin-bottom: 24px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
    }
    .welcome-content { position: relative; z-index: 2; }
    .welcome-date { font-size: 0.85rem; opacity: 0.8; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .welcome-title { font-size: 1.8rem; font-weight: 800; margin: 0; line-height: 1.2; }
    .welcome-subtitle { font-size: 1rem; opacity: 0.8; margin-top: 5px; font-weight: 400; }
    .welcome-card::after {
        content: ''; position: absolute; top: -50%; right: -5%;
        width: 250px; height: 250px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%; z-index: 1; pointer-events: none;
    }

    /* Card General */
    .dash-card {
        background: var(--c-bg-card); border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05); overflow: hidden; height: 100%;
        display: flex; flex-direction: column;
    }
    .dash-header {
        padding: 20px 24px; border-bottom: 1px solid #f1f5f9;
        display: flex; justify-content: space-between; align-items: center;
        background: #fff;
    }
    .dash-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px; }

    /* Badge Helper */
    .badge {
        display: inline-block; padding: 0.35em 0.65em; font-size: 0.75em; font-weight: 700;
        line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem;
    }
    .bg-light { background-color: #f8f9fa !important; color: #212529 !important; border: 1px solid #e9ecef; }

    /* Schedule Table */
    .table-responsive-custom { width: 100%; overflow-x: auto; }
    .schedule-table { width: 100%; border-collapse: collapse; min-width: 500px; }
    .schedule-table th { 
        text-align: left; padding: 15px 24px; font-size: 0.75rem; text-transform: uppercase; 
        color: #64748b; background: #f8fafc; font-weight: 700; border-bottom: 1px solid #e2e8f0;
    }
    .schedule-table td { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .schedule-table tr:last-child td { border-bottom: none; }
    
    .time-box {
        font-family: 'Consolas', monospace; font-size: 0.9rem; color: #334155;
        background: #f1f5f9; padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0;
        display: inline-block; font-weight: 600;
    }
    
    /* Buttons */
    .btn-action {
        padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        transition: 0.2s; border: 1px solid transparent; cursor: pointer;
    }
    .btn-primary-soft { background: #eff6ff; color: #2563eb; border-color: #dbeafe; }
    .btn-primary-soft:hover { background: #2563eb; color: white; }
    
    .btn-warning { background: #f59e0b; color: white; width: 100%; justify-content: center; }
    .btn-outline-success { background: white; border: 1px solid #10b981; color: #10b981; width: 100%; justify-content: center; }

    /* Wali Widget */
    .wali-widget-container { 
        padding: 24px; flex: 1; display: flex; flex-direction: column; 
        justify-content: center; align-items: center; text-align: center; 
    }
    .wali-icon {
        width: 64px; height: 64px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; margin-bottom: 15px;
    }
    .state-pending .wali-icon { background: #fff7ed; color: #d97706; }
    .state-success .wali-icon { background: #f0fdf4; color: #166534; }
    
    .wali-stat { font-size: 2.5rem; font-weight: 800; line-height: 1; margin: 5px 0 10px; color: #1e293b; }
    .wali-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 5px; color: #334155; }
    .wali-desc { color: #64748b; font-size: 0.9rem; margin-bottom: 20px; line-height: 1.5; }

    /* Info Box */
    .info-box-content { padding: 20px; font-size: 0.9rem; color: #475569; line-height: 1.6; }
    .app-version { background: #f8fafc; padding: 10px 15px; border-radius: 8px; margin-top: 15px; font-size: 0.85rem; border: 1px solid #e2e8f0; }

    /* Utilities */
    .text-primary { color: #2563eb !important; }
</style>

<div class="welcome-card">
    <div class="welcome-content">
        <div class="welcome-date">
            <i class="bi bi-calendar2-week"></i> <?php echo $hariIni .
                ", " .
                $tanggal; ?>
        </div>
        <h1 class="welcome-title">Halo, <?php echo htmlspecialchars(
            $username
        ); ?>!</h1>
        <p class="welcome-subtitle">Selamat datang kembali. Siap mencerdaskan kehidupan bangsa hari ini?</p>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8 mb-4">
        <div class="dash-card">
            <div class="dash-header">
                <h3 class="dash-title">
                    <i class="bi bi-clock-history text-primary"></i> Jadwal Mengajar
                </h3>
                <span class="badge bg-light">
                    <?php echo isset($jadwal_hari_ini)
                        ? count($jadwal_hari_ini)
                        : 0; ?> Kelas Hari Ini
                </span>
            </div>
            
            <div class="table-responsive-custom">
                <?php if (
                    isset($jadwal_hari_ini) &&
                    count($jadwal_hari_ini) > 0
                ): ?>
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th width="160">Waktu</th>
                                <th>Kelas & Mata Pelajaran</th>
                                <th width="140" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwal_hari_ini as $jadwal): ?>
                            <tr>
                                <td>
                                    <div class="time-box">
                                        <?php echo date(
                                            "H:i",
                                            strtotime($jadwal["jam_mulai"])
                                        ); ?> - 
                                        <?php echo date(
                                            "H:i",
                                            strtotime($jadwal["jam_selesai"])
                                        ); ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; font-size: 1.1rem; color: #1e293b; margin-bottom: 4px;">
                                        <?php echo htmlspecialchars(
                                            $jadwal["nama_kelas"]
                                        ); ?>
                                    </div>
                                    <div style="color: #64748b; font-size: 0.95rem; font-weight: 500;">
                                        <?php echo htmlspecialchars(
                                            $jadwal["nama_mapel"]
                                        ); ?> 
                                        <span style="font-size: 0.75rem; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; border:1px solid #e2e8f0; margin-left: 6px; font-weight: 600;">
                                            <?php echo htmlspecialchars(
                                                $jadwal["kode_mapel"]
                                            ); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo BASE_URL; ?>/absensi/create?jadwal_id=<?php echo $jadwal[
    "jadwal_id"
]; ?>" 
                                       class="btn-action btn-primary-soft">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: 60px 20px; text-align: center; color: #94a3b8;">
                        <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                            <i class="bi bi-cup-hot-fill" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                        <h5 style="color: #334155; font-weight: 700; margin-bottom: 5px;">Jadwal Kosong</h5>
                        <p style="margin:0;">Tidak ada kelas mengajar untuk hari ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        
        <?php if (isset($is_wali_kelas) && $is_wali_kelas):

            $countPending = $validasi_pending ?? 0;
            $hasPending = $countPending > 0;
            $borderColor = $hasPending ? "#f97316" : "#10b981";
            ?>
        <div class="dash-card mb-4" style="border-top: 4px solid <?php echo $borderColor; ?>;">
            <div class="dash-header">
                <h3 class="dash-title" style="font-size: 1rem;">
                    <i class="bi bi-person-workspace"></i> Area Wali Kelas
                </h3>
                <span class="badge bg-light"><?php echo htmlspecialchars(
                    $kelas_wali["nama_kelas"]
                ); ?></span>
            </div>
            
            <div class="wali-widget-container <?php echo $hasPending
                ? "state-pending"
                : "state-success"; ?>">
                <?php if ($hasPending): ?>
                    <div class="wali-icon">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <div class="wali-stat" style="color: #c2410c;"><?php echo $countPending; ?></div>
                    <div class="wali-title" style="color: #9a3412;">Menunggu Validasi</div>
                    <div class="wali-desc">Ada laporan absensi yang belum Anda periksa.</div>
                    <a href="<?php echo BASE_URL; ?>/absensi/validasi" class="btn-action btn-warning">
                        Periksa Sekarang <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                <?php else: ?>
                    <div class="wali-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="wali-title" style="color: #15803d; font-size: 1.2rem; margin-top: 10px;">Semua Aman!</div>
                    <div class="wali-desc">Seluruh laporan absensi sudah tervalidasi.</div>
                    <a href="<?php echo BASE_URL; ?>/absensi/validasi" class="btn-action btn-outline-success">
                        Lihat Riwayat
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        endif; ?>

        <div class="dash-card">
            <div class="dash-header">
                <h3 class="dash-title" style="font-size: 1rem;">
                    <i class="bi bi-info-circle"></i> Informasi
                </h3>
            </div>
            <div class="info-box-content">
                <p style="margin-bottom: 0;">
                    Pastikan Anda selalu melakukan <strong>Logout</strong> setelah selesai menggunakan sistem demi keamanan data.
                </p>
                <div class="app-version">
                    <div style="font-weight: 700; color: #334155;">SIAKAD v1.0.0</div>
                    <div style="color: #64748b;">Support: IT Team Texmaco</div>
                </div>
            </div>
        </div>

    </div>
</div>
