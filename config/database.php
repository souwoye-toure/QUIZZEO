<?php
// "Connexion" à la base de données : ici, stockage fichier JSON pour simplifier le déploiement.
// On pourrait brancher un SGBD réel en remplaçant ces fonctions par des appels PDO.

define('APP_ROOT', __DIR__ . '/..');
define('DATA_DIR', APP_ROOT . '/data');

if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

function db_read($file) {
    $path = DATA_DIR . '/' . $file;
    if (!file_exists($path)) {
        return [];
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function db_write($file, $data) {
    $path = DATA_DIR . '/' . $file;
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
