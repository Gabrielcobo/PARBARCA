<?php

namespace Models;

use PDO;
use Exception;

class Factura{
    private $db;
    private $table ='facturas';
    private $configModel;
    private $facturaRequerimientoModel;
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
         $this ->db =$db;
         $this ->configModel =new Configuracion($db);
         $this ->facturaRequerimientoModel =new FacturaRequerimiento($db);
    }
    
    // Listar facturas con filtros
    public function listar($filtros =[]){
        $query ="SELECT f.*, 
                        c.nombre AS cliente_nombre, 
                        c.apellido AS cliente_apellido,
                        e.nombre AS empleado_nombre,
                        e.apellido AS empleado_apellido
                  FROM  {$this ->table} f
                  INNER JOIN usuarios c ON f.cliente_id =c.id
                  INNER JOIN usuarios e ON f.empleado_id =e.id
                  WHERE 1=1";
        
        $params =[];
        
        if (!empty($filtros['estado'])){
            $query .=" AND f.estado =:estado";
            $params[':estado'] =$filtros['estado'];
        }
        
        if (!empty($filtros['cliente_id'])){
            $query .=" AND f.cliente_id =:cliente_id";
            $params[':cliente_id'] =$filtros['cliente_id'];
        }
        
        if (!empty($filtros['empleado_id'])){
            $query .=" AND f.empleado_id =:empleado_id";
            $params[':empleado_id'] =$filtros['empleado_id'];
        }
        
        if (!empty($filtros['fecha_inicio'])){
            $query .=" AND f.fecha_emision >=+:fecha_inicio";
            $params[':fecha_inicio'] =$filtros['fecha_inicio'];
        }
        
        if (!empty($filtros['fecha_fin'])){
            $query .=" AND f.fecha_emision <=:fecha_fin";
            $params[':fecha_fin'] =$filtros['fecha_fin'];
        }
        
        $query .=" ORDER BY f.fecha_emision DESC";
        
        $stmt =$this ->db ->prepare($query);
        
        foreach ($params as $key =>$value){
            $stmt ->bindValue($key, $value);
        }
        
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Listar facturas por empleado
    public function listarPorEmpleado($empleado_id){
        $query ="SELECT f.*, 
                        c.nombre AS cliente_nombre, 
                        c.apellido AS cliente_apellido
                  FROM  {$this ->table} f
                  INNER JOIN usuarios c ON f.cliente_id =c.id
                  WHERE f.empleado_id =:empleado_id
                  ORDER BY f.fecha_emision DESC";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':empleado_id', $empleado_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Listar facturas por cliente
    public function listarPorCliente($cliente_id){
        $query ="SELECT * FROM {$this ->table} 
                  WHERE cliente_id =:cliente_id 
                  ORDER BY fecha_emision DESC";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':cliente_id', $cliente_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Crear factura (con número automático)
    public function crear($cliente_id, $empleado_id, $requerimientos, $descripcion, $monto_total){
        // Generar número de factura automático
        $numeroData =$this ->configModel ->generarNumeroFactura();
        $nuevoNumero =$numeroData['numero'];
        $numeroFactura =$numeroData['factura'];
        
        // Iniciar transacción
        $this ->db ->beginTransaction();
        
        try {
            // Crear factura
            $query ="INSERT INTO {$this ->table} 
                      (numero_factura, cliente_id, empleado_id, fecha_emision, monto_total, estado, descripcion) VALUES 
                      (:numero_factura, :cliente_id, :empleado_id, :fecha_emision, :monto_total, 'pendiente', :descripcion)";
            
            $stmt  =$this ->db ->prepare($query);
            $fecha_emision =date('Y-m-d');
            
            $stmt ->bindParam(':numero_factura', $numeroFactura);
            $stmt ->bindParam(':cliente_id', $cliente_id);
            $stmt ->bindParam(':empleado_id', $empleado_id);
            $stmt ->bindParam(':fecha_emision', $fecha_emision);
            $stmt ->bindParam(':monto_total', $monto_total);
            $stmt ->bindParam(':descripcion', $descripcion);
            
            $stmt ->execute();
            $factura_id =$this ->db ->lastInsertId();
            
            // Asociar requerimientos a la factura
            foreach ($requerimientos as $req) {
                $this ->facturaRequerimientoModel ->asociar($factura_id, $req['id'], $req['monto']);
            }
            
            // Actualizar último número de factura en configuración
            $this ->configModel ->actualizarUltimoNumero($nuevoNumero);
            
            // Confirmar transacción
            $this ->db ->commit();
            
            return [
                'id' =>$factura_id,
                'numero_factura' =>$numeroFactura
            ];
            
        } catch (Exception $e) {
            $this ->db ->rollBack();
            return false;
        }
    }
    
    // Cambiar estado de factura
  public function cambiarEstado($id, $nuevoEstado){
    $query ="UPDATE {$this ->table} SET estado =:estado WHERE id =:id";
    $stmt =$this ->db ->prepare($query);
    $stmt ->bindParam(':id', $id);
    $stmt ->bindParam(':estado', $nuevoEstado);

    return $stmt ->execute();
}
    
    // Anular factura
    public function anular($id){
        return $this ->cambiarEstado($id, 'anulada');
    }
    
    // Obtener factura por ID
    public function obtenerPorId($id){
        $query ="SELECT f.*, 
                        c.nombre AS cliente_nombre, 
                        c.apellido AS cliente_apellido,
                        c.email AS cliente_email,
                        c.cedula AS cliente_cedula,
                        c.telefono AS cliente_telefono,
                        e.nombre AS empleado_nombre,
                        e.apellido AS empleado_apellido
                  FROM  {$this ->table} f
                  INNER JOIN usuarios c ON f.cliente_id =c.id
                  INNER JOIN usuarios e ON f.empleado_id =e.id
                  WHERE f.id =:id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->execute();
        
        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener factura por número
    public function obtenerPorNumero($numero_factura){
        $query ="SELECT * FROM vista_factura_detalle WHERE numero_factura =:numero_factura";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':numero_factura', $numero_factura);
        $stmt ->execute();
        
        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
    
    // Suma facturada por período
    public function sumarPorPeriodo($fecha_inicio, $fecha_fin, $estado ='pagada'){
        $query ="SELECT COALESCE(SUM(monto_total), 0) as total 
                  FROM {$this ->table} 
                  WHERE estado =:estado 
                  AND fecha_emision BETWEEN :fecha_inicio AND :fecha_fin";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':estado', $estado);
        $stmt ->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt ->bindParam(':fecha_fin', $fecha_fin);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }
    
    // Suma facturada por empleado en período
    public function sumarPorEmpleadoPeriodo($empleado_id, $fecha_inicio, $fecha_fin){
        $query ="SELECT COALESCE(SUM(monto_total), 0) as total 
                  FROM {$this ->table} 
                  WHERE empleado_id =:empleado_id 
                  AND estado ='pagada'
                  AND fecha_emision BETWEEN :fecha_inicio AND :fecha_fin";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':empleado_id', $empleado_id);
        $stmt ->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt ->bindParam(':fecha_fin', $fecha_fin);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Contar facturas por estado
    public function contarPorEstado($estado =null){
        $query ="SELECT COUNT(*) as total FROM {$this ->table}";
        
        if ($estado){
            $query .=" WHERE estado =:estado";
            $stmt  =$this ->db ->prepare($query);
            $stmt ->bindParam(':estado', $estado);
        } else {
            $stmt  =$this ->db ->prepare($query);
        }
          
        $stmt ->execute();
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }
    
    // Contar facturas por empleado
    public function contarPorEmpleado($empleado_id){
        $query ="SELECT COUNT(*) as total FROM {$this ->table} WHERE empleado_id =:empleado_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':empleado_id', $empleado_id);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    
    // DASHBOARD Y REPORTES
    public function sumarFacturadoPorMes(){
        $query ="SELECT COALESCE(SUM(monto_total), 0) as total 
                FROM {$this ->table} 
                WHERE estado ='pagada' 
                AND MONTH(fecha_emision) =MONTH(CURDATE()) 
                AND YEAR(fecha_emision) =YEAR(CURDATE())";

        $stmt  =$this ->db ->prepare($query);
        $stmt ->execute();
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }

    // Obtener detalle completo de factura 
    public function obtenerDetalleCompleto($id){
        $query ="SELECT * FROM vista_factura_detalle WHERE id =:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->execute();

        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener facturación por período para reporte
    public function obtenerFacturacionPorPeriodo($fecha_inicio, $fecha_fin){
        $query ="SELECT * FROM vista_reporte_facturacion_periodo 
                WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY fecha DESC";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt ->bindParam(':fecha_fin', $fecha_fin);
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener reporte de facturas con filtros avanzados
    public function obtenerReporte($estado, $cliente_id, $fecha_inicio, $fecha_fin){
        $query ="SELECT * FROM vista_reporte_facturas WHERE 1=1";
        $params =[];
        
        if (!empty($estado)){
            $query .=" AND estado =:estado";
            $params[':estado'] =$estado;
        }

        if (!empty($cliente_id)){
            $query .=" AND cliente_id =:cliente_id";
            $params[':cliente_id'] =$cliente_id;
        }

        if (!empty($fecha_inicio)){
            $query .=" AND fecha_emision >=:fecha_inicio";
            $params[':fecha_inicio'] =$fecha_inicio;
        }

        if (!empty($fecha_fin)){
            $query .=" AND fecha_emision <=:fecha_fin";
            $params[':fecha_fin'] =$fecha_fin;
        }
        
        $query .=" ORDER BY fecha_emision DESC";
        $stmt  =$this ->db ->prepare($query);
        foreach ($params as $key =>$value) {
            $stmt ->bindValue($key, $value);
        }

        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
}