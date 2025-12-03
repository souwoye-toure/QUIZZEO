<?php
require_once __DIR__ . '/../includes/functions.php';


// Chemin vers les fichiers de données (à créer)
define('QUIZ_DATA_FILE', 'data/quizzes.json');
define('USER_DATA_FILE', 'data/users.json');

// --- FONCTIONS DE GESTION DE FICHIERS ---

/**
 * Lit et décode le contenu d'un fichier JSON.
 * @param string $filePath Le chemin du fichier.
 * @return array Le tableau décodé ou un tableau vide si le fichier est illisible.
 */
function readJsonData($filePath) {
    if (!file_exists($filePath)) {
        // En cas d'absence du fichier, on retourne un tableau vide
        return [];
    }
    $content = file_get_contents($filePath);
    if ($content === false) {
        // En cas d'erreur de lecture
        return [];
    }
    // Décodage du JSON en tableau associatif PHP
    $data = json_decode($content, true);
    // On s'assure que $data est bien un tableau (si le fichier est vide ou mal formaté)
    return is_array($data) ? $data : [];
}

/**
 * Récupère tous les quizzes avec le nom de leur créateur.
 * @return array Une liste des quiz prêts à être affichés.
 */
function getAllQuizzes() {
    // 1. Charger les données des quiz
    $quizzes = readJsonData(QUIZ_DATA_FILE);
    
    // 2. Charger les données des utilisateurs (pour trouver le créateur)
    $users = readJsonData(USER_DATA_FILE);
    
    // Convertir le tableau users en une map (id -> user) pour une recherche rapide
    $usersMap = [];
    foreach ($users as $user) {
        $usersMap[$user['id']] = $user;
    }
    
    // 3. Joindre les informations (équivalent d'un JOIN SQL)
    $quizzes_with_creator = [];
    foreach ($quizzes as $quiz) {
        $creator_id = $quiz['user_id'] ?? null;
        $creator_username = 'Inconnu'; // Valeur par défaut
        
        // Trouver le créateur dans la map des utilisateurs
        if ($creator_id && isset($usersMap[$creator_id])) {
            $creator_username = htmlspecialchars($usersMap[$creator_id]['username']);
        }

        // Ajouter le nom du créateur au tableau du quiz
        $quiz['creator'] = $creator_username;
        $quizzes_with_creator[] = $quiz;
    }
    
    // Tri par date de création (du plus récent au plus ancien)
    usort($quizzes_with_creator, function($a, $b) {
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });

    return $quizzes_with_creator;
}

// ------------------------------------------------------------------

// LOGIQUE PRINCIPALE DU CONTRÔLEUR
// Dans un environnement réel, on vérifierait la session de l'utilisateur ici.
// Par hypothèse, l'utilisateur est connecté pour accéder à cette page.

$is_user_logged_in = true; // Simuler une connexion réussie

$quizzes_list = [];

if ($is_user_logged_in) {
    $quizzes_list = getAllQuizzes();
}

// La variable $quizzes_list est prête pour être affichée par la Vue (quizzes.php)
?>
