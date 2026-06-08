<?php
require_once __DIR__ . '/../includes/functions.php';

$stateSlug = $state_slug ?? '';
$state = getUSAState($stateSlug);

if (!$state) {
    include __DIR__ . '/404.php';
    exit;
}

$stateName = $state['name'];
$cities = getStateCities($stateSlug);
$seoTitle = "Custom Apparel Manufacturer in {$stateName}";
$seoDesc = getStateSEODescription($stateName);

$metaTags = generateMetaTags(
    $seoTitle,
    $seoDesc,
    '',
    SITE_URL . '/locations/' . $stateSlug
);

$breadcrumb = [
    ['label' => 'USA Locations', 'url' => '/locations'],
    ['label' => $stateName]
];

$cityData = [
    'miami' => ['desc' => 'Custom apparel manufacturer in Miami, Florida specializing in premium sportswear, streetwear, and team uniforms. Serving Miami-Dade County with factory-direct pricing.', 'image' => '/uploads/products/soccer-uniform-kit.jpg'],
    'los-angeles' => ['desc' => 'Custom apparel manufacturer in Los Angeles, California. Premium sportswear, streetwear, and uniforms for LA brands, teams, and businesses.', 'image' => '/uploads/products/custom-sublimation-hoodie.jpg'],
    'new-york-city' => ['desc' => 'Custom apparel manufacturer in New York City. Premium streetwear, sportswear, and uniforms serving NYC boroughs with fast delivery.', 'image' => '/uploads/products/custom-varsity-jacket.jpg'],
    'chicago' => ['desc' => 'Custom apparel manufacturer in Chicago, Illinois. Premium sportswear, workwear, and team uniforms for the Windy City.', 'image' => '/uploads/products/custom-sweatshirt.jpg'],
    'houston' => ['desc' => 'Custom apparel manufacturer in Houston, Texas. Premium sportswear, workwear, and promotional apparel for businesses.', 'image' => '/uploads/products/promotional-t-shirt.jpg'],
    'atlanta' => ['desc' => 'Custom apparel manufacturer in Atlanta, Georgia. Premium sportswear, streetwear, and uniforms for Atlanta businesses and teams.', 'image' => '/uploads/products/custom-tracksuit-set.jpg'],
    'seattle' => ['desc' => 'Custom apparel manufacturer in Seattle, Washington. Premium outdoor apparel, streetwear, and workwear for the Pacific Northwest.', 'image' => '/uploads/products/custom-softshell-jacket.jpg'],
    'denver' => ['desc' => 'Custom apparel manufacturer in Denver, Colorado. Premium performance wear, streetwear, and outdoor apparel.', 'image' => '/uploads/products/hockey-uniform.jpg'],
    'las-vegas' => ['desc' => 'Custom apparel manufacturer in Las Vegas, Nevada. Premium uniforms, promotional apparel, and team wear.', 'image' => '/uploads/products/promotional-hoodie.jpg'],
    'dallas' => ['desc' => 'Custom apparel manufacturer in Dallas, Texas. Premium sportswear, workwear, and corporate apparel.', 'image' => '/uploads/products/custom-polo-shirt.jpg'],
    'phoenix' => ['desc' => 'Custom apparel manufacturer in Phoenix, Arizona. Premium performance wear and team uniforms for desert sports.', 'image' => '/uploads/products/baseball-uniform.jpg'],
    'portland' => ['desc' => 'Custom apparel manufacturer in Portland, Oregon. Premium streetwear, outdoor apparel, and custom designs.', 'image' => '/uploads/products/custom-tie-dye-hoodie.jpg'],
    'detroit' => ['desc' => 'Custom apparel manufacturer in Detroit, Michigan. Premium sportswear, workwear, and team uniforms.', 'image' => '/uploads/products/american-football-uniform.jpg'],
    'nashville' => ['desc' => 'Custom apparel manufacturer in Nashville, Tennessee. Premium performance wear, streetwear, and promotional apparel.', 'image' => '/uploads/products/custom-sublimation-t-shirt.jpg'],
    'boston' => ['desc' => 'Custom apparel manufacturer in Boston, Massachusetts. Premium sportswear, academic apparel, and team uniforms.', 'image' => '/uploads/products/custom-varsity-jacket.jpg'],
];

include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div style="display: grid; gap: 30px; align-items: center; grid-template-columns: 1fr; margin-top: 20px;">
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <span style="font-size: 48px;"><?php echo getStateFlag(); ?></span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--color-accent); letter-spacing: 2px; text-transform: uppercase;"><?php echo e($state['code']); ?></span>
                </div>
                <span class="section-label">USA Location</span>
                <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px); margin-bottom: 16px;">Custom Apparel Manufacturer in <?php echo e($stateName); ?></h1>
                <p style="color: var(--color-text-muted); max-width: 700px; line-height: 1.8;"><?php echo e($seoDesc); ?></p>
                <div style="display: flex; gap: 16px; margin-top: 30px; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Get a Quote</button>
                    <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header reveal" style="text-align: left;">
            <span class="section-label">Cities We Serve</span>
            <h2 class="section-title">Cities in <?php echo e($stateName); ?></h2>
            <p class="section-desc" style="margin: 0;">We serve businesses and teams across <?php echo e($stateName); ?>. Browse cities below or contact us for your specific location.</p>
        </div>
        
        <?php if (!empty($cities)): ?>
        <div class="country-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
            <?php foreach ($cities as $citySlug): ?>
            <?php $cityName = getCityName($citySlug); ?>
            <?php $cityInfo = $cityData[$citySlug] ?? ['desc' => "Custom apparel manufacturing services in {$cityName}, {$stateName}. Premium sportswear, streetwear, and uniforms.", 'image' => '']; ?>
            <a href="/locations/<?php echo e($stateSlug); ?>/<?php echo e($citySlug); ?>" class="country-card reveal" style="padding: 0; overflow: hidden; text-align: left;">
                <?php if ($cityInfo['image']): ?>
                <div style="width: 100%; height: 140px; overflow: hidden; background: var(--color-bg-alt);">
                    <img src="<?php echo e($cityInfo['image']); ?>" alt="<?php echo e($cityName); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <?php endif; ?>
                <div style="padding: 20px;">
                    <h3 class="country-name" style="font-size: 16px;"><?php echo e($cityName); ?></h3>
                    <p class="country-desc" style="font-size: 13px;"><?php echo e(truncate($cityInfo['desc'], 100)); ?></p>
                    <span style="font-size: 13px; color: var(--color-accent); font-weight: 500;">Learn More →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px;">
            <p style="color: var(--color-text-muted);">Contact us for custom apparel services in <?php echo e($stateName); ?>.</p>
            <button class="btn btn-primary" style="margin-top: 20px;" onclick="openQuoteModal()">Request a Quote</button>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="cta-section" style="padding: 60px 0;">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <h2 class="cta-section-title" style="font-size: 32px;">Need Custom Apparel in <?php echo e($stateName); ?>?</h2>
            <p class="cta-section-desc">We ship to all cities in <?php echo e($stateName); ?> with reliable logistics. Get your free quote today.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
