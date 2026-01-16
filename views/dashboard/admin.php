<style>
    /* =========================================
   DASHBOARD SPECIFIC STYLES (Modern UI)
   ========================================= */

/* 1. DASHBOARD HEADER (Gradient) */
.dashboard-header {
    background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
    color: white;
    border-radius: 15px;
    padding: 30px;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 10px 20px rgba(78, 84, 200, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-header h2 { font-weight: 700; margin-bottom: 5px; font-size: 1.8rem; }
.dashboard-header p { opacity: 0.9; font-size: 1rem; margin-bottom: 5px; }
.dashboard-header small { opacity: 0.7; font-size: 0.85rem; }

/* Lingkaran Dekorasi */
.dashboard-header::after {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    pointer-events: none;
}

/* Icon Besar di Header (Responsive) */
.header-icon-large {
    font-size: 3.5rem;
    opacity: 0.8;
    margin-right: 20px;
    color: rgba(255,255,255,0.9);
}
@media (max-width: 600px) {
    .header-icon-large { display: none; } /* Sembunyikan di HP */
    .dashboard-header { flex-direction: column; text-align: start; align-items: flex-start; }
}

/* 2. STAT CARDS (Menggunakan Grid System dari dashboard.css sebelumnya) */
/* .grid-stats sudah ada di CSS sebelumnya, kita gunakan styling card-nya saja */

.stat-card {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    height: 100%;
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--text-main);
    border: 1px solid rgba(0,0,0,0.02);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px; height: 60px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; margin-right: 20px; flex-shrink: 0;
}

.stat-content h3 { font-size: 28px; font-weight: 700; margin: 0; color: #2c3e50; line-height: 1; }
.stat-content p { margin: 5px 0 0; color: #95a5a6; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

/* Themes Colors */
.theme-blue { background: #e3f2fd; color: #1976d2; }
.theme-green { background: #e8f5e9; color: #2e7d32; }
.theme-orange { background: #fff3e0; color: #f57c00; }
.theme-red { background: #ffebee; color: #c62828; }

/* 3. QUICK ACTIONS GRID */
.section-title {
    margin-top: 30px;
    margin-bottom: 15px;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-light);
}

.grid-actions {
    display: grid;
    /* Grid otomatis: minimal lebar 160px, kalau muat isi sisa ruang */
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
}

.btn-quick {
    background: white;
    border: 1px solid #eee;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    transition: all 0.2s;
    color: var(--text-main);
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    height: 100%;
}

.btn-quick:hover {
    background: #f8f9fa;
    border-color: #ddd;
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.btn-quick i {
    font-size: 28px;
    margin-bottom: 10px;
    color: #4e54c8;
}

</style>

<?php
// Pastikan tidak ada spasi sebelum tag PHP
?>

<div class="dashboard-header">
    <div class="header-content">
        <h2>Halo, <?php echo htmlspecialchars($username ?? "Admin"); ?>! 👋</h2>
        <p>Selamat datang kembali di Panel Administrasi Sekolah.</p>
        <small>Role: <strong><?php echo $role ??
            "User"; ?></strong> | <?php echo date("d F Y"); ?></small>
    </div>
    <div class="header-icon-large">
        <i class="bi bi-shield-check"></i>
    </div>
</div>

<div class="grid-stats">
    <a href="<?php echo BASE_URL; ?>/siswa" class="stat-card">
        <div class="stat-icon theme-blue">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo number_format($total_siswa ?? 0); ?></h3>
            <p>Siswa Aktif</p>
        </div>
    </a>

    <a href="<?php echo BASE_URL; ?>/guru" class="stat-card">
        <div class="stat-icon theme-green">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo number_format($total_guru ?? 0); ?></h3>
            <p>Guru Pengajar</p>
        </div>
    </a>

    <a href="<?php echo BASE_URL; ?>/kelas" class="stat-card">
        <div class="stat-icon theme-orange">
            <i class="bi bi-building-fill"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo number_format($total_kelas ?? 0); ?></h3>
            <p>Total Kelas</p>
        </div>
    </a>

    <a href="<?php echo BASE_URL; ?>/mapel" class="stat-card">
        <div class="stat-icon theme-red">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo number_format($total_mapel ?? 0); ?></h3>
            <p>Mata Pelajaran</p>
        </div>
    </a>
</div>

<h4 class="section-title">Akses Cepat</h4>

<div class="grid-actions">
    <a href="<?php echo BASE_URL; ?>/siswa/create" class="btn-quick">
        <i class="bi bi-person-plus"></i>
        <span>Tambah Siswa</span>
    </a>
    
    <a href="<?php echo BASE_URL; ?>/guru/create" class="btn-quick">
        <i class="bi bi-person-plus-fill"></i>
        <span>Tambah Guru</span>
    </a>
    
    <a href="<?php echo BASE_URL; ?>/jadwal" class="btn-quick">
        <i class="bi bi-calendar-week"></i>
        <span>Jadwal Pelajaran</span>
    </a>
</div>