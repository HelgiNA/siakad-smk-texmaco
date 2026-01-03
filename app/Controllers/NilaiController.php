<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Core\Flash;

class NilaiController extends Controller
{
    public function create()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Guru') {
            Flash::set('error', 'Anda tidak memiliki akses ke halaman ini.');
            $this->redirect('/login');
            return;
        }

        $guru_id = $_SESSION['user']['guru_id'];
        if (!isset($_SESSION['tahun_ajaran_aktif'])) {
            Flash::set('error', 'Tidak ada tahun ajaran yang aktif saat ini.');
            $this->redirect('/dashboard');
            return;
        }
        
        $tahun_id = $_SESSION['tahun_ajaran_aktif']['tahun_id'];

        $data['jadwal'] = Jadwal::getKelasMapelByGuru($guru_id, $tahun_id);
        $data['page_title'] = "Input Nilai - Pilih Kelas & Mata Pelajaran";

        $this->view('nilai/create', $data);
    }

    public function input()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Guru') {
            Flash::set('error', 'Anda tidak memiliki akses ke halaman ini.');
            $this->redirect('/login');
            return;
        }

        if (!isset($_GET['kelas_id']) || !isset($_GET['mapel_id'])) {
            Flash::set('error', 'Parameter kelas atau mata pelajaran tidak valid.');
            $this->redirect('/nilai/create');
            return;
        }

        $kelas_id = $_GET['kelas_id'];
        $mapel_id = $_GET['mapel_id'];
        $tahun_id = $_SESSION['tahun_ajaran_aktif']['tahun_id'];

        $data['kelas'] = Kelas::find($kelas_id);
        $data['mapel'] = Mapel::find($mapel_id);
        $data['siswa'] = Siswa::getByKelas($kelas_id);
        $data['nilai'] = Nilai::getByKelasMapel($kelas_id, $mapel_id, $tahun_id);
        $data['page_title'] = "Input Nilai - " . $data['kelas']['nama_kelas'] . " - " . $data['mapel']['nama_mapel'];
        $data['kelas_id'] = $kelas_id;
        $data['mapel_id'] = $mapel_id;

        if (!$data['kelas'] || !$data['mapel']) {
            Flash::set('error', 'Data kelas atau mapel tidak ditemukan.');
            $this->redirect('/nilai/create');
            return;
        }
        
        $this->view('nilai/input', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Guru') {
            Flash::set('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
            $this->redirect('/login');
            return;
        }

        $kelas_id = $_POST['kelas_id'];
        $mapel_id = $_POST['mapel_id'];
        $tahun_id = $_POST['tahun_id'];
        $nilaiData = $_POST['nilai'];

        $dataToSave = [];
        foreach ($nilaiData as $siswa_id => $nilai) {
            $dataToSave[] = [
                'siswa_id' => $siswa_id,
                'mapel_id' => $mapel_id,
                'tahun_id' => $tahun_id,
                'tugas'    => !empty($nilai['tugas']) ? $nilai['tugas'] : 0,
                'uts'      => !empty($nilai['uts']) ? $nilai['uts'] : 0,
                'uas'      => !empty($nilai['uas']) ? $nilai['uas'] : 0,
            ];
        }

        if (Nilai::saveBatch($dataToSave)) {
            Flash::set('success', 'Nilai berhasil disimpan.');
        } else {
            Flash::set('error', 'Gagal menyimpan nilai.');
        }

        $this->redirect("/nilai/input?kelas_id={$kelas_id}&mapel_id={$mapel_id}");
    }
}
