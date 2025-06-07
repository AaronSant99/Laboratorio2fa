<?php
$host = "localhost";
$usuario = "ComodinUser7";
$clave = "comodincontra77"; 
$bd = "autenticador2fa"; 

$conn = new mysqli($host, $usuario, $clave, $bd);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
