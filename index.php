<?php
// index.php

// Kita load Controller secara manual (karena tanpa Composer autoloader)
require_once 'app/Controllers/ProductController.php';

use App\Controllers\ProductController;

// Ambil parameter 'page' dari URL (contoh: index.php?page=product)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$id   = isset($_GET['id']) ? $_GET['id'] : null; // Tangkap ID jika ada

$controller = new ProductController();

// LOGIKA ROUTING SEDERHANA
switch ($page) {
    case 'home':
        require_once __DIR__ . '/views/dashboard.php';
        break;

    // --- READ ---
    case 'product':
        $controller->index();
        break;

    // --- CREATE ---
    case 'product_create':
        $controller->create(); // Tampilkan Form
        break;
    case 'product_store':
        $controller->store(); // Proses Simpan
        break;

    // --- UPDATE ---
    case 'product_edit':
        $controller->edit($id); // Tampilkan Form Edit
        break;
    case 'product_update':
        $controller->update($id); // Proses Update
        break;

    // --- DELETE ---
    case 'product_delete':
        $controller->delete($id); // Proses Hapus
        break;

    default:
        echo "404 Not Found";
        break;
}