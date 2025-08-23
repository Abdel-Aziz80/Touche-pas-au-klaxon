<?php
declare(strict_types=1);

namespace App\Config;

use PDO;

final class Database {
    public static function pdo(): PDO {
        static $pdo = null;
        if ($pdo) return $pdo;

        $dsn  = $_ENV['DB_DSN']  ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $pass = $_ENV['DB_PASS'] ?? '';

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }
}
