<?php

namespace Controllers;

use Models\Usuario;
use Core\Validator;

class AuthController{
    private $db;
    private $usuarioModel;
    
    // conexion 
    public function __construct($db){
        $this ->db =$db;
        $this ->usuarioModel =new Usuario($db);
    }
    
    // login 
    public function showLogin(){

        require_once 'app/views/login.php';
    }
    
    // Iniciar sesion
    public function login(){
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){

            header('Location: index.php?action=login');
            return;
        }
        
        $email =Validator::sanitizarInput($_POST['email'] ?? '');
        $password =$_POST['password'] ?? '';
        
        if (!Validator::validarEmail($email)){
            $_SESSION['error'] ='Formato de correo electrónico inválido';
            $_SESSION['old_email'] =$email;

            header('Location: index.php?action=login');
            return;
        }
        
        if (!Validator::required($password)){
            $_SESSION['error'] ='La contraseña es requerida';
            $_SESSION['old_email'] =$email;

            header('Location: index.php?action=login');
            return;
        }
        
        $usuario =$this ->usuarioModel ->login($email, $password);
        
        if ($usuario){
            unset($_SESSION['old_email']);
            $_SESSION['user_id'] =$usuario['id'];
            $_SESSION['user_nombre'] =$usuario['nombre'];
            $_SESSION['user_apellido'] =$usuario['apellido'] ?? '';
            $_SESSION['user_rol'] =$usuario['rol'];
            $_SESSION['user_email'] =$usuario['email'];
            session_regenerate_id(true);
            
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

    // cerrar sesion
    public function logout(){
        session_destroy();

        header('Location: index.php?action=login');
    }
    
    // registrar
    public function showRegistro(){
        require_once 'app/views/registro.php';
    }
    
    // vista para registar
    public function registro(){
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){

            header('Location: index.php?action=registro');
            return;
        }
        
        unset($_SESSION['old_nombre'], 
            $_SESSION['old_apellido'], 
            $_SESSION['old_cedula'],
            $_SESSION['old_telefono'], 
            $_SESSION['old_email'], 
            $_SESSION['old_direccion']);
        
        $nombre =Validator::sanitizarInput($_POST['nombre'] ?? '');
        $apellido =Validator::sanitizarInput($_POST['apellido'] ?? '');
        $cedula =Validator::sanitizarInput($_POST['cedula'] ?? '');
        $telefono =Validator::sanitizarInput($_POST['telefono'] ?? '');
        $email =Validator::sanitizarInput($_POST['email'] ?? '');
        $direccion =Validator::sanitizarInput($_POST['direccion'] ?? '');
        $password =$_POST['password'] ?? '';
        $confirmPassword =$_POST['confirm_password'] ?? '';
        
        $_SESSION['old_nombre'] =$nombre;
        $_SESSION['old_apellido'] =$apellido;
        $_SESSION['old_cedula'] =$cedula;
        $_SESSION['old_telefono'] =$telefono;
        $_SESSION['old_email'] =$email;
        $_SESSION['old_direccion'] =$direccion;
        
        if (!Validator::required($nombre) || !Validator::required($apellido) || 
            !Validator::required($cedula) || !Validator::required($email) || 
            !Validator::required($password)){
            $_SESSION['error'] ='Los campos marcados con * son obligatorios';

            header('Location: index.php?action=registro');
            return;
        }
        
        if (!Validator::validarCedula($cedula)){
            $_SESSION['error'] ='Formato de cédula inválido. Ejemplo: V-12345678';

            header('Location: index.php?action=registro');
            return;
        }
        
        if (!Validator::validarEmail($email)){
            $_SESSION['error'] ='Formato de correo electrónico inválido';

            header('Location: index.php?action=registro');
            return;
        }
        
        if (!Validator::validarPassword($password)){
            $_SESSION['error'] =Validator::getPasswordErrorMessage();

            header('Location: index.php?action=registro');
            return;
        }
        
        if ($password !==$confirmPassword){
            $_SESSION['error'] ='Las contraseñas no coinciden';

            header('Location: index.php?action=registro');
            return;
        }
        
        $resultado =$this ->usuarioModel ->registrarCliente(
            $nombre, $apellido, $cedula, $telefono, $email, $direccion, $password
        );

        switch ($resultado){
            case 'exito':
                $_SESSION['success'] ='Registro exitoso. Ahora puedes iniciar sesión.';
                header('Location: index.php?action=login');
                break;
            case 'email_existe':
                $_SESSION['error'] ='El correo electrónico ya está registrado';
                header('Location: index.php?action=registro');
                break;
            case 'cedula_existe':
                $_SESSION['error'] ='La cédula ya está registrada';
                header('Location: index.php?action=registro');
                break;
            default:
                $_SESSION['error'] ='Error al registrar. Intenta nuevamente';
                header('Location: index.php?action=registro');
                break;
        }
    }
    
    // perfil de loa roles
    public function miPerfil(){
        $usuario =$this ->usuarioModel ->obtenerPorId($_SESSION['user_id']);
        $rol =$_SESSION['user_rol'] ?? 'cliente';
        
        switch ($rol){
            case 'admin': 
                require_once 'app/views/admin/mi_perfil.php'; 
                break;
            case 'empleado': 
                require_once 'app/views/empleado/mi_perfil.php'; 
                break;
            default: 
            require_once 'app/views/cliente/mi_perfil.php'; 
            break;
        }
    }
    
    // editar perfil por rol
    public function editarPerfil(){
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){
            $this ->redirigirPerfil();
            return;
        }
        
        $nombre =Validator::sanitizarInput($_POST['nombre'] ?? '');
        $apellido =Validator::sanitizarInput($_POST['apellido'] ?? '');
        $cedula =Validator::sanitizarInput($_POST['cedula'] ?? '');
        $telefono =Validator::sanitizarInput($_POST['telefono'] ?? '');
        $direccion =Validator::sanitizarInput($_POST['direccion'] ?? '');
        $userId =$_SESSION['user_id'] ?? 0;
        
        if (!Validator::required($nombre) || !Validator::required($apellido) || !Validator::required($cedula)){
            $_SESSION['error'] ='Los campos nombre, apellido y cédula son obligatorios';
            $this ->redirigirPerfil();
            return;
        }
        
        if (!Validator::validarCedula($cedula)){
            $_SESSION['error'] ='Formato de cédula inválido';
            $this ->redirigirPerfil();
            return;
        }
        
        if (!empty($telefono) && !Validator::validarTelefono($telefono)){
            $_SESSION['error'] ='Formato de teléfono inválido';
            $this ->redirigirPerfil();
            return;
        }
        
        $resultado =$this ->usuarioModel ->editar($userId, $nombre, $apellido, $_SESSION['user_email'] ?? '', $telefono, $direccion);
        
        if ($resultado){
            $_SESSION['success'] ='Perfil actualizado correctamente';
        } else{
            $_SESSION['error'] ='Error al actualizar el perfil';
        }
        $this ->redirigirPerfil();
    }
    
    // cambiar contraseña de los perfiles por rol
    public function cambiarPassword(){
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){
            $this ->redirigirPerfil();
            return;
        }
        
        $actual =$_POST['password_actual'] ?? '';
        $nueva =$_POST['password_nueva'] ?? '';
        $confirmar =$_POST['password_confirmar'] ?? '';
        $userId =$_SESSION['user_id'] ?? 0;
        
        if (!Validator::required($actual) || !Validator::required($nueva) || !Validator::required($confirmar)){
            $_SESSION['error'] ='Todos los campos son obligatorios';
            $this ->redirigirPerfil();
            return;
        }
        
        if (!Validator::validarPassword($nueva)){
            $_SESSION['error'] =Validator::getPasswordErrorMessage();
            $this ->redirigirPerfil();
            return;
        }
        
        if ($nueva !==$confirmar){
            $_SESSION['error'] ='Las contraseñas nuevas no coinciden';
            $this ->redirigirPerfil();
            return;
        }
        
        if (!$this ->usuarioModel ->verificarPassword($userId, $actual)){
            $_SESSION['error'] ='Contraseña actual incorrecta';
            $this ->redirigirPerfil();
            return;
        }
        
        if ($this ->usuarioModel ->cambiarPassword($userId, $nueva)){
            $_SESSION['success'] ='Contraseña actualizada correctamente';
        } else{
            $_SESSION['error'] ='Error al actualizar la contraseña';
        }
        $this ->redirigirPerfil();
    }
    
    // vista de los perfile3s de los roles
    private function redirigirPerfil(){
        $rol =$_SESSION['user_rol'] ?? 'cliente';
        switch ($rol){
            case 'admin': 
                header('Location: index.php?action=admin_mi_perfil'); 
                break;
            case 'empleado': 
                header('Location: index.php?action=empleado_mi_perfil'); 
                break;
            default: 
            header('Location: index.php?action=cliente_mi_perfil'); 
            break;
        }
        exit();
    }
}