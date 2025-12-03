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

