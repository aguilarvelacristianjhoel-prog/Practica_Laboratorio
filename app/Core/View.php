<?php
namespace App\Core;
class View {
    public static function render(string $name, array $data = []): void {
        extract($data, EXTR_SKIP);
        $file = dirname(__DIR__) . '/Views/' . $name . '.php';
        if (!is_file($file)) { http_response_code(500); exit('Vista no encontrada'); }
        require dirname(__DIR__) . '/Views/layout_top.php';
        require $file;
        require dirname(__DIR__) . '/Views/layout_bottom.php';
    }
}
