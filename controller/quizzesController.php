<?php
require_once __DIR__ . '/../functions/quizzes.php';

function get_public_quizzes() {
    return quizzes_published();
}
