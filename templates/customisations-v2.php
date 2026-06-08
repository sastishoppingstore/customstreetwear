<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$customisations = dbFetchAll("SELECT * FROM customisations WHERE status = 1 ORDER BY sort_order");

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Customisation Services - Custom Apparel Manufacturing USA | Custom Streetwear',
    'meta_description' => 'Explore our custom apparel customisation services: sublimation, screen printing, embroidery, cut & sew, private label and more. Factory-direct pricing for USA brands.',
    'focus_keyword' => 'Custom Apparel Customisation Services USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-40%;right:-5%;width:400px;height:400px;background:radial-gradient(circle,rgba(57,255,20,0.04),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'Customisations']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('custom_page_label', 'Our Services')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('custom_page_title', 'Custom Apparel Customisation Services')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:720px;line-height:1.6;"><?php echo e(getSetting('custom_page_desc', 'From design to finished product, we offer comprehensive customisation services. Whether you need sublimation, screen printing, or cut & sew, our USA-based facility delivers premium quality at factory-direct prices.')); ?></p>
        </div>
    </div>
</section>

<?php if (getSetting('custom_trust_bar_enabled', '1') === '1'): ?>
<section style="border-bottom:1px solid var(--color-border);"><div class="trust-bar"><div class="container"><div class="trust-bar-inner">
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Trusted by 2500+ USA Brands</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Ships Within 15-20 Days</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>100% Quality Guaranteed</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Factory-Direct Pricing</span>
</div></div></div></section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('custom_grid_label', 'What We Offer')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('custom_grid_title', 'Complete Customisation Solutions')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('custom_grid_desc', 'From small batches to mass production, we handle every customisation need under one roof.')); ?></p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
            <?php foreach ($customisations as $index => $c): ?>
            <div class="glass-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
                <h3 class="glass-card-title"><?php echo e($c['title']); ?></h3>
                <p class="glass-card-text"><?php echo e($c['description'] ?: 'Full-service customisation available. Contact us for details.'); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('custom_trust_label', 'Why Choose Us')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('custom_trust_title', 'Trusted Customisation Partner')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('custom_trust_desc', 'We deliver quality and reliability on every order.')); ?></p>
        </div>
        <?php echo renderTrustSignals(); ?>
    </div>
</section>

<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('custom_cta_label', 'Get Started')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('custom_cta_title', 'Ready to Customise Your Apparel?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('custom_cta_desc', 'Contact us to discuss your customisation requirements and get a free quote within 24 hours.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('custom_cta_btn1', 'Request a Quote')); ?></button>
                <a href="/contact" class="btn btn-outline btn-lg"><?php echo e(getSetting('custom_cta_btn2', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
