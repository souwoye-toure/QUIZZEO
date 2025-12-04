<?php

// Helpers (remonte d'un dossier)
require_once __DIR__ . '/../includes/helpers.php';

// Contrôleur utilisateur (remonte d'un dossier)
require_once __DIR__ . '/../controller/userController.php';

// Header (reste dans views)
require_once __DIR__ . '/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_login_submit();
}
$error = get_flash('error');
?>

<main class="container single">
  <!-- Section principale, centrée dans un container -->
  
  <section class="card auth">
    <!-- Carte visuelle contenant le formulaire d'authentification -->
    
    <h1>Connexion</h1>

    <?php if ($error): ?>
      <!-- Si une erreur existe (ex: mauvais identifiants), on l'affiche -->
      <div class="alert error">
        <?= htmlspecialchars($error) ?>
        <!-- htmlspecialchars() empêche l'injection HTML -->
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <!-- Formulaire envoyé en méthode POST -->

      <label>Nom d'utilisateur
        <!-- Champ texte pour le username -->
        <input type="text" name="username" required>
      </label>

      <label>Mot de passe
        <!-- Champ mot de passe -->
        <input type="password" name="password" required>
      </label>

      <button class="btn primary" type="submit">
        Se connecter
      </button>
      <!-- Bouton d’envoi du formulaire -->
    </form>

    <p class="hint">
      <!-- Infos de test affichées sous le formulaire -->
      Comptes de test :
      <code>admin</code>,
      <code>ecole</code>,
      <code>entreprise</code>,
      <code>user</code>
      (mot de passe : <code>password</code>).
    </p>
    
  </section>
</main>


<?php
require_once __DIR__ .'/../views/footer.php';
?>

