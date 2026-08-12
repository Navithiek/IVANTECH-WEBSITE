<?php
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>
<div style="max-width:520px;margin:48px auto;color:#eef;">
  <h2>Password Reset</h2>
  <?php if (!empty($_SESSION['flash'])): ?><div style="background:#072a1f;padding:12px;border-radius:8px;margin-bottom:12px;color:#bfe;"><?php echo e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
  <form method="post" action="/actions/request_password_reset.php">
    <?php echo csrf_field(); ?>
    <div style="margin-bottom:8px;"><label>Email</label><input name="email" type="email" required style="width:100%;padding:8px;border-radius:6px;"/></div>
    <div style="text-align:right;"><button class="btn btn-primary">Send reset link</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
