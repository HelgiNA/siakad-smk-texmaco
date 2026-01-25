<?php
namespace App\Controllers;

// Load Controller Utama
require_once __DIR__ . '/Controller.php';

// Load Models
require_once __DIR__ . '/../Models/Siswa.php';
require_once __DIR__ . '/../Models/Guru.php';
require_once __DIR__ . '/../Models/Kelas.php';
require_once __DIR__ . '/../Models/Mapel.php';
require_once __DIR__ . '/../Models/Jadwal.php';
require_once __DIR__ . '/../Models/Absensi.php';
require_once __DIR__ . '/../Models/Nilai.php'; // Tambahan untuk monitoring nilai

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Nilai;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Cek Sesi Login
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // 2. Data Dasar
        $data = [
            'title'    => 'Dashboard',
            'username' => $_SESSION['username'] ?? 'User',
            'role'     => $_SESSION['role'],
            'tanggal'  => date('d F Y')
        ];

        // 3. Routing Data Berdasarkan Role
        switch ($_SESSION['role']) {
            case 'Admin':
                $data = array_merge($data, $this->prepareDashboardAdmin());
                $viewFile = 'dashboard/admin'; // Opsional: jika view dipisah
                break;
                
            case 'Kepsek':
                $data = array_merge($data, $this->prepareDashboardKepsek());
                $viewFile = 'dashboard/kepsek';
                break;
                
            case 'Guru':
                $data = array_merge($data, $this->prepareDashboardGuru());
                $viewFile = 'dashboard/guru';
                break;
                
            case 'Siswa':
                $data = array_merge($data, $this->prepareDashboardSiswa());
                $viewFile = 'dashboard/siswa';
                break;
                
            default:
                header('Location: ' . BASE_URL . '/login');
                exit;
        }

        // 4. Render View (Menggunakan satu file dashboard.php yang dinamis atau terpisah)
        // Disini saya asumsikan pakai satu file view 'dashboard' yang meng-include partials
        $this->view('dashboard', $data);
    }

    // =========================================================================
    // DATA PREPARATION METHODS
    // =========================================================================

    /**
     * Data untuk Administrator
     * Fokus: Manajemen Data Master
     */
    private function prepareDashboardAdmin()
    {
        return [
            'total_siswa' => Siswa::countActive(),
            'total_guru'  => Guru::countActive(),
            'total_kelas' => Kelas::countActive(),
            'total_mapel' => Mapel::countActive(),
            // Log aktivitas bisa ditambahkan di sini
        ];
    }

    /**
     * Data untuk Kepala Sekolah
     * Fokus: Monitoring & Statistik (Executive Summary)
     */
    private function prepareDashboardKepsek()
    {
        // Statistik Umum
        $stats = [
            'total_siswa' => Siswa::countActive(),
            'total_guru'  => Guru::countActive(),
            'total_kelas' => Kelas::countActive(),
            'avg_hadir'   => Absensi::getGlobalAttendancePercentage() // Buat method ini di Model Absensi
        ];

        // Monitoring Nilai (Mencari guru yang belum submit nilai)
        // Asumsi: Model Nilai punya method getPendingSubmission()
        try {
            $stats['pending_nilai'] = Nilai::getPendingSubmission(5); // Ambil 5 data terbaru
        } catch (\Exception $e) {
            $stats['pending_nilai'] = [];
        }

        // Siswa Bermasalah (Alpa Tinggi)
        try {
            $stats['siswa_bermasalah'] = Absensi::getSiswaBermasalah(3); // Di atas 3 Alpa
        } catch (\Exception $e) {
            $stats['siswa_bermasalah'] = [];
        }

        return $stats;
    }

    /**
     * Data untuk Guru
     * Fokus: Jadwal Harian & Tugas Wali Kelas
     */
    private function prepareDashboardGuru()
    {
        $guruData = [
            'jadwal_hari_ini'  => [],
            'is_wali_kelas'    => false,
            'kelas_wali'       => null,
            'validasi_pending' => 0
        ];

        try {
            // 1. Ambil ID Guru
            $guru = Guru::findByUserId($_SESSION['user_id']);
            
            if ($guru) {
                $guru_id = $guru['guru_id'];
                
                // 2. Ambil Jadwal Hari Ini
                $hari_ini = $this->getHariIndo();
                $guruData['jadwal_hari_ini'] = Jadwal::getJadwalGuruHariIni($guru_id, $hari_ini);

                // 3. Cek Status Wali Kelas
                $kelasWali = Kelas::getByWaliKelas($guru_id);
                if ($kelasWali) {
                    $guruData['is_wali_kelas'] = true;
                    $guruData['kelas_wali']    = $kelasWali;
                    
                    // Hitung jumlah absensi status 'Pending' di kelas tersebut
                    $guruData['validasi_pending'] = Absensi::countPendingByKelas($kelasWali['kelas_id']);
                }
            }
        } catch (\Exception $e) {
            // Log error jika perlu
            $guruData['error'] = $e->getMessage();
        }

        return $guruData;
    }

    /**
     * Data untuk Siswa
     * Fokus: Profil & Absensi Pribadi
     */
    private function prepareDashboardSiswa()
    {
        $siswaData = [
            'siswa_info'        => [],
            'statistik_absensi' => [
                'hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpa' => 0, 
                'total_pertemuan' => 0, 'persentase_hadir' => 0
            ]
        ];

        try {
            // 1. Ambil Data Siswa
            $siswa = Siswa::getByUserId($_SESSION['user_id']);
            
            if ($siswa) {
                $siswaData['siswa_info'] = $siswa;
                
                // 2. Ambil Statistik Absensi
                $stats = Siswa::getStatistikAbsensi($siswa['siswa_id']);
                if ($stats) {
                    $siswaData['statistik_absensi'] = $stats;
                }
            }
        } catch (\Exception $e) {
            $siswaData['error'] = $e->getMessage();
        }

        return $siswaData;
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Konversi index hari (1-7) ke Nama Hari Indonesia
     */
    private function getHariIndo()
    {
        $hariInggris = date('N'); // 1 (Mon) - 7 (Sun)
        $map = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        return $map[$hariInggris] ?? 'Senin';
    }
}