<?php

$currentAction =$_GET['action'] ?? 'dashboard';
$rol =$_SESSION['user_rol'] ?? 'cliente';
?>

<link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/sidebar_style.css">

<nav class="sidebar" id="sidebar">
    <div class="sidebar-header" id="sidebarToggle">
        <div style="display: flex; align-items: center; gap: 12px; flex: 1; cursor: pointer;">
            <img src="<?php echo BASE_URL; ?>public/asset/img/logo.png" alt="Logo Parbarca" class="sidebar-logo">
            <span class="brand-name">Parbarca</span>
        </div>
    </div>
    <div class="menu-content">
        
        <!-- DASHBOARD -->
        <div class="menu-section">
            <p class="menu-title">Principal</p>
            <?php if($rol =='admin'): ?>
                <a href="index.php?action=admin_dashboard" class="menu-item <?php echo ($currentAction =='admin_dashboard') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            <?php elseif($rol =='empleado'): ?>
                <a href="index.php?action=empleado_dashboard" class="menu-item <?php echo ($currentAction =='empleado_dashboard') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Mi Panel</span>
                </a>
            <?php else: ?>
                <a href="index.php?action=cliente_dashboard" class="menu-item <?php echo ($currentAction =='cliente_dashboard') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Mi Panel</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- SECCIÓN ADMIN -->
        <?php if($rol =='admin'): ?>
        <div class="menu-section">
            <p class="menu-title">Gestión</p>
            <a href="index.php?action=admin_empleados" class="menu-item <?php echo ($currentAction =='admin_empleados') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users-gear"></i>
                <span>Empleados</span>
            </a>
            <a href="index.php?action=admin_configuracion" class="menu-item <?php echo ($currentAction =='admin_configuracion') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gears"></i>
                <span>Configuración</span>
            </a>
        </div>
        <?php endif; ?>

        <!-- CLIENTES -->
        <?php if($rol =='admin' || $rol =='empleado'): ?>
        <div class="menu-section">
            <p class="menu-title">Clientes</p>
            <?php if($rol =='admin'): ?>
                <a href="index.php?action=admin_clientes" class="menu-item <?php echo ($currentAction =='admin_clientes') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Clientes</span>
                </a>
            <?php else: ?>
                <a href="index.php?action=empleado_clientes" class="menu-item <?php echo ($currentAction =='empleado_clientes') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Clientes</span>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- REQUERIMIENTOS -->
        <div class="menu-section">
            <p class="menu-title">Requerimientos</p>
            <?php if($rol =='admin'): ?>
                <a href="index.php?action=admin_requerimientos" class="menu-item <?php echo ($currentAction =='admin_requerimientos') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Requerimientos</span>
                </a>
            <?php elseif($rol =='empleado'): ?>
                <a href="index.php?action=empleado_requerimientos" class="menu-item <?php echo ($currentAction =='empleado_requerimientos') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Requerimientos</span>
                </a>
            <?php else: ?>
                <a href="index.php?action=cliente_requerimientos" class="menu-item <?php echo ($currentAction =='cliente_requerimientos') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Mis Requerimientos</span>
                </a>
                <a href="index.php?action=cliente_requerimiento_crear_form" class="menu-item <?php echo ($currentAction =='cliente_requerimiento_crear_form') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Nuevo Requerimiento</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- FACTURAS -->
        <div class="menu-section">
            <p class="menu-title">Facturas</p>
            <?php if($rol =='admin'): ?>
                <a href="index.php?action=admin_facturas" class="menu-item <?php echo ($currentAction =='admin_facturas') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Facturas</span>
                </a>
            <?php elseif($rol =='empleado'): ?>
                <a href="index.php?action=empleado_facturas" class="menu-item <?php echo ($currentAction =='empleado_facturas') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Mis Facturas</span>
                </a>
                <a href="index.php?action=empleado_factura_crear_form" class="menu-item <?php echo ($currentAction =='empleado_factura_crear_form') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Crear Factura</span>
                </a>
            <?php else: ?>
                <a href="index.php?action=cliente_facturas" class="menu-item <?php echo ($currentAction =='cliente_facturas') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Mis Facturas</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- REPORTES -->
        <?php if($rol =='admin'): ?>
        <div class="menu-section">
            <p class="menu-title">Reportes</p>
            <a href="index.php?action=admin_reporte_facturacion" class="menu-item <?php echo ($currentAction =='admin_reporte_facturacion') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Facturación</span>
            </a>
            <a href="index.php?action=admin_reporte_requerimientos" class="menu-item <?php echo ($currentAction =='admin_reporte_requerimientos') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Requerimientos</span>
            </a>
        </div>
        <?php endif; ?>

        <!-- USUARIO -->
        <div class="menu-section bottom">
            <p class="menu-title">Usuario</p>
            <?php if($rol =='admin'): ?>
                <a href="index.php?action=admin_mi_perfil" class="menu-item <?php echo ($currentAction =='admin_mi_perfil') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user"></i>
                    <span>Mi Perfil</span>
                </a>
            <?php elseif($rol =='empleado'): ?>
                <a href="index.php?action=empleado_mi_perfil" class="menu-item <?php echo ($currentAction =='empleado_mi_perfil') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user"></i>
                    <span>Mi Perfil</span>
                </a>
            <?php else: ?>
                <a href="index.php?action=cliente_mi_perfil" class="menu-item <?php echo ($currentAction =='cliente_mi_perfil') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user"></i>
                    <span>Mi Perfil</span>
                </a>
            <?php endif; ?>
            <a href="index.php?action=logout" class="menu-item logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>
</nav>
<script src="<?php echo BASE_URL; ?>public/asset/js/sidebar.js"></script>