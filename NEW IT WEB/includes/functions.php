<?php
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function flash_set($k, $msg) {
    if (!session_id()) session_start();
    $_SESSION['flash'] ??= [];
    $_SESSION['flash'][$k] = $msg;
}

function flash_get($k) {
    if (!session_id()) session_start();
    if (empty($_SESSION['flash'][$k])) return null;
    $m = $_SESSION['flash'][$k];
    unset($_SESSION['flash'][$k]);
    return $m;
}
