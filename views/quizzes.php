<?php

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../functions/quizzes.php';

$quizzes = quizzes_published();
$user = current_user();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiz disponibles</title>

    <!-- Le CSS doit obligatoirement être dans le head -->
   <link rel="stylesheet" href="/QUIZZEO/assets/css/style.css">
   <link rel="stylesheet" href="/QUIZZEO/assets/css/login.css">

</head>

<body>

<?php include __DIR__ . '/../views/header.php'; ?>

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

                        <a class="btn ghost" href="/views/quiz.php?id=<?= urlencode($q['id']) ?>">
                            Répondre
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>
</main>

<?php include __DIR__ . '/../views/footer.php'; ?>

</body>
</html>
