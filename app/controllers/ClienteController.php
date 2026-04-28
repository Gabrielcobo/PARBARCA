<?php

namespace Controllers;

use Models\Requerimiento;
use Models\Factura;
use Models\Usuario;
use Models\Configuracion;
use Models\HistorialRequerimiento;
use Core\Validator;

class ClienteController{
    private $db;
    private $requerimientoModel;
    private $facturaModel;
    private $usuarioModel;
    private $configModel;
    private $historialModel;
    
    // Conxion a la BD
    public function __construct($db){ 
        $this ->db =$db;
        $this ->requerimientoModel =new Requerimiento($db);
        $this ->facturaModel =new Factura($db);
        $this ->usuarioModel =new Usuario($db);
        $this ->configModel =new Configuracion($db);
        $this ->historialModel =new HistorialRequerimiento($db);
        
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !=='cliente'){

            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // Dasboard
    public function dashboard(){
        $userId =$_SESSION['user_id'];
        $requerimientos =$this ->requerimientoModel ->listarPorCliente($userId);
        $facturas =$this ->facturaModel ->listarPorCliente($userId);
        
        $totalRequerimientosCliente =count($requerimientos);
        $pendientesCliente =0;
        $finalizadosCliente =0;
        
        foreach ($requerimientos as $req){
            if ($req['estado'] =='pendiente') $pendientesCliente++;
            if ($req['estado'] =='finalizado') $finalizadosCliente++;
        }
        
        $totalFacturadoCliente =0;
        foreach ($facturas as $factura){
            if ($factura['estado'] =='pagada'){
                $totalFacturadoCliente +=$factura['monto_total'];
            }
        }
        $requerimientosRecientes =array_slice($requerimientos, 0, 5);

        require_once 'app/views/dashboard.php';
    }
    
    // Listar requerimientos del cliente
    public function requerimientos(){
        $clienteId =$_SESSION['user_id'];
        $requerimientos =$this ->requerimientoModel ->listarPorCliente($clienteId);

        require_once 'app/views/cliente/requerimientos.php';
    }
    
    // Formulario para crear nuevo requerimiento
    public function requerimientoCrearForm(){

        require_once 'app/views/cliente/requerimiento_crear.php';
    }
    
    // Crear nuevo requerimiento
    public function requerimientoCrear(){
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){

            header('Location: index.php?action=cliente_requerimientos');
            return;
        }
        
        $titulo =Validator::sanitizarInput($_POST['titulo'] ?? '');
        $descripcion =Validator::sanitizarInput($_POST['descripcion'] ?? '');
        $clienteId =$_SESSION['user_id'];
        
        if (Validator::required($titulo) || Validator::required($descripcion)){
            $_SESSION['error'] ='Todos los campos son obligatorios';

            header('Location: index.php?action=cliente_requerimiento_crear_form');
            return;
        }
        
        $resultado =$this ->requerimientoModel ->crear($clienteId, $titulo, $descripcion);
        
        if ($resultado){
            $_SESSION['success'] ='Requerimiento creado exitosamente';

            header('Location: index.php?action=cliente_requerimientos');
        } else{
            $_SESSION['error'] ='Error al crear requerimiento';

            header('Location: index.php?action=cliente_requerimiento_crear_form');
        }
    }
    
    // Detalle de requerimiento
    public function requerimientoDetalle(){
        $id =Validator::sanitizarInput($_GET['id'] ?? '');
        $requerimiento =$this ->requerimientoModel ->obtenerDetalle($id);
        
        if ($requerimiento['cliente_id'] !=$_SESSION['user_id']){
            $_SESSION['error'] ='No tienes permiso';

            header('Location: index.php?action=cliente_requerimientos');
            return;
        }
        
        $historial =$this ->historialModel ->obtenerPorRequerimiento($id);
        
        require_once 'app/views/cliente/requerimiento_detalle.php';
    }
    
    // Formulario para editar requerimiento
    public function requerimientoEditarForm(){
        $id =Validator::sanitizarInput($_GET['id'] ?? '');
        $requerimiento =$this ->requerimientoModel ->obtenerDetalle($id);
        
        if ($requerimiento['cliente_id'] !=$_SESSION['user_id'] || $requerimiento['estado'] !=='pendiente'){
            $_SESSION['error'] ='No tienes permiso';
            header('Location: index.php?action=cliente_requerimientos');
            return;
        }
        require_once 'app/views/cliente/requerimiento_editar.php';
    }
    
    // Editar requerimiento
    public function requerimientoEditar(){
        if ($_SERVER['REQUEST_METHOD'] !=='POST'){

            header('Location: index.php?action=cliente_requerimientos');
            return;
        }
        
        $id =Validator::sanitizarInput($_POST['id'] ?? '');
        $titulo =Validator::sanitizarInput($_POST['titulo'] ?? '');
        $descripcion =Validator::sanitizarInput($_POST['descripcion'] ?? '');
        $requerimiento =$this ->requerimientoModel ->obtenerDetalle($id);
        
        if ($requerimiento['cliente_id'] !=$_SESSION['user_id'] || $requerimiento['estado'] !=='pendiente'){
            $_SESSION['error'] ='No tienes permiso';

            header('Location: index.php?action=cliente_requerimientos');
            return;
        }
        
        $resultado =$this ->requerimientoModel ->editar($id, $titulo, $descripcion, $_SESSION['user_id']);
        
        if ($resultado){
            $_SESSION['success'] ='Requerimiento actualizado';
        } else{
            $_SESSION['error'] ='Error al actualizar';
        }
        header('Location: index.php?action=cliente_requerimientos');
    }
    
    // Eliminar requerimiento
    public function requerimientoEliminar(){
        $id =Validator::sanitizarInput($_GET['id'] ?? '');
        $requerimiento =$this ->requerimientoModel ->obtenerDetalle($id);
        
        if ($requerimiento['cliente_id'] !=$_SESSION['user_id'] || $requerimiento['estado'] !=='pendiente'){
            $_SESSION['error'] ='No tienes permiso';

            header('Location: index.php?action=cliente_requerimientos');
            return;
        }
        
        $resultado =$this ->requerimientoModel ->eliminar($id, $_SESSION['user_id']);
        
        if ($resultado){

            header('Location: index.php?action=cliente_requerimientos&deleted=true');
        } else{
            $_SESSION['error'] ='Error al eliminar';

            header('Location: index.php?action=cliente_requerimientos');
        }
    }
    
    // Listar facturas del cliente
    public function facturas(){
        $facturas =$this ->facturaModel ->listarPorCliente($_SESSION['user_id']);

        require_once 'app/views/cliente/facturas.php';
    }
    
    // Ver detalles de factura vía AJAX
    public function facturaVerAjax(){
        $id =Validator::sanitizarInput($_GET['id'] ?? '');
        $factura =$this ->facturaModel ->obtenerPorId($id);
        
        if ($factura['cliente_id'] !=$_SESSION['user_id']){
            echo '<div style="padding:20px;color:red;">No tienes permiso</div>';
            return;
        }
        
        $facturaDetalle =$this ->facturaModel ->obtenerDetalleCompleto($id);
        $facturaDetalle['logo'] =$this ->configModel ->obtener()['logo'] ?? null;
        
        require_once 'app/views/cliente/factura_ver_ajax.php';
    }
    
    // Descargar factura en PDF
    public function facturaDescargar(){
        $id =Validator::sanitizarInput($_GET['id'] ?? '');
        $factura =$this ->facturaModel ->obtenerPorId($id);
        
        if ($factura['cliente_id'] !=$_SESSION['user_id']) {
            $_SESSION['error'] ='No tienes permiso';

            header('Location: index.php?action=cliente_facturas');
            return;
        }
        
        $facturaDetalle =$this ->facturaModel ->obtenerDetalleCompleto($id);
        $facturaDetalle['logo'] =$this ->configModel ->obtener()['logo'] ?? null;
        
        require_once 'app/views/cliente/factura_pdf.php';
    }
}