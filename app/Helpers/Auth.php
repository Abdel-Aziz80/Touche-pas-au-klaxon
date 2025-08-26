<?php

declare(strict_types=1);

namespace App\Helpers;

final class Auth
{
    /** @param array{id:int, email:string, role:string} $user */
    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    /** @return array{id:int, email:string, role:string}|null */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function isAdmin(): bool
    {
        return (self::user()['role'] ?? '') === 'admin';
    }
}
