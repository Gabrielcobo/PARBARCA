<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Facturas - Cliente</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/factura.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>public/asset/img/logo.png">
</head>
<body>
<?php 
// menu lateral
$base_path = dirname(__DIR__); include $base_path . '/sidebar.php'; 
?>

<main class="content-wrapper">
    <div class="main-container">
        <div class="form-section">
            <div class="brand-container"><span class="brand-name">Parbarca</span></div>
            <div class="form-wrapper">
                <h1>Mis Facturas</h1>
                <p style="font-size: 0.85rem; color: #666; margin-bottom: 20px;">Consulta tus facturas emitidas.</p>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo Validator::sanitizarOutput($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?php echo Validator::sanitizarOutput($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="search-section">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Buscar por número de factura..." onkeyup="filtrarFacturas()">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="data-table" id="facturasTable">
                        <thead>
                            <tr><th>N° Factura</th><th>Fecha</th><th>Monto</th><th>Estado</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                            <?php if(count($facturas) > 0): ?>
                                <?php foreach($facturas as $factura): ?>
                                <tr class="factura-row" data-numero="<?php echo strtolower($factura['numero_factura']); ?>">
                                    <td><strong><?php echo Validator::sanitizarOutput($factura['numero_factura']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($factura['fecha_emision'])); ?></td>
                                    <td>$<?php echo number_format($factura['monto_total'], 2); ?></td>
                                    <td><span class="badge estado-<?php echo $factura['estado']; ?>"><?php echo Validator::sanitizarOutput(ucfirst($factura['estado'])); ?></span></td>
                                    <td class="actions">
                                        <button class="btn-icon btn-view" onclick="verFactura(<?php echo $factura['id']; ?>)" title="Ver Factura"><i class="fa-solid fa-eye"></i></button>
                                        <button class="btn-icon btn-download" onclick="descargarFactura(<?php echo $factura['id']; ?>)" title="Descargar PDF"><i class="fa-solid fa-download"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No tienes facturas registradas</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="modalFactura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="facturaContenido"></div>
        </div>
    </div>
</div>
<script src="./public/asset/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/factura.js"></script>
</body>
</html>