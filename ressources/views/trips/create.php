<h1>Nouveau trajet</h1>
<form method="post" action="/trips">
  <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">

  <div>
    <label>Agence départ
      <select name="from_agency_id" required>
        <option value="">--</option>
        <?php foreach ($agencies as $a): ?>
          <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>

  <div>
    <label>Agence arrivée
      <select name="to_agency_id" required>
        <option value="">--</option>
        <?php foreach ($agencies as $a): ?>
          <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>

  <div>
    <label>Départ (YYYY-MM-DD HH:MM)
      <input type="text" name="departure_at" placeholder="2025-09-01 08:00" required>
    </label>
  </div>

  <div>
    <label>Arrivée (YYYY-MM-DD HH:MM)
      <input type="text" name="arrival_at" placeholder="2025-09-01 10:00" required>
    </label>
  </div>

  <div><label>Places <input type="number" name="seats_left" min="0" value="1" required></label></div>
  <div><label>Prix (€) <input type="number" name="price" step="0.01" min="0" value="0"></label></div>
  <button type="submit">Créer</button>
</form>
