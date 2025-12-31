<?php

session_start();

require_once 'app/Core/database.php';
require_once 'app/Core/Route.php';
require_once 'app/Core/Middleware.php';

require_once 'config/config.php';

require_once 'app/Controllers/HomeController.php';
require_once 'app/Controllers/AuthController.php';
require_once 'app/Controllers/UserController.php';
require_once 'app/Controllers/SiswaController.php';
require_once 'app/Controllers/GuruController.php';

$routes = require_once 'config/routes.php';

$routes->run();
