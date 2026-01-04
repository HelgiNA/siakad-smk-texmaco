<?php
namespace App\Controllers;

// Pastikan load parent Controller
require_once __DIR__ . '/Controller.php';

class HomeController extends Controller
{
    public function index()
    {
        // Data yang ingin dikirim ke Dashboard (misal: Judul Halaman)
        $data = [
            'title'    => 'Dashboard Utama',
            'username' => 'Admin', // Contoh kirim data user
        ];

        // Panggil view dashboard menggunakan fungsi helper dari parent Controller
        $this->view('dashboard', $data);
    }
}
