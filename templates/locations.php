<?php
require_once __DIR__ . '/../includes/functions.php';

$usaStates = getUSAStates();

$metaTags = generateMetaTags(
    'Custom Apparel Manufacturer in USA',
    'Custom Streetwear is a leading custom apparel manufacturer in the USA. Premium sportswear, streetwear, workwear, and uniforms. Serving all 50 states with factory-direct pricing.',
    '',
    SITE_URL . '/locations'
);

$breadcrumb = [
    ['label' => 'Locations']
];

include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div class="section-header" style="text-align: left; margin-bottom: 20px;">
            <span class="section-label">USA Locations</span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px);">Custom Apparel Manufacturer in USA</h1>
            <p class="section-desc" style="margin: 0; max-width: 700px;">Custom Streetwear proudly serves all 50 states with premium custom apparel manufacturing. Find your state below and discover how we serve your local area with factory-direct pricing and reliable delivery.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="country-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
            <?php $index = 0; foreach ($usaStates as $slug => $state): ?>
            <a href="/locations/<?php echo e($slug); ?>" class="country-card reveal" style="padding: 20px; transition-delay: <?php echo ($index % 16) * 0.03; ?>s">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;"><?php echo getStateFlag(); ?></div>
                <h3 class="country-name" style="font-size: 15px;"><?php echo e($state['name']); ?></h3>
                <span style="font-size: 11px; color: var(--color-accent);">View Cities →</span>
            </a>
            <?php $index++; endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section" style="padding: 60px 0;">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <h2 class="cta-section-title" style="font-size: 32px;">Custom Apparel for Your State?</h2>
            <p class="cta-section-desc">We ship to all 50 states with reliable logistics. Contact us to discuss your requirements.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
