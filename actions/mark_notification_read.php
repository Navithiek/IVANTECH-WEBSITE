<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
csrf_check();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id) { header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/')); exit; }
$pdo = getPDO();
$stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
$stmt->execute([$id, currentUserId()]);
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/')); exit;
