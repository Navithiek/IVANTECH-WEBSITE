<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
requireAdmin();
$pdo = getPDO();
$totalProducts = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$pendingInquiries = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'pending'")->fetchColumn();
$totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:980px;margin:28px auto;color:#eef">
  <h1 style="font-family:Orbitron, sans-serif;">Admin Dashboard</h1>
  <div style="display:flex;gap:12px;margin-top:18px;">
    <div style="background:#081018;padding:18px;border-radius:12px;flex:1;">Products<br/><strong style="font-size:20px"><?php echo e($totalProducts); ?></strong></div>
    <div style="background:#081018;padding:18px;border-radius:12px;flex:1;">Pending Inquiries<br/><strong style="font-size:20px"><?php echo e($pendingInquiries); ?></strong></div>
    <div style="background:#081018;padding:18px;border-radius:12px;flex:1;">Users<br/><strong style="font-size:20px"><?php echo e($totalUsers); ?></strong></div>
  </div>
  <div style="margin-top:18px;"><a class="btn" href="products.php">Manage Products</a></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
