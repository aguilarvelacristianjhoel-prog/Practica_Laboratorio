<?php
namespace App\Core;
use InvalidArgumentException;
use PDO;

/** ORM didáctico: cada modelo representa una tabla y expone operaciones CRUD.
 * El Query Builder construye SQL internamente; vistas y controladores no escriben SQL.
 */
abstract class Model {
    protected string $table;
    private string $from = '';
    private string $columns = '*';
    private array $joins = [];
    private array $conditions = [];
    private array $params = [];

    public function __construct() { $this->reset(); }
    private function reset(): void {
        $this->from = $this->table;
        $this->columns = '*';
        $this->joins = [];
        $this->conditions = [];
        $this->params = [];
    }
    public function from(string $tableWithAlias): static { $this->from = $tableWithAlias; return $this; }
    public function select(string $columns): static { $this->columns = $columns; return $this; }
    public function join(string $table, string $on): static {
        $this->joins[] = "INNER JOIN $table ON $on";
        return $this;
    }
    public function where(string $column, mixed $value): static {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_.]*$/', $column)) {
            throw new InvalidArgumentException('Columna no válida');
        }
        $this->conditions[] = "$column = ?";
        $this->params[] = $value;
        return $this;
    }
    public function get(): array {
        $sql = "SELECT {$this->columns} FROM {$this->from}";
        if ($this->joins) $sql .= ' ' . implode(' ', $this->joins);
        if ($this->conditions) $sql .= ' WHERE ' . implode(' AND ', $this->conditions);
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($this->params);
        $rows = $stmt->fetchAll();
        $this->reset();
        return $rows;
    }
    public function find(int $id): ?array {
        $this->reset();
        $stmt = Database::connection()->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    public function insert(array $data): int {
        if (!$data) throw new InvalidArgumentException('Datos vacíos');
        $columns = array_keys($data);
        $this->validateColumns($columns);
        $names = implode(', ', array_map(fn($c) => "`$c`", $columns));
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $stmt = Database::connection()->prepare("INSERT INTO {$this->table} ($names) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return (int) Database::connection()->lastInsertId();
    }
    public function update(int $id, array $data): bool {
        if (!$data) throw new InvalidArgumentException('Datos vacíos');
        $columns = array_keys($data);
        $this->validateColumns($columns);
        $set = implode(', ', array_map(fn($c) => "`$c` = ?", $columns));
        $stmt = Database::connection()->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }
    private function validateColumns(array $columns): void {
        foreach ($columns as $column) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
                throw new InvalidArgumentException('Nombre de columna inválido');
            }
        }
    }
}
