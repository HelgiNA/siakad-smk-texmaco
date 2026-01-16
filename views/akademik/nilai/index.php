<?php ob_start(); ?>

<style>
    /* Status Badge dengan Dot Indikator */
    .badge-status {
        padding: 6px 12px; border-radius: 30px; font-size: 0.8rem; font-weight: 600;
        display: inline-flex; align-items: center; border: 1px solid transparent;
    }
    .status-belum { background: #f1f5f9; color: #64748b; border-color: #cbd5e1; }
    .status-draft { background: #fffbeb; color: #d97706; border-color: #fcd34d; }
    .status-final { background: #ecfdf5; color: #059669; border-color: #6ee7b7; }
    
    .dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; display: inline-block; }
    .dot-belum { background: #94a3b8; }
    .dot-draft { background: #f59e0b; }
    .dot-final { background: #10b981; }

    /* Mapel Code */
    .code-badge {
        font-family: 'Consolas', monospace; font-size: 0.8rem;
        color: #64748b; background: #f8fafc; 
        padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0;
    }

    /* Kelas Badge */
    .kelas-badge {
        background: #eff6ff; color: #1d4ed8; font-weight: 700;
        padding: 5px 10px; border-radius: 6px; font-size: 0.9rem;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Input Nilai Siswa</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Kelola penilaian akademik berdasarkan jadwal mengajar Anda</p>
    </div>
    
    <div style="text-align: right;">
        <span style="font-size: 0.85rem; color: #64748b; display: block;">Periode Aktif</span>
        <span class="badge-custom badge-active" style="margin-top: 5px;">
            <?php echo isset($current_tahun)
                ? $current_tahun
                : "Tahun Ajaran Aktif"; ?>
        </span>
    </div>
</div>

<div class="alert-info-soft" style="background: #eff6ff; border: 1px solid #dbeafe; color: #1e40af; padding: 15px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px;">
    <i class="bi bi-info-circle-fill" style="font-size: 1.2rem; margin-top: -2px;"></i>
    <div>
        <strong>Petunjuk Pengisian:</strong>
        <ul style="margin: 5px 0 0 20px; padding: 0; font-size: 0.9rem; opacity: 0.9;">
            <li>Status <strong>Draft</strong> berarti nilai tersimpan tapi belum dipublikasikan ke rapor.</li>
            <li>Status <strong>Final</strong> berarti nilai sudah dikunci dan siap dicetak.</li>
        </ul>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
        <h3 style="margin: 0; font-size: 1.1rem; color: var(--text-main); font-weight: 700;">
            <i class="bi bi-journal-check me-2" style="color: var(--primary);"></i> Daftar Kelas Ajar
        </h3>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Status Input</th>
                    <th width="180" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($listMengajar)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 50px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <i class="bi bi-clipboard-x" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                <span style="font-size: 1rem; font-weight: 500;">Tidak ada jadwal mengajar aktif</span>
                                <span style="font-size: 0.85rem;">Hubungi admin kurikulum jika ini kesalahan.</span>
                            </div>
                        </td>
                    </tr>
                <?php
                    // Tentukan Style Status // Kuning/Amber // Putih/Abu
                    // Tentukan Style Status
                    // Kuning/Amber
                    // Putih/Abu
                    else: ?>
                    <?php
                    $no = 1;
                    foreach ($listMengajar as $item):

                        $status = $item["status_nilai"] ?? "Belum Input";

                        $statusClass = "status-belum";
                        $dotClass = "dot-belum";
                        $btnClass = "btn-primary";
                        $btnIcon = "bi-pencil-square";
                        $btnText = "Mulai Input";

                        if ($status === "Draft") {
                            $statusClass = "status-draft";
                            $dotClass = "dot-draft";
                            $btnClass = "btn-edit";
                            $btnText = "Lanjut Edit";
                        } elseif ($status === "Final") {
                            $statusClass = "status-final";
                            $dotClass = "dot-final";
                            $btnClass = "btn-secondary";
                            $btnIcon = "bi-eye";
                            $btnText = "Lihat Nilai";
                        }
                        ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        
                        <td>
                            <span class="kelas-badge">
                                <?php echo htmlspecialchars(
                                    $item["nama_kelas"]
                                ); ?>
                            </span>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                                <?php echo htmlspecialchars(
                                    $item["nama_mapel"]
                                ); ?>
                            </div>
                            <span class="code-badge">
                                <?php echo htmlspecialchars(
                                    $item["kode_mapel"] ?? "-"
                                ); ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge-status <?php echo $statusClass; ?>">
                                <span class="dot <?php echo $dotClass; ?>"></span>
                                <?php echo $status; ?>
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <a href="<?php echo BASE_URL; ?>/nilai/create?kelas_id=<?php echo $item[
    "kelas_id"
]; ?>&mapel_id=<?php echo $item["mapel_id"]; ?>" 
                               class="btn btn-sm" 
                               style="width: 100%; justify-content: center; <?php echo $status ===
                               "Final"
                                   ? "background: #fff; border: 1px solid #e2e8f0; color: #475569;"
                                   : "background: var(--primary); color: white;"; ?>">
                                <i class="bi <?php echo $btnIcon; ?>"></i> <?php echo $btnText; ?>
                            </a>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";

?>
