<?php
namespace App\Controllers;

class Controller
{

    // Fungsi helper buatan sendiri agar mirip Laravel
    public function view($viewPath, $data = [])
    {
        extract($data); // Mengubah array ['title' => 'Halo'] menjadi variabel $title = 'Halo'
        require_once __DIR__ . '/../../views/' . $viewPath . '.php';
    }

    // Helper Redirect (Agar kodingan lebih bersih)
    public function redirect($url)
    {
        return new class($url)
        {
            private $url;

            public function __construct($url)
            {
                $this->url = $url;
            }

            /**
             * Fungsi berantai untuk set session flash dan eksekusi redirect
             */
            public function with($key, $value)
            {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Simpan pesan ke session flash
                $_SESSION['flash'] = [
                    'type'    => $key,
                    'message' => $value,
                ];

                // Lakukan pengalihan halaman
                header("Location: " . BASE_URL . '/' . $this->url);
                exit();
            }
        };
    }
}