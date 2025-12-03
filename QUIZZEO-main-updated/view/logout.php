<?php
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

// Protection contre les caches (facultatif)
header("Cache-Control: no-cache, must-revalidate");
header("location: index.php");
exit;
