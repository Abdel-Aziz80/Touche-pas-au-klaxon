<?php
declare(strict_types=1);

// 👇 debug visible
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);
ob_implicit_flush(true);



use Buki\Router\Router;

require dirname(__DIR__) . '/vendor/autoload.php';


$root = dirname(__DIR__);

// Env
$dotenv = Dotenv\Dotenv::createImmutable($root);
$dotenv->safeLoad();

if (empty($_ENV['DB_DSN'])) { die('ENV non chargé: vérifier le .env à la racine.'); }

// PDO (container simplifié)
$pdo = (function () {
    $dsn  = $_ENV['DB_DSN']  ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASS'] ?? '';
    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        die('Erreur connexion DB: ' . $e->getMessage());
    }
})();

$logDir = $root . '/storage/logs';
if (!is_dir($logDir)) { @mkdir($logDir, 0775, true); }

$logger = new Monolog\Logger('tpak');
$logger->pushHandler(new Monolog\Handler\StreamHandler($logDir.'/app.log', Monolog\Level::Info));

App\Helpers\Registry::set('logger', $logger);

ini_set('session.cookie_httponly','1');
ini_set('session.use_strict_mode','1');
ini_set('session.cookie_samesite','Lax');
// en prod derrière HTTPS :
// ini_set('session.cookie_secure','1');

session_start();

$router = new Router([
    'paths' => [
        'controllers' => $root . '/app/Controllers',
        'middlewares' => $root . '/app/Middlewares',
    ],
    'namespaces' => [
        'controllers' => 'App\\Controllers',
        'middlewares' => 'App\\Middlewares',
    ],
     // important si l’app n’est pas à la racine du vhost
    // 'base_folder' => '/Touche pas au klaxon/public',
]);


// Injection simple
// ⚠️ Si la classe Registry n'existe pas encore, commente ces 2 lignes et reteste
App\Helpers\Registry::set('pdo', $pdo);
App\Helpers\Registry::set('views_path', $root . '/ressources/views');

require $root . '/config/routes.php';


// Route de test (au cas où routes.php est vide)
$router->get('/ping', function () { echo 'pong'; });

$router->error(function() {
    http_response_code(404);
    echo '404 Not Found';
});


$router->run();

