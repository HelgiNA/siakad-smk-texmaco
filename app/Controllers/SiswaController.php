<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Siswa.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/Controller.php';

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;

class SiswaController extends Controller
{
    public function __construct()
    {
        // Hanya Admin dan Guru (mungkin) yang boleh akses
        if (! isset($_SESSION['role']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Guru')) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title'    => 'Master Data Siswa',
            'students' => Siswa::getAllWithRelasi(),
        ];
        $this->view('master/siswa/index', $data);
    }

    public function create()
    {
        $this->view('master/siswa/create', [
            'title' => 'Tambah Siswa Baru',
            'kelas' => Kelas::getAll(),
        ]);
    }

    public function store()
    {
        // 1. Ambil Data
        $data = [
            'kelas_id'      => $_POST['kelas_id'],
            'nis'           => $_POST['nis'],
            'nisn'          => $_POST['nisn'],
            'nama_lengkap'  => $_POST['nama_lengkap'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'alamat'        => $_POST['alamat'],
        ];

        // 2. Validasi Simple
        if (in_array('', $data)) {
            $this->redirect('siswa/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        // 3. Cek NIS
        $existing = Siswa::where('nis', $data['nis'])->first();
        if ($existing) {
            $this->redirect('siswa/create')->with('error', 'NIS ' . $data['nis'] . ' sudah ada!');
            exit;
        }

        // 4. Cek NISN
        $existing = Siswa::where('nisn', $data['nisn'])->first();
        if ($existing) {
            $this->redirect('siswa/create')->with('error', 'NISN ' . $data['nisn'] . ' sudah ada!');
            exit;
        }

        // 5. Cek kelas
        $kelas = Kelas::find($data['kelas_id']);
        if (! $kelas) {
            $this->redirect('siswa/create')->with('error', 'Kelas tidak ditemukan!');
            exit;
        }

        // Mulai Ke Database
        // 6. Buat User
        $userResult = User::create([
            'username' => $data['nis'],
            'password' => password_hash($data['nis'], PASSWORD_DEFAULT),
            'role'     => 'Siswa',
        ]);

        // CEK STATUS
        if ($userResult['status'] === false) {
            // Kita bisa cek apakah errornya karena duplikat?
            $pesan = "Gagal membuat akun user: " . $userResult['error'];
            $this->redirect('siswa/create')->with('error', $pesan);
            die();
        }

        $data['user_id'] = $userResult['lastInsertId'];

        // 7. Buat Siswa (Pakai ID dari result user)
        $siswaResult = Siswa::create($data);

        if ($siswaResult['status'] === false) {
            User::delete($userResult['lastInsertId']);

            $this->redirect('siswa/create')->with('error', 'Gagal menyimpan data siswa: ' . $siswaResult['error'] . " " . $userResult);
            die();
        }

        $this->redirect('siswa')->with('success', 'Data Siswa berhasil disimpan!');
    }

    public function edit()
    {
        // 1. Ambil ID dari URL (?id=1) secara manual
        // Kita pakai operator '?? null' agar tidak error jika id tidak ada
        $id = $_GET['id'] ?? null;

        // 2. Validasi sederhana
        if (! $id) {
            $this->redirect('siswa')->with('error', 'ID Siswa tidak ditemukan!');
            exit;
        }

        // 3. Panggil Model
        $student = Siswa::find($id);

        // 4. Cek apakah siswanya ketemu di DB?
        if (! $student) {
            $this->redirect('siswa')->with('error', 'Data siswa tidak ditemukan!');
            exit;
        }

        // 5. Tampilkan View
        $data = [
            'title'   => 'Edit Siswa',
            'student' => $student,
            'kelas'   => Kelas::getAll(),
        ];
        $this->view('master/siswa/edit', $data);
    }

    public function update()
    {
        // 1. Ambil ID (Wajib ada)
        $id = $_POST['id'] ?? null;

        // 2. Validasi sederhana
        if (! $id) {
            $this->redirect('siswa')->with('error', 'ID Siswa tidak ditemukan!');
            die();
        }

        // Mulai Ke Database
        // 3. Ambil Data dari form
        $data = [
            'kelas_id'      => $_POST['kelas_id'],
            'nis'           => $_POST['nis'],
            'nisn'          => $_POST['nisn'],
            'nama_lengkap'  => $_POST['nama_lengkap'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'alamat'        => $_POST['alamat'],
        ];

        // 4. Validasi Simple
        if (in_array('', $data)) {
            $this->redirect('siswa/edit?id=' . $id)->with('error', 'Semua data wajib diisi!');
            die();
        }

        // 5. Cek kelas
        $kelas = Kelas::find($data['kelas_id']);
        if (! $kelas) {
            $this->redirect('siswa/edit?id=' . $id)->with('error', 'Kelas tidak ditemukan!');
            exit;
        }

        // 6. Eksekusi Update ke Tabel Siswa Saja
        $updateResult = Siswa::update($id, $data);

        // CEK STATUS
        if ($updateResult['status'] === false) {
            // Kita bisa cek apakah errornya karena duplikat?
            $pesan = "Gagal update data siswa: " . $updateResult['error'];
            $this->redirect('siswa/edit?id=' . $id)->with('error', $pesan);
            die();
        }

        // 7. Selesai!
        $this->redirect('siswa')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy()
    {
                                   // 1. Ambil ID dari URL (?id=1) secara manual
                                   // Kita pakai operator '?? null' agar tidak error jika id tidak ada
        $id = $_GET['id'] ?? null; // ini adalah siswa_id

        // 2. Cari data siswa dulu untuk dapat user_id
        $student = Siswa::find($id);

        // 3. Cek apakah siswanya ketemu di DB?
        if (! $student) {
            $this->redirect('siswa')->with('error', 'Data siswa tidak ditemukan!');
            exit;
        }

        // 4. Hapus data Siswa (Child) terlebih dahulu
        $deleteResult = Siswa::delete($id);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data siswa: " . $deleteResult['error'];
            $this->redirect('siswa')->with('error', $pesan);
            die();
        }

        // 5. Hapus data User (Parent)
        $deleteResult = User::delete($student['user_id']);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data user: " . $deleteResult['error'];
            $this->redirect('siswa')->with('error', $pesan);
            die();
        }

        $this->redirect('siswa')->with('success', 'Data Siswa & Akun Login berhasil dihapus!');
    }
}