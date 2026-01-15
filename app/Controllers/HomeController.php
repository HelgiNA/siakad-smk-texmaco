<?php
namespace App\Controllers;

// Pastikan load parent Controller
require_once __DIR__ . '/Controller.php';

// Import Models
require_once __DIR__ . '/../Models/Siswa.php';
require_once __DIR__ . '/../Models/Guru.php';
require_once __DIR__ . '/../Models/Kelas.php';
require_once __DIR__ . '/../Models/Mapel.php';
require_once __DIR__ . '/../Models/Jadwal.php';
require_once __DIR__ . '/../Models/Absensi.php';

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Jadwal;
use App\Models\Absensi;

class HomeController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            redirect('/login');
            exit;
        }

        $data = [
            'title' => 'Dashboard',
            'username' => $_SESSION['username'] ?? 'User',
            'role' => $_SESSION['role']
        ];

        // Logika berdasarkan role
        switch ($_SESSION['role']) {
            case 'Admin':
            case 'Kepsek':
                $data = $this->prepareDashboardAdmin($data);
                break;
            case 'Guru':
                $data = $this->prepareDashboardGuru($data);
                break;
            case 'Siswa':
                $data = $this->prepareDashboardSiswa($data);
                break;
            default:
                redirect('/login');
                exit;
        }

        // Panggil view dashboard menggunakan fungsi helper dari parent Controller
        $this->view('dashboard', $data);
    }

    /**
     * Persiapkan data untuk Dashboard Admin/Kepsek
     * Menampilkan: Total Siswa, Total Guru, Total Kelas, Total Mapel
     */
    private function prepareDashboardAdmin($data)
    {
        try {
            $data['total_siswa'] = Siswa::countActive();
            $data['total_guru'] = Guru::countActive();
            $data['total_kelas'] = Kelas::countActive();
            $data['total_mapel'] = Mapel::countActive();
        } catch (\Exception $e) {
            $data['total_siswa'] = 0;
            $data['total_guru'] = 0;
            $data['total_kelas'] = 0;
            $data['total_mapel'] = 0;
            $data['error'] = 'Gagal memuat data dashboard: ' . $e->getMessage();
        }

        return $data;
    }

    /**
     * Persiapkan data untuk Dashboard Guru
     * Menampilkan: Jadwal Mengajar Hari Ini, Notifikasi Validasi Absensi (jika wali kelas)
     */
    private function prepareDashboardGuru($data)
    {
        try {
            $user_id = $_SESSION['user_id'];

            // Cari data guru dari user_id
            $guru = Guru::findByUserId($user_id);
            if (!$guru) {
                $data['error'] = 'Data guru tidak ditemukan';
                $data['jadwal_hari_ini'] = [];
                $data['validasi_pending'] = 0;
                return $data;
            }

            $guru_id = $guru['guru_id'];

            // Tentukan hari saat ini (Senin, Selasa, dst)
            // date('N') mengembalikan 1-7 (Monday-Sunday), perlu konversi ke nama hari Indonesia
            $hari_number = date('N');
            $hari_mapping = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu' // Jarang ada jadwal Minggu
            ];
            $hari_ini = $hari_mapping[$hari_number];

            // Ambil jadwal mengajar hari ini
            $data['jadwal_hari_ini'] = Jadwal::getJadwalGuruHariIni($guru_id, $hari_ini);

            // Cek apakah guru ini adalah wali kelas
            $kelas_wali = Kelas::getByWaliKelas($guru_id);
            if ($kelas_wali) {
                $data['is_wali_kelas'] = true;
                $data['kelas_wali'] = $kelas_wali;
                $data['validasi_pending'] = Absensi::countPendingByKelas($kelas_wali['kelas_id']);
            } else {
                $data['is_wali_kelas'] = false;
                $data['validasi_pending'] = 0;
            }
        } catch (\Exception $e) {
            $data['error'] = 'Gagal memuat data dashboard: ' . $e->getMessage();
            $data['jadwal_hari_ini'] = [];
            $data['is_wali_kelas'] = false;
            $data['validasi_pending'] = 0;
        }

        return $data;
    }

    /**
     * Persiapkan data untuk Dashboard Siswa
     * Menampilkan: Informasi Pribadi, Statistik Kehadiran
     */
    private function prepareDashboardSiswa($data)
    {
        try {
            $user_id = $_SESSION['user_id'];

            // Cari data siswa dari user_id
            $siswa = Siswa::getByUserId($user_id);
            if (!$siswa) {
                $data['error'] = 'Data siswa tidak ditemukan';
                $data['statistik_absensi'] = [];
                return $data;
            }

            $siswa_id = $siswa['siswa_id'];

            // Ambil statistik absensi
            $data['statistik_absensi'] = Siswa::getStatistikAbsensi($siswa_id);
            $data['siswa_info'] = $siswa;
        } catch (\Exception $e) {
            $data['error'] = 'Gagal memuat data dashboard: ' . $e->getMessage();
            $data['statistik_absensi'] = [];
        }

        return $data;
    }
}
