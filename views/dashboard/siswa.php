<?php
// Pastikan data tersedia untuk menghindari error
$siswa = $statistik_absensi ?? [];
$info  = $siswa_info ?? [];

// Ambil Data
$nama   = $siswa['nama_siswa'] ?? 'Siswa';
$nis    = $info['nis'] ?? '-';
$nisn   = $info['nisn'] ?? '-';
$kelas  = $siswa['nama_kelas'] ?? 'Belum Ada Kelas';
$wali   = $siswa['nama_wali_kelas'] ?? '-';

// Statistik
$hadir  = $siswa['hadir'] ?? 0;
$sakit  = $siswa['sakit'] ?? 0;
$izin   = $siswa['izin'] ?? 0;
$alpa   = $siswa['alpa'] ?? 0;
$total  = $siswa['total_pertemuan'] ?? 1; // Hindari division by zero
$persen = $siswa['persentase_hadir'] ?? 0;

// Helper Status Text
$statusText = 'Kurang';
$statusColor = '#ef4444'; // Red
if ($persen >= 90) { $statusText = 'Sangat Baik'; $statusColor = '#10b981'; } // Green
elseif ($persen >= 75) { $statusText = 'Baik'; $statusColor = '#2563eb'; } // Blue
elseif ($persen >= 60) { $statusText = 'Cukup'; $statusColor = '#f59e0b'; } // Orange
?>

<style>
    :root {
        --c-primary: #0f172a;
        --c-accent: #2563eb;
        --c-bg-card: #ffffff;
        --radius: 12px;
    }

    /* 1. Welcome Banner */
    .welcome-card {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white; border-radius: var(--radius);
        padding: 30px; margin-bottom: 24px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
    }
    .welcome-card::after {
        content: ''; position: absolute; top: -50%; right: -5%;
        width: 250px; height: 250px; background: rgba(255,255,255,0.1);
        border-radius: 50%; pointer-events: none;
    }
    .welcome-title { font-size: 1.5rem; font-weight: 800; margin: 0; }
    .welcome-desc { font-size: 0.95rem; opacity: 0.9; margin-top: 5px; font-weight: 300; }

    /* 2. Profile Card */
    .profile-card {
        background: var(--c-bg-card); border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
        text-align: center; height: 100%;
    }
    .profile-bg {
        height: 100px; background: #eff6ff; 
        border-bottom: 1px solid #dbeafe;
    }
    .profile-avatar {
        width: 90px; height: 90px; background: white; 
        border-radius: 50%; margin: -45px auto 15px;
        display: flex; align-items: center; justify-content: center;
        border: 4px solid white; color: var(--c-accent);
        font-size: 3rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .profile-name { font-size: 1.2rem; font-weight: 800; color: #1e293b; margin-bottom: 5px; padding: 0 15px; }
    .profile-meta { font-family: monospace; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; display: inline-block; margin-bottom: 20px; }
    
    .info-list { text-align: left; padding: 0 24px 24px; }
    .info-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #e2e8f0; font-size: 0.9rem; }
    .info-item:last-child { border-bottom: none; }
    .info-label { color: #64748b; font-weight: 600; }
    .info-val { color: #1e293b; font-weight: 700; text-align: right; }

    /* 3. Stats Card */
    .stats-card {
        background: var(--c-bg-card); border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05); padding: 24px;
        height: 100%; display: flex; flex-direction: column;
    }
    .card-heading {
        font-size: 1.1rem; font-weight: 700; color: #1e293b; 
        padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
    }

    /* Circular Chart */
    .chart-container {
        display: flex; align-items: center; justify-content: center; gap: 30px; margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .circular-chart {
        position: relative; width: 140px; height: 140px; border-radius: 50%;
        background: conic-gradient(<?php echo $statusColor; ?> 0% <?php echo $persen; ?>%, #e2e8f0 <?php echo $persen; ?>% 100%);
        display: flex; align-items: center; justify-content: center;
    }
    .chart-inner {
        width: 115px; height: 115px; background: white; border-radius: 50%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .chart-val { font-size: 1.8rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .chart-label { font-size: 0.8rem; color: #64748b; margin-top: 5px; text-transform: uppercase; }
    
    .chart-legend { flex: 1; min-width: 180px; }
    .legend-status { font-size: 1.5rem; font-weight: 700; color: <?php echo $statusColor; ?>; margin-bottom: 5px; }
    .legend-desc { font-size: 0.9rem; color: #64748b; line-height: 1.4; }

    /* Stat Grid (H/S/I/A) */
    .stat-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;
    }
    .stat-box {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
        padding: 15px 10px; text-align: center; transition: 0.2s;
    }
    .stat-box:hover { transform: translateY(-2px); border-color: var(--c-accent); }
    .stat-box-val { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 5px; }
    .stat-box-lbl { font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; }
    
    /* Colors for stats */
    .s-hadir { color: #10b981; }
    .s-sakit { color: #f59e0b; }
    .s-izin { color: #2563eb; }
    .s-alpa { color: #ef4444; }

    /* Info Box */
    .info-alert {
        background: #eff6ff; border-left: 4px solid #2563eb;
        padding: 15px 20px; border-radius: 8px; margin-top: 24px;
        color: #1e40af; font-size: 0.9rem; line-height: 1.6;
    }
</style>

<div class="welcome-card">
    <h1 class="welcome-title">Halo, <?php echo htmlspecialchars($nama); ?>!</h1>
    <p class="welcome-desc">Selamat datang di Dashboard Akademik. Pantau terus kehadiranmu ya!</p>
</div>

<?php if (isset($statistik_absensi) && !empty($statistik_absensi)): ?>
<div class="row">
    
    <div class="col-lg-4 mb-4">
        <div class="profile-card">
            <div class="profile-bg"></div>
            <div class="profile-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            
            <div class="profile-name"><?php echo htmlspecialchars($nama); ?></div>
            <div class="profile-meta"><?php echo htmlspecialchars($nis); ?></div>

            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <span class="info-val text-primary"><?php echo htmlspecialchars($kelas); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">NISN</span>
                    <span class="info-val"><?php echo htmlspecialchars($nisn); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Wali Kelas</span>
                    <span class="info-val"><?php echo htmlspecialchars($wali); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Semester</span>
                    <span class="info-val"><?php echo htmlspecialchars($statistik_absensi['semester'] ?? 'Ganjil'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="stats-card">
            <div class="card-heading">
                <i class="bi bi-pie-chart-fill text-primary"></i> Statistik Kehadiran
            </div>

            <div class="chart-container">
                <div class="circular-chart">
                    <div class="chart-inner">
                        <span class="chart-val"><?php echo $persen; ?>%</span>
                        <span class="chart-label">Hadir</span>
                    </div>
                </div>

                <div class="chart-legend">
                    <div class="legend-status">Status: <?php echo $statusText; ?></div>
                    <div class="legend-desc">
                        Tingkat kehadiran kamu saat ini. 
                        <?php if ($persen < 75): ?>
                            <br><strong style="color: #ef4444;">Perhatian:</strong> Kehadiran di bawah 75% dapat menghambat ujian.
                        <?php else: ?>
                            Pertahankan semangat belajarmu!
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-box">
                    <div class="stat-box-val s-hadir"><?php echo $hadir; ?></div>
                    <div class="stat-box-lbl">Hadir</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-val s-sakit"><?php echo $sakit; ?></div>
                    <div class="stat-box-lbl">Sakit</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-val s-izin"><?php echo $izin; ?></div>
                    <div class="stat-box-lbl">Izin</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-val s-alpa"><?php echo $alpa; ?></div>
                    <div class="stat-box-lbl">Alpa</div>
                </div>
            </div>

            <div class="info-alert">
                <i class="bi bi-info-circle-fill me-2"></i> 
                <strong>Catatan:</strong> Data di atas berdasarkan rekapitulasi absensi yang telah divalidasi oleh Wali Kelas. Jika ada ketidaksesuaian, segera lapor ke Wali Kelas.
            </div>

        </div>
    </div>

</div>
<?php else: ?>
    <div style="padding: 40px; text-align: center; background: white; border-radius: 12px; border: 1px solid #e2e8f0; color: #64748b;">
        <i class="bi bi-emoji-frown" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
        <h4 style="color: #334155; font-weight: 700;">Data Tidak Ditemukan</h4>
        <p>Maaf, sistem tidak dapat memuat data statistik absensi Anda saat ini.</p>
    </div>
<?php endif; ?>
