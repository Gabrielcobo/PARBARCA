<?php

namespace Models;

use PDO;
use Core\Validator; 

class Usuario{
    private $db;
    private $table ='usuarios';
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this ->db =$db;
    }
    
    // AUTENTICACIÓN
    public function login($email, $password){
        $query ="SELECT * FROM {$this ->table} 
                  WHERE email =:email 
                  AND estado ='activo' 
                  LIMIT 1";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();
        
        $usuario =$stmt ->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['password'])){
            return $usuario;
        }
        return false;
    }
    
    // LISTAR USUARIOS POR ROL
    public function listarPorRol($rol, $incluirInactivos = false){
        $query ="SELECT * FROM {$this ->table} WHERE rol =:rol";
        
        if (!$incluirInactivos){
            $query .=" AND estado ='activo'";
        }
        
        $query .=" ORDER BY nombre ASC";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':rol', $rol);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Listar usuarios por rol excluyendo un ID específico
    public function listarPorRolExcluyendo($rol, $excluir_id, $incluirInactivos =false){
        $query ="SELECT * FROM {$this ->table} WHERE rol =:rol AND id !=:excluir_id";
        
        if (!$incluirInactivos){
            $query .=" AND estado ='activo'";
        }
        
        $query .=" ORDER BY nombre ASC";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':rol', $rol);
        $stmt ->bindParam(':excluir_id', $excluir_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar empleados excluyendo al admin actual
    public function listarEmpleadosExceptoAdmin($admin_id){
        $query ="SELECT * FROM {$this ->table} 
                  WHERE rol IN ('admin', 'empleado') 
                  AND id !=:admin_id
                  ORDER BY nombre ASC";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':admin_id', $admin_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // REGISTRAR USUARIOS
    public function registrarCliente($nombre, $apellido, $cedula, $telefono, $email, $direccion, $password){
        // VALIDAR CONTRASEÑA PRIMERO
        if (!Validator::validarPassword($password)){
            return 'error_password_invalida';
        }
        
        // Verificar email
        $query ="SELECT id FROM {$this ->table} WHERE email =:email";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();
        
        if ($stmt ->fetch()){
            return 'email_existe';
        }
        
        // Verificar cédula
        $query ="SELECT id FROM {$this ->table} WHERE cedula =:cedula";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':cedula', $cedula);
        $stmt ->execute();
        
        if ($stmt ->fetch()) {
            return 'cedula_existe';
        }
        
        // Registrar
        $query ="INSERT INTO {$this ->table} 
                (nombre, apellido, email, cedula, telefono, direccion, password, rol, estado) VALUES 
                (:nombre, :apellido, :email, :cedula, :telefono, :direccion, :password, 'cliente', 'activo')";
        
        $stmt  =$this ->db ->prepare($query);
        
        $hashedPassword =password_hash($password, PASSWORD_DEFAULT);
        
        $stmt ->bindParam(':nombre', $nombre);
        $stmt ->bindParam(':apellido', $apellido);
        $stmt ->bindParam(':email', $email);
        $stmt ->bindParam(':cedula', $cedula);
        $stmt ->bindParam(':telefono', $telefono);
        $stmt ->bindParam(':direccion', $direccion);
        $stmt ->bindParam(':password', $hashedPassword);
        
        if ($stmt ->execute()){
            return 'exito';
        }
        return 'error';
    }
    
    // Validar campos y registrar cliente
    public function registrarEmpleado($nombre, $apellido, $cedula, $telefono, $email, $direccion, $password, $rol = 'empleado'){
        // Verificar email
        $query ="SELECT id FROM {$this ->table} WHERE email =:email";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();
        
        if ($stmt ->fetch()){
            return 'email_existe';
        }
        
        // Verificar cédula
        $query ="SELECT id FROM {$this ->table} WHERE cedula =:cedula";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':cedula', $cedula);
        $stmt ->execute();
        
        if ($stmt ->fetch()){
            return 'cedula_existe';
        }

        // Registrar
        $query ="INSERT INTO {$this ->table} 
                  (nombre, apellido, email, cedula, telefono, direccion, password, rol, estado) VALUES 
                  (:nombre, :apellido, :email, :cedula, :telefono, :direccion, :password, :rol, 'activo')";
        
        $stmt  =$this ->db ->prepare($query);
        
        $hashedPassword =password_hash($password, PASSWORD_DEFAULT);
        
        $stmt ->bindParam(':nombre', $nombre);
        $stmt ->bindParam(':apellido', $apellido);
        $stmt ->bindParam(':email', $email);
        $stmt ->bindParam(':cedula', $cedula);
        $stmt ->bindParam(':telefono', $telefono);
        $stmt ->bindParam(':direccion', $direccion);
        $stmt ->bindParam(':password', $hashedPassword);
        $stmt ->bindParam(':rol', $rol);
        
        if ($stmt ->execute()){
            return 'exito';
        }
        return 'error';
    }
    

    // EDITAR USUARIOS
    public function editar($id, $nombre, $apellido, $email, $telefono, $direccion){
        // Verificar si el nuevo email ya existe en otro usuario
        $query ="SELECT id FROM {$this ->table} WHERE email =:email AND id !=:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':email', $email);
        $stmt ->bindParam(':id', $id);
        $stmt ->execute();
        
        if ($stmt ->fetch()){
            return false; // Email ya existe
        }
        
        $query ="UPDATE {$this ->table} 
                  SET nombre =:nombre, 
                      apellido =:apellido, 
                      email =:email, 
                      telefono =:telefono, 
                      direccion =:direccion 
                  WHERE id =:id";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->bindParam(':nombre', $nombre);
        $stmt ->bindParam(':apellido', $apellido);
        $stmt ->bindParam(':email', $email);
        $stmt ->bindParam(':telefono', $telefono);
        $stmt ->bindParam(':direccion', $direccion);
        
        return $stmt ->execute();
    }
    
    // Editar perfil (solo teléfono y dirección)
    public function editarPerfil($id, $telefono, $direccion){
        $query ="UPDATE {$this ->table} 
                  SET telefono =:telefono, direccion =:direccion 
                  WHERE id =:id";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->bindParam(':telefono', $telefono);
        $stmt ->bindParam(':direccion', $direccion);
        
        return $stmt ->execute();
    }
    
    //  CAMBIAR CONTRASEÑA
    public function cambiarPassword($id, $nuevaPassword){
        $query ="UPDATE {$this ->table} 
                  SET password =:password 
                  WHERE id =:id";
        
        $stmt  =$this ->db ->prepare($query);
        $hashedPassword =password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $stmt ->bindParam(':id', $id);
        $stmt ->bindParam(':password', $hashedPassword);
        
        return $stmt ->execute();
    }
    
    // Verificar contraseña actual antes de cambiarla
    public function verificarPassword($id, $passwordActual){
        $query ="SELECT password FROM {$this ->table} WHERE id =:id";
        $stmt  =$this ->db->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->execute();
        
        $usuario =$stmt ->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($passwordActual, $usuario['password'])){

            return true;
        }
        return false;
    }
    
    // Actualizar contraseña por email
    public function actualizarPasswordPorEmail($email, $nuevaPassword){
        $hashedPassword =password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $query ="UPDATE {$this ->table} SET password =:password WHERE email =:email";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':password', $hashedPassword);
        $stmt ->bindParam(':email', $email);

        return $stmt ->execute();
    }
    
    // BUSCAR USUARIOS
    public function buscarPorEmail($email){
        $query ="SELECT * FROM {$this ->table} WHERE email =:email AND estado ='activo' LIMIT 1";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();

        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener usuario por ID
    public function obtenerPorId($id){
        $query ="SELECT * FROM {$this ->table} WHERE id =:id LIMIT 1";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->execute();

        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
    
    // Verificar si un email ya está registrado 
    public function emailExiste($email){
        $query ="SELECT id FROM {$this ->table} WHERE email =:email";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();
        
        return $stmt ->fetch() ? true : false;
    }
    
    //  HABILITAR USUARIOS
    public function deshabilitar($id){
        $query ="UPDATE {$this ->table} SET estado ='inactivo' WHERE id =:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt  ->bindParam(':id', $id);

        return $stmt ->execute();
    }
    
    // Habilitar usuario
    public function habilitar($id){
        $query ="UPDATE {$this ->table} SET estado ='activo' WHERE id =:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt  ->bindParam(':id', $id);

        return $stmt ->execute();
    }
    
    // CONTAR USUARIOS
    public function contarPorRol($rol, $activos =true){
        $query ="SELECT COUNT(*) as total FROM {$this ->table} WHERE rol =:rol";
        
        if ($activos){
            $query .=" AND estado ='activo'";
        }
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':rol', $rol);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    // CONTAR CLIENTES ACTIVOS
    public function contarClientesActivos(){
        $query ="SELECT COUNT(*) as total 
                FROM {$this ->table} 
                WHERE rol ='cliente' AND estado ='activo'";
        $stmt =$this ->db ->prepare($query);
        $stmt ->execute();
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }
}