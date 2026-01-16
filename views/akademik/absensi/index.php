<?php
// Helper untuk format tanggal Indonesia
$hariIndo = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
$namaHari = $hariIndo[date('l', strtotime($tanggal))];
$tglIndo  = date('d F Y', strtotime($tanggal));

ob_start(); 
?>

<style>
    /* Time Badge (Monospace) */
    .time-badge {
        font-family: 'Consolas', monospace; font-size: 0.9rem;
        background: #f8fafc; padding: 6px 10px; border-radius: 6px; 
        color: #334155; border: 1px solid #e2e8f0; display: inline-block;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px; border-radius: 30px; font-size: 0.8rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    
    .status-belum   { background: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1; } /* Abu-abu */
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }   /* Oranye */
    .status-valid   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }   /* Hijau */
    .status-reject  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }   /* Merah */

    /* Info Tanggal Besar */
    .date-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white; border-radius: 12px; padding: 25px; margin-bottom: 25px;
        box-shadow: 0 10px 15px -3px rgba(13, 110, 253, 0.2);
        display: flex; justify-content: space-between; align-items: center;
    }
</style>

<div class="date-card">
    <div>
        <h2 style="margin: 0; font-weight: 800; font-size: 2rem;"><?php echo $namaHari; ?></h2>
        <div style="font-size: 1.1rem; opacity: 0.9; margin-top: 5px;">
            <i class="bi bi-calendar-event me-2"></i> <?php echo $tglIndo; ?>
        </div>
    </div>
    <div style="text-align: right; opacity: 0.8; font-size: 0.9rem;">
        <i class="bi bi-clock me-1"></i> Jadwal Mengajar
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; background: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <h3 style="margin: 0; font-size: 1.1rem; color: var(--text-main); font-weight: 700;">
            <i class="bi bi-list-task me-2" style="color: var(--primary);"></i> Daftar Kelas Hari Ini
        </h3>
    </div>

    <?php if (function_exists('showAlert')) showAlert(); ?>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="150">Waktu</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Status Presensi</th>
                    <th width="180" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jadwal)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 60px 20px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <i class="bi bi-cup-hot-fill" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                                </div>
                                <h4 style="margin: 0 0 10px; color: #475569;">Tidak Ada Jadwal Mengajar</h4>
                                <span style="font-size: 0.95rem;">Nikmati hari libur Anda!</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jadwal as $j): 
                        // Tentukan Style Status
                        $statusClass = 'status-belum';
                        $iconStatus = 'bi-circle';
                        $labelStatus = 'Belum Input';
                        
                        if ($j['status_absensi'] === 'Pending') {
                            $statusClass = 'status-pending';
                            $iconStatus = 'bi-hourglass-split';
                            $labelStatus = 'Menunggu Validasi';
                        } elseif ($j['status_absensi'] === 'Valid') {
                            $statusClass = 'status-valid';
                            $iconStatus = 'bi-check-circle-fill';
                            $labelStatus = 'Tervalidasi';
                        } elseif ($j['status_absensi'] === 'Rejected') {
                            $statusClass = 'status-reject';
                            $iconStatus = 'bi-x-circle-fill';
                            $labelStatus = 'Ditolak';
                        }
                    ?>
                    <tr>
                        <td>
                            <span class="time-badge">
                                <?php echo date("H:i", strtotime($j["jam_mulai"])); ?> - 
                                <?php echo date("H:i", strtotime($j["jam_selesai"])); ?>
                            </span>
                        </td>

                        <td>
                            <span style="font-weight: 700; font-size: 1rem; color: var(--primary);">
                                <?php echo htmlspecialchars($j["nama_kelas"]); ?>
                            </span>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">
                                <?php echo htmlspecialchars($j["nama_mapel"]); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 2px;">
                                Kode: <?php echo htmlspecialchars($j["kode_mapel"]); ?>
                            </div>
                        </td>

                        <td>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <i class="bi <?php echo $iconStatus; ?>"></i> <?php echo $labelStatus; ?>
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <?php if ($j["status_absensi"] === "Belum Input" || $j["status_absensi"] === "Rejected"): ?>
                                <a href="<?php echo BASE_URL; ?>/absensi/create?jadwal_id=<?php echo $j["jadwal_id"]; ?>" 
                                   class="btn btn-primary btn-sm" style="width: 100%;">
                                   <i class="bi bi-pencil-square"></i> Input Presensi
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled style="width: 100%; opacity: 0.7; cursor: default;">
                                    <i class="bi bi-check2-all"></i> Selesai
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";
?>
