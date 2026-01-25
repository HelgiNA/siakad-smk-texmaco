<?php
// Ambil role dari session
$role = $_SESSION['role'] ?? 'Guest';

/**
 * Helper function untuk mendapatkan URI saat ini
 * Menggunakan string casting (string) untuk memastikan tidak pernah NULL
 */
function getCurrentUri() {
    return isset($_SERVER['REQUEST_URI']) ? (string)$_SERVER['REQUEST_URI'] : '/';
}

/**
 * Cek apakah menu aktif
 */
if (!function_exists('isActive')) {
    function isActive($path) {
        $uri = getCurrentUri();
        // Cek strpos hanya jika $uri tidak kosong
        return strpos($uri, $path) !== false ? 'active' : '';
    }
}

/**
 * Cek apakah parent menu (accordion) harus terbuka
 */
if (!function_exists('isOpen')) {
    function isOpen($paths) {
        $uri = getCurrentUri();
        // Pastikan paths berupa array
        if (!is_array($paths)) {
            $paths = [$paths];
        }
        
        foreach($paths as $path) {
            if (strpos($uri, $path) !== false) return 'open';
        }
        return '';
    }
}
?>

<aside class="sidebar">
    <a href="<?php echo BASE_URL; ?>/dashboard" class="sidebar-brand">
        SIAKAD <span style="color: var(--primary); margin-left:5px;">APP</span>
    </a>

    <div style="height: calc(100vh - 64px); overflow-y: auto; padding-bottom: 20px;">
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/dashboard" class="nav-link <?php echo isActive(
    "/dashboard"
); ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Dashboard
                </a>
            </li>

            <?php if ($role === "Admin"): ?>
                <li class="nav-header">MASTER DATA</li>

                <details class="nav-accordion" <?php echo isOpen([
                    "/users",
                    "/profil",
                    "/siswa",
                    "/guru"
                ]); ?>>
                    <summary>
                        <div style="display:flex;align-items:center;">
                            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Manajemen User
                        </div>
                    </summary>
                    <div class="nav-submenu">
                        <a href="<?php echo BASE_URL; ?>/users" class="nav-link <?php echo isActive(
    "/users"
); ?>">Data Akun</a>
                        <a href="<?php echo BASE_URL; ?>/siswa" class="nav-link <?php echo isActive(
    "/siswa"
); ?>">Data Siswa</a>
                        <a href="<?php echo BASE_URL; ?>/guru" class="nav-link <?php echo isActive(
    "/guru"
); ?>">Data Guru</a>
                    </div>
                </details>

                <details class="nav-accordion" <?php echo isOpen([
                    "/tahun-ajaran",
                    "/kelas",
                    "/mapel",
                ]); ?>>
                    <summary>
                        <div style="display:flex;align-items:center;">
                            <svg class="nav-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            Data Sekolah
                        </div>
                    </summary>
                    <div class="nav-submenu">
                        <a href="<?php echo BASE_URL; ?>/tahun-ajaran" class="nav-link <?php echo isActive(
    "/tahun-ajaran"
); ?>">Tahun Ajaran</a>
                        <a href="<?php echo BASE_URL; ?>/kelas" class="nav-link <?php echo isActive(
    "/kelas"
); ?>">Data Kelas</a>
                        <a href="<?php echo BASE_URL; ?>/mapel" class="nav-link <?php echo isActive(
    "/mapel"
); ?>">Mata Pelajaran</a>
                    </div>
                </details>

                <details class="nav-accordion" <?php echo isOpen([
                    "/jadwal",
                    "/plotting",
                ]); ?>>
                    <summary>
                        <div style="display:flex;align-items:center;">
                            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Akademik
                        </div>
                    </summary>
                    <div class="nav-submenu">
                        <a href="<?php echo BASE_URL; ?>/jadwal" class="nav-link <?php echo isActive(
    "/jadwal"
); ?>">Jadwal Pelajaran</a>
                        <a href="<?php echo BASE_URL; ?>/plotting" class="nav-link <?php echo isActive(
    "/plotting"
); ?>">Plotting Siswa</a>
                    </div>
                </details>
            <?php endif; ?>

            <?php if ($role === "Guru"): ?>
                <li class="nav-header">GURU & AKADEMIK</li>

                <details class="nav-accordion" <?php echo isOpen([
                    "/absensi",
                ]); ?>>
                    <summary>
                        <div style="display:flex;align-items:center;">
                            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Absensi
                        </div>
                    </summary>
                    <div class="nav-submenu">
                        <a href="<?php echo BASE_URL; ?>/absensi" class="nav-link <?php echo isActive(
    "/absensi"
); ?>">Input Absensi</a>
                        <a href="<?php echo BASE_URL; ?>/absensi/validasi" class="nav-link <?php echo isActive(
    "/absensi/validasi"
); ?>">Validasi (Wali)</a>
                    </div>
                </details>

                <details class="nav-accordion" <?php echo isOpen([
                    "/nilai",
                ]); ?>>
                    <summary>
                        <div style="display:flex;align-items:center;">
                            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            Penilaian
                        </div>
                    </summary>
                    <div class="nav-submenu">
                        <a href="<?php echo BASE_URL; ?>/nilai" class="nav-link <?php echo isActive(
    "/nilai"
) && !isActive("/nilai/validasi"); ?>">Input Nilai Mapel</a>
                        <a href="<?php echo BASE_URL; ?>/nilai/validasi" class="nav-link <?php echo isActive(
    "/nilai/validasi"
); ?>">Validasi Nilai (Wali)</a>
                    </div>
                </details>

                <details class="nav-accordion" <?php echo isOpen([
                    "/rapor",
                ]); ?>>
                    <summary>
                        <div style="display:flex;align-items:center;">
                            <svg class="nav-icon" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                            E-Rapor
                        </div>
                    </summary>
                    <div class="nav-submenu">
                        <a href="<?php echo BASE_URL; ?>/rapor/catatan" class="nav-link <?php echo isActive(
    "/rapor/catatan"
); ?>">Catatan Wali Kelas</a>
                    </div>
                </details>
            <?php endif; ?>

            <?php if ($role === "Siswa"): ?>
                <li class="nav-header">SISWA</li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/profil" class="nav-link <?php echo isActive(
    "/profil"
); ?>">
                        <svg class="nav-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Profil Saya
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top:10px;">
                <a href="<?php echo BASE_URL; ?>/logout" class="nav-link" style="color: #f87171;">
                    <svg class="nav-icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Keluar Sistem
                </a>
            </li>

        </ul>
    </div>
</aside>
