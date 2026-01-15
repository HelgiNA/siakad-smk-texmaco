<!-- Dashboard Guru -->

<!-- Notifikasi Validasi Absensi (jika wali kelas) -->
<?php if (isset($is_wali_kelas) && $is_wali_kelas && isset($validasi_pending)): ?>
    <?php if ($validasi_pending > 0): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-triangle"></i> Perhatian!</strong>
            Anda memiliki <strong><?php echo $validasi_pending; ?></strong> absensi menunggu validasi sebagai Wali Kelas dari <strong><?php echo $kelas_wali['nama_kelas']; ?></strong>
            <a href="<?php echo BASE_URL; ?>/absensi/validasi" class="alert-link ms-2">Lihat Detail</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php else: ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-check-circle"></i> Baik!</strong>
            Semua absensi kelas <strong><?php echo $kelas_wali['nama_kelas']; ?></strong> sudah tervalidasi.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Jadwal Mengajar Hari Ini -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="bi bi-calendar-event"></i> Jadwal Mengajar Hari Ini
                </h3>
                <div class="card-tools">
                    <span class="text-white"><small><?php echo date('l, d F Y', strtotime(date('Y-m-d'))); ?></small></span>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($jadwal_hari_ini) && count($jadwal_hari_ini) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jadwal_hari_ini as $jadwal): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo date('H:i', strtotime($jadwal['jam_mulai'])); ?></strong>
                                            -
                                            <?php echo date('H:i', strtotime($jadwal['jam_selesai'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $jadwal['nama_kelas']; ?></span>
                                        </td>
                                        <td>
                                            <?php echo $jadwal['nama_mapel']; ?>
                                            <br>
                                            <small class="text-muted"><?php echo $jadwal['kode_mapel']; ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/absensi/create/<?php echo $jadwal['jadwal_id']; ?>" class="btn btn-sm btn-primary" title="Input Absensi">
                                                <i class="bi bi-plus-circle"></i> Input Absensi
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Tidak ada jadwal mengajar hari ini.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
                <p>Selamat Datang, <b><?php echo $username ?? 'Guru'; ?></b>!</p>
                <p>Dashboard ini menampilkan jadwal mengajar Anda hari ini. Gunakan tombol "Input Absensi" untuk mencatat kehadiran siswa.</p>
                <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
                    <p>Anda juga bertindak sebagai Wali Kelas dari <strong><?php echo $kelas_wali['nama_kelas']; ?></strong>. Pastikan untuk memvalidasi absensi siswa yang masuk dalam tanggung jawab Anda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
