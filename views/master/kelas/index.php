<?php
// 1. PHP LOGIC: Ambil Data Unik untuk Filter Dropdown
$uniqueTingkat = [];
$uniqueJurusan = [];

if (!empty($data['kelas'])) {
    // Ambil Tingkat unik
    $tingkatArr = array_column($data['kelas'], 'tingkat');
    $uniqueTingkat = array_unique($tingkatArr);
    sort($uniqueTingkat); // Urutkan (10, 11, 12)

    // Ambil Jurusan unik
    $jurusanArr = array_column($data['kelas'], 'jurusan');
    $uniqueJurusan = array_unique($jurusanArr);
    sort($uniqueJurusan);
}

ob_start(); 
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/datatable.css">

<style>
    /* Badge Tingkat (Bulat) */
    .badge-tingkat {
        width: 30px; height: 30px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem;
    }
    .bg-t-10 { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; } /* Hijau */
    .bg-t-11 { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; } /* Biru */
    .bg-t-12 { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; } /* Merah */
    .bg-t-def { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; } /* Abu */

    /* Jurusan Tag */
    .tag-jurusan {
        font-size: 0.85rem; padding: 4px 8px; border-radius: 4px;
        background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-weight: 600;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Data Kelas</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Manajemen rombongan belajar dan wali kelas</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div class="table-controls">
        <div class="filter-group">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari Nama Kelas...">
            </div>

            <select id="filterTingkat" class="form-select-filter">
                <option value="">Semua Tingkat</option>
                <?php foreach ($uniqueTingkat as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>">Kelas <?php echo htmlspecialchars($t); ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterJurusan" class="form-select-filter">
                <option value="">Semua Jurusan</option>
                <?php foreach ($uniqueJurusan as $j): ?>
                    <option value="<?php echo htmlspecialchars($j); ?>"><?php echo htmlspecialchars($j); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/kelas/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kelas
        </a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Tingkat</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th width="150" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($data['kelas'])): ?>
                    <tr id="noDataRow">
                        <td colspan="7" style="text-align: center; padding: 50px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <i class="bi bi-door-closed" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                <span style="font-size: 1rem; font-weight: 500;">Belum ada data kelas</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $no = 1; 
                    foreach ($data['kelas'] as $row): 
                        // Logic Warna Badge Tingkat
                        $t = $row['tingkat'];
                        $badgeClass = 'bg-t-def';
                        if(strpos($t, '10') !== false || $t == 'X') $badgeClass = 'bg-t-10';
                        if(strpos($t, '11') !== false || $t == 'XI') $badgeClass = 'bg-t-11';
                        if(strpos($t, '12') !== false || $t == 'XII') $badgeClass = 'bg-t-12';
                    ?>
                    <tr class="data-row" 
                        data-tingkat="<?php echo htmlspecialchars($row['tingkat']); ?>"
                        data-jurusan="<?php echo htmlspecialchars($row['jurusan']); ?>">
                        
                        <td><?php echo $no++; ?></td>
                        
                        <td>
                            <div class="badge-tingkat <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars($row['tingkat']); ?>
                            </div>
                        </td>

                        <td class="searchable-name" style="font-weight: 700; color: var(--primary);">
                            <?php echo htmlspecialchars($row['nama_kelas']); ?>
                        </td>

                        <td>
                            <span class="tag-jurusan">
                                <?php echo htmlspecialchars($row['jurusan']); ?>
                            </span>
                        </td>

                        <td>
                            <?php if (!empty($row['nama_wali_kelas'])): ?>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="bi bi-person-circle" style="color: #cbd5e1;"></i>
                                    <span><?php echo htmlspecialchars($row['nama_wali_kelas']); ?></span>
                                </div>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">
                                    Belum ditentukan
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span style="font-size: 0.9rem; color: #475569;">
                                <i class="bi bi-calendar4-week" style="margin-right: 5px; color: #94a3b8;"></i>
                                <?php echo htmlspecialchars($row['tahun']); ?>
                                <small class="text-muted">(<?php echo htmlspecialchars($row['semester']); ?>)</small>
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <a href="<?php echo BASE_URL ?>/kelas/edit?id=<?php echo $row['kelas_id'] ?>" 
                               class="btn-action btn-edit" title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?php echo BASE_URL ?>/kelas/delete?id=<?php echo $row['kelas_id'] ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('Hapus kelas <?php echo htmlspecialchars($row['nama_kelas']); ?>? Data siswa di dalamnya mungkin akan terdampak.')" 
                               title="Hapus Data">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
    require_once __DIR__ . '/../../layouts/main.php';
?>
