<?php
/**
 * View: Profil Siswa
 * Menampilkan: Biodata, Statistik Kehadiran, Daftar Nilai
 */

$layout = 'main';
include BASE_PATH . '/views/layouts/' . $layout . '.php';
?>

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Page Title -->
        <div id="kt_app_page_title" class="app-page-title px-15 py-4">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?php echo BASE_URL; ?>/dashboard" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Profil Saya</li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- ROW 1: Kartu Identitas Siswa (Atas) -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-header bg-primary text-white p-3 rounded-top">
                                <h3 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-card-text me-2"></i> Identitas Siswa
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <!-- Kolom Kiri: Foto & Nama -->
                                    <div class="col-md-3 text-center mb-3 mb-md-0">
                                        <div class="mb-3">
                                            <i class="bi bi-person-fill text-primary" style="font-size: 4rem;"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark"><?php echo htmlspecialchars($profileData['nama_lengkap'] ?? '-'); ?></h5>
                                        <p class="text-muted small">NIS: <?php echo htmlspecialchars($profileData['nis'] ?? '-'); ?></p>
                                    </div>

                                    <!-- Kolom Tengah & Kanan: Data Diri -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold">NIS</label>
                                                    <p class="text-dark fw-semibold"><?php echo htmlspecialchars($profileData['nis'] ?? '-'); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold">NISN</label>
                                                    <p class="text-dark fw-semibold"><?php echo htmlspecialchars($profileData['nisn'] ?? '-'); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold">Tanggal Lahir</label>
                                                    <p class="text-dark fw-semibold">
                                                        <?php 
                                                            if (!empty($profileData['tanggal_lahir'])) {
                                                                echo date('d/m/Y', strtotime($profileData['tanggal_lahir']));
                                                            } else {
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold">Kelas</label>
                                                    <p class="text-dark fw-semibold">
                                                        <span class="badge bg-warning text-dark fs-7">
                                                            <?php echo htmlspecialchars($profileData['nama_kelas'] ?? 'Belum Ditugaskan'); ?>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold">Jurusan</label>
                                                    <p class="text-dark fw-semibold"><?php echo htmlspecialchars($profileData['jurusan'] ?? '-'); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold">Tahun Ajaran</label>
                                                    <p class="text-dark fw-semibold">
                                                        <?php echo htmlspecialchars($profileData['tahun'] ?? '-'); ?> 
                                                        (<?php echo htmlspecialchars($profileData['semester'] ?? '-'); ?>)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Alamat -->
                                        <div class="row mt-3 border-top pt-3">
                                            <div class="col-md-12">
                                                <label class="form-label text-muted small fw-bold">Alamat</label>
                                                <p class="text-dark"><?php echo htmlspecialchars($profileData['alamat'] ?? '-'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 2: Statistik Kehadiran (Kiri) & Daftar Nilai (Kanan) -->
                <div class="row">
                    <!-- Kolom Kiri: Statistik Kehadiran -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-lg h-100">
                            <div class="card-header bg-success text-white p-3 rounded-top">
                                <h3 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-graph-up me-2"></i> Statistik Kehadiran
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <?php
                                    $hadir = $rekapAbsensi['hadir'] ?? 0;
                                    $sakit = $rekapAbsensi['sakit'] ?? 0;
                                    $izin = $rekapAbsensi['izin'] ?? 0;
                                    $alpa = $rekapAbsensi['alpa'] ?? 0;
                                    $total = $rekapAbsensi['total_pertemuan'] ?? 0;
                                    $persentase = $rekapAbsensi['persentase_hadir'] ?? 0;
                                ?>

                                <!-- Progress Kehadiran -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-dark">Persentase Kehadiran</span>
                                        <span class="fw-bold fs-5 text-success"><?php echo $persentase; ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 28px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?php echo $persentase; ?>%" 
                                             aria-valuenow="<?php echo $persentase; ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <span class="fw-bold text-white"><?php echo $persentase; ?>%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Breakdown Statistik -->
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted fw-bold">
                                                    <i class="bi bi-check-circle text-success me-2"></i> Hadir
                                                </td>
                                                <td class="text-end fw-bold text-dark"><?php echo $hadir; ?> hari</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-bold">
                                                    <i class="bi bi-hospital text-warning me-2"></i> Sakit
                                                </td>
                                                <td class="text-end fw-bold text-dark"><?php echo $sakit; ?> hari</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-bold">
                                                    <i class="bi bi-file-earmark text-info me-2"></i> Izin
                                                </td>
                                                <td class="text-end fw-bold text-dark"><?php echo $izin; ?> hari</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-bold">
                                                    <i class="bi bi-x-circle text-danger me-2"></i> Alpa
                                                </td>
                                                <td class="text-end fw-bold text-dark"><?php echo $alpa; ?> hari</td>
                                            </tr>
                                            <tr class="border-top">
                                                <td class="text-muted fw-bold">Total Pertemuan</td>
                                                <td class="text-end fw-bold text-dark"><?php echo $total; ?> hari</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Status Badge -->
                                <div class="mt-4">
                                    <?php 
                                        if ($persentase >= 90) {
                                            $badgeClass = 'bg-success';
                                            $status = 'Sangat Baik';
                                        } elseif ($persentase >= 75) {
                                            $badgeClass = 'bg-info';
                                            $status = 'Baik';
                                        } elseif ($persentase >= 60) {
                                            $badgeClass = 'bg-warning';
                                            $status = 'Cukup';
                                        } else {
                                            $badgeClass = 'bg-danger';
                                            $status = 'Kurang';
                                        }
                                    ?>
                                    <div class="alert alert-light-<?php echo str_replace('bg-', '', $badgeClass); ?> border-0 p-3 text-center">
                                        <span class="badge <?php echo $badgeClass; ?> fw-bold fs-7">
                                            Status: <?php echo $status; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Daftar Nilai (KHS) -->
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-header bg-info text-white p-3 rounded-top">
                                <h3 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-book me-2"></i> Daftar Nilai (KHS)
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($listNilai)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-muted fw-bold px-4 py-3">Mata Pelajaran</th>
                                                    <th class="text-muted fw-bold text-center py-3">Tugas</th>
                                                    <th class="text-muted fw-bold text-center py-3">UTS</th>
                                                    <th class="text-muted fw-bold text-center py-3">UAS</th>
                                                    <th class="text-muted fw-bold text-center py-3">Akhir</th>
                                                    <th class="text-muted fw-bold text-center py-3">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($listNilai as $nilai): ?>
                                                    <tr>
                                                        <td class="px-4 py-3">
                                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($nilai['nama_mapel']); ?></div>
                                                            <div class="text-muted small">
                                                                Kelompok: 
                                                                <span class="badge bg-light-secondary text-secondary fw-bold">
                                                                    <?php echo htmlspecialchars($nilai['kelompok']); ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <?php if ($nilai['is_draft']): ?>
                                                                <span class="badge bg-light-warning text-warning fw-bold">-</span>
                                                            <?php else: ?>
                                                                <span class="fw-bold text-dark"><?php echo number_format($nilai['nilai_tugas'], 2); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <?php if ($nilai['is_draft']): ?>
                                                                <span class="badge bg-light-warning text-warning fw-bold">-</span>
                                                            <?php else: ?>
                                                                <span class="fw-bold text-dark"><?php echo number_format($nilai['nilai_uts'], 2); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <?php if ($nilai['is_draft']): ?>
                                                                <span class="badge bg-light-warning text-warning fw-bold">-</span>
                                                            <?php else: ?>
                                                                <span class="fw-bold text-dark"><?php echo number_format($nilai['nilai_uas'], 2); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <?php if ($nilai['is_draft']): ?>
                                                                <span class="badge bg-light-warning text-warning fw-bold">-</span>
                                                            <?php else: ?>
                                                                <span class="fw-bold text-dark"><?php echo number_format($nilai['nilai_akhir'], 2); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <?php if ($nilai['is_draft']): ?>
                                                                <span class="badge bg-light-warning">
                                                                    <i class="bi bi-clock"></i> Belum Rilis
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-light-success text-success fw-bold">
                                                                    <i class="bi bi-check-circle"></i> Rilis
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-light-info border-0 m-4">
                                        <div class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-3 fw-semibold">Belum ada data nilai</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="alert alert-light-info border-0 mt-4 p-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-info me-2" style="font-size: 1.3rem;"></i>
                                <div>
                                    <strong class="text-info">Catatan Penting:</strong>
                                    <ul class="mb-0 ms-3 mt-2">
                                        <li>Nilai yang menampilkan <span class="badge bg-light-warning text-warning fw-bold ms-1">-</span> masih dalam proses validasi oleh Wali Kelas.</li>
                                        <li>Nilai akan ditampilkan setelah divalidasi dan dirilis oleh Wali Kelas.</li>
                                        <li>Hubungi Wali Kelas jika ada pertanyaan terkait nilai Anda.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
