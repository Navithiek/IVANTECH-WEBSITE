<?php session_start(); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/navbar.php"); ?>

<main>
    <section class="page-hero">
        <div class="page-hero-copy">
            <span class="tag tag-red">Featured products</span>
            <h1>High-performance security and networking equipment for modern projects.</h1>
            <p>Explore dependable CCTV, recording, access control, and installation accessories selected for reliability, performance, and long-term support.</p>
            <div class="hero-actions">
                <a href="contact.php" class="btn btn-primary">Request a Quote</a>
                <a href="services.php" class="btn btn-outline-teal">Explore Services</a>
            </div>
        </div>
        <div class="page-hero-card glass">
            <h3>Popular categories</h3>
            <div class="chip-list">
                <span class="chip">CCTV Cameras</span>
                <span class="chip">NVR / DVR</span>
                <span class="chip">Access Control</span>
                <span class="chip">Networking</span>
            </div>
        </div>
    </section>

    <section class="section section-page">
        <div class="product-grid">
            <article class="product-card glass glass-hover">
                <div class="product-image"><img src="https://images.unsplash.com/photo-1563920443079-783e5c786b83?w=700&h=520&fit=crop&auto=format" alt="PTZ camera"></div>
                <div class="product-body">
                    <span class="tag tag-red">PTZ Camera</span>
                    <h3>Hikvision 32× PTZ</h3>
                    <p>Motorized zoom, smart tracking, and weatherproof construction for large-area surveillance.</p>
                    <div class="product-meta">Model: DS-2DE4225IWG-E</div>
                </div>
            </article>
            <article class="product-card glass glass-hover">
                <div class="product-image"><img src="https://images.unsplash.com/photo-1585206031650-9e9a7c87dcfe?w=700&h=520&fit=crop&auto=format" alt="NVR"></div>
                <div class="product-body">
                    <span class="tag tag-teal">Recording System</span>
                    <h3>16-Channel 4K NVR</h3>
                    <p>PoE-ready storage and AI-powered recording designed for fast, stable monitoring.</p>
                    <div class="product-meta">Model: DS-7616NXI-K2/16P</div>
                </div>
            </article>
            <article class="product-card glass glass-hover">
                <div class="product-image"><img src="https://images.unsplash.com/photo-1528312635006-8ea0bc49ec63?w=700&h=520&fit=crop&auto=format" alt="access control"></div>
                <div class="product-body">
                    <span class="tag tag-red">Access Control</span>
                    <h3>Fingerprint Access Panel</h3>
                    <p>Biometric access with RFID support, durable housing, and simple administration.</p>
                    <div class="product-meta">Model: DS-K1T804BF</div>
                </div>
            </article>
            <article class="product-card glass glass-hover">
                <div class="product-image"><img src="https://images.unsplash.com/photo-1639313521811-fdfb1c040ddb?w=700&h=520&fit=crop&auto=format" alt="security kit"></div>
                <div class="product-body">
                    <span class="tag tag-teal">Installation Kit</span>
                    <h3>Complete Installer Kit</h3>
                    <p>Power supplies, connectors, cable management, and protective accessories for smooth deployment.</p>
                    <div class="product-meta">Package: IVAN-KIT-PRO</div>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include("includes/footer.php"); ?>