<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
csrf_check();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id) { header('Location: ../../admin/products.php'); exit; }
$pdo = getPDO();
$stmt = $pdo->prepare('SELECT path, product_id FROM product_images WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$row = $stmt->fetch();
if ($row) {
  $file = __DIR__ . '/../../' . ltrim($row['path'], '/');
  if (is_file($file)) @unlink($file);
  $pdo->prepare('DELETE FROM product_images WHERE id = ?')->execute([$id]);
  // log
  $actor = $_SESSION['name'] ?? $_SESSION['email'] ?? ('user#' . ($_SESSION['user_id'] ?? 'system'));
  $log = $pdo->prepare('INSERT INTO activity_logs (admin_name, action, details) VALUES (?,?,?)');
  $log->execute([$actor, 'Deleted product image', json_encode(['product_id'=>$row['product_id'],'image_id'=>$id])]);
}
header('Location: ../../admin/products.php'); exit;
