<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/Controller.php';
use App\Models\Product;

class ProductController extends Controller
{
    // --- HALAMAN INDEX ---
    public function index()
    {
        $productModel = new Product();
        $products     = $productModel->getAll();
        $this->view('product/index', ['title' => 'Daftar Produk', 'products' => $products]);
    }

    // --- FITUR CREATE ---
    public function create()
    {
        // Tampilkan Form Tambah
        $this->view('product/create', ['title' => 'Tambah Produk']);
    }

    public function store()
    {
        // Proses Simpan Data dari POST
        $productModel = new Product();
        $productModel->create($_POST); // Kirim data $_POST ke Model
        $this->redirect('product');
    }

    // --- FITUR EDIT ---
    public function edit($id)
    {
        $productModel = new Product();
        $product      = $productModel->find($id); // Ambil data lama
        $this->view('product/edit', ['title' => 'Edit Produk', 'product' => $product]);
    }

    public function update($id)
    {
        $productModel = new Product();
        $productModel->update($id, $_POST);
        $this->redirect('product');
    }

    // --- FITUR DELETE ---
    public function delete($id)
    {
        $productModel = new Product();
        $productModel->delete($id);
        $this->redirect('product');
    }
}