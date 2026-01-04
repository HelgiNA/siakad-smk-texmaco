<?php
namespace App\Controllers;

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Guru.php';
require_once __DIR__ . '/../Models/User.php';

use App\Core\Database;
use App\Models\Guru;
use App\Models\User;
use Exception;

class GuruController extends Controller
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db   = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Data Guru',
            'gurus' => Guru::getAllWithUser(),
        ];
        $this->view('master/guru/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Guru Baru'];
        $this->view('master/guru/create', $data);
    }

    public function store()
    {
        // 1. Ambil Data
        $data = [
            'nip'          => $_POST['nip'],
            'nama_lengkap' => $_POST['nama_lengkap'],
        ];

        // 2. Validasi Simple
        if (in_array('', $data)) {
            $this->redirect('guru/create')->with('error', 'Semua kolom wajib diisi!');
            exit;
        }

        try {
            // 3. Start Transaction
            $this->conn->beginTransaction();

            // A. Insert ke Users
            // Note: Kita butuh cara insert tanpa commit. Model::create() melakukan commit sendiri.
            // Kita bypass Model::create() untuk transaction atomicity atau kita modifikasi Model.

            // OPSI TERBAIK: Manual SQL di sini agar terkontrol penuh dalam transaction ini.

            $queryUser = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
            $stmtUser  = $this->conn->prepare($queryUser);
            $stmtUser->execute([
                'username' => $data['nip'],
                'password' => password_hash($data['nip'], PASSWORD_DEFAULT),
                'role'     => 'Guru',
            ]);

            $userId = $this->conn->lastInsertId();

            // B. Insert ke Guru
            $queryGuru = "INSERT INTO guru (user_id, nip, nama_lengkap) VALUES (:user_id, :nip, :nama_lengkap)";
            $stmtGuru  = $this->conn->prepare($queryGuru);
            $stmtGuru->execute([
                'user_id'      => $userId,
                'nip'          => $data['nip'],
                'nama_lengkap' => $data['nama_lengkap'],
            ]);

            // C. Commit Transaction
            $this->conn->commit();

            $this->redirect('guru')->with('success', 'Data Guru berhasil disimpan!');

        } catch (Exception $e) {
            $this->conn->rollBack();

            $msg = $e->getMessage();
            if (strpos($msg, 'Duplicate entry') !== false) {
                $msg = "NIP/Username " . $data['nip'] . " sudah terdaftar!";
            }

            $this->redirect('guru/create')->with('error', 'Gagal menyimpan: ' . $msg);
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('guru')->with('error', 'ID tidak ditemukan');
            exit;
        }

        $guru = Guru::find($id);
        if (! $guru) {
            $this->redirect('guru')->with('error', 'Data tidak ditemukan');
            exit;
        }

        $data = [
            'title' => 'Edit Data Guru',
            'guru'  => $guru,
        ];
        $this->view('master/guru/edit', $data);
    }

    public function update()
    {
        $id = $_POST['guru_id'] ?? null;
        if (! $id) {
            $this->redirect('guru')->with('error', 'ID tidak valid');
            exit;
        }

        $data = [
            'nip'          => $_POST['nip'],
            'nama_lengkap' => $_POST['nama_lengkap'],
        ];

        // Validasi
        if (in_array('', $data)) {
            $this->redirect('guru/edit?id=' . $id)->with('error', 'Semua kolom wajib diisi');
            exit;
        }

        // Update Guru Saja (Simple)
        $result = Guru::update($id, $data);

        if ($result['status']) {
            $this->redirect('guru')->with('success', 'Data Guru berhasil diperbarui');
        } else {
            $this->redirect('guru/edit?id=' . $id)->with('error', 'Gagal update: ' . $result['error']);
        }
    }

    public function destroy()
    {
        // 1. Cari ID
        $id = $_GET['id'] ?? null;
        if (! $id) {
            $this->redirect('guru')->with('error', 'ID tidak valid');
            exit;
        }

        // 2. Cari data guru
        $guru = Guru::find($id);

        // 3. Cek apakah guru ketemu di DB?
        if (! $guru) {
            $this->redirect('guru')->with('error', 'Data guru tidak ditemukan!');
            exit;
        }

        // 4. Hapus data Guru (Child) terlebih dahulu
        $deleteResult = Guru::delete($id);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data guru: " . $deleteResult['error'];
            $this->redirect('guru')->with('error', $pesan);
            die();
        }

        // 5. Hapus data User (Parent)
        $deleteResult = User::delete($guru['user_id']);

        // CEK STATUS
        if ($deleteResult['status'] === false) {
            $pesan = "Gagal menghapus data user: " . $deleteResult['error'];
            $this->redirect('guru')->with('error', $pesan);
            die();
        }

        $this->redirect('guru')->with('success', 'Data Guru & Akun Login berhasil dihapus!');
    }
}