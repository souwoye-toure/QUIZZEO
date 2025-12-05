<?php
$host = '127.0.0.1';        // ou 'localhost'
$db   = 'quizzeo_db';         // nom de la base que tu as créée
$user = 'root';             // utilisateur MySQL
$pass = 'SuperPass2024!';   // mot de passe root que tu as défini
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
