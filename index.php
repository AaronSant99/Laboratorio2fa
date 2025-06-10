<?php
include("conexion.php");
include("comunes/utils_auditoria.php");
session_start();

if (isset($_POST['tolog'])) {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['contrasena'] ?? '';

    // Buscar el usuario y su secreto 2FA
    $stmt = $conn->prepare("SELECT id, HashMagic, secret_2fa FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Contar intentos fallidos recientes (última hora)
    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM intentos_login WHERE usuario = ? AND resultado = 'fallido' AND fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt2->bind_param("s", $usuario);
    $stmt2->execute();
    $stmt2->bind_result($cantidad_intentos);
    $stmt2->fetch();
    $stmt2->close();

    $cantidad_intentos = $cantidad_intentos ?? 0;

    if ($user && password_verify($clave, $user['HashMagic'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['Usuario'] = $usuario;
        $_SESSION['secret_2fa'] = $user['secret_2fa'];  // <---- IMPORTANTE
        registrar_intento_login($conn, $user['id'], $usuario, 'exitoso', null, $cantidad_intentos);
        registrar_evento_trazabilidad($conn, $user['id'], 'login');
        header('Location: codigo_2fa.php');
        exit();
    } else {
        $motivo = $user ? 'Contraseña incorrecta' : 'Usuario no encontrado';
        registrar_intento_login(
            $conn,
            $user ? $user['id'] : null,
            $usuario,
            'fallido',
            $motivo,
            $cantidad_intentos + 1
        );
        $_SESSION["emsg"] = 1;
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>