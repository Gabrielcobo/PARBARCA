<?php

namespace Models;

use PDO;

class Configuracion{
    private $db ;
    private $table ='configuracion';
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db ){
        $this ->db  =$db ;
    }
    
    // Obtener configuración
    public function obtener(){
        $query ="SELECT * FROM {$this ->table} LIMIT 1";
        
        $stmt =$this  ->db  ->prepare($query);
        $stmt ->execute();
        
        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
    
    // Actualizar datos de la empresa
    public function actualizarEmpresa($nombre, $rif, $direccion, $telefono, $email){
        $query ="UPDATE {$this ->table} 
                  SET empresa_nombre =:nombre, 
                      empresa_rif =:rif, 
                      empresa_direccion =:direccion, 
                      empresa_telefono =:telefono, 
                      empresa_email =:email 
                  WHERE id =1";
        
        $stmt =$this ->db  ->prepare($query);
        $stmt ->bindParam(':nombre', $nombre);
        $stmt ->bindParam(':rif', $rif);
        $stmt ->bindParam(':direccion', $direccion);
        $stmt ->bindParam(':telefono', $telefono);
        $stmt ->bindParam(':email', $email);
        
        return $stmt ->execute();
    }
    
    // Actualizar formato de factura
    public function actualizarFormatoFactura($prefijo, $numero_inicial){
        $query ="UPDATE {$this ->table} 
                  SET factura_prefijo =:prefijo, 
                      factura_numero_inicial =:numero_inicial 
                  WHERE id =1";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':prefijo', $prefijo);
        $stmt ->bindParam(':numero_inicial', $numero_inicial);
        
        return $stmt ->execute();
    }
    
    //  Actualizar último número de factura usado
    public function actualizarUltimoNumero($numero){
        $query ="UPDATE {$this ->table} 
                  SET factura_ultimo_numero =:numero 
                  WHERE id =1";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':numero', $numero);
        
        return $stmt ->execute();
    }
    
    // Actualizar logo
    public function actualizarLogo($ruta_logo){
        $query ="UPDATE {$this ->table} 
                  SET logo =:logo 
                  WHERE id =1";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':logo', $ruta_logo);
        
        return $stmt ->execute();
    }
    
    // Obtener próximo número de factura
    public function obtenerProximoNumeroFactura(){
        $config =$this ->obtener();
        
        return $config['factura_ultimo_numero'] +1;
    }
    
    // Generar número de factura completo
    public function generarNumeroFactura(){
        $config =$this ->obtener();
        $nuevoNumero =$config['factura_ultimo_numero'] +1;
        $anio =date('Y');
        $numeroFormateado =str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        $numeroFactura =$config['factura_prefijo'] . $anio . '-' . $numeroFormateado;
        
        return [
            'numero' =>$nuevoNumero,
            'factura' =>$numeroFactura
        ];
    }
}