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
     * SIA-008 V2: Validasi Nilai Akademik (Wali Kelas)
     * 
     * Tampilkan HANYA nilai yang status = 'Submitted'
     * (Data 'Draft' tidak perlu divalidasi, itu privasi Guru Mapel)
     * 
     * Wali Kelas bisa approve (Final) atau reject (Revisi + Catatan)
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

        // 3. Cek apakah guru ini adalah Wali Kelas
        $kelas = Kelas::find($guru['kelas_wali_id'] ?? null);
        if (!$kelas) {
            setAlert("error", "Anda tidak ditunjuk sebagai Wali Kelas.");
            return $this->redirect("dashboard");
        }

        // 4. Ambil HANYA nilai dengan status 'Submitted' (SIA-008 V2 - Filter Data)
        // Draft tidak perlu ditampilkan, masih privasi guru
        $nilai = Nilai::getByStatus($kelas['kelas_id'], $activeTahun['tahun_id'], 'Submitted');

        // 5. Render view
        return $this->render('akademik/validasi/nilai', [
            'nilai'       => $nilai,
            'kelas'       => $kelas,
            'tahunAjaran' => $activeTahun
        ]);
    }

    /**
     * SIA-008 V2: Proses Validasi dengan Catatan Revisi
     * 
     * POST /validasi-nilai/proses
     * Params: 
     *   - nilai_id: ID nilai yang divalidasi
     *   - keputusan: 'Final' atau 'Revisi'
     *   - catatan_revisi: (MANDATORY jika Revisi) feedback untuk guru
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
        $nilai_id      = $_POST['nilai_id'] ?? null;
        $keputusan     = $_POST['keputusan'] ?? null;
        $catatan_revisi = $_POST['catatan_revisi'] ?? null;

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
                return $this->redirect("validasi-nilai");
            }
        }

        // 5. PRE-CHECK: Jika keputusan = 'Revisi', catatan WAJIB ada
        if ($keputusan === 'Revisi' && empty($catatan_revisi)) {
            setAlert("error", "Catatan revisi wajib diisi saat menolak.");
            return $this->redirect("validasi-nilai");
        }

        // 6. Update status + catatan revisi (SIA-008 V2)
        $result = Nilai::updateStatusWithNote($nilai_id, $keputusan, $catatan_revisi);

        if ($result['status']) {
            if ($keputusan === 'Final') {
                $msg = "Nilai berhasil disetujui (Final). Data terkunci.";
            } else {
                $msg = "Nilai ditolak (Revisi). Guru akan melihat catatan Anda.";
            }
            setAlert("success", $msg);
        } else {
            setAlert("error", $result['message']);
        }

        return $this->redirect("validasi-nilai");
    }
}

