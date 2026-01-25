<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Rapor.php";
require_once __DIR__ . "/../Models/Siswa.php";
require_once __DIR__ . "/../Models/Kelas.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/../Models/Jadwal.php"; // <--- TAMBAHKAN INI
require_once __DIR__ . "/Controller.php";

use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Jadwal; // <--- USE JADWAL

class LaporanController extends Controller
{
    // ... (method index, inputCatatan, storeCatatan biarkan tetap sama) ...

    public function index()
    {
        // Kode index tetap sama...
        $activeTahun = TahunAjaran::getActive();
        if (!$activeTahun) {
            $this->redirect('dashboard')->with('error', 'Tahun ajaran belum aktif.');
            exit;
        }

        $guru = Guru::where('user_id', $_SESSION['user_id'])->first();
        if (!$guru) {
            $this->redirect('dashboard'); 
            exit;
        }

        $kelas = Kelas::getByWaliKelas($guru['guru_id']);
        
        if (!$kelas) {
             $this->view('akademik/rapor/index', [
                'title' => 'Rapor Siswa',
                'isWaliKelas' => false,
                'list' => []
            ]);
            return;
        }

        $siswaList = Siswa::getByKelas($kelas['kelas_id']);
        $listData = [];

        foreach ($siswaList as $s) {
            $catatan = Rapor::getCatatan($s['siswa_id'], $activeTahun['tahun_id']);
            $listData[] = [
                'siswa' => $s,
                'status_catatan' => $catatan ? 'Sudah Diisi' : 'Belum Diisi',
            ];
        }

        $data = [
            'title' => 'Rapor - Kelas ' . $kelas['nama_kelas'],
            'isWaliKelas' => true,
            'kelas' => $kelas,
            'activeTahun' => $activeTahun,
            'list' => $listData
        ];

        $this->view('akademik/rapor/index', $data);
    }

    public function inputCatatan()
    {
        // Kode tetap sama...
        $siswa_id = $_GET['id'] ?? null;
        if (!$siswa_id) { $this->redirect('rapor'); exit; }

        $activeTahun = TahunAjaran::getActive();
        $biodata = Rapor::getBiodata($siswa_id);
        $catatan = Rapor::getCatatan($siswa_id, $activeTahun['tahun_id']);

        $data = [
            'title' => 'Input Catatan Rapor',
            'biodata' => $biodata,
            'catatan' => $catatan,
            'tahun' => $activeTahun
        ];

        $this->view('akademik/rapor/input_catatan', $data);
    }

    public function storeCatatan()
    {
        // Kode tetap sama...
        $activeTahun = TahunAjaran::getActive();
        $guru = Guru::where('user_id', $_SESSION['user_id'])->first();

        $data = [
            'siswa_id' => $_POST['siswa_id'],
            'tahun_id' => $activeTahun['tahun_id'],
            'guru_wali_id' => $guru['guru_id'],
            'catatan_sikap' => $_POST['catatan_sikap'],
            'catatan_akademik' => $_POST['catatan_akademik']
        ];

        if (Rapor::saveCatatan($data)) {
            $this->redirect('rapor')->with('success', 'Catatan rapor berhasil disimpan.');
        } else {
            $this->redirect('rapor/catatan?id='.$_POST['siswa_id'])->with('error', 'Gagal menyimpan catatan.');
        }
    }

    // ---------------------------------------------------------
    // PERBAIKAN PADA METHOD PRINT
    // ---------------------------------------------------------
    public function print()
    {
        $siswa_id = $_GET['id'] ?? null;
        if (!$siswa_id) { $this->redirect('rapor'); exit; }

        $activeTahun = TahunAjaran::getActive();

        // 1. Ambil Data
        $biodata = Rapor::getBiodata($siswa_id);
        $nilai   = Rapor::getNilaiAkademik($siswa_id, $activeTahun['tahun_id']);
        $absensi = Rapor::getAbsensi($siswa_id, $activeTahun['tahun_id']);
        $catatan = Rapor::getCatatan($siswa_id, $activeTahun['tahun_id']);

        // --- VALIDASI 1: Cek Catatan Wali Kelas ---
        if (!$catatan) {
            $this->redirect('rapor')->with('error', 'Gagal Cetak: Catatan Wali Kelas belum diisi.');
            exit;
        }

        // --- VALIDASI 2: Cek Kelengkapan Nilai (Tidak boleh ada Nilai Akhir 0/Kosong) ---
        // Gabungkan semua kelompok nilai (A, B, C) menjadi satu array rata
        $semuaNilai = array_merge(
            $nilai['A'] ?? [], 
            $nilai['B'] ?? [], 
            $nilai['C'] ?? []
        );

        if (empty($semuaNilai)) {
            $this->redirect('rapor')->with('error', 'Gagal Cetak: Belum ada data nilai sama sekali.');
            exit;
        }

        foreach ($semuaNilai as $n) {
            // Asumsi key 'nilai_akhir' ada di database. Jika nilai 0 atau null, tolak.
            if (!isset($n['nilai_akhir']) || $n['nilai_akhir'] == 0 || $n['nilai_akhir'] === null) {
                $namaMapel = $n['nama_mapel'] ?? 'Salah satu mapel';
                $this->redirect('rapor')->with('error', "Gagal Cetak: Nilai untuk mapel <b>{$namaMapel}</b> belum lengkap (Nilai Akhir masih 0/Kosong).");
                exit;
            }
        }

        // --- VALIDASI 3: Cross-Check dengan Jadwal Pelajaran ---
        if (isset($biodata['kelas_id'])) {
            // PERBAIKAN: Panggil static method dari Model Jadwal
            // Tidak perlu new Jadwal() dan akses ->conn lagi
            $totalMapelJadwal = Jadwal::countMapelInKelas($biodata['kelas_id'], $activeTahun['tahun_id']);
            
            $totalMapelNilai = count($semuaNilai);

            if ($totalMapelNilai < $totalMapelJadwal) {
                $selisih = $totalMapelJadwal - $totalMapelNilai;
                $this->redirect('rapor')->with('error', "Gagal Cetak: Nilai belum lengkap. Ada <b>{$selisih}</b> mata pelajaran yang dijadwalkan tapi belum dinilai sama sekali.");
                exit;
            }
        }

        // Jika semua lolos, baru cetak
        Rapor::logPrint($_SESSION['user_id'], $siswa_id, 'Rapor');

        $data = [
            'title' => 'Rapor ' . $biodata['nama_lengkap'],
            'biodata' => $biodata,
            'nilai' => $nilai,
            'absensi' => $absensi,
            'catatan' => $catatan,
            'tahun' => $activeTahun
        ];

        $this->view('akademik/rapor/cetak', $data);
    }
}