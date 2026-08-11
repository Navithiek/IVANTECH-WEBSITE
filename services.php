<?php session_start(); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/navbar.php"); ?>

<main>
    <section class="page-hero">
        <div class="page-hero-copy">
            <span class="tag tag-teal">Our services</span>
            <h1>End-to-end support for security systems, networks, and IT infrastructure.</h1>
            <p>We provide design, installation, maintenance, and repair services that keep your operations reliable and your team protected.</p>
            <div class="hero-actions">
                <a href="messenger.php" class="btn btn-primary">Talk to an Expert</a>
                <a href="contact.php" class="btn btn-outline-teal">Book a Site Visit</a>
            </div>
        </div>
        <div class="page-hero-card glass">
            <h3>Service highlights</h3>
            <ul class="info-list">
                <li>Site assessment and system planning</li>
                <li>Structured cabling and network setup</li>
                <li>Preventive maintenance and emergency support</li>
            </ul>
        </div>
    </section>

    <section class="section section-page">
        <div class="content-grid">
            <article class="content-card glass">
                <h3>CCTV installation</h3>
                <p>Camera placement, wiring, configuration, and commissioning for homes, offices, and commercial properties.</p>
            </article>
            <article class="content-card glass">
                <h3>Networking services</h3>
                <p>Reliable switch, router, wireless, and structured cabling solutions for fast and resilient connectivity.</p>
            </article>
            <article class="content-card glass">
                <h3>Computer services</h3>
                <p>Hardware upgrades, repairs, maintenance, and troubleshooting for business and personal workstations.</p>
            </article>
            <article class="content-card glass">
                <h3>Access control systems</h3>
                <p>Secure, scalable access management with biometrics and smart entry options for modern facilities.</p>
            </article>
        </div>
    </section>

    <section class="section section-page">
        <div class="section-header">
            <span class="tag tag-red">How we work</span>
            <h2>A simple process from planning to long-term support.</h2>
        </div>
        <div class="timeline">
            <div class="timeline-item glass">
                <strong>01</strong>
                <h3>Assess</h3>
                <p>We review your space, goals, and existing systems to shape a practical solution.</p>
            </div>
            <div class="timeline-item glass">
                <strong>02</strong>
                <h3>Install</h3>
                <p>Our technicians handle cabling, setup, and testing with careful attention to detail.</p>
            </div>
            <div class="timeline-item glass">
                <strong>03</strong>
                <h3>Support</h3>
                <p>We continue with maintenance, upgrades, and responsive help whenever you need it.</p>
            </div>
        </div>
    </section>
</main>

<?php include("includes/footer.php"); ?>