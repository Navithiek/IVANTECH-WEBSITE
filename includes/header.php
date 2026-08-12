<?php
if (!session_id()) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IVANTECH</title>
  <link rel="stylesheet" href="/assets/css/styles.css" />
</head>
<body>
  <div class="site-content">
  <header class="site-header">
    <nav style="padding:18px;display:flex;justify-content:space-between;align-items:center;">
      <a href="/">IVANTECH</a>
      <div>
        <a href="/products.php">Products</a> |
        <?php if (!empty($_SESSION['user_id'])): ?>
          <a href="/customer/saved.php">Saved</a> |
          <a href="/customer/notifications.php">Notifications</a> |
          <a href="/logout.php">Logout</a>
        <?php else: ?>
          <a href="/login.php">Login</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>
