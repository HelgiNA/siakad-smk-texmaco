<?php
// PHP Logic tetap ada di sini
$uniqueClasses = [];
if (!empty($students)) {
    $classes = array_column($students, "nama_kelas");
    $uniqueClasses = array_unique($classes);
    sort($uniqueClasses);
}

ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Manajemen Siswa</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Kelola data siswa, kelas, dan status akademik</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div class="table-controls">
        <div class="filter-group">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari Nama atau NIS...">
            </div>

            <select id="filterClass" class="form-select-filter">
                <option value="">Semua Kelas</option>
                <?php foreach ($uniqueClasses as $kelas): ?>
                    <?php if (!empty($kelas)): ?>
                        <option value="<?php echo htmlspecialchars(
                            $kelas
                        ); ?>"><?php echo htmlspecialchars($kelas); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
                <option value="NULL">Tanpa Kelas</option>
            </select>

            <select id="filterStatus" class="form-select-filter">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/siswa/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Identitas (NIS/NISN)</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th width="100" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $no = 1;
                foreach ($students as $student):

                    $kelasVal = !empty($student["nama_kelas"])
                        ? $student["nama_kelas"]
                        : "NULL";
                    $statusVal = $student["status_aktif"];
                    ?>
                <tr class="data-row" data-kelas="<?php echo htmlspecialchars(
                    $kelasVal
                ); ?>" data-status="<?php echo $statusVal; ?>">
                    <td><?php echo $no++; ?></td>
                    
                    <td class="searchable-nis">
                        <span style="font-weight: 600; color: #334155;"><?php echo htmlspecialchars(
                            $student["nis"]
                        ); ?></span>
                        <span style="color: #94a3b8; font-size: 0.85rem;"> | <?php echo htmlspecialchars(
                            $student["nisn"]
                        ); ?></span>
                    </td>

                    <td class="searchable-name">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:bold; color:#64748b;">
                                <?php echo strtoupper(
                                    substr($student["nama_lengkap"], 0, 1)
                                ); ?>
                            </div>
                            <?php echo htmlspecialchars(
                                $student["nama_lengkap"]
                            ); ?>
                        </div>
                    </td>

                    <td class="searchable-class">
                        <?php if (!empty($student["nama_kelas"])): ?>
                            <span class="badge-custom badge-class"><i class="bi bi-diagram-3-fill"></i> <?php echo htmlspecialchars(
                                $student["nama_kelas"]
                            ); ?></span>
                        <?php else: ?>
                            <span style="color: #94a3b8; font-style: italic; font-size: 0.9rem;">Belum ada kelas</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($student["status_aktif"] == 1): ?>
                            <span class="badge-custom badge-active">Aktif</span>
                        <?php else: ?>
                            <span class="badge-custom badge-inactive">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>

                    <td style="text-align: center;">
                        <a href="<?php echo BASE_URL; ?>/siswa/edit?id=<?php echo $student[
    "siswa_id"
]; ?>" class="btn-action btn-edit"><i class="bi bi-pencil-square"></i></a>
                        <a href="<?php echo BASE_URL; ?>/siswa/delete?id=<?php echo $student[
    "siswa_id"
]; ?>" class="btn-action btn-delete" onclick="return confirm('Hapus data siswa ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php
                endforeach;
                ?>
                
                <tr id="noDataRow" style="display: none;">
                    <td colspan="6" style="text-align: center; padding: 50px 20px;">
                        <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                            <i class="bi bi-filter-circle" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                            <span style="font-size: 1rem; font-weight: 500;">Data tidak ditemukan</span>
                            <span style="font-size: 0.85rem;">Coba ubah kata kunci atau filter anda.</span>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div class="pagination-info" id="paginationInfo">Memuat...</div>
        <div class="pagination-btns" id="paginationControls"></div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
