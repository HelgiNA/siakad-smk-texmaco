<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Mapel.php';
require_once __DIR__ . '/Controller.php';

use App\Models\Mapel;

class MapelController extends Controller
{
    public function __construct()
    {
        if (! isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Master Data Mata Pelajaran',
            'mapel' => Mapel::getAll(),
        ];
        $this->view('master/mapel/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Mata Pelajaran'];
        $this->view('master/mapel/create', $data);
    }

    public function store()
    {
        $data = [
            'kode_mapel' => strtoupper($_POST['kode_mapel']),
            'nama_mapel' => $_POST['nama_mapel'],
            'kkm'        => $_POST['kkm'],
        ];

        // Validasi Simple
        if (in_array('', $data)) {
            $this->redirect('mapel/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        // Validasi Unique
        $existing = Mapel::findByKode($data['kode_mapel']);
        if ($existing) {
            $this->redirect('mapel/create')->with('error', 'Kode Mapel ' . $data['kode_mapel'] . ' sudah ada!');
            exit;
        }

        if ($data['kkm'] < 0 || $data['kkm'] > 100) {
            $this->redirect('mapel/create')->with('error', 'KKM harus antara 0-100');
            exit;
        }

        $result = Mapel::create($data);

        if ($result['status']) {
            $this->redirect('mapel')->with('success', 'Data Mata Pelajaran berhasil disimpan!');
        } else {
            $this->redirect('mapel/create')->with('error', 'Gagal menyimpan: ' . $result['error']);
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('mapel')->with('error', 'ID tidak valid');
            exit;
        }

        $mapel = Mapel::find($id);
        if (! $mapel) {
            $this->redirect('mapel')->with('error', 'Data tidak ditemukan');
            exit;
        }

        $data = [
            'title' => 'Edit Mata Pelajaran',
            'mapel' => $mapel,
        ];
        $this->view('master/mapel/edit', $data);
    }

    public function update()
    {
        $id = $_POST['mapel_id'] ?? null;
        if (! $id) {
            $this->redirect('mapel')->with('error', 'ID tidak valid');
            exit;
        }

        $data = [
            'kode_mapel' => strtoupper($_POST['kode_mapel']),
            'nama_mapel' => $_POST['nama_mapel'],
            'kkm'        => $_POST['kkm'],
        ];

        if (in_array('', $data)) {
            $this->redirect('mapel/edit?id=' . $id)->with('error', 'Semua kolom wajib diisi');
            exit;
        }

        // Validasi Unique (Ignore Self)
        $existing = Mapel::findByKode($data['kode_mapel']);
        if ($existing && $existing['mapel_id'] != $id) {
            $this->redirect('mapel/edit?id=' . $id)->with('error', 'Kode Mapel ' . $data['kode_mapel'] . ' sudah digunakan!');
            exit;
        }

        $result = Mapel::update($id, $data);

        if ($result['status']) {
            $this->redirect('mapel')->with('success', 'Data Mata Pelajaran berhasil diperbarui!');
        } else {
            $this->redirect('mapel/edit?id=' . $id)->with('error', 'Gagal update: ' . $result['error']);
        }
    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('mapel')->with('error', 'ID tidak valid');
            exit;
        }

        $result = Mapel::delete($id);

        if ($result['status']) {
            $this->redirect('mapel')->with('success', 'Data Mata Pelajaran berhasil dihapus');
        } else {
            $this->redirect('mapel')->with('error', 'Gagal hapus: ' . $result['error']);
        }
    }
}
