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
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){
        $this ->db =$db;
        $this ->usuarioModel =new Usuario($db);
        $this ->requerimientoModel =new Requerimiento($db);
        $this ->facturaModel =new Factura($db);
        $this ->configModel =new Configuracion($db);
        $this ->facturaRequerimientoModel =new FacturaRequerimiento($db);
        
        // Verificar que sea admin
       if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !=='admin'){
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    
    // Mostrar dashboard con estadísticas y actividad reciente
    public function dashboard(){
        // Obtener datos desde los modelos 
        $resumen =$this ->requerimientoModel ->obtenerResumenDashboard();
        $total_clientes_activos =$this ->usuarioModel ->contarClientesActivos();
        $facturacion_mes_actual =$this ->facturaModel ->sumarFacturadoPorMes();
        $actividad_reciente =$this ->requerimientoModel ->obtenerActividadReciente(5);
        
        // Transformar datos para la vista
        $total_requerimientos =$resumen['total_requerimientos'] ?? 0;
        $requerimientos_pendientes =$resumen['pendientes'] ?? 0;
        $requerimientos_finalizados =$resumen['finalizados'] ?? 0;
        
        require_once 'app/views/dashboard.php';
    }
}