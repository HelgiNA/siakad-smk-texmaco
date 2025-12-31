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
        $users = User::getAll();

        $data = [
            'title' => 'Manajemen User',
            'users' => $users,
        ];

        $this->view('user/index', $data);
    }

    // MENAMPILKAN FORM TAMBAH
    public function create()
    {
        $data = ['title' => 'Tambah User Baru'];
        $this->view('user/create', $data);
    }

    // MENYIMPAN DATA USER BARU
    public function store()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role     = $_POST['role'];

        if (empty($username) || empty($password) || empty($role)) {
            $this->redirect('users/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'username' => $username,
            'password' => $hash,
            'role'     => $role,
        ];

        if (! User::create($data)) {
            $this->redirect('users/create')->with('error', 'Gagal menambahkan user');
            exit;
        }

        $this->redirect('users')->with('success', 'User berhasil ditambahkan');
    }

    // MENAMPILKAN FORM EDIT
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (! $id) {
            $this->redirect('users');
            exit;
        }

        $user = User::find($id);

        if (! $user) {
            $this->redirect('users')->with('error', 'User tidak ditemukan');
            exit;
        }

        $data = [
            'title' => 'Edit User',
            'user'  => $user,
        ];

        $this->view('user/edit', $data);
    }

    // PROSES UPDATE KE DATABASE
    public function update()
    {
        $id   = $_POST['user_id'];
        $data = [
            'username' => $_POST['username'],
            'role'     => $_POST['role'],
        ];

        if (empty($data['username']) || empty($data['role'])) {
            $this->redirect('users/edit?id=' . $id)->with('error', 'Username dan role wajib diisi!');
            exit;
        }

        if (! empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (! User::update($id, $data)) {
            $this->redirect('users/edit?id=' . $id)->with('error', 'Gagal memperbarui user');
            exit;
        }

        $this->redirect('users')->with('success', 'Data user berhasil diperbarui');
    }

    // MENGHAPUS USER
    public function destroy()
    {
        $id = $_GET['id'] ?? null;

        // Cegah Admin menghapus dirinya sendiri
        if ($id == $_SESSION['user_id']) {
            $this->redirect('users')->with('error', 'Anda tidak bisa menghapus akun sendiri!');
            exit;
        }

        if (! User::delete($id)) {
            $this->redirect('users')->with('error', 'Gagal menghapus user');
            exit;
        }

        $this->redirect('users')->with('success', 'User berhasil dihapus');
    }
}