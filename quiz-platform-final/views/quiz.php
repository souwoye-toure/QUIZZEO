<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../functions/quizzes.php';
require_once __DIR__ . '/../controller/quizController.php';

$user = current_user();
if (!$user) {
    set_flash('error', "Vous devez être connecté pour répondre à un quiz.");
    redirect('/views/login.php');
}

$id = $_GET['id'] ?? null;
$quiz = $id ? quizzes_find($id) : null;
if (!$quiz || ($quiz['status'] ?? '') !== 'lancé') {
    echo "Quiz introuvable ou non disponible.";
    exit;
}

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handle_quiz_answer($user, $quiz);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($quiz['title']) ?> - Quiz</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg">
<header class="topbar">
  <div class="brand">
    <span class="brand-logo">QZ</span>
    <span class="brand-name"><?= htmlspecialchars($quiz['title']) ?></span>
  </div>
  <nav class="nav">
    <a class="btn ghost" href="/index.php">Accueil</a>
    <a class="btn ghost" href="/views/dashboard.php">Mon dashboard</a>
  </nav>
</header>
<main class="container single">
  <section class="card">
    <h1><?= htmlspecialchars($quiz['title']) ?></h1>
    <p class="quiz-desc"><?= htmlspecialchars($quiz['description'] ?? '') ?></p>

    <?php if ($result): ?>
      <div class="alert success">
        Votre score : <?= $result['score'] ?> / <?= $result['max'] ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <?php foreach ($quiz['questions'] as $idx => $q): ?>
        <div class="question-block">
          <strong>Question <?= $idx + 1 ?> :</strong><br>
          <span><?= htmlspecialchars($q['text']) ?></span><br>
          <?php if (($q['type'] ?? 'qcm') === 'qcm'): ?>
            <?php foreach (($q['options'] ?? []) as $optIdx => $opt): ?>
              <label>
                <input type="radio" name="answers[<?= $idx ?>]" value="<?= $optIdx ?>">
                <?= htmlspecialchars($opt) ?>
              </label><br>
            <?php endforeach; ?>
          <?php else: ?>
            <input type="text" name="answers[<?= $idx ?>]" placeholder="Votre réponse">
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <button class="btn primary" type="submit">Valider mes réponses</button>
    </form>
  </section>
</main>
</body>
</html>
