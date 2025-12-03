<?php

require_once __DIR__ . '/views/header.php';
// Récupère l'utilisateur connecté
$user = current_user();
?>

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
