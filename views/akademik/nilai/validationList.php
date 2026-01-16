<?php ob_start(); ?>

<style>
    /* Header Card khusus Validasi */
    .header-val {
        background: #f0fdf4; /* Hijau pudar sangat muda */
        border-bottom: 1px solid #bbf7d0;
        padding: 20px 25px; 
        display: flex; justify-content: space-between; align-items: center;
    }
    .header-title { color: #166534; margin: 0; font-weight: 700; font-size: 1.1rem; }
    .header-subtitle { 
        background: #fff; color: #15803d; 
        padding: 5px 12px; border-radius: 20px; 
        font-size: 0.85rem; font-weight: 600; border: 1px solid #bbf7d0;
    }

    /* Table Styles */
    .table-wrapper { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
    .custom-table th { 
        background: #f8fafc; color: #64748b; font-weight: 700; 
        padding: 12px 15px; text-align: left; 
        border-bottom: 1px solid #e2e8f0; font-size: 0.8rem; text-transform: uppercase; 
    }
    .custom-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    
    /* Time Badge */
    .time-badge {
        font-family: 'Consolas', monospace; font-size: 0.85rem;
        background: #f8fafc; padding: 4px 8px; border-radius: 4px; 
        color: #475569; border: 1px solid #e2e8f0; display: inline-block; margin-top: 4px;
    }

    /* Avatar Guru */
    .teacher-avatar {
        width: 32px; height: 32px; 
        background: #e0f2fe; color: #0284c7; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; margin-right: 10px;
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
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
</style>

<div style="margin-bottom: 24px;">
    <h1 class="page-title" style="margin:0;">Validasi Nilai</h1>
    <p style="color: #64748b; margin: 5px 0 0;">Verifikasi nilai rapor yang diajukan oleh guru mata pelajaran.</p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card" style="border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            
            <div class="header-val">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; background: #fff; color: #166534; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #bbf7d0;">
                        <i class="bi bi-clipboard-check-fill" style="font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="header-title">Pengajuan Nilai Masuk</h5>
                        <div style="font-size: 0.85rem; color: #15803d; margin-top: 2px;">
                            Perlu divalidasi: <strong><?php echo count($pending); ?></strong> Mapel
                        </div>
                    </div>
                </div>
                
                <?php if ($isWaliKelas): ?>
                <span class="header-subtitle">
                    <i class="bi bi-people-fill me-1"></i> Kelas <?php echo htmlspecialchars($kelas["nama_kelas"]); ?>
                </span>
                <?php endif; ?>
            </div>
            
            <div class="card-body" style="padding: 0;">
                <?php if (function_exists('showAlert')) showAlert(); ?>
                
                <?php if (empty($pending)): ?>
                    <div class="empty-state">
                        <div style="width: 80px; height: 80px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="bi bi-check-lg" style="font-size: 3rem; color: #86efac;"></i>
                        </div>
                        <h4 style="color: #334155; font-weight: 700; margin-bottom: 5px;">Tugas Selesai!</h4>
                        <p style="margin: 0;">Tidak ada nilai yang perlu divalidasi saat ini.</p>
                    </div>
                <?php else: ?>
                
                <div class="table-wrapper">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Waktu Pengajuan</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru Pengampu</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending as $row): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #334155;">
                                        <?php echo date("d M Y", strtotime($row["tgl_input"])); ?>
                                    </div>
                                    <span class="time-badge">
                                        <i class="bi bi-clock"></i> <?php echo date("H:i", strtotime($row["tgl_input"])); ?> WIB
                                    </span>
                                </td>

                                <td>
                                    <div style="font-weight: 700; color: #0d6efd; font-size: 1rem;">
                                        <?php echo htmlspecialchars($row["nama_mapel"]); ?>
                                    </div>
                                    <?php if(isset($row["kode_mapel"])): ?>
                                        <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 2px;">
                                            Kode: <?php echo htmlspecialchars($row["kode_mapel"]); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div class="teacher-avatar">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span style="font-weight: 500; color: #334155;">
                                            <?php echo htmlspecialchars($row["nama_guru"]); ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span style="background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="bi bi-hourglass-split"></i> Menunggu
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo BASE_URL; ?>/nilai/validasi/review?nilai_id=<?php echo $row["nilai_id"]; ?>"
                                        class="btn-review">
                                        <i class="bi bi-search"></i> Periksa
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
