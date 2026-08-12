<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
csrf_check();
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if (!$productId) { header('Location: ../../admin/products.php'); exit; }
$pdo = getPDO();
if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) { header('Location: ../../admin/product-edit.php?id=' . $productId); exit; }
$file = $_FILES['image'];
if ($file['size'] > 3 * 1024 * 1024) { $_SESSION['admin_error']='Image too large'; header('Location: ../../admin/product-edit.php?id=' . $productId); exit; }
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
if (!isset($allowed[$mime])) { $_SESSION['admin_error']='Invalid image type'; header('Location: ../../admin/product-edit.php?id=' . $productId); exit; }
$ext = $allowed[$mime];
$safe = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
$destPath = __DIR__ . '/../../uploads/products/' . $safe;
if (!move_uploaded_file($file['tmp_name'], $destPath)) { $_SESSION['admin_error']='Failed to move upload'; header('Location: ../../admin/product-edit.php?id=' . $productId); exit; }
$publicPath = '/uploads/products/' . $safe;
$ins = $pdo->prepare('INSERT INTO product_images (product_id, path, is_featured, created_at, alt) VALUES (?,?,?,?,?)');
$ins->execute([$productId, $publicPath, 0, date('Y-m-d H:i:s'), '']);
// log
$actor = $_SESSION['name'] ?? $_SESSION['email'] ?? ('user#' . ($_SESSION['user_id'] ?? 'system'));
$log = $pdo->prepare('INSERT INTO activity_logs (admin_name, action, details) VALUES (?,?,?)');
$log->execute([$actor, 'Uploaded product image', json_encode(['product_id'=>$productId,'path'=>$publicPath])]);
header('Location: ../../admin/product-edit.php?id=' . $productId);
exit;
