<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Siswa.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/Controller.php';

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
            'title'    => 'Data Siswa',
            'students' => Siswa::getAllWithUser(),
        ];
        $this->view('master/siswa/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Siswa Baru'];
        $this->view('master/siswa/create', $data);
    }

    public function store()
    {
        // 1. Ambil Data
        $data = [
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

        // 3. Buat User
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

        // 4. Buat Siswa (Pakai ID dari result user)
        $siswaResult = Siswa::create([
            'kelas_id'      => 1,
            'nis'           => $data['nis'],
            'nisn'          => $data['nisn'],
            'nama_lengkap'  => $data['nama_lengkap'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat'        => $data['alamat'],
            'user_id'       => $userResult['data']['user_id'], // <--- AMAN & JELAS
        ]);

        if ($siswaResult['status'] === false) {
            User::delete($userResult['data']['user_id']);

            $this->redirect('siswa/create')->with('error', 'Gagal menyimpan data siswa: ' . $siswaResult['error']);
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
        ];
        $this->view('master/siswa/edit', $data);
    }

    public function update()
    {
        // 1. Ambil ID (Wajib ada)
        $id = $_POST['id'] ?? null;

        if (! $id) {
            $this->redirect('siswa')->with('error', 'ID Siswa tidak ditemukan!');
            die();
        }

        // 2. Ambil Data
        $dataSiswa = [
            'nis'           => $_POST['nis'],
            'nisn'          => $_POST['nisn'],
            'nama_lengkap'  => $_POST['nama_lengkap'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'alamat'        => $_POST['alamat'],
        ];

        // 3. Validasi Simple
        if (in_array('', $dataSiswa)) {
            $this->redirect('siswa/edit?id=' . $id)->with('error', 'Semua data wajib diisi!');
            exit;
        }

        // 4. Eksekusi Update ke Tabel Siswa Saja
        $updateResult = Siswa::update($id, $dataSiswa);

        // CEK STATUS
        if ($updateResult['status'] === false) {
            // Kita bisa cek apakah errornya karena duplikat?
            $pesan = "Gagal update data siswa: " . $updateResult['error'];
            $this->redirect('siswa/edit?id=' . $id)->with('error', $pesan);
            die();
        }

        // 5. Selesai!
        $this->redirect('siswa')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null; // ini adalah siswa_id

        // 1. Cari data siswa dulu untuk dapat user_id
        $student = Siswa::find($id);

        if (! $student) {
            $this->redirect('siswa')->with('error', 'Data siswa tidak ditemukan!');
            exit;
        }

        $userId = $student['user_id'];

        // 2. Hapus data Siswa (Child) terlebih dahulu
        $deleteResult = Siswa::delete($id);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data siswa: " . $deleteResult['error'];
            $this->redirect('siswa')->with('error', $pesan);
            die();
        }

        // 3. Hapus data User (Parent)
        $deleteResult = User::delete($userId);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data user: " . $deleteResult['error'];
            $this->redirect('siswa')->with('error', $pesan);
            die();
        }

        $this->redirect('siswa')->with('success', 'Data Siswa & Akun Login berhasil dihapus!');
    }
}