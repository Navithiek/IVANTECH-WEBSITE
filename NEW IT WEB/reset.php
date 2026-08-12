<?php
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
$token = $_GET['token'] ?? '';
if (!$token) {
  echo '<div style="max-width:600px;margin:48px auto;color:#eef;">Invalid reset token.</div>'; require_once __DIR__ . '/includes/footer.php'; exit;
}
$pdo = getPDO();
$stmt = $pdo->prepare('SELECT pr.*, u.email FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE pr.token = ? LIMIT 1');
$stmt->execute([$token]);
$row = $stmt->fetch();
if (!$row) { echo '<div style="max-width:600px;margin:48px auto;color:#eef;">Invalid or expired token.</div>'; require_once __DIR__ . '/includes/footer.php'; exit; }
if (strtotime($row['expires_at']) < time()) { echo '<div style="max-width:600px;margin:48px auto;color:#eef;">Token expired.</div>'; require_once __DIR__ . '/includes/footer.php'; exit; }
?>
<div style="max-width:520px;margin:48px auto;color:#eef;">
  <h2>Reset Password</h2>
  <?php if (!empty($_SESSION['flash'])): ?><div style="background:#072a1f;padding:12px;border-radius:8px;margin-bottom:12px;color:#bfe;"><?php echo e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
  <form method="post" action="/actions/reset_password.php">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="token" value="<?php echo e($token); ?>" />
    <div style="margin-bottom:8px;"><label>New password</label><input name="password" type="password" required style="width:100%;padding:8px;border-radius:6px;"/></div>
    <div style="margin-bottom:8px;"><label>Confirm password</label><input name="password_confirm" type="password" required style="width:100%;padding:8px;border-radius:6px;"/></div>
    <div style="text-align:right;"><button class="btn btn-primary">Set new password</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
