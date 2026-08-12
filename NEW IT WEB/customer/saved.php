<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
requireLogin();
$pdo = getPDO();
$userId = currentUserId();
$stmt = $pdo->prepare('SELECT s.id AS saved_id, p.* FROM saved_products s JOIN products p ON s.product_id = p.id WHERE s.user_id = ? ORDER BY s.created_at DESC');
$stmt->execute([$userId]);
$items = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:980px;margin:24px auto;color:#eef;">
  <h2>Saved Products</h2>
  <?php if (empty($items)): ?><div style="padding:18px;background:#071018;border-radius:8px;color:#889;margin-top:12px;">You have no saved products.</div><?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:12px;">
    <?php foreach($items as $p):
      $imgStmt = $pdo->prepare('SELECT path FROM product_images WHERE product_id = ? ORDER BY id LIMIT 1');
      $imgStmt->execute([$p['id']]);
      $img = $imgStmt->fetchColumn();
      $imgUrl = $img ? $img : '/assets/images/placeholder.png';
    ?>
      <div style="background:#071018;padding:12px;border-radius:8px;">
        <img src="<?php echo $imgUrl; ?>" style="width:100%;height:140px;object-fit:cover;border-radius:6px;" alt=""/>
        <div style="margin-top:8px;"><strong><?php echo e($p['name']); ?></strong></div>
        <div style="margin-top:8px;display:flex;justify-content:space-between;align-items:center;"><a class="btn" href="/product.php?id=<?php echo (int)$p['id']; ?>">View</a>
        <form method="post" action="/actions/save_product.php" style="display:inline-block;">
          <?php echo csrf_field(); ?><input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>" />
          <button class="btn">Remove</button>
        </form></div>
      </div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
