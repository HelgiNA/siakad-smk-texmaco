<!-- Dashboard Siswa -->

<!-- Profil Siswa -->
<?php if (isset($statistik_absensi) && !empty($statistik_absensi)): ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-fill" style="font-size: 3rem; color: #0d6efd;"></i>
                    </div>
                    <h5 class="card-title"><?php echo $statistik_absensi['nama_siswa'] ?? '-'; ?></h5>
                    <p class="text-muted mb-2">
                        <strong>NIS:</strong> <?php echo $siswa_info['nis'] ?? '-'; ?> <br>
                        <strong>NISN:</strong> <?php echo $siswa_info['nisn'] ?? '-'; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Informasi Kelas & Wali Kelas -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">
                        <i class="bi bi-book"></i> Informasi Kelas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Kelas:</strong><br>
                                <span class="badge bg-warning text-dark" style="font-size: 1rem;">
                                    <?php echo $statistik_absensi['nama_kelas'] ?? 'Belum Ditugaskan'; ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Wali Kelas:</strong><br>
                                <?php echo $statistik_absensi['nama_wali_kelas'] ?? 'Belum Ditugaskan'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Kehadiran -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">
                        <i class="bi bi-graph-up"></i> Statistik Kehadiran
                    </h3>
                </div>
                <div class="card-body">
                    <?php 
                        $hadir = $statistik_absensi['hadir'] ?? 0;
                        $sakit = $statistik_absensi['sakit'] ?? 0;
                        $izin = $statistik_absensi['izin'] ?? 0;
                        $alpa = $statistik_absensi['alpa'] ?? 0;
                        $total = $statistik_absensi['total_pertemuan'] ?? 0;
                        $persentase = $statistik_absensi['persentase_hadir'] ?? 0;
                    ?>
                    
                    <!-- Progress Kehadiran -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Persentase Kehadiran:</strong></span>
                            <span>
                                <strong><?php echo $persentase; ?>%</strong>
                                <?php 
                                    if ($persentase >= 90) {
                                        echo '<span class="badge bg-success">Sangat Baik</span>';
                                    } elseif ($persentase >= 75) {
                                        echo '<span class="badge bg-info">Baik</span>';
                                    } elseif ($persentase >= 60) {
                                        echo '<span class="badge bg-warning">Cukup</span>';
                                    } else {
                                        echo '<span class="badge bg-danger">Kurang</span>';
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persentase; ?>%" aria-valuenow="<?php echo $persentase; ?>" aria-valuemin="0" aria-valuemax="100">
                                <?php echo $persentase; ?>%
                            </div>
                        </div>
                    </div>

                    <!-- Detail Breakdown -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5 class="text-success"><i class="bi bi-check-circle"></i> Hadir</h5>
                                    <h3 class="text-success"><?php echo $hadir; ?></h3>
                                    <small class="text-muted">dari <?php echo $total; ?> pertemuan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h5 class="text-warning"><i class="bi bi-exclamation-circle"></i> Tidak Hadir</h5>
                                    <h3 class="text-warning"><?php echo ($sakit + $izin + $alpa); ?></h3>
                                    <small class="text-muted">(Sakit: <?php echo $sakit; ?>, Izin: <?php echo $izin; ?>, Alpa: <?php echo $alpa; ?>)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        <strong>Informasi tidak lengkap!</strong> Data siswa Anda tidak dapat dimuat. Silakan hubungi administrator.
    </div>
<?php endif; ?>

<!-- Footer Information -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Catatan Penting</h3>
            </div>
            <div class="card-body">
                <p><strong>Selamat Datang, <?php echo $username ?? 'Siswa'; ?>!</strong></p>
                <ul>
                    <li>Monitor kehadiran Anda secara rutin melalui dashboard ini.</li>
                    <li>Kehadiran minimal 75% diperlukan untuk mengikuti ujian akhir.</li>
                    <li>Jika terdapat ketidaksesuaian data, segera lapor kepada Wali Kelas Anda.</li>
                    <li>Untuk informasi nilai dan rapor, silakan akses menu "Akademik" di menu navigasi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
