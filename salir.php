<?php
include("comunes/loginfunciones.php");
include("conexion.php");
include("comunes/utils_auditoria.php");
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? null;
if ($usuario_id) {
    registrar_evento_trazabilidad($conn, $usuario_id, 'logout');
}

session_destroy();
redireccionar("login.php");
?>