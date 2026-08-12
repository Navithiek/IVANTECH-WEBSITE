<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_check($_POST['csrf_token'] ?? '')) { http_response_code(400); echo 'Invalid CSRF token.'; exit; }

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (!$email || !$password || !$name) { $_SESSION['reg_error']='Missing required fields'; header('Location: /login.php'); exit; }

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) { $_SESSION['reg_error']='Email already registered'; header('Location: /login.php'); exit; }

$hash = password_hash($password, PASSWORD_DEFAULT);
$ins = $pdo->prepare('INSERT INTO users (email,password,name,role,phone,address,status) VALUES (?, ?, ?, ?, ?, ?, ?)');
$ins->execute([$email, $hash, $name, 'customer', $phone, '', 'active']);
$id = $pdo->lastInsertId();

// Auto-login
session_regenerate_id(true);
$_SESSION['user_id'] = $id;
$_SESSION['name'] = $name;
$_SESSION['role'] = 'customer';

header('Location: /');
exit;
