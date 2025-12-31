<?php

use App\Controllers\AuthController;
use App\Controllers\GuruController;
use App\Controllers\HomeController;
use App\Controllers\SiswaController;
use App\Controllers\UserController;
use App\Core\Route;

$routes = new Route();

$routes->get('/', [HomeController::class, 'index'], 'authMiddleware');
$routes->get('/dashboard', [HomeController::class, 'index'], 'authMiddleware');

$routes->get('/login', [AuthController::class, 'login']);
$routes->post('/login', [AuthController::class, 'submitLogin']);
$routes->get('/logout', [AuthController::class, 'logout'], 'authMiddleware');

// MANAJEMEN USER (Hanya Admin)
// middleware 'authMiddleware' dipasang sebagai lapisan keamanan pertama (harus login dulu)
$routes->get('/users', [UserController::class, 'index'], 'authMiddleware');
$routes->get('/users/create', [UserController::class, 'create'], 'authMiddleware');
$routes->post('/users/store', [UserController::class, 'store'], 'authMiddleware');
$routes->get('/users/edit', [UserController::class, 'edit'], 'authMiddleware');
$routes->post('/users/update', [UserController::class, 'update'], 'authMiddleware');
$routes->get('/users/delete', [UserController::class, 'destroy'], 'authMiddleware');

$routes->get('/siswa', [SiswaController::class, 'index'], 'authMiddleware');
$routes->get('/siswa/create', [SiswaController::class, 'create', 'authMiddleware']);
$routes->post('/siswa/store', [SiswaController::class, 'store'], 'authMiddleware');
$routes->get('/siswa/edit', [SiswaController::class, 'edit'], 'authMiddleware');
$routes->post('/siswa/update', [SiswaController::class, 'update'], 'authMiddleware');
$routes->get('/siswa/delete', [SiswaController::class, 'destroy'], 'authMiddleware');

// MANAJEMEN GURU
$routes->get('/guru', [GuruController::class, 'index'], 'authMiddleware');
$routes->get('/guru/create', [GuruController::class, 'create'], 'authMiddleware');
$routes->post('/guru/store', [GuruController::class, 'store'], 'authMiddleware');
$routes->get('/guru/edit', [GuruController::class, 'edit'], 'authMiddleware');      // NEW
$routes->post('/guru/update', [GuruController::class, 'update'], 'authMiddleware'); // NEW
$routes->get('/guru/delete', [GuruController::class, 'destroy'], 'authMiddleware'); // NEW

return $routes;