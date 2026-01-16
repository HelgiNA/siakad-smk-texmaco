<?php
// 1. PHP LOGIC: Ambil Data Unik untuk Filter Dropdown
$uniqueTingkat = [];
$uniqueJurusan = [];

if (!empty($kelas)) {
    // Ambil Tingkat unik
    $tingkatArr = array_column($kelas, 'tingkat');
    $uniqueTingkat = array_unique($tingkatArr);
    sort($uniqueTingkat);

    // Ambil Jurusan unik
    $jurusanArr = array_column($kelas, 'jurusan');
    $uniqueJurusan = array_unique($jurusanArr);
    sort($uniqueJurusan);
}

ob_start(); 
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/datatable.css">

<style>
    /* Badge Tingkat (Bulat - Konsisten dengan Data Kelas) */
    .badge-tingkat {
        width: 30px; height: 30px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem;
    }
    .bg-t-10 { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .bg-t-11 { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .bg-t-12 { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .bg-t-def { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

    /* Badge Jumlah Siswa */
    .count-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 20px;
        font-weight: 700; font-size: 0.9rem;
    }
    .count-empty { background: #f1f5f9; color: #94a3b8; border: 1px dashed #cbd5e1; }
    .count-filled { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }

    /* Tombol Kelola */
    .btn-manage {
        background: #0f172a; /* Dark Navy */
        color: white; border: none;
        padding: 8px 16px; border-radius: 6px;
        font-size: 0.85rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: 0.2s;
    }
    .btn-manage:hover { background: #334155; transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Plotting Siswa</h1>
        <p style="color: #64748b; margin:5px 0 0; font-size: 0.95rem;">Manajemen anggota rombongan belajar</p>
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
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th style="text-align: center;">Jumlah Siswa</th>
                    <th width="150" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($kelas)): ?>
                    <tr id="noDataRow">
                        <td colspan="6" style="text-align: center; padding: 50px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <i class="bi bi-people" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                <span style="font-size: 1rem; font-weight: 500;">Belum ada data Kelas</span>
                                <span style="font-size: 0.85rem;">Silakan tambah kelas di menu Master Data.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $no = 1; 
                    foreach ($kelas as $row): 
                        // Style Tingkat
                        $t = $row['tingkat'];
                        $bgTingkat = 'bg-t-def';
                        if(strpos($t, '10') !== false || $t == 'X') $bgTingkat = 'bg-t-10';
                        if(strpos($t, '11') !== false || $t == 'XI') $bgTingkat = 'bg-t-11';
                        if(strpos($t, '12') !== false || $t == 'XII') $bgTingkat = 'bg-t-12';

                        // Style Jumlah Siswa
                        $count = $row['jumlah_siswa'];
                        $countClass = ($count > 0) ? 'count-filled' : 'count-empty';
                        $iconCount = ($count > 0) ? 'bi-person-check-fill' : 'bi-person-dash';
                    ?>
                    <tr class="data-row" 
                        data-tingkat="<?php echo htmlspecialchars($row['tingkat']); ?>"
                        data-jurusan="<?php echo htmlspecialchars($row['jurusan']); ?>">
                        
                        <td><?php echo $no++; ?></td>
                        
                        <td class="searchable-name" style="font-weight: 700; color: var(--text-main);">
                            <?php echo htmlspecialchars($row['nama_kelas']); ?>
                        </td>

                        <td>
                            <div class="badge-tingkat <?php echo $bgTingkat; ?>">
                                <?php echo htmlspecialchars($row['tingkat']); ?>
                            </div>
                        </td>

                        <td>
                            <span style="font-weight: 600; color: #64748b;">
                                <?php echo htmlspecialchars($row['jurusan']); ?>
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <span class="count-badge <?php echo $countClass; ?>">
                                <i class="bi <?php echo $iconCount; ?>"></i>
                                <?php echo $count; ?> Siswa
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <a href="<?php echo BASE_URL ?>/plotting/manage?id=<?php echo $row['kelas_id'] ?>" 
                               class="btn-manage">
                                <i class="bi bi-gear-wide-connected"></i> Atur Anggota
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
