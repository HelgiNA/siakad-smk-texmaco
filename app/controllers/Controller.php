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
    public function redirect($page)
    {
        header("Location: index.php?page=" . $page);
        exit;
    }
}