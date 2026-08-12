<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
requireAdmin();
$pdo = getPDO();
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div style="max-width:980px;margin:28px auto;color:#eef">
  <div style="display:flex;justify-content:space-between;align-items:center;"><h2>Categories</h2><a class="btn btn-primary" href="category-create.php">Create Category</a></div>
  <table style="width:100%;margin-top:12px;border-collapse:collapse;">
    <thead style="text-align:left;color:#99a;"><tr><th style="padding:8px">Name</th><th>Description</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($cats as $c): ?>
      <tr style="border-top:1px solid rgba(255,255,255,0.03);"><td style="padding:8px"><?php echo e($c['name']); ?></td><td style="padding:8px"><?php echo e($c['description']); ?></td>
        <td style="padding:8px"><a class="btn" href="category-create.php?id=<?php echo (int)$c['id']; ?>">Edit</a>
        <form method="post" action="admin/actions/category_delete.php" style="display:inline-block;margin-left:6px;">
          <?php echo csrf_field(); ?><input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>" />
          <button class="btn" onclick="return confirm('Delete category?')">Delete</button>
        </form></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
