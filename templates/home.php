<?php
require_once __DIR__ . '/../includes/functions.php';
$sliders = dbFetchAll("SELECT * FROM sliders WHERE status = 1 ORDER BY sort_order");
$categories = getCategories();
$bestSellers = getProducts(['best_seller' => true], intval(getSetting('home_bestseller_count', '8')));
$featuredProducts = getProducts(['featured' => true], intval(getSetting('home_featured_count', '8')));
$testimonials = dbFetchAll("SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order LIMIT " . intval(getSetting('home_testimonial_count', '6')));
$blogs = dbFetchAll("SELECT * FROM blogs WHERE status = 1 ORDER BY published_at DESC LIMIT " . intval(getSetting('home_blog_count', '4')));
$videos = dbFetchAll("SELECT * FROM videos WHERE status = 1 ORDER BY sort_order LIMIT " . intval(getSetting('home_video_count', '3')));
$brochures = dbFetchAll("SELECT * FROM brochures WHERE status = 1 ORDER BY sort_order LIMIT " . intval(getSetting('home_brochure_count', '8')));
$usaStates = array_slice(getUSAStates(), 0, 8);
$metaTags = generateMetaTags();
include __DIR__ . '/../includes/header.php';
?>

<!-- Hero Slider with 3D Background -->
<section class="hero-slider" id="heroSlider">
    <!-- 3D Canvas Background -->
    <div id="hero3d-canvas" style="position:absolute;inset:0;z-index:0;pointer-events:none;"></div>
    
    <?php foreach ($sliders as $index => $slide): ?>
    <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
        <div class="hero-slide-bg" style="background-image: url('<?php echo e($slide['image']); ?>')"></div>
        <div class="hero-slide-overlay" style="background: linear-gradient(135deg, rgba(5,5,5,0.85) 0%, rgba(5,5,5,0.4) 100%);"></div>
        <div class="container">
            <div class="hero-slide-content">
                <span class="hero-label" style="display:inline-block;animation:fadeInUp 0.6s ease both;"><?php echo e($slide['subtitle']); ?></span>
                <h1 class="hero-title" style="animation:fadeInUp 0.6s ease 0.1s both;"><?php echo nl2br(e($slide['title'])); ?></h1>
                <p class="hero-desc" style="animation:fadeInUp 0.6s ease 0.2s both;"><?php echo e($slide['description']); ?></p>
                <div class="hero-buttons" style="animation:fadeInUp 0.6s ease 0.3s both;">
                    <a href="<?php echo e($slide['button_link']); ?>" class="btn btn-primary btn-lg"><?php echo e($slide['button_text']); ?></a>
                    <a href="/products" class="btn btn-outline btn-lg">Explore Products</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="hero-controls">
        <button class="hero-control" onclick="prevSlide(); resetAutoSlide();" aria-label="Previous slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="hero-control" onclick="nextSlide(); resetAutoSlide();" aria-label="Next slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    </div>
    <div class="hero-dots">
        <?php foreach ($sliders as $index => $slide): ?>
        <div class="hero-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Three.js for 3D Background -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="/assets/js/3d-background.js?v=<?php echo filemtime(CSW_ROOT . '/assets/js/3d-background.js'); ?>"></script>

<!-- Product Categories -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_categories_label', 'Our Collection')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_categories_title', 'Product Categories')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_categories_desc', 'Explore our wide range of custom apparel categories. From streetwear to sportswear, workwear to leather goods.')); ?></p>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $index => $cat): ?>
            <a href="/category/<?php echo e($cat['slug']); ?>" class="category-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="category-card-image" style="background-image: url('<?php echo e($cat['image'] ?: '/uploads/categories/' . $cat['slug'] . '.jpg'); ?>')"></div>
                <div class="category-card-overlay"></div>
                <div class="category-card-content">
                    <h3 class="category-card-title"><?php echo e($cat['name']); ?></h3>
                    <span class="category-card-count">View Products</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Best Sellers -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_bestseller_label', 'Most Popular')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_bestseller_title', 'Best Seller Products')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_bestseller_desc', 'Our most popular custom apparel products trusted by brands and teams worldwide.')); ?></p>
        </div>
        <div class="product-grid">
            <?php foreach ($bestSellers as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action" title="View Details">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <button class="product-card-action" title="Request Quote" onclick="openQuoteModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </button>
                    </div>
                    <?php if ($product['is_best_seller']): ?>
                    <span class="product-card-badge">Best Seller</span>
                    <?php endif; ?>
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

<!-- About Section -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="about-grid">
            <div class="about-image reveal">
                <img src="<?php echo e(getSetting('home_about_image', '/uploads/pages/about-factory.jpg')); ?>" alt="Custom Streetwear Manufacturing Facility" loading="lazy">
            </div>
            <div class="about-content reveal">
                <span class="section-label"><?php echo e(getSetting('home_about_label', 'About Us')); ?></span>
                <h2><?php echo e(getSetting('home_about_title', "America's Trusted Custom Apparel Manufacturer Since 2012")); ?></h2>
                <p><?php echo e(getSetting('home_about_text1', 'Custom Streetwear is a premier manufacturer of custom sportswear, streetwear, workwear, uniforms, and leather garments. Since 2012, we have been at the forefront of the apparel manufacturing industry, serving Fortune 500 brands, professional sports teams, major retailers, and government institutions across the United States.')); ?></p>
                <p><?php echo nl2br(e(getSetting('home_about_text2', 'Our state-of-the-art manufacturing facility spans 150,000 square feet with a production capacity of over 50,000 units per day. We combine cutting-edge technology with generations of craftsmanship to deliver products that meet the most demanding quality standards.'))); ?></p>
                <a href="<?php echo e(getSetting('home_about_link', '/about-us')); ?>" class="btn btn-primary"><?php echo e(getSetting('home_about_link_text', 'Learn More About Us')); ?></a>
                <div class="about-stats">
                    <div class="about-stat">
                        <div class="about-stat-number" data-counter="<?php echo e(getSetting('home_stat_units_number', '5000000')); ?>">0</div>
                        <div class="about-stat-label"><?php echo e(getSetting('home_stat_units_label', 'Units Produced Annually')); ?></div>
                    </div>
                    <div class="about-stat">
                        <div class="about-stat-number" data-counter="<?php echo e(getSetting('home_stat_clients_number', '2500')); ?>">0</div>
                        <div class="about-stat-label"><?php echo e(getSetting('home_stat_clients_label', 'USA Clients Served')); ?></div>
                    </div>
                    <div class="about-stat">
                        <div class="about-stat-number" data-counter="<?php echo e(getSetting('home_stat_states_number', '50')); ?>">0</div>
                        <div class="about-stat-label"><?php echo e(getSetting('home_stat_states_label', 'States Served')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_whychoose_label', 'Why Custom Streetwear')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_whychoose_title', 'Trusted by Industry Leaders Across America')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_whychoose_desc', 'Preferred by Fortune 500 companies, professional sports teams, and thousands of businesses nationwide. Here is why they choose us.')); ?></p>
        </div>
        <div class="stats-grid" style="margin-bottom: 60px;">
            <div class="stat-item reveal">
                <div class="stat-number" data-counter="<?php echo e(getSetting('home_whychoose_stat1_number', '50000000')); ?>">0</div>
                <div class="stat-label"><?php echo e(getSetting('home_whychoose_stat1_label', 'Total Units Produced')); ?></div>
            </div>
            <div class="stat-item reveal" style="transition-delay: 0.1s">
                <div class="stat-number" data-counter="<?php echo e(getSetting('home_whychoose_stat2_number', '2500')); ?>">0</div>
                <div class="stat-label"><?php echo e(getSetting('home_whychoose_stat2_label', 'USA Clients Served')); ?></div>
            </div>
            <div class="stat-item reveal" style="transition-delay: 0.2s">
                <div class="stat-number" data-counter="<?php echo e(getSetting('home_whychoose_stat3_number', '50')); ?>">0</div>
                <div class="stat-label"><?php echo e(getSetting('home_whychoose_stat3_label', 'States Served')); ?></div>
            </div>
            <div class="stat-item reveal" style="transition-delay: 0.3s">
                <div class="stat-number" data-counter="<?php echo e(getSetting('home_whychoose_stat4_number', '13')); ?>">0</div>
                <div class="stat-label"><?php echo e(getSetting('home_whychoose_stat4_label', 'Years of Excellence')); ?></div>
            </div>
        </div>
        <div class="why-choose-grid">
            <div class="glass-card reveal">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card1_title', 'Trusted by Millions')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card1_text', 'Preferred by millions across America, delivering exceptional quality, performance, and reliability that businesses, teams, and organizations can count on every single day.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.1s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </div>
                <h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card2_title', 'Uncompromised Quality')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card2_text', 'Experience top-tier craftsmanship with our premium products, designed for durability, comfort, and performance. Every garment passes 12-point quality inspection.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.2s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card3_title', 'Competitive Pricing')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card3_text', 'Get the best value with factory-direct pricing on premium sportswear and custom uniforms. Quality, performance, and affordability combined in every order.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.3s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <h3 class="glass-card-title"><?php echo e(getSetting('home_whychoose_card4_title', 'On-Time Delivery')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('home_whychoose_card4_text', 'Reliable, efficient, and punctual delivery ensures your products arrive when you need them, every time, without compromise. 98% on-time delivery record.')); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_featured_label', 'Featured Items')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_featured_title', 'Featured Products')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_featured_desc', 'Handpicked custom apparel products showcasing our manufacturing excellence.')); ?></p>
        </div>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action" title="View Details">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <button class="product-card-action" title="Request Quote" onclick="openQuoteModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </button>
                    </div>
                    <?php if ($product['is_featured']): ?>
                    <span class="product-card-badge">Featured</span>
                    <?php endif; ?>
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

<!-- Performance-Driven Technology -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_tech_label', 'Manufacturing Excellence')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_tech_title', 'Performance-Driven Technology')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_tech_desc', 'We use cutting-edge apparel technology to ensure comfort, durability, and peak performance. From moisture-wicking fabrics to precision stitching and digital printing, every piece is engineered with innovation.')); ?></p>
        </div>
        <div class="tech-grid">
            <div class="glass-card reveal" style="padding: 30px 20px;">
                <div class="glass-card-icon" style="width: 56px; height: 56px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <h3 class="glass-card-title" style="font-size: 15px;"><?php echo e(getSetting('home_tech_card1_title', 'Sublimation')); ?></h3>
                <p class="glass-card-text" style="font-size: 13px;"><?php echo e(getSetting('home_tech_card1_text', 'Transform your designs into vibrant, durable prints that won\'t fade, crack, or peel over time.')); ?></p>
            </div>
            <div class="glass-card reveal" style="padding: 30px 20px; transition-delay: 0.08s;">
                <div class="glass-card-icon" style="width: 56px; height: 56px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <h3 class="glass-card-title" style="font-size: 15px;"><?php echo e(getSetting('home_tech_card2_title', 'Cut & Sew')); ?></h3>
                <p class="glass-card-text" style="font-size: 13px;"><?php echo e(getSetting('home_tech_card2_text', 'Precision Cut & Sew techniques, crafting custom apparel with impeccable fit, style, and durability.')); ?></p>
            </div>
            <div class="glass-card reveal" style="padding: 30px 20px; transition-delay: 0.16s;">
                <div class="glass-card-icon" style="width: 56px; height: 56px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3 class="glass-card-title" style="font-size: 15px;"><?php echo e(getSetting('home_tech_card3_title', 'Screen Printing')); ?></h3>
                <p class="glass-card-text" style="font-size: 13px;"><?php echo e(getSetting('home_tech_card3_text', 'High-quality screen printing services delivering vibrant, long-lasting prints on every garment.')); ?></p>
            </div>
            <div class="glass-card reveal" style="padding: 30px 20px; transition-delay: 0.24s;">
                <div class="glass-card-icon" style="width: 56px; height: 56px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                </div>
                <h3 class="glass-card-title" style="font-size: 15px;"><?php echo e(getSetting('home_tech_card4_title', 'Embroidery')); ?></h3>
                <p class="glass-card-text" style="font-size: 13px;"><?php echo e(getSetting('home_tech_card4_text', 'Elevate your apparel with custom embroidery and applique, adding professional designs to every piece.')); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Factory Videos -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_video_label', 'Behind The Scenes')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_video_title', 'Factory & Product Videos')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_video_desc', 'Explore our manufacturing process and see the craftsmanship that goes into every garment.')); ?></p>
        </div>
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
            <div class="video-card reveal" onclick="window.open('<?php echo e($video['video_url']); ?>', '_blank')">
                <img src="<?php echo e($video['thumbnail'] ?: '/uploads/sliders/slider-1.jpg'); ?>" alt="<?php echo e($video['title']); ?>" loading="lazy">
                <div class="video-card-overlay">
                    <div class="video-play-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                </div>
                <div class="video-card-title"><?php echo e($video['title']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo e(getSetting('youtube_url', '#')); ?>" target="_blank" class="btn btn-outline" rel="noopener">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                View More Videos
            </a>
        </div>
    </div>
</section>

<!-- Brochures -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_brochure_label', 'Download')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_brochure_title', 'Our Brochures')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_brochure_desc', 'Download our product catalogs to discover our complete range of custom apparel.')); ?></p>
        </div>
        <div class="brochure-grid">
            <?php foreach ($brochures as $index => $brochure): ?>
            <div class="brochure-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="brochure-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h3 class="brochure-title"><?php echo e($brochure['title']); ?></h3>
                <p class="brochure-desc"><?php echo e(truncate($brochure['description'] ?? '', 80)); ?></p>
                <?php if ($brochure['file_path']): ?>
                <a href="<?php echo e($brochure['file_path']); ?>" target="_blank" class="btn btn-outline btn-sm">Download</a>
                <?php else: ?>
                <button class="btn btn-outline btn-sm" onclick="openQuoteModal()">Request Catalog</button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_testimonial_label', 'Testimonials')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_testimonial_title', 'What Our Clients Say')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_testimonial_desc', 'Trusted by brands, teams, and businesses worldwide. Here is what they have to say about us.')); ?></p>
        </div>
        <div class="testimonial-grid">
            <?php foreach ($testimonials as $index => $t): ?>
            <div class="testimonial-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <p class="testimonial-quote">"<?php echo e(truncate($t['message'], 200)); ?>"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar"><?php echo e(substr($t['client_name'], 0, 1)); ?></div>
                    <div>
                        <div class="testimonial-name"><?php echo e($t['client_name']); ?></div>
                        <div class="testimonial-company"><?php echo e($t['company']); ?> - <?php echo e($t['country']); ?></div>
                        <div class="testimonial-rating">
                            <?php for ($i = 0; $i < ($t['rating'] ?? 5); $i++): ?>
                            <svg width="14" height="14" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- USA Locations -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_locations_label', 'USA Coverage')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_locations_title', 'Custom Apparel Manufacturer in USA')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_locations_desc', 'We serve all 50 states with premium custom apparel manufacturing. Factory-direct pricing, nationwide delivery.')); ?></p>
        </div>
        <div class="country-grid">
            <?php $idx = 0; foreach ($usaStates as $slug => $state): ?>
            <a href="/locations/<?php echo e($slug); ?>" class="country-card reveal" style="transition-delay: <?php echo $idx * 0.1; ?>s; padding: 20px;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;"><?php echo getStateFlag(); ?></div>
                <h3 class="country-name" style="font-size: 16px;"><?php echo e($state['name']); ?></h3>
                <span style="font-size: 12px; color: var(--color-accent);">View Cities →</span>
            </a>
            <?php $idx++; endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 50px;">
            <a href="/locations" class="btn btn-primary btn-lg">View All States</a>
        </div>
    </div>
</section>

<!-- Latest Blogs -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('home_blog_label', 'Latest News')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('home_blog_title', 'From Our Blog')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('home_blog_desc', 'Stay updated with the latest trends, insights, and news from the apparel industry.')); ?></p>
        </div>
        <div class="blog-grid">
            <?php foreach ($blogs as $index => $blog): ?>
            <article class="blog-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="blog-card-image">
                    <img src="<?php echo e($blog['image'] ?: '/uploads/blogs/blog-1.jpg'); ?>" alt="<?php echo e($blog['alt_text'] ?: $blog['title']); ?>" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span class="blog-card-category"><?php echo e($blog['category'] ?: 'News'); ?></span>
                        <span><?php echo formatDate($blog['published_at'] ?: $blog['created_at']); ?></span>
                    </div>
                    <h3 class="blog-card-title">
                        <a href="/blog/<?php echo e($blog['slug']); ?>"><?php echo e(truncate($blog['title'], 70)); ?></a>
                    </h3>
                    <p class="blog-card-excerpt"><?php echo e(truncate(strip_tags($blog['short_description'] ?: $blog['content']), 120)); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="cta-section-accent"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('home_cta_label', 'Get Started')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('home_cta_title', 'Ready to Create Your Custom Apparel?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('home_cta_desc', 'Whether you need custom sportswear, streetwear, workwear, or uniforms, we are here to bring your vision to life. Request a free quote today.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('home_cta_btn1_text', 'Request a Quote')); ?></button>
                <a href="<?php echo e(getSetting('home_cta_btn2_link', '/contact')); ?>" class="btn btn-outline btn-lg"><?php echo e(getSetting('home_cta_btn2_text', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
