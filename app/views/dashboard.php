<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Parbarca</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/menu_style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>public/asset/img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php 
    // Cargar el menú lateral común para todos los roles
    include 'sidebar.php';
     ?>

    <main class="main-page">
        <div class="container-fluid px-0">
            <header class="dashboard-banner">
                <div class="banner-overlay">
                    <div class="banner-text">
                        <h1>Bienvenido a Parbarca</h1>
                        <p>Gestión integral de proyectos y control administrativo.</p>
                        <p class="rol-badge"><?php echo Validator::sanitizarOutput(ucfirst($_SESSION['user_rol'] ?? 'cliente')); ?> - Panel de control</p>
                    </div>
                </div>
            </header>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    <?php echo Validator::sanitizarOutput($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <?php echo Validator::sanitizarOutput($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if($_SESSION['user_rol'] =='admin'): ?>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.1s">
                            <div class="icon-box blue"><i class="fa-solid fa-folder-open"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($totalRequerimientos ?? 0); ?></h3>
                                <p>Total Requerimientos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.2s">
                            <div class="icon-box orange"><i class="fa-solid fa-clock"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($requerimientosPendientes ?? 0); ?></h3>
                                <p>Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.3s">
                            <div class="icon-box green"><i class="fa-solid fa-check-double"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($requerimientosFinalizados ?? 0); ?></h3>
                                <p>Finalizados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.4s">
                            <div class="icon-box purple"><i class="fa-solid fa-users"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($totalClientesActivos ?? 0); ?></h3>
                                <p>Clientes Activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.5s">
                            <div class="icon-box teal"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div class="stat-info">
                                <h3><?php echo number_format((float)($facturacionMesActual ?? 0), 2); ?> $</h3>
                                <p>Facturación del Mes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-large">
                            <div class="card-header-custom">
                                <h3><i class="fa-solid fa-clock-rotate-left"></i> Actividad Reciente</h3>
                            </div>
                            <?php if(!empty($actividadReciente)): ?>
                                <div class="activity-timeline">
                                    <?php foreach($actividadReciente as $actividad): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-icon"><i class="fa-solid fa-user-check"></i></div>
                                            <div class="timeline-content">
                                                <div class="timeline-header">
                                                    <span class="timeline-user"><?php echo Validator::sanitizarOutput($actividad['usuario']); ?></span>
                                                    <span class="timeline-date"><?php echo Validator::sanitizarOutput($actividad['fecha']); ?></span>
                                                </div>
                                                <div class="timeline-body">
                                                    <span class="timeline-action"><?php echo Validator::sanitizarOutput($actividad['accion']); ?></span>
                                                    <span class="timeline-detail"><?php echo Validator::sanitizarOutput($actividad['detalle']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <p>No hay eventos recientes para mostrar.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php elseif($_SESSION['user_rol'] =='empleado'): ?>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.1s">
                            <div class="icon-box blue"><i class="fa-solid fa-folder-open"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($totalRequerimientosEmpleado ?? 0); ?></h3>
                                <p>Total Requerimientos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.2s">
                            <div class="icon-box orange"><i class="fa-solid fa-clock"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($pendientesEmpleado ?? 0); ?></h3>
                                <p>Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.3s">
                            <div class="icon-box green"><i class="fa-solid fa-check-double"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($finalizadosEmpleado ?? 0); ?></h3>
                                <p>Finalizados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.4s">
                            <div class="icon-box teal"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div class="stat-info">
                                <h3><?php echo number_format((float)($totalFacturadoEmpleado ?? 0), 2); ?> $</h3>
                                <p>Mi Facturación</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-large">
                            <div class="card-header-custom">
                                <h3><i class="fa-solid fa-clock-rotate-left"></i> Mi Actividad</h3>
                            </div>
                            <?php if(!empty($actividadRecienteEmpleado)): ?>
                                <div class="activity-timeline">
                                    <?php foreach($actividadRecienteEmpleado as $actividad): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-icon"><i class="fa-solid fa-check-circle"></i></div>
                                            <div class="timeline-content">
                                                <div class="timeline-header">
                                                    <span class="timeline-date"><?php echo Validator::sanitizarOutput($actividad['fecha']); ?></span>
                                                </div>
                                                <div class="timeline-body">
                                                    <span class="timeline-detail"><?php echo Validator::sanitizarOutput($actividad['mensaje']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <p>No hay actividad reciente</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.1s">
                            <div class="icon-box blue"><i class="fa-solid fa-folder-open"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($totalRequerimientosCliente ?? 0); ?></h3>
                                <p>Mis Requerimientos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.2s">
                            <div class="icon-box orange"><i class="fa-solid fa-clock"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($pendientesCliente ?? 0); ?></h3>
                                <p>Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.3s">
                            <div class="icon-box green"><i class="fa-solid fa-check-double"></i></div>
                            <div class="stat-info">
                                <h3><?php echo (int)($finalizadosCliente ?? 0); ?></h3>
                                <p>Finalizados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="stat-card" data-delay="0.4s">
                            <div class="icon-box teal"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div class="stat-info">
                                <h3><?php echo number_format((float)($totalFacturadoCliente ?? 0), 2); ?> $</h3>
                                <p>Mis Facturas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-large">
                            <div class="card-header-custom">
                                <h3><i class="fa-solid fa-clock-rotate-left"></i> Mis Requerimientos Recientes</h3>
                            </div>
                            <?php if(!empty($requerimientosRecientes)): ?>
                                <div class="activity-timeline">
                                    <?php foreach($requerimientosRecientes as $req): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-icon"><i class="fa-solid fa-file-alt"></i></div>
                                            <div class="timeline-content">
                                                <div class="timeline-header">
                                                    <span class="timeline-title"><?php echo Validator::sanitizarOutput($req['titulo']); ?></span>
                                                    <span class="timeline-date"><?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?></span>
                                                </div>
                                                <div class="timeline-body">
                                                    <span class="timeline-status status-<?php echo $req['estado']; ?>">
                                                        <?php echo Validator::sanitizarOutput(ucfirst(str_replace('_', ' ', $req['estado']))); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <p>No tienes requerimientos registrados</p>
                                    <a href="index.php?action=cliente_requerimiento_crear_form" class="btn btn-primary">
                                        <i class="fa-solid fa-plus me-2"></i>Crear mi primer requerimiento
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="<?php echo BASE_URL; ?>public/asset/js/dashboard.js"></script>
</body>
</html>