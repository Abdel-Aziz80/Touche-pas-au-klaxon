<?php
declare(strict_types=1);

use Buki\Router\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);

// Env
$dotenv = Dotenv\Dotenv::createImmutable($root);
$dotenv->safeLoad();
if (empty($_ENV['DB_DSN'])) { die('ENV non chargé: vérifier le .env à la racine.'); }
// Simple container (ex: PDO)
$pdo = (function () {
    $dsn  = $_ENV['DB_DSN']  ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASS'] ?? '';
    $pdo  = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
})();

// Flash helper (session)
session_start();

$router = new Router([
    'paths' => [
        'controllers' => '../app/Controllers',
        'middlewares' => '../app/Middlewares',
    ],
    'namespaces' => [
        'controllers' => 'App\\Controllers',
        'middlewares' => 'App\\Middlewares',
    ]
]);

// Injection simple via singleton (à remplacer par un vrai container si tu veux)
App\Helpers\Registry::set('pdo', $pdo);
App\Helpers\Registry::set('views_path', $root . '/ressources/views');

// Routes
require $root . '/config/routes.php';

// 404
$router->error(function() {
    http_response_code(404);
    echo '404 Not Found';
});

// Run
$router->run();
