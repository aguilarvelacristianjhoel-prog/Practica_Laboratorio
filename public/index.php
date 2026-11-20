<?php

session_start();

spl_autoload_register(function ($class) {

    $prefix = 'App\\';

    if (str_starts_with($class, $prefix)) {

        $nombre = substr($class, strlen($prefix));

        $archivo = dirname(__DIR__) . '/app/' .
                   str_replace('\\', '/', $nombre) . '.php';

        if (file_exists($archivo)) {
            require_once $archivo;
        }
    }
});

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function url(string $ruta = 'productos'): string
{
    return $_SERVER['SCRIPT_NAME'] . '?route=' . rawurlencode($ruta);
}

function e($dato): string
{
    return htmlspecialchars(
        (string)$dato,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

try {

    $ruta = $_GET['route'] ?? 'productos';
    $ruta = trim($ruta, '/');

    $controlador = new \App\Controllers\ProductosController();

    if ($ruta == 'productos') {

        $controlador->index();

    } elseif ($ruta == 'productos/crear') {

        $controlador->crear();

    } elseif ($ruta == 'productos/guardar') {

        $controlador->guardar();

    } elseif (preg_match('/^productos\/editar\/([1-9][0-9]*)$/', $ruta, $datos)) {

        $id = (int)$datos[1];
        $controlador->editar($id);

    } elseif (preg_match('/^productos\/actualizar\/([1-9][0-9]*)$/', $ruta, $datos)) {

        $id = (int)$datos[1];
        $controlador->actualizar($id);

    } elseif (preg_match('/^productos\/eliminar\/([1-9][0-9]*)$/', $ruta, $datos)) {

        $id = (int)$datos[1];
        $controlador->eliminar($id);

    } else {

        http_response_code(404);
        echo 'La pagina solicitada no existe.';

    }

} catch (\Throwable $error) {

    http_response_code(500);

    error_log((string)$error);

    echo '<h2>Error de la aplicacion</h2>';
    echo '<p>Ocurrio un problema al ejecutar el sistema.</p>';
    echo '<p>Revise la conexion a la base de datos y el registro de errores de PHP.</p>';

}
