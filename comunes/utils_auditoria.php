<?php
function registrar_evento_trazabilidad($conn, $usuario_id, $evento) {
    $stmt = $conn->prepare("INSERT INTO trazabilidad (usuario_id, evento) VALUES (?, ?)");
    $stmt->bind_param("is", $usuario_id, $evento);
    $stmt->execute();
    $stmt->close();
}

function registrar_intento_login($conn, $usuario_id, $usuario, $resultado, $motivo_fallo, $cantidad_intentos) {
    $stmt = $conn->prepare("INSERT INTO intentos_login (usuario_id, usuario, resultado, motivo_fallo, cantidad_intentos) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $usuario_id, $usuario, $resultado, $motivo_fallo, $cantidad_intentos);
    $stmt->execute();
    $stmt->close();
}
?>