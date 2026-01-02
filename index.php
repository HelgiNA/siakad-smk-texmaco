<?php

if (session_status() === PHP_SESSION_NONE) session_start();

require_once "app/Core/Database.php";
require_once "app/Core/Route.php";
require_once "app/Core/Middleware.php";

require_once "config/config.php";
require_once "app/helpers.php";

require_once "app/Controllers/HomeController.php";
require_once "app/Controllers/AuthController.php";
require_once "app/Controllers/UserController.php";
require_once "app/Controllers/SiswaController.php";
require_once "app/Controllers/GuruController.php";
require_once "app/Controllers/TahunAjaranController.php";
require_once "app/Controllers/KelasController.php";
require_once "app/Controllers/MapelController.php";
require_once "app/Controllers/JadwalController.php";
require_once "app/Controllers/PlottingController.php";
require_once "app/Controllers/AbsensiController.php";
require_once "app/Controllers/ValidasiController.php";

$routes = require_once "config/routes.php";

$routes->run();
