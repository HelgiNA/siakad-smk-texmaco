<?php
// Mencegah akses langsung jika variabel dari controller tidak ada
$totalSiswa = $total_siswa ?? 0;
$totalGuru  = $total_guru ?? 0;
$totalKelas = $total_kelas ?? 0;
$avgHadir   = $avg_hadir ?? 0;

// Data List (Default array kosong jika data tidak ada)
$pendingNilai    = $pending_nilai ?? [];
$siswaBermasalah = $siswa_bermasalah ?? [];

// Tanggal (Diambil dari controller atau generate sendiri)
$tanggalHariIni = $tanggal ?? date('d F Y');
?>

<style>
    :root {
        --c-primary: #0f172a;
        --c-accent: #2563eb;
        --c-bg-card: #ffffff;
        --radius: 12px;
    }

    /* 1. Welcome Banner (Executive Look) */
    .welcome-card {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: white; border-radius: var(--radius);
        padding: 30px 25px; margin-bottom: 24px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
    }
    .welcome-content { position: relative; z-index: 2; }
    .welcome-date { font-size: 0.85rem; opacity: 0.8; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .welcome-title { font-size: 1.8rem; font-weight: 800; margin: 0; line-height: 1.2; }
    .welcome-subtitle { font-size: 1rem; opacity: 0.8; margin-top: 5px; font-weight: 400; }
    
    /* Dekorasi Background */
    .welcome-card::after {
        content: ''; position: absolute; top: -50%; right: -5%;
        width: 250px; height: 250px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%; z-index: 1; pointer-events: none;
    }

    /* 2. Stat Cards Grid */
    .stat-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;
    }
    .stat-card {
        background: white; padding: 20px; border-radius: var(--radius);
        border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        display: flex; align-items: center; gap: 15px; transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); border-color: var(--c-accent); }
    
    .stat-icon {
        width: 50px; height: 50px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0;
    }
    .stat-info h3 { margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b; }
    .stat-info p { margin: 0; font-size: 0.85rem; color: #64748b; font-weight: 600; }

    /* Helper Colors */
    .bg-blue-soft { background: #eff6ff; color: #2563eb; }
    .bg-green-soft { background: #f0fdf4; color: #166534; }
    .bg-purple-soft { background: #f5f3ff; color: #7c3aed; }
    .bg-orange-soft { background: #fff7ed; color: #c2410c; }

    /* 3. Dashboard Panels */
    .dash-card {
        background: white; border-radius: var(--radius);
        border: 1px solid #e2e8f0; height: 100%;
        display: flex; flex-direction: column; overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .dash-header {
        padding: 15px 20px; border-bottom: 1px solid #f1f5f9; background: #fff;
        font-weight: 700; color: #334155; display: flex; justify-content: space-between; align-items: center;
    }
    
    /* Table Mini */
    .table-responsive { width: 100%; overflow-x: auto; }
    .table-mini { width: 100%; border-collapse: collapse; font-size: 0.9rem; min-width: 500px; }
    .table-mini th { text-align: left; padding: 12px 15px; background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; }
    .table-mini td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .table-mini tr:last-child td { border-bottom: none; }

    /* Badges */
    .badge-status { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
    .badge-draft { background: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; }
    .badge-submitted { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    
    /* Layout Fix */
    .row { display: flex; flex-wrap: wrap; margin: 0 -10px; }
    .col-lg-8 { width: 100%; padding: 0 10px; margin-bottom: 20px; }
    .col-lg-4 { width: 100%; padding: 0 10px; margin-bottom: 20px; }
    
    @media(min-width: 992px) {
        .col-lg-8 { width: 66.66%; }
        .col-lg-4 { width: 33.33%; }
    }
</style>

<div class="welcome-card">
    <div class="welcome-content">
        <div class="welcome-date">
            <i class="bi bi-geo-alt-fill"></i> SMK TEXMACO SUBANG &bull; <?php echo $tanggalHariIni; ?>
        </div>
        <h1 class="welcome-title">Selamat Datang, Bapak Kepala Sekolah</h1>
        <p class="welcome-subtitle">Berikut adalah ringkasan performa akademik sekolah hari ini.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon bg-blue-soft"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($totalSiswa); ?></h3>
            <p>Total Siswa Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple-soft"><i class="bi bi-person-badge-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($totalGuru); ?></h3>
            <p>Total Guru</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green-soft"><i class="bi bi-pie-chart-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo $avgHadir; ?>%</h3>
            <p>Rata-rata Kehadiran</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-orange-soft"><i class="bi bi-building"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($totalKelas); ?></h3>
            <p>Rombongan Belajar</p>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-header">
                <span><i class="bi bi-clipboard-data me-2 text-primary"></i> Monitoring Input Nilai</span>
                <small class="text-muted">Status Terkini</small>
            </div>
            
            <div class="table-responsive">
                <table class="table-mini">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pendingNilai)): ?>
                            <?php foreach($pendingNilai as $pn): 
                                $status = $pn['status'] ?? 'Draft';
                                $badgeClass = ($status == 'Submitted') ? 'badge-submitted' : 'badge-draft';
                            ?>
                            <tr>
                                <td width="100"><strong><?php echo htmlspecialchars($pn['kelas']); ?></strong></td>
                                <td><?php echo htmlspecialchars($pn['mapel']); ?></td>
                                <td><?php echo htmlspecialchars($pn['guru']); ?></td>
                                <td class="text-center">
                                    <span class="badge-status <?php echo $badgeClass; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4" style="padding: 30px;">
                                    <i class="bi bi-check-circle text-success fs-3 mb-2 d-block"></i>
                                    Semua nilai sudah final. Tidak ada pending.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="padding: 15px; border-top: 1px solid #f1f5f9; text-align: center; margin-top: auto;">
                <a href="<?php echo BASE_URL; ?>/laporan/nilai" class="btn btn-sm btn-outline-primary" style="text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                    Lihat Laporan Lengkap <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-header" style="border-left: 4px solid #ef4444;">
                <span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Perhatian Khusus</span>
            </div>
            <div style="padding: 20px;">
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 15px; line-height: 1.5;">
                    Siswa berikut memiliki tingkat ketidakhadiran (Alpa) tertinggi semester ini.
                </p>
                
                <?php if(!empty($siswaBermasalah)): ?>
                    <?php foreach($siswaBermasalah as $sb): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #e2e8f0;">
                        <div>
                            <div style="font-weight: 700; color: #334155; font-size: 0.95rem;">
                                <?php echo htmlspecialchars($sb['nama']); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #94a3b8;">
                                <?php echo htmlspecialchars($sb['kelas']); ?>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.2rem; font-weight: 800; color: #ef4444;">
                                <?php echo htmlspecialchars($sb['alpa']); ?>
                            </span>
                            <span style="font-size: 0.7rem; color: #ef4444; display: block; font-weight: 600;">Alpa</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-success py-4">
                        <i class="bi bi-emoji-smile-fill mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0 small fw-bold">Tidak ada siswa bermasalah.</p>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>

</div>