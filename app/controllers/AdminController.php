<?php

namespace Controllers;

use Models\Usuario;
use Models\Requerimiento;
use Models\Factura;
use Models\Configuracion;
use Models\FacturaRequerimiento;
use Core\Validator;
use PDO;

class AdminController{
    private $db;
    private $usuarioModel;
    private $requerimientoModel;
    private $facturaModel;
    private $configModel;
    private $facturaRequerimientoModel;
    
    // Conxion a la BD
    public function __construct($db){
        $this ->db =$db;
        $this ->usuarioModel =new Usuario($db);
        $this ->requerimientoModel =new Requerimiento($db);
        $this ->facturaModel =new Factura($db);
        $this ->configModel =new Configuracion($db);
        $this ->facturaRequerimientoModel =new FacturaRequerimiento($db);
        
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !=='admin'){

            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // Dashboard
    public function dashboard(){
        $resumen =$this ->requerimientoModel ->obtenerResumenDashboard();
        $totalClientesActivos =$this ->usuarioModel ->contarClientesActivos();
        $facturacionMesActual =$this ->facturaModel ->sumarFacturadoPorMes();
        $actividadReciente =$this ->requerimientoModel ->obtenerActividadReciente(5);
        
        $totalRequerimientos =$resumen['total_requerimientos'] ?? 0;
        $requerimientosPendientes =$resumen['pendientes'] ?? 0;
        $requerimientosFinalizados =$resumen['finalizados'] ?? 0;
        
        require_once 'app/views/dashboard.php';
    }
}