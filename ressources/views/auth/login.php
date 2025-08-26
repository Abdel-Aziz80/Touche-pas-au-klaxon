<h1>Connexion</h1>
<form method="post" action="/login" autocomplete="on">
  <input type="hidden" name="csrf" value="<?= \App\Helpers\Csrf::token() ?>">
  <div><label>Email <input type="email" name="email" required></label></div>
  <div><label>Mot de passe <input type="password" name="password" required></label></div>
  <button type="submit">Se connecter</button>
</form>
<p>Admin de démo : <code>admin@tpak.local</code> / <code>password</code></p>
