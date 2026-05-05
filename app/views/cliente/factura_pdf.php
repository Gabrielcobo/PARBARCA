<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo Validator::sanitizarOutput($facturaDetalle['numero_factura']); ?></title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/facturas_pdf.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>public/asset/img/logo.png">
</head>
<body>
    <div class="container py-3" id="facturaParaPDF" data-numero-factura="<?php echo Validator::sanitizarOutput($facturaDetalle['numero_factura']); ?>">
        
        <!-- Encabezado -->
        <div class="factura-header">
            <div class="logo-section">
                <img src="<?php echo BASE_URL; ?>public/asset/img/logo.png" alt="Logo" class="logo-factura">
            </div>
            <div class="empresa-section">
                <h2><?php echo Validator::sanitizarOutput($facturaDetalle['empresa_nombre']); ?></h2>
                <div class="empresa-datos">
                    <?php if(!empty($facturaDetalle['empresa_rif'])): ?>
                        RIF: <?php echo Validator::sanitizarOutput($facturaDetalle['empresa_rif']); ?><br>
                    <?php endif; ?>
                    <?php if(!empty($facturaDetalle['empresa_direccion'])): ?>
                        <?php echo Validator::sanitizarOutput($facturaDetalle['empresa_direccion']); ?><br>
                    <?php endif; ?>
                    <?php if(!empty($facturaDetalle['empresa_telefono'])): ?>
                        Tel: <?php echo Validator::sanitizarOutput($facturaDetalle['empresa_telefono']); ?><br>
                    <?php endif; ?>
                    <?php if(!empty($facturaDetalle['empresa_email'])): ?>
                        Email: <?php echo Validator::sanitizarOutput($facturaDetalle['empresa_email']); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="factura-titulo">
                <h1 class="text-primary">FACTURA</h1>
                <div class="factura-numero">N° <?php echo Validator::sanitizarOutput($facturaDetalle['numero_factura']); ?></div>
            </div>
        </div>

        <!-- Información del cliente y factura -->
        <div class="factura-info row g-3">
            <div class="col-md-6">
                <div class="info-cliente">
                    <strong>Cliente:</strong><br>
                    <?php echo Validator::sanitizarOutput($facturaDetalle['cliente_nombre'] . ' ' . $facturaDetalle['cliente_apellido']); ?><br>
                    <?php echo Validator::sanitizarOutput($facturaDetalle['cliente_email']); ?><br>
                    <?php echo Validator::sanitizarOutput($facturaDetalle['cliente_telefono']); ?><br>
                    <?php echo Validator::sanitizarOutput($facturaDetalle['cliente_cedula']); ?><br>
                    <?php echo Validator::sanitizarOutput($facturaDetalle['cliente_direccion']); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-factura">
                    <p><strong>Fecha de emisión:</strong> <?php echo date('d/m/Y', strtotime($facturaDetalle['fecha_emision'])); ?></p>
                    <p><strong>Empleado:</strong> <?php echo Validator::sanitizarOutput($facturaDetalle['empleado_nombre'] . ' ' . $facturaDetalle['empleado_apellido']); ?></p>
                    <p><strong>Estado:</strong> 
                        <span class="badge estado-<?php echo $facturaDetalle['factura_estado']; ?>">
                            <?php echo Validator::sanitizarOutput(ucfirst($facturaDetalle['factura_estado'])); ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Detalle del servicio -->
        <div class="factura-detalle">
            <h3>Detalle del Servicio</h3>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo nl2br(Validator::sanitizarOutput($facturaDetalle['factura_descripcion'])); ?></td>
                        <td class="text-end fw-bold">$<?php echo number_format($facturaDetalle['monto_total'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="text-end mb-4">
            <span class="bg-light p-3 rounded fw-bold fs-5">
                <strong>TOTAL:</strong> $<?php echo number_format($facturaDetalle['monto_total'], 2); ?>
            </span>
        </div>

        <!-- Pie de página -->
        <div class="text-center border-top pt-3 mt-4">
            <p class="small text-muted">Gracias por confiar en <?php echo Validator::sanitizarOutput($facturaDetalle['empresa_nombre']); ?></p>
            <p class="small text-muted">Este documento es una factura válida para efectos legales.</p>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="./public/asset/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/asset/js/factura_pdf.js"></script>
</body>
</html>