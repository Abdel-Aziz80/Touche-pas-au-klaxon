<h1>Trajets</h1>
<?php if (\App\Helpers\Auth::check()): ?>
  <p><a href="/trips/create">+ Nouveau trajet</a></p>
<?php endif; ?>

<?php if (!$trips): ?>
  <p>Aucun trajet.</p>
<?php else: ?>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr>
    <th>Départ</th><th>Arrivée</th><th>Départ à</th><th>Arrivée à</th>
    <th>Places</th><th>Prix</th><?php if (\App\Helpers\Auth::check()): ?><th>Actions</th><?php endif; ?>
  </tr></thead>
  <tbody>
  <?php foreach ($trips as $t): ?>
    <tr>
      <td><?= htmlspecialchars($t['from_agency']) ?></td>
      <td><?= htmlspecialchars($t['to_agency']) ?></td>
      <td><?= htmlspecialchars($t['departure_at']) ?></td>
      <td><?= htmlspecialchars($t['arrival_at']) ?></td>
      <td><?= (int)$t['seats_left'] ?></td>
      <td><?= number_format((float)$t['price'],2,',',' ') ?> €</td>
      <?php if (\App\Helpers\Auth::check()): ?>
      <td>
        <a href="/trips/<?= (int)$t['id'] ?>/edit">Éditer</a>
        <form method="post" action="/trips/<?= (int)$t['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer ?');">
          <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">
          <button type="submit">Supprimer</button>
        </form>
      </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
