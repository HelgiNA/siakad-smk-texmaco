<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/TahunAjaran.php';
require_once __DIR__ . '/Controller.php';

use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    
    public function index()
    {
        $data = [
            'title'        => 'Master Tahun Ajaran',
            'tahun_ajaran' => TahunAjaran::getAll(),
        ];
        $this->view('master/tahun_ajaran/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Tahun Ajaran'];
        $this->view('master/tahun_ajaran/create', $data);
    }

    public function store()
    {
        $data = [
            'tahun'     => $_POST['tahun'],
            'semester'  => $_POST['semester'],
            'is_active' => 0, // Default tidak aktif
        ];

        if (empty($data['tahun']) || empty($data['semester'])) {
            $this->redirect('tahun-ajaran/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        $result = TahunAjaran::create($data);

        if ($result['status']) {
            $this->redirect('tahun-ajaran')->with('success', 'Data Tahun Ajaran berhasil disimpan!');
        } else {
            $this->redirect('tahun-ajaran/create')->with('error', 'Gagal menyimpan: ' . $result['error']);
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('tahun-ajaran')->with('error', 'ID tidak valid!');
            exit;
        }

        $tahunAjaran = TahunAjaran::find($id);
        if (! $tahunAjaran) {
            $this->redirect('tahun-ajaran')->with('error', 'Data tidak ditemukan!');
            exit;
        }

        $data = [
            'title'        => 'Edit Tahun Ajaran',
            'tahun_ajaran' => $tahunAjaran,
        ];
        $this->view('master/tahun_ajaran/edit', $data);
    }

    public function update()
    {
        $id = $_POST['tahun_id'] ?? null;
        if (! $id) {
            $this->redirect('tahun-ajaran')->with('error', 'ID tidak valid!');
            exit;
        }

        $data = [
            'tahun'    => $_POST['tahun'],
            'semester' => $_POST['semester'],
        ];

        if (empty($data['tahun']) || empty($data['semester'])) {
            $this->redirect('tahun-ajaran/edit?id=' . $id)->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        // Simpan update standard (is_active tidak diubah dr sini)
        $result = TahunAjaran::update($id, $data);

        if ($result['status']) {
            $this->redirect('tahun-ajaran')->with('success', 'Data berhasil diperbarui!');
        } else {
            $this->redirect('tahun-ajaran/edit?id=' . $id)->with('error', 'Gagal update: ' . $result['error']);
        }
    }

    public function activate()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('tahun-ajaran')->with('error', 'ID tidak valid!');
            exit;
        }

        if (TahunAjaran::activateSemester($id)) {
            $this->redirect('tahun-ajaran')->with('success', 'Tahun Ajaran Aktif Berhasil Diubah!');
        } else {
            $this->redirect('tahun-ajaran')->with('error', 'Gagal mengubah status aktif!');
        }
    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('tahun-ajaran')->with('error', 'ID tidak valid!');
            exit;
        }

        // Cek jika sedang aktif, jangan dihapus (Business Rule Opsional, tapi baik untuk safety)
        $ta = TahunAjaran::find($id);
        if ($ta['is_active'] == 1) {
            $this->redirect('tahun-ajaran')->with('error', 'Tidak dapat menghapus Tahun Ajaran yang sedang Aktif!');
            exit;
        }

        $result = TahunAjaran::delete($id);

        if ($result['status']) {
            $this->redirect('tahun-ajaran')->with('success', 'Data berhasil dihapus!');
        } else {
            $this->redirect('tahun-ajaran')->with('error', 'Gagal menghapus: ' . $result['error']);
        }
    }
}
