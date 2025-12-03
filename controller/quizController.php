<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/functions/quizzes.php';

function handle_quiz_creation($user) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if (!$title) {
        set_flash('error', "Le titre du quiz est obligatoire.");
        return;
    }

    $questions = [];
    if (!empty($_POST['question']) && is_array($_POST['question'])) {
        foreach ($_POST['question'] as $idx => $qText) {
            $qText = trim($qText);
            if (!$qText) continue;
            $type = $_POST['type'][$idx] ?? 'qcm';
            $points = intval($_POST['points'][$idx] ?? 1);
            $options = $_POST['options'][$idx] ?? [];
            $correct = intval($_POST['correct'][$idx] ?? 0);
            $questions[] = [
                'text' => $qText,
                'type' => $type,
                'options' => $options,
                'correct' => $correct,
                'points' => $points
            ];
        }
    }

    quizzes_create($user, $title, $description, $questions);
    set_flash('success', "Quiz créé. Vous pouvez le lancer depuis le dashboard.");
}

function handle_quiz_answer($user, $quiz) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;

    $answers = $_POST['answers'] ?? [];
    $score = 0;
    $max = 0;
    foreach ($quiz['questions'] as $idx => $q) {
        $points = intval($q['points'] ?? 1);
        $max += $points;
        if (($q['type'] ?? 'qcm') === 'qcm') {
            $correct = intval($q['correct'] ?? -1);
            $given = isset($answers[$idx]) ? intval($answers[$idx]) : -1;
            if ($given === $correct) $score += $points;
        }
    }
    quizzes_add_response($quiz['id'], $user, $answers, $score, $max);
    return ['score' => $score, 'max' => $max];
}
