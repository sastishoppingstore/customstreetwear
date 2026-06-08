<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$sitePhone = getSetting('site_phone', '+1 (555) 123-4567');
$categories = getCategories();

$metaTags = generateAdvancedMetaTags([
    'meta_title' => getSetting('whatwedo_meta_title', 'What We Do - Custom Apparel Manufacturing Services USA | Custom Streetwear'),
    'meta_description' => getSetting('whatwedo_meta_desc', 'Custom Streetwear provides premium custom apparel manufacturing services across the USA. Sublimation, screen printing, cut & sew, embroidery, and leather craftsmanship.'),
    'focus_keyword' => 'Custom Apparel Manufacturing Services USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30%;right:-10%;width:400px;height:400px;background:radial-gradient(circle,rgba(57,255,20,0.04),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'What We Do']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('whatwedo_page_label', 'Our Services')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('whatwedo_page_title', 'Custom Apparel Manufacturing Services in USA')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:720px;line-height:1.6;"><?php echo e(getSetting('whatwedo_page_desc', 'From concept to delivery, we offer end-to-end custom apparel manufacturing services. Whether you need sportswear, streetwear, workwear, or uniforms, our team delivers premium quality at factory-direct prices.')); ?></p>
        </div>
    </div>
</section>

<?php if (getSetting('whatwedo_trust_bar_enabled', '1') === '1'): ?>
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

<!-- Service Categories -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('whatwedo_categories_label', 'Our Capabilities')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('whatwedo_categories_title', 'Custom Apparel Manufacturing Capabilities')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('whatwedo_categories_desc', 'We offer a comprehensive range of custom apparel manufacturing services under one roof.')); ?></p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;">
            <div class="glass-card reveal">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></div>
                <h3 class="glass-card-title"><?php echo e(getSetting('whatwedo_cap1_title', 'Sublimation Printing')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('whatwedo_cap1_text', 'Vibrant, all-over prints that won\'t fade, crack, or peel. Perfect for sportswear and activewear.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.1s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div>
                <h3 class="glass-card-title"><?php echo e(getSetting('whatwedo_cap2_title', 'Cut & Sew Manufacturing')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('whatwedo_cap2_text', 'Full cut-and-sew manufacturing for custom garments with unique patterns, fits, and designs.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.2s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
                <h3 class="glass-card-title"><?php echo e(getSetting('whatwedo_cap3_title', 'Screen Printing')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('whatwedo_cap3_text', 'High-quality screen printing for bulk orders. Durable, long-lasting prints on all garment types.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.3s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div>
                <h3 class="glass-card-title"><?php echo e(getSetting('whatwedo_cap4_title', 'Custom Embroidery')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('whatwedo_cap4_text', 'Professional digitized embroidery for logos, text, and designs on caps, shirts, jackets, and uniforms.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.4s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg></div>
                <h3 class="glass-card-title"><?php echo e(getSetting('whatwedo_cap5_title', 'Leather Garments')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('whatwedo_cap5_text', 'Premium custom leather jackets, vests, and accessories with expert craftsmanship.')); ?></p>
            </div>
            <div class="glass-card reveal" style="transition-delay:0.5s;">
                <div class="glass-card-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
                <h3 class="glass-card-title"><?php echo e(getSetting('whatwedo_cap6_title', 'DTF & Heat Transfer')); ?></h3>
                <p class="glass-card-text"><?php echo e(getSetting('whatwedo_cap6_text', 'Direct-to-film and heat transfer for small batches and detailed designs. No minimum order.')); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Products We Make -->
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('whatwedo_products_label', 'Products We Make')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('whatwedo_products_title', 'Custom Apparel We Manufacture')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('whatwedo_products_desc', 'From sportswear to streetwear, workwear to uniforms - we manufacture it all.')); ?></p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            <?php
            $prodList = [
                ['t' => getSetting('whatwedo_prod1_title', 'T-Shirts & Tops'), 'd' => getSetting('whatwedo_prod1_text', 'Custom t-shirts, tank tops, polo shirts, and raglan sleeves.')],
                ['t' => getSetting('whatwedo_prod2_title', 'Hoodies & Sweatshirts'), 'd' => getSetting('whatwedo_prod2_text', 'Premium hoodies, zip-ups, crewnecks, and pullovers.')],
                ['t' => getSetting('whatwedo_prod3_title', 'Joggers & Shorts'), 'd' => getSetting('whatwedo_prod3_text', 'Custom joggers, sweatpants, shorts, and track pants.')],
                ['t' => getSetting('whatwedo_prod4_title', 'Jackets & Outerwear'), 'd' => getSetting('whatwedo_prod4_text', 'Varsity jackets, bomber jackets, windbreakers, and vests.')],
                ['t' => getSetting('whatwedo_prod5_title', 'Sports Uniforms'), 'd' => getSetting('whatwedo_prod5_text', 'Full sublimation sports uniforms for all sports.')],
                ['t' => getSetting('whatwedo_prod6_title', 'Workwear & Uniforms'), 'd' => getSetting('whatwedo_prod6_text', 'Custom work shirts, pants, coveralls, and corporate uniforms.')],
                ['t' => getSetting('whatwedo_prod7_title', 'Caps & Headwear'), 'd' => getSetting('whatwedo_prod7_text', 'Custom snapbacks, dad hats, beanies, and visors.')],
                ['t' => getSetting('whatwedo_prod8_title', 'Leather Jackets'), 'd' => getSetting('whatwedo_prod8_text', 'Premium custom leather jackets and accessories.')],
            ];
            foreach ($prodList as $idx => $p):
            ?>
            <div class="glass-card reveal" style="padding:24px;text-align:center;transition-delay:<?php echo $idx * 0.08; ?>s;">
                <h4 style="font-size:14px;font-weight:600;margin:0 0 6px 0;"><?php echo e($p['t']); ?></h4>
                <p style="font-size:12px;color:var(--color-text-muted);margin:0;"><?php echo e($p['d']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Trust Signals -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('whatwedo_trust_label', 'Why Work With Us')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('whatwedo_trust_title', 'What Sets Us Apart')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('whatwedo_trust_desc', 'We deliver exceptional quality and service that keeps clients coming back.')); ?></p>
        </div>
        <?php echo renderTrustSignals(); ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-top:30px;">
            <div class="glass-card reveal"><h3 style="font-size:16px;margin:0 0 8px 0;"><?php echo e(getSetting('whatwedo_feat1_title', 'No Minimum Order')); ?></h3><p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php echo e(getSetting('whatwedo_feat1_text', 'Whether you need 50 or 50,000 units, we accommodate orders of all sizes.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.1s;"><h3 style="font-size:16px;margin:0 0 8px 0;"><?php echo e(getSetting('whatwedo_feat2_title', 'Free Design Support')); ?></h3><p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php echo e(getSetting('whatwedo_feat2_text', 'Our in-house design team helps bring your vision to life at no extra cost.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.2s;"><h3 style="font-size:16px;margin:0 0 8px 0;"><?php echo e(getSetting('whatwedo_feat3_title', 'Sample Before Production')); ?></h3><p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php echo e(getSetting('whatwedo_feat3_text', 'Get a physical sample before full production. Approve quality before we proceed.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.3s;"><h3 style="font-size:16px;margin:0 0 8px 0;"><?php echo e(getSetting('whatwedo_feat4_title', 'USA-Based Support')); ?></h3><p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php echo e(getSetting('whatwedo_feat4_text', 'Our USA-based team ensures smooth communication and support throughout your project.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.4s;"><h3 style="font-size:16px;margin:0 0 8px 0;"><?php echo e(getSetting('whatwedo_feat5_title', 'Fast Turnaround')); ?></h3><p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php echo e(getSetting('whatwedo_feat5_text', 'Ships within 15-20 business days. Rush orders available for urgent projects.')); ?></p></div>
            <div class="glass-card reveal" style="transition-delay:0.5s;"><h3 style="font-size:16px;margin:0 0 8px 0;"><?php echo e(getSetting('whatwedo_feat6_title', 'Quality Guarantee')); ?></h3><p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php echo e(getSetting('whatwedo_feat6_text', 'Every garment passes 12-point quality inspection. 100% satisfaction guaranteed.')); ?></p></div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('whatwedo_cta_label', 'Start Your Project')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('whatwedo_cta_title', 'Ready to Start Your Custom Apparel Manufacturing Project?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('whatwedo_cta_desc', 'Get a free, no-obligation quote within 24 hours. Factory-direct pricing for USA brands.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('whatwedo_cta_btn1_text', 'Request a Quote')); ?></button>
                <a href="/contact" class="btn btn-outline btn-lg"><?php echo e(getSetting('whatwedo_cta_btn2_text', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
