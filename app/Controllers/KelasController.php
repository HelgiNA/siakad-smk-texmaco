<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Kelas.php';
require_once __DIR__ . '/../Models/Guru.php';
require_once __DIR__ . '/../Models/TahunAjaran.php';
require_once __DIR__ . '/Controller.php';

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class KelasController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Master Data Kelas',
            'kelas' => Kelas::getAllWithDetails(),
        ];
        $this->view('master/kelas/index', $data);
    }

    public function create()
    {
        $data = [
            'title'  => 'Tambah Kelas',
            'gurus'  => Guru::getAll(),
            'tahuns' => TahunAjaran::getAll(), // Ambil semua, nanti di view user pilih yang aktif atau sesuai
        ];
        $this->view('master/kelas/create', $data);
    }

    public function store()
    {
        $data = [
            'guru_wali_id' => $_POST['guru_wali_id'],
            'tahun_id'     => $_POST['tahun_id'],
            'nama_kelas'   => $_POST['nama_kelas'],
            'tingkat'      => $_POST['tingkat'],
            'jurusan'      => $_POST['jurusan'],
        ];

        // Validasi
        if (in_array('', $data)) {
            $this->redirect('kelas/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        $result = Kelas::create($data);

        if ($result['status']) {
            $this->redirect('kelas')->with('success', 'Data Kelas berhasil disimpan!');
        } else {
            $this->redirect('kelas/create')->with('error', 'Gagal menyimpan: ' . $result['error']);
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('kelas')->with('error', 'ID tidak valid');
            exit;
        }

        $kelas = Kelas::find($id);
        if (! $kelas) {
            $this->redirect('kelas')->with('error', 'Data tidak ditemukan');
            exit;
        }

        $data = [
            'title'  => 'Edit Data Kelas',
            'kelas'  => $kelas,
            'gurus'  => Guru::getAll(),
            'tahuns' => TahunAjaran::getAll(),
        ];
        $this->view('master/kelas/edit', $data);
    }

    public function update()
    {
        $id = $_POST['kelas_id'] ?? null;
        if (! $id) {
            $this->redirect('kelas')->with('error', 'ID tidak valid');
            exit;
        }

        $data = [
            'guru_wali_id' => $_POST['guru_wali_id'],
            'tahun_id'     => $_POST['tahun_id'],
            'nama_kelas'   => $_POST['nama_kelas'],
            'tingkat'      => $_POST['tingkat'],
            'jurusan'      => $_POST['jurusan'],
        ];

        if (in_array('', $data)) {
            $this->redirect('kelas/edit?id=' . $id)->with('error', 'Semua kolom wajib diisi');
            exit;
        }

        $result = Kelas::update($id, $data);

        if ($result['status']) {
            $this->redirect('kelas')->with('success', 'Data Kelas berhasil diperbarui!');
        } else {
            $this->redirect('kelas/edit?id=' . $id)->with('error', 'Gagal update: ' . $result['error']);
        }
    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('kelas')->with('error', 'ID tidak valid');
            exit;
        }

        $result = Kelas::delete($id);

        if ($result['status']) {
            $this->redirect('kelas')->with('success', 'Data Kelas berhasil dihapus');
        } else {
            // Bisa jadi constraint restrict
            $this->redirect('kelas')->with('error', 'Gagal hapus (mungkin masih ada siswa di kelas ini): ' . $result['error']);
        }
    }
}
