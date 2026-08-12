<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
// fetch featured products for homepage
$pdo = getPDO();
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active' ORDER BY p.featured DESC, p.created_at DESC LIMIT 9");
$featured = $stmt->fetchAll();
?>
<main style="padding:64px;max-width:1100px;margin:0 auto;color:#f0f0f8;">
  <section style="text-align:center;padding:60px 20px;background:var(--bg);">
    <h1 style="font-family:Orbitron, sans-serif;font-size:36px;margin-bottom:12px;">IVANTECH</h1>
    <p style="color:rgba(240,240,248,0.6);max-width:760px;margin:0 auto;">Professional surveillance systems for homes & businesses. Request a custom quote tailored to your security needs.</p>
    <div style="margin-top:24px;"><a class="btn btn-primary" href="/products.php">Browse Products</a></div>
  </section>

  <section style="padding:40px 20px;">
    <h2 style="font-family:Orbitron, sans-serif;color:#f0f0f8;">Featured Products</h2>
    <p style="color:rgba(240,240,248,0.5);">Import the database and visit the admin to manage products.</p>
    <?php if (empty($featured)): ?>
      <div style="margin-top:12px;padding:18px;background:#071018;border-radius:8px;color:#889;">No products available yet.</div>
    <?php else: ?>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:12px;">
        <?php foreach ($featured as $p):
          $img = '/assets/images/placeholder.png';
          $imgStmt = $pdo->prepare('SELECT path FROM product_images WHERE product_id = ? ORDER BY id LIMIT 1');
          $imgStmt->execute([$p['id']]);
          $imgRow = $imgStmt->fetch();
          if ($imgRow) $img = $imgRow['path'];
        ?>
          <div style="background:#071018;padding:12px;border-radius:8px;">
            <a href="/product.php?id=<?php echo (int)$p['id']; ?>"><img src="<?php echo $img; ?>" style="width:100%;height:160px;object-fit:cover;border-radius:6px;" alt=""/></a>
            <div style="margin-top:8px;"><a href="/product.php?id=<?php echo (int)$p['id']; ?>" style="color:#fff;font-weight:600;"><?php echo e($p['name']); ?></a></div>
            <div style="color:#99a;margin-top:6px;font-size:13px;"><?php echo e($p['category_name'] ?? ''); ?></div>
            <div style="margin-top:8px;display:flex;gap:8px;justify-content:space-between;align-items:center;"><a class="btn" href="/product.php?id=<?php echo (int)$p['id']; ?>">View</a><a class="btn" href="/customer/index.php">Request Quote</a></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
