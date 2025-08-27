<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
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
        $this->pdo   = Registry::get('pdo');
        $this->views = Registry::get('views_path');
    }

    public function index(): void
    {
        $sql = "
            SELECT
                t.id,
                fa.name AS from_agency,
                ta.name AS to_agency,
                t.depart_at,
                t.arrive_at,
                t.seats_total,
                t.seats_available
            FROM trips t
            JOIN agencies fa ON fa.id = t.agency_from_id
            JOIN agencies ta ON ta.id = t.agency_to_id
            ORDER BY t.depart_at ASC
        ";
        $trips = $this->pdo->query($sql)->fetchAll();

        $title = 'Trajets';
        ob_start();
        $data = compact('trips');
        extract($data, EXTR_SKIP);
        require $this->views . '/trips/index.php';
        $content = (string) ob_get_clean();

        require $this->views . '/layouts/header.php';
        echo $content;
        require $this->views . '/layouts/footer.php';
    }

    public function create(): void
    {
        $agencies = $this->pdo->query("SELECT id, name FROM agencies ORDER BY name")->fetchAll();

        $title = 'Nouveau trajet';
        ob_start();
        $data = compact('agencies');
        extract($data, EXTR_SKIP);
        require $this->views . '/trips/create.php';
        $content = (string) ob_get_clean();

        require $this->views . '/layouts/header.php';
        echo $content;
        require $this->views . '/layouts/footer.php';
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée.');
            header('Location: /trips/create');
            exit;
        }

        $user = Auth::user();
        $authorId = (int) ($user['id'] ?? 0);
        if ($authorId <= 0) {
            Flash::add('danger', 'Vous devez être connecté.');
            header('Location: /login');
            exit;
        }

        $from  = (int) ($_POST['agency_from_id'] ?? 0);
        $to    = (int) ($_POST['agency_to_id'] ?? 0);
        $dep   = trim((string) ($_POST['depart_at'] ?? ''));   // format: YYYY-MM-DD HH:MM:SS
        $arr   = trim((string) ($_POST['arrive_at'] ?? ''));   // idem
        $total = max(1, (int) ($_POST['seats_total'] ?? 1));   // 1..9
        $avail = max(0, (int) ($_POST['seats_available'] ?? 0));
        $cName = trim((string) ($_POST['contact_name'] ?? ''));
        $cPhone = trim((string) ($_POST['contact_phone'] ?? ''));
        $cMail = trim((string) ($_POST['contact_email'] ?? ''));

        // validations côté app, cohérentes avec les CHECK de la DB
        $errors = [];
        if ($from <= 0 || $to <= 0 || $from === $to) {
            $errors[] = 'Agences invalides.';
        }
        if ($dep === '' || $arr === '' || (strtotime($arr) <= strtotime($dep))) {
            $errors[] = 'Dates invalides (arrivée > départ).';
        }
        if ($total > 9) { $errors[] = 'Nombre total de sièges: 1..9.'; }

        if ($avail > $total) { $errors[] = 'Sièges disponibles incohérents.'; }

        if ($cName === '' || $cPhone === '' || !filter_var($cMail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Contacts incomplets/invalides.';
        }

        if ($errors !== []) {
            Flash::add('danger', implode(' ', $errors));
            header('Location: /trips/create');
            exit;
        }

        $sql = "
            INSERT INTO trips
                (author_id, agency_from_id, agency_to_id, depart_at, arrive_at,
                 seats_total, seats_available, contact_name, contact_phone, contact_email)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $authorId, $from, $to, $dep, $arr,
            $total, $avail, $cName, $cPhone, $cMail,
        ]);

        Flash::add('success', 'Trajet créé.');
        header('Location: /trips');
        exit;
    }

    public function edit(string $id): void
    {
        $tripId = (int) $id;

        $stmt = $this->pdo->prepare("SELECT * FROM trips WHERE id = ?");
        $stmt->execute([$tripId]);
        $trip = $stmt->fetch();

        if (!$trip) {
            http_response_code(404);
            echo 'Trajet introuvable';
            return;
        }

        $agencies = $this->pdo->query("SELECT id, name FROM agencies ORDER BY name")->fetchAll();

        $title = 'Éditer trajet';
        ob_start();
        $data = compact('trip', 'agencies');
        extract($data, EXTR_SKIP);
        require $this->views . '/trips/edit.php';
        $content = (string) ob_get_clean();

        require $this->views . '/layouts/header.php';
        echo $content;
        require $this->views . '/layouts/footer.php';
    }

    public function update(string $id): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée.');
            header("Location: /trips/{$id}/edit");
            exit;
        }

        $tripId = (int) $id;

        $from  = (int) ($_POST['agency_from_id'] ?? 0);
        $to    = (int) ($_POST['agency_to_id'] ?? 0);
        $dep   = trim((string) ($_POST['depart_at'] ?? ''));
        $arr   = trim((string) ($_POST['arrive_at'] ?? ''));
        $total = max(1, (int) ($_POST['seats_total'] ?? 1));
        $avail = max(0, (int) ($_POST['seats_available'] ?? 0));
        $cName = trim((string) ($_POST['contact_name'] ?? ''));
        $cPhone = trim((string) ($_POST['contact_phone'] ?? ''));
        $cMail = trim((string) ($_POST['contact_email'] ?? ''));

        $errors = [];
        if ($from <= 0 || $to <= 0 || $from === $to) {
            $errors[] = 'Agences invalides.';
        }
        if ($dep === '' || $arr === '' || (strtotime($arr) <= strtotime($dep))) {
            $errors[] = 'Dates invalides (arrivée > départ).';
        }
        if ($total > 9) { $errors[] = 'Nombre total de sièges: 1..9.'; }

        if ($avail > $total) { $errors[] = 'Sièges disponibles incohérents.'; }
        
        if ($cName === '' || $cPhone === '' || !filter_var($cMail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Contacts incomplets/invalides.';
        }

        if ($errors !== []) {
            Flash::add('danger', implode(' ', $errors));
            header("Location: /trips/{$id}/edit");
            exit;
        }

        $sql = "
            UPDATE trips SET
                agency_from_id = ?,
                agency_to_id = ?,
                depart_at = ?,
                arrive_at = ?,
                seats_total = ?,
                seats_available = ?,
                contact_name = ?,
                contact_phone = ?,
                contact_email = ?
            WHERE id = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $from, $to, $dep, $arr, $total, $avail, $cName, $cPhone, $cMail, $tripId,
        ]);

        Flash::add('success', 'Trajet mis à jour.');
        header('Location: /trips');
        exit;
    }

    public function destroy(string $id): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::add('danger', 'Session expirée.');
            header('Location: /trips');
            exit;
        }

        $tripId = (int) $id;
        $stmt = $this->pdo->prepare("DELETE FROM trips WHERE id = ?");
        $stmt->execute([$tripId]);

        Flash::add('success', 'Trajet supprimé.');
        header('Location: /trips');
        exit;
    }
}
