<?php
session_start();
$errores = $_SESSION['errores'] ?? [];
$valores = $_SESSION['valores'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="Estilos/estilosregistro.css">
</head>
  <body>
    <div class="contenedor">
      <h2>Registro</h2>
        <form method="post" action="registrar.php">
            <label for="nombre">Nombre</label>
            <?php if (isset($errores['nombre'])): ?>
                <div class="error-msg"><?= $errores['nombre'] ?></div>
            <?php endif; ?>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($valores['nombre'] ?? '') ?>" required>

            <label for="apellido">Apellido</label>
            <?php if (isset($errores['apellido'])): ?>
                <div class="error-msg"><?= $errores['apellido'] ?></div>
            <?php endif; ?>
            <input type="text" name="apellido" id="apellido" value="<?= htmlspecialchars($valores['apellido'] ?? '') ?>" required>

            <label for="correo">Correo</label>
            <?php if (isset($errores['correo'])): ?>
                <div class="error-msg"><?= $errores['correo'] ?></div>
            <?php endif; ?>
            <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($valores['correo'] ?? '') ?>" required>

            <label for="usuario">Usuario</label>
            <?php if (isset($errores['usuario'])): ?>
                <div class="error-msg"><?= $errores['usuario'] ?></div>
            <?php endif; ?>
            <input type="text" name="usuario" id="usuario" value="<?= htmlspecialchars($valores['usuario'] ?? '') ?>" required>

            <label for="clave">Contraseña</label>
            <?php if (isset($errores['clave'])): ?>
                <div class="error-msg"><?= $errores['clave'] ?></div>
            <?php endif; ?>
            <input type="password" name="clave" id="clave" required>

            <label for="sexo">Sexo</label>
            <?php if (isset($errores['sexo'])): ?>
                <div class="error-msg"><?= $errores['sexo'] ?></div>
            <?php endif; ?>
            <select name="sexo" id="sexo" required>
                <option value="">Selecciona</option>
                <option value="M" <?= (isset($valores['sexo']) && $valores['sexo'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= (isset($valores['sexo']) && $valores['sexo'] == 'F') ? 'selected' : '' ?>>Femenino</option>
            </select>

            <button type="submit">Registrar</button>
        </form>
    </div>
  </body>
  </html>