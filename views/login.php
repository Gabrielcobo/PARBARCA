<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Parbarca</title>
    <link rel="stylesheet" href="public/asset/css/style.css">
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
                <h1>Bienvenido</h1>
                <p class="subtitle">Ingresa tus credenciales para acceder</p>

                <!-- Mostrar mensaje de bienvenida después del login exitoso -->          
                <?php if(isset($_SESSION['welcome'])): ?>
                    <div class="alert alert-success welcome-alert">
                        <?php 
                            echo $_SESSION['welcome']; 
                        ?>
                    </div>
                    
                    <!-- Redirigir automáticamente después de 5 segundos -->
                    <script>
                        setTimeout(function() {
                            window.location.href = 'index.php?action=<?php echo $_SESSION['redirect_after_welcome']; ?>';
                        }, 5000);
                    </script>
                <?php endif; ?>

                <!-- Mostrar mensajes de error -->
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                            echo htmlspecialchars($_SESSION['error']); 
                            unset($_SESSION['error']); 
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Mostrar mensajes de éxito -->
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                            echo htmlspecialchars($_SESSION['success']); 
                            unset($_SESSION['success']); 
                        ?>
                    </div>
                <?php endif; ?>
                <form action="index.php?action=login_post" method="POST">
                    <div class="input-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="usuario@parbarca.com" value="<?php echo $_SESSION['old_email'] ?? ''; ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="••••••••••••••" required>
                    </div>
                    <a href="index.php?action=recuperar_password" class="forgot-password">¿Olvidaste tu contraseña?</a>
                    <button type="submit" class="btn-submit">Iniciar Sesión</button>
                </form>
            </div>
            <div class="form-footer">
                <p>¿No tienes cuenta? <a href="index.php?action=registro">Registrate aqui</a></p>
            </div>
        </div>
        <div class="image-section">
            <img src="public/asset/img/fondologin.jfif" alt="Equipo de Parbarca trabajando" class="bg-image">
            <div class="overlay-card">
                <div class="overlay-icon">📅</div>
                <div class="meeting-info">
                    <span>Reunión de Obra</span>
                    <p>Hoy: 12:00pm - 01:30pm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Limpiar sesión de welcome después de la redirección -->
    <?php if(isset($_SESSION['welcome']) && !isset($_SESSION['redirect_after_welcome'])): ?>
        <?php 
            unset($_SESSION['welcome']);
            unset($_SESSION['redirect_after_welcome']);
        ?>
    <?php endif; ?>
</body>
</html>