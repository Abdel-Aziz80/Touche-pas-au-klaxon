<?php

declare(strict_types=1);

namespace App\Helpers;

final class Flash
{
    /** @param 'success'|'danger'|'warning'|'info' $type */
    public static function add(string $type, string $msg): void
    {
        $_SESSION['flash'][] = ['t' => $type, 'm' => $msg];
    }

    /** @return list<array{t:string, m:string}> */
    public static function consume(): array
    {
        $f = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $f;
    }
}
