<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Rapor.php";
require_once __DIR__ . "/../Models/Siswa.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Guru;

class LaporanController extends Controller
{
    public function index()
    {
        // Hanya Wali Kelas (Guru) yang melihat daftar siswanya
        $guru = Guru::where('user_id', '=', $_SESSION['user_id'])->first();
        if (! $guru) {
            setAlert('error', 'Data guru tidak ditemukan.');
            return $this->redirect('dashboard');
        }

        // Cari kelas yang menjadi tanggung jawab wali untuk tahun aktif
        $active = TahunAjaran::getActive();
        if (! $active) {
            setAlert('error', 'Belum ada tahun ajaran aktif.');
            return $this->redirect('dashboard');
        }

        $query = "SELECT * FROM kelas WHERE guru_wali_id = :guru_id AND tahun_id = :tahun_id";
        $db = new \App\Core\Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([':guru_id' => $guru['guru_id'], ':tahun_id' => $active['tahun_id']]);
        $kelas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Ambil siswa per kelas (jika lebih dari satu, gabungkan)
        $list = [];
        foreach ($kelas as $k) {
            $siswa = Siswa::getByKelas($k['kelas_id']);
            foreach ($siswa as $s) {
                $status = Rapor::checkStatusValidasi($s['siswa_id']) ? 'Lengkap' : 'Belum';
                $list[] = [
                    'siswa' => $s,
                    'kelas' => $k,
                    'status' => $status
                ];
            }
        }

        $data = [
            'title' => 'Cetak Rapor - Daftar Siswa',
            'list'  => $list,
            'activeTahun' => $active
        ];

        $this->view('akademik/rapor/index', $data);
    }

    public function print()
    {
        $siswa_id = $_GET['id'] ?? null;
        if (! $siswa_id) {
            setAlert('error', 'Parameter siswa tidak ditemukan.');
            return $this->redirect('rapor');
        }

        // Cek akses: Kepsek boleh, atau Guru yang merupakan wali kelas siswa
        $role = $_SESSION['role'] ?? null;
        $allowed = false;

        if ($role === 'Kepsek' || $role === 'Admin') {
            $allowed = true;
        } else {
            $guru = Guru::where('user_id', '=', $_SESSION['user_id'])->first();
            if ($guru) {
                // cek apakah guru adalah wali kelas siswa
                $bio = Siswa::find($siswa_id);
                if ($bio && $bio['kelas_id']) {
                    $kelasRow = $this->getKelasById($bio['kelas_id']);
                    if ($kelasRow && $kelasRow['guru_wali_id'] == $guru['guru_id']) {
                        $allowed = true;
                    }
                }
            }
        }

        if (! $allowed) {
            setAlert('error', 'Anda tidak berhak mencetak rapor siswa ini.');
            return $this->redirect('rapor');
        }

        // Ambil data
        $active = TahunAjaran::getActive();
        if (! $active) {
            setAlert('error', 'Belum ada tahun ajaran aktif.');
            return $this->redirect('rapor');
        }

        if (! Rapor::checkStatusValidasi($siswa_id)) {
            setAlert('error', 'Nilai siswa belum final. Cetak diblokir.');
            return $this->redirect('rapor');
        }

        $biodata = Rapor::getBiodata($siswa_id);
        $nilai = Rapor::getNilaiAkademik($siswa_id, $active['tahun_id']);
        $absensi = Rapor::getAbsensi($siswa_id, $active['tahun_id']);

        $data = [
            'title' => 'Cetak Rapor - ' . ($biodata['nama_lengkap'] ?? ''),
            'biodata' => $biodata,
            'nilai' => $nilai,
            'absensi' => $absensi,
            'activeTahun' => $active
        ];

        // Gunakan view cetak tanpa sidebar/navbar (view standalone)
        $this->view('akademik/rapor/cetak', $data);
    }

    private function getKelasById($kelas_id)
    {
        $db = new \App\Core\Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM kelas WHERE kelas_id = :id');
        $stmt->execute([':id' => $kelas_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
