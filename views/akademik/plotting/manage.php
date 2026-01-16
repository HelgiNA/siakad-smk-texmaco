<?php ob_start(); ?>

<style>
    /* Card Styles */
    .card-plotting {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        background: white;
        overflow: hidden;
    }

    .card-plotting-header {
        padding: 15px 20px;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    /* Warna Header Kiri (Amber) & Kanan (Hijau) */
    .header-source { background: #fff7ed; color: #c2410c; border-bottom-color: #ffedd5; }
    .header-target { background: #ecfdf5; color: #047857; border-bottom-color: #d1fae5; }

    /* Search Box Mini */
    .search-mini {
        width: 100%;
        padding: 8px 12px 8px 35px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.85rem;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 10px center;
        margin-bottom: 10px;
    }
    .search-mini:focus { outline: none; border-color: var(--primary); background-color: white; }

    /* Scrollable Area */
    .scroll-area {
        flex: 1;
        overflow-y: auto;
        max-height: 500px; /* Batas tinggi agar tidak memanjang ke bawah */
        padding: 0;
    }
    
    /* Table Styling */
    .table-plotting { width: 100%; font-size: 0.9rem; border-collapse: collapse; }
    .table-plotting th {
        background: #f8fafc; position: sticky; top: 0; z-index: 10;
        padding: 10px 15px; font-size: 0.8rem; text-transform: uppercase; color: #64748b; font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-plotting td { padding: 10px 15px; border-bottom: 1px solid #f1f5f9; color: var(--text-main); }
    .table-plotting tr:hover td { background: #f8fafc; }

    /* Action Buttons */
    .btn-remove {
        width: 28px; height: 28px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2;
        transition: 0.2s; cursor: pointer;
    }
    .btn-remove:hover { background: #ef4444; color: white; border-color: #ef4444; }

    .btn-submit-add {
        width: 100%; padding: 12px; background: var(--primary); color: white;
        border: none; font-weight: 700; cursor: pointer; transition: 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit-add:disabled { background: #cbd5e1; cursor: not-allowed; }
    .btn-submit-add:not(:disabled):hover { background: var(--primary-dark); }
</style>

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
    <div>
        <a href="<?php echo BASE_URL; ?>/plotting" style="text-decoration: none; color: var(--text-light); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 5px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
        </a>
        <h1 class="page-title" style="margin:0;">Kelola Rombel: <?php echo htmlspecialchars($kelas['nama_kelas']); ?></h1>
        <div style="display: flex; gap: 10px; margin-top: 5px;">
            <span class="badge-custom badge-class"><?php echo htmlspecialchars($kelas['tingkat']); ?></span>
            <span class="badge-custom badge-guest"><?php echo htmlspecialchars($kelas['jurusan']); ?></span>
        </div>
    </div>
</div>

<div class="row" style="display: flex; flex-wrap: wrap; gap: 20px;">
    
    <div class="col-md-6" style="flex: 1; min-width: 300px;">
        <div class="card-plotting">
            <div class="card-plotting-header header-source">
                <span><i class="bi bi-person-lines-fill"></i> Siswa Belum Dapat Kelas</span>
                <span class="badge-custom badge-guest" style="background:white;"><?php echo count($unassigned); ?></span>
            </div>
            
            <div style="padding: 15px 15px 0;">
                <input type="text" id="searchUnassigned" class="search-mini" placeholder="Cari Nama / NIS siswa...">
            </div>

            <form action="<?php echo BASE_URL; ?>/plotting/add" method="POST" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                <input type="hidden" name="kelas_id" value="<?php echo $kelas['kelas_id']; ?>">

                <div class="scroll-area">
                    <?php if (empty($unassigned)): ?>
                        <div style="padding: 40px; text-align: center; color: #94a3b8;">
                            <i class="bi bi-check-circle-fill" style="font-size: 2rem; color: #10b981; margin-bottom: 10px; display: block;"></i>
                            Semua siswa sudah memiliki kelas.
                        </div>
                    <?php else: ?>
                        <table class="table-plotting" id="tableUnassigned">
                            <thead>
                                <tr>
                                    <th width="40" style="text-align: center;">
                                        <input type="checkbox" id="checkAll" style="cursor: pointer;">
                                    </th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unassigned as $s): ?>
                                <tr class="student-row">
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="siswa_id[]" value="<?php echo $s['siswa_id']; ?>" class="siswa-checkbox" style="cursor: pointer;">
                                    </td>
                                    <td class="nis-cell"><?php echo htmlspecialchars($s['nis']); ?></td>
                                    <td class="name-cell" style="font-weight: 600;"><?php echo htmlspecialchars($s['nama_lengkap']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <?php if (!empty($unassigned)): ?>
                    <button type="submit" id="btnAdd" class="btn-submit-add" disabled>
                        <i class="bi bi-box-arrow-in-right"></i> Masukkan Siswa (0)
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-md-6" style="flex: 1; min-width: 300px;">
        <div class="card-plotting">
            <div class="card-plotting-header header-target">
                <span><i class="bi bi-people-fill"></i> Anggota Kelas Saat Ini</span>
                <span class="badge-custom badge-active" style="background:white; color:#047857;"><?php echo count($assigned); ?></span>
            </div>

            <div style="padding: 15px 15px 0;">
                <input type="text" id="searchAssigned" class="search-mini" placeholder="Cari Anggota Kelas...">
            </div>

            <div class="scroll-area">
                <?php if (empty($assigned)): ?>
                    <div style="padding: 40px; text-align: center; color: #94a3b8;">
                        <i class="bi bi-person-dash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        Kelas ini masih kosong.
                    </div>
                <?php else: ?>
                    <table class="table-plotting" id="tableAssigned">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th width="50" style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assigned as $idx => $s): ?>
                            <tr class="student-row">
                                <td><?php echo $idx + 1; ?></td>
                                <td class="nis-cell"><?php echo htmlspecialchars($s['nis']); ?></td>
                                <td class="name-cell" style="font-weight: 600;"><?php echo htmlspecialchars($s['nama_lengkap']); ?></td>
                                <td style="text-align: center;">
                                    <form action="<?php echo BASE_URL; ?>/plotting/remove" method="POST" onsubmit="return confirm('Keluarkan siswa <?php echo htmlspecialchars($s['nama_lengkap']); ?> dari kelas ini?');" style="margin:0;">
                                        <input type="hidden" name="kelas_id" value="<?php echo $kelas['kelas_id']; ?>">
                                        <input type="hidden" name="siswa_id" value="<?php echo $s['siswa_id']; ?>">
                                        <button type="submit" class="btn-remove" title="Keluarkan dari kelas">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LOGIC CHECKBOX & BUTTON STATE ---
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.siswa-checkbox');
    const btnAdd = document.getElementById('btnAdd');

    function updateButton() {
        if (!btnAdd) return;
        const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
        btnAdd.disabled = checkedCount === 0;
        
        if (checkedCount === 0) {
            btnAdd.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Masukkan Siswa (0)';
            btnAdd.style.background = '#cbd5e1'; // Abu-abu
        } else {
            btnAdd.innerHTML = `<i class="bi bi-check-circle-fill"></i> Masukkan ${checkedCount} Siswa Terpilih`;
            btnAdd.style.background = 'var(--primary)'; // Biru
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            // Hanya centang yang visible (hasil filter search)
            const visibleRows = document.querySelectorAll('#tableUnassigned tbody tr:not([style*="display: none"]) .siswa-checkbox');
            visibleRows.forEach(cb => cb.checked = this.checked);
            updateButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateButton);
    });

    // --- 2. LOGIC CLIENT-SIDE SEARCH (Filter Cepat) ---
    function filterTable(inputId, tableId) {
        const input = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        if (!input || !table) return;

        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const nis = row.querySelector('.nis-cell').innerText.toLowerCase();
                const name = row.querySelector('.name-cell').innerText.toLowerCase();
                
                if (nis.includes(filter) || name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Uncheck hidden rows agar tidak ikut terkirim
                    const cb = row.querySelector('.siswa-checkbox');
                    if (cb) cb.checked = false;
                }
            });
            updateButton(); // Update counter button jika ada yang uncheck otomatis
        });
    }

    filterTable('searchUnassigned', 'tableUnassigned');
    filterTable('searchAssigned', 'tableAssigned');

});
</script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
