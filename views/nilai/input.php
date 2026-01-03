<?php $this->layout('layouts::main') ?>

<form action="<?= BASE_URL ?>/nilai/store" method="post">
    <div class="card">
        <div class="card-header">
            <h3>Input Nilai</h3>
        </div>
        <div class="card-body">
            <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">
            <input type="hidden" name="mapel_id" value="<?= $mapel_id ?>">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Tugas</th>
                        <th>UTS</th>
                        <th>UAS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($siswa as $item) : ?>
                        <tr>
                            <td><?= $item['nis'] ?></td>
                            <td><?= $item['nama'] ?></td>
                            <td>
                                <input type="number" class="form-control" name="nilai[<?= $item['id'] ?>][tugas]" value="<?= $nilai[$item['id']]['nilai_tugas'] ?? 0 ?>" min="0" max="100">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="nilai[<?= $item['id'] ?>][uts]" value="<?= $nilai[$item['id']]['nilai_uts'] ?? 0 ?>" min="0" max="100">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="nilai[<?= $item['id'] ?>][uas]" value="<?= $nilai[$item['id']]['nilai_uas'] ?? 0 ?>" min="0" max="100">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
