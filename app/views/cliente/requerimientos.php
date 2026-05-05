<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Requerimientos - Cliente</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/requerimientos.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/sweetalert2.min.css">
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
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <h1>Mis Requerimientos</h1>
                </div>
                <p class="small text-muted mb-4">Gestiona tus solicitudes de servicios.</p>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo Validator::sanitizarOutput($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo Validator::sanitizarOutput($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="search-section">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Buscar por título o descripción..." onkeyup="filtrarRequerimientos()">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="requerimientosTable">
                        <thead class="table-light">
                            <tr><th>Título</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                            <?php if(count($requerimientos) > 0): ?>
                                <?php foreach($requerimientos as $req): ?>
                                <tr class="requerimiento-row" data-id="<?php echo $req['id']; ?>">
                                    <td><?php echo Validator::sanitizarOutput($req['titulo']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?></td>
                                    <td><span class="badge estado-<?php echo $req['estado']; ?>"><?php echo Validator::sanitizarOutput(ucfirst(str_replace('_', ' ', $req['estado']))); ?></span></td>
                                    <td class="actions">
                                        <button class="btn-icon btn-view" onclick="verDetalle(<?php echo $req['id']; ?>)" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                                        <?php if($req['estado'] == 'pendiente'): ?>
                                            <button class="btn-icon btn-edit" onclick="editarRequerimiento(<?php echo $req['id']; ?>)" title="Editar"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon btn-delete" onclick="eliminarRequerimiento(<?php echo $req['id']; ?>)" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">No tienes requerimientos registrados</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<div id="modalDetalle" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header"><h2>Detalle del Requerimiento</h2><button class="modal-close" onclick="cerrarModalDetalle()">&times;</button></div>
        <div id="detalleContenido"></div>
    </div>
</div>
<script src="./public/asset/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/sweetalert2.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/requerimiento.js"></script>
</body>
</html>