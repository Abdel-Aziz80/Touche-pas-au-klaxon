<?php
/** @var array $trip */
/** @var array $agencies */

$toLocal = function (string $dt): string {
    // "YYYY-mm-dd HH:ii:ss" -> "YYYY-mm-ddTHH:ii"
    return str_replace(' ', 'T', substr($dt, 0, 16));
};
?>
<h1>Éditer trajet #<?= (int)$trip['id'] ?></h1>

<form action="/trips/<?= (int)$trip['id'] ?>/update" method="post">
  <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">

  <label>De
    <select name="agency_from_id" required>
      <?php foreach ($agencies as $a): ?>
        <option value="<?= (int)$a['id'] ?>" <?= (int)$a['id'] === (int)$trip['agency_from_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($a['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Vers
    <select name="agency_to_id" required>
      <?php foreach ($agencies as $a): ?>
        <option value="<?= (int)$a['id'] ?>" <?= (int)$a['id'] === (int)$trip['agency_to_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($a['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Départ
    <input type="datetime-local" name="depart_at" value="<?= htmlspecialchars($toLocal($trip['depart_at'])) ?>" required>
  </label>

  <label>Arrivée
    <input type="datetime-local" name="arrive_at" value="<?= htmlspecialchars($toLocal($trip['arrive_at'])) ?>" required>
  </label>

  <label>Places totales (1..9)
    <input type="number" name="seats_total" min="1" max="9" value="<?= (int)$trip['seats_total'] ?>" required>
  </label>

  <label>Places dispo
    <input type="number" name="seats_available" min="0" max="9" value="<?= (int)$trip['seats_available'] ?>" required>
  </label>

  <fieldset>
    <legend>Contact</legend>
    <label>Nom <input type="text" name="contact_name" value="<?= htmlspecialchars($trip['contact_name']) ?>" required></label>
    <label>Téléphone <input type="text" name="contact_phone" value="<?= htmlspecialchars($trip['contact_phone']) ?>" required></label>
    <label>Email <input type="email" name="contact_email" value="<?= htmlspecialchars($trip['contact_email']) ?>" required></label>
  </fieldset>

  <button type="submit">Mettre à jour</button>
</form>
