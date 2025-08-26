<?php
$dt_dep = htmlspecialchars(substr($trip['departure_at'],0,16)); // "YYYY-MM-DD HH:MM"
$dt_arr = htmlspecialchars(substr($trip['arrival_at'],0,16));
?>
<h1>Éditer trajet #<?= (int)$trip['id'] ?></h1>
<form method="post" action="/trips/<?= (int)$trip['id'] ?>/update">
  <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">

  <div>
    <label>Agence départ
      <select name="from_agency_id" required>
        <?php foreach ($agencies as $a): ?>
          <option value="<?= (int)$a['id'] ?>" <?= (int)$a['id']===(int)$trip['from_agency_id']?'selected':'' ?>>
            <?= htmlspecialchars($a['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>

  <div>
    <label>Agence arrivée
      <select name="to_agency_id" required>
        <?php foreach ($agencies as $a): ?>
          <option value="<?= (int)$a['id'] ?>" <?= (int)$a['id']===(int)$trip['to_agency_id']?'selected':'' ?>>
            <?= htmlspecialchars($a['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>

  <div><label>Départ <input type="text" name="departure_at" value="<?= $dt_dep ?>" required></label></div>
  <div><label>Arrivée <input type="text" name="arrival_at" value="<?= $dt_arr ?>" required></label></div>
  <div><label>Places <input type="number" name="seats_left" min="0" value="<?= (int)$trip['seats_left'] ?>" required></label></div>
  <div><label>Prix (€) <input type="number" step="0.01" min="0" name="price" value="<?= (float)$trip['price'] ?>"></label></div>

  <button type="submit">Mettre à jour</button>
  <a href="/trips">Annuler</a>
</form>
