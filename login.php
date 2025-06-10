<?php
require_once 'conexion.php';
require_once 'utils_auditoria.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Buscar el usuario
    $stmt = $pdo->prepare("SELECT id, password FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    // Contar intentos fallidos recientes (última hora)
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM intentos_login WHERE usuario = ? AND resultado = 'fallido' AND fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt2->execute([$usuario]);
    $cantidad_intentos = (int)$stmt2->fetchColumn();

    if ($user && password_verify($clave, $user['password'])) {
        $_SESSION['usuario_id'] = $user['id'];
        // Registrar intento login exitoso
        registrar_intento_login($pdo, $user['id'], $usuario, 'exitoso', null, $cantidad_intentos);
        // Registrar evento en trazabilidad
        registrar_evento_trazabilidad($pdo, $user['id'], 'login');
        header('Location: panel.php');
        exit();
    } else {
        $motivo = $user ? 'Contraseña incorrecta' : 'Usuario no encontrado';
        // Registrar intento login fallido
        registrar_intento_login(
            $pdo,
            $user ? $user['id'] : null,
            $usuario,
            'fallido',
            $motivo,
            $cantidad_intentos + 1
        );
        echo "Usuario o contraseña incorrectos";
    }
}
?>