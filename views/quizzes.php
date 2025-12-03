<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/functions/quizzes.php';
$quizzes = quizzes_published();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Quiz publiés - Quiz Platform</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg">
<header class="topbar">
  <div class="brand">
    <span class="brand-logo">QZ</span>
    <span class="brand-name">Quiz publiés</span>
  </div>
  <nav class="nav">
    <a class="btn ghost" href="/index.php">Accueil</a>
    <?php if ($user): ?>
      <a class="btn ghost" href="/views/dashboard.php">Mon dashboard</a>
    <?php else: ?>
      <a class="btn ghost" href="/views/login.php">Se connecter</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
  <section class="card">
    <h1>Quiz disponibles</h1>
    <?php if (empty($quizzes)): ?>
      <p>Aucun quiz lancé.</p>
    <?php else: ?>
      <div class="quiz-grid">
        <?php foreach ($quizzes as $q): ?>
          <article class="quiz-card">
            <h3><?= htmlspecialchars($q['title']) ?></h3>
            <p class="quiz-desc"><?= htmlspecialchars($q['description'] ?? '') ?></p>
            <p class="quiz-meta">
              Créé par <?= htmlspecialchars($q['author'] ?? 'inconnu') ?><br>
              Statut : <?= htmlspecialchars($q['status']) ?>
            </p>
            <a class="btn ghost" href="/views/quiz.php?id=<?= urlencode($q['id']) ?>">Répondre</a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
</body>
</html>
