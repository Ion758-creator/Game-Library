<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: php/login.php?error=login_required");
        exit();
    }
}

function getUsername() {
    return $_SESSION['username'] ?? '';
}
?>