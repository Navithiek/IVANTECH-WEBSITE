<?php session_start(); ?>
<?php
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Please enter your name.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($message === '') {
        $errors[] = 'Please enter your message.';
    }

    if (empty($errors)) {
        $successMessage = "Thanks, {$name}! Your message has been queued and our team will respond soon.";
    }
}
?>
<?php include("includes/header.php"); ?>
<?php include("includes/navbar.php"); ?>

<main>
    <section class="page-hero">
        <div class="page-hero-copy">
            <span class="tag tag-teal">Messenger support</span>
            <h1>Start a quick conversation with our team.</h1>
            <p>Use the form below for a fast inquiry or reach us via phone or email for urgent support.</p>
            <div class="hero-actions">
                <a href="contact.php" class="btn btn-primary">Contact Form</a>
                <a href="mailto:info@ivantech.com" class="btn btn-outline-teal">Email Us</a>
            </div>
        </div>
        <div class="page-hero-card glass">
            <h3>Support channels</h3>
            <ul class="info-list">
                <li>📞 +63 912 345 6789</li>
                <li>✉ info@ivantech.com</li>
                <li>🕒 Mon–Sat, 8AM–6PM</li>
            </ul>
        </div>
    </section>

    <section class="section section-page">
        <div class="contact-grid">
            <div class="glass contact-card">
                <h3>Send a message</h3>
                <?php if ($successMessage !== ''): ?>
                    <div class="form-alert success"><?php echo htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="form-alert error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post" class="contact-form">
                    <label>
                        Name
                        <input type="text" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    </label>
                    <label>
                        Email
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </label>
                    <label>
                        Message
                        <textarea name="message" rows="6" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                    </label>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            <div class="glass contact-card">
                <h3>Fast response promise</h3>
                <p>Our team prioritizes service requests that include the type of system, site location, and preferred contact method so we can respond with the right information.</p>
            </div>
        </div>
    </section>
</main>

<?php include("includes/footer.php"); ?>