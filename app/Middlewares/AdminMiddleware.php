<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Helpers\Auth;
use App\Helpers\Flash;

final class AdminMiddleware
{
    public function handle(): void
    {
        if (!Auth::isAdmin()) {
            Flash::add('danger', "Accès réservé à l’administrateur.");
            header('Location: /');
            exit;
        }
    }
}
