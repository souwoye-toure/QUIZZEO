<?php
$user = current_user();
?>

<main class="container">
  <section class="card hero">
    <h1>Bienvenue sur Quiz Platform</h1>
    <p>Créez des quiz, partagez-les et consultez les résultats de vos participants.</p>
    <div class="hero-actions">
      <a href="#quizzes" class="btn primary">Voir les quiz publiés</a>
      <?php if (!$user): ?>
      <a href="/../views/login.php" class="btn ghost">Se connecter</a>
      <?php endif; ?>
    </div>
  </section>

  <section class="card" id="quizzes">
    <h2>Quiz disponibles</h2>
    <?php if (empty($quizzes)): ?>
      <p>Aucun quiz lancé pour le moment.</p>
    <?php else: ?>
      <div class="quiz-grid">
        <?php foreach ($quizzes as $q): ?>
          <article class="quiz-card">
            <h3><?= htmlspecialchars($q['title']) ?></h3>
            <p class="quiz-desc"><?= htmlspecialchars($q['description'] ?? '') ?></p>
            <p class="quiz-meta">
              Créé par <?= htmlspecialchars($q['author'] ?? 'inconnu') ?><br>
              Statut : <?= htmlspecialchars($q['status'] ?? '') ?>
            </p>
            <a class="btn ghost" href="/../views/quiz.php?id=<?= urlencode($q['id']) ?>">Répondre</a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
