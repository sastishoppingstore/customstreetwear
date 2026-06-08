<?php
/**
 * Custom Streetwear - Customisations Page
 */
require_once __DIR__ . '/../includes/functions.php';

$customisations = dbFetchAll("SELECT * FROM customisations WHERE status = 1 ORDER BY sort_order");
$metaTags = generateMetaTags('Customisation Services', 'We offer sublimation, screen printing, embroidery, cut & sew, private label and more customisation services.');
$breadcrumb = [['label' => 'Customisations']];
include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div class="section-header" style="text-align: left; margin-bottom: 20px;">
            <span class="section-label">Our Services</span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px);">Customisations</h1>
            <p class="section-desc" style="margin: 0; max-width: 600px;">From design to finished product, we offer comprehensive customisation services to bring your vision to life.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="custom-grid">
            <?php foreach ($customisations as $index => $c): ?>
            <div class="custom-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="custom-card-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3 class="custom-card-title"><?php echo e($c['title']); ?></h3>
                <p class="custom-card-desc"><?php echo e($c['description'] ?: 'Content pending: replace from Admin Panel after authorized copy is provided.'); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section" style="padding: 60px 0;">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <h2 class="cta-section-title" style="font-size: 32px;">Ready to Customize?</h2>
            <p class="cta-section-desc">Contact us to discuss your customisation requirements and get a free quote.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
