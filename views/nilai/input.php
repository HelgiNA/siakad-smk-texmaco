<!-- views/nilai/input.php -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Nilai untuk <?= htmlspecialchars($data['mapel']['nama_mapel']) ?> - Kelas <?= htmlspecialchars($data['kelas']['nama_kelas']) ?></h3>
                    <div class="card-tools">
                        <a href="/nilai/create" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($data['siswa'])) : ?>
                        <div class="alert alert-warning">Tidak ada siswa di kelas ini.</div>
                    <?php else : ?>
                        <form action="/nilai/store" method="POST">
                            <input type="hidden" name="kelas_id" value="<?= $data['kelas_id'] ?>">
                            <input type="hidden" name="mapel_id" value="<?= $data['mapel_id'] ?>">
                            <input type="hidden" name="tahun_id" value="<?= $_SESSION['tahun_ajaran_aktif']['tahun_id'] ?>">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="2" class="align-middle">No</th>
                                            <th rowspan="2" class="align-middle">NIS</th>
                                            <th rowspan="2" class="align-middle">Nama Siswa</th>
                                            <th colspan="3">Nilai</th>
                                        </tr>
                                        <tr>
                                            <th>Tugas (20%)</th>
                                            <th>UTS (30%)</th>
                                            <th>UAS (50%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($data['siswa'] as $siswa) : ?>
                                            <?php $nilaiSiswa = $data['nilai'][$siswa['id']] ?? null; ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($siswa['nis']) ?></td>
                                                <td><?= htmlspecialchars($siswa['nama_lengkap']) ?></td>
                                                <input type="hidden" name="nilai[<?= $siswa['id'] ?>][siswa_id]" value="<?= $siswa['id'] ?>">
                                                <td>
                                                    <input type="number" name="nilai[<?= $siswa['id'] ?>][tugas]" class="form-control" value="<?= htmlspecialchars($nilaiSiswa['nilai_tugas'] ?? '') ?>" min="0" max="100">
                                                </td>
                                                <td>
                                                    <input type="number" name="nilai[<?= $siswa['id'] ?>][uts]" class="form-control" value="<?= htmlspecialchars($nilaiSiswa['nilai_uts'] ?? '') ?>" min="0" max="100">
                                                </td>
                                                <td>
                                                    <input type="number" name="nilai[<?= $siswa['id'] ?>][uas]" class="form-control" value="<?= htmlspecialchars($nilaiSiswa['nilai_uas'] ?? '') ?>" min="0" max="100">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
