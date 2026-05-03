<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Cliente</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/perfil.css">
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
            <div class="form-wrapper">
                <div class="brand-container"><span class="brand-name">Parbarca</span></div>
                <h1>Mi Perfil</h1>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo Validator::sanitizarOutput($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?php echo Validator::sanitizarOutput($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=perfil_editar" method="POST" id="formPerfil">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo Validator::sanitizarOutput($usuario['nombre']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo Validator::sanitizarOutput($usuario['apellido']); ?>" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" id="cedula" name="cedula" class="form-control" value="<?php echo Validator::sanitizarOutput($usuario['cedula']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo Validator::sanitizarOutput($usuario['telefono']); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="hidden" name="email" value="<?php echo Validator::sanitizarOutput($usuario['email']); ?>">
                        <input type="email" id="email" class="form-control bg-light text-muted" value="<?php echo Validator::sanitizarOutput($usuario['email']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea id="direccion" name="direccion" class="form-control" rows="2"><?php echo Validator::sanitizarOutput($usuario['direccion']); ?></textarea>
                    </div>
                    <button type="button" class="btn btn-primary w-100" id="btnGuardar">Guardar Cambios</button>
                </form>
                <div class="change-password-section">
                    <hr><h3>Cambiar Contraseña</h3>
                    <form action="index.php?action=cambiar_password" method="POST" id="formPassword">
                            <label for="password_actual" class="form-label">Contraseña Actual</label>
                            <input type="password" id="password_actual" name="password_actual" class="form-control" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                                <input type="password" id="password_nueva" name="password_nueva" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmar" class="form-label">Confirmar Contraseña</label>
                                <input type="password" id="password_confirmar" name="password_confirmar" class="form-control" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary w-100" id="btnPassword">Actualizar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="./public/asset/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/sweetalert2.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/perfil.js"></script>
</body>
</html>