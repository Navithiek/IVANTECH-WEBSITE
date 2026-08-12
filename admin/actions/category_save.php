<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
csrf_check();
$pdo = getPDO();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name = trim($_POST['name'] ?? '');
$desc = trim($_POST['description'] ?? '');
if (!$name) { $_SESSION['admin_error'] = 'Name required'; header('Location: ../categories.php'); exit; }
if ($id) {
  $stmt = $pdo->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?');
  $stmt->execute([$name, $desc, $id]);
  $action = 'Updated category';
} else {
  $stmt = $pdo->prepare('INSERT INTO categories (name, description, created_at) VALUES (?, ?, NOW())');
  $stmt->execute([$name, $desc]);
  $id = $pdo->lastInsertId();
  $action = 'Created category';
}
// log
$actor = $_SESSION['name'] ?? $_SESSION['email'] ?? ('user#' . ($_SESSION['user_id'] ?? 'system'));
$log = $pdo->prepare('INSERT INTO activity_logs (admin_name, action, details) VALUES (?,?,?)');
$log->execute([$actor, $action, json_encode(['category_id'=>$id,'name'=>$name])]);
header('Location: ../categories.php'); exit;
