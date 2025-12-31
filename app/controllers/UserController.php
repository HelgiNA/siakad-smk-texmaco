<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/Controller.php';
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        // CEK OTORISASI (LEVEL ADMIN)
        // Sesuai ADPL: Hanya role 'Admin' yang boleh akses halaman ini
        // Jika role bukan Admin, tendang ke dashboard
        if (! isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    // MENAMPILKAN SEMUA USER
    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => User::getAll(),
        ];

        $this->view('users/index', $data);
    }

    // MENAMPILKAN FORM TAMBAH
    public function create()
    {
        $data = ['title' => 'Tambah User Baru'];
        $this->view('users/create', $data);
    }

    // MENYIMPAN DATA USER BARU
    public function store()
    {
        // 1. Ambil Data dari Form
        $data = [
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'role'     => $_POST['role'],
        ];

        // 2. Validasi Simple
        if (in_array('', $data)) {
            $this->redirect('users/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);

        $data['password'] = $hash;

        // 3. Buat User
        $userResult = User::create($data);

        // CEK STATUS
        if ($userResult['status'] === false) {
            // Kita bisa cek apakah errornya karena duplikat?
            $pesan = "Gagal membuat akun user: " . $userResult['error'];
            $this->redirect('users/create')->with('error', $pesan);
            die();
        }

        $this->redirect('users')->with('success', 'User berhasil ditambahkan');
    }

    // MENAMPILKAN FORM EDIT
    public function edit()
    {
        // 1. Ambil ID dari URL (?id=1) secara manual
        // Kita pakai operator '?? null' agar tidak error jika id tidak ada
        $id = $_GET['id'] ?? null;

        // 2. Validasi sederhana
        if (! $id) {
            $this->redirect('users')->with('error', 'ID User tidak ditemukan!');
            exit;
        }

        // 3. Panggil Model
        $user = User::find($id);

        // 4. Cek apakah user ketemu di DB?
        if (! $user) {
            $this->redirect('users')->with('error', 'User tidak ditemukan');
            exit;
        }

        $data = [
            'title' => 'Edit User',
            'user'  => $user,
        ];

        $this->view('users/edit', $data);
    }

    // PROSES UPDATE KE DATABASE
    public function update()
    {
        // 1. Ambil ID
        $id = $_POST['user_id'] ?? null;

        // 2. Validasi sederhana
        if (! $id) {
            $this->redirect('users')->with('error', 'ID User tidak ditemukan!');
            die();
        }

        // 3. Ambil Data
        $data = [
            'username' => $_POST['username'],
            'role'     => $_POST['role'],
        ];

        // 4. Validasi Simple
        if (in_array('', $data)) {
            $this->redirect('users/edit?id=' . $id)->with('error', 'Semua data wajib diisi!');
            die();
        }

        // 5. Update Password Jika Ada
        if (! empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // 6. Update User
        if (! User::update($id, $data)) {
            $this->redirect('users/edit?id=' . $id)->with('error', 'Gagal memperbarui user');
            die();
        }

        // 7. Redirect
        $this->redirect('users')->with('success', 'Data user berhasil diperbarui');
    }

    // MENGHAPUS USER
    public function destroy()
    {
        $id = $_GET['id'] ?? null;

        // 1. Cek ID
        if (! $id) {
            $this->redirect('users')->with('error', 'ID User tidak ditemukan!');
            exit;
        }

        // 2. Cek Admin
        if ($id == $_SESSION['user_id']) {
            $this->redirect('users')->with('error', 'Anda tidak bisa menghapus akun sendiri!');
            exit;
        }

        // 3. Hapus User
        $deleteResult = User::delete($id);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data user: " . $deleteResult['error'];
            $this->redirect('users')->with('error', $pesan);
            die();
        }

        // 4. Redirect
        $this->redirect('users')->with('success', 'User berhasil dihapus');
    }
}