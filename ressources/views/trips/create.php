<?php /** @var array $agencies */ ?>
<h1>Nouveau trajet</h1>

<form action="/trips" method="post">
  <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">

  <label>De
    <select name="agency_from_id" required>
      <option value="">--</option>
      <?php foreach ($agencies as $a): ?>
        <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Vers
    <select name="agency_to_id" required>
      <option value="">--</option>
      <?php foreach ($agencies as $a): ?>
        <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Départ
    <input type="datetime-local" name="depart_at" required>
  </label>

  <label>Arrivée
    <input type="datetime-local" name="arrive_at" required>
  </label>

  <label>Places totales (1..9)
    <input type="number" name="seats_total" min="1" max="9" required>
  </label>

  <label>Places dispo
    <input type="number" name="seats_available" min="0" max="9" required>
  </label>

  <fieldset>
    <legend>Contact</legend>
    <label>Nom <input type="text" name="contact_name" required></label>
    <label>Téléphone <input type="text" name="contact_phone" required></label>
    <label>Email <input type="email" name="contact_email" required></label>
  </fieldset>

  <button type="submit">Créer</button>
</form>
