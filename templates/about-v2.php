<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$sitePhone = getSetting('site_phone', '+1 (555) 123-4567');
$siteEmail = getSetting('site_email', 'sales@customstreetwear.co');
$siteAddress = getSetting('site_address', 'Los Angeles, CA, USA');

$metaTags = generateAdvancedMetaTags([
    'meta_title' => getSetting('about_meta_title', 'About Us - Custom Apparel Manufacturer in USA | Custom Streetwear'),
    'meta_description' => getSetting('about_meta_desc', 'Custom Streetwear is America\'s trusted custom apparel manufacturer. Premium sportswear, streetwear, workwear & uniforms. Factory-direct since 2012. 2500+ USA clients served.'),
    'focus_keyword' => 'Custom Apparel Manufacturer in USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-50%;left:-20%;width:500px;height:500px;background:radial-gradient(circle,rgba(57,255,20,0.04),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'About Us']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('about_page_label', 'About Custom Streetwear')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('about_page_title', "America's Trusted Custom Apparel Manufacturer")); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:720px;line-height:1.6;"><?php echo e(getSetting('about_page_desc', 'Custom Streetwear is a premier custom apparel manufacturer serving the United States. Since 2012, we have been delivering premium-quality sportswear, streetwear, workwear, uniforms, and leather garments to brands, teams, and businesses nationwide.')); ?></p>
        </div>
    </div>
</section>

<?php if (getSetting('about_trust_bar_enabled', '1') === '1'): ?>
<section style="border-bottom:1px solid var(--color-border);">
    <div class="trust-bar">
        <div class="container">
            <div class="trust-bar-inner">
                <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Trusted by 2500+ USA Brands</span>
                <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Ships Within 15-20 Days</span>
                <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>100% Quality Guaranteed</span>
                <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Factory-Direct Pricing</span>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Stats -->
<section class="section" style="padding:40px 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;">
            <div class="reveal stat-item"><div class="stat-number" data-counter="<?php echo e(getSetting('home_stat_units_number', '5000000')); ?>">0</div><div class="stat-label"><?php echo e(getSetting('home_stat_units_label', 'Units Produced Annually')); ?></div></div>
            <div class="reveal stat-item" style="transition-delay:0.1s;"><div class="stat-number" data-counter="<?php echo e(getSetting('home_stat_clients_number', '2500')); ?>">0</div><div class="stat-label"><?php echo e(getSetting('home_stat_clients_label', 'USA Clients Served')); ?></div></div>
            <div class="reveal stat-item" style="transition-delay:0.2s;"><div class="stat-number" data-counter="<?php echo e(getSetting('home_stat_states_number', '50')); ?>">0</div><div class="stat-label"><?php echo e(getSetting('home_stat_states_label', 'States Served')); ?></div></div>
            <div class="reveal stat-item" style="transition-delay:0.3s;"><div class="stat-number" data-counter="<?php echo e(getSetting('about_stat_years', '12')); ?>">0</div><div class="stat-label"><?php echo e(getSetting('about_stat_years_label', 'Years of Excellence')); ?></div></div>
        </div>
    </div>
</section>

<!-- Story -->
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
            <div class="reveal">
                <h2 style="font-family:var(--font-display);font-size:20px;text-transform:uppercase;margin-bottom:16px;"><?php echo e(getSetting('about_story_title', 'Our Story')); ?></h2>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('about_story_text1', 'Founded with a vision to revolutionize custom apparel manufacturing in the United States, Custom Streetwear has grown from a small workshop into a state-of-the-art manufacturing facility spanning 150,000 square feet. Our journey began with a simple mission: provide American businesses, sports teams, and brands with factory-direct custom apparel without compromising on quality.')); ?></p>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('about_story_text2', "Today, we produce over 5 million garments annually and serve more than 2,500 clients across all 50 states. From professional sports teams and Fortune 500 companies to small businesses and startups, we partner with organizations of every size to bring their custom apparel visions to life.")); ?></p>
                <p style="color:var(--color-text-muted);line-height:1.8;"><?php echo e(getSetting('about_story_text3', 'Our manufacturing capabilities span sublimation, cut & sew, screen printing, embroidery, and leather craftsmanship — all under one roof, ensuring consistent quality and faster turnaround times.')); ?></p>
            </div>
            <div class="reveal" style="transition-delay:0.2s;">
                <img src="<?php echo e(getSetting('about_story_image', '/uploads/pages/about-factory.jpg')); ?>" alt="Custom Streetwear Manufacturing Facility" style="width:100%;border-radius:var(--radius-lg);border:1px solid var(--color-border);">
            </div>
        </div>
    </div>
</section>

<!-- Trust Signals -->
<?php if (getSetting('about_trust_signals_enabled', '1') === '1'): ?>
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('about_trust_label', 'Why Choose Us')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('about_trust_title', 'Trusted by Industry Leaders Across America')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('about_trust_desc', 'Preferred by Fortune 500 companies, professional sports teams, and thousands of businesses nationwide.')); ?></p>
        </div>
        <?php echo renderTrustSignals(); ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-top:30px;">
            <div class="glass-card reveal"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('about_why_card1_title', 'Factory-Direct Pricing')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('about_why_card1_text', 'No middlemen. Get premium quality at the best factory-direct prices for bulk orders across the USA.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.1s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('about_why_card2_title', 'Fast USA Shipping')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('about_why_card2_text', 'Ships within 15-20 business days. 98% on-time delivery record across all 50 states.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.2s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('about_why_card3_title', '100% Satisfaction')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('about_why_card3_text', 'Every garment passes a 12-point quality inspection. We stand behind every product we ship.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.3s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('about_why_card4_title', 'Custom Everything')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('about_why_card4_text', 'Full customization: colors, logos, designs, fabrics, sizes. No minimum order required.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.4s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('about_why_card5_title', '150K Sq Ft Facility')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('about_why_card5_text', 'State-of-the-art manufacturing with capacity of 50,000+ units per day.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.5s;"><div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div><h3 class="glass-card-title"><?php echo e(getSetting('about_why_card6_title', 'Top Rated Service')); ?></h3><p class="glass-card-text"><?php echo e(getSetting('about_why_card6_text', '4.9/5 average rating from 2,500+ satisfied USA clients.')); ?></p></div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Facility -->
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
            <div class="reveal" style="transition-delay:0.1s;">
                <img src="<?php echo e(getSetting('about_facility_image', '/uploads/pages/about-factory.jpg')); ?>" alt="Our Manufacturing Facility" style="width:100%;border-radius:var(--radius-lg);border:1px solid var(--color-border);">
            </div>
            <div class="reveal">
                <span class="section-label"><?php echo e(getSetting('about_facility_label', 'Our Facility')); ?></span>
                <h2 style="font-family:var(--font-display);font-size:20px;text-transform:uppercase;margin-bottom:16px;margin-top:8px;"><?php echo e(getSetting('about_facility_title', 'State-of-the-Art Manufacturing Facility')); ?></h2>
                <ul style="list-style:none;padding:0;display:grid;gap:12px;">
                    <li style="display:flex;gap:12px;align-items:flex-start;"><span style="color:var(--color-accent);margin-top:2px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><span style="color:var(--color-text-muted);"><?php echo e(getSetting('about_facility_feature1', '150,000 sq ft manufacturing facility in Los Angeles, CA')); ?></span></li>
                    <li style="display:flex;gap:12px;align-items:flex-start;"><span style="color:var(--color-accent);margin-top:2px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><span style="color:var(--color-text-muted);"><?php echo e(getSetting('about_facility_feature2', '50,000+ units production capacity per day')); ?></span></li>
                    <li style="display:flex;gap:12px;align-items:flex-start;"><span style="color:var(--color-accent);margin-top:2px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><span style="color:var(--color-text-muted);"><?php echo e(getSetting('about_facility_feature3', 'In-house sublimation, screen printing, embroidery, cut & sew')); ?></span></li>
                    <li style="display:flex;gap:12px;align-items:flex-start;"><span style="color:var(--color-accent);margin-top:2px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><span style="color:var(--color-text-muted);"><?php echo e(getSetting('about_facility_feature4', '12-point quality inspection on every garment')); ?></span></li>
                    <li style="display:flex;gap:12px;align-items:flex-start;"><span style="color:var(--color-accent);margin-top:2px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><span style="color:var(--color-text-muted);"><?php echo e(getSetting('about_facility_feature5', 'Shipping to all 50 states within 15-20 business days')); ?></span></li>
                    <li style="display:flex;gap:12px;align-items:flex-start;"><span style="color:var(--color-accent);margin-top:2px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span><span style="color:var(--color-text-muted);"><?php echo e(getSetting('about_facility_feature6', '500+ skilled workers and experienced designers')); ?></span></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('about_cta_label', 'Work With Us')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('about_cta_title', 'Ready to Start Your Custom Apparel Project?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('about_cta_desc', 'Request a free quote today. Factory-direct pricing, fast delivery across all 50 states.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('about_cta_btn1_text', 'Request a Quote')); ?></button>
                <a href="/contact" class="btn btn-outline btn-lg"><?php echo e(getSetting('about_cta_btn2_text', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
