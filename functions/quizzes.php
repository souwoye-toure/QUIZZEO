<?php
require_once __DIR__ . '/config/database.php';


 // Lire tous les utilisateurs depuis un fichier JSON

function users_all() {
    return db_read('users.json'); // Retourne un tableau d'utilisateurs
}


 // Enregistrer tous les utilisateurs dans users.json

function users_save_all($users) {
    db_write('users.json', $users);
}


 //Trouver un utilisateur par son nom d'utilisateur
 
function users_find_by_username($username) {
    foreach (users_all() as $u) {
        // strcasecmp = compare sans tenir compte des majuscules/minuscules
        if (strcasecmp($u['username'], $username) === 0) {
            return $u;
        }
    }
    return null; // Aucun utilisateur trouvé
}


 //Trouver un utilisateur par ID

function users_find_by_id($id) {
    foreach (users_all() as $u) {
        if ($u['id'] === $id) {
            return $u;
        }
    }
    return null;
}


// Si users.json est vide → créer 4 utilisateurs par défaut

function users_create_default_if_empty() {
    $users = users_all();
    if ($users) return; // Si déjà des utilisateurs → ne rien faire

    //  Hash du mot de passe "password"
    $hash = password_hash('password', PASSWORD_DEFAULT);

    // Liste des utilisateurs par défaut
    $users = [
        [
            'id' => 'u_admin',
            'username' => 'admin',
            'role' => 'administrateur',
            'password' => $hash,
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

    // Sauvegarde dans le fichier JSON
    users_save_all($users);
}


 // Vérifier l'authentification d'un utilisateur

function users_authenticate($username, $password) {

    // S’assure que les utilisateurs par défaut existent
    users_create_default_if_empty();

    // Cherche l'utilisateur par son nom
    $u = users_find_by_username($username);

    // Si pas trouvé ou désactivé → échec
    if (!$u || empty($u['is_active'])) {
        return null;
    }

    // Vérifie le mot de passe haché avec password_verify
    if (!password_verify($password, $u['password'])) {
        return null;
    }

    // Tout est bon → retourner l'utilisateur
    return $u;
}
