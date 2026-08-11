<?php session_start(); ?>
<?php
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Please enter your name.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($message === '') {
        $errors[] = 'Please share a short project description.';
    }

    if (empty($errors)) {
        $successMessage = "Thanks, {$name}! We will reach out to you shortly about your {$service} request.";
    }
}
?>
<?php include("includes/header.php"); ?>
<?php include("includes/navbar.php"); ?>

<main>
    <section class="page-hero">
        <div class="page-hero-copy">
            <span class="tag tag-red">Contact us</span>
            <h1>Let’s discuss your next security or technology upgrade.</h1>
            <p>Whether you need a surveillance system, a network refresh, or a service visit, our team is ready to help with a tailored recommendation.</p>
            <div class="hero-actions">
                <a href="messenger.php" class="btn btn-primary">Open Messenger</a>
                <a href="services.php" class="btn btn-outline-teal">View Services</a>
            </div>
        </div>
        <div class="page-hero-card glass">
            <h3>Reach us directly</h3>
            <ul class="info-list">
                <li>📍 Cebu City, Philippines</li>
                <li>📞 +63 912 345 6789</li>
                <li>✉ info@ivantech.com</li>
            </ul>
        </div>
    </section>

    <section class="section section-page">
        <div class="contact-grid">
            <div class="glass contact-card">
                <h3>Tell us about your project</h3>
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
                        Phone
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                    </label>
                    <label>
                        Service Needed
                        <select name="service">
                            <option value="CCTV Installation">CCTV Installation</option>
                            <option value="Networking">Networking</option>
                            <option value="Access Control">Access Control</option>
                            <option value="Computer Services">Computer Services</option>
                        </select>
                    </label>
                    <label>
                        Message
                        <textarea name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                    </label>
                    <button type="submit" class="btn btn-primary">Send Inquiry</button>
                </form>
            </div>
            <div class="glass contact-card">
                <h3>What happens next?</h3>
                <div class="service-list">
                    <div><strong>01</strong><span>We review your request and confirm the best service fit.</span></div>
                    <div><strong>02</strong><span>We discuss your site requirements and timeline.</span></div>
                    <div><strong>03</strong><span>We prepare a tailored proposal and follow up promptly.</span></div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include("includes/footer.php"); ?>