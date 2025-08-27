<?php /** @var array $trips */ ?>
<h1>Trajets</h1>

<p><a href="/trips/create">Créer un trajet</a></p>

<table border="1" cellpadding="6">
  <thead>
    <tr>
      <th>#</th>
      <th>De</th>
      <th>Vers</th>
      <th>Départ</th>
      <th>Arrivée</th>
      <th>Places</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($trips as $t): ?>
    <tr>
      <td><?= (int)$t['id'] ?></td>
      <td><?= htmlspecialchars($t['from_agency']) ?></td>
      <td><?= htmlspecialchars($t['to_agency']) ?></td>
      <td><?= htmlspecialchars($t['depart_at']) ?></td>
      <td><?= htmlspecialchars($t['arrive_at']) ?></td>
      <td><?= (int)$t['seats_available'] ?>/<?= (int)$t['seats_total'] ?></td>
      <td>
        <a href="/trips/<?= (int)$t['id'] ?>/edit">Éditer</a>
        <form action="/trips/<?= (int)$t['id'] ?>/delete" method="post" style="display:inline" onsubmit="return confirm('Supprimer ?');">
          <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">
          <button type="submit">Supprimer</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
