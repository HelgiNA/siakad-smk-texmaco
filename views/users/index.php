<?php ob_start(); ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0;">Manajemen Pengguna</h1>
        <p style="color: var(--text-light); margin:5px 0 0; font-size: 0.95rem;">Kelola data akun untuk akses sistem</p>
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;"> <div class="table-controls">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari Username atau Role...">
        </div>
        
        <a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah User
        </a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table" id="userTable">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Username</th>
                    <th>Role (Peran)</th>
                    <th width="120" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $no = 1;
                foreach ($users as $user): ?>
                <tr class="data-row">
                    <td><?php echo $no++; ?></td>
                    <td class="searchable-name">
                        <strong><?php echo htmlspecialchars(
                            $user["username"]
                        ); ?></strong>
                    </td>
                    <td class="searchable-role">
                        <?php if ($user["role"] == "Admin"): ?>
                            <span class="badge-custom badge-admin"><i class="bi bi-shield-lock-fill"></i> Admin</span>
                        <?php elseif ($user["role"] == "Guru"): ?>
                            <span class="badge-custom badge-guru"><i class="bi bi-person-workspace"></i> Guru</span>
                        <?php elseif ($user["role"] == "Siswa"): ?>
                            <span class="badge-custom badge-siswa"><i class="bi bi-person-fill"></i> Siswa</span>
                        <?php else: ?>
                            <span class="badge-custom badge-guest">Guest</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo BASE_URL; ?>/users/edit?id=<?php echo $user[
    "user_id"
]; ?>" 
                           class="btn-action btn-edit" title="Edit Data">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/users/delete?id=<?php echo $user[
    "user_id"
]; ?>" 
                           class="btn-action btn-delete" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus user <?php echo htmlspecialchars(
                               $user["username"]
                           ); ?>?')" 
                           title="Hapus Data">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach;
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
        <div class="pagination-info" id="paginationInfo">Menuat data...</div>
        <div class="pagination-btns" id="paginationControls"></div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../layouts/main.php";


?>
