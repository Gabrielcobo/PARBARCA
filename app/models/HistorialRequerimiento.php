<?php

namespace Models;

use PDO;

class HistorialRequerimiento{
    private $db;
    private $table ='historial_requerimientos';
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this ->db =$db;
    }
    
    // Registrar un cambio de estado en el historial
    public function registrar($requerimiento_id, $usuario_id, $estado_anterior, $estado_nuevo, $comentario =null){
        $query ="INSERT INTO {$this ->table} 
                  (requerimiento_id, usuario_id, estado_anterior, estado_nuevo, comentario) VALUES 
                  (:requerimiento_id, :usuario_id, :estado_anterior, :estado_nuevo, :comentario)";
        
        $stmt =$this ->db->prepare($query);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->bindParam(':usuario_id', $usuario_id);
        $stmt ->bindParam(':estado_anterior', $estado_anterior);
        $stmt ->bindParam(':estado_nuevo', $estado_nuevo);
        $stmt ->bindParam(':comentario', $comentario);
        
        return $stmt ->execute();
    }
    
    // Obtener todo el historial de un requerimiento
    public function obtenerPorRequerimiento($requerimiento_id){
        $query ="SELECT h.*, 
                        u.nombre AS usuario_nombre, 
                        u.apellido AS usuario_apellido,
                        u.email AS usuario_email
                  FROM  {$this ->table} h
                  INNER JOIN usuarios u ON h.usuario_id =u.id
                  WHERE h.requerimiento_id =:requerimiento_id
                  ORDER BY h.created_at DESC";
        
        $stmt  =$this ->db->prepare($query);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener el último cambio de un requerimiento
    public function obtenerUltimoCambio($requerimiento_id){
        $query ="SELECT h.*, 
                        u.nombre AS usuario_nombre, 
                        u.apellido AS usuario_apellido
                  FROM  {$this ->table} h
                  INNER JOIN usuarios u ON h.usuario_id =u.id
                  WHERE h.requerimiento_id =:requerimiento_id
                  ORDER BY h.created_at DESC
                  LIMIT 1";
        
        $stmt  =$this ->db->prepare($query);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
    
    // Contar cuántos cambios ha hecho un usuario
    public function contarCambiosPorUsuario($usuario_id){
        $query ="SELECT COUNT(*) as total FROM {$this ->table} WHERE usuario_id =:usuario_id";
        
        $stmt  =$this ->db->prepare($query);
        $stmt ->bindParam(':usuario_id', $usuario_id);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Obtener historial de cambios por período
    public function obtenerPorPeriodo($fecha_inicio, $fecha_fin){
        $query ="SELECT h.*, 
                        u.nombre AS usuario_nombre, 
                        u.apellido AS usuario_apellido,
                        r.titulo AS requerimiento_titulo,
                        r.cliente_id
                  FROM  {$this ->table} h
                  INNER JOIN usuarios u ON h.usuario_id =u.id
                  INNER JOIN requerimientos r ON h.requerimiento_id =r.id
                  WHERE DATE(h.created_at) BETWEEN :fecha_inicio AND :fecha_fin
                  ORDER BY h.created_at DESC";
        
        $stmt  =$this ->db->prepare($query);
        $stmt ->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt ->bindParam(':fecha_fin', $fecha_fin);
        $stmt ->execute();
        
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //Obtener estadísticas de cambios por estado
    
    public function obtenerEstadisticas($requerimiento_id = null){
        $query = "SELECT 
                    estado_nuevo,
                    COUNT(*) as total_cambios,
                    COUNT(DISTINCT usuario_id) as usuarios_involucrados,
                    MIN(created_at) as primer_cambio,
                    MAX(created_at) as ultimo_cambio
                  FROM {$this ->table}";
        
        $params =[];
        
        if ($requerimiento_id) {
            $query .=" WHERE requerimiento_id =:requerimiento_id";
            $params[':requerimiento_id'] =$requerimiento_id;
        }
        
        $query .=" GROUP BY estado_nuevo ORDER BY total_cambios DESC";
        
        $stmt  =$this ->db->prepare($query);
        
        foreach ($params as $key =>$value) {
            $stmt ->bindValue($key, $value);
        }
        
        $stmt ->execute();
        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener tiempo promedio de atención de requerimientos
    public function obtenerTiempoPromedioAtencion(){
        $query ="SELECT AVG(TIMESTAMPDIFF(HOUR, r.created_at, h.created_at)) as promedio_horas
                  FROM {$this ->table} h
                  INNER JOIN requerimientos r ON h.requerimiento_id =r.id
                  WHERE h.estado_nuevo ='finalizado'
                  AND h.estado_anterior !='finalizado'";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->execute();
        
        $result =$stmt ->fetch(PDO::FETCH_ASSOC);
        return round($result['promedio_horas'] ?? 0, 2);
    }
    
    // Obtener historial completo con detalles de requerimiento y usuario
    public function obtenerHistorialCompleto($limit =null){
        $query ="SELECT h.*, 
                        u.nombre AS usuario_nombre, 
                        u.apellido AS usuario_apellido,
                        u.email AS usuario_email,
                        u.rol AS usuario_rol,
                        r.titulo AS requerimiento_titulo,
                        r.descripcion AS requerimiento_descripcion,
                        c.nombre AS cliente_nombre,
                        c.apellido AS cliente_apellido
                  FROM  {$this ->table} h
                  INNER JOIN usuarios u ON h.usuario_id =u.id
                  INNER JOIN requerimientos r ON h.requerimiento_id =r.id
                  INNER JOIN usuarios c ON r.cliente_id =c.id
                  ORDER BY h.created_at DESC";
        
        if ($limit){
            $query .=" LIMIT :limit";
        }
        
        $stmt  =$this ->db ->prepare($query);
        
        if ($limit){
            $stmt ->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        
        $stmt ->execute();

        return $stmt ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Eliminar historial de un requerimiento (solo si el requerimiento se elimina)
    public function eliminarPorRequerimiento($requerimiento_id){
        $query ="DELETE FROM {$this ->table} WHERE requerimiento_id =:requerimiento_id";
        
        $stmt  =$this ->db ->prepare($query);
        $stmt ->bindParam(':requerimiento_id', $requerimiento_id);
        
        return $stmt ->execute();
    }
}