<?php

namespace Config;

use PDO;
use PDOException;

class Database{
    private static $instance =null;
    private $conn;
    
    // Constructor  
    private function __construct(){
        $this ->connect();
    }
    
    // Obtener la única instancia
    public static function getInstance(){
        if (self::$instance ===null){
            self::$instance =new self();
        }
        return self::$instance;
    }
    
    // Conectar a la base de datos
    private function connect(){
        $host ='localhost';
        $dbname ='parbarca';
        $username ='';
        $password ='';
        
        try {
            $this ->conn =new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password
            );
            $this ->conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this ->conn ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // Manejo de errores     
        } catch (PDOException $e){
            die("Error de conexión: " . $e ->getMessage());
        }
    }
    
    // Obtener la conexión
    public function getConnection(){
        return $this ->conn;
    }
    
    // Evitar clonación
    private function __clone(){}
    
    // Evitar deserialización
    public function __wakeup(){}
}