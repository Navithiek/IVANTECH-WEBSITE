<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/csrf.php';

requireLogin();
if (($_SESSION['role'] ?? '') !== 'customer') { http_response_code(403); echo 'Forbidden'; exit; }

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$inquiriesStmt = $pdo->prepare('SELECT * FROM inquiries WHERE customer_id = ? ORDER BY created_at DESC');
$inquiriesStmt->execute([$_SESSION['user_id']]);
$inquiries = $inquiriesStmt->fetchAll();

?>
<main style="padding:48px;max-width:1000px;margin:0 auto;color:#f0f0f8;">
  <h1 style="font-family:Orbitron, sans-serif;">My Account</h1>
  <p>Welcome back, <?php echo e($user['name']); ?>.</p>

  <section style="margin-top:24px;">
    <h2 style="font-family:Orbitron, sans-serif;font-size:16px;">My Inquiries</h2>
    <?php if (empty($inquiries)): ?>
      <div style="color:rgba(240,240,248,0.3);padding:20px;border-radius:8px;background:rgba(255,255,255,0.02);">No inquiries yet.</div>
    <?php else: ?>
      <?php foreach ($inquiries as $inq): ?>
        <div style="padding:12px;border-radius:8px;background:rgba(255,255,255,0.03);margin-bottom:8px;">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <div><strong><?php echo e($inq['inquiry_code']); ?></strong><div style="color:rgba(240,240,248,0.5);font-size:13px"><?php echo e($inq['created_at']); ?></div></div>
            <div style="font-family:JetBrains Mono,monospace;color:#00C4B4;"><?php echo e($inq['status']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <section style="margin-top:24px;">
    <h2 style="font-family:Orbitron, sans-serif;font-size:16px;">Request a Quote</h2>
    <form action="/actions/inquiry.php" method="post" style="display:grid;grid-template-columns:1fr 120px;gap:8px;max-width:680px;">
      <?php echo csrf_field(); ?>
      <select name="product_id" required style="padding:10px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;">
        <option value="">Choose a product…</option>
        <?php
          $ps = $pdo->query('SELECT id,name,model FROM products WHERE status = "active" ORDER BY name')->fetchAll();
          foreach ($ps as $pp) { echo '<option value="'.e($pp['id']).'">'.e($pp['name']).' — '.e($pp['model']).'</option>'; }
        ?>
      </select>
      <input name="quantity" type="number" min="1" value="1" style="padding:10px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;" />
      <textarea name="message" rows="4" placeholder="Message / requirements" style="grid-column:1/-1;padding:10px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;"></textarea>
      <div style="grid-column:1/-1;text-align:right;"><button class="btn btn-primary" type="submit">Submit Inquiry</button></div>
    </form>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
