<?php
require_once __DIR__ . '/../config/database.php';

function users_all() {
    return db_read('users.json');
}

function users_save_all($users) {
    db_write('users.json', $users);
}

function users_find_by_username($username) {
    foreach (users_all() as $u) {
        if (strcasecmp($u['username'], $username) === 0) return $u;
    }
    return null;
}

function users_find_by_id($id) {
    foreach (users_all() as $u) {
        if ($u['id'] === $id) return $u;
    }
    return null;
}

function users_create_default_if_empty() {
    $users = users_all();
    if ($users) return;
    // Mot de passe par défaut : "password" (hashé)
    $hash = password_hash('password', PASSWORD_DEFAULT);
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
    users_save_all($users);
}

function users_authenticate($username, $password) {
    users_create_default_if_empty();
    $u = users_find_by_username($username);
    if (!$u || empty($u['is_active'])) return null;
    if (!password_verify($password, $u['password'])) return null;
    return $u;
}
