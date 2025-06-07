<?php
require 'conexion.php';
require 'vendor/autoload.php';
require_once 'clases/SanitizarEntrada.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class RegistroUsuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registrar($datos) {
        session_start();
        $errores = [];
        $valores = [
            'nombre'   => $datos['nombre'] ?? '',
            'apellido' => $datos['apellido'] ?? '',
            'correo'   => $datos['correo'] ?? '',
            'usuario'  => $datos['usuario'] ?? '',
            'sexo'     => $datos['sexo'] ?? ''
        ];

        // Sanitizar entradas
        $nombre   = SanitizarEntrada::limpiarNombreApellido($valores['nombre']);
        $apellido = SanitizarEntrada::limpiarNombreApellido($valores['apellido']);
        $correo   = SanitizarEntrada::limpiarCorreo($valores['correo']);
        $usuario  = SanitizarEntrada::limpiarUsuario($valores['usuario']);
        $sexo     = SanitizarEntrada::limpiarSexo($valores['sexo']);
        $password = SanitizarEntrada::limpiarCadena($datos['clave'] ?? '');

        // Validar usuario
        if ($usuario === null) {
            $errores['usuario'] = "El usuario solo puede contener letras, números, guiones y guiones bajos (3-20 caracteres).";
        }

        // Validar contraseña
        if (!SanitizarEntrada::validarPassword($password)) {
            $errores['clave'] = "La contraseña debe tener al menos 8 caracteres, minimo una mayuscula y solo caracteres permitidos.";
        }

        // Validaciones básicas
        if (empty($nombre))   $errores['nombre'] = "El nombre es obligatorio y debe ser válido.";
        if (empty($apellido)) $errores['apellido'] = "El apellido es obligatorio y debe ser válido.";
        if (empty($correo))   $errores['correo'] = "El correo es obligatorio y debe ser válido.";
        if (empty($usuario))  $errores['usuario'] = "El usuario es obligatorio y debe ser válido.";
        if (empty($password)) $errores['clave'] = "La contraseña es obligatoria.";
        if (empty($sexo))     $errores['sexo'] = "El sexo es obligatorio y debe ser válido.";

        // Verificar si el correo ya existe
        if (empty($errores['correo']) && $this->existeCampo('correo', $correo)) {
            $errores['correo'] = "⚠️ Ya existe un usuario con ese correo.";
        }

        // Verificar si el usuario ya existe
        if (empty($errores['usuario']) && $this->existeCampo('usuario', $usuario)) {
            $errores['usuario'] = "⚠️ Ya existe una cuenta con ese usuario.";
        }

        // Si hay errores, guardar en sesión y redirigir
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['valores'] = $valores;
            header("Location: registro.php");
            exit();
        }

        // Generar secret 2FA
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();

        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Guardar en la base de datos
        $sqlInsert = "INSERT INTO usuarios (nombre, apellido, correo, usuario, HashMagic, sexo, secret_2fa) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sqlInsert);
        $stmt->bind_param("sssssss", $nombre, $apellido, $correo, $usuario, $hashedPassword, $sexo, $secret);
        if ($stmt->execute()) {
            unset($_SESSION['errores'], $_SESSION['valores']); 
            $_SESSION['Usuario'] = $usuario;
            $_SESSION['secret_2fa'] = $secret;

            $qrUrl = GoogleQrUrl::generate($usuario, $secret, 'Autenticador2FA');

            echo <<<HTML
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Registro exitoso</title>
                <link rel="stylesheet" href="Estilos/estilosregistro.css">
            </head>
            <body>
                <div class="qr-container">
                    <h2>✔️ Registro exitoso</h2>
                    <p>Escanea este código QR con Google Authenticator:</p>
                    <img src="$qrUrl" alt="Código QR 2FA">
                    <p>O introduce este código manualmente:</p>
                    <code>$secret</code>
                    <br>
                    <a href="login_form.php">Continuar</a>
                </div>
            </body>
            </html>
            HTML;
        } else {
            // Error al registrar usuario
            $_SESSION['errores'] = ['general' => "❌ Error al registrar usuario."];
            $_SESSION['valores'] = $valores;
            header("Location: registro.php");
        }
        $stmt->close();
    }

    private function existeCampo($campo, $valor) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE $campo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $cuenta = 0;
        $stmt->bind_result($cuenta);
        $stmt->fetch();
        $stmt->close();
        return $cuenta > 0;
    }
}

// Uso de la clase
$registro = new RegistroUsuario($conn);
$registro->registrar($_POST);

$conn->close();
?>
