<?php
function cargarEnv($rutaArchivo)
{
    if (!is_file($rutaArchivo)) {
        return [];
    }

    $variables = [];
    $lineas = file($rutaArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lineas === false) {
        return [];
    }

    foreach ($lineas as $linea) {
        $linea = trim($linea);

        if ($linea === '' || strpos($linea, '#') === 0) {
            continue;
        }

        if (strpos($linea, '=') === false) {
            continue;
        }

        [$clave, $valor] = array_map('trim', explode('=', $linea, 2));
        $valor = trim($valor, " \t\n\r\0\x0B\"'");
        $variables[$clave] = $valor;
    }

    return $variables;
}

$env = cargarEnv(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');

//Ip de la pc servidor de base de datos
define("DB_HOST", $env['DB_HOST'] ?? "127.0.0.1");

//Nombre de la base de datos
define("DB_NAME", $env['DB_NAME'] ?? "dbsistema");

//Usuario de la base de datos
define("DB_USERNAME", $env['DB_USERNAME'] ?? "ImportMotors_Web");

//Contraseña del usuario de la base de datos
define("DB_PASSWORD", $env['DB_PASSWORD'] ?? "ImportMotors.*2026*");

//Puerto de la base de datos
define("DB_PORT", $env['DB_PORT'] ?? "3306");

//definimos la codificación de los caracteres
define("DB_ENCODE", $env['DB_ENCODE'] ?? "utf8");

//Definimos una constante como nombre del proyecto
define("PRO_NOMBRE", $env['PRO_NOMBRE'] ?? "ImportMotors");

// define('APP_VERSION', '2.0.1');

?>
