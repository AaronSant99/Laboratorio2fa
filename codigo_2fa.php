<?php
session_start();
if (!isset($_SESSION['Usuario']) || !isset($_SESSION['secret_2fa'])) {
    header("Location: login_form.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificación 2FA</title>
  <link rel="stylesheet" href="Estilos\estilosregistro.css">
</head>
<body>
  <div class="contenedor">
    <h2>Introduce el código de Google Authenticator</h2>
    <form method="POST" action="validar_2fa.php">
      <label for="codigo">Código:</label>
      <input type="text" name="codigo_2fa" required>
      <button type="submit">Verificar</button>
    </form>
  </div>
</body>
</html>
