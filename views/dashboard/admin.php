<!-- Dashboard Admin / Kepsek - Statistik Global Sekolah -->
<div class="row">
    <!-- Total Siswa -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo $total_siswa ?? 0; ?></h3>
                <p>Total Siswa Aktif</p>
            </div>
            <div class="icon">
                <i class="bi bi-people"></i>
            </div>
            <a href="<?php echo BASE_URL; ?>/siswa" class="small-box-footer">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Guru -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo $total_guru ?? 0; ?></h3>
                <p>Total Guru Aktif</p>
            </div>
            <div class="icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <a href="<?php echo BASE_URL; ?>/guru" class="small-box-footer">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Kelas -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo $total_kelas ?? 0; ?></h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon">
                <i class="bi bi-book"></i>
            </div>
            <a href="<?php echo BASE_URL; ?>/kelas" class="small-box-footer">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Mapel -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo $total_mapel ?? 0; ?></h3>
                <p>Total Mata Pelajaran</p>
            </div>
            <div class="icon">
                <i class="bi bi-journal-text"></i>
            </div>
            <a href="<?php echo BASE_URL; ?>/mapel" class="small-box-footer">
                Lihat Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang</h3>
            </div>
            <div class="card-body">
                <p>Selamat Datang, <b><?php echo $username ?? 'Admin'; ?></b>!</p>
                <p>Anda login sebagai <span class="badge bg-primary"><?php echo $role; ?></span></p>
                <p>Dashboard ini menampilkan ringkasan statistik global sekolah. Gunakan menu navigasi untuk mengelola data siswa, guru, kelas, jadwal, dan informasi akademik lainnya.</p>
            </div>
        </div>
    </div>
</div>
