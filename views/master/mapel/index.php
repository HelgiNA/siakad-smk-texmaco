<?php
// views/master/mapel/index.php
ob_start(); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Mata Pelajaran</h3>
        <div class="card-tools">
            <a href="<?php echo BASE_URL; ?>/mapel/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Mapel
            </a>
        </div>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Kode Mapel</th>
                    <th>Nama Mapel</th>
                    <th>Kelompok</th>
                    <th>KKM</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["mapel"])): ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($data["mapel"] as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars(
                        $row["kode_mapel"]
                    ); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($row["nama_mapel"]); ?></td>
                    <td>
                        <?php
                        $badgeClass = "bg-secondary";
                        if ($row["kelompok"] == "A") {
                            $badgeClass = "bg-primary";
                        } elseif ($row["kelompok"] == "B") {
                            $badgeClass = "bg-success";
                        } elseif (strpos($row["kelompok"], "C") === 0) {
                            $badgeClass = "bg-warning text-dark";
                        }
                        ?>
                        <span
                            class="badge<?php echo $badgeClass; ?>"><?php echo htmlspecialchars(
    $row["kelompok"]
); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($row["kkm"]); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/mapel/edit?id=<?php echo $row[
    "mapel_id"
]; ?>"
                            class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="<?php echo BASE_URL; ?>/mapel/delete?id=<?php echo $row[
    "mapel_id"
]; ?>"
                            class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../../layouts/main.php";

?>
