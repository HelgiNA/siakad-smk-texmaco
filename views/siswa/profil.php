<?php
/**
 * View: Dashboard / Profil Siswa
 */
ob_start();

// Helper Predikat Sederhana
function getPredikat($nilai)
{
    if ($nilai >= 90) {
        return "A";
    }
    if ($nilai >= 80) {
        return "B";
    }
    if ($nilai >= 75) {
        return "C";
    }
    return "D";
}
?>

<style>
    :root {
        --c-primary: #0f172a;
        --c-accent: #2563eb;
        --c-bg-card: #ffffff;
        --c-text-muted: #64748b;
        --radius: 12px;
    }

    /* Card Styling */
    .profile-card {
        background: var(--c-bg-card); border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
        margin-bottom: 24px;
    }
    
    .card-header-clean {
        padding: 20px 24px; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
        background: #fff;
    }
    .card-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px; }

    /* Profile Section */
    .avatar-box {
        width: 100px; height: 100px; background: #eff6ff; color: var(--c-accent);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 3rem; margin: 0 auto 15px; border: 4px solid #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .student-name { font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 5px; }
    .student-nis { font-family: monospace; color: var(--c-text-muted); font-size: 0.95rem; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; }

    /* Info Grid */
    .info-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; font-weight: 700; margin-bottom: 2px; }
    .info-value { font-size: 1rem; color: #334155; font-weight: 600; }
    
    /* Attendance Stats */
    .stat-circle {
        position: relative; width: 120px; height: 120px; margin: 0 auto 20px;
        border-radius: 50%; background: conic-gradient(var(--c-accent) 0% <?php echo $rekapAbsensi[
            "persentase_hadir"
        ]; ?>%, #e2e8f0 <?php echo $rekapAbsensi["persentase_hadir"]; ?>% 100%);
        display: flex; align-items: center; justify-content: center;
    }
    .stat-inner {
        width: 100px; height: 100px; background: white; border-radius: 50%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .stat-percent { font-size: 1.5rem; font-weight: 800; color: var(--c-accent); line-height: 1; }
    .stat-label-small { font-size: 0.75rem; color: #64748b; margin-top: 2px; }

    .att-list-item {
        display: flex; justify-content: space-between; padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0; font-size: 0.9rem;
    }
    .att-list-item:last-child { border-bottom: none; }

    /* Grades Table */
    .grade-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .grade-table th { background: #f8fafc; color: #64748b; font-weight: 700; padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .grade-table td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
    .col-score { text-align: center; font-family: 'Consolas', monospace; width: 80px; }
    
    .badge-score { padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 0.85rem; }
    .score-high { color: #10b981; background: #dcfce7; }
    .score-mid { color: #d97706; background: #fef3c7; }
    .score-low { color: #ef4444; background: #fee2e2; }
</style>

<div style="margin-bottom: 24px;">
    <h1 class="page-title" style="margin:0; font-size: 1.5rem; font-weight: 700;">Profil Saya</h1>
    <p style="color: #64748b; margin: 5px 0 0;">Informasi akademik dan rekapitulasi studi.</p>
</div>

<div class="row">
    
    <div class="col-lg-4">
        
        <div class="profile-card">
            <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); padding: 30px 20px; text-align: center;">
                <div class="avatar-box">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="student-name"><?php echo htmlspecialchars(
                    $profileData["nama_lengkap"]
                ); ?></div>
                <span class="student-nis"><?php echo htmlspecialchars(
                    $profileData["nis"]
                ); ?></span>
            </div>
            
            <div style="padding: 24px;">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-label">Kelas</div>
                        <div class="info-value"><?php echo htmlspecialchars(
                            $profileData["nama_kelas"] ?? "-"
                        ); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Tahun Ajaran</div>
                        <div class="info-value"><?php echo htmlspecialchars(
                            $profileData["tahun"] ?? "-"
                        ); ?></div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Jurusan</div>
                        <div class="info-value"><?php echo htmlspecialchars(
                            $profileData["jurusan"] ?? "-"
                        ); ?></div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Alamat</div>
                        <div class="info-value" style="font-weight: 400; font-size: 0.95rem;">
                            <?php echo htmlspecialchars(
                                $profileData["alamat"] ?? "-"
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <div class="card-header-clean">
                <h3 class="card-title"><i class="bi bi-calendar-check text-primary"></i> Kehadiran</h3>
            </div>
            <div style="padding: 24px;">
                <div class="stat-circle">
                    <div class="stat-inner">
                        <span class="stat-percent"><?php echo $rekapAbsensi[
                            "persentase_hadir"
                        ]; ?>%</span>
                        <span class="stat-label-small">Kehadiran</span>
                    </div>
                </div>

                <div>
                    <div class="att-list-item">
                        <span><i class="bi bi-check-circle-fill text-success me-2"></i> Hadir</span>
                        <span class="fw-bold"><?php echo $rekapAbsensi[
                            "hadir"
                        ]; ?></span>
                    </div>
                    <div class="att-list-item">
                        <span><i class="bi bi-emoji-neutral-fill text-warning me-2"></i> Sakit</span>
                        <span class="fw-bold"><?php echo $rekapAbsensi[
                            "sakit"
                        ]; ?></span>
                    </div>
                    <div class="att-list-item">
                        <span><i class="bi bi-info-circle-fill text-info me-2"></i> Izin</span>
                        <span class="fw-bold"><?php echo $rekapAbsensi[
                            "izin"
                        ]; ?></span>
                    </div>
                    <div class="att-list-item">
                        <span><i class="bi bi-x-circle-fill text-danger me-2"></i> Alpa</span>
                        <span class="fw-bold"><?php echo $rekapAbsensi[
                            "alpa"
                        ]; ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-8">
        <div class="profile-card">
            <div class="card-header-clean">
                <h3 class="card-title"><i class="bi bi-journal-bookmark-fill text-primary"></i> Hasil Studi (KHS)</h3>
                <span class="badge bg-light text-dark border">Semester <?php echo htmlspecialchars(
                    $profileData["semester"] ?? "-"
                ); ?></span>
            </div>
            
            <div style="overflow-x: auto;">
                <?php if (empty($listNilai)): ?>
                    <div style="padding: 50px; text-align: center; color: #94a3b8;">
                        <i class="bi bi-journal-x" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mt-2">Belum ada data nilai yang dipublikasikan.</p>
                    </div>
                <?php
                    // Logic Status Nilai

                    // Kalkulasi Tampilan

                    // Style Predikat
                    // Logic Status Nilai
                    // Kalkulasi Tampilan
                    // Style Predikat
                    // Logic Status Nilai

                    // Kalkulasi Tampilan

                    // Style Predikat
                    // Logic Status Nilai
                    // Kalkulasi Tampilan
                    // Style Predikat
                    else: ?>
                    <table class="grade-table">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th class="col-score">Tugas</th>
                                <th class="col-score">UTS</th>
                                <th class="col-score">UAS</th>
                                <th class="col-score">Akhir</th>
                                <th class="col-score">Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listNilai as $nilai):
                                $isDraft = $nilai["is_draft"] ?? false;

                                // --- PERBAIKAN DI SINI ---
                                // Kita ambil nilai mentah
                                $rawAkhir = $nilai["nilai_akhir"];

                                // Cek apakah nilainya angka. Jika ya diformat, jika tidak (misal "-") biarkan apa adanya/set 0
                                if (is_numeric($rawAkhir)) {
                                    $akhir = number_format((float)$rawAkhir, 2);
                                    $predikat = getPredikat((float)$rawAkhir);
                                } else {
                                    $akhir = "-"; 
                                    $predikat = "-";
                                }

                                // Tentukan warna badge berdasarkan predikat
                                $badgeClass = "score-mid";
                                if ($predikat == "A") {
                                    $badgeClass = "score-high";
                                }
                                if ($predikat == "D" || $predikat == "-") {
                                    $badgeClass = "score-low";
                                }
                                // -------------------------
                                ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #334155;">
                                        <?php echo htmlspecialchars($nilai["nama_mapel"]); ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #94a3b8;">
                                        <?php echo htmlspecialchars($nilai["kelompok"]); ?>
                                    </div>
                                </td>

                                <?php if ($isDraft): ?>
                                    <td colspan="5" class="text-center text-muted" style="font-style: italic; background: #fdfdfd;">
                                        <i class="bi bi-hourglass-split me-1"></i> Nilai belum difinalisasi guru
                                    </td>
                                <?php else: ?>
                                    <td class="col-score text-muted">
                                        <?php echo is_numeric($nilai["nilai_tugas"]) ? number_format($nilai["nilai_tugas"], 0) : '-'; ?>
                                    </td>
                                    <td class="col-score text-muted">
                                        <?php echo is_numeric($nilai["nilai_uts"]) ? number_format($nilai["nilai_uts"], 0) : '-'; ?>
                                    </td>
                                    <td class="col-score text-muted">
                                        <?php echo is_numeric($nilai["nilai_uas"]) ? number_format($nilai["nilai_uas"], 0) : '-'; ?>
                                    </td>

                                    <td class="col-score" style="font-weight: 700; color: #1e293b;"><?php echo $akhir; ?></td>
                                    <td class="col-score">
                                        <span class="badge-score <?php echo $badgeClass; ?>"><?php echo $predikat; ?></span>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="alert alert-info d-flex align-items-center" role="alert" style="background: #eff6ff; border-color: #dbeafe; color: #1e40af;">
            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
            <div>
                <strong>Catatan:</strong> Jika terdapat kesalahan data diri atau nilai, silakan segera hubungi Wali Kelas atau Bagian Tata Usaha.
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../layouts/main.php";


?>
