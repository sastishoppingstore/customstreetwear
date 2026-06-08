<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$sitePhone = getSetting('site_phone', '+1 (555) 123-4567');

$metaTags = generateAdvancedMetaTags([
    'meta_title' => getSetting('howwedo_meta_title', 'How We Do - Custom Apparel Manufacturing Process USA | Custom Streetwear'),
    'meta_description' => getSetting('howwedo_meta_desc', 'Learn how Custom Streetwear manufactures premium custom apparel. From design and sampling to production and quality control - our proven 5-step process ensures excellence.'),
    'focus_keyword' => 'Custom Apparel Manufacturing Process USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-40%;left:-5%;width:450px;height:450px;background:radial-gradient(circle,rgba(57,255,20,0.04),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'How We Do']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('howwedo_page_label', 'Our Process')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('howwedo_page_title', 'How We Manufacture Custom Apparel')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:720px;line-height:1.6;"><?php echo e(getSetting('howwedo_page_desc', 'From your initial idea to the final product delivered to your door. Our proven 5-step manufacturing process ensures consistent quality, on-time delivery, and complete satisfaction for every custom apparel order.')); ?></p>
        </div>
    </div>
</section>

<?php if (getSetting('howwedo_trust_bar_enabled', '1') === '1'): ?>
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

<!-- Process Steps -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('howwedo_steps_label', '5-Step Process')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('howwedo_steps_title', 'Our Proven Manufacturing Process')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('howwedo_steps_desc', 'A streamlined process designed for quality, efficiency, and complete customer satisfaction.')); ?></p>
        </div>

        <!-- Step 1 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-bottom:80px;">
            <div class="reveal">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    <span style="width:48px;height:48px;border-radius:50%;background:var(--color-accent);color:#0a0a0a;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;font-family:var(--font-display);">1</span>
                    <span class="section-label" style="margin:0;"><?php echo e(getSetting('howwedo_step1_label', 'Step 1')); ?></span>
                </div>
                <h3 style="font-family:var(--font-display);font-size:22px;text-transform:uppercase;margin-bottom:12px;"><?php echo e(getSetting('howwedo_step1_title', 'Consultation & Design')); ?></h3>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('howwedo_step1_text', 'We start by understanding your vision. Share your ideas, sketches, logos, or reference images. Our experienced design team will create detailed tech packs and digital mockups for your approval. We discuss fabric selection, colors, sizing, quantities, and budget to ensure alignment from day one.')); ?></p>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step1_feat1', 'Free design consultation and mockups')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step1_feat2', 'Fabric selection with physical swatches')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step1_feat3', 'Transparent pricing with no hidden fees')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step1_feat4', 'Timeline planning and milestone scheduling')); ?></li>
                </ul>
            </div>
            <div class="reveal" style="transition-delay:0.2s;">
                <div style="width:100%;aspect-ratio:4/3;border-radius:var(--radius-lg);border:1px solid var(--color-border);background:var(--color-bg-card);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:14px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-bottom:80px;">
            <div class="reveal" style="order:2;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    <span style="width:48px;height:48px;border-radius:50%;background:var(--color-accent);color:#0a0a0a;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;font-family:var(--font-display);">2</span>
                    <span class="section-label" style="margin:0;"><?php echo e(getSetting('howwedo_step2_label', 'Step 2')); ?></span>
                </div>
                <h3 style="font-family:var(--font-display);font-size:22px;text-transform:uppercase;margin-bottom:12px;"><?php echo e(getSetting('howwedo_step2_title', 'Sampling & Approval')); ?></h3>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('howwedo_step2_text', 'Once the design is finalized, we create a physical sample garment. This is your opportunity to check fit, fabric feel, print quality, and overall craftsmanship. We allow revisions until you are completely satisfied before moving to full production.')); ?></p>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step2_feat1', 'Physical sample within 5-7 business days')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step2_feat2', 'Free revisions until satisfaction')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step2_feat3', 'Digital proof and physical sample review')); ?></li>
                </ul>
            </div>
            <div class="reveal" style="transition-delay:0.2s;order:1;">
                <div style="width:100%;aspect-ratio:4/3;border-radius:var(--radius-lg);border:1px solid var(--color-border);background:var(--color-bg-card);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:14px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-bottom:80px;">
            <div class="reveal">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    <span style="width:48px;height:48px;border-radius:50%;background:var(--color-accent);color:#0a0a0a;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;font-family:var(--font-display);">3</span>
                    <span class="section-label" style="margin:0;"><?php echo e(getSetting('howwedo_step3_label', 'Step 3')); ?></span>
                </div>
                <h3 style="font-family:var(--font-display);font-size:22px;text-transform:uppercase;margin-bottom:12px;"><?php echo e(getSetting('howwedo_step3_title', 'Production & Manufacturing')); ?></h3>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('howwedo_step3_text', 'With your approval, we move into full production. Our 150,000 sq ft facility houses cutting-edge machinery for sublimation, screen printing, cut & sew, embroidery, and more. Each garment is crafted by skilled professionals using premium materials sourced from trusted USA suppliers.')); ?></p>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step3_feat1', '50,000+ units daily production capacity')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step3_feat2', 'In-house manufacturing - no outsourcing')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step3_feat3', 'Premium materials from USA suppliers')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step3_feat4', 'Real-time production tracking')); ?></li>
                </ul>
            </div>
            <div class="reveal" style="transition-delay:0.2s;">
                <div style="width:100%;aspect-ratio:4/3;border-radius:var(--radius-lg);border:1px solid var(--color-border);background:var(--color-bg-card);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:14px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-bottom:80px;">
            <div class="reveal" style="order:2;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    <span style="width:48px;height:48px;border-radius:50%;background:var(--color-accent);color:#0a0a0a;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;font-family:var(--font-display);">4</span>
                    <span class="section-label" style="margin:0;"><?php echo e(getSetting('howwedo_step4_label', 'Step 4')); ?></span>
                </div>
                <h3 style="font-family:var(--font-display);font-size:22px;text-transform:uppercase;margin-bottom:12px;"><?php echo e(getSetting('howwedo_step4_title', 'Quality Control')); ?></h3>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('howwedo_step4_text', 'Every single garment undergoes a rigorous 12-point quality inspection before it leaves our facility. Our QC team checks stitching, print quality, color accuracy, sizing, fabric defects, and packaging. Only garments that pass all checks are shipped to our clients.')); ?></p>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step4_feat1', '12-point inspection on every garment')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step4_feat2', 'Color accuracy verification under multiple lighting')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step4_feat3', 'Size and fit consistency checks')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step4_feat4', 'Industry-leading < 1% defect rate')); ?></li>
                </ul>
            </div>
            <div class="reveal" style="transition-delay:0.2s;order:1;">
                <div style="width:100%;aspect-ratio:4/3;border-radius:var(--radius-lg);border:1px solid var(--color-border);background:var(--color-bg-card);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:14px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-bottom:40px;">
            <div class="reveal">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    <span style="width:48px;height:48px;border-radius:50%;background:var(--color-accent);color:#0a0a0a;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;font-family:var(--font-display);">5</span>
                    <span class="section-label" style="margin:0;"><?php echo e(getSetting('howwedo_step5_label', 'Step 5')); ?></span>
                </div>
                <h3 style="font-family:var(--font-display);font-size:22px;text-transform:uppercase;margin-bottom:12px;"><?php echo e(getSetting('howwedo_step5_title', 'Packaging & Shipping')); ?></h3>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:16px;"><?php echo e(getSetting('howwedo_step5_text', 'Approved garments are carefully folded, packed, and labeled according to your specifications. We ship to all 50 states via trusted carriers with tracking provided. Our 98% on-time delivery record means you can count on us to meet your deadlines.')); ?></p>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step5_feat1', 'Ships within 15-20 business days')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step5_feat2', '98% on-time delivery record')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step5_feat3', 'Tracking provided for all orders')); ?></li>
                    <li style="display:flex;gap:10px;font-size:14px;color:var(--color-text-muted);"><span style="color:var(--color-accent);">&#10003;</span> <?php echo e(getSetting('howwedo_step5_feat4', 'Custom packaging options available')); ?></li>
                </ul>
            </div>
            <div class="reveal" style="transition-delay:0.2s;">
                <div style="width:100%;aspect-ratio:4/3;border-radius:var(--radius-lg);border:1px solid var(--color-border);background:var(--color-bg-card);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:14px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Signals -->
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('howwedo_trust_label', 'Why Choose Our Process')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('howwedo_trust_title', 'Trusted Manufacturing Process')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('howwedo_trust_desc', 'Our streamlined process has delivered premium custom apparel to over 2,500 satisfied USA clients.')); ?></p>
        </div>
        <?php echo renderTrustSignals(); ?>
    </div>
</section>

<!-- Urgency + CTA -->
<section class="section">
    <div class="container">
        <div style="padding:40px;background:linear-gradient(135deg,rgba(57,255,20,0.05),rgba(57,255,20,0.02));border:1px solid rgba(57,255,20,0.15);border-radius:var(--radius-lg);text-align:center;">
            <div class="urgency-bar" style="display:inline-flex;margin-bottom:20px;">
                <span class="urgency-dot"></span>
                <span><?php echo e(getSetting('howwedo_urgent_text', 'Limited Production Slots Available | Submit Your Order Today')); ?></span>
            </div>
            <h2 style="font-family:var(--font-display);font-size:clamp(24px,3vw,36px);text-transform:uppercase;margin-bottom:12px;"><?php echo e(getSetting('howwedo_urgent_title', 'Ready to Start Your Order?')); ?></h2>
            <p style="color:var(--color-text-muted);max-width:600px;margin:0 auto 24px;"><?php echo e(getSetting('howwedo_urgent_desc', 'Get a free quote within 24 hours. Our team will guide you through every step of the process.')); ?></p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('howwedo_urgent_btn1', 'Request Free Quote')); ?></button>
                <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $sitePhone)); ?>" class="btn btn-outline btn-lg"><?php echo e(getSetting('howwedo_urgent_btn2', 'Call Us')); ?>: <?php echo e($sitePhone); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
