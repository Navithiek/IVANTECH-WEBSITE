<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
csrf_check();
$pdo = getPDO();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id) { header('Location: ../../admin/products.php'); exit; }
try {
  $pdo->beginTransaction();
  // delete images and files
  $imgs = $pdo->prepare('SELECT path FROM product_images WHERE product_id = ?');
  $imgs->execute([$id]);
  foreach ($imgs->fetchAll() as $row) {
    $path = __DIR__ . '/../../' . ltrim($row['path'], '/');
    if (is_file($path)) @unlink($path);
  }
  $pdo->prepare('DELETE FROM product_images WHERE product_id = ?')->execute([$id]);
  $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

  // log activity
  $actor = $_SESSION['name'] ?? $_SESSION['email'] ?? ('user#' . ($_SESSION['user_id'] ?? 'system'));
  $details = json_encode(['product_id' => $id]);
  $log = $pdo->prepare('INSERT INTO activity_logs (admin_name, action, details) VALUES (?,?,?)');
  $log->execute([$actor, 'Deleted product', $details]);
  $pdo->commit();
  header('Location: ../../admin/products.php'); exit;
} catch (Exception $e) {
  $pdo->rollBack();
  $_SESSION['admin_error'] = $e->getMessage();
  header('Location: ../../admin/products.php'); exit;
}
