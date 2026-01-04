<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Siswa.php';
require_once __DIR__ . '/../Models/Kelas.php';
require_once __DIR__ . '/Controller.php';

use App\Models\Kelas;
use App\Models\Siswa;

class PlottingController extends Controller
{

    public function index()
    {
        $kelas  = Kelas::getAll();
        $result = [];
        // Calculate student count for each class
        foreach ($kelas as $k) {
            $students          = Siswa::getByKelas($k['kelas_id']);
            $k['jumlah_siswa'] = count($students);
            $result[]          = $k;
        }

        $data = [
            'title' => 'Plotting Siswa',
            'kelas' => $result,
        ];

        $this->view('akademik/plotting/index', $data);
    }

    public function manage()
    {
        $kelas_id = $_GET['id'] ?? null;
        if (! $kelas_id) {
            $this->redirect('plotting')->with('error', 'ID Kelas tidak valid');
            exit;
        }

        $kelas = Kelas::find($kelas_id);
        if (! $kelas) {
            $this->redirect('plotting')->with('error', 'Kelas tidak ditemukan');
            exit;
        }

        $data = [
            'title'      => 'Kelola Rombel: ' . $kelas['nama_kelas'],
            'kelas'      => $kelas,
            'unassigned' => Siswa::getUnassigned(),
            'assigned'   => Siswa::getByKelas($kelas_id),
        ];

        $this->view('akademik/plotting/manage', $data);
    }

    public function add()
    {
        $kelas_id  = $_POST['kelas_id'] ?? null;
        $siswa_ids = $_POST['siswa_id'] ?? [];

        if (! $kelas_id || empty($siswa_ids)) {
            $this->redirect('plotting/manage?id=' . $kelas_id)->with('error', 'Pilih minimal satu siswa!');
            exit;
        }

        $count = 0;
        foreach ($siswa_ids as $sid) {
            $siswa = Siswa::update($sid, ['kelas_id' => $kelas_id]);
            $count++;
        }

        if ($siswa['status']) {
            $this->redirect('plotting/manage?id=' . $kelas_id)->with('success', $count . ' siswa berhasil ditambahkan ke kelas.');
        } else {
            $this->redirect('plotting/manage?id=' . $kelas_id)->with('error', 'Gagal menambahkan siswa ke kelas. ' . $siswa['error']);
        }
    }

    public function remove()
    {
        $kelas_id = $_POST['kelas_id'] ?? null;
        $siswa_id = $_POST['siswa_id'] ?? null;

        if (! $kelas_id || ! $siswa_id) {
            $this->redirect('plotting')->with('error', 'Data tidak valid');
            exit;
        }

        $siswa = Siswa::update($siswa_id, ['kelas_id' => null]);

        if ($siswa['status']) {
            $this->redirect('plotting/manage?id=' . $kelas_id)->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
        } else {
            $this->redirect('plotting/manage?id=' . $kelas_id)->with('error', 'Gagal menghapus siswa dari kelas. ' . $siswa['error']);
        }
    }
}