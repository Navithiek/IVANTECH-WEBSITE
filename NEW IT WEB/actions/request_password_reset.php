<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/functions.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_check()) { http_response_code(400); echo 'Invalid CSRF token.'; exit; }
$email = trim($_POST['email'] ?? '');
if (!$email) { $_SESSION['flash'] = 'Please provide your email.'; header('Location: /forgot.php'); exit; }
$pdo = getPDO();
$stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = ? AND status = "active" LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();
if (!$user) {
  // Do not reveal whether email exists
  $_SESSION['flash'] = 'If an account exists, a reset link was sent.';
  header('Location: /forgot.php'); exit;
}
$token = bin2hex(random_bytes(24));
$expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
$ins = $pdo->prepare('INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?,?,?,NOW())');
$ins->execute([$user['id'], $token, $expires]);
$resetUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/reset.php?token=' . $token;

// Try to send email; if mail() not configured, show token once for testing
$subject = 'Password reset request';
$message = "Hello {$user['name']},\n\nTo reset your password, visit the link below (expires in 1 hour):\n\n" . $resetUrl . "\n\nIf you did not request this, ignore this message.";
$headers = 'From: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n";
$sent = false;
try { $sent = mail($email, $subject, $message, $headers); } catch (Exception $e) { $sent = false; }
if ($sent) {
  $_SESSION['flash'] = 'Reset link sent to your email.';
} else {
  $_SESSION['flash'] = 'Reset link created. For testing use this URL: ' . $resetUrl;
}
header('Location: /forgot.php'); exit;
