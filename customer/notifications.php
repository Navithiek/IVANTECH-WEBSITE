<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
requireLogin();
$pdo = getPDO();
$userId = currentUserId();
$stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$notes = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:980px;margin:24px auto;color:#eef;">
  <h2>Notifications</h2>
  <?php if (empty($notes)): ?><div style="padding:18px;background:#071018;border-radius:8px;color:#889;margin-top:12px;">No notifications.</div><?php else: ?>
    <ul style="list-style:none;padding:0;margin-top:12px;">
    <?php foreach($notes as $n): ?>
      <li style="padding:12px;background:<?php echo $n['is_read'] ? '#061018' : '#0b1220'; ?>;border-radius:8px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;">
        <div>
          <div style="font-weight:600;"><?php echo e($n['type']); ?></div>
          <div style="color:#cbd;margin-top:6px;"><?php echo e($n['message']); ?></div>
          <div style="font-size:12px;color:#667;margin-top:6px;"><?php echo e($n['created_at']); ?></div>
        </div>
        <div style="margin-left:12px;">
          <?php if (!$n['is_read']): ?>
          <form method="post" action="/actions/mark_notification_read.php">
            <?php echo csrf_field(); ?><input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>" />
            <button class="btn">Mark read</button>
          </form>
          <?php else: ?>
            <span style="color:#66a;">Read</span>
          <?php endif; ?>
        </div>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
