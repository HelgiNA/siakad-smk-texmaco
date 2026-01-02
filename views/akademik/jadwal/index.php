<?php
    ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Jadwal Pelajaran</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/jadwal/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </a>
        </div>
    </div>

    <div class="card-body">
        <?php if (! $data['tahun_aktif']): ?>
        <div class="alert alert-warning">
            Tidak ada Tahun Ajaran Aktif. Silakan aktifkan Tahun Ajaran terlebih dahulu di Master Data.
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            Tahun Ajaran Aktif: <strong><?php echo $data['tahun_aktif']['tahun']; ?> -
                <?php echo $data['tahun_aktif']['semester']; ?></strong>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-nowrap">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th style="width: 100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['jadwal'])): ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada jadwal pelajaran.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($data['jadwal'] as $index => $row): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <?php
                                // Badge warna hari
                                $badges = [
                                    'Senin' => 'bg-primary', 'Selasa' => 'bg-info', 'Rabu'    => 'bg-success',
                                    'Kamis' => 'bg-warning', 'Jumat'  => 'bg-danger', 'Sabtu' => 'bg-secondary',
                                ];
                                $bg = $badges[$row['hari']] ?? 'bg-secondary';
                            ?>
                            <span class="badge<?php echo $bg; ?>"><?php echo htmlspecialchars($row['hari']); ?></span>
                        </td>
                        <td><?php echo substr($row['jam_mulai'], 0, 5); ?> -
                            <?php echo substr($row['jam_selesai'], 0, 5); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_kelas']); ?></strong></td>
                        <td>
                            <?php echo htmlspecialchars($row['nama_mapel']); ?> <br>
                            <small class="text-muted"><?php echo htmlspecialchars($row['kode_mapel']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['nama_guru']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL ?>/jadwal/edit?id=<?php echo $row['jadwal_id'] ?>"
                                class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?php echo BASE_URL ?>/jadwal/delete?id=<?php echo $row['jadwal_id'] ?>"
                                class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal ini?')"
                                title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
    $content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>