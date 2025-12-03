<?php

// Inclusion des fichiers nécessaires


// Fonctions utilitaires : current_user(), redirect(), flash(), etc.
require_once __DIR__ . '/../includes/helpers.php';

// Fonctions liées aux quiz (lecture, récupération des quiz publiés, etc.)
require_once __DIR__ . '/../functions/quizzes.php';

// Header HTML (navigation, structure de page)
require_once __DIR__ . '/../views/header.php';


// Récupération des quiz publiés

$quizzes = quizzes_published(); // Renvoie un tableau de quiz dont le statut est 'lancé'

// Récupération de l'utilisateur courant (si connecté)
$user = current_user();
?>

<!-- CONTENU PRINCIPAL : affichage des quiz -->
<main class="container">
  <section class="card">

    <h1>Quiz disponibles</h1>

    <!-- Si aucun quiz n'est disponible -->
    <?php if (empty($quizzes)): ?>
      <p>Aucun quiz lancé.</p>

    <!-- Sinon on affiche la liste des quiz -->
    <?php else: ?>
      <div class="quiz-grid">
        <?php foreach ($quizzes as $q): ?>
          <article class="quiz-card">

            <!-- Titre du quiz -->
            <h3><?= htmlspecialchars($q['title']) ?></h3>

            <!-- Description du quiz -->
            <p class="quiz-desc"><?= htmlspecialchars($q['description'] ?? '') ?></p>

            <!-- Meta : auteur et statut -->
            <p class="quiz-meta">
              Créé par <?= htmlspecialchars($q['author'] ?? 'inconnu') ?><br>
              Statut : <?= htmlspecialchars($q['status']) ?>
            </p>

            <!-- Lien pour répondre au quiz -->
            <a class="btn ghost" href="/views/quiz.php?id=<?= urlencode($q['id']) ?>">
              Répondre
            </a>

          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </section>
</main>

<?php
// Footer HTML (fermeture de la page)
require_once __DIR__ . '/../views/footer.php';
?>

