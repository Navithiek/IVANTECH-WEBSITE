<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/functions.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_check()) { http_response_code(400); echo 'Invalid CSRF token.'; exit; }
$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['password_confirm'] ?? '';
if (!$token || !$password || !$confirm) { $_SESSION['flash']='Missing fields'; header('Location: /reset.php?token=' . urlencode($token)); exit; }
if ($password !== $confirm) { $_SESSION['flash']='Passwords do not match'; header('Location: /reset.php?token=' . urlencode($token)); exit; }
$pdo = getPDO();
$stmt = $pdo->prepare('SELECT pr.*, u.id AS user_id FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE pr.token = ? LIMIT 1');
$stmt->execute([$token]);
$row = $stmt->fetch();
if (!$row || strtotime($row['expires_at']) < time()) { $_SESSION['flash']='Invalid or expired token'; header('Location: /forgot.php'); exit; }
$userId = $row['user_id'];
$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo->beginTransaction();
$pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $userId]);
$pdo->prepare('DELETE FROM password_resets WHERE id = ?')->execute([$row['id']]);
$pdo->commit();
$_SESSION['flash'] = 'Password updated. You can now log in.';
header('Location: /login.php'); exit;
