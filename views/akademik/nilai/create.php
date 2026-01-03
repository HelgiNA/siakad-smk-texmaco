<?php
/**
 * FASE 1: Input Nilai - Pilih Kelas & Mapel
 * 
 * Guru login → klik "Input Nilai" → pilih kelas & mapel yang diajarnya
 * 
 * Views/Flow:
 * 1. Tampilkan list kelas & mapel yang diajar guru
 * 2. Guru klik salah satu → go to input form (Fase 2)
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $title; ?></h3>
                </div>
                
                <div class="card-body">
                    <?php if (empty($kelasMapelList)): ?>
                        <div class="alert alert-info">
                            Anda tidak memiliki jadwal mengajar di tahun ajaran ini.
                        </div>
                        <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Kembali</a>
                    <?php else: ?>
                        <p class="text-muted">Pilih Kelas dan Mapel yang akan Anda input nilainya:</p>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($kelasMapelList as $item): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><strong><?php echo htmlspecialchars($item['nama_kelas']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($item['nama_mapel']); ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>/nilai/input?kelas_id=<?php echo $item['kelas_id']; ?>&mapel_id=<?php echo $item['mapel_id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Input Nilai
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/components/flash.php'; ?>
