<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$siteName = getSetting('site_name', 'Custom Streetwear');
$targetKeyword = getSetting('home_sports_uniforms_target', 'Custom Sports Uniforms Manufacturer in USA');
$description = getSetting('home_sports_uniforms_desc', 'Premium custom sports uniforms manufacturer serving teams, schools, and clubs across the USA. Factory-direct pricing, custom designs, fast delivery.');

$categories = dbFetchAll("SELECT * FROM categories WHERE status = 1 ORDER BY sort_order");
$testimonials = dbFetchAll("SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order LIMIT 6");
$products = getProducts([], 8);

$metaTags = generateAdvancedMetaTags([
    'meta_title' => $targetKeyword,
    'meta_description' => $description,
    'focus_keyword' => $targetKeyword,
    'og_type' => 'website'
]);
$sportsFaqs = [
    [
        'question' => 'Do you manufacture custom sports uniforms for teams in the USA?',
        'answer' => 'Yes. Custom Streetwear manufactures custom sports uniforms for teams, schools, clubs, leagues, and brands across all 50 U.S. states.'
    ],
    [
        'question' => 'What customization methods are available for sports uniforms?',
        'answer' => 'Available methods include sublimation, screen printing, embroidery, applique, tackle twill, private labels, custom sizing, and full cut-and-sew production.'
    ],
    [
        'question' => 'How long does a custom sports uniform order take?',
        'answer' => 'Standard production usually ships within 15 to 20 business days after artwork, sizing, and order details are approved. Rush options can be discussed during quoting.'
    ],
    [
        'question' => 'Can you handle bulk sports uniform orders?',
        'answer' => 'Yes. The manufacturing workflow is built for team, league, school, corporate, and reseller bulk orders with factory-direct pricing.'
    ]
];
$extraHead = schemaScript(apparelServiceSchema($targetKeyword, $description, SITE_URL . '/sports-uniforms'));
$extraHead .= schemaScript(faqSchemaFromRows($sportsFaqs, 'Custom Sports Uniforms Manufacturer in USA FAQ'));

include __DIR__ . '/../includes/header.php';
?>

<!-- Sports Hero Section -->
<section class="sports-hero">
    <div class="sports-hero-bg">
        <div class="sports-hero-particles" id="sportsParticles"></div>
    </div>
    <div class="container">
        <div class="sports-hero-content">
            <div class="urgency-bar" style="margin:0 auto 20px;">
                <span class="urgency-dot"></span>
                <span><?php echo e(getSetting('home_urgent_badge_text', 'Factory Direct | Bulk Orders | Ships in 15-20 Days')); ?></span>
            </div>
            <h1><?php echo e($targetKeyword); ?></h1>
            <p><?php echo e($description); ?></p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Get a Quote</button>
                <a href="#sports-types" class="btn btn-outline btn-lg">Explore Sports</a>
            </div>
            <div style="margin-top:24px;display:flex;justify-content:center;gap:32px;flex-wrap:wrap;">
                <div><strong style="font-size:24px;color:var(--color-accent);">50+</strong><br><span style="font-size:13px;color:var(--color-text-muted);">Sports Types</span></div>
                <div><strong style="font-size:24px;color:var(--color-accent);">2500+</strong><br><span style="font-size:13px;color:var(--color-text-muted);">Teams Served</span></div>
                <div><strong style="font-size:24px;color:var(--color-accent);">50</strong><br><span style="font-size:13px;color:var(--color-text-muted);">States Served</span></div>
                <div><strong style="font-size:24px;color:var(--color-accent);">98%</strong><br><span style="font-size:13px;color:var(--color-text-muted);">On-Time Delivery</span></div>
            </div>
        </div>
    </div>
</section>

<!-- Sports Types -->
<section class="section" id="sports-types" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Sports We Cover</span>
            <h2 class="section-title">Complete Range of Sports Uniforms</h2>
            <p class="section-desc">From football to basketball, baseball to soccer — we manufacture premium custom uniforms for every sport.</p>
        </div>
        <div class="sports-types reveal-stagger">
            <?php
            $sports = [
                ['Football', '🏈', 'Custom football jerseys, pants, and practice gear for teams of all levels.'],
                ['Basketball', '🏀', 'Performance basketball uniforms with moisture-wicking fabric.'],
                ['Baseball', '⚾', 'Professional baseball jerseys, pants, and caps with custom designs.'],
                ['Soccer', '⚽', 'Custom soccer kits with sublimation printing and team crests.'],
                ['Rugby', '🏉', 'Durable rugby jerseys built for the toughest matches.'],
                ['Volleyball', '🏐', 'Lightweight volleyball uniforms with maximum mobility.'],
                ['Hockey', '🏒', 'Custom hockey jerseys with tackle twill lettering.'],
                ['Lacrosse', '🥍', 'Performance lacrosse gear for men and women teams.'],
                ['Wrestling', '🤼', 'Custom wrestling singlets with team colors and designs.'],
                ['Track & Field', '🏃', 'Lightweight track uniforms for competitive athletes.'],
                ['Softball', '🥎', 'Custom softball jerseys and uniforms for all divisions.'],
                ['Esports', '🎮', 'Premium esports jerseys with custom branding and patterns.'],
            ];
            foreach ($sports as $i => $s): ?>
            <div class="sports-type-card reveal" style="transition-delay:<?php echo $i * 0.05; ?>s;">
                <div class="sports-type-icon"><?php echo $s[1]; ?></div>
                <div class="sports-type-name"><?php echo $s[0]; ?></div>
                <p style="font-size:12px;color:var(--color-text-muted);margin-top:8px;"><?php echo $s[2]; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us for Sports -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Why Teams Choose Us</span>
            <h2 class="section-title">America's Trusted Sports Uniform Manufacturer</h2>
            <p class="section-desc">We combine decades of manufacturing expertise with cutting-edge technology to deliver uniforms that perform.</p>
        </div>
        <div class="why-choose-grid" style="--grid-min:260px;">
            <div class="glass-card reveal">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h3 class="glass-card-title">Premium Materials</h3>
                <p class="glass-card-text">100% polyester performance fabrics, moisture-wicking, breathable, and durable for the toughest games.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.1s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>
                <h3 class="glass-card-title">Custom Designs</h3>
                <p class="glass-card-text">Full sublimation, screen printing, and embroidery. Any color, any pattern, any logo — we bring your vision to life.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.2s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                <h3 class="glass-card-title">Factory Direct</h3>
                <p class="glass-card-text">Cut out the middleman. Get premium quality at factory-direct prices. No minimum quantity requirements.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.3s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <h3 class="glass-card-title">Fast Turnaround</h3>
                <p class="glass-card-text">15-20 business days standard production. Rush orders available. 98% on-time delivery record across all 50 states.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Our Products</span>
            <h2 class="section-title">Sports Uniform Collection</h2>
            <p class="section-desc">Browse our range of custom sports uniforms and apparel.</p>
        </div>
        <div class="product-grid">
            <?php foreach ($products as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        <button class="product-card-action" onclick="openQuoteModal()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></button>
                    </div>
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

<!-- Sports FAQ -->
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Team Ordering FAQ</span>
            <h2 class="section-title">Custom Sports Uniform Questions</h2>
            <p class="section-desc">Clear answers for USA teams, schools, leagues, and apparel buyers comparing custom uniform manufacturers.</p>
        </div>
        <div class="faq-section reveal" style="max-width:900px;margin:0 auto;">
            <?php foreach ($sportsFaqs as $faq): ?>
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
            <span class="section-label">Get Started</span>
            <h2 class="cta-section-title">Ready to Outfit Your Team?</h2>
            <p class="cta-section-desc">Get a free quote for your custom sports uniforms. No minimum quantity, factory-direct pricing, and fast delivery to all 50 states.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<style>
.sports-types { grid-template-columns: repeat(auto-fill, minmax(var(--grid-min, 160px), 1fr)); }
.why-choose-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(var(--grid-min, 260px), 1fr)); gap: 20px; }
.sports-hero .urgency-bar { display: inline-flex; justify-content: center; }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
