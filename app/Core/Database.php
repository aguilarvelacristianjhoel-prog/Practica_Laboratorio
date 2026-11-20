<?php
namespace App\Core;
use PDO;
use PDOException;
use RuntimeException;
class Database {
    private static ?PDO $connection = null;
    public static function connection(): PDO {
        if (self::$connection === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['database']);
            try {
                self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new RuntimeException('No se pudo conectar a la base de datos. Verifica config/database.php y que MySQL esté iniciado.', 0, $e);
            }
        }
        return self::$connection;
    }
}
