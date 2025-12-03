<?php
require_once __DIR__ . '1:config/database.php';

function quizzes_all() {
    return db_read('quizzes.json');
}

function quizzes_save_all($quizzes) {
    db_write('quizzes.json', $quizzes);
}

function quizzes_published() {
    $out = [];
    foreach (quizzes_all() as $q) {
        if (($q['status'] ?? '') === 'lancé') {
            $out[] = $q;
        }
    }
    return $out;
}

function quizzes_by_user($userId) {
    $out = [];
    foreach (quizzes_all() as $q) {
        if (($q['user_id'] ?? null) === $userId) $out[] = $q;
    }
    return $out;
}

function quizzes_find($id) {
    foreach (quizzes_all() as $q) {
        if ($q['id'] === $id) return $q;
    }
    return null;
}

function quizzes_next_id() {
    $max = 0;
    foreach (quizzes_all() as $q) {
        $num = intval(preg_replace('/\D+/', '', $q['id']));
        if ($num > $max) $max = $num;
    }
    return 'q_' . ($max + 1);
}

function quizzes_create($user, $title, $description, $questions = []) {
    $all = quizzes_all();
    $id = quizzes_next_id();
    $quiz = [
        'id' => $id,
        'user_id' => $user['id'],
        'author' => $user['username'],
        'title' => $title,
        'description' => $description,
        'status' => 'en cours d\'écriture',
        'questions' => $questions,
        'responses' => [],
        'created_at' => date('Y-m-d H:i:s')
    ];
    $all[] = $quiz;
    quizzes_save_all($all);
    return $quiz;
}

function quizzes_toggle_status($id) {
    $all = quizzes_all();
    foreach ($all as &$q) {
        if ($q['id'] === $id) {
            if ($q['status'] === 'en cours d\'écriture') $q['status'] = 'lancé';
            else if ($q['status'] === 'lancé') $q['status'] = 'terminé';
            else $q['status'] = 'en cours d\'écriture';
        }
    }
    quizzes_save_all($all);
}

function quizzes_add_response($quizId, $user, $answers, $score, $max) {
    $all = quizzes_all();
    foreach ($all as &$q) {
        if ($q['id'] === $quizId) {
            if (!isset($q['responses']) || !is_array($q['responses'])) $q['responses'] = [];
            $q['responses'][] = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'score' => $score,
                'max' => $max,
                'answers' => $answers,
                'answered_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    quizzes_save_all($all);
}
