<?php
    // views/akademik/jadwal/edit.php
    ob_start();
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Edit Jadwal Pelajaran</h3>
                    </div>

                    <form action="<?php echo BASE_URL;?>/jadwal/update" method="POST">
                        <input type="hidden" name="jadwal_id" value="<?php echo $data['jadwal']['jadwal_id'];?>">

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kelas_id">Kelas</label>
                                        <select class="form-control" id="kelas_id" name="kelas_id" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            <?php foreach ($data['kelas'] as $kls): ?>
                                                <option value="<?php echo $kls['kelas_id'];?>" <?php echo $kls['kelas_id'] == $data['jadwal']['kelas_id'] ? 'selected' : '';?>>
                                                    <?php echo htmlspecialchars($kls['nama_kelas']);?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hari">Hari</label>
                                        <select class="form-control" id="hari" name="hari" required>
                                            <option value="">-- Pilih Hari --</option>
                                            <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari): ?>
                                                <option value="<?php echo $hari;?>" <?php echo $hari == $data['jadwal']['hari'] ? 'selected' : '';?>>
                                                    <?php echo $hari;?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mapel_id">Mata Pelajaran</label>
                                        <select class="form-control" id="mapel_id" name="mapel_id" required>
                                            <option value="">-- Pilih Mapel --</option>
                                            <?php foreach ($data['mapel'] as $mp): ?>
                                                <option value="<?php echo $mp['mapel_id'];?>" <?php echo $mp['mapel_id'] == $data['jadwal']['mapel_id'] ? 'selected' : '';?>>
                                                    <?php echo htmlspecialchars($mp['nama_mapel']);?> (<?php echo htmlspecialchars($mp['kode_mapel']);?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guru_id">Guru Pengampu</label>
                                        <select class="form-control" id="guru_id" name="guru_id" required>
                                            <option value="">-- Pilih Guru --</option>
                                            <?php foreach ($data['gurus'] as $gr): ?>
                                                <option value="<?php echo $gr['guru_id'];?>" <?php echo $gr['guru_id'] == $data['jadwal']['guru_id'] ? 'selected' : '';?>>
                                                    <?php echo htmlspecialchars($gr['nama_lengkap']);?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_mulai">Jam Mulai</label>
                                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai"
                                               value="<?php echo htmlspecialchars($data['jadwal']['jam_mulai']);?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_selesai">Jam Selesai</label>
                                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai"
                                               value="<?php echo htmlspecialchars($data['jadwal']['jam_selesai']);?>" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">Update</button>
                            <a href="<?php echo BASE_URL;?>/jadwal" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/main.php';
?>
