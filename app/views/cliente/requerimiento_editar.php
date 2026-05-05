<?php

use Core\Validator;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Requerimiento - Cliente</title>
    <link rel="stylesheet" href="./public/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/asset/css/formulario.css">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Editar Requerimiento</h1>
                    <a href="index.php?action=cliente_requerimientos" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                </div>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo Validator::sanitizarOutput($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=cliente_requerimiento_editar" method="POST" id="formEditar">
                    <input type="hidden" name="id" value="<?php echo $requerimiento['id']; ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Titulo</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo Validator::sanitizarOutput($requerimiento['titulo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="5" required><?php echo Validator::sanitizarOutput($requerimiento['descripcion']); ?></textarea>
                    </div>
                    <button type="button" class="btn btn-danger w-100" onclick="confirmarEditar()">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="./public/asset/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/sweetalert2.min.js"></script>
<script src="<?php echo BASE_URL; ?>public/asset/js/requerimiento.js"></script>
</body>
</html>