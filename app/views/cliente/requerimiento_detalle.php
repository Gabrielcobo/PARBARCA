<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Requerimiento - Cliente</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/requerimientos.css">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Detalle del Requerimiento</h1>
                    <a href="index.php?action=cliente_requerimientos" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                </div>
                <div class="card mb-4">
                    <div class="card-header"><i class="fa-solid fa-info-circle"></i> Información</div>
                    <div class="card-body">
                        <p><strong>Título:</strong> <?php echo Validator::sanitizarOutput($requerimiento['titulo']); ?></p>
                        <p><strong>Descripción:</strong> <?php echo nl2br(Validator::sanitizarOutput($requerimiento['descripcion'])); ?></p>
                        <p><strong>Fecha de creación:</strong> <?php echo date('d/m/Y H:i', strtotime($requerimiento['created_at'])); ?></p>
                        <p><strong>Estado:</strong> <span class="badge estado-<?php echo $requerimiento['estado']; ?>"><?php echo Validator::sanitizarOutput(ucfirst(str_replace('_', ' ', $requerimiento['estado']))); ?></span></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><i class="fa-solid fa-clock"></i> Historial de Cambios</div>
                    <div class="card-body">
                        <?php if (count($historial) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr><th>Fecha</th><th>Usuario</th><th>Estado Anterior</th><th>Estado Nuevo</th><th>Comentario</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($historial as $h): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($h['created_at'])); ?></td>
                                            <td><?php echo Validator::sanitizarOutput($h['usuario_nombre'] . ' ' . ($h['usuario_apellido'] ?? '')); ?></td>
                                            <td><?php echo Validator::sanitizarOutput(ucfirst(str_replace('_', ' ', $h['estado_anterior'] ?? 'Creación'))); ?></td>
                                            <td><?php echo Validator::sanitizarOutput(ucfirst(str_replace('_', ' ', $h['estado_nuevo']))); ?></td>
                                            <td><?php echo Validator::sanitizarOutput($h['comentario'] ?: '—'); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay historial de cambios registrado</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="./public/asset/js/bootstrap.bundle.min.js"></script>
</body>
</html>