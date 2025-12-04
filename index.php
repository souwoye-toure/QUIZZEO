<?php

require_once __DIR__ . '/views/header.php';
require_once __DIR__ . '/includes/helpers.php';
// Récupère l'utilisateur connecté
$user = current_user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>page header</title>
    <link rel="stylesheet" href="/Cours_PHP/quizzeo/assets/css/style.css">


</head>
<body>
    <header class="site-header" role="banner">
      <div class="container header-inner">
            <div class="brand">
                <img src="assets/img/logo_quizzeo-png.png"alt="Logo"
                style="width: 200px; height: 55px"/>
            </div>
 
            <!-- Bouton menu (mobile/tablette) -->
         <button
          id="menu-toggle"
          class="menu-btn"
          aria-label="Ouvrir le menu"
          aria-expanded="false"
          aria-controls="side-menu"
        >
          <span class="hamburger" aria-hidden="true"></span>
        </button>
 
            <!-- Nav principale (desktop) -->
        <nav class="top-nav"role="navigation"aria-label="Navigation principale">
          <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="views/quizzes.php">Quiz</a></li>
            <li><a href="views/dashboard.php">Dashboard</a></li>
            <li><a href="views/login.php" class="btn-connexion">Connexion</a></li>
          </ul>
        </nav>
      </div>
 
      <!-- Menu latéral slide-in -->
      <aside
        id="side-menu"
        class="side-menu"
        aria-hidden="true"
        role="dialog"
        aria-label="Menu principal"
      >
        <div class="side-inner">
          <button
            id="side-close"
            class="side-close"
            aria-label="Fermer le menu">&times;
          </button>
 
          <nav class="side-nav" role="navigation" aria-label="Menu mobile">
            <ul>
              <li><a href="#">Accueil</a></li>
              <li><a href="views/quizzes.php">Quiz</a></li>
              <li><a href="views/dashboard.php">Dashboard</a></li>
              <li><a href="views/login.php" class="btn-connexion">Connexion</a></li>
            </ul>
          </nav>
        </div>
      </aside>
    </header>
<main class="container">
  <!-- Section d'accueil -->
  <section class="card hero">
    <h1>Bienvenue sur Quiz Platform</h1>
    <p>Créez des quiz, partagez-les et consultez les résultats de vos participants.</p>
    <div class="hero-actions">
      <!-- Bouton vers la section quiz -->
      <a href="#quizzes" class="btn primary">Voir les quiz publiés</a>
      <!-- Bouton connexion affiché uniquement si l'utilisateur n'est pas connecté -->
      <?php if (!$user): ?>
        <a href="views/login.php" class="btn ghost">Se connecter</a>
      <?php endif; ?>
    </div>
  </section>

  <!-- Section des quiz disponibles -->
  <section class="card" id="quizzes">
    <h2>Quiz disponibles</h2>
    <?php if (empty($quizzes)): ?>
      <!-- Message si aucun quiz -->
      <p>Aucun quiz lancé pour le moment.</p>
    <?php else: ?>
      <!-- Grille des quiz -->
      <div class="quiz-grid">
        <?php foreach ($quizzes as $q): ?>
          <article class="quiz-card">
            <!-- Titre du quiz -->
            <h3><?= htmlspecialchars($q['title']) ?></h3>
            <!-- Description -->
            <p class="quiz-desc"><?= htmlspecialchars($q['description'] ?? '') ?></p>
            <!-- Auteur et statut -->
            <p class="quiz-meta">
              Créé par <?= htmlspecialchars($q['author'] ?? 'inconnu') ?><br>
              Statut : <?= htmlspecialchars($q['status'] ?? '') ?>
            </p>
            <!-- Bouton pour répondre -->
            <a class="btn ghost" href="views/quiz.php?id=<?= urlencode($q['id']) ?>">Répondre</a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php
require_once __DIR__ . '/views/footer.php';
?>
