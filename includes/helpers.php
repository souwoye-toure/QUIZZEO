<?php

// helpers.php : fonctions utilitaires globales pour QUIZZEO


// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Fonction de redirection

// Permet de rediriger l'utilisateur vers une autre page.
// Exemple : redirect('views/login.php');
function redirect($path) {
    header("Location: " . $path);
    exit; // On arrête l'exécution après la redirection
}


// Gestion des messages flash

// Les messages flash sont stockés en session et affichés une seule fois.
// Utile pour informer l'utilisateur après une action (succès, erreur...).

// Enregistre un message flash
function set_flash($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}

// Récupère et supprime un message flash
function get_flash($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]); // Supprime après lecture
        return $msg;
    }
    return null; // Aucun message trouvé
}


// Utilisateur courant

// Retourne l'utilisateur connecté (stocké en session) ou null si personne.
function current_user() {
    return $_SESSION['user'] ?? null;
}


// Protection des pages

// Vérifie si un utilisateur est connecté.
// Si non, ajoute un message flash et redirige vers la page de login.
function require_login() {
    if (!current_user()) {
        set_flash('error', "Vous devez être connecté pour accéder à cette page.");
        redirect('/views/login.php');
    }
}

