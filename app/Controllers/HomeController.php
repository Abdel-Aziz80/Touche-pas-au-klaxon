<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Registry;

final class HomeController
{
    public function index(): void
    {
        $views = Registry::get('views_path') ?? dirname(__DIR__, 2) . '/ressources/views';
        $title = 'Touche pas au Klaxon';
        require $views . '/home.php';
    }
}
