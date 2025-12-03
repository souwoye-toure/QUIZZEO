<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../functions/users.php';

function handle_login_submit() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        set_flash('error', "Veuillez remplir tous les champs.");
        return null;
    }

    $user = users_authenticate($username, $password);
    if (!$user) {
        set_flash('error', "Identifiants incorrects ou compte inactif.");
        return null;
    }

    $_SESSION['user'] = $user;
    // Redirection en fonction du rôle
    if ($user['role'] === 'ecole' || $user['role'] === 'entreprise') {
        redirect('/views/dashboard.php');
    } elseif ($user['role'] === 'administrateur') {
        redirect('/views/dashboard.php'); // même page, affichage différent
    } else {
        redirect('/views/dashboard.php');
    }
}

function handle_logout() {
    $_SESSION = [];
    session_destroy();
    redirect('/index.php');
}
