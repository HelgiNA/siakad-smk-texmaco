<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;

$routes = new Route();

$routes->get('/', [HomeController::class, 'index'], 'authMiddleware');
$routes->get('/dashboard', [HomeController::class, 'index'], 'authMiddleware');
$routes->get('/login', [AuthController::class, 'login']);
$routes->post('/login', [AuthController::class, 'submitLogin']);
$routes->get('/logout', [AuthController::class, 'logout'], 'authMiddleware');

return $routes;