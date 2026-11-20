<?php
namespace App\Controllers;
use App\Core\View;
use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\MarcaModel;

class ProductosController {
    private ProductoModel $model;
    public function __construct() { $this->model = new ProductoModel(); }
    public function index(): void {
        View::render('productos/index', ['productos' => $this->model->listarConDetalle()]);
    }
    private function maestros(): array {
        return ['categorias' => (new CategoriaModel())->activas(), 'marcas' => (new MarcaModel())->activas()];
    }
    public function crear(): void {
        View::render('productos/form', array_merge($this->maestros(), ['producto' => null, 'errores' => []]));
    }
    public function guardar(): void {
        $this->verifyPost();
        [$datos, $errores] = $this->validar();
        if ($this->model->codigoExiste($datos['codigo'])) $errores[] = 'El código ya existe.';
        if ($errores) { http_response_code(422); View::render('productos/form', array_merge($this->maestros(), ['producto' => $datos, 'errores' => $errores])); return; }
        $datos['estado'] = 1;
        $this->model->insert($datos);
        $_SESSION['flash'] = 'Producto registrado correctamente.';
        $this->redirect();
    }
    public function editar(int $id): void {
        $producto = $this->productoActivo($id);
        View::render('productos/form', array_merge($this->maestros(), ['producto' => $producto, 'errores' => []]));
    }
    public function actualizar(int $id): void {
        $this->verifyPost();
        $actual = $this->productoActivo($id);
        [$datos, $errores] = $this->validar();
        if ($this->model->codigoExiste($datos['codigo'], $id)) $errores[] = 'El código ya existe.';
        if ($errores) {
            http_response_code(422);
            View::render('productos/form', array_merge($this->maestros(), ['producto' => array_merge($actual, $datos), 'errores' => $errores]));
            return;
        }
        $this->model->update($id, $datos);
        $_SESSION['flash'] = 'Producto actualizado correctamente.';
        $this->redirect();
    }
    public function eliminar(int $id): void {
        $this->verifyPost();
        $this->productoActivo($id);
        $this->model->update($id, ['estado' => 0]); // Eliminación lógica, no DELETE.
        $_SESSION['flash'] = 'Producto eliminado lógicamente: estado = 0.';
        $this->redirect();
    }
    private function productoActivo(int $id): array {
        $producto = $this->model->find($id);
        if (!$producto || (int)$producto['estado'] !== 1) { http_response_code(404); exit('Producto activo no encontrado'); }
        return $producto;
    }
    private function validar(): array {
        $errores = [];
        $datos = [
            'id_categoria' => filter_var($_POST['id_categoria'] ?? null, FILTER_VALIDATE_INT) ?: 0,
            'id_marca' => filter_var($_POST['id_marca'] ?? null, FILTER_VALIDATE_INT) ?: 0,
            'codigo' => trim((string)($_POST['codigo'] ?? '')),
            'nombre' => trim((string)($_POST['nombre'] ?? '')),
            'descripcion' => trim((string)($_POST['descripcion'] ?? '')),
            'precio' => (string)($_POST['precio'] ?? ''),
            'stock' => (string)($_POST['stock'] ?? ''),
            'unidad_medida' => trim((string)($_POST['unidad_medida'] ?? '')),
            'fecha_registro' => (string)($_POST['fecha_registro'] ?? ''),
        ];
        $categorias = array_column((new CategoriaModel())->activas(), 'id');
        $marcas = array_column((new MarcaModel())->activas(), 'id');
        if (!in_array($datos['id_categoria'], array_map('intval', $categorias), true)) $errores[] = 'Selecciona una categoría válida.';
        if (!in_array($datos['id_marca'], array_map('intval', $marcas), true)) $errores[] = 'Selecciona una marca válida.';
        if ($datos['codigo'] === '' || mb_strlen($datos['codigo']) > 30) $errores[] = 'Código obligatorio (máximo 30 caracteres).';
        if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 120) $errores[] = 'Nombre obligatorio (máximo 120 caracteres).';
        if (mb_strlen($datos['descripcion']) > 250) $errores[] = 'Descripción demasiado larga.';
        if (!is_numeric($datos['precio']) || (float)$datos['precio'] < 0 || (float)$datos['precio'] > 99999999.99) $errores[] = 'El precio debe ser un número válido y no negativo.';
        if (filter_var($datos['stock'], FILTER_VALIDATE_INT) === false || (int)$datos['stock'] < 0) $errores[] = 'Stock debe ser un entero no negativo.';
        if ($datos['unidad_medida'] === '' || mb_strlen($datos['unidad_medida']) > 30) $errores[] = 'Unidad de medida obligatoria.';
        $fecha = \DateTimeImmutable::createFromFormat('!Y-m-d', $datos['fecha_registro']);
        if (!$fecha || $fecha->format('Y-m-d') !== $datos['fecha_registro']) $errores[] = 'Fecha inválida.';
        return [$datos, $errores];
    }
    private function verifyPost(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método no permitido'); }
        if (!hash_equals((string)($_SESSION['csrf'] ?? ''), (string)($_POST['csrf'] ?? ''))) { http_response_code(403); exit('Token de seguridad inválido'); }
    }
    private function redirect(): void {
        header('Location: ' . url('productos'));
        exit;
    }
}
