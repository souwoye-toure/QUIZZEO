<?php

// On inclut les fichiers nécessaires

require_once __DIR__ . '/../includes/helpers.php';   // Fonctions utilitaires (set_flash, redirection…)

require_once __DIR__ . '/../functions/quizzes.php'; // Fonctions liées aux quiz (create, add_response…)
 
// Fonction qui gère la création d’un quiz

function handle_quiz_creation($user) {

    // Vérifie que la requête est bien en POST

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
 
    // Récupère le titre et la description du formulaire

    $title = trim($_POST['title'] ?? '');

    $description = trim($_POST['description'] ?? '');
 
    // Vérifie que le titre est obligatoire

    if (!$title) {

        set_flash('error', "Le titre du quiz est obligatoire.");

        return;

    }
 
    // Prépare le tableau des questions

    $questions = [];
 
    // Vérifie que "question" existe et est un tableau

    if (!empty($_POST['question']) && is_array($_POST['question'])) {

        foreach ($_POST['question'] as $idx => $qText) {

            $qText = trim($qText);

            if (!$qText) continue; // Ignore les questions vides
 
            // Type de question (par défaut QCM)

            $type = $_POST['type'][$idx] ?? 'qcm';
 
            // Nombre de points attribués

            $points = intval($_POST['points'][$idx] ?? 1);
 
            // Options de réponses (pour QCM)

            $options = $_POST['options'][$idx] ?? [];
 
            // Index de la bonne réponse

            $correct = intval($_POST['correct'][$idx] ?? 0);
 
            // Ajoute la question formatée dans le tableau

            $questions[] = [

                'text'    => $qText,

                'type'    => $type,

                'options' => $options,

                'correct' => $correct,

                'points'  => $points

            ];

        }

    }
 
    // Appelle la fonction qui enregistre le quiz

    quizzes_create($user, $title, $description, $questions);
 
    // Message de confirmation

    set_flash('success', "Quiz créé. Vous pouvez le lancer depuis le dashboard.");

}
 
// Fonction qui gère la soumission d’un quiz et calcule le score

function handle_quiz_answer($user, $quiz) {

    // Vérifie que la requête est bien en POST

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;
 
    // Récupère les réponses envoyées par l’utilisateur

    $answers = $_POST['answers'] ?? [];
 
    $score = 0; // Score obtenu

    $max   = 0; // Score maximum possible
 
    // Parcourt toutes les questions du quiz

    foreach ($quiz['questions'] as $idx => $q) {

        $points = intval($q['points'] ?? 1);

        $max += $points;
 
        // Vérifie si c’est une question QCM

        if (($q['type'] ?? 'qcm') === 'qcm') {

            $correct = intval($q['correct'] ?? -1); // bonne réponse

            $given   = isset($answers[$idx]) ? intval($answers[$idx]) : -1; // réponse donnée
 
            // Si la réponse est correcte → ajoute les points

            if ($given === $correct) {

                $score += $points;

            }

        }

    }
 
    // Enregistre la réponse de l’utilisateur

    quizzes_add_response($quiz['id'], $user, $answers, $score, $max);
 
    // Retourne le score et le maximum pour affichage

    return ['score' => $score, 'max' => $max];

}

 
quizzesController.php
 
<?php

require_once __DIR__ . '/../functions/quizzes.php';
 
// Récupère tous les quiz publiés

function get_public_quizzes() {

    return quizzes_published();

}

 