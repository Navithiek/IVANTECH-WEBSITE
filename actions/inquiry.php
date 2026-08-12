<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'customer') { http_response_code(403); echo 'Login required'; exit; }
if (!csrf_check($_POST['csrf_token'] ?? '')) { http_response_code(400); echo 'Invalid CSRF token.'; exit; }

$userId = $_SESSION['user_id'];
$productId = $_POST['product_id'] ?? null;
$quantity = max(1, (int)($_POST['quantity'] ?? 1));
$message = trim($_POST['message'] ?? '');

if (!$productId) { $_SESSION['inquiry_error']='No product selected'; header('Location: /products.php'); exit; }

$pdo = getPDO();
$pdo->beginTransaction();
try {
  $code = 'INQ-'.strtoupper(bin2hex(random_bytes(3)));
  $ins = $pdo->prepare('INSERT INTO inquiries (inquiry_code, customer_id, message, status) VALUES (?, ?, ?, ?)');
  $ins->execute([$code, $userId, $message, 'pending']);
  $inqId = $pdo->lastInsertId();

  $prodStmt = $pdo->prepare('SELECT name FROM products WHERE id = ? LIMIT 1');
  $prodStmt->execute([$productId]);
  $prod = $prodStmt->fetch();
  $pname = $prod ? $prod['name'] : '';

  $ip = $pdo->prepare('INSERT INTO inquiry_products (inquiry_id, product_id, quantity, product_name) VALUES (?, ?, ?, ?)');
  $ip->execute([$inqId, $productId, $quantity, $pname]);

  $notif = $pdo->prepare('INSERT INTO notifications (user_id, message, type) VALUES (?, ?, ?)');
  $notif->execute([$userId, "Your inquiry {$code} has been submitted.", 'inquiry']);

  $pdo->commit();
  $_SESSION['inquiry_success'] = 'Inquiry submitted successfully.';
  header('Location: /customer/index.php');
  exit;
} catch (Exception $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo 'An error occurred.';
}
