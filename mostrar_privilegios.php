<?php
require 'conexion.php';

$usuario = 'ComodinUser7';
$host = 'localhost';

$sql = "SHOW GRANTS FOR '$usuario'@'$host'";
$result = $conn->query($sql);

echo "<h3>Privilegios del usuario '$usuario':</h3>";
if ($result) {
    while ($row = $result->fetch_row()) {
        echo "<pre>{$row[0]}</pre>";
    }
} else {
    echo "No se pudieron obtener los privilegios.";
}

$conn->close();
?>