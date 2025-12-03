
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzeo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <br>
    <br>
    <h1>Bienvenu sur Quizzeo !</h1>
    <p>Découvre des Quizz créé par nos utilisateurs, en cliquant <br> sur le bouton ci-dessous :</p>
    <br>
    <a href="views/quizzes.php"><button>Voir les Quizz</button></a>
</body>
</html>

<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/functions/quizzes.php';
$quizzes = quizzes_published();
include __DIR__ . '/views/index.php';
