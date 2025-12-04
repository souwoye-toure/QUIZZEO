<?php
require_once __DIR__ . '/../includes/helpers.php';   // Pour set_flash(), redirections, etc
require_once __DIR__ . '/../functions/quizzes.php'; // Pour quizzes_create(), quizzes_add_response(), etc

// Gère la création d'un quiz (envoyé depuis un formulaire POST)

function handle_quiz_creation($user) {

    // On traite seulement les formulaires envoyés en POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    // Récupération du titre et description (sécurisés)
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Titre obligatoire
    if (!$title) {
        set_flash('error', "Le titre du quiz est obligatoire.");
        return;
    }

    // Préparation des questions
    $questions = [];

    // Vérifie que "question" existe et est un tableau
    if (!empty($_POST['question']) && is_array($_POST['question'])) {

        foreach ($_POST['question'] as $idx => $qText) {

            $qText = trim($qText);
            if (!$qText) continue; // Ignore les questions vides

            // Type de question (QCM par défaut)
            $type = $_POST['type'][$idx] ?? 'qcm';

            // Nombre de points (entier)
            $points = intval($_POST['points'][$idx] ?? 1);

            // Options de réponses pour les QCM
            $options = $_POST['options'][$idx] ?? [];

            // Index de la bonne réponse
            $correct = intval($_POST['correct'][$idx] ?? 0);

            // Ajout d'une question formatée proprement
            $questions[] = [
                'text' => $qText,
                'type' => $type,
                'options' => $options,
                'correct' => $correct,
                'points' => $points
            ];
        }
    }

    // On appelle la fonction qui crée réellement le quiz dans quizzes.json
    quizzes_create($user, $title, $description, $questions);

    // Feedback utilisateur
    set_flash('success', "Quiz créé. Vous pouvez le lancer depuis le dashboard.");
}

/**
  Gère la soumission d’un quiz et calcule le score
 Retourne ['score' => x, 'max' => y]
 */
function handle_quiz_answer($user, $quiz) {

    // On traite seulement si le formulaire a été envoyé
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;

    // Récupère toutes les réponses envoyées par l'utilisateur
    $answers = $_POST['answers'] ?? [];

    $score = 0;
    $max = 0;

    // Parcours de toutes les questions du quiz
    foreach ($quiz['questions'] as $idx => $q) {

        // Valeur en points de la question
        $points = intval($q['points'] ?? 1);
        $max += $points;

        // Gestion des QCM
        if (($q['type'] ?? 'qcm') === 'qcm') {

            $correct = intval($q['correct'] ?? -1); // bonne réponse
            $given = isset($answers[$idx]) ? intval($answers[$idx]) : -1; // réponse donnée

            if ($given === $correct) {
                $score += $points; // Bonne réponse → ajoute les points
            }
        }
    }

    // Enregistre la réponse dans quizzes.json
    quizzes_add_response($quiz['id'], $user, $answers, $score, $max);

    // Retourne le score pour l’affichage final
    return ['score' => $score, 'max' => $max];
}
