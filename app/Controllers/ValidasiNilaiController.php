<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Nilai.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Kelas.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Nilai;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\Kelas;

class ValidasiNilaiController extends Controller
{
    /**
     * PHASE 4: Validasi Nilai Akademik (Wali Kelas)
     * 
     * Tampilkan rekap nilai siswa di kelas ampuan
     * Wali Kelas bisa approve (Final) atau reject (Revisi)
     * 
     * Pre-check: Nilai tidak boleh 0 saat finalisasi
     */
    public function index()
    {
        // 1. Tentukan Tahun Ajaran Aktif
        $activeTahun = TahunAjaran::getActive();
        if (!$activeTahun) {
            setAlert("error", "Belum ada Tahun Ajaran aktif.");
            return $this->redirect("dashboard");
        }

        // 2. Identifikasi Guru (yang menjadi Wali Kelas)
        $guru = Guru::where("user_id", "=", $_SESSION["user_id"])->first();
        if (!$guru) {
            setAlert("error", "Data Guru tidak ditemukan.");
            return $this->redirect("dashboard");
        }

        // 3. Cek apakah guru ini adalah Wali Kelas (check di database)
        // Asumsi: Ada column kelas_wali_id di table guru
        // Jika belum ada, sesuaikan dengan struktur database Anda
        $kelas = Kelas::find($guru['kelas_wali_id'] ?? null);
        if (!$kelas) {
            setAlert("error", "Anda tidak ditunjuk sebagai Wali Kelas.");
            return $this->redirect("dashboard");
        }

        // 4. Ambil rekap nilai untuk kelas ini
        $nilai = Nilai::getRekapByKelas($kelas['kelas_id'], $activeTahun['tahun_id']);

        // 5. Render view
        return $this->render('akademik/validasi/nilai', [
            'nilai'       => $nilai,
            'kelas'       => $kelas,
            'tahunAjaran' => $activeTahun
        ]);
    }

    /**
     * Proses validasi: terima (Final) atau tolak (Revisi)
     * 
     * POST /validasi-nilai/proses
     * Params: 
     *   - nilai_id: ID nilai yang divalidasi
     *   - keputusan: 'Final' atau 'Revisi'
     *   - catatan: (optional) keterangan revisi
     */
    public function proses()
    {
        // 1. Identifikasi Guru (Wali Kelas)
        $guru = Guru::where("user_id", "=", $_SESSION["user_id"])->first();
        if (!$guru) {
            setAlert("error", "Akses ditolak: Guru tidak ditemukan.");
            return $this->redirect("dashboard");
        }

        // 2. Validasi input
        $nilai_id  = $_POST['nilai_id'] ?? null;
        $keputusan = $_POST['keputusan'] ?? null;

        if (!$nilai_id || !in_array($keputusan, ['Final', 'Revisi'])) {
            setAlert("error", "Input tidak valid.");
            return $this->redirect("dashboard");
        }

        // 3. Ambil data nilai yang akan divalidasi
        $nilai = Nilai::find($nilai_id);
        if (!$nilai) {
            setAlert("error", "Data nilai tidak ditemukan.");
            return $this->redirect("dashboard");
        }

        // 4. PRE-CHECK: Jika keputusan = 'Final', cek apakah nilai kosong/0
        if ($keputusan === 'Final') {
            if ($nilai['nilai_tugas'] == 0 || $nilai['nilai_uts'] == 0 || $nilai['nilai_uas'] == 0) {
                setAlert("error", "Tidak bisa finalisasi: Ada nilai yang masih kosong (0).");
                return $this->redirect("dashboard");
            }
        }

        // 5. Update status validasi
        $result = Nilai::updateStatus($nilai_id, $keputusan);

        if ($result) {
            $msg = $keputusan === 'Final' 
                ? "Nilai berhasil disetujui (Final)."
                : "Nilai ditolak. Guru akan melakukan revisi.";
            
            setAlert("success", $msg);
        } else {
            setAlert("error", "Gagal mengupdate status validasi.");
        }

        return $this->redirect("akademik/validasi");
    }
}
