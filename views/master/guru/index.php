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
            <style>
                /* Badge Kepsek (Ungu Wibawa) */
                .badge-kepsek { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
                /* Badge Guru (Oranye Standar) */
                .badge-guru   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
            </style>

            <tbody id="tableBody">
                <?php foreach ($data["gurus"] as $index => $guru): 
                // Logika Cek Apakah Role adalah Kepsek
                // Pastikan field di database sesuai, misal 'role' atau 'jabatan'
                $isKepsek = (isset($guru["role"]) && $guru["role"] === "Kepsek");

                // Visual Tweak: Avatar Kepsek warna Ungu, Guru warna Oranye
                $avatarBg = $isKepsek ? '#e0e7ff' : '#fff7ed';
                $avatarCol = $isKepsek ? '#4338ca' : '#ea580c';
                ?>
                <tr class="data-row" style="<?php echo $isKepsek ? 'background-color: #f8fafc;' : ''; ?>">
                    <td><?php echo $index + 1; ?></td>

                    <td class="searchable-nip">
                        <span style="font-weight: 600; color: var(--text-main); font-family: monospace;">
                            <?php echo !empty($guru["nip"]) ? htmlspecialchars($guru["nip"]) : "-"; ?>
                        </span>
                    </td>

                    <td class="searchable-name">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:34px; height:34px; background:<?php echo $avatarBg; ?>; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:bold; color:<?php echo $avatarCol; ?>;">
                                <?php echo strtoupper(substr($guru["nama_lengkap"], 0, 1)); ?>
                            </div>

                            <div>
                                <span style="font-weight: 600; color: #334155;">
                                    <?php echo htmlspecialchars($guru["nama_lengkap"]); ?>
                                </span>
                                <?php if ($isKepsek): ?>
                                <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 0.8rem;" title="Kepala Sekolah"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

                    <td>
                        <?php if ($isKepsek): ?>
                        <span class="badge-custom badge-kepsek">
                            <i class="bi bi-star-fill"></i> Kepsek
                        </span>
                        <?php else: ?>
                        <span class="badge-custom badge-guru">
                            <i class="bi bi-person-badge"></i> Guru
                        </span>
                        <?php endif; ?>
                    </td>

                    <td style="text-align: center;">
                        <a href="<?php echo BASE_URL; ?>/guru/edit?id=<?php echo $guru["guru_id"]; ?>" 
                           class="btn-action btn-edit" title="Edit Data">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/guru/delete?id=<?php echo $guru["guru_id"]; ?>" 
                           class="btn-action btn-delete" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data <?php echo $isKepsek ? 'Kepala Sekolah' : 'Guru'; ?> ini?')" 
                           title="Hapus Data">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
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
