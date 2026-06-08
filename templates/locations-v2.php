<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$usaStates = getUSAStates();

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Custom Apparel Manufacturer in USA - Serving All 50 States | Custom Streetwear',
    'meta_description' => 'Custom Streetwear is a leading custom apparel manufacturer serving all 50 states. Premium sportswear, streetwear, workwear, and uniforms. Factory-direct pricing nationwide.',
    'focus_keyword' => 'Custom Apparel Manufacturer in USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-50%;left:-10%;width:500px;height:500px;background:radial-gradient(circle,rgba(57,255,20,0.04),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'USA Locations']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('locations_page_label', 'USA Coverage')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('locations_page_title', 'Custom Apparel Manufacturer in USA')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:720px;line-height:1.6;"><?php echo e(getSetting('locations_page_desc', 'Custom Streetwear proudly serves all 50 states with premium custom apparel manufacturing. Find your state below and discover how we serve your local area with factory-direct pricing and reliable delivery.')); ?></p>
        </div>
    </div>
</section>

<?php if (getSetting('locations_trust_bar_enabled', '1') === '1'): ?>
<section style="border-bottom:1px solid var(--color-border);"><div class="trust-bar"><div class="container"><div class="trust-bar-inner">
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Trusted by 2500+ USA Brands</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Ships Within 15-20 Days</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>100% Quality Guaranteed</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Factory-Direct Pricing</span>
</div></div></div></section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="reveal" style="padding:24px 0;">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:16px;">
                <?php $idx = 0; foreach ($usaStates as $slug => $state): ?>
                <a href="/locations/<?php echo e($slug); ?>" class="glass-card reveal" style="padding:20px;text-align:center;text-decoration:none;transition-delay:<?php echo ($idx % 20) * 0.03; ?>s;">
                    <div style="font-size:28px;margin-bottom:4px;"><?php echo getStateFlag(); ?></div>
                    <h3 style="font-size:14px;font-weight:600;margin:0 0 4px 0;"><?php echo e($state['name']); ?></h3>
                    <span style="font-size:11px;color:var(--color-accent);">View Cities →</span>
                </a>
                <?php $idx++; endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('locations_trust_label', 'Nationwide Coverage')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('locations_trust_title', 'Why USA Brands Trust Us')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('locations_trust_desc', 'We deliver premium custom apparel to businesses and teams across America.')); ?></p>
        </div>
        <?php echo renderTrustSignals(); ?>
    </div>
</section>

<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('locations_cta_label', 'Get Started')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('locations_cta_title', 'Custom Apparel for Your State?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('locations_cta_desc', 'We ship to all 50 states with reliable logistics. Contact us to discuss your requirements.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('locations_cta_btn1', 'Request a Quote')); ?></button>
                <a href="/contact" class="btn btn-outline btn-lg"><?php echo e(getSetting('locations_cta_btn2', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
