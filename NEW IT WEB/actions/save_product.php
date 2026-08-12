<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
csrf_check();
$userId = currentUserId();
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if (!$productId) { header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/')); exit; }
$pdo = getPDO();
$stmt = $pdo->prepare('SELECT id FROM saved_products WHERE user_id = ? AND product_id = ? LIMIT 1');
$stmt->execute([$userId, $productId]);
$exists = $stmt->fetch();
if ($exists) {
  $pdo->prepare('DELETE FROM saved_products WHERE id = ?')->execute([$exists['id']]);
} else {
  $ins = $pdo->prepare('INSERT INTO saved_products (user_id, product_id, created_at) VALUES (?,?,NOW())');
  try { $ins->execute([$userId, $productId]); } catch (Exception $e) {}
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/')); exit;
