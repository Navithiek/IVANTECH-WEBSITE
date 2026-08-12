<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id, password, name, role FROM users WHERE email = ? AND status = "active" LIMIT 1');
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['name'] = $u['name'];
            $_SESSION['role'] = $u['role'];
            header('Location: /'); exit;
        }
    }
    $error = 'Incorrect email or password.';
}
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
?>
<main style="padding:48px;display:flex;justify-content:center;">
  <div style="width:420px;background:rgba(255,255,255,0.04);padding:28px;border-radius:16px;">
    <h2 style="font-family:Orbitron, sans-serif;color:#f0f0f8;margin-bottom:8px;">Sign In</h2>
    <?php if ($error): ?><div style="background:rgba(200,16,46,0.12);padding:10px;border-radius:8px;color:#ff8088;margin-bottom:12px;"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post">
      <div style="margin-bottom:10px;"><label>Email</label><input name="email" type="email" required style="width:100%;padding:10px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;margin-top:6px;" /></div>
      <div style="margin-bottom:10px;"><label>Password</label><input name="password" type="password" required style="width:100%;padding:10px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;margin-top:6px;" /></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;"><button class="btn btn-primary" type="submit">Sign In</button></div>
    </form>
    <div style="margin-top:8px;text-align:right;"><a href="/forgot.php">Forgot password?</a></div>
  </div>
  <div style="width:420px;background:rgba(255,255,255,0.03);padding:20px;border-radius:16px;margin-left:18px;">
    <h3 style="font-family:Orbitron, sans-serif;color:#f0f0f8;margin-bottom:8px;">Create Account</h3>
    <?php if (!empty($_SESSION['reg_error'])): ?><div style="background:rgba(200,16,46,0.12);padding:10px;border-radius:8px;color:#ff8088;margin-bottom:12px;"><?php echo e($_SESSION['reg_error']); unset($_SESSION['reg_error']); ?></div><?php endif; ?>
    <form method="post" action="/actions/register.php">
      <?php echo csrf_field(); ?>
      <div style="margin-bottom:8px;"><label>Full name</label><input name="name" required style="width:100%;padding:8px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;margin-top:6px;" /></div>
      <div style="margin-bottom:8px;"><label>Phone</label><input name="phone" style="width:100%;padding:8px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;margin-top:6px;" /></div>
      <div style="margin-bottom:8px;"><label>Email</label><input name="email" type="email" required style="width:100%;padding:8px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;margin-top:6px;" /></div>
      <div style="margin-bottom:12px;"><label>Password</label><input name="password" type="password" required style="width:100%;padding:8px;border-radius:8px;background:#0b0b12;border:1px solid rgba(255,255,255,0.06);color:#fff;margin-top:6px;" /></div>
      <div style="text-align:right;"><button class="btn btn-primary" type="submit">Create Account</button></div>
    </form>
    <div style="margin-top:14px;padding:10px;background:rgba(0,196,180,0.05);border-radius:8px;color:#00C4B4;font-family:JetBrains Mono,monospace;font-size:12px;">Demo: admin@ivantech.ph / Admin@1234<br/>Customer: juan@example.com / Customer@1234</div>
  </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
