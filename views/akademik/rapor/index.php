<?php
ob_start();

// Hitung Statistik Ringkas
$totalSiswa = count($list ?? []);
$siapCetak = 0;

if (!empty($list)) {
    foreach ($list as $item) {
        if ($item["status_catatan"] === "Sudah Diisi") {
            $siapCetak++;
        }
    }
}
$persenSelesai = $totalSiswa > 0 ? round(($siapCetak / $totalSiswa) * 100) : 0;
?>

<style>
    :root {
        --c-primary: #0f172a; /* Dark Navy for Print Context */
        --c-accent: #3b82f6;
        --c-success: #10b981;
        --c-warning: #f59e0b;
        --c-bg-card: #ffffff;
        --radius: 12px;
    }

    /* Card & Layout */
    .rapor-card {
        background: var(--c-bg-card); border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
    }
    
    .rapor-header {
        background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;
    }

    /* Stats Box */
    .stats-row {
        display: flex; gap: 20px; padding: 20px 24px;
        background: white; border-bottom: 1px solid #f1f5f9;
    }
    .stat-item {
        display: flex; flex-direction: column;
    }
    .stat-label { font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px; }
    .stat-val { font-size: 1.5rem; font-weight: 800; color: #1e293b; line-height: 1.2; }
    .stat-sub { font-size: 0.85rem; color: #10b981; font-weight: 600; }

    /* Progress Bar */
    .progress-wrapper {
        flex: 1; display: flex; flex-direction: column; justify-content: center; padding-left: 20px; border-left: 1px solid #e2e8f0;
    }
    .progress-bar-bg {
        width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-top: 5px;
    }
    .progress-bar-fill {
        height: 100%; background: var(--c-success); border-radius: 4px; transition: width 0.5s ease;
    }

    /* Table Styling */
    .table-wrapper { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
    .custom-table th { 
        background: #f8fafc; color: #64748b; font-weight: 700; 
        padding: 12px 16px; text-align: left; 
        border-bottom: 1px solid #e2e8f0; font-size: 0.8rem; text-transform: uppercase; 
    }
    .custom-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    
    /* Badges */
    .status-badge {
        padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .badge-ready { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-pending { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Action Buttons */
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; border: 1px solid transparent; transition: 0.2s;
    }
    .btn-note { background: #fff; border-color: #cbd5e1; color: #475569; }
    .btn-note:hover { background: #f8fafc; border-color: #94a3b8; color: #334155; }
    
    .btn-print { background: #0f172a; color: white; }
    .btn-print:hover { background: #1e293b; color: white; transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    
    .btn-disabled { background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; border: 1px solid #e2e8f0; }
</style>

<div style="margin-bottom: 24px;">
    <h1 class="page-title" style="margin:0; font-size: 1.5rem; font-weight: 700;">Cetak Rapor Siswa</h1>
    <p style="color: #64748b; margin: 5px 0 0;">
        Kelola catatan wali kelas dan cetak hasil belajar siswa.
    </p>
</div>

<div class="row">
    <div class="col-12">
        <div class="rapor-card">
            
            <div class="rapor-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; background: #e0f2fe; color: #0284c7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-printer-fill" style="font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <h5 style="margin: 0; font-weight: 700; color: #1e293b;">Manajemen Rapor</h5>
                        <?php if (isset($kelas)): ?>
                            <span style="font-size: 0.9rem; color: #64748b;">
                                Kelas: <strong><?php echo htmlspecialchars(
                                    $kelas["nama_kelas"]
                                ); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($list)): ?>
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-label">Total Siswa</span>
                    <span class="stat-val"><?php echo $totalSiswa; ?></span>
                </div>
                <div class="stat-item" style="padding-left: 30px; border-left: 1px solid #f1f5f9;">
                    <span class="stat-label">Siap Cetak</span>
                    <span class="stat-val" style="color: #10b981;"><?php echo $siapCetak; ?></span>
                </div>
                
                <div class="progress-wrapper">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600; color: #64748b;">
                        <span>Kelengkapan Catatan</span>
                        <span><?php echo $persenSelesai; ?>%</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?php echo $persenSelesai; ?>%;"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card-body" style="padding: 0;">
                <?php if (empty($list)): ?>
                    <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                        <i class="bi bi-person-x" style="font-size: 3rem; margin-bottom: 15px; display: block; opacity: 0.5;"></i>
                        <h5 style="color: #475569; font-weight: 600;">Data Tidak Ditemukan</h5>
                        <p style="font-size: 0.95rem;">Anda belum terdaftar sebagai Wali Kelas atau kelas belum memiliki siswa.</p>
                    </div>
                <?php 
                    else: ?>
                    
                    <div class="table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width:50px; text-align: center;">No</th>
                                    <th style="width:120px;">NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Data</th>
                                    <th style="width:220px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($list as $item):

                                    $s = $item["siswa"];
                                    $status = $item["status_catatan"];
                                    $isReady = $status === "Sudah Diisi";
                                    ?>
                                <tr>
                                    <td style="text-align: center; color: #94a3b8;"><?php echo $no++; ?></td>
                                    <td style="font-family: monospace; color: #64748b;"><?php echo htmlspecialchars(
                                        $s["nis"]
                                    ); ?></td>
                                    <td style="font-weight: 600; color: #334155;">
                                        <?php echo htmlspecialchars(
                                            $s["nama_lengkap"]
                                        ); ?>
                                    </td>
                                    
                                    <td>
                                        <?php if ($isReady): ?>
                                            <span class="status-badge badge-ready">
                                                <i class="bi bi-check-circle-fill"></i> Siap Cetak
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge badge-pending">
                                                <i class="bi bi-exclamation-circle-fill"></i> Butuh Catatan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 8px;">
                                            <a href="<?php echo BASE_URL; ?>/rapor/catatan?id=<?php echo $s[
    "siswa_id"
]; ?>" 
                                               class="btn btn-action btn-note" 
                                               title="Input Catatan Wali Kelas">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <?php if ($isReady): ?>
                                                <a href="<?php echo BASE_URL; ?>/rapor/print?id=<?php echo $s[
    "siswa_id"
]; ?>" 
                                                   target="_blank"
                                                   class="btn btn-action btn-print"
                                                   title="Cetak Rapor PDF">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-action btn-disabled" disabled title="Lengkapi catatan terlebih dahulu">
                                                    <i class="bi bi-printer"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
