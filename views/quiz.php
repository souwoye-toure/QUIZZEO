<?php

// Chargement des fichiers nécessaires


// Fonctions utilitaires : current_user(), redirect(), flash(), etc.
require_once __DIR__ . '/includes/helpers.php';

// Fonctions liées aux quiz (lecture, récupération, etc.)
require_once __DIR__ . '/functions/quizzes.php';

// Contrôleur : logique de traitement des réponses aux quiz
require_once __DIR__ . '/controller/quizController.php';

// Header HTML (structure, menus…)
require_once __DIR__ . '/views/header.php';



// Vérification utilisateur : doit être connecté pour répondre

$user = current_user();

// Si l’utilisateur n’est pas connecté → redirection vers login
if (!$user) {
    set_flash('error', "Vous devez être connecté pour répondre à un quiz.");
    redirect('/views/login.php');
}



// Récupération du quiz via l'ID passé dans l'URL

$id = $_GET['id'] ?? null;         // On récupère ?id=xxx
$quiz = $id ? quizzes_find($id) : null; // On charge le quiz correspondant

// Vérifie que le quiz existe et qu'il est bien "lancé"
if (!$quiz || ($quiz['status'] ?? '') !== 'lancé') {
    echo "Quiz introuvable ou non disponible.";
    exit; // On arrête l'exécution
}



// Traitement du formulaire : l’utilisateur a répondu au quiz

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // handle_quiz_answer() corrige les réponses et retourne le score
    $result = handle_quiz_answer($user, $quiz);
}
?>

<!-- CONTENU HTML DU QUIZ-->
<main class="container single">
  <section class="card">

    <!-- Titre du quiz -->
    <h1><?= htmlspecialchars($quiz['title']) ?></h1>

    <!-- Description -->
    <p class="quiz-desc"><?= htmlspecialchars($quiz['description'] ?? '') ?></p>

    <!-- Message affiché après validation -->
    <?php if ($result): ?>
      <div class="alert success">
        Votre score : <?= $result['score'] ?> / <?= $result['max'] ?>
      </div>
    <?php endif; ?>


    <!-- FORMULAIRE DES QUESTIONS -->
    <form method="post" class="form">

      <?php foreach ($quiz['questions'] as $idx => $q): ?>
        <div class="question-block">

          <!-- Numéro + question -->
          <strong>Question <?= $idx + 1 ?> :</strong><br>
          <span><?= htmlspecialchars($q['text']) ?></span><br>

          <!-- Si la question est un QCM -->
          <?php if (($q['type'] ?? 'qcm') === 'qcm'): ?>

            <?php foreach (($q['options'] ?? []) as $optIdx => $opt): ?>
              <label>
                <!-- Chaque réponse radio est identifiée par l’index de la question -->
                <input type="radio" name="answers[<?= $idx ?>]" value="<?= $optIdx ?>">
                <?= htmlspecialchars($opt) ?>
              </label><br>
            <?php endforeach; ?>

          <!-- Sinon : question à réponse libre -->
          <?php else: ?>
            <input type="text"
                   name="answers[<?= $idx ?>]"
                   placeholder="Votre réponse">
          <?php endif; ?>

        </div>
      <?php endforeach; ?>

      <!-- Bouton d’envoi -->
      <button class="btn primary" type="submit">Valider mes réponses</button>
    </form>

  </section>
</main>

<?php
// Footer HTML (fermeture du layout)
require_once __DIR__ . '/views/footer.php';
?>

