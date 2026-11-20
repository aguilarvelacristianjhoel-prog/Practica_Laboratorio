<?php
namespace App\Models;
use App\Core\Model;
class CategoriaModel extends Model {
    protected string $table = 'categoria';
    public function activas(): array { return $this->where('estado', 1)->get(); }
}
