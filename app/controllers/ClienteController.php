<?php

namespace Controllers;

use Models\Requerimiento;
use Models\Factura;
use Models\Usuario;
use Models\Configuracion;

class ClienteController{
    private $db;
    private $requerimientoModel;
    private $facturaModel;
    private $usuarioModel;
    private $configModel;
    
    // Constructor que recibe la conexión a la base de datos
    public function __construct($db){ 
        $this ->db =$db;
        $this ->requerimientoModel =new Requerimiento($db);
        $this ->facturaModel =new Factura($db);
        $this ->usuarioModel =new Usuario($db);
        $this ->configModel =new Configuracion($db);
        
        // Verificar que sea cliente
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !=='cliente') {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // Mostrar dashboard con estadísticas y actividad reciente
        public function dashboard(){
        $user_id =$_SESSION['user_id'];
        
        // Obtener datos desde los modelos
        $requerimientos =$this ->requerimientoModel ->listarPorCliente($user_id);
        $facturas =$this ->facturaModel ->listarPorCliente($user_id);
        
        // Calcular estadísticas
        $total_requerimientos_cliente =count($requerimientos);
        
        $pendientes_cliente =0;
        $finalizados_cliente =0;
        
        foreach ($requerimientos as $req) {
            if ($req['estado'] =='pendiente')$pendientes_cliente++;
            if ($req['estado'] =='finalizado')$finalizados_cliente++;
        }
        
        // Solo sumamos las facturas pagadas para el total facturado
        $total_facturado_cliente =0;
        foreach ($facturas as $factura) {
            if ($factura['estado'] =='pagada') {
                $total_facturado_cliente +=$factura['monto_total'];
            }
        }
        
        // Requerimientos recientes
        $requerimientos_recientes =array_slice($requerimientos, 0, 5);
        
        require_once 'app/views/dashboard.php';
    }
}