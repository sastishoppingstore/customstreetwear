<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$sliders = dbFetchAll("SELECT * FROM sliders WHERE status = 1 ORDER BY sort_order");
$categories = getCategories();
$bestSellers = getProducts(['best_seller' => true], intval(getSetting('home_bestseller_count', '8')));
$featuredProducts = getProducts(['featured' => true], intval(getSetting('home_featured_count', '8')));
$testimonials = dbFetchAll("SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order LIMIT " . intval(getSetting('home_testimonial_count', '6')));
$blogs = dbFetchAll("SELECT * FROM blogs WHERE status = 1 ORDER BY published_at DESC LIMIT " . intval(getSetting('home_blog_count', '4')));
$videos = dbFetchAll("SELECT * FROM videos WHERE status = 1 ORDER BY sort_order LIMIT " . intval(getSetting('home_video_count', '3')));
$brochures = dbFetchAll("SELECT * FROM brochures WHERE status = 1 ORDER BY sort_order LIMIT " . intval(getSetting('home_brochure_count', '8')));
$usaStates = array_slice(getUSAStates(), 0, 8);
$homeSections = dbFetchAll("SELECT * FROM home_sections WHERE visibility = 'visible' ORDER BY sort_order");
$sectionKeys = array_column($homeSections, 'section_key');
$sectionsByKey = [];
foreach ($homeSections as $section) {
    $sectionsByKey[$section['section_key']] = $section;
}
$sectionText = function ($sectionKey, $field, $settingKey, $default = '') use ($sectionsByKey) {
    $value = $sectionsByKey[$sectionKey][$field] ?? '';
    return $value !== '' && $value !== null ? $value : getSetting($settingKey, $default);
};
$sectionStyle = function ($sectionKey, $default = '') use ($sectionsByKey) {
    $section = $sectionsByKey[$sectionKey] ?? [];
    $styles = trim($default);
    if (!empty($section['background_color'])) {
        $styles .= ($styles ? ';' : '') . 'background:' . $section['background_color'];
    }
    if (!empty($section['background_image'])) {
        $styles .= ($styles ? ';' : '') . "background-image:url('" . e($section['background_image']) . "');background-size:cover;background-position:center";
    }
    return $styles;
};

$metaTags = generateAdvancedMetaTags([
    'meta_title' => getSetting('home_meta_title', 'Custom Apparel Manufacturer in USA | Custom Streetwear'),
    'meta_description' => getSetting('home_meta_desc', 'Custom Streetwear is a USA-focused custom apparel manufacturer for sports uniforms, streetwear, workwear, promotional apparel, and private label clothing with factory-direct pricing.'),
    'focus_keyword' => 'Custom Apparel Manufacturer in USA',
]);
$extraHead = schemaScript([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Custom Apparel Manufacturer in USA',
    'description' => getSetting('home_meta_desc', 'USA-focused custom apparel manufacturing for teams, brands, schools, companies, and organizations.'),
    'url' => SITE_URL,
    'mainEntity' => [
        '@type' => 'ItemList',
        'name' => 'Custom Apparel Categories',
        'itemListElement' => array_map(function ($cat, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $cat['name'],
                'url' => SITE_URL . '/category/' . $cat['slug']
            ];
        }, $categories, array_keys($categories))
    ]
]);
include __DIR__ . '/../includes/header.php';
?>

<?php
$homeCustomCss = '';
foreach ($homeSections as $section) {
    if (!empty($section['custom_css'])) {
        $homeCustomCss .= "\n/* " . preg_replace('/[^a-z0-9_-]/i', '', $section['section_key']) . " */\n" . $section['custom_css'];
    }
}
if ($homeCustomCss): ?>
<style><?php echo $homeCustomCss; ?></style>
<?php endif; ?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<!-- First Look Psychology Elements -->
<div class="first-look-elements" style="position:fixed;top:0;left:0;right:0;z-index:99;pointer-events:none;">
    <?php echo renderFirstLookElements(); ?>
</div>
<?php endif; ?>

<?php if (getSetting('home_trust_bar_enabled', '1') === '1'): ?>
<!-- Trust Bar -->
<div class="trust-bar">
    <div class="container">
        <div class="trust-bar-inner">
            <span class="trust-bar-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Trusted by 2500+ USA Brands
            </span>
            <span class="trust-bar-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Ships Within 15-20 Days
            </span>
            <span class="trust-bar-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                100% Quality Guaranteed
            </span>
            <span class="trust-bar-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                Factory-Direct Pricing
            </span>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (in_array('hero', $sectionKeys)): ?>
<!-- Hero Slider with 3D Background -->
<section class="hero-slider" id="heroSlider">
    <div id="hero3d-canvas" style="position:absolute;inset:0;z-index:0;pointer-events:none;"></div>
    <?php foreach ($sliders as $index => $slide): ?>
    <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
        <div class="hero-slide-bg" style="background-image: url('<?php echo e($slide['image']); ?>')"></div>
        <div class="hero-slide-overlay" style="background: linear-gradient(135deg, <?php echo e(getSetting('home_hero_overlay_color', '#050505')); ?> 0%, rgba(5,5,5,0.4) 100%);"></div>
        <div class="container">
            <div class="hero-slide-content">
                <?php if (getSetting('site_social_proof_enabled', '1') === '1'): ?>
                <div class="urgency-bar" style="animation:fadeInUp 0.6s ease both;margin-bottom:16px;">
                    <span class="urgency-dot"></span>
                    <span><?php echo e(getSetting('home_urgent_badge_text', 'Bulk Orders Welcome | Ships Within 15-20 Days')); ?></span>
                </div>
                <?php endif; ?>
                <span class="hero-label" style="display:inline-block;animation:fadeInUp 0.6s ease both;"><?php echo e($slide['subtitle']); ?></span>
                <h1 class="hero-title" style="animation:fadeInUp 0.6s ease 0.1s both;"><?php echo nl2br(e($slide['title'])); ?></h1>
                <p class="hero-desc" style="animation:fadeInUp 0.6s ease 0.2s both;"><?php echo e($slide['description']); ?></p>
                <div class="hero-buttons" style="animation:fadeInUp 0.6s ease 0.3s both;">
                    <a href="<?php echo e($slide['button_link']); ?>" class="btn btn-primary btn-lg magnetic"><?php echo e($slide['button_text']); ?></a>
                    <a href="#categories" class="btn btn-outline btn-lg magnetic">Explore Products</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="hero-controls">
        <button class="hero-control" onclick="prevSlide(); resetAutoSlide();" aria-label="Previous slide"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></button>
        <button class="hero-control" onclick="nextSlide(); resetAutoSlide();" aria-label="Next slide"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></button>
    </div>
    <div class="hero-dots">
        <?php foreach ($sliders as $index => $slide): ?>
        <div class="hero-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></div>
        <?php endforeach; ?>
    </div>
</section>

<?php if (getSetting('home_hero_3d_enabled', '1') === '1'): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="/assets/js/3d-background-v2.js?v=<?php echo filemtime(CSW_ROOT . '/assets/js/3d-background-v2.js'); ?>"></script>
<?php endif; ?>
<?php endif; ?>

<?php if (in_array('categories', $sectionKeys)): ?>
<section class="section" id="categories" style="<?php echo e($sectionStyle('categories', 'background: var(--color-bg-alt);')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('categories', 'subtitle', 'home_categories_label', 'Our Collection')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('categories', 'title', 'home_categories_title', 'Product Categories')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('categories', 'description', 'home_categories_desc', 'Explore our wide range of custom apparel categories.')); ?></p>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $index => $cat): ?>
            <a href="/category/<?php echo e($cat['slug']); ?>" class="category-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s;--card-index:<?php echo $index; ?>;">
                <div class="category-card-image" style="background-image: url('<?php echo e($cat['image'] ?: '/uploads/categories/' . $cat['slug'] . '.jpg'); ?>')"></div>
                <div class="category-card-overlay"></div>
                <div class="category-card-content">
                    <h3 class="category-card-title"><?php echo e($cat['name']); ?></h3>
                    <span class="category-card-count">View Products →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('bestsellers', $sectionKeys)): ?>
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('bestsellers', 'subtitle', 'home_bestseller_label', 'Most Popular')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('bestsellers', 'title', 'home_bestseller_title', 'Best Seller Products')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('bestsellers', 'description', 'home_bestseller_desc', 'Our most popular custom apparel products trusted by brands and teams nationwide.')); ?></p>
        </div>
        <div class="product-grid">
            <?php foreach ($bestSellers as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s;--card-index:<?php echo $index; ?>;">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action" title="View Details"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        <button class="product-card-action" title="Request Quote" onclick="openQuoteModal()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></button>
                    </div>
                    <?php if ($product['is_best_seller']): ?><span class="product-card-badge">Best Seller</span><?php endif; ?>
                </div>
                <div class="product-card-info">
                    <span class="product-card-category"><?php echo e($product['category_name']); ?></span>
                    <h3 class="product-card-title"><?php echo e($product['title']); ?></h3>
                    <span class="product-card-sku"><?php echo e($product['sku']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('about', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('about', 'background: var(--color-bg-alt);')); ?>">
    <div class="container">
        <div class="about-grid">
            <div class="about-image reveal">
                <img src="<?php echo e($sectionText('about', 'image', 'home_about_image', '/uploads/pages/about-factory.jpg')); ?>" alt="<?php echo e($sectionText('about', 'image_alt', 'home_about_image_alt', 'Custom Streetwear Manufacturing Facility')); ?>" loading="lazy">
            </div>
            <div class="about-content reveal">
                <span class="section-label"><?php echo e($sectionText('about', 'subtitle', 'home_about_label', 'About Us')); ?></span>
                <h2><?php echo e($sectionText('about', 'title', 'home_about_title', "America's Trusted Custom Apparel Manufacturer Since 2012")); ?></h2>
                <p><?php echo e($sectionText('about', 'description', 'home_about_text1', 'Custom Streetwear is a premier manufacturer of custom sportswear, streetwear, workwear, uniforms, and leather garments.')); ?></p>
                <p><?php echo nl2br(e(getSetting('home_about_text2', 'Our state-of-the-art manufacturing facility spans 150,000 square feet with a production capacity of over 50,000 units per day.'))); ?></p>
                <a href="<?php echo e(getSetting('home_about_link', '/about-us')); ?>" class="btn btn-primary"><?php echo e(getSetting('home_about_link_text', 'Learn More About Us')); ?></a>
                <div class="about-stats">
                    <div class="about-stat"><div class="about-stat-number" data-counter="<?php echo e(getSetting('home_stat_units_number', '5000000')); ?>">0</div><div class="about-stat-label"><?php echo e(getSetting('home_stat_units_label', 'Units Produced Annually')); ?></div></div>
                    <div class="about-stat"><div class="about-stat-number" data-counter="<?php echo e(getSetting('home_stat_clients_number', '2500')); ?>">0</div><div class="about-stat-label"><?php echo e(getSetting('home_stat_clients_label', 'USA Clients Served')); ?></div></div>
                    <div class="about-stat"><div class="about-stat-number" data-counter="<?php echo e(getSetting('home_stat_states_number', '50')); ?>">0</div><div class="about-stat-label"><?php echo e(getSetting('home_stat_states_label', 'States Served')); ?></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('whychoose', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('whychoose')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('whychoose', 'subtitle', 'home_whychoose_label', 'Why Custom Streetwear')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('whychoose', 'title', 'home_whychoose_title', 'Trusted by Industry Leaders Across America')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('whychoose', 'description', 'home_whychoose_desc', 'Preferred by Fortune 500 companies, professional sports teams, and thousands of businesses nationwide.')); ?></p>
        </div>
        <?php echo renderTrustSignals(); ?>
        <div class="why-choose-grid">
            <div class="glass-card reveal"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card1_title', 'Trusted by Millions')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card1_text', 'Preferred by millions across America, delivering quality, performance, and reliability.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.1s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card2_title', 'Uncompromised Quality')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card2_text', 'Every garment passes a 12-point quality inspection for durability and comfort.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.2s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card3_title', 'Competitive Pricing')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card3_text', 'Factory-direct pricing on premium sportswear and custom uniforms.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.3s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card4_title', 'On-Time Delivery')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card4_text', '98% on-time delivery record across all 50 states.')); ?></p></div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('featured', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('featured', 'background:var(--color-bg-alt);')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('featured', 'subtitle', 'home_featured_label', 'Featured Items')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('featured', 'title', 'home_featured_title', 'Featured Products')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('featured', 'description', 'home_featured_desc', 'Handpicked custom apparel products showcasing our manufacturing excellence.')); ?></p>
        </div>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;--card-index:<?php echo $index; ?>;">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        <button class="product-card-action" onclick="openQuoteModal()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></button>
                    </div>
                    <?php if ($product['is_featured']): ?><span class="product-card-badge">Featured</span><?php endif; ?>
                </div>
                <div class="product-card-info">
                    <span class="product-card-category"><?php echo e($product['category_name']); ?></span>
                    <h3 class="product-card-title"><?php echo e($product['title']); ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('tech', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('tech')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('tech', 'subtitle', 'home_tech_label', 'Manufacturing Excellence')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('tech', 'title', 'home_tech_title', 'Performance-Driven Technology')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('tech', 'description', 'home_tech_desc', 'Cutting-edge apparel technology ensuring comfort, durability, and peak performance.')); ?></p>
        </div>
        <div class="tech-grid">
            <div class="glass-card reveal" style="padding:30px 20px;"><div class="glass-card-icon" style="width:56px;height:56px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_tech_card1_title', 'Sublimation')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_tech_card1_text', 'Vibrant, durable prints that won\'t fade, crack, or peel.')); ?></p></div>
            <div class="glass-card reveal" style="padding:30px 20px;transition-delay:0.08s;"><div class="glass-card-icon" style="width:56px;height:56px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_tech_card2_title', 'Cut & Sew')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_tech_card2_text', 'Precision craftsmanship for impeccable fit and durability.')); ?></p></div>
            <div class="glass-card reveal" style="padding:30px 20px;transition-delay:0.16s;"><div class="glass-card-icon" style="width:56px;height:56px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_tech_card3_title', 'Screen Printing')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_tech_card3_text', 'High-quality, long-lasting prints on every garment.')); ?></p></div>
            <div class="glass-card reveal" style="padding:30px 20px;transition-delay:0.24s;"><div class="glass-card-icon" style="width:56px;height:56px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('home_tech_card4_title', 'Embroidery')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('home_tech_card4_text', 'Professional custom embroidery for a premium finish.')); ?></p></div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('videos', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('videos', 'background:var(--color-bg-alt);')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('videos', 'subtitle', 'home_video_label', 'Behind The Scenes')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('videos', 'title', 'home_video_title', 'Factory & Product Videos')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('videos', 'description', 'home_video_desc', 'See the craftsmanship that goes into every garment.')); ?></p>
        </div>
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
            <div class="video-card reveal" onclick="window.open('<?php echo e($video['video_url']); ?>', '_blank')">
                <img src="<?php echo e($video['thumbnail'] ?: '/uploads/sliders/slider-1.jpg'); ?>" alt="<?php echo e($video['title']); ?>" loading="lazy">
                <div class="video-card-overlay"><div class="video-play-btn"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg></div></div>
                <div class="video-card-title"><?php echo e($video['title']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('brochures', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('brochures')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('brochures', 'subtitle', 'home_brochure_label', 'Download')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('brochures', 'title', 'home_brochure_title', 'Our Brochures')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('brochures', 'description', 'home_brochure_desc', 'Download our product catalogs to discover our complete range.')); ?></p>
        </div>
        <div class="brochure-grid">
            <?php foreach ($brochures as $index => $brochure): ?>
            <div class="brochure-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;">
                <div class="brochure-icon"><svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
                <h3 class="brochure-title"><?php echo e($brochure['title']); ?></h3>
                <p class="brochure-desc"><?php echo e(truncate($brochure['description'] ?? '', 80)); ?></p>
                <?php if ($brochure['file_path']): ?><a href="<?php echo e($brochure['file_path']); ?>" target="_blank" class="btn btn-outline btn-sm">Download</a><?php else: ?><button class="btn btn-outline btn-sm" onclick="openQuoteModal()">Request Catalog</button><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('testimonials', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('testimonials', 'background:var(--color-bg-alt);')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('testimonials', 'subtitle', 'home_testimonial_label', 'Testimonials')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('testimonials', 'title', 'home_testimonial_title', 'What Our Clients Say')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('testimonials', 'description', 'home_testimonial_desc', 'Trusted by brands, teams, and businesses nationwide.')); ?></p>
        </div>
        <div class="testimonial-grid">
            <?php foreach ($testimonials as $index => $t): ?>
            <div class="testimonial-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;">
                <p class="testimonial-quote">"<?php echo e(truncate($t['message'], 200)); ?>"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar"><?php echo e(substr($t['client_name'], 0, 1)); ?></div>
                    <div>
                        <div class="testimonial-name"><?php echo e($t['client_name']); ?></div>
                        <div class="testimonial-company"><?php echo e($t['company']); ?> - <?php echo e($t['country']); ?></div>
                        <div class="testimonial-rating">
                            <?php for ($i = 0; $i < ($t['rating'] ?? 5); $i++): ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#ffaa00"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('locations', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('locations')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('locations', 'subtitle', 'home_locations_label', 'USA Coverage')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('locations', 'title', 'home_locations_title', 'Custom Apparel Manufacturer in USA')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('locations', 'description', 'home_locations_desc', 'We serve all 50 states with premium custom apparel manufacturing.')); ?></p>
        </div>
        <div class="country-grid">
            <?php $idx = 0; foreach ($usaStates as $slug => $state): ?>
            <a href="/locations/<?php echo e($slug); ?>" class="country-card reveal" style="transition-delay:<?php echo $idx * 0.1; ?>s;--card-index:<?php echo $idx; ?>;">
                <div class="country-flag" style="width:48px;height:48px;font-size:20px;"><?php echo getStateFlag(); ?></div>
                <h3 class="country-name"><?php echo e($state['name']); ?></h3>
                <span style="font-size:12px;color:var(--color-accent);">View Cities →</span>
            </a>
            <?php $idx++; endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:50px;"><a href="/locations" class="btn btn-primary btn-lg">View All States</a></div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('blogs', $sectionKeys)): ?>
<section class="section" style="<?php echo e($sectionStyle('blogs', 'background:var(--color-bg-alt);')); ?>">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e($sectionText('blogs', 'subtitle', 'home_blog_label', 'Latest News')); ?></span>
            <h2 class="section-title"><?php echo e($sectionText('blogs', 'title', 'home_blog_title', 'From Our Blog')); ?></h2>
            <p class="section-desc"><?php echo e($sectionText('blogs', 'description', 'home_blog_desc', 'Stay updated with the latest trends and insights.')); ?></p>
        </div>
        <div class="blog-grid">
            <?php foreach ($blogs as $index => $blog): ?>
            <article class="blog-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;--card-index:<?php echo $index; ?>;">
                <div class="blog-card-image"><img src="<?php echo e($blog['image'] ?: '/uploads/blogs/blog-1.jpg'); ?>" alt="<?php echo e($blog['alt_text'] ?: $blog['title']); ?>" loading="lazy"></div>
                <div class="blog-card-content">
                    <div class="blog-card-meta"><span class="blog-card-category"><?php echo e($blog['category'] ?: 'News'); ?></span><span><?php echo formatDate($blog['published_at'] ?: $blog['created_at']); ?></span></div>
                    <h3 class="blog-card-title"><a href="/blog/<?php echo e($blog['slug']); ?>"><?php echo e(truncate($blog['title'], 70)); ?></a></h3>
                    <p class="blog-card-excerpt"><?php echo e(truncate(strip_tags($blog['short_description'] ?: $blog['content']), 120)); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (in_array('cta', $sectionKeys)): ?>
<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="cta-section-accent"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e($sectionText('cta', 'subtitle', 'home_cta_label', 'Get Started')); ?></span>
            <h2 class="cta-section-title"><?php echo e($sectionText('cta', 'title', 'home_cta_title', 'Ready to Create Your Custom Apparel?')); ?></h2>
            <p class="cta-section-desc"><?php echo e($sectionText('cta', 'description', 'home_cta_desc', 'Request a free quote today. Factory-direct pricing, fast delivery across all 50 states.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('home_cta_btn1_text', 'Request a Quote')); ?></button>
                <a href="<?php echo e(getSetting('home_cta_btn2_link', '/contact')); ?>" class="btn btn-outline btn-lg"><?php echo e(getSetting('home_cta_btn2_text', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php foreach ($homeSections as $section): ?>
    <?php if (!empty($section['custom_html'])): ?>
    <section class="section home-custom-section home-custom-section-<?php echo e($section['section_key']); ?>">
        <div class="container"><?php echo $section['custom_html']; ?></div>
    </section>
    <?php endif; ?>
<?php endforeach; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
