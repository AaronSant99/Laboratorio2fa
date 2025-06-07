<?php
session_start();
require 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if (!isset($_SESSION['Usuario']) || !isset($_SESSION['secret_2fa'])) {
    header("Location: login_form.php");
    exit();
}

// Validar entrada del usuario
$codigo = $_POST['codigo_2fa'] ?? '';
$secret = $_SESSION['secret_2fa'] ?? '';

if (empty($codigo) || empty($secret)) {
    mostrarMensaje("⚠️ Código o secreto faltante.", "codigo_2fa.php");
    exit();
}

// Inicializar verificador
$g = new GoogleAuthenticator();

// Validar el código
if ($g->checkCode($secret, $codigo)) {
    $_SESSION['verificado_2fa'] = true;
    header("Location: formularios/PanelControl.php");
    exit();
} else {
    mostrarMensaje("❌ Código incorrecto.<br>💡 Asegúrate de escanear el QR generado en el registro más reciente y que la hora del dispositivo esté sincronizada.", "codigo_2fa.php");
    exit();
}

// Función para mostrar mensaje estilizado
function mostrarMensaje($mensaje, $enlace) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Validación 2FA</title>
        <link rel="stylesheet" href="Estilos\estilosregistro.css">
    </head>
    <body>
        <div class="qr-container">
            <h2>Resultado de validación</h2>
            <p>' . $mensaje . '</p>
            <a href="' . htmlspecialchars($enlace) . '">Volver</a>
        </div>
    </body>
    </html>';
}
?>
