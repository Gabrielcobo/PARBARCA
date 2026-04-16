<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Parbarca</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/login_register.css">
    <link rel="icon" type="image/x-icon" href="public/asset/img/logo.png">
</head>
<body>
    <div class="main-container">
        <div class="form-section">
            <div class="brand-container">
                <img src="public/asset/img/logo.png" alt="Logo Parbarca" class="logo-img">
                <span class="brand-name">Parbarca</span>
            </div>
            <div class="form-wrapper">
                <h1>Crear cuenta</h1>
                <p class="subtitle">Regístrate para solicitar nuestros servicios</p>

                <!-- Mostrar mensajes de error  -->
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                            echo htmlspecialchars($_SESSION['error']); 
                            unset($_SESSION['error']); 
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Mostrar mensajes de éxito  -->
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                            echo htmlspecialchars($_SESSION['success']); 
                            unset($_SESSION['success']); 
                        ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=registro_post" method="POST">
                    <div class="row">
                        <div class="input-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan" value="<?php echo $_SESSION['old_nombre'] ?? ''; ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="apellido">Apellido *</label>
                            <input type="text" id="apellido" name="apellido" placeholder="Ej. Pérez" value="<?php echo $_SESSION['old_apellido'] ?? ''; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group">
                            <label for="cedula">Cédula *</label>
                            <input type="text" id="cedula" name="cedula" placeholder="V-00000000" value="<?php echo $_SESSION['old_cedula'] ?? ''; ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="telefono">Teléfono *</label>
                            <input type="tel" id="telefono" name="telefono" placeholder="0412-0000000" value="<?php echo $_SESSION['old_telefono'] ?? ''; ?>" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="email">Correo electrónico *</label>
                        <input type="email" id="email" name="email" placeholder="usuario@parbarca.com" value="<?php echo $_SESSION['old_email'] ?? ''; ?>" required>
                    </div>
                    <div class="row">
                        <div class="input-group">
                            <label for="password">Contraseña *</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="input-group">
                            <label for="confirm_password">Confirmar Contraseña *</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="direccion">Dirección</label>
                        <textarea id="direccion" name="direccion" rows="2" placeholder="Indique su dirección de habitación..."><?php echo $_SESSION['old_direccion'] ?? ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Registrar Usuario</button>
                </form>
            </div>
            <div class="form-footer">
                <p>¿Ya tienes una cuenta? <a href="index.php?action=login">Inicia sesión</a></p>
            </div>
        </div>
        <div class="image-section">
            <img src="public/asset/img/fondologin.jfif" alt="Personal de Parbarca" class="bg-image">
            <div class="overlay-card">
                <div class="overlay-icon">🏗️</div>
                <div class="meeting-info">
                    <span>Gestión de Proyectos</span>
                    <p>Módulo de Control Interno</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>