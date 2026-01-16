<?php
// 1. PHP LOGIC: Ambil daftar Kelompok unik untuk Filter Dropdown
$uniqueGroups = [];
if (!empty($data["mapel"])) {
    // Ambil huruf pertama kelompok (misal "C" dari "C1") agar grouping lebih rapi
    foreach ($data["mapel"] as $m) {
        $groupChar = substr($m["kelompok"], 0, 1); // A, B, atau C
        if (!in_array($groupChar, $uniqueGroups)) {
            $uniqueGroups[] = $groupChar;
        }
    }
    sort($uniqueGroups);
}

ob_start();
?>

<style>
    /* Badge Warna Warni untuk Kelompok Mapel */
    .badge-group-a { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; } /* Biru (Nasional) */
    .badge-group-b { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; } /* Hijau (Kewilayahan) */
    .badge-group-c { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; } /* Oranye (Kejuruan) */
    
    /* Styling khusus Kode Mapel */
    .code-text {
        font-family: 'Consolas', 'Monaco', monospace;
        font-weight: 600;
        color: var(--primary);
        background: #f8fafc;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        font-size: 0.85rem;
    }

    /* KKM Badge */
    .badge-kkm {
        background: #f1f5f9; color: #475569; 
        font-weight: 800; border: 1px solid #e2e8f0;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Mata Pelajaran</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Manajemen kurikulum dan kriteria ketuntasan (KKM)</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div class="table-controls">
        <div class="filter-group">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari Nama atau Kode Mapel...">
            </div>

            <select id="filterGroup" class="form-select-filter">
                <option value="">Semua Kelompok</option>
                <?php foreach ($uniqueGroups as $g): ?>
                    <option value="<?php echo htmlspecialchars(
                        $g
                    ); ?>">Kelompok <?php echo htmlspecialchars($g); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/mapel/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Mapel
        </a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Kelompok</th>
                    <th>KKM</th>
                    <th width="150" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($data["mapel"])): ?>
                    <tr id="noDataRow" style="display: none;">
                        <td colspan="6" style="text-align: center; padding: 50px 20px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <i class="bi bi-filter-circle" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                <span style="font-size: 1rem; font-weight: 500;">Data tidak ditemukan</span>
                                <span style="font-size: 0.85rem;">Coba ubah kata kunci atau filter anda.</span>
                            </div>
                        </td>
                    </tr>

                <?php else: ?>
                    <?php
                    $no = 1;
                    foreach ($data["mapel"] as $row):

                        $grp = strtoupper(substr($row["kelompok"], 0, 1));
                        $badgeClass = "badge-group-c";
                        if ($grp === "A") {
                            $badgeClass = "badge-group-a";
                        }
                        if ($grp === "B") {
                            $badgeClass = "badge-group-b";
                        }
                        ?>
                    <tr class="data-row" data-group="<?php echo $grp; ?>">
                        <td><?php echo $no++; ?></td>
                        
                        <td class="searchable-code">
                            <span class="code-text"><?php echo htmlspecialchars(
                                $row["kode_mapel"]
                            ); ?></span>
                        </td>

                        <td class="searchable-name" style="font-weight: 600;">
                            <?php echo htmlspecialchars($row["nama_mapel"]); ?>
                        </td>

                        <td>
                            <span class="badge-custom <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars(
                                    $row["kelompok"]
                                ); ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge-custom badge-kkm">
                                <?php echo htmlspecialchars($row["kkm"]); ?>
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <a href="<?php echo BASE_URL; ?>/mapel/edit?id=<?php echo $row[
    "mapel_id"
]; ?>" 
                               class="btn-action btn-edit" title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/mapel/delete?id=<?php echo $row[
    "mapel_id"
]; ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('Hapus mapel <?php echo htmlspecialchars(
                                   $row["nama_mapel"]
                               ); ?>?')" 
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

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
