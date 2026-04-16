<?php

namespace Models;

use PDO;

class Requerimiento{
    private $db;
    private $table ='requerimientos';
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this ->db =$db;
    }
    
    // LISTAR REQUERIMIENTOS
    public function listarTodos($filtros =[]){
        $sql ="SELECT   r.*, 
                        c.nombre AS cliente_nombre, 
                        c.apellido AS cliente_apellido, 
                        c.email AS cliente_email,
                        c.telefono AS cliente_telefono,
                        c.cedula AS cliente_cedula,
                        c.direccion AS cliente_direccion
                    FROM requerimientos r
                INNER JOIN usuarios c ON r.cliente_id =c.id
                WHERE 1=1";
        
        $params =[];
        
        if (isset($filtros['estado']) && !empty($filtros['estado'])){
            $sql .=" AND r.estado =:estado";
            $params[':estado'] =$filtros['estado'];
        }
        
        if (isset($filtros['cliente_id']) && !empty($filtros['cliente_id'])){
            $sql .=" AND r.cliente_id =:cliente_id";
            $params[':cliente_id'] =$filtros['cliente_id'];
        }
        
        if (isset($filtros['fecha_inicio']) && !empty($filtros['fecha_inicio'])){
            $sql .=" AND DATE(r.created_at) >=:fecha_inicio";
            $params[':fecha_inicio'] =$filtros['fecha_inicio'];
        }
        
        if (isset($filtros['fecha_fin']) && !empty($filtros['fecha_fin'])){
            $sql .=" AND DATE(r.created_at) <=:fecha_fin";
            $params[':fecha_fin'] =$filtros['fecha_fin'];
        }
        
        if (isset($filtros['search']) && !empty($filtros['search'])){
            $sql .=" AND (r.titulo LIKE :search OR r.descripcion LIKE :search)";
            $params[':search'] ='%' . $filtros['search'] . '%';
        }
        
        $sql .=" ORDER BY r.created_at DESC";
        
        $stmt =$this ->db->prepare($sql);
        
        foreach ($params as $key =>$value) {
            $stmt ->bindValue($key, $value);
        }
        
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Listar requerimientos de un cliente específico con búsqueda
    public function listarPorCliente($cliente_id, $search =null){
        $query ="SELECT * FROM {$this ->table} WHERE cliente_id =:cliente_id";
        $params =[':cliente_id' =>$cliente_id];
        
        if (!empty($search)) {
            $query .=" AND (titulo LIKE :search OR descripcion LIKE :search)";
            $params[':search'] ='%' . $search . '%';
        }
        
        $query .=" ORDER BY created_at DESC";
        
        $stmt  =$this ->db->prepare($query);
        
        foreach ($params as $key =>$value) {
            $stmt ->bindValue($key, $value);
        }
        
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    // DASHBOARD 
    public function obtenerResumenDashboard(){
        $query ="SELECT 
                    (SELECT COUNT(*) FROM {$this ->table}) as total_requerimientos,
                    (SELECT COUNT(*) FROM {$this ->table} WHERE estado ='pendiente') as pendientes,
                    (SELECT COUNT(*) FROM {$this ->table} WHERE estado ='finalizado') as finalizados
                  FROM DUAL";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->execute();
        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener actividad reciente de requerimientos
    public function obtenerActividadReciente($limite =5){
        $query ="SELECT 
                    DATE_FORMAT(h.created_at, '%d/%m/%Y %H:%i') as fecha,
                    CONCAT(u.nombre, ' ', u.apellido) as usuario,
                    CASE 
                        WHEN h.estado_nuevo ='pendiente' THEN 'Creó requerimiento'
                        WHEN h.estado_nuevo ='en_proceso' THEN 'Inició atención'
                        WHEN h.estado_nuevo ='finalizado' THEN 'Finalizó'
                        WHEN h.estado_nuevo ='rechazado' THEN 'Rechazó'
                        ELSE 'Cambió estado'
                    END as accion,
                    r.titulo as detalle
                  FROM historial_requerimientos h
                  INNER JOIN usuarios u ON h.usuario_id =u.id
                  INNER JOIN {$this ->table} r ON h.requerimiento_id = r.id
                  ORDER BY h.created_at DESC
                  LIMIT :limite";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt ->execute();
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener actividad reciente del empleado
    public function obtenerActividadEmpleado($usuario_id, $limite =5){
        $query ="SELECT DATE_FORMAT(h.created_at, '%d/%m/%Y %H:%i') as fecha,
                        CONCAT('Requerimiento #', r.id, ': ', 
                                CASE WHEN h.estado_nuevo ='en_proceso' THEN 'Iniciaste atención'
                                    WHEN h.estado_nuevo ='finalizado' THEN 'Finalizaste'
                                    ELSE 'Cambiaste state'
                                END) as mensaje
                FROM historial_requerimientos h
                INNER JOIN {$this ->table} r ON h.requerimiento_id =r.id
                WHERE h.usuario_id =:usuario_id
                ORDER BY h.created_at DESC 
                LIMIT :limite";
        
        $stmt =$this ->db->prepare($query);
        $stmt ->bindParam(':usuario_id', $usuario_id);
        $stmt ->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt ->execute();
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener resumen de requerimientos por estado para un cliente específico
    public function obtenerHistorial($requerimiento_id){
        $query ="SELECT h.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido
                  FROM historial_requerimientos h
                  INNER JOIN usuarios u ON h.usuario_id =u.id
                  WHERE h.requerimiento_id =:requerimiento_id
                  ORDER BY h.created_at DESC";
        $stmt  =$this ->db->prepare($query);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener resumen de requerimientos por estado para un cliente específico
    public function obtenerReporte($estado, $cliente_id, $fecha_inicio, $fecha_fin){
        $query ="SELECT * FROM vista_reporte_requerimientos WHERE 1=1";
        $params =[];
        
        if (!empty($estado)){
            $query .=" AND estado =:estado";
            $params[':estado'] =$estado;
        }
        if (!empty($cliente_id)){
            $query .=" AND cliente_id =:cliente_id";
            $params[':cliente_id'] =$cliente_id;
        }
        if (!empty($fecha_inicio)) {
            $query .=" AND fecha_creacion >=:fecha_inicio";
            $params[':fecha_inicio'] =$fecha_inicio;
        }
        if (!empty($fecha_fin)) {
            $query .=" AND fecha_creacion <=:fecha_fin";
            $params[':fecha_fin'] =$fecha_fin;
        }
        
        $query .=" ORDER BY fecha_creacion DESC";
        $stmt  =$this ->db->prepare($query);
        foreach ($params as $key =>$value) {
            $stmt ->bindValue($key, $value);
        }
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    // CRUD REQUERIMIENTOS
    public function crear($cliente_id, $titulo, $descripcion){
        $query ="INSERT INTO {$this ->table} 
                  (cliente_id, titulo, descripcion, estado) VALUES (:cliente_id, :titulo, :descripcion, 'pendiente')";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':cliente_id', $cliente_id);
        $stmt ->bindParam(':titulo', $titulo);
        $stmt ->bindParam(':descripcion', $descripcion);
        
        if ($stmt ->execute()){
            $id =$this ->db ->lastInsertId();
            $this ->cambiarEstado($id, 'pendiente', $cliente_id, 'Requerimiento creado');

            return $id;
        }
        return false;
    }
    
    // Editar requerimiento (solo si está pendiente y pertenece al cliente)
    public function editar($id, $titulo, $descripcion, $cliente_id = null){
        $query ="SELECT id, cliente_id, estado FROM {$this ->table} WHERE id =:id";
        $stmt  =$this ->db->prepare($query);
        $stmt  ->bindParam(':id', $id);
        $stmt  ->execute();
        $requerimiento =$stmt ->fetch(PDO::FETCH_ASSOC);
        
        if (!$requerimiento){
            return false;
        }
        
        if ($cliente_id !==null && $requerimiento['cliente_id'] !=$cliente_id){
            return false;
        }
        
        if ($requerimiento['estado'] !=='pendiente'){
            return false;
        }
        
        $query ="UPDATE {$this ->table} 
                  SET titulo =:titulo, descripcion =:descripcion 
                  WHERE id =:id AND estado ='pendiente'";
        
        $stmt =$this ->db->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->bindParam(':titulo', $titulo);
        $stmt ->bindParam(':descripcion', $descripcion);
        
        return $stmt ->execute();
    }
    
    //  Eliminar requerimiento
    public function eliminar($id, $cliente_id =null){
        $query ="SELECT id, cliente_id, estado FROM {$this ->table} WHERE id =:id";
        $stmt  =$this ->db->prepare($query);
        $stmt  ->bindParam(':id', $id);
        $stmt  ->execute();
        $requerimiento =$stmt ->fetch(PDO::FETCH_ASSOC);
        
        if (!$requerimiento){
            return false;
        }
        
        if ($cliente_id !==null && $requerimiento['cliente_id'] !=$cliente_id){
            return false;
        }
        
        if ($requerimiento['estado'] !=='pendiente'){
            return false;
        }
        
        $query ="SELECT COUNT(*) as total FROM factura_requerimientos WHERE requerimiento_id = :id";
        $stmt  =$this ->db->prepare($query);
        $stmt  ->bindParam(':id', $id);
        $stmt  ->execute();
        $facturas =$stmt ->fetch(PDO::FETCH_ASSOC);
        
        if ($facturas['total'] >0){
            return false;
        }
        
        $query ="DELETE FROM historial_requerimientos WHERE requerimiento_id =:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt  ->bindParam(':id', $id);
        $stmt  ->execute();
        
        $query ="DELETE FROM {$this ->table} WHERE id =:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt  ->bindParam(':id', $id);
        
        return $stmt ->execute();
    }
    
    // Cambiar estado de un requerimiento y registrar en historial
    public function cambiarEstado($id, $nuevoEstado, $usuario_id, $comentario =null){
        $query ="SELECT estado FROM {$this ->table} WHERE id =:id";
        $stmt  =$this ->db ->prepare($query);
        $stmt  ->bindParam(':id', $id);
        $stmt  ->execute();
        $estado_actual =$stmt ->fetchColumn();
        
        if (!$estado_actual){
            return false;
        }
        
        $query ="UPDATE {$this ->table} SET estado =:estado WHERE id =:id";
        $stmt  = $this ->db ->prepare($query);
        $stmt  ->bindParam(':id', $id);
        $stmt  ->bindParam(':estado', $nuevoEstado);
        
        if ($stmt ->execute()){
            $query ="INSERT INTO historial_requerimientos 
                      (requerimiento_id, usuario_id, estado_anterior, estado_nuevo, comentario) 
                      VALUES 
                      (:req_id, :user_id, :estado_anterior, :estado_nuevo, :comentario)";
            
            $stmt =$this ->db->prepare($query);
            $stmt ->bindParam(':req_id', $id);
            $stmt ->bindParam(':user_id', $usuario_id);
            $stmt ->bindParam(':estado_anterior', $estado_actual);
            $stmt ->bindParam(':estado_nuevo', $nuevoEstado);
            $stmt ->bindParam(':comentario', $comentario);
            $stmt ->execute();
            
            return true;
        }
        return false;
    }
    
    // Obtener detalles de un requerimiento específico
    public function obtenerDetalle($id){
        $query ="SELECT r.*, 
                        c.nombre AS cliente_nombre, 
                        c.apellido AS cliente_apellido,
                        c.email AS cliente_email,
                        c.telefono AS cliente_telefono,
                        c.cedula AS cliente_cedula,
                        c.direccion AS cliente_direccion
                  FROM  {$this ->table} r
                  INNER JOIN usuarios c ON r.cliente_id =c.id
                  WHERE r.id =:id";
        
        $stmt =$this ->db ->prepare($query);
        $stmt ->bindParam(':id', $id);
        $stmt ->execute();
        
        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
    
    
    // CONTADORES
    public function contarPorEstado($estado =null){
        $query ="SELECT COUNT(*) as total FROM {$this ->table}";
        
        if ($estado){
            $query .=" WHERE estado =:estado";
            $stmt =$this ->db ->prepare($query);
            $stmt ->bindParam(':estado', $estado);
        } else {
            $stmt =$this ->db ->prepare($query);
        }
        
        $stmt ->execute();
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Contar requerimientos por cliente
    public function contarPorCliente($cliente_id){
        $query ="SELECT COUNT(*) as total FROM {$this ->table} WHERE cliente_id =:cliente_id";
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':cliente_id', $cliente_id);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Contar requerimientos creados en un rango de fechas
    public function obtenerPendientes(){
        $query ="SELECT * FROM {$this ->table} WHERE estado ='pendiente' ORDER BY created_at ASC";
        $stmt  =$this ->db ->prepare($query);
        $stmt  ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
}