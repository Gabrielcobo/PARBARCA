<?php

namespace Models;
use PDO;

class Usuario
{
    private $db;
    private $table = 'usuarios';
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    //  Autenticación
    public function login($email, $password)
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE email = :email 
                  AND estado = 'activo' 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }
    
    //  Listar usuarios por rol
    public function listarPorRol($rol, $incluirInactivos = false)
    {
        $query = "SELECT * FROM {$this->table} WHERE rol = :rol";
        
        if (!$incluirInactivos) {
            $query .= " AND estado = 'activo'";
        }
        
        $query .= " ORDER BY nombre ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Registrar cliente
    public function registrarCliente($nombre, $apellido, $cedula, $telefono, $email, $direccion, $password)
    {
        // Verificar email
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return 'email_existe';
        }
        
        // Verificar cédula
        $query = "SELECT id FROM {$this->table} WHERE cedula = :cedula";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return 'cedula_existe';
        }
        
        // Registrar
        $query = "INSERT INTO {$this->table} 
                  (nombre, apellido, email, cedula, telefono, direccion, password, rol, estado) 
                  VALUES 
                  (:nombre, :apellido, :email, :cedula, :telefono, :direccion, :password, 'cliente', 'activo')";
        
        $stmt = $this->db->prepare($query);
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':password', $hashedPassword);
        
        if ($stmt->execute()) {
            return 'exito';
        }
        
        return 'error';
    }
    
    // Registrar empleado (solo admin)
    public function registrarEmpleado($nombre, $apellido, $cedula, $telefono, $email, $direccion, $password)
    {
        // Verificar email
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return 'email_existe';
        }
        
        // Verificar cédula
        $query = "SELECT id FROM {$this->table} WHERE cedula = :cedula";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return 'cedula_existe';
        }
        
        // Registrar
        $query = "INSERT INTO {$this->table} 
                  (nombre, apellido, email, cedula, telefono, direccion, password, rol, estado) 
                  VALUES 
                  (:nombre, :apellido, :email, :cedula, :telefono, :direccion, :password, 'empleado', 'activo')";
        
        $stmt = $this->db->prepare($query);
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':password', $hashedPassword);
        
        if ($stmt->execute()) {
            return 'exito';
        }
        
        return 'error';
    }
    
    //  Editar usuario
    public function editar($id, $nombre, $apellido, $email, $telefono, $direccion)
    {
        $query = "UPDATE {$this->table} 
                  SET nombre = :nombre, 
                      apellido = :apellido, 
                      email = :email, 
                      telefono = :telefono, 
                      direccion = :direccion 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        
        return $stmt->execute();
    }
    
    //  Cambiar contraseña
    public function cambiarPassword($id, $nuevaPassword)
    {
        $query = "UPDATE {$this->table} 
                  SET password = :password 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $hashedPassword);
        
        return $stmt->execute();
    }
    
    //  Deshabilitar usuario 
    public function deshabilitar($id)
    {
        $query = "UPDATE {$this->table} 
                  SET estado = 'inactivo' 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    //  Habilitar usuario
    public function habilitar($id)
    {
        $query = "UPDATE {$this->table} 
                  SET estado = 'activo' 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    //  Obtener usuario por ID
    public function obtenerPorId($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Contar usuarios por rol
    public function contarPorRol($rol, $activos = true)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE rol = :rol";
        
        if ($activos) {
            $query .= " AND estado = 'activo'";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}