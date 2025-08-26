<!doctype html><html lang="fr"><head>
<meta charset="utf-8"><title><?= htmlspecialchars($title??'TPAK',ENT_QUOTES) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body><nav>
  <a href="/">Accueil</a> | <a href="/trips">Trajets</a> |
  <?php use App\Helpers\Auth; if (Auth::check()): ?>
    <span><?= htmlspecialchars(Auth::user()['email']) ?></span> <a href="/logout">Se déconnecter</a>
  <?php else: ?>
    <a href="/login">Se connecter</a>
  <?php endif; ?>
</nav><hr>
<?php foreach (App\Helpers\Flash::consume() as $f): ?>
  <div style="padding:6px;border:1px solid #ccc;margin:6px 0;">[<?= htmlspecialchars($f['t']) ?>] <?= htmlspecialchars($f['m']) ?></div>
<?php endforeach; ?>
<main>
