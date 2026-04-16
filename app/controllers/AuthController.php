<?php

namespace Controllers;

use Models\Usuario;
use Core\Validator;

class AuthController{
    private $db;
    private $usuarioModel;
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this->db =$db;
        $this->usuarioModel =new Usuario($db);
    }
    
    // Carga de vista login
    public function showLogin(){
        require_once 'app/views/login.php';
    }
    
    public function login(){
    // Validar que sea POST
    if ($_SERVER['REQUEST_METHOD'] !=='POST') {
        header('Location: index.php?action=login');
        return;
    }
    
    // Limpiar datos de entrada
    $email =trim($_POST['email'] ?? '');
    $password =$_POST['password'] ?? '';
    
    // Validar campos
    if (empty($email) || empty($password)){
        $_SESSION['error'] ='Todos los campos son obligatorios';
        $_SESSION['old_email'] =$email;
        header('Location: index.php?action=login');
        return;
    }
    
    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['error'] ='Formato de correo electrónico inválido';
        $_SESSION['old_email'] =$email;
        header('Location: index.php?action=login');
        return;
    }
    
    // Intentar iniciar sesión
    $usuario =$this ->usuarioModel ->login($email, $password);
    
    if ($usuario){
        unset($_SESSION['old_email']);
        
        $_SESSION['user_id'] =$usuario['id'];
        $_SESSION['user_nombre'] =$usuario['nombre'];
        $_SESSION['user_apellido'] =$usuario['apellido'] ?? '';
        $_SESSION['user_rol'] =$usuario['rol'];
        $_SESSION['user_email'] =$usuario['email'];
        
        // Redirigir DIRECTAMENTE al dashboard según el rol
        switch ($usuario['rol']){
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
        exit();
        
    } else {
        $_SESSION['error'] ='Correo electrónico o contraseña incorrectos';
        $_SESSION['old_email'] =$email;
        header('Location: index.php?action=login');
    }
}

    // Cerrar sesión
    public function logout(){
        session_destroy();
        header('Location: index.php?action=login');
    }
    
    //  Cargar la vista del registro
    public function showRegistro(){
        require_once 'app/views/registro.php';
    }
    
    // Registro de cliente 
    public function registro(){
        // Validar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){
            header('Location: index.php?action=registro');
            return;
        }
        
        // Limpiar datos de entrada
        unset($_SESSION['old_nombre']);
        unset($_SESSION['old_apellido']);
        unset($_SESSION['old_cedula']);
        unset($_SESSION['old_telefono']);
        unset($_SESSION['old_email']);
        unset($_SESSION['old_direccion']);
        
        // Recoger y limpiar datos del formulario
        $nombre =trim($_POST['nombre'] ?? '');
        $apellido =trim($_POST['apellido'] ?? '');
        $cedula =trim($_POST['cedula'] ?? '');
        $telefono =trim($_POST['telefono'] ?? '');
        $email =trim($_POST['email'] ?? '');
        $direccion =trim($_POST['direccion'] ?? '');
        $password =$_POST['password'] ?? '';
        $confirm_password =$_POST['confirm_password'] ?? '';
        
        // Guardar datos en sesión para prellenar el formulario en caso de error
        $_SESSION['old_nombre'] =$nombre;
        $_SESSION['old_apellido'] =$apellido;
        $_SESSION['old_cedula'] =$cedula;
        $_SESSION['old_telefono'] =$telefono;
        $_SESSION['old_email'] =$email;
        $_SESSION['old_direccion'] =$direccion;
        
        // Validar campos obligatorios
        if (empty($nombre) || empty($apellido) || empty($cedula) || empty($email) || empty($password)){
            $_SESSION['error'] ='Los campos marcados con * son obligatorios';
            header('Location: index.php?action=registro');
            return;
        }
        
        // Validar cedula
        if (!preg_match('/^[VEJPGvejpg]-?\d{5,10}$/', $cedula)){
            $_SESSION['error'] ='Formato de cédula inválido. Ejemplo: V-12345678';
            header('Location: index.php?action=registro');
            return;
        }
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] ='Formato de correo electrónico inválido';
            header('Location: index.php?action=registro');
            return;
        }
        
        // Validación de contraseña(mayúscula, número, carácter especial)
        if (!Validator::validarPassword($password)){
            $_SESSION['error'] =Validator::getPasswordErrorMessage();
            header('Location: index.php?action=registro');
            return;
        }
        
        // Validar que las contraseñas coincidan
        if ($password !==$confirm_password) {
            $_SESSION['error'] ='Las contraseñas no coinciden';
            header('Location: index.php?action=registro');
            return;
        }

        // Intentar registrar el cliente y manejar los posibles resultados
        $resultado =$this ->usuarioModel ->registrarCliente(
            $nombre, $apellido, $cedula, $telefono, $email, $direccion, $password);

            switch ($resultado){
                case 'exito':
                    // Registro exitoso, redirigir al login con mensaje de éxito
                    break;
                case 'email_existe':
                    $_SESSION['error'] ='El correo electrónico ya está registrado';
                    break;
                case 'cedula_existe':
                    $_SESSION['error'] ='La cédula ya está registrada';
                    break;
                case 'error_password_invalida':
                    $_SESSION['error'] =Validator::getPasswordErrorMessage();
                    break;
                default:
                    $_SESSION['error'] ='Error al registrar. Intenta nuevamente';
                    break;
            }
    }
}