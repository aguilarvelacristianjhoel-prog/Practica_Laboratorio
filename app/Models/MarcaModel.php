<?php
namespace App\Models;
use App\Core\Model;
class MarcaModel extends Model {
    protected string $table = 'marca';
    public function activas(): array { return $this->where('estado', 1)->get(); }
}
