<?php

namespace Controllers;

use Models\Requerimiento;
use Models\Factura;
use Models\Usuario;
use Models\Configuracion;
use Models\FacturaRequerimiento;
use Core\Validator;

class EmpleadoController{
    private $db;
    private $requerimientoModel;
    private $facturaModel;
    private $usuarioModel;
    private $configModel;
    private $facturaRequerimientoModel;
    
    // Conxion a la BD
    public function __construct($db){
        $this ->db =$db;
        $this ->requerimientoModel =new Requerimiento($db);
        $this ->facturaModel =new Factura($db);
        $this ->usuarioModel =new Usuario($db);
        $this ->configModel =new Configuracion($db);
        $this ->facturaRequerimientoModel =new FacturaRequerimiento($db);
        
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !=='empleado'){

            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // Dasboard
    public function dashboard(){
        $userId =$_SESSION['user_id'];
        $requerimientos =$this ->requerimientoModel ->listarTodos();
        $totalRequerimientosEmpleado =count($requerimientos);
        
        $pendientesEmpleado =0;
        $finalizadosEmpleado =0;
        
        foreach ($requerimientos as $req) {
            if ($req['estado'] =='pendiente') $pendientesEmpleado++;
            if ($req['estado'] =='finalizado') $finalizadosEmpleado++;
        }
        
        $fechaInicio = date('Y-m-01');
        $fechaFin = date('Y-m-t');
        $totalFacturadoEmpleado =$this ->facturaModel ->sumarPorEmpleadoPeriodo($userId, $fechaInicio, $fechaFin);
        $actividadRecienteEmpleado =$this ->requerimientoModel ->obtenerActividadEmpleado($userId, 5);
        
        require_once 'app/views/dashboard.php';
    }
}