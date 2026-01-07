<?php
// Standalone print view (tidak memanggil layout utama)
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo APP_NAME . ' - Rapor ' . ($biodata['nama_lengkap'] ?? ''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: Arial, Helvetica, sans-serif; color:#000; }
        .kop { text-align: center; margin-bottom: 10px }
        .biodata, .nilai, .absensi { width:100%; margin-bottom: 12px }
        table { width:100%; border-collapse: collapse }
        th, td { border:1px solid #000; padding:6px; font-size:12px }
        .no-border { border: none }
        .small { font-size:11px }
        .ttd { width:50%; display:inline-block; text-align:center; margin-top:40px }
    </style>
</head>
<body>
    <div class="kop">
        <h3><?php echo APP_NAME; ?></h3>
        <div><?php echo 'Tahun Ajaran: ' . ($activeTahun['tahun'] ?? ''); ?></div>
        <hr />
    </div>

    <h4>Rapor Siswa</h4>
    <table class="biodata no-border">
        <tr class="no-border">
            <td class="no-border" style="width:25%"><strong>Nama</strong></td>
            <td class="no-border" style="width:75%">: <?php echo htmlspecialchars($biodata['nama_lengkap'] ?? ''); ?></td>
        </tr>
        <tr class="no-border">
            <td class="no-border"><strong>NIS / NISN</strong></td>
            <td class="no-border">: <?php echo htmlspecialchars($biodata['nis'] ?? '') . ' / ' . htmlspecialchars($biodata['nisn'] ?? ''); ?></td>
        </tr>
        <tr class="no-border">
            <td class="no-border"><strong>Kelas</strong></td>
            <td class="no-border">: <?php echo htmlspecialchars($biodata['nama_kelas'] ?? ''); ?></td>
        </tr>
    </table>

    <?php foreach (['A','B','C'] as $g): ?>
        <h5>Kelompok <?php echo $g; ?></h5>
        <table class="nilai">
            <thead>
                <tr>
                    <th style="width:8%">#</th>
                    <th>Mata Pelajaran</th>
                    <th style="width:12%">KKM</th>
                    <th style="width:12%">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($nilai[$g])): ?>
                <tr>
                    <td colspan="4" class="small">Tidak ada data.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($nilai[$g] as $idx => $row): ?>
                <tr>
                    <td><?php echo $idx + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_mapel']); ?></td>
                    <td><?php echo htmlspecialchars($row['kkm']); ?></td>
                    <td><?php echo htmlspecialchars($row['nilai_akhir']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endforeach; ?>

    <h5>Rekap Kehadiran</h5>
    <table class="absensi">
        <thead>
            <tr>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $absensi['Hadir'] ?? 0; ?></td>
                <td><?php echo $absensi['Sakit'] ?? 0; ?></td>
                <td><?php echo $absensi['Izin'] ?? 0; ?></td>
                <td><?php echo $absensi['Alpa'] ?? 0; ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top:30px">
        <div class="ttd">
            <div>Wali Kelas</div>
            <br><br>
            <div>______________________</div>
        </div>
        <div class="ttd" style="float:right">
            <div>Kepala Sekolah</div>
            <br><br>
            <div>______________________</div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    </script>
</body>
</html>
