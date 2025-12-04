<?php
require_once __DIR__ . '/../config/database.php'; 
// database.php contient db_read() et db_write(), qui gèrent le fichier JSON

// Retourne tous les utilisateurs sous forme de tableau PHP


function users_all() {
    return db_read('users.json'); 
}


 // Sauvegarde la liste complète d’utilisateurs dans users.json

function users_save_all($users) {
    db_write('users.json', $users);
}


 // Trouver un utilisateur par son nom (insensible à la casse)
function users_find_by_username($username) {
    foreach (users_all() as $u) {
        // strcasecmp : compare deux chaînes sans tenir compte des majuscules
        if (strcasecmp($u['username'], $username) === 0) {
            return $u; // Utilisateur trouvé
        }
    }
    return null; // Aucun utilisateur
}


 // Trouver un utilisateur par son ID unique

function users_find_by_id($id) {
    foreach (users_all() as $u) {
        if ($u['id'] === $id) {
            return $u;
        }
    }
    return null;
}

/**
 * Si aucun utilisateur n'existe → créer 4 utilisateurs par défaut
 * (admin, école, entreprise, utilisateur)
 */
function users_create_default_if_empty() {
    $users = users_all();

    // Si le fichier users.json n'est pas vide → on ne fait rien
    if ($users) return;

    // Mot de passe par défaut pour tous : "password", mais HASHÉ
    $hash = password_hash('password', PASSWORD_DEFAULT);

    // Utilisateurs par défaut
    $users = [
        [
            'id' => 'u_admin',
            'username' => 'admin',
            'role' => 'administrateur',
            'password' => $hash, // mot de passe hashé
            'is_active' => true
        ],
        [
            'id' => 'u_ecole',
            'username' => 'ecole',
            'role' => 'ecole',
            'password' => $hash,
            'is_active' => true
        ],
        [
            'id' => 'u_entreprise',
            'username' => 'entreprise',
            'role' => 'entreprise',
            'password' => $hash,
            'is_active' => true
        ],
        [
            'id' => 'u_user',
            'username' => 'user',
            'role' => 'utilisateur',
            'password' => $hash,
            'is_active' => true
        ]
    ];

    // Enregistrer les utilisateurs dans users.json
    users_save_all($users);
}

/**
 * Authentifier un utilisateur
 * Vérifie :
 *  que l'utilisateur existe
 * qu'il est actif
  que le mot de passe est correct
 */
function users_authenticate($username, $password) {

    // S'assurer que les comptes par défaut existent (1 seule fois)
    users_create_default_if_empty();

    // Trouver l'utilisateur par son nom
    $u = users_find_by_username($username);

    // Si aucun utilisateur ou compte désactivé
    if (!$u || empty($u['is_active'])) return null;

    // Vérification du mot de passe haché
    if (!password_verify($password, $u['password'])) return null;

    // Tout est bon → connexion réussie
    return $u;
}
