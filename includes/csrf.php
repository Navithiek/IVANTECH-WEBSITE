<?php
if (!session_id()) session_start();

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    $t = csrf_token();
    return '<input type="hidden" name="csrf_token" value="'.htmlspecialchars($t, ENT_QUOTES, 'UTF-8').'">';
}

function csrf_check($token = null): bool {
    if (!isset($_SESSION['csrf_token'])) return false;
    if ($token === null) $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'], (string)$token);
}
