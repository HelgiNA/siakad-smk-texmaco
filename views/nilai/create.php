<!-- views/nilai/create.php -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Kelas dan Mata Pelajaran</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($data['jadwal'])) : ?>
                        <div class="alert alert-warning">Tidak ada jadwal mengajar untuk Anda di tahun ajaran ini.</div>
                    <?php else : ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['jadwal'] as $jadwal) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($jadwal['nama_kelas']) ?></td>
                                        <td><?= htmlspecialchars($jadwal['nama_mapel']) ?></td>
                                        <td>
                                            <a href="/nilai/input?kelas_id=<?= $jadwal['kelas_id'] ?>&mapel_id=<?= $jadwal['mapel_id'] ?>" class="btn btn-primary btn-sm">Input Nilai</a>
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
</div>
