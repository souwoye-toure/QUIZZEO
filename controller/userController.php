<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../functions/users.php';
 
// Gère la soumission du formulaire de connexion
function handle_login_submit() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;
 
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
 
    // Vérifie que les champs sont remplis
    if (!$username || !$password) {
        set_flash('error', "Veuillez remplir tous les champs.");
        return null;
    }
 
    // Vérifie les identifiants
    $user = users_authenticate($username, $password);
    if (!$user) {
        set_flash('error', "Identifiants incorrects ou compte inactif.");
        return null;
    }
 
    // Stocke l'utilisateur dans la session
    $_SESSION['user'] = $user;
 
    // Redirection selon le rôle
    if ($user['role'] === 'ecole' || $user['role'] === 'entreprise') {
        redirect('/views/dashboard.php');
    } elseif ($user['role'] === 'administrateur') {
        redirect('/views/dashboard.php'); // même page, affichage différent
    } else {
        redirect('/views/dashboard.php');
    }
}
 
// Gère la déconnexion
function handle_logout() {
    $_SESSION = [];
    session_destroy();
}