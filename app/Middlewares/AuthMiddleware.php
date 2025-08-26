<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Helpers\Auth;
use App\Helpers\Flash;

final class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            Flash::add('warning', 'Veuillez vous connecter.');
            header('Location: /login');
            exit;
        }
    }
}
