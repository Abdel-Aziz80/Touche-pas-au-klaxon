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
        $this->pdo   = Registry::get('pdo');     // <- on garde PDO comme dans le brief
        $this->views = Registry::get('views_path');
    }

    public function index(): void
    {
        $title = 'Touche pas au klaxon';

        // On lit vraiment $this->pdo (résout l’alerte PHPStan) : de petits compteurs pour la home
        $stats = ['agencies' => 0, 'trips' => 0];
        try {
            $stats['agencies'] = (int) ($this->pdo->query('SELECT COUNT(*) FROM agencies')->fetchColumn() ?: 0);
            $stats['trips']    = (int) ($this->pdo->query('SELECT COUNT(*) FROM trips')->fetchColumn() ?: 0);
        } catch (\Throwable $e) {
            // En dev, si la base n’est pas prête, on ignore tranquillement.
        }

        $content = $this->render('home.php', [
            'message' => 'Bienvenue 👋',
            'stats'   => $stats,
        ]);

        $this->layout($title, $content);
    }

    /**
     * @param array<string,mixed> $data
     */
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
