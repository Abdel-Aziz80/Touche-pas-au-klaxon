<?php
declare(strict_types=1);

namespace App\Helpers;

final class Registry {
    private static array $items = [];
    public static function set(string $key, mixed $value): void { self::$items[$key] = $value; }
    public static function get(string $key): mixed { return self::$items[$key] ?? null; }
    public static function has(string $key): bool { return array_key_exists($key, self::$items); }
    public static function all(): array { return self::$items; }
}
