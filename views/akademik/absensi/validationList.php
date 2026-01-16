<?php
// Helper Tanggal Indo
$hariIndo = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu",
];
ob_start();
?>

<style>
    /* Card Header Hijau */
    .header-validation {
        background: #ecfdf5; border-bottom: 1px solid #d1fae5;
        padding: 20px 25px; display: flex; justify-content: space-between; align-items: center;
    }
    .header-title { color: #065f46; margin: 0; font-weight: 700; font-size: 1.1rem; }
    .header-subtitle { color: #047857; font-size: 0.9rem; background: #d1fae5; padding: 4px 10px; border-radius: 20px; font-weight: 600; }

    /* Table Styles */
    .table-wrapper { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
    .custom-table th { background: #f8fafc; color: #64748b; font-weight: 700; padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.8rem; text-transform: uppercase; }
    .custom-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    
    /* Time Badge */
    .time-badge {
        font-family: 'Consolas', monospace; font-size: 0.85rem;
        background: #f1f5f9; padding: 4px 8px; border-radius: 4px; color: #334155; border: 1px solid #e2e8f0;
    }

    /* Status Pending */
    .badge-pending {
        background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;
        padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px;
    }

    /* Button Review */
    .btn-review {
        background: #10b981; color: white; border: none; padding: 8px 16px; 
        border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; 
        display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    .btn-review:hover { background: #059669; transform: translateY(-1px); color: white; }

    /* Empty State */
    .empty-state { text-align: center; padding: 50px 20px; color: #94a3b8; }
    .empty-icon { font-size: 3rem; color: #cbd5e1; margin-bottom: 15px; display: block; }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        
        <div style="margin-bottom: 20px;">
            <h1 class="page-title" style="margin:0;">Validasi Absensi</h1>
            <p style="color: #64748b; margin: 5px 0 0;">Verifikasi laporan kehadiran dari guru mata pelajaran.</p>
        </div>

        <div class="card" style="border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            
            <div class="header-validation">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; background: #10b981; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-clipboard-check-fill" style="font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h5 class="header-title">Daftar Menunggu Persetujuan</h5>
                        <div style="font-size: 0.85rem; color: #059669; margin-top: 2px;">
                            Total: <strong><?php echo count(
                                $pending
                            ); ?></strong> Laporan
                        </div>
                    </div>
                </div>
                
                <?php if ($isWaliKelas): ?>
                    <span class="header-subtitle">
                        <i class="bi bi-door-open-fill"></i> Kelas <?php echo htmlspecialchars(
                            $kelas["nama_kelas"]
                        ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div style="background: white;">

                <?php if (empty($pending)): ?>
                    <div class="empty-state">
                        <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: #10b981; opacity: 0.2; margin-bottom: 10px;"></i>
                        <h4 style="color: #334155; font-weight: 700; margin-bottom: 5px;">Semua Beres!</h4>
                        <p>Tidak ada laporan absensi yang perlu divalidasi saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Waktu Pelaksanaan</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru Pengampu</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending as $row):

                                    $timestamp = strtotime($row["tanggal"]);
                                    $hari = $hariIndo[date("l", $timestamp)];
                                    $tgl = date("d M Y", $timestamp);
                                    ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: #334155; margin-bottom: 4px;">
                                            <?php echo $hari . ", " . $tgl; ?>
                                        </div>
                                        <span class="time-badge">
                                            <i class="bi bi-clock"></i> 
                                            <?php echo date(
                                                "H:i",
                                                strtotime($row["jam_mulai"])
                                            ); ?> - 
                                            <?php echo date(
                                                "H:i",
                                                strtotime($row["jam_selesai"])
                                            ); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div style="font-weight: 700; color: #0d6efd;"><?php echo htmlspecialchars(
                                            $row["nama_mapel"]
                                        ); ?></div>
                                        <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 2px;">
                                            Kode: <?php echo htmlspecialchars(
                                                $row["kode_mapel"]
                                            ); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 28px; height: 28px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <span style="font-weight: 500;"><?php echo htmlspecialchars(
                                                $row["nama_guru"]
                                            ); ?></span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge-pending">
                                            <i class="bi bi-hourglass-split"></i> Pending
                                        </span>
                                    </td>

                                    <td style="text-align: center;">
                                        <a href="<?php echo BASE_URL; ?>/absensi/validasi/review?absensi_id=<?php echo $row[
    "absensi_id"
]; ?>" 
                                           class="btn-review">
                                            <i class="bi bi-search"></i> Periksa
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                endforeach; ?>
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
