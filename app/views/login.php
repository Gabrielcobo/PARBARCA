<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Parbarca</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/login_register.css">
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>public/asset/img/logo.png">
</head>
<body class="d-flex align-items-center justify-content-center vh-100"  >
    <div class="container">
        <div class="card border-0 rounded-4 overflow-hidden shadow-lg mx-auto" style="max-width: 900px;">
            <div class="row g-0">
                
                <!-- Contenedor -->
                <div class="col-md-6 bg-white">
                    <div class="p-4 p-lg-5 overflow-auto" style="max-height: 600px;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <img src="public/asset/img/logo.png" alt="Logo" height="38">
                            <span class="fw-bold text-uppercase">Parbarca</span>
                        </div>
                        <h1 class="h3 fw-bold">Bienvenido</h1>
                        <p class="text-muted small mb-4">Ingresa tus credenciales para acceder</p>

                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger py-2 small"><?php echo Validator::sanitizarOutput($_SESSION['error']); unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success py-2 small"><?php echo Validator::sanitizarOutput($_SESSION['success']); unset($_SESSION['success']); ?></div>
                        <?php endif; ?>

                        <form action="index.php?action=login_post" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="usuario@parbarca.com" 
                                       value="<?php echo Validator::sanitizarOutput($_SESSION['old_email'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••••••••" required>
                            </div>
                            <div class="text-end mb-3">
                                <a href="index.php?action=recuperar" class="text-muted small text-decoration-none">¿Olvidaste tu contraseña?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>
                        <p class="text-center text-muted small mt-3 mb-0">
                            ¿No tienes cuenta? <a href="index.php?action=registro" class="text-decoration-none fw-semibold text-primary">Regístrate aquí</a>
                        </p>
                    </div>
                </div>
                
                <!-- IMAGEN -->
                <div class="col-md-6 d-none d-md-block position-relative bg-white p-2">
                    <img src="public/asset/img/fondologin.jfif" alt="Equipo" class="w-100 h-100 rounded-3" style="object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 end-0 m-3 p-3 bg-white rounded-3 d-flex align-items-center gap-3 shadow-sm">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="min-width:40px;height:40px;">📅</div>
                        <div>
                            <small class="text-muted fw-bold d-block">Reunión de Obra</small>
                            <small class="fw-semibold">Hoy: 12:00pm - 01:30pm</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>