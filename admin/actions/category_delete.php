<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
csrf_check();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id) { header('Location: ../categories.php'); exit; }
$pdo = getPDO();
$stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
$stmt->execute([$id]);
// log
$actor = $_SESSION['name'] ?? $_SESSION['email'] ?? ('user#' . ($_SESSION['user_id'] ?? 'system'));
$log = $pdo->prepare('INSERT INTO activity_logs (admin_name, action, details) VALUES (?,?,?)');
$log->execute([$actor, 'Deleted category', json_encode(['category_id'=>$id])]);
header('Location: ../categories.php'); exit;
