<?php
namespace App\Models;
use App\Core\Model;
class ProductoModel extends Model {
    protected string $table = 'producto';
    public function listarConDetalle(): array {
        return $this->from('producto p')
            ->select('p.id, p.codigo, p.nombre, p.descripcion, p.precio, p.stock, p.unidad_medida, p.fecha_registro, c.nombre AS categoria, m.nombre AS marca')
            ->join('categoria c', 'c.id = p.id_categoria')
            ->join('marca m', 'm.id = p.id_marca')
            ->where('p.estado', 1)
            ->get();
    }
    public function codigoExiste(
    string $codigo,
    ?int $exceptoId = null
): bool {
    $productos = $this
        ->where('codigo', $codigo)
        ->get();

    foreach ($productos as $producto) {
        if (
            $exceptoId === null ||
            (int)$producto['id'] !== $exceptoId
        ) {
            return true;
        }
    }

    return false;
}

}
