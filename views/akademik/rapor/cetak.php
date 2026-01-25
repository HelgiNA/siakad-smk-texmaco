<?php
// Helper Logika Predikat (Bisa disesuaikan dengan aturan sekolah)
function getPredikat($nilai, $kkm) {
    if ($nilai >= 92) return "A (Sangat Baik)";
    if ($nilai >= 83) return "B (Baik)";
    if ($nilai >= $kkm) return "C (Cukup)";
    return "D (Kurang)";
}

// Format Tanggal Indonesia
$tanggalCetak = date("d F Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor_<?php echo htmlspecialchars($biodata["nis"]); ?>_<?php echo htmlspecialchars($biodata["nama_lengkap"]); ?></title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" rel="stylesheet">
    
    <style>
        /* SETUP HALAMAN A4 */
        @page {
            size: A4;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm; /* Atas Kanan Bawah Kiri */
        }

        body {
            font-family: 'Times New Roman', Times, serif; /* Font Resmi */
            font-size: 12pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        /* UTILITIES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-4 { margin-top: 20px; }
        
        /* KOP SURAT */
        .header-kop {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda tebal */
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header-kop td { vertical-align: middle; }
        .school-name { font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .school-address { font-size: 10pt; margin: 2px 0; }
        
        /* INFO SISWA */
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        
        /* TABEL NILAI */
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grade-table th, .grade-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        .grade-table th {
            background-color: #f0f0f0 !important; /* Abu muda */
            -webkit-print-color-adjust: exact;
            text-align: center;
            font-weight: bold;
        }
        .group-header {
            background-color: #f9f9f9 !important;
            -webkit-print-color-adjust: exact;
            font-weight: bold;
            font-style: italic;
        }

        /* CATATAN & ABSENSI */
        .notes-container {
            display: table;
            width: 100%;
            margin-top: 10px;
            border-spacing: 0 10px; /* Jarak antar baris layout tabel */
        }
        .note-box {
            border: 1px solid #000;
            padding: 8px;
            height: 100%;
        }

        /* TANDA TANGAN */
        .signature-section {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid; /* Jangan terpotong halaman */
        }
        .signature-table { width: 100%; }
        .signature-table td { 
            text-align: center; 
            vertical-align: top;
            width: 33.33%;
        }
        .signature-space { height: 80px; }

        /* BUTTONS (Hanya di Layar) */
        @media screen {
            body { background: #f3f4f6; padding: 20px; }
            .paper {
                background: white; width: 210mm; min-height: 297mm;
                padding: 1.5cm; margin: 0 auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .no-print {
                position: fixed; top: 20px; right: 20px; z-index: 1000;
                display: flex; gap: 10px;
            }
            .btn {
                padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;
                font-family: sans-serif; font-weight: bold; color: white;
                text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
            }
            .btn-print { background: #2563eb; }
            .btn-close { background: #4b5563; }
        }

        /* KHUSUS CETAK */
        @media print {
            .no-print { display: none; }
            .paper { width: 100%; margin: 0; padding: 0; box-shadow: none; }
            body { background: white; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5z"/></svg>
            Cetak Dokumen
        </button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

    <div class="paper">
        
        <table class="header-kop">
            <tr>
                <td class="text-center">
                    <h3 class="school-name">SMK TEXMACO SUBANG</h3>
                    <p class="school-address">
                        Kawasan Industri Perkasa, Jl. Raya Cipeundeuy - Pabuaran No.km 3<br>
                        Karangmukti, Kec. Cipeundeuy, Kabupaten Subang, Jawa Barat 41262<br>
                        Email: info@smktexmaco.sch.id | Web: smktexmaco.sch.id
                    </p>
                </td>
            </tr>
        </table>

        <div class="text-center mb-4">
            <h3 style="margin: 0; text-decoration: underline;">LAPORAN HASIL BELAJAR SISWA</h3>
        </div>

        <table class="info-table">
            <tr>
                <td width="18%">Nama Peserta Didik</td>
                <td width="2%">:</td>
                <td width="45%" class="text-bold"><?php echo strtoupper($biodata["nama_lengkap"]); ?></td>
                <td width="15%">Kelas</td>
                <td width="2%">:</td>
                <td><?php echo $biodata["nama_kelas"]; ?></td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td><?php echo $biodata["nis"]; ?> / <?php echo $biodata["nisn"]; ?></td>
                <td>Semester</td>
                <td>:</td>
                <td><?php echo $tahun["semester"]; ?></td>
            </tr>
            <tr>
                <td>Program Keahlian</td>
                <td>:</td>
                <td><?php echo $biodata["jurusan"]; ?></td>
                <td>Tahun Ajaran</td>
                <td>:</td>
                <td><?php echo $tahun["tahun"]; ?></td>
            </tr>
        </table>

        <table class="grade-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Mata Pelajaran</th>
                    <th width="10%">KKM</th>
                    <th width="10%">Nilai</th>
                    <th width="35%">Predikat & Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($nilai["A"])): ?>
                <tr><td colspan="5" class="group-header">A. Muatan Nasional</td></tr>
                <?php $no=1; foreach ($nilai["A"] as $row): ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo $row["nama_mapel"]; ?></td>
                    <td class="text-center"><?php echo $row["kkm"]; ?></td>
                    <td class="text-center text-bold"><?php echo $row["nilai_akhir"]; ?></td>
                    <td class="text-center"><?php echo getPredikat($row["nilai_akhir"], $row["kkm"]); ?></td>
                </tr>
                <?php endforeach; endif; ?>

                <?php if (!empty($nilai["B"])): ?>
                <tr><td colspan="5" class="group-header">B. Muatan Kewilayahan</td></tr>
                <?php $no=1; foreach ($nilai["B"] as $row): ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo $row["nama_mapel"]; ?></td>
                    <td class="text-center"><?php echo $row["kkm"]; ?></td>
                    <td class="text-center text-bold"><?php echo $row["nilai_akhir"]; ?></td>
                    <td class="text-center"><?php echo getPredikat($row["nilai_akhir"], $row["kkm"]); ?></td>
                </tr>
                <?php endforeach; endif; ?>

                <?php if (!empty($nilai["C"])): ?>
                <tr><td colspan="5" class="group-header">C. Muatan Peminatan Kejuruan</td></tr>
                <?php $no=1; foreach ($nilai["C"] as $row): ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo $row["nama_mapel"]; ?></td>
                    <td class="text-center"><?php echo $row["kkm"]; ?></td>
                    <td class="text-center text-bold"><?php echo $row["nilai_akhir"]; ?></td>
                    <td class="text-center"><?php echo getPredikat($row["nilai_akhir"], $row["kkm"]); ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>

        <div class="notes-container">
            <div style="display: table-cell; width: 65%; vertical-align: top; padding-right: 15px;">
                <div class="grade-table" style="border: 1px solid #000; margin: 0;">
                    <div style="background: #f0f0f0; padding: 5px 10px; border-bottom: 1px solid #000; font-weight: bold; -webkit-print-color-adjust: exact;">
                        Catatan Wali Kelas
                    </div>
                    <div style="padding: 10px; min-height: 100px;">
                        <div class="mb-2">
                            <strong>Sikap & Karakter:</strong><br>
                            <?php echo nl2br(htmlspecialchars($catatan["catatan_sikap"])); ?>
                        </div>
                        <div>
                            <strong>Akademik:</strong><br>
                            <?php echo nl2br(htmlspecialchars($catatan["catatan_akademik"])); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: table-cell; width: 35%; vertical-align: top;">
                <table class="grade-table" style="margin: 0;">
                    <tr>
                        <th colspan="2">Ketidakhadiran</th>
                    </tr>
                    <tr>
                        <td width="70%">Sakit</td>
                        <td class="text-center"><?php echo $absensi["Sakit"]; ?> hari</td>
                    </tr>
                    <tr>
                        <td>Izin</td>
                        <td class="text-center"><?php echo $absensi["Izin"]; ?> hari</td>
                    </tr>
                    <tr>
                        <td>Tanpa Keterangan</td>
                        <td class="text-center"><?php echo $absensi["Alpa"]; ?> hari</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        Mengetahui,<br>Orang Tua / Wali
                        <div class="signature-space"></div>
                        (.........................................)
                    </td>
                    <td>
                        </td>
                    <td>
                        Subang, <?php echo $tanggalCetak; ?><br>
                        Wali Kelas
                        <div class="signature-space"></div>
                        
                        <span class="text-bold" style="text-decoration: underline;">
                            <?php echo htmlspecialchars($biodata["guru_wali"] ?? "........................."); ?>
                        </span><br>
                        NIP. <?php echo htmlspecialchars($biodata["guru_nip"] ?? "-"); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 30px;">
                        Mengetahui,<br>
                        Kepala Sekolah
                        <div class="signature-space"></div>
                        
                        <span class="text-bold" style="text-decoration: underline;">
                            <?php echo htmlspecialchars($biodata["kepala_sekolah"] ?? "........................."); ?>
                        </span><br>
                        NIP. <?php echo htmlspecialchars($biodata["kepsek_nip"] ?? "-"); ?>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</body>
</html>
