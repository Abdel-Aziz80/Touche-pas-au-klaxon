<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Registry;
use PDO;

final class HomeController
{
    private PDO $pdo;
    private string $views;

    public function __construct()
    {
        $this->pdo   = Registry::get('pdo');
        $this->views = Registry::get('views_path');
    }

    public function index(): void
    {
        // Exemple simple (tu pourras mettre ta vraie requête ensuite)
        $title = 'Touche pas au klaxon';
        $content = $this->render('home.php', ['message' => 'Bienvenue 👋']);
        $this->layout($title, $content);
    }

    private function render(string $view, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $this->views . '/' . $view;
        return (string) ob_get_clean();
    }

    private function layout(string $title, string $content): void
    {
        require $this->views . '/layouts/header.php';
        echo $content;
        require $this->views . '/layouts/footer.php';
    }
}
