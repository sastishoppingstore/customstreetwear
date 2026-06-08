<?php
require_once __DIR__ . '/../includes/functions.php';

$stateSlug = $state_slug ?? '';
$citySlug = $city_slug ?? '';
$state = getUSAState($stateSlug);

if (!$state) {
    include __DIR__ . '/404.php';
    exit;
}

$stateName = $state['name'];
$cityName = getCityName($citySlug);

$cityContent = [
    'miami' => [
        'title' => 'Apparel Manufacturer in Miami, Florida',
        'desc' => 'Custom Streetwear is a premier apparel manufacturer in Miami, Florida, providing premium custom sportswear, streetwear, workwear, and uniforms to businesses, teams, and brands throughout Miami-Dade County. Our factory-direct model ensures competitive pricing without compromising on quality, making us the preferred apparel manufacturing partner for Miami clients.',
        'highlights' => ['Premium sportswear & streetwear', 'Team uniforms for Miami schools & leagues', 'Workwear & corporate apparel', 'Hotel & hospitality uniforms', 'Fast delivery in Miami-Dade County'],
        'img' => '/uploads/products/soccer-uniform-kit.jpg'
    ],
    'los-angeles' => [
        'title' => 'Apparel Manufacturer in Los Angeles, California',
        'desc' => 'Custom Streetwear is a leading apparel manufacturer in Los Angeles, California, serving LA brands, fashion labels, sports teams, and businesses with premium custom apparel manufacturing. From streetwear to sportswear, our LA clients benefit from factory-direct pricing and world-class quality.',
        'highlights' => ['Streetwear for LA brands', 'Custom sportswear & uniforms', 'Sample development & small batches', 'Bulk production for retail brands', 'Local pickup available'],
        'img' => '/uploads/products/custom-sublimation-hoodie.jpg'
    ],
    'new-york-city' => [
        'title' => 'Apparel Manufacturer in New York City',
        'desc' => 'Custom Streetwear is a trusted apparel manufacturer in New York City, providing premium custom apparel for NYC fashion brands, sports teams, restaurants, and businesses. Our manufacturing expertise spans streetwear, sportswear, workwear, and uniforms with fast delivery across all five boroughs.',
        'highlights' => ['Streetwear for NYC brands', 'Restaurant & hospitality uniforms', 'Custom sportswear & team kits', 'Bulk production & private label', 'Fast delivery to all 5 boroughs'],
        'img' => '/uploads/products/custom-varsity-jacket.jpg'
    ],
    'chicago' => [
        'title' => 'Apparel Manufacturer in Chicago, Illinois',
        'desc' => 'Custom Streetwear is a leading apparel manufacturer in Chicago, Illinois, providing premium custom sportswear, workwear, corporate apparel, and team uniforms to Chicago businesses, schools, and organizations. Factory-direct pricing and reliable delivery across the Chicagoland area.',
        'highlights' => ['Custom sportswear & uniforms', 'Corporate apparel & promotional wear', 'Workwear & safety apparel', 'School & team uniforms', 'Delivery across Chicagoland'],
        'img' => '/uploads/products/custom-sweatshirt.jpg'
    ],
    'houston' => [
        'title' => 'Apparel Manufacturer in Houston, Texas',
        'desc' => 'Custom Streetwear is a premier apparel manufacturer in Houston, Texas, serving Houston businesses, sports teams, and organizations with premium custom apparel. From custom workwear to promotional apparel and team uniforms, our Houston clients enjoy wholesale pricing and top-quality manufacturing.',
        'highlights' => ['Custom workwear & uniforms', 'Promotional apparel for businesses', 'Sportswear & team uniforms', 'Corporate branded apparel', 'Bulk order discounts'],
        'img' => '/uploads/products/promotional-t-shirt.jpg'
    ],
    'atlanta' => [
        'title' => 'Apparel Manufacturer in Atlanta, Georgia',
        'desc' => 'Custom Streetwear is a leading apparel manufacturer in Atlanta, Georgia, providing premium custom apparel for Atlanta businesses, sports teams, music artists, and brands. Our manufacturing services cover streetwear, sportswear, workwear, and promotional products.',
        'highlights' => ['Custom streetwear & urban fashion', 'Music merchandise & artist apparel', 'Sports team uniforms', 'Corporate & promotional wear', 'Quick turnaround'],
        'img' => '/uploads/products/custom-tracksuit-set.jpg'
    ],
    'seattle' => [
        'title' => 'Apparel Manufacturer in Seattle, Washington',
        'desc' => 'Custom Streetwear is a premier apparel manufacturer in Seattle, Washington, specializing in custom outdoor apparel, streetwear, workwear, and uniforms for Pacific Northwest businesses and teams.',
        'highlights' => ['Outdoor & performance apparel', 'Custom streetwear', 'Workwear & safety gear', 'Team uniforms', 'Sustainable manufacturing options'],
        'img' => '/uploads/products/custom-softshell-jacket.jpg'
    ],
    'dallas' => [
        'title' => 'Apparel Manufacturer in Dallas, Texas',
        'desc' => 'Custom Streetwear is a trusted apparel manufacturer in Dallas, Texas. We provide premium custom sportswear, corporate apparel, workwear, and uniforms for Dallas businesses and sports organizations.',
        'highlights' => ['Corporate branded apparel', 'Custom sportswear', 'Workwear & uniforms', 'Promotional products', 'Wholesale pricing'],
        'img' => '/uploads/products/custom-polo-shirt.jpg'
    ],
    'phoenix' => [
        'title' => 'Apparel Manufacturer in Phoenix, Arizona',
        'desc' => 'Custom Streetwear is a leading apparel manufacturer in Phoenix, Arizona, serving the Southwest with premium custom sportswear, performance wear, workwear, and team uniforms.',
        'highlights' => ['Performance & athletic wear', 'Team uniforms', 'Workwear & hospitality uniforms', 'Custom branding & embroidery', 'Quick shipping to AZ'],
        'img' => '/uploads/products/baseball-uniform.jpg'
    ],
    'boston' => [
        'title' => 'Apparel Manufacturer in Boston, Massachusetts',
        'desc' => 'Custom Streetwear is a premier apparel manufacturer in Boston, Massachusetts, providing premium custom sportswear, academic apparel, streetwear, and team uniforms to Boston schools, colleges, and businesses.',
        'highlights' => ['Academic & college apparel', 'Sports team uniforms', 'Custom streetwear', 'Workwear & corporate wear', 'Bulk order discounts'],
        'img' => '/uploads/products/custom-varsity-jacket.jpg'
    ],
];

$currentCity = $cityContent[$citySlug] ?? [
    'title' => "Apparel Manufacturer in {$cityName}, {$stateName}",
    'desc' => "Custom Streetwear is a trusted apparel manufacturer in {$cityName}, {$stateName}, providing premium custom sportswear, streetwear, workwear, and uniforms to local businesses, teams, and organizations. Factory-direct pricing and reliable delivery.",
    'highlights' => ['Custom sportswear & streetwear', 'Team uniforms', 'Workwear & corporate apparel', 'Promotional & branded wear', 'Fast delivery to ' . $cityName],
    'img' => '/uploads/products/default.jpg'
];

$metaTags = generateMetaTags(
    $currentCity['title'],
    $currentCity['desc'],
    $currentCity['img'],
    SITE_URL . '/locations/' . $stateSlug . '/' . $citySlug
);

$schema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "name" => "Custom Streetwear - {$cityName}",
    "image" => SITE_URL . $currentCity['img'],
    "url" => SITE_URL . '/locations/' . $stateSlug . '/' . $citySlug,
    "telephone" => getSetting('site_phone', ''),
    "description" => $currentCity['desc'],
    "address" => [
        "@type" => "PostalAddress",
        "addressLocality" => $cityName,
        "addressRegion" => $stateName,
        "addressCountry" => "US"
    ],
    "areaServed" => [
        "@type" => "City",
        "name" => $cityName,
        "containedInPlace" => [
            "@type" => "State",
            "name" => $stateName
        ]
    ]
];

$breadcrumb = [
    ['label' => 'USA Locations', 'url' => '/locations'],
    ['label' => $stateName, 'url' => '/locations/' . $stateSlug],
    ['label' => $cityName]
];

include __DIR__ . '/../includes/header.php';
?>

<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div style="display: grid; gap: 40px; align-items: center; grid-template-columns: 1fr 1fr; margin-top: 20px;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-size: 14px; font-weight: 600; color: var(--color-accent); letter-spacing: 2px; text-transform: uppercase;"><?php echo e($stateName); ?> | <?php echo e($state['code']); ?></span>
                </div>
                <span class="section-label">USA Location</span>
                <h1 class="section-title" style="font-size: clamp(24px, 3.5vw, 42px); margin-bottom: 16px;"><?php echo e($currentCity['title']); ?></h1>
                <p style="color: var(--color-text-muted); line-height: 1.8;"><?php echo e($currentCity['desc']); ?></p>
                <div style="display: flex; gap: 16px; margin-top: 30px; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Quote</button>
                    <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
                </div>
            </div>
            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-border);">
                <img src="<?php echo e($currentCity['img']); ?>" alt="<?php echo e($currentCity['title']); ?>" style="width: 100%; height: auto; display: block;">
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display: grid; gap: 40px; grid-template-columns: 1fr 1fr;">
            <div class="reveal">
                <span class="section-label">Our Services</span>
                <h2 class="section-title" style="font-size: 28px;">Apparel Manufacturing in <?php echo e($cityName); ?></h2>
                <p style="color: var(--color-text-muted); line-height: 1.8; margin-bottom: 20px;">We offer comprehensive apparel manufacturing services for clients in <?php echo e($cityName); ?>, <?php echo e($stateName); ?>:</p>
                <ul style="display: grid; gap: 12px;">
                    <?php foreach ($currentCity['highlights'] as $h): ?>
                    <li style="display: flex; gap: 10px; font-size: 14px; color: var(--color-text-muted);">
                        <span style="color: var(--color-accent);">✓</span>
                        <?php echo e($h); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 40px;" class="reveal" style="transition-delay: 0.1s;">
                <span class="section-label">Why Choose Us</span>
                <h3 style="font-family: var(--font-display); font-size: 20px; text-transform: uppercase; margin-bottom: 20px;">Why <?php echo e($cityName); ?> Clients Trust Us</h3>
                <div style="display: grid; gap: 20px;">
                    <div>
                        <strong style="color: var(--color-accent); font-size: 14px;">✓ Factory-Direct Pricing</strong>
                        <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 4px;">No middlemen. Get wholesale prices directly from the manufacturer.</p>
                    </div>
                    <div>
                        <strong style="color: var(--color-accent); font-size: 14px;">✓ Premium Quality</strong>
                        <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 4px;">Top-grade fabrics and materials with strict quality control.</p>
                    </div>
                    <div>
                        <strong style="color: var(--color-accent); font-size: 14px;">✓ Custom Design</strong>
                        <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 4px;">Full customization including sublimation, embroidery, and screen printing.</p>
                    </div>
                    <div>
                        <strong style="color: var(--color-accent); font-size: 14px;">✓ Reliable Delivery</strong>
                        <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 4px;">Fast shipping to <?php echo e($cityName); ?>, <?php echo e($stateName); ?> with order tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--color-bg-alt); padding: 40px 0;">
    <div class="container">
        <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
            <a href="/sports-uniforms" class="btn btn-outline">Sports Uniforms</a>
            <a href="/locations/<?php echo e($stateSlug); ?>" class="btn btn-outline">All <?php echo e($stateName); ?> Cities</a>
            <a href="/locations" class="btn btn-outline">All USA Locations</a>
            <a href="/contact" class="btn btn-outline">Contact Us</a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="cta-section-accent"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label">Get Started</span>
            <h2 class="cta-section-title">Custom Apparel for Your <?php echo e($cityName); ?> Business?</h2>
            <p class="cta-section-desc">Contact us today for a free quote. We deliver premium custom apparel to <?php echo e($cityName); ?>, <?php echo e($stateName); ?> with factory-direct pricing.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Free Quote</button>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
