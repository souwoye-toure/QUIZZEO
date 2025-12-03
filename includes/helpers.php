<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect($path) {
    header("Location: " . $path);
    exit;
}

function set_flash($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}

function get_flash($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login() {
    if (!current_user()) {
        set_flash('error', "Vous devez être connecté pour accéder à cette page.");
        redirect('/views/login.php');
    }
}
