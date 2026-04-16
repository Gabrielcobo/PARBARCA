<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Parbarca</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/menu-style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>public/asset/img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-page">
        <header class="dashboard-banner">
            <div class="banner-overlay">
                <div class="banner-text">
                    <h1>Bienvenido a Parbarca</h1>
                    <p>Gestión integral de proyectos y control administrativo.</p>
                    <p class="rol-badge"><?php echo ucfirst($_SESSION['user_rol'] ?? 'cliente'); ?> - Panel de control</p>
                </div>
            </div>
        </header>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN ADMIN (solo visible para admin) -->
        <?php if($_SESSION['user_rol'] =='admin'): ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box blue"><i class="fa-solid fa-folder-open"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_requerimientos ?? 0; ?></h3>
                    <p>Total Requerimientos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box orange"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $requerimientos_pendientes ?? 0; ?></h3>
                    <p>Pendientes</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box green"><i class="fa-solid fa-check-double"></i></div>
                <div class="stat-info">
                    <h3><?php echo $requerimientos_finalizados ?? 0; ?></h3>
                    <p>Finalizados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box purple"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_clientes_activos ?? 0; ?></h3>
                    <p>Clientes Activos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box teal"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="stat-info">
                    <h3><?php echo number_format($facturacion_mes_actual ?? 0, 2); ?> $</h3>
                    <p>Facturación del Mes</p>
                </div>
            </div>
        </div>

        <!-- Actividad Reciente para Admin -->
        <div class="content-body">
            <div class="card-large">
                <div class="card-header">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Actividad Reciente</h3>
                </div>
                <?php if(!empty($actividad_reciente)): ?>
                    <div class="activity-timeline">
                        <?php foreach($actividad_reciente as $actividad): ?>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="timeline-user"><?php echo $actividad['usuario']; ?></span>
                                        <span class="timeline-date"><?php echo $actividad['fecha']; ?></span>
                                    </div>
                                    <div class="timeline-body">
                                        <span class="timeline-action"><?php echo $actividad['accion']; ?></span>
                                        <span class="timeline-detail"><?php echo $actividad['detalle']; ?></span>
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

        
        <!-- SECCIÓN EMPLEADO (solo visible para empleado) -->
        <?php elseif($_SESSION['user_rol'] =='empleado'): ?>  
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box blue"><i class="fa-solid fa-folder-open"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_requerimientos_empleado ?? 0; ?></h3>
                    <p>Total Requerimientos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box orange"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $pendientes_empleado ?? 0; ?></h3>
                    <p>Pendientes</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box green"><i class="fa-solid fa-check-double"></i></div>
                <div class="stat-info">
                    <h3><?php echo $finalizados_empleado ?? 0; ?></h3>
                    <p>Finalizados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box teal"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="stat-info">
                    <h3><?php echo number_format($total_facturado_empleado ?? 0, 2); ?> $</h3>
                    <p>Mi Facturación</p>
                </div>
            </div>
        </div>

        <!-- Actividad Reciente para Empleado -->
        <div class="content-body">
            <div class="card-large">
                <div class="card-header">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Mi Actividad</h3>
                </div>
                <?php if(!empty($actividad_reciente_empleado)): ?>
                    <div class="activity-timeline">
                        <?php foreach($actividad_reciente_empleado as $actividad): ?>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <i class="fa-solid fa-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="timeline-date"><?php echo $actividad['fecha']; ?></span>
                                    </div>
                                    <div class="timeline-body">
                                        <span class="timeline-detail"><?php echo $actividad['mensaje']; ?></span>
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

        
        <!-- SECCIÓN CLIENTE -->
         <?php elseif($_SESSION['user_rol'] =='cliente'): ?>
        <?php else: ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box blue"><i class="fa-solid fa-folder-open"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_requerimientos_cliente ?? 0; ?></h3>
                    <p>Mis Requerimientos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box orange"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $pendientes_cliente ?? 0; ?></h3>
                    <p>Pendientes</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box green"><i class="fa-solid fa-check-double"></i></div>
                <div class="stat-info">
                    <h3><?php echo $finalizados_cliente ?? 0; ?></h3>
                    <p>Finalizados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box teal"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="stat-info">
                    <h3><?php echo number_format($total_facturado_cliente ?? 0, 2); ?> $</h3>
                    <p>Mis Facturas</p>
                </div>
            </div>
        </div>

        <!-- Requerimientos Recientes para Cliente -->
        <div class="content-body">
            <div class="card-large">
                <div class="card-header">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Mis Requerimientos Recientes</h3>
                </div>
                <?php if(!empty($requerimientos_recientes)): ?>
                    <div class="activity-timeline">
                        <?php foreach($requerimientos_recientes as $req): ?>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <i class="fa-solid fa-file-alt"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="timeline-title"><?php echo htmlspecialchars($req['titulo']); ?></span>
                                        <span class="timeline-date"><?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?></span>
                                    </div>
                                    <div class="timeline-body">
                                        <span class="timeline-status status-<?php echo $req['estado']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $req['estado'])); ?>
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
                        <a href="index.php?action=cliente_requerimiento_crear_form" class="btn-primary">Crear mi primer requerimiento</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </main>
    <script src="<?php echo BASE_URL; ?>public/asset/js/dashboard.js"></script>
</body>
</html>