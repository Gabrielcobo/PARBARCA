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
switch ($action){
    
    // AUTENTICACIÓN
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
        $controller->registro();
        break;
        
    case 'logout':
        $controller =new AuthController($db);
        $controller ->logout();
        break;

    case 'recuperar':
        require_once 'app/views/recuperar.php';
        break;

    // PERFIL segun el rol
    case 'admin_mi_perfil':
    case 'empleado_mi_perfil':
    case 'cliente_mi_perfil':
        $controller =new AuthController($db);
        $controller ->miPerfil();
        break;

    case 'perfil_editar':
        $controller =new AuthController($db);
        $controller ->editarPerfil();
        break;

    case 'cambiar_password':
        $controller =new AuthController($db);
        $controller ->cambiarPassword();
        break;

    // ADMIN
    case 'admin':
    case 'admin_dashboard':
        $controller =new AdminController($db);
        $controller ->dashboard();
        break;

    // EMPLEADO
    case 'empleado':
    case 'empleado_dashboard':
        $controller =new EmpleadoController($db);
        $controller ->dashboard();
        break;

    // CLIENTE
    case 'cliente':
    case 'cliente_dashboard':
        $controller =new ClienteController($db);
        $controller ->dashboard();
        break;
        
    case 'cliente_requerimientos':
        $controller =new ClienteController($db);
        $controller ->requerimientos();
        break;
        
    case 'cliente_requerimiento_crear_form':
        $controller =new ClienteController($db);
        $controller ->requerimientoCrearForm();
        break;
        
    case 'cliente_requerimiento_crear':
        $controller =new ClienteController($db);
        $controller ->requerimientoCrear();
        break;
        
    case 'cliente_requerimiento_detalle':
        $controller =new ClienteController($db);
        $controller ->requerimientoDetalle();
        break;
        
    case 'cliente_requerimiento_editar_form':
        $controller =new ClienteController($db);
        $controller ->requerimientoEditarForm();
        break;
        
    case 'cliente_requerimiento_editar':
        $controller =new ClienteController($db);
        $controller ->requerimientoEditar();
        break;
        
    case 'cliente_requerimiento_eliminar':
        $controller =new ClienteController($db);
        $controller ->requerimientoEliminar();
        break;
        
    case 'cliente_facturas':
        $controller =new ClienteController($db);
        $controller ->facturas();
        break;
        
    case 'cliente_factura_ver_ajax':
        $controller =new ClienteController($db);
        $controller ->facturaVerAjax();
        break;
        
    case 'cliente_factura_descargar':
        $controller =new ClienteController($db);
        $controller ->facturaDescargar();
        break;

    default:
        header('Location: index.php?action=login');
        exit();
        break;
}