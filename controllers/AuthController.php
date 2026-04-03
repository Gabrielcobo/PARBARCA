<?php

namespace Controllers;
use Models\Usuario;

class AuthController
{
    private $db;
    private $usuarioModel;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->usuarioModel = new Usuario($db);
    }
    
    public function showLogin()
    {
        require_once 'views/login.php';
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $_SESSION['old_email'] = $email;
            header('Location: index.php?action=login');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Formato de correo electrónico inválido';
            $_SESSION['old_email'] = $email;
            header('Location: index.php?action=login');
            return;
        }
        
        $usuario = $this->usuarioModel->login($email, $password);
        
        if ($usuario) {
            unset($_SESSION['old_email']);
            
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['user_apellido'] = $usuario['apellido'];
            $_SESSION['user_rol'] = $usuario['rol'];
            
            // Mensaje de bienvenida 
            $_SESSION['welcome'] = '¡Bienvenido ' . $usuario['nombre'] . ' ' . $usuario['apellido'] . '!';
            
            // Redirigir según el rol
            switch ($usuario['rol']) {
                case 'admin':
                    header('Location: index.php?action=admin_dashboard');
                    break;
                case 'empleado':
                    header('Location: index.php?action=empleado_dashboard');
                    break;
                case 'cliente':
                    header('Location: index.php?action=cliente_dashboard');
                    break;
            }
        } else {
            $_SESSION['error'] = 'Correo electrónico o contraseña incorrectos';
            $_SESSION['old_email'] = $email;
            header('Location: index.php?action=login');
        }
    }
    
    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=login');
    }
    
    public function showRegistro()
    {
        require_once 'views/registro.php';
    }
    
    public function registro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=registro');
            return;
        }
        
        unset($_SESSION['old_nombre']);
        unset($_SESSION['old_apellido']);
        unset($_SESSION['old_cedula']);
        unset($_SESSION['old_telefono']);
        unset($_SESSION['old_email']);
        unset($_SESSION['old_direccion']);
        
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $cedula = trim($_POST['cedula'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        $_SESSION['old_nombre'] = $nombre;
        $_SESSION['old_apellido'] = $apellido;
        $_SESSION['old_cedula'] = $cedula;
        $_SESSION['old_telefono'] = $telefono;
        $_SESSION['old_email'] = $email;
        $_SESSION['old_direccion'] = $direccion;
        
        if (empty($nombre) || empty($apellido) || empty($cedula) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Los campos marcados con * son obligatorios';
            header('Location: index.php?action=registro');
            return;
        }
        
        if (!preg_match('/^[VEJPGvejpg]-?\d{5,10}$/', $cedula)) {
            $_SESSION['error'] = 'Formato de cédula inválido. Ejemplo: V-12345678';
            header('Location: index.php?action=registro');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Formato de correo electrónico inválido';
            header('Location: index.php?action=registro');
            return;
        }
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: index.php?action=registro');
            return;
        }
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            header('Location: index.php?action=registro');
            return;
        }
        
        $resultado = $this->usuarioModel->registrarCliente(
            $nombre, $apellido, $cedula, $telefono, $email, $direccion, $password
        );
        
        switch ($resultado) {
            case 'exito':
                unset($_SESSION['old_nombre']);
                unset($_SESSION['old_apellido']);
                unset($_SESSION['old_cedula']);
                unset($_SESSION['old_telefono']);
                unset($_SESSION['old_email']);
                unset($_SESSION['old_direccion']);
                
                $_SESSION['success'] = '¡Registro exitoso! Ahora puedes iniciar sesión';
                header('Location: index.php?action=login');
                break;
            case 'email_existe':
                $_SESSION['error'] = 'El correo electrónico ya está registrado';
                header('Location: index.php?action=registro');
                break;
            case 'cedula_existe':
                $_SESSION['error'] = 'La cédula ya está registrada';
                header('Location: index.php?action=registro');
                break;
            default:
                $_SESSION['error'] = 'Error al registrar. Intenta nuevamente';
                header('Location: index.php?action=registro');
                break;
        }
    }
}