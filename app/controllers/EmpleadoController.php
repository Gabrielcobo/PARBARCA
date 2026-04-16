<?php

namespace Controllers;

use Models\Requerimiento;
use Models\Factura;
use Models\Usuario;
use Models\Configuracion;
use Models\FacturaRequerimiento;

class EmpleadoController{
    private $db;
    private $requerimientoModel;
    private $facturaModel;
    private $usuarioModel;
    private $configModel;
    private $facturaRequerimientoModel;
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this ->db =$db;
        $this ->requerimientoModel =new Requerimiento($db);
        $this ->facturaModel =new Factura($db);
        $this ->usuarioModel =new Usuario($db);
        $this ->configModel =new Configuracion($db);
        $this ->facturaRequerimientoModel =new FacturaRequerimiento($db);
        
        // Verificar que sea empleado
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !=='empleado') {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // Mostrar dashboard con estadísticas y actividad reciente
    public function dashboard(){
            $user_id =$_SESSION['user_id'];
        
        // Obtener estadísticas desde los modelos
        $requerimientos =$this ->requerimientoModel ->listarTodos();
        $total_requerimientos_empleado =count($requerimientos);
        
        // Contar requerimientos pendientes y finalizados
        $pendientes_empleado =0;
        $finalizados_empleado =0;
        
        foreach ($requerimientos as $req) {
            if ($req['estado'] =='pendiente')$pendientes_empleado++;
            if ($req['estado'] =='finalizado')$finalizados_empleado++;
        }
        
        // Calcular total facturado en el mes actual para este empleado
        $fecha_inicio =date('Y-m-01');
        $fecha_fin =date('Y-m-t');
        $total_facturado_empleado =$this ->facturaModel ->sumarPorEmpleadoPeriodo($user_id, $fecha_inicio, $fecha_fin);
        
        // Obtener actividad reciente desde el modelo
        $actividad_reciente_empleado =$this ->requerimientoModel ->obtenerActividadEmpleado($user_id, 5);
        
        require_once 'app/views/dashboard.php';
    } 
}