<?php

// Chargement des fichiers utiles


// Fonctions générales (redirect(), flash(), current_user(), etc.)
require_once __DIR__ . '/includes/helpers.php';

// Fonctions liées aux quiz (CRUD)
require_once __DIR__ . '/functions/quizzes.php';

// Contrôleur de création / gestion des quiz
require_once __DIR__ . '/controller/quizController.php';

// Header HTML (menu, styles, etc.)
require_once __DIR__ . '/views/header.php';



// Vérification de l'utilisateur connecté


// Récupère l'utilisateur via la session
$user = current_user();

// Si aucun utilisateur → redirection vers le login
if (!$user) {
    redirect('/views/login.php');
}



// Gestion de la création de quiz


// Seuls les rôles "ecole" et "entreprise" peuvent créer des quiz
// On vérifie aussi que la page a reçu un formulaire POST
if (($user['role'] === 'ecole' || $user['role'] === 'entreprise') && $_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_quiz_creation($user); // Fonction du contrôleur
}



// Messages flash (succès / erreur)

$flash_success = get_flash('success');
$flash_error   = get_flash('error');



// Récupération des quiz appartenant à l’utilisateur connecté

$myQuizzes = quizzes_by_user($user['id']);
?>

<!-- CONTENU HTML PRINCIPAL -->
<main class="container">
  <section class="card">

    <h1>Mes quiz</h1>

    <!-- Message succès -->
    <?php if ($flash_success): ?>
      <div class="alert success"><?= htmlspecialchars($flash_success) ?></div>
    <?php endif; ?>

    <!-- Message erreur -->
    <?php if ($flash_error): ?>
      <div class="alert error"><?= htmlspecialchars($flash_error) ?></div>
    <?php endif; ?>


    <!--PARTIE CRÉATION DE QUIZ (réservée école/entreprise) -->
    <?php if ($user['role'] === 'ecole' || $user['role'] === 'entreprise'): ?>

      <h2>Créer un nouveau quiz</h2>

      <!-- Formulaire de création de quiz -->
      <form method="post" class="form quiz-create">

        <label>Titre du quiz
          <input type="text" name="title" required>
        </label>

        <label>Description
          <textarea name="description"></textarea>
        </label>

        <!-- Conteneur dynamique des questions -->
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

        <!-- Bouton pour ajouter une nouvelle question (JS) -->
        <button class="btn ghost" type="button" onclick="addQuestion()">Ajouter une question</button>

        <!-- Soumission -->
        <button class="btn primary" type="submit">Enregistrer le quiz</button>
      </form>


      <!--  LISTE DES QUIZ CRÉÉS PAR L’UTILISATEUR -->
      <h2>Liste de mes quiz</h2>

      <?php if (empty($myQuizzes)): ?>
        <p>Aucun quiz pour le moment.</p>

      <?php else: ?>
        <div class="quiz-grid">

          <!-- Boucle d'affichage de chaque quiz -->
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


    <!-- PARTIE POUR LES UTILISATEURS "simples" -->
    <?php else: ?>
      <h2>Quiz auxquels j'ai répondu</h2>
      <p>Fonctionnalité simplifiée : utilisez la page d'accueil pour accéder aux quiz.</p>
    <?php endif; ?>

  </section>
</main>

<!-- JAVASCRIPT : ajout de questions -->
<script>
let questionCount = 1;

// Ajoute un bloc "question" visuellement
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

<?php
// Pied de page (fermeture HTML, scripts, etc.)
require_once __DIR__ . '/views/footer.php';
?>

