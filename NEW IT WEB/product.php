<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: /products.php'); exit; }

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT p.*, c.name AS category FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? LIMIT 1');
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { echo '<main style="padding:48px;color:#f0f0f8;">Product not found.</main>'; require_once __DIR__ . '/includes/footer.php'; exit; }

$imgPath = '/assets/images/product-'.$p['id'].'.jpg';

?>
<main style="padding:48px;max-width:1000px;margin:0 auto;color:#f0f0f8;">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">
    <div style="background:#0d0d1a;border-radius:12px;padding:14px;">
      <img src="<?php echo $imgPath; ?>" alt="<?php echo e($p['name']); ?>" style="width:100%;height:420px;object-fit:cover;border-radius:8px;" onerror="this.style.display='none'" />
    </div>
    <div>
      <div style="font-family:JetBrains Mono,monospace;color:#00C4B4;font-size:12px;margin-bottom:8px"><?php echo e($p['category'] ?: 'Uncategorized'); ?></div>
      <h1 style="font-family:Orbitron, sans-serif;font-size:24px;margin-bottom:8px"><?php echo e($p['name']); ?></h1>
      <div style="color:rgba(240,240,248,0.5);margin-bottom:14px"><?php echo e($p['model']); ?></div>
      <div style="color:rgba(240,240,248,0.7);line-height:1.6;margin-bottom:18px"><?php echo nl2br(e($p['description'])); ?></div>
      <div style="font-size:13px;color:#00C4B4;font-weight:700;margin-bottom:12px">Price: Contact for Quote</div>
      <a href="/login.php" class="btn btn-primary">Request Quote</a>
    </div>
  </div>
  <section style="margin-top:28px;">
    <h3 style="font-family:Orbitron, sans-serif;font-size:16px;margin-bottom:10px">Specifications</h3>
    <div style="display:flex;gap:8px;flex-wrap:wrap;color:rgba(240,240,248,0.6);">
      <?php if ($p['specs']):
        $specs = json_decode($p['specs'], true) ?: explode(',', $p['specs']);
        foreach ($specs as $s): ?>
          <span style="background:rgba(255,255,255,0.03);padding:6px 10px;border-radius:8px;font-family:JetBrains Mono,monospace;font-size:12px"><?php echo e(trim($s)); ?></span>
        <?php endforeach; endif; ?>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
