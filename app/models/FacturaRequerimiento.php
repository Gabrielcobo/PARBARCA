<?php

namespace Models;

use PDO;

class FacturaRequerimiento{
    private $db;
    private $table ='factura_requerimientos';

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this ->db =$db;
    }
    
    // Asociar un requerimiento a una factura con un monto específico
    public function asociar($factura_id, $requerimiento_id, $monto){
        $query ="INSERT INTO {$this ->table} 
                (factura_id, requerimiento_id, monto) VALUES (:factura_id, :requerimiento_id, :monto)";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->bindParam(':monto', $monto);
        
        return $stmt ->execute();
    }
    
  // Obtener todos los requerimientos asociados a una factura
    public function obtenerPorFactura($factura_id){
        $query ="SELECT fr.*, 
                        r.titulo, 
                        r.descripcion, 
                        r.estado as requerimiento_estado
                  FROM  {$this ->table} fr
                  INNER JOIN requerimientos r ON fr.requerimiento_id =r.id
                  WHERE fr.factura_id =:factura_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Sumar el monto total de los requerimientos asociados a una factura
    public function sumarMontoFactura($factura_id){
        $query ="SELECT COALESCE(SUM(monto), 0) as total 
                  FROM  {$this ->table} 
                  WHERE factura_id =:factura_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return floatval($result['total']);
    }
    
    // Obtener detalles de la asociación por requerimiento
    public function obtenerPorRequerimiento($requerimiento_id){
        $query ="SELECT fr.*, 
                        f.numero_factura, 
                        f.fecha_emision, 
                        f.estado as factura_estado
                  FROM  {$this ->table} fr
                  INNER JOIN facturas f ON fr.factura_id = f.id
                  WHERE fr.requerimiento_id =:requerimiento_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Eliminar todas las asociaciones de una factura
    public function eliminarPorFactura($factura_id){
        $query ="DELETE FROM {$this ->table} WHERE factura_id =:factura_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        
        return $stmt ->execute();
    }
    
    // Eliminar una asociación específica entre factura y requerimiento
    public function eliminarAsociacion($factura_id, $requerimiento_id){
        $query ="DELETE FROM {$this ->table} 
                  WHERE factura_id =:factura_id 
                  AND requerimiento_id =:requerimiento_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        
        return $stmt ->execute();
    }
    
    // Contar cuántos requerimientos tiene una factura
    public function contarRequerimientosPorFactura($factura_id){
        $query ="SELECT COUNT(*) as total 
                  FROM {$this ->table} 
                  WHERE factura_id =:factura_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        
        return intval($result['total']);
    }
    
    // Verificar si un requerimiento ya está asociado a una factura
    public function existeAsociacion($factura_id, $requerimiento_id){
        $query ="SELECT id FROM {$this ->table} 
                  WHERE factura_id =:factura_id 
                  AND requerimiento_id =:requerimiento_id 
                  LIMIT 1";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->execute();
        
        return $stmt ->fetch() ? true : false;
    }
    
    // Actualizar el monto de una asociación
    public function actualizarMonto($factura_id, $requerimiento_id, $monto){
        $query ="UPDATE {$this ->table} 
                  SET monto =:monto 
                  WHERE factura_id =:factura_id 
                  AND requerimiento_id =:requerimiento_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':monto', $monto);
        $stmt ->bindParam(':factura_id', $factura_id);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        
        return $stmt ->execute();
    }
}