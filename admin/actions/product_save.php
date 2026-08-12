<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
csrf_check();
$pdo = getPDO();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$wasNew = $id === 0;
$name = trim($_POST['name'] ?? '');
$sku = trim($_POST['sku'] ?? '');
$category_id = $_POST['category_id'] ? (int)$_POST['category_id'] : null;
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] !== '' ? (float)$_POST['price'] : null;
$stock = $_POST['stock'] !== '' ? (int)$_POST['stock'] : null;
try {
  $pdo->beginTransaction();
  if ($id) {
    $stmt = $pdo->prepare('UPDATE products SET name=?, sku=?, category_id=?, description=?, price=?, stock=? WHERE id = ?');
    $stmt->execute([$name, $sku, $category_id, $description, $price, $stock, $id]);
  } else {
    $stmt = $pdo->prepare('INSERT INTO products (name, sku, category_id, description, price, stock, created_at) VALUES (?,?,?,?,?,?,NOW())');
    $stmt->execute([$name, $sku, $category_id, $description, $price, $stock]);
    $id = $pdo->lastInsertId();
  }

  // Handle uploaded image
  if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    if ($file['size'] > 2 * 1024 * 1024) throw new Exception('Image too large');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) throw new Exception('Unsupported image type');
    $ext = $allowed[$mime];
    $safe = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    $dest = __DIR__ . '/../../uploads/products/' . $safe;
    if (!move_uploaded_file($file['tmp_name'], $dest)) throw new Exception('Failed to move uploaded file');
    $publicPath = '/uploads/products/' . $safe;
    $ins = $pdo->prepare('INSERT INTO product_images (product_id, path, is_featured, created_at, alt) VALUES (?,?,?,?,?)');
    // some schemas expect `path` and `is_featured`, adapt accordingly
    $ins->execute([$id, $publicPath, 0, date('Y-m-d H:i:s'), $name]);
  }

  // log activity
  $actor = $_SESSION['name'] ?? $_SESSION['email'] ?? ('user#' . ($_SESSION['user_id'] ?? 'system'));
  $action = $wasNew ? 'Created product' : 'Updated product';
  $details = json_encode(['product_id' => $id, 'name' => $name]);
  $log = $pdo->prepare('INSERT INTO activity_logs (admin_name, action, details) VALUES (?,?,?)');
  $log->execute([$actor, $action, $details]);

  $pdo->commit();
  header('Location: ../products.php'); exit;
} catch (Exception $e) {
  $pdo->rollBack();
  // store error in session and redirect back
  $_SESSION['admin_error'] = $e->getMessage();
  header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../products.php')); exit;
}
