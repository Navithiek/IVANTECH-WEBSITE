<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
requireAdmin();
$pdo = getPDO();
$cats = $pdo->query('SELECT id,name FROM categories ORDER BY name')->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:780px;margin:24px auto;color:#eef;">
  <h2>Create Product</h2>
  <form method="post" action="admin/actions/product_save.php" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div style="margin-bottom:8px;"><label>Name</label><input name="name" required style="width:100%;padding:8px;border-radius:6px;background:#0b0b12;border:1px solid rgba(255,255,255,0.04);color:#fff;"/></div>
    <div style="margin-bottom:8px;display:flex;gap:8px;"><div style="flex:1;"><label>SKU</label><input name="sku" style="width:100%;padding:8px;border-radius:6px;"/></div><div style="width:220px;"><label>Category</label><select name="category_id" style="width:100%;padding:8px;border-radius:6px;"><option value="">--</option><?php foreach($cats as $c): ?><option value="<?php echo (int)$c['id']; ?>"><?php echo e($c['name']); ?></option><?php endforeach; ?></select></div></div>
    <div style="margin-bottom:8px;"><label>Description</label><textarea name="description" style="width:100%;padding:8px;border-radius:6px;height:120px;background:#071018;color:#eef;"></textarea></div>
    <div style="display:flex;gap:8px;margin-bottom:8px;"><div style="flex:1;"><label>Price (optional)</label><input name="price" type="number" step="0.01" style="width:100%;padding:8px;border-radius:6px;"/></div><div style="width:160px;"><label>Stock</label><input name="stock" type="number" style="width:100%;padding:8px;border-radius:6px;"/></div></div>
    <div style="margin-bottom:12px;"><label>Primary Image</label><input type="file" name="image" accept="image/*"/></div>
    <div style="text-align:right;"><button class="btn btn-primary">Create Product</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
