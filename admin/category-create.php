<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
requireAdmin();
$pdo = getPDO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cat = null;
if ($id) {
  $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?'); $stmt->execute([$id]); $cat = $stmt->fetch();
}
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:720px;margin:28px auto;color:#eef;">
  <h2><?php echo $cat ? 'Edit' : 'Create'; ?> Category</h2>
  <form method="post" action="admin/actions/category_save.php">
    <?php echo csrf_field(); ?>
    <?php if ($cat): ?><input type="hidden" name="id" value="<?php echo (int)$cat['id']; ?>" /><?php endif; ?>
    <div style="margin-bottom:8px;"><label>Name</label><input name="name" required value="<?php echo e($cat['name'] ?? ''); ?>" style="width:100%;padding:8px;border-radius:6px;"/></div>
    <div style="margin-bottom:8px;"><label>Description</label><textarea name="description" style="width:100%;padding:8px;border-radius:6px;height:120px;"><?php echo e($cat['description'] ?? ''); ?></textarea></div>
    <div style="text-align:right;"><button class="btn btn-primary">Save Category</button></div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
