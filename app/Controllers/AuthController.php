<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Csrf;
use App\Helpers\Flash;
use App\Helpers\Registry;
use PDO;

final class AuthController
{
    private PDO $pdo;
    private string $views;

    public function __construct()
    {
        $this->pdo   = Registry::get('pdo');
        $this->views = Registry::get('views_path');
    }

    public function showLogin(): void
    {
        $title = 'Connexion';
        ob_start();
        require $this->views . '/auth/login.php';
        $content = (string) ob_get_clean();

        require $this->views . '/layouts/header.php';
        echo $content;
        require $this->views . '/layouts/footer.php';
    }

    public function login(): void
    {
        // ✅ CSRF: seulement pour le POST
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée, veuillez réessayer.');
            header('Location: /login');
            exit;
        }

        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
        $pass  = (string)($_POST['password'] ?? '');

        if ($email === '' || $pass === '') {
            Flash::add('danger', 'Champs requis.');
            header('Location: /login');
            exit;
        }

        // La table a une colonne `password`
        $stmt = $this->pdo->prepare('SELECT id, email, role, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($pass, $user['password'])) {
            Flash::add('danger', 'Identifiants invalides.');
            header('Location: /login');
            exit;
        }

        Auth::login($user);
        Flash::add('success', 'Bienvenue !');
        header('Location: /');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::add('info', 'Déconnecté.');
        header('Location: /');
        exit;
    }
}
