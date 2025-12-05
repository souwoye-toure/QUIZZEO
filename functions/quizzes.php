<?php
require_once __DIR__ . '/../config/database.php';
/**
 * Lire les données depuis un fichier JSON
 *
 * @param string $filename Le nom du fichier à lire
 * @return mixed Les données décodées du fichier JSON ou null en cas d'erreur
 */
function db_read($filename) {
    if (file_exists($filename)) {
        $json_data = file_get_contents($filename);
        return json_decode($json_data, true);
    }
    return null; // Si le fichier n'existe pas, retourner null
}
/**
 * Écrire les données dans un fichier JSON
 *
 * @param string $filename Le nom du fichier à écrire
 * @param mixed $data Les données à encoder en JSON
 * @return void
 */
function db_write($filename, $data) {
    $json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json_data);
}
/**
 * Lire tous les quiz depuis quizzes.json
 */
function quizzes_all() {
    return db_read('quizzes.json') ?? [];
}
/**
 * Sauvegarder tous les quiz dans quizzes.json
 */
function quizzes_save_all($quizzes) {
    db_write('quizzes.json', $quizzes);
}
/**
 * Trouver un quiz par son ID
 */
function quizzes_find_by_id($id) {
    foreach (quizzes_all() as $q) {
        if ($q['id'] === $id) {
            return $q;
        }
    }
    return null;
}
/**
 * Créer des quiz par défaut si le fichier est vide
 */
function quizzes_create_default_if_empty() {
    $quizzes = quizzes_all();
    if ($quizzes) return; // déjà des quiz → ne rien faire
    $quizzes = [
        [
            'id' => 'q_php',
            'title' => 'Quiz PHP',
            'description' => 'Testez vos connaissances en PHP',
            'author' => 'admin',
            'status' => 'publié'
        ],
        [
            'id' => 'q_sql',
            'title' => 'Quiz SQL',
            'description' => 'Requêtes et bases de données',
            'author' => 'ecole',
            'status' => 'publié'
        ],
        [
            'id' => 'q_html',
            'title' => 'Quiz HTML',
            'description' => 'Balises et structure des pages web',
            'author' => 'entreprise',
            'status' => 'publié'
        ]
    ];
    quizzes_save_all($quizzes);
}
/**
 * Retourner uniquement les quiz publiés
 */
function quizzes_published() {
    quizzes_create_default_if_empty();
    $published = [];
    foreach (quizzes_all() as $q) {
        if (isset($q['status']) && $q['status'] === 'publié') {
            $published[] = $q;
        }
    }
    return $published;
}