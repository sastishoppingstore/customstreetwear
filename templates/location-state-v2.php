<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$stateSlug = $state_slug ?? ($_GET['state'] ?? '');
$stateInfo = getUSAState($stateSlug);
if (!$stateInfo) {
    include __DIR__ . '/404.php';
    return;
}
$stateName = $stateInfo['name'];
$stateCode = $stateInfo['code'];
$cities = getStateCities($stateSlug);

$siteName = getSetting('site_name', 'Custom Streetwear');
$targetKeyword = "Custom Apparel Manufacturer in {$stateName}";
$metaDesc = getSetting("location_seo_state_{$stateSlug}", '') ?: getLocationMetaDescription('state', $stateName);

$metaTags = generateAdvancedMetaTags([
    'meta_title' => $targetKeyword,
    'meta_description' => $metaDesc,
    'focus_keyword' => $targetKeyword,
    'og_type' => 'website'
]);

$locationContent = dbFetchOne("SELECT * FROM location_seo WHERE location_type='state' AND state_slug=?", [$stateSlug]);
$stateImage = "/uploads/locations/{$stateSlug}.jpg";
$locationHeroImage = file_exists(CSW_ROOT . $stateImage) ? $stateImage : '/uploads/locations/placeholder.jpg';

include __DIR__ . '/../includes/header.php';
?>

<section class="location-hero">
    <div class="location-hero-bg" style="background-image:url('<?php echo e($locationHeroImage); ?>');"></div>
    <div class="location-hero-overlay"></div>
    <div class="container">
        <div class="location-hero-content">
            <?php echo advancedBreadcrumb([['label' => 'USA Locations', 'url' => SITE_URL . '/locations'], ['label' => $stateName]]); ?>
            <h1><?php echo e($locationContent['h1_heading'] ?? "Custom Apparel Manufacturer in {$stateName}"); ?></h1>
            <p><?php echo e($locationContent['meta_description'] ?? $metaDesc); ?></p>
            <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Quote in <?php echo e($stateName); ?></button>
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

<!-- Cities Grid -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Cities We Serve in <?php echo e($stateName); ?></span>
            <h2 class="section-title">Custom Apparel in <?php echo e($stateName); ?> Cities</h2>
            <p class="section-desc">Factory-direct custom apparel manufacturing available across all major cities in <?php echo e($stateName); ?>. Click your city for more information.</p>
        </div>
        <div class="country-grid">
            <?php foreach ($cities as $idx => $citySlug):
                $cityName = getCityName($citySlug);
            ?>
            <a href="/locations/<?php echo e($stateSlug); ?>/<?php echo e($citySlug); ?>" class="country-card reveal" style="transition-delay:<?php echo $idx * 0.05; ?>s;--card-index:<?php echo $idx; ?>;">
                <div class="country-flag" style="width:48px;height:48px;font-size:20px;">🏙️</div>
                <h3 class="country-name"><?php echo e($cityName); ?></h3>
                <span style="font-size:12px;color:var(--color-accent);">Get a Quote →</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="location-stats">
            <div class="location-stat reveal">
                <div class="location-stat-value" data-counter="<?php echo e(getSetting('home_stat_clients_number', '2500')); ?>">0</div>
                <div class="location-stat-label">USA Clients Served</div>
            </div>
            <div class="location-stat reveal" style="transition-delay:0.1s;">
                <div class="location-stat-value"><?php echo count($cities); ?></div>
                <div class="location-stat-label">Cities in <?php echo e($stateName); ?></div>
            </div>
            <div class="location-stat reveal" style="transition-delay:0.2s;">
                <div class="location-stat-value">98%</div>
                <div class="location-stat-label">On-Time Delivery</div>
            </div>
            <div class="location-stat reveal" style="transition-delay:0.3s;">
                <div class="location-stat-value">13+</div>
                <div class="location-stat-label">Years Experience</div>
            </div>
        </div>
    </div>
</section>

<?php if ($locationContent && $locationContent['content_bottom']): ?>
<section class="section">
    <div class="container">
        <div class="reveal"><?php echo $locationContent['content_bottom']; ?></div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label">Manufacturing for <?php echo e($stateName); ?></span>
            <h2 class="cta-section-title">Need Custom Apparel in <?php echo e($stateName); ?>?</h2>
            <p class="cta-section-desc">Get factory-direct pricing on premium custom sportswear, streetwear, workwear, and uniforms delivered to <?php echo e($stateName); ?>.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<!-- State Schema -->
<script type="application/ld+json">
<?php
echo json_encode([
    "@context" => "https://schema.org",
    "@type" => "Product",
    "name" => "Custom Apparel Manufacturing in {$stateName}",
    "description" => $metaDesc,
    "areaServed" => [
        "@type" => "State",
        "name" => $stateName
    ],
    "offers" => [
        "@type" => "AggregateOffer",
        "priceCurrency" => "USD",
        "availability" => "https://schema.org/InStock"
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
