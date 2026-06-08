<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$stateSlug = $state_slug ?? ($_GET['state'] ?? '');
$citySlug = $city_slug ?? ($_GET['city'] ?? '');
$stateInfo = getUSAState($stateSlug);

if (!$stateInfo) {
    include __DIR__ . '/404.php';
    return;
}
$stateName = $stateInfo['name'];
$cityName = getCityName($citySlug);

$siteName = getSetting('site_name', 'Custom Streetwear');
$targetKeyword = "Apparel Manufacturer in {$cityName}, {$stateName}";
$metaDesc = getSetting("location_seo_city_{$stateSlug}_{$citySlug}", '') ?: getCitySEODescription($cityName, $stateName);

$metaTags = generateAdvancedMetaTags([
    'meta_title' => $targetKeyword,
    'meta_description' => $metaDesc,
    'focus_keyword' => $targetKeyword,
    'og_type' => 'website'
]);

$locationContent = dbFetchOne("SELECT * FROM location_seo WHERE location_type='city' AND state_slug=? AND city_slug=?", [$stateSlug, $citySlug]);
$cityImage = "/uploads/locations/{$stateSlug}/{$citySlug}.jpg";
$stateImage = "/uploads/locations/{$stateSlug}.jpg";
$locationHeroImage = file_exists(CSW_ROOT . $cityImage)
    ? $cityImage
    : (file_exists(CSW_ROOT . $stateImage) ? $stateImage : '/uploads/locations/placeholder.jpg');
$pageTitle = $locationContent['page_title'] ?? $targetKeyword;
$h1 = $locationContent['h1_heading'] ?? $targetKeyword;
$cityFaqs = [
    [
        'question' => "Do you serve apparel manufacturing buyers in {$cityName}, {$stateName}?",
        'answer' => "Yes. Custom Streetwear serves businesses, teams, schools, events, and private label buyers in {$cityName}, {$stateName} with custom apparel manufacturing and USA delivery."
    ],
    [
        'question' => "What apparel can be manufactured for {$cityName} customers?",
        'answer' => 'Available products include sports uniforms, streetwear, hoodies, t-shirts, workwear, jackets, promotional apparel, and private label clothing.'
    ],
    [
        'question' => "Can {$cityName} businesses order bulk custom apparel?",
        'answer' => 'Yes. Bulk orders are supported with factory-direct pricing, custom branding, product development guidance, and production support.'
    ]
];
$extraHead = schemaScript(apparelServiceSchema($pageTitle, $metaDesc, SITE_URL . "/locations/{$stateSlug}/{$citySlug}", [
    'type' => 'City',
    'name' => "{$cityName}, {$stateName}"
]));
$extraHead .= schemaScript(faqSchemaFromRows($cityFaqs, "{$pageTitle} FAQ"));

include __DIR__ . '/../includes/header.php';
?>

<section class="location-hero">
    <div class="location-hero-bg" style="background-image:url('<?php echo e($locationHeroImage); ?>');"></div>
    <div class="location-hero-overlay"></div>
    <div class="container">
        <div class="location-hero-content">
            <?php echo advancedBreadcrumb([
                ['label' => 'USA Locations', 'url' => SITE_URL . '/locations'],
                ['label' => $stateName, 'url' => SITE_URL . '/locations/' . $stateSlug],
                ['label' => $cityName]
            ]); ?>
            <h1><?php echo e($h1); ?></h1>
            <p><?php echo e($locationContent['meta_description'] ?? $metaDesc); ?></p>
            <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Get a Quote in <?php echo e($cityName); ?></button>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php if ($locationContent && $locationContent['content_top']): ?>
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="reveal"><?php echo $locationContent['content_top']; ?></div>
    </div>
</section>
<?php endif; ?>

<!-- Services in City -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Our Services in <?php echo e($cityName); ?></span>
            <h2 class="section-title">Custom Apparel Manufacturing in <?php echo e($cityName); ?></h2>
            <p class="section-desc">Premium custom apparel services available for businesses, teams, and organizations in <?php echo e($cityName); ?>, <?php echo e($stateName); ?>.</p>
        </div>
        <div class="why-choose-grid" style="--grid-min: 220px;">
            <div class="glass-card reveal">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
                <h3 class="glass-card-title">Custom Sportswear</h3>
                <p class="glass-card-text">High-performance athletic wear for teams and sports organizations in <?php echo e($cityName); ?>.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.1s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></div>
                <h3 class="glass-card-title">Team Uniforms</h3>
                <p class="glass-card-text">Custom uniforms for schools, clubs, and professional teams in <?php echo e($cityName); ?>.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.2s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
                <h3 class="glass-card-title">Workwear & Uniforms</h3>
                <p class="glass-card-text">Professional workwear and corporate uniforms for <?php echo e($cityName); ?> businesses.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.3s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg></div>
                <h3 class="glass-card-title">Nationwide Delivery</h3>
                <p class="glass-card-text">Fast, reliable shipping to <?php echo e($cityName); ?>, <?php echo e($stateName); ?> and all across the USA.</p>
            </div>
        </div>
    </div>
</section>

<?php if ($locationContent && $locationContent['content_bottom']): ?>
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="reveal"><?php echo $locationContent['content_bottom']; ?></div>
    </div>
</section>
<?php endif; ?>

<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($cityName); ?> FAQ</span>
            <h2 class="section-title">Apparel Manufacturing Questions in <?php echo e($cityName); ?></h2>
            <p class="section-desc">AI-readable answers for local buyers looking for a custom apparel manufacturer in <?php echo e($cityName); ?>, <?php echo e($stateName); ?>.</p>
        </div>
        <div class="faq-section reveal" style="max-width:900px;margin:0 auto;">
            <?php foreach ($cityFaqs as $faq): ?>
            <div class="faq-item">
                <button class="faq-question" onclick="this.closest('.faq-item').classList.toggle('active');">
                    <?php echo e($faq['question']); ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer"><div class="faq-answer-inner"><?php echo e($faq['answer']); ?></div></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e($cityName); ?> Apparel Manufacturing</span>
            <h2 class="cta-section-title">Custom Apparel Manufacturing in <?php echo e($cityName); ?></h2>
            <p class="cta-section-desc">Get premium custom apparel manufactured and delivered to <?php echo e($cityName); ?>, <?php echo e($stateName); ?>. Factory-direct pricing, no minimum quantity.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/locations/<?php echo e($stateSlug); ?>" class="btn btn-outline btn-lg">More <?php echo e($stateName); ?> Cities</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
