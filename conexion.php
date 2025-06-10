<?php
$host = "localhost";
$usuario = "lab2fa";
$clave = "lab2fapass"; 
$bd = "autenticador2fa"; 

$conn = new mysqli($host, $usuario, $clave, $bd);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
