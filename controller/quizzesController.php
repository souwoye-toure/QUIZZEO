<?php
require_once __DIR__ . '/../functions/quizzes.php';
 
// Récupère tous les quiz publiés
function get_public_quizzes() {
    return quizzes_published();
}