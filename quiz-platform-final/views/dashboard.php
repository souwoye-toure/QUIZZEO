<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../functions/quizzes.php';
require_once __DIR__ . '/../controller/quizController.php';

$user = current_user();
if (!$user) {
    redirect('/views/login.php');
}

if (($user['role'] === 'ecole' || $user['role'] === 'entreprise') && $_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_quiz_creation($user);
}

$flash_success = get_flash('success');
$flash_error = get_flash('error');

$myQuizzes = quizzes_by_user($user['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Quiz Platform</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg">
<header class="topbar">
  <div class="brand">
    <span class="brand-logo">QZ</span>
    <span class="brand-name">Dashboard</span>
  </div>
  <nav class="nav">
    <span class="nav-user"><?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
    <a class="btn ghost" href="/index.php">Accueil</a>
    <a class="btn ghost" href="/views/logout.php">Déconnexion</a>
  </nav>
</header>
<main class="container">
  <section class="card">
    <h1>Mes quiz</h1>
    <?php if ($flash_success): ?><div class="alert success"><?= htmlspecialchars($flash_success) ?></div><?php endif; ?>
    <?php if ($flash_error): ?><div class="alert error"><?= htmlspecialchars($flash_error) ?></div><?php endif; ?>

    <?php if ($user['role'] === 'ecole' || $user['role'] === 'entreprise'): ?>
      <h2>Créer un nouveau quiz</h2>
      <form method="post" class="form quiz-create">
        <label>Titre du quiz
          <input type="text" name="title" required>
        </label>
        <label>Description
          <textarea name="description"></textarea>
        </label>
        <div id="questions">
          <div class="question-block">
            <h3>Question 1</h3>
            <label>Intitulé
              <input type="text" name="question[]" required>
            </label>
            <label>Type
              <select name="type[]">
                <option value="qcm">QCM</option>
                <option value="libre">Réponse libre</option>
              </select>
            </label>
            <label>Options (séparées par des virgules, pour QCM)
              <input type="text" name="options[][text]" placeholder="ex: Paris, Londres, Berlin">
            </label>
            <label>Indice de la bonne réponse (0,1,2...)
              <input type="number" name="correct[]" value="0" min="0">
            </label>
            <label>Points
              <input type="number" name="points[]" value="1" min="1">
            </label>
          </div>
        </div>
        <button class="btn ghost" type="button" onclick="addQuestion()">Ajouter une question</button>
        <button class="btn primary" type="submit">Enregistrer le quiz</button>
      </form>

      <h2>Liste de mes quiz</h2>
      <?php if (empty($myQuizzes)): ?>
        <p>Aucun quiz pour le moment.</p>
      <?php else: ?>
        <div class="quiz-grid">
          <?php foreach ($myQuizzes as $q): ?>
            <article class="quiz-card">
              <h3><?= htmlspecialchars($q['title']) ?></h3>
              <p class="quiz-meta">
                Statut : <?= htmlspecialchars($q['status']) ?><br>
                Réponses : <?= count($q['responses'] ?? []) ?>
              </p>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <h2>Quiz auxquels j'ai répondu</h2>
      <p>Fonctionnalité simplifiée dans cette version : utilisez la page d'accueil pour accéder aux quiz.</p>
    <?php endif; ?>
  </section>
</main>
<script>
let questionCount = 1;
function addQuestion() {
  questionCount++;
  const container = document.getElementById('questions');
  const block = document.createElement('div');
  block.className = 'question-block';
  block.innerHTML = `
    <h3>Question ${questionCount}</h3>
    <label>Intitulé
      <input type="text" name="question[]" required>
    </label>
    <label>Type
      <select name="type[]">
        <option value="qcm">QCM</option>
        <option value="libre">Réponse libre</option>
      </select>
    </label>
    <label>Options (séparées par des virgules, pour QCM)
      <input type="text" name="options[][text]" placeholder="ex: Paris, Londres, Berlin">
    </label>
    <label>Indice de la bonne réponse (0,1,2...)
      <input type="number" name="correct[]" value="0" min="0">
    </label>
    <label>Points
      <input type="number" name="points[]" value="1" min="1">
    </label>
  `;
  container.appendChild(block);
}
</script>
</body>
</html>
