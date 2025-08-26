<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Helpers\Flash;
use App\Helpers\Registry;
use PDO;

final class TripController
{
    private PDO $pdo;
    private string $views;
    public function __construct()
    {
        $this->pdo = Registry::get('pdo');
        $this->views = Registry::get('views_path');
    }

    public function index(): void
    {
        $sql = "SELECT t.id, fa.name AS from_agency, ta.name AS to_agency,
                   t.departure_at, t.arrival_at, t.seats_left, t.price
            FROM trips t
            JOIN agencies fa ON fa.id=t.from_agency_id
            JOIN agencies ta ON ta.id=t.to_agency_id
            ORDER BY t.departure_at ASC";
        $trips = $this->pdo->query($sql)->fetchAll();
        $title = 'Trajets';
        ob_start();
        $data = compact('trips');
        extract($data);
        require $this->views.'/trips/index.php';
        $content = (string)ob_get_clean();
        require $this->views.'/layouts/header.php';
        echo $content;
        require $this->views.'/layouts/footer.php';
    }

    public function create(): void
    {
        $agencies = $this->pdo->query("SELECT id,name FROM agencies ORDER BY name")->fetchAll();
        $title = 'Nouveau trajet';
        ob_start();
        $data = compact('agencies');
        extract($data);
        require $this->views.'/trips/create.php';
        $content = (string)ob_get_clean();
        require $this->views.'/layouts/header.php';
        echo $content;
        require $this->views.'/layouts/footer.php';
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée.');
            header('Location:/trips/create');
            exit;
        }
        $from = (int)($_POST['from_agency_id'] ?? 0);
        $to = (int)($_POST['to_agency_id'] ?? 0);
        $dep = trim($_POST['departure_at'] ?? '');
        $arr = trim($_POST['arrival_at'] ?? '');
        $seats = max(0, (int)($_POST['seats_left'] ?? 0));
        $price = (float)($_POST['price'] ?? 0);
        if ($from <= 0 || $to <= 0 || $from === $to || $dep === '' || $arr === '') {
            Flash::add('danger', 'Champs invalides.');
            header('Location:/trips/create');
            exit;
        }

        $stmt = $this->pdo->prepare("INSERT INTO trips (from_agency_id,to_agency_id,departure_at,arrival_at,seats_left,price)
                               VALUES (?,?,?,?,?,?)");
        $stmt->execute([$from,$to,$dep,$arr,$seats,$price]);
        Flash::add('success', 'Trajet créé.');
        header('Location:/trips');
        exit;
    }

    public function edit(int|string $id): void
    {
        $id = (int)$id;
        $trip = $this->pdo->prepare("SELECT * FROM trips WHERE id=?");
        $trip->execute([$id]);
        $trip = $trip->fetch();
        if (!$trip) {
            http_response_code(404);
            echo 'Trajet introuvable';
            return;
        }
        $agencies = $this->pdo->query("SELECT id,name FROM agencies ORDER BY name")->fetchAll();
        $title = 'Éditer trajet';
        ob_start();
        $data = compact('trip', 'agencies');
        extract($data);
        require $this->views.'/trips/edit.php';
        $content = (string)ob_get_clean();
        require $this->views.'/layouts/header.php';
        echo $content;
        require $this->views.'/layouts/footer.php';
    }

    public function update(int|string $id): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée.');
            header("Location:/trips/{$id}/edit");
            exit;
        }
        $id = (int)$id;
        $from = (int)($_POST['from_agency_id'] ?? 0);
        $to = (int)($_POST['to_agency_id'] ?? 0);
        $dep = trim($_POST['departure_at'] ?? '');
        $arr = trim($_POST['arrival_at'] ?? '');
        $seats = max(0, (int)($_POST['seats_left'] ?? 0));
        $price = (float)($_POST['price'] ?? 0);
        if ($from <= 0 || $to <= 0 || $from === $to || $dep === '' || $arr === '') {
            Flash::add('danger', 'Champs invalides.');
            header("Location:/trips/{$id}/edit");
            exit;
        }

        $stmt = $this->pdo->prepare("UPDATE trips SET from_agency_id=?,to_agency_id=?,departure_at=?,arrival_at=?,seats_left=?,price=? WHERE id=?");
        $stmt->execute([$from,$to,$dep,$arr,$seats,$price,$id]);
        Flash::add('success', 'Trajet mis à jour.');
        header('Location:/trips');
        exit;
    }

    public function destroy(int|string $id): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée.');
            header('Location:/trips');
            exit;
        }
        $id = (int)$id;
        $stmt = $this->pdo->prepare("DELETE FROM trips WHERE id=?");
        $stmt->execute([$id]);
        Flash::add('success', 'Trajet supprimé.');
        header('Location:/trips');
        exit;
    }
}
