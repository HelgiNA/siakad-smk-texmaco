<?php

namespace App\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\DetailAbsensi;

class ProfilController extends Controller
{
    /**
     * Constructor
     * Cek bahwa user login adalah Siswa
     */
    public function __construct()
    {
        // Pastikan user sudah login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit();
        }

        // Pastikan user adalah Siswa
        if ($_SESSION['role'] !== 'Siswa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    /**
     * Menampilkan halaman profil siswa
     * Menampilkan: Biodata, Statistik Kehadiran, Daftar Nilai
     */
    public function index()
    {
        $user_id = $_SESSION['user_id'];

        // 1. Ambil data profil lengkap siswa
        $profileData = Siswa::getProfileByUserId($user_id);

        if (empty($profileData)) {
            abort(404, 'Data profil siswa tidak ditemukan.');
        }

        $siswa_id = $profileData['siswa_id'];

        // 2. Ambil statistik kehadiran
        $rekapAbsensi = Siswa::getStatistikAbsensi($siswa_id);

        // 3. Ambil daftar nilai (KHS)
        $listNilai = Nilai::getKHS($siswa_id);

        // 4. PREPROCESSING: Sensor nilai yang masih Draft
        // Ubah angka menjadi "-" jika status_validasi = 'Pending' atau bukan 'Final'
        foreach ($listNilai as &$nilai) {
            // Cek apakah nilai sudah final atau masih pending
            if ($nilai['status_validasi'] !== 'Final') {
                // Sensor: ganti angka dengan "-"
                $nilai['nilai_tugas'] = '-';
                $nilai['nilai_uts'] = '-';
                $nilai['nilai_uas'] = '-';
                $nilai['nilai_akhir'] = '-';
                $nilai['is_draft'] = true;
            } else {
                $nilai['is_draft'] = false;
            }
        }
        unset($nilai);

        // 5. Prepare data untuk view
        $data = [
            'title' => 'Profil Saya',
            'profileData' => $profileData,
            'rekapAbsensi' => $rekapAbsensi,
            'listNilai' => $listNilai,
        ];

        // 6. Render view
        $this->view('siswa/profil', $data);
    }
}
