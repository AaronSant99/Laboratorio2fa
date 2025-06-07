<?php
class SanitizarEntrada {

    // Sanitiza nombre y apellido: elimina etiquetas, espacios excesivos y convierte caracteres especiales
    public static function limpiarNombreApellido($cadena) {
        $cadena = strip_tags($cadena);
        $cadena = preg_replace('/\s+/', ' ', $cadena); // Espacios múltiples a uno solo
        $cadena = trim($cadena);
        return htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
    }

    // Sanitiza sexo: solo permite 'M' o 'F'
    public static function limpiarSexo($sexo) {
        $sexo = strtoupper(trim($sexo));
        return ($sexo === 'M' || $sexo === 'F') ? $sexo : null;
    }

    // Sanitiza usuario: solo letras, números, guion y guion bajo, longitud entre 3 y 20
    public static function limpiarUsuario($usuario) {
        // Solo letras (a-z, A-Z), números (0-9), guion (-) y guion bajo (_)
        $usuario = preg_replace('/[^a-zA-Z0-9\-_]/', '', $usuario);
        $usuario = trim($usuario);
        if (strlen($usuario) < 3 || strlen($usuario) > 20) {
            return null;
        }
        return $usuario;
    }

    // Sanitiza correo: valida y limpia
    public static function limpiarCorreo($correo) {
        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return $correo;
        }
        return null;
    }

    // Sanitiza secreto 2FA: solo alfanumérico
    public static function limpiarSecreto2FA($secreto) {
        return ctype_alnum($secreto) ? $secreto : null;
    }

    // Sanitiza una cadena genérica (por ejemplo, clave)
    public static function limpiarCadena($cadena) {
        return trim(strip_tags($cadena));
    }

    // Valida la contraseña: mínimo 8 caracteres, al menos una mayúscula, solo caracteres permitidos
    public static function validarPassword($password) {
        // Solo letras, números y símbolos comunes
        if (!preg_match('/^[A-Za-z0-9!@#$%^&*()_\-+=\[\]{};:,.<>?]+$/', $password)) {
            return false;
        }
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        return true;
    }

}
?>