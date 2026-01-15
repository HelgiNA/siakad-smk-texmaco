<?php ob_start(); ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Data Guru</h1>
        <p style="color: var(--text-light); margin:5px 0 0; font-size: 0.95rem;">Kelola data pengajar dan staf akademik</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    
    <div class="table-controls">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari NIP atau Nama Guru...">
        </div>
        
        <a href="<?php echo BASE_URL; ?>/guru/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Guru
        </a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>NIP / Identitas</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th width="100" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($data["gurus"])): ?>
                    <tr id="emptyStateRow">
                        <td colspan="5" style="text-align: center; padding: 50px 20px;">
                            <div style="color: #94a3b8; display: flex; flex-direction: column; align-items: center;">
                                <i class="bi bi-person-x" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                <span style="font-size: 1rem; font-weight: 500;">Belum ada data guru</span>
                                <span style="font-size: 0.85rem;">Silakan tambahkan guru baru.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["gurus"] as $index => $guru): ?>
                    <tr class="data-row">
                        <td><?php echo $index + 1; ?></td>
                        
                        <td class="searchable-nip">
                            <span style="font-weight: 600; color: var(--text-main);">
                                <?php echo !empty($guru["nip"])
                                    ? htmlspecialchars($guru["nip"])
                                    : "-"; ?>
                            </span>
                        </td>

                        <td class="searchable-name">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; background:#fff7ed; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:bold; color:#ea580c;">
                                    <?php echo strtoupper(
                                        substr($guru["nama_lengkap"], 0, 1)
                                    ); ?>
                                </div>
                                <?php echo htmlspecialchars(
                                    $guru["nama_lengkap"]
                                ); ?>
                            </div>
                        </td>

                        <td>
                            <span class="badge-custom badge-guru">
                                <i class="bi bi-person-badge"></i> <?php echo htmlspecialchars(
                                    $guru["role"] ?? "Guru"
                                ); ?>
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <a href="<?php echo BASE_URL; ?>/guru/edit?id=<?php echo $guru[
    "guru_id"
]; ?>" 
                               class="btn-action btn-edit" title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/guru/delete?id=<?php echo $guru[
    "guru_id"
]; ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')" 
                               title="Hapus Data">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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
        <div class="pagination-info" id="paginationInfo">Memuat data...</div>
        <div class="pagination-btns" id="paginationControls"></div>
    </div>
</div>

 

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";


?>
