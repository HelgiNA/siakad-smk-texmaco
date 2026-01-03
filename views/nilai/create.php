<?php $this->layout('layouts::main') ?>

<div class="card">
    <div class="card-header">
        <h3>Pilih Kelas & Mata Pelajaran</h3>
    </div>
    <div class="card-body">
        <?php $this->insert('components::flash') ?>
        <form action="<?= BASE_URL ?>/nilai/input" method="get">
            <div class="form-group">
                <label for="">Pilih Jadwal</label>
                <select name="jadwal" id="" class="form-control" required>
                    <option value="">-- Pilih Jadwal --</option>
                    <?php foreach ($jadwal as $item) : ?>
                        <option value="<?= $item['kelas_id'] ?>-<?= $item['mapel_id'] ?>">
                            Kelas: <?= $item['nama_kelas'] ?> (Mapel: <?= $item['nama_mapel'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lanjut</button>
        </form>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const selected = document.querySelector('select[name=jadwal]').value.split('-');
        if (selected[0] && selected[1]) {
            window.location.href = `<?= BASE_URL ?>/nilai/input?kelas_id=${selected[0]}&mapel_id=${selected[1]}`;
        }
    });
</script>
