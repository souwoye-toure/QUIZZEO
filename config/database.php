<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizzeo_db"; // nom exact de la base

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Mode d'erreur : exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mode de récupération par défaut : tableau associatif
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

/**
 * Lire un fichier JSON et retourner son contenu sous forme de tableau
 */
function db_read($filename) {
    $path = __DIR__ . '/../data/' . $filename; // dossier data/ à la racine du projet
    if (!file_exists($path)) {
        return [];
    }
    $json = file_get_contents($path);
    return json_decode($json, true) ?? [];
}

/**
 * Écrire un tableau dans un fichier JSON
 */
function db_write($filename, $data) {
    $path = __DIR__ . '/../data/' . $filename;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($path, $json);
}

