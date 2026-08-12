<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
requireAdmin();
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
  header('Location: products.php'); exit;
}
$cats = $pdo->query('SELECT id,name FROM categories ORDER BY name')->fetchAll();
$imgStmt = $pdo->prepare('SELECT path FROM product_images WHERE product_id = ? ORDER BY id LIMIT 1');
$imgStmt->execute([$id]);
  $imgRow = $imgStmt->fetch();
  $img = $imgRow ? $imgRow['path'] : null;
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:780px;margin:24px auto;color:#eef;">
  <h2>Edit Product</h2>
  <form method="post" action="admin/actions/product_save.php" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>" />
    <div style="margin-bottom:8px;"><label>Name</label><input name="name" required value="<?php echo e($product['name']); ?>" style="width:100%;padding:8px;border-radius:6px;"/></div>
    <div style="margin-bottom:8px;display:flex;gap:8px;"><div style="flex:1;"><label>SKU</label><input name="sku" value="<?php echo e($product['sku']); ?>" style="width:100%;padding:8px;border-radius:6px;"/></div><div style="width:220px;"><label>Category</label><select name="category_id" style="width:100%;padding:8px;border-radius:6px;"><option value="">--</option><?php foreach($cats as $c): ?><option value="<?php echo (int)$c['id']; ?>" <?php if($product['category_id']==$c['id']) echo 'selected'; ?>><?php echo e($c['name']); ?></option><?php endforeach; ?></select></div></div>
    <div style="margin-bottom:8px;"><label>Description</label><textarea name="description" style="width:100%;padding:8px;border-radius:6px;height:120px;"><?php echo e($product['description']); ?></textarea></div>
    <div style="display:flex;gap:8px;margin-bottom:8px;"><div style="flex:1;"><label>Price</label><input name="price" type="number" step="0.01" value="<?php echo e($product['price']); ?>" style="width:100%;padding:8px;border-radius:6px;"/></div><div style="width:160px;"><label>Stock</label><input name="stock" type="number" value="<?php echo e($product['stock']); ?>" style="width:100%;padding:8px;border-radius:6px;"/></div></div>
    <div style="margin-bottom:12px;">
      <label>Primary Image</label>
      <?php if ($img): ?><div style="margin:8px 0;"><img src="<?php echo $img; ?>" style="height:88px;border-radius:8px;object-fit:cover;" alt=""/></div><?php endif; ?>
      <input type="file" name="image" accept="image/*"/>
    </div>
    <hr/>
    <h3>Gallery</h3>
    <?php
      $g = $pdo->prepare('SELECT id, path FROM product_images WHERE product_id = ? ORDER BY id DESC');
      $g->execute([$id]);
      $gallery = $g->fetchAll();
    ?>
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
      <?php foreach($gallery as $gi): ?>
        <div style="width:140px;background:#061018;padding:6px;border-radius:6px;">
          <img src="<?php echo e($gi['path']); ?>" style="width:100%;height:80px;object-fit:cover;border-radius:4px;" alt=""/>
          <form method="post" action="admin/actions/image_delete.php" style="margin-top:6px;">
            <?php echo csrf_field(); ?><input type="hidden" name="id" value="<?php echo (int)$gi['id']; ?>" />
            <button class="btn" style="width:100%;">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
    <form method="post" action="admin/actions/upload_image.php" enctype="multipart/form-data">
      <?php echo csrf_field(); ?><input type="hidden" name="product_id" value="<?php echo (int)$id; ?>" />
      <div style="margin-bottom:12px;"><label>Upload Additional Image</label><input type="file" name="image" accept="image/*" required/></div>
      <div style="text-align:right;"><button class="btn">Upload</button></div>
    </form>
    <div style="text-align:right;"><button class="btn btn-primary">Save Changes</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
