<?php
// 1. PHP LOGIC: Ambil Data Unik untuk Filter
$uniqueKelas = [];
$uniqueHari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]; // Hardcode urutan hari

if (!empty($data["jadwal"])) {
    // Ambil Kelas unik
    $kelasArr = array_column($data["jadwal"], "nama_kelas");
    $uniqueKelas = array_unique($kelasArr);
    sort($uniqueKelas);
}

ob_start();
?>
<style>
    /* Badge Hari Warna-Warni */
    .badge-hari { padding: 5px 10px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; border: 1px solid transparent; min-width: 70px; text-align: center; display: inline-block; }
    
    .hari-Senin   { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; } /* Biru */
    .hari-Selasa  { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; } /* Hijau */
    .hari-Rabu    { background: #fdf4ff; color: #a21caf; border-color: #f0abfc; } /* Ungu/Fuchsia */
    .hari-Kamis   { background: #fff7ed; color: #c2410c; border-color: #fed7aa; } /* Oranye */
    .hari-Jumat   { background: #fef2f2; color: #b91c1c; border-color: #fecaca; } /* Merah */
    .hari-Sabtu   { background: #f8fafc; color: #475569; border-color: #e2e8f0; } /* Abu */

    /* Time Badge */
    .time-badge {
        font-family: 'Consolas', monospace; font-size: 0.9rem;
        background: #f1f5f9; padding: 4px 8px; border-radius: 4px; color: #334155;
        border: 1px solid #e2e8f0;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Jadwal Pelajaran</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Manajemen jadwal KBM per kelas</p>
    </div>
</div>

<?php if (!$data["tahun_aktif"]): ?>
    <div class="alert alert-danger" style="border-left: 5px solid #ef4444;">
        <i class="bi bi-exclamation-triangle-fill" style="margin-right: 10px; font-size: 1.2rem;"></i>
        <div>
            <strong>Perhatian!</strong> Tidak ada Tahun Ajaran Aktif. 
            <a href="<?php echo BASE_URL; ?>/tahun-ajaran" style="text-decoration: underline; font-weight: 700;">Aktifkan di sini</a>.
        </div>
    </div>
<?php
    // Format Jam (Hapus detik)
    // Format Jam (Hapus detik)
    // Format Jam (Hapus detik)
    // Format Jam (Hapus detik)
    else: ?>
    <div class="alert alert-success" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-left: 5px solid #16a34a;">
        <i class="bi bi-calendar-check-fill" style="margin-right: 10px; font-size: 1.2rem;"></i>
        <div>
            Tahun Ajaran Aktif: <strong><?php echo htmlspecialchars(
                $data["tahun_aktif"]["tahun"]
            ); ?> - Semester <?php echo htmlspecialchars(
     $data["tahun_aktif"]["semester"]
 ); ?></strong>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: visible;">
        
        <div class="table-controls">
            <div class="filter-group">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari Mapel atau Guru...">
                </div>

                <select id="filterHari" class="form-select-filter">
                    <option value="">Semua Hari</option>
                    <?php foreach ($uniqueHari as $hari): ?>
                        <option value="<?php echo $hari; ?>"><?php echo $hari; ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="filterKelas" class="form-select-filter">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($uniqueKelas as $kls): ?>
                        <option value="<?php echo htmlspecialchars(
                            $kls
                        ); ?>"><?php echo htmlspecialchars($kls); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <a href="<?php echo BASE_URL; ?>/jadwal/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </a>
        </div>

        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th width="100" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($data["jadwal"])): ?>
                        <tr id="noDataRow">
                            <td colspan="7" style="text-align: center; padding: 50px;">
                                <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                    <i class="bi bi-calendar-x" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                    <span style="font-size: 1rem; font-weight: 500;">Belum ada jadwal pelajaran</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $no = 1;
                        foreach ($data["jadwal"] as $row):

                            $jamMulai = substr($row["jam_mulai"], 0, 5);
                            $jamSelesai = substr($row["jam_selesai"], 0, 5);
                            ?>
                        <tr class="data-row" 
                            data-hari="<?php echo htmlspecialchars(
                                $row["hari"]
                            ); ?>"
                            data-kelas="<?php echo htmlspecialchars(
                                $row["nama_kelas"]
                            ); ?>">
                            
                            <td><?php echo $no++; ?></td>
                            
                            <td>
                                <span class="badge-hari hari-<?php echo $row[
                                    "hari"
                                ]; ?>">
                                    <?php echo htmlspecialchars(
                                        $row["hari"]
                                    ); ?>
                                </span>
                            </td>

                            <td>
                                <span class="time-badge"><?php echo $jamMulai .
                                    " - " .
                                    $jamSelesai; ?></span>
                            </td>

                            <td class="searchable-kelas" style="font-weight: 700; color: var(--primary);">
                                <?php echo htmlspecialchars(
                                    $row["nama_kelas"]
                                ); ?>
                            </td>

                            <td class="searchable-mapel">
                                <div style="font-weight: 600; color: var(--text-main);">
                                    <?php echo htmlspecialchars(
                                        $row["nama_mapel"]
                                    ); ?>
                                </div>
                                <div style="font-size: 0.8rem; color: #94a3b8;">
                                    Kode: <?php echo htmlspecialchars(
                                        $row["kode_mapel"]
                                    ); ?>
                                </div>
                            </td>

                            <td class="searchable-guru">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="bi bi-person-circle" style="color: #cbd5e1;"></i>
                                    <span><?php echo htmlspecialchars(
                                        $row["nama_guru"]
                                    ); ?></span>
                                </div>
                            </td>

                            <td style="text-align: center;">
                                <a href="<?php echo BASE_URL; ?>/jadwal/edit?id=<?php echo $row[
    "jadwal_id"
]; ?>" 
                                   class="btn-action btn-edit" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/jadwal/delete?id=<?php echo $row[
    "jadwal_id"
]; ?>" 
                                   class="btn-action btn-delete" 
                                   onclick="return confirm('Hapus jadwal <?php echo htmlspecialchars(
                                       $row["nama_mapel"]
                                   ); ?> hari <?php echo $row["hari"]; ?>?')" 
                                   title="Hapus Data">
                                    <i class="bi bi-trash"></i>
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

        <div class="pagination-container">
            <div class="pagination-info" id="paginationInfo">Memuat data...</div>
            <div class="pagination-btns" id="paginationControls"></div>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
