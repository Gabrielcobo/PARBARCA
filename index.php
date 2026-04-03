<?php

session_start();

require_once 'core/Autoload.php';

// Iniciar autoload
\Core\Autoload::register();

use Config\Database;
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\EmpleadoController;
use Controllers\ClienteController;

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener acción de la URL
$action = $_GET['action'] ?? 'login';

// Enrutador 
switch ($action) {
    //  Autentificacion
    case 'login':
        $controller = new AuthController($db);
        $controller->showLogin();
        break;
    case 'login_post':
        $controller = new AuthController($db);
        $controller->login();
        break;
    case 'registro':
        $controller = new AuthController($db);
        $controller->showRegistro();
        break;
    case 'registro_post':
        $controller = new AuthController($db);
        $controller->registro();
        break;
    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;
    
    default:
        header('Location: index.php?action=login');
        break;
}