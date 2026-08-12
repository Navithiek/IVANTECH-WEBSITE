<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getPDO();

$q = $_GET['q'] ?? '';
$cat = $_GET['cat'] ?? 'All';

$params = [];
$where = 'p.status = "active"';
if ($q) { $where .= ' AND (p.name LIKE ? OR p.model LIKE ?)'; $params[] = "%{$q}%"; $params[] = "%{$q}%"; }
if ($cat !== 'All') { $where .= ' AND c.name = ?'; $params[] = $cat; }

$sql = "SELECT p.id, p.name, p.model, p.description, p.stock, p.badge, p.badge_color, c.name AS category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE {$where}
        ORDER BY p.featured DESC, p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$catsStmt = $pdo->query('SELECT name FROM categories WHERE status = "active" ORDER BY name');
$categories = $catsStmt->fetchAll(PDO::FETCH_COLUMN);

?>
<main style="padding:48px;max-width:1200px;margin:0 auto;color:#f0f0f8;">
  <h1 style="font-family:Orbitron, sans-serif;">Product Catalog</h1>
  <form method="get" style="margin:14px 0;display:flex;gap:8px;">
    <input name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search products…" style="padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:#0b0b12;color:#fff;" />
    <select name="cat" style="padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:#0b0b12;color:#fff;">
      <option<?php if ($cat==='All') echo ' selected'; ?>>All</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?php echo e($c); ?>"<?php if ($cat===$c) echo ' selected'; ?>><?php echo e($c); ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-primary">Search</button>
  </form>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;margin-top:20px;">
    <?php if (count($products)===0): ?>
      <div style="grid-column:1/-1;color:rgba(240,240,248,0.35);padding:40px;text-align:center;">No products found.</div>
    <?php endif; ?>
    <?php foreach ($products as $p): ?>
      <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;overflow:hidden;">
        <div style="height:160px;background:#0d0d1a;display:flex;align-items:center;justify-content:center;color:#888;">
          <img src="/assets/images/product-<?php echo e($p['id']); ?>.jpg" alt="<?php echo e($p['name']); ?>" style="max-width:100%;max-height:100%;object-fit:cover;" onerror="this.style.display='none'" />
          <div style="padding:8px;color:rgba(240,240,248,0.4);">No image</div>
        </div>
        <div style="padding:14px;">
          <div style="font-family:JetBrains Mono,monospace;font-size:10px;color:#00C4B4;margin-bottom:6px;"><?php echo e($p['category'] ?: 'Uncategorized'); ?></div>
          <div style="font-weight:700;color:#f0f0f8;"><?php echo e($p['name']); ?></div>
          <div style="color:rgba(240,240,248,0.45);font-size:13px;margin:8px 0;"><?php echo e($p['model']); ?></div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;">
            <div style="font-size:12px;color:#00C4B4;font-weight:700;">Contact for Quote</div>
            <a href="/product.php?id=<?php echo e($p['id']); ?>" class="btn btn-primary">View</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
