<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/controller/userController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_login_submit();
}
$error = get_flash('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Quiz Platform</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg">
<header class="topbar">
  <div class="brand">
    <span class="brand-logo">QZ</span>
    <span class="brand-name">Quiz Platform</span>
  </div>
</header>
<main class="container single">
  <section class="card auth">
    <h1>Connexion</h1>
    <?php if ($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="form">
      <label>Nom d'utilisateur
        <input type="text" name="username" required>
      </label>
      <label>Mot de passe
        <input type="password" name="password" required>
      </label>
      <button class="btn primary" type="submit">Se connecter</button>
    </form>
    <p class="hint">Comptes de test : <code>admin</code>, <code>ecole</code>, <code>entreprise</code>, <code>user</code> (mot de passe : <code>password</code>).</p>
  </section>
</main>
</body>
</html>
