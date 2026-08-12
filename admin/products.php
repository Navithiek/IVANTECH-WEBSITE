<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
requireAdmin();
$pdo = getPDO();
$stmt = $pdo->query('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
$products = $stmt->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:980px;margin:22px auto;color:#eef">
  <div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Products</h2>
    <div><a class="btn btn-primary" href="product-create.php">Create Product</a></div>
  </div>
  <table style="width:100%;margin-top:12px;border-collapse:collapse;">
    <thead style="text-align:left;color:#99a;">
      <tr><th style="padding:8px">#</th><th>Name</th><th>Category</th><th>Stock</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p):
      $img = null;
      $imgStmt = $pdo->prepare('SELECT path FROM product_images WHERE product_id = ? ORDER BY id LIMIT 1');
      $imgStmt->execute([$p['id']]);
      $imgRow = $imgStmt->fetch();
      if ($imgRow) $img = $imgRow['path'];
    ?>
      <tr style="border-top:1px solid rgba(255,255,255,0.03);">
        <td style="padding:8px;vertical-align:middle;"><img src="<?php echo $img ?: '/assets/images/placeholder.png'; ?>" style="height:40px;width:40px;object-fit:cover;border-radius:6px;margin-right:8px;vertical-align:middle;" alt=""/></td>
        <td style="padding:8px;vertical-align:middle;"><strong><?php echo e($p['name']); ?></strong><div style="font-size:12px;color:#889;margin-top:6px;">SKU: <?php echo e($p['sku']); ?></div></td>
        <td style="padding:8px;vertical-align:middle;"><?php echo e($p['category_name']); ?></td>
        <td style="padding:8px;vertical-align:middle;"><?php echo e($p['stock'] ?? '—'); ?></td>
        <td style="padding:8px;vertical-align:middle;">
          <a class="btn" href="product-edit.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
          <form method="post" action="admin/actions/product_delete.php" style="display:inline-block;margin-left:6px;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>" />
            <button class="btn" onclick="return confirm('Delete this product?')">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
