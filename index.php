<?php

session_start();

// Cargar autoload
require_once 'app/core/Autoload.php';

// Iniciar autoload
Core\Autoload::register();

use Config\Database;
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\EmpleadoController;
use Controllers\ClienteController;

// Obtener conexión de la BD
$db =Database::getInstance() ->getConnection();

// Definir BASE_URL
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/');

// Obtener acción de la URL
$action =$_GET['action'] ?? 'login';

// Enrutamiento
switch ($action) {
    
    //  AUTENTICACIÓN
    case 'login':
        $controller =new AuthController($db);
        $controller ->showLogin();
        break;
    case 'login_post':
        $controller =new AuthController($db);
        $controller ->login();
        break;
    case 'registro':
        $controller =new AuthController($db);
        $controller ->showRegistro();
        break;
    case 'registro_post':
        $controller =new AuthController($db);
        $controller ->registro();
        break;
    case 'logout':
        $controller =new AuthController($db);
        $controller ->logout();
        break;

    // Admin
    case 'admin_dashboard':
        $controller =new Controllers\AdminController($db);
        $controller ->dashboard();
        break;

    // Empleado
    case 'empleado_dashboard':
        $controller =new Controllers\EmpleadoController($db);
        $controller ->dashboard();
        break;

    // Cliente
    case 'cliente_dashboard':
        $controller =new Controllers\ClienteController($db);
        $controller ->dashboard();
        break;

    default:
        header('Location: index.php?action=login');
        break;
}