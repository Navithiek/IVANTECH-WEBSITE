<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

if (!session_id()) session_start();

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isLoggedIn() || ($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo 'Access denied.';
        exit;
    }
}
